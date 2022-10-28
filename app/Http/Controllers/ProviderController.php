<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserRequests;
use App\RequestFilter;
use App\Provider;
use Carbon\Carbon;
use App\Http\Controllers\ProviderResources\TripController;
use App\AdminHelps;
use App\AdminTerms;
use App\AdminFaq;
use App\ProviderService;
use Illuminate\Support\Facades\DB;
class ProviderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('provider');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // echo "Dashboard page";
        // exit()
         try{

            $rides = UserRequests::has('user')->orderBy('id','desc')->get();
            $cancel_rides = UserRequests::where('status','CANCELLED')->where('provider_id',\Auth::guard('provider')->user()->id);
            $scheduled_rides = UserRequests::where('status','SCHEDULED')->where('provider_id',\Auth::guard('provider')->user()->id)->count();
           
            $completed_rides = UserRequests::where('status','COMPLETED')->where('provider_id',\Auth::guard('provider')->user()->id)->count();
        
            //$accepted_rides = UserRequests::where('status','ACCEPTED')->where('provider_id',\Auth::guard('provider')->user()->id)->count();
            $accepted_rides = $scheduled_rides + $completed_rides;
            $cancel_rides = $cancel_rides->count();
            $rides = $rides->where('provider_id',\Auth::guard('provider')->user()->id);
            //$date = date('Y-m-d H:i:s');
            //echo $carbon = Carbon::today();

            $from_date1 = date("Y-m-d")." 00:00:00";
            $to_date1 = date("Y-m-d")." 23:59:59";
           
            //$today_rides = $rides->where('provider_id',\Auth::guard('provider')->user()->id)->where('created_at', '>=', $from_date1)->andwhere('created_at', '<', $to_date1);
        
            $today_rides = $rides->where('provider_id',\Auth::guard('provider')->user()->id)->where('created_at', '>', Carbon::today()->startOfDay())->where('created_at', '<', Carbon::today()->endOfDay());

            //whereBetween('created_at', [$from_date1, $to_date1]);
           
            $fully = UserRequests::where('provider_id',\Auth::guard('provider')->user()->id)
                    ->with('payment','service_type')
                    ->get();
                    
            $query = "SELECT SUM(user_request_payments.fixed + user_request_payments.distance + user_request_payments.tax ) as revenue FROM `user_requests` LEFT JOIN user_request_payments on user_requests.id=user_request_payments.request_id where provider_id=".\Auth::guard('provider')->user()->id;
            $rev = collect(DB::select($query))->first();
           
            return view('provider.index',compact('scheduled_rides','cancel_rides','rides','accepted_rides','today_rides','completed_rides','fully','rev'));
        }
        catch(Exception $e){
            return redirect()->route('provider.index')->with('flash_error','Something Went Wrong with Dashboard!');
        }

    }
/**
     * Show the application dashboard online offline.
     *
     * @return \Illuminate\Http\Response
     */

    // public function updateStatus(Request $request)
    // {
    //   return ProviderService::where('status',$request->status)->update('provider_id',Auth::guard('provider')->user()->id);
        
    // }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function incoming(Request $request)
    {
       
        return (new TripController())->index($request);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function accept(Request $request, $id)
    {
        return (new TripController())->accept($request, $id);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function reject($id)
    {
        return (new TripController())->destroy($id);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        return (new TripController())->update($request, $id);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function rating(Request $request, $id)
    {
        return (new TripController())->rate($request, $id);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function earnings()
    {
        $provider = Provider::where('id',\Auth::guard('provider')->user()->id)
                    ->with('service','accepted','cancelled')
                    ->get();

        $weekly = UserRequests::where('provider_id',\Auth::guard('provider')->user()->id)
                    ->with('payment')
                    ->where('created_at', '>=', Carbon::now()->subWeekdays(7))
                    ->get();

        $today = UserRequests::where('provider_id',\Auth::guard('provider')->user()->id)
                    ->where('created_at', '>=', Carbon::today())
                    ->count();

        $fully = UserRequests::where('provider_id',\Auth::guard('provider')->user()->id)
                    ->with('payment','service_type')
                    ->get();

        return view('provider.payment.earnings',compact('provider','weekly','fully','today'));
    }

    public function weeklyearning()
    {
        $provider = Provider::where('id',\Auth::guard('provider')->user()->id)
                    ->with('service','accepted','cancelled')
                    ->get();

        $weekly = UserRequests::where('provider_id',\Auth::guard('provider')->user()->id)
                    ->with('payment')
                    ->where('created_at', '>=', Carbon::now()->subWeekdays(7))
                    ->get();

        $today = UserRequests::where('provider_id',\Auth::guard('provider')->user()->id)
                    ->where('created_at', '>=', Carbon::today())
                    ->count();

        $fully = UserRequests::where('provider_id',\Auth::guard('provider')->user()->id)
                    ->with('payment','service_type')
                    ->get();

        return view('provider.payment.weeklyearning',compact('provider','weekly','fully','today'));
    }

    public function yearlyearning()
    {
        $provider = Provider::where('id',\Auth::guard('provider')->user()->id)
                    ->with('service','accepted','cancelled')
                    ->get();

        $weekly = UserRequests::where('provider_id',\Auth::guard('provider')->user()->id)
                    ->with('payment')
                    ->where('created_at', '>=', Carbon::now()->subWeekdays(7))
                    ->get();

        $today = UserRequests::where('provider_id',\Auth::guard('provider')->user()->id)
                    ->where('created_at', '>=', Carbon::today())
                    ->count();

        $fully = UserRequests::where('provider_id',\Auth::guard('provider')->user()->id)
                    ->with('payment','service_type')
                    ->get();

        return view('provider.payment.yearlyearning',compact('provider','weekly','fully','today'));
    }

    public function dailyearning()
    {
        $provider = Provider::where('id',\Auth::guard('provider')->user()->id)
                    ->with('service','accepted','cancelled')
                    ->get();

        $weekly = UserRequests::where('provider_id',\Auth::guard('provider')->user()->id)
                    ->with('payment')
                    ->where('created_at', '>=', Carbon::now()->subWeekdays(7))
                    ->get();

        $today = UserRequests::where('provider_id',\Auth::guard('provider')->user()->id)
                    ->where('created_at', '>=', Carbon::today())
                    ->count();

        $fully = UserRequests::where('provider_id',\Auth::guard('provider')->user()->id)
                    ->with('payment','service_type')
                    ->orderBy('id', 'desc')->take(10)
                    ->get();
        
        return view('provider.payment.dailyearning',compact('provider','weekly','fully','today'));
    }

    public function dailyearning_detail(Request $request)
    {
        $provider = Provider::where('id',\Auth::guard('provider')->user()->id)
                    ->with('service','accepted','cancelled')
                    ->get();

        $fully = (new ProviderResources\TripController)->dailyearning_detail($request);            

        return view('provider.payment.dailyearning_detail',compact('provider','fully'));
    }

    /**
     * available.
     *
     * @return \Illuminate\Http\Response
     */
    public function available(Request $request)
    {
        (new ProviderResources\ProfileController)->available($request);
        return back();
    }

    /**
     * Show the application change password.
     *
     * @return \Illuminate\Http\Response
     */
    public function change_password()
    {
        return view('provider.profile.change_password');
    }

    /**
     * Change Password.
     *
     * @return \Illuminate\Http\Response
     */
    public function update_password(Request $request)
    {
        $this->validate($request, [
                'password' => 'required|confirmed',
                'old_password' => 'required',
            ]);

        $Provider = \Auth::user();

        if(password_verify($request->old_password, $Provider->password))
        {
            $Provider->password = bcrypt($request->password);
            $Provider->save();

            return back()->with('flash_success','Password changed successfully!');
        } else {
            return back()->with('flash_error','Please enter correct password');
        }
    }

    public function edit_profile()
    {
        $fully = UserRequests::where('provider_id',\Auth::guard('provider')->user()->id)
                    ->with('payment','service_type')
                    ->get();

        return view('provider.profile.index',compact('fully'));
    }


    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function location_edit()
    {
         $fully = UserRequests::where('provider_id',\Auth::guard('provider')->user()->id)
                    ->with('payment','service_type')
                    ->get();

        return view('provider.location.index',compact('fully'));
    }

    /**
     * Update latitude and longitude of the user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function location_update(Request $request)
    {
        $this->validate($request, [
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
            ]);

        if($Provider = \Auth::user()){

            $Provider->latitude = $request->latitude;
            $Provider->longitude = $request->longitude;
            $Provider->save();

            return back()->with(['flash_success' => 'Location Updated successfully!']);

        } else {
            return back()->with(['flash_error' => 'Provider Not Found!']);
        }
    }

    /**
     * upcoming history.
     *
     * @return \Illuminate\Http\Response
     */
    public function upcoming_trips()
    {
        $fully = (new ProviderResources\TripController)->upcoming_trips();

        return view('provider.payment.upcoming',compact('fully'));
    }

    public function upcoming_details(Request $request)
    {
        $fully = (new ProviderResources\TripController)->upcoming_details($request);
        return view('provider.payment.upcoming_detail',compact('fully'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */


    public function cancel(Request $request) {

        try{
            (new TripController)->cancel($request);

            $rides = UserRequests::has('user')->orderBy('id','desc')->get();
            $cancel_rides = UserRequests::where('status','CANCELLED')->where('provider_id',\Auth::guard('provider')->user()->id);
            $scheduled_rides = UserRequests::where('status','SCHEDULED')->where('provider_id',\Auth::guard('provider')->user()->id)->count();
           
            $completed_rides = UserRequests::where('status','COMPLETED')->where('provider_id',\Auth::guard('provider')->user()->id)->count();
        
            $accepted_rides = $scheduled_rides + $completed_rides;
            $cancel_rides = $cancel_rides->count();
            $rides = $rides->where('provider_id',\Auth::guard('provider')->user()->id);
          
            $today_rides = $rides->where('provider_id',\Auth::guard('provider')->user()->id)->where('created_at', '>', Carbon::today()->startOfDay())->where('created_at', '<', Carbon::today()->endOfDay());

            $fully = (new ProviderResources\TripController)->upcoming_details($request);
           
            return view('provider.index',compact('scheduled_rides','cancel_rides','rides','accepted_rides','today_rides','completed_rides','fully'));
        } catch (ModelNotFoundException $e) {
            return back()->with(['flash_error' => "Something Went Wrong"]);
        }
    }

    public function helpsget()
    {
        try{
            $AdminHelps = AdminHelps::HelpsList()->get();
            return $AdminHelps;
        }

        catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')]);
        } 
    }

    public function helps()
    {
        $helps = $this->helpsget();
        $fully = UserRequests::where('provider_id',\Auth::guard('provider')->user()->id)
                    ->with('payment','service_type')
                    ->get();
        return view('provider.help',compact('helps','fully'));
    }

    public function termsget()
    {
        try{
            $AdminTerms = AdminTerms::TermsList()->get();
            return $AdminTerms;
        }

        catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')]);
        } 
    }

    public function terms()
    {
        $terms = $this->termsget();
        $fully = UserRequests::where('provider_id',\Auth::guard('provider')->user()->id)
                    ->with('payment','service_type')
                    ->get();
        return view('provider.termsandcondition',compact('terms','fully'));
    }

    public function faqsget()
    {
       try{
            $AdminFaq = AdminFaq::FaqList()->get();
            return $AdminFaq;
        }

        catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')]);
        } 
    }

    public function faqs()
    {
        $faqs = $this->faqsget();
        $fully = UserRequests::where('provider_id',\Auth::guard('provider')->user()->id)
                    ->with('payment','service_type')
                    ->get();
        return view('provider.faq',compact('faqs','fully'));
    }
}
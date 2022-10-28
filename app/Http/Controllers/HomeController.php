<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserRequests;
use App\User;
use Auth;
use App\AdminHelps;
use App\AdminTerms;
use App\AdminFaq;
use App\Card;
use Session;

class HomeController extends Controller
{
    protected $UserAPI;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserApiController $UserAPI)
    {
        $this->middleware('auth');
        $this->UserAPI = $UserAPI;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Response = $this->UserAPI->request_status_check1()->getData();

        if(empty($Response->data))
        {

            $trips = $this->UserAPI->alltrips();
            $rides = UserRequests::has('user')->where('user_id',Auth::user()->id)->orderBy('id','desc')->get();
            $all_ride = $rides->where('user_id',Auth::user()->id)->count();
            $rides = $rides->where('user_id',Auth::user()->id);
            $cancel_rides = UserRequests::where('status','CANCELLED')->where('user_id',Auth::user()->id);
            $scheduled_rides = UserRequests::where('status','SCHEDULED')->where('user_id',Auth::user()->id)->count();
            
            $cancel_rides = $cancel_rides->count();
            
            return view('user.dashboard',compact('trips','scheduled_rides','cancel_rides','rides','all_ride'));
        }else{
           

            return view('user.ride.waiting')->with('request',$Response->data[0]);
        }
    }

    public function mytrips()
    {
        $Response = $this->UserAPI->request_status_check1()->getData();

        if(empty($Response->data))
        {
            $trips = $this->UserAPI->alltrips();

            return view('user.ride.mytrips',compact('trips'));

        }else{

            return view('user.ride.waiting')->with('request',$Response->data[0]);
        }
    }

    public function mytrips_details(Request $request)
    {
        $Response = $this->UserAPI->request_status_check1()->getData();

        if(empty($Response->data))
        {
            $trips = $this->UserAPI->alltrip_details($request);

            return view('user.ride.mytrips_detail',compact('trips'));
        }else{
            return view('user.ride.waiting')->with('request',$Response->data[0]);
        }
    }

   
    /**
     * Show the application profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        return view('user.account.profile');
    }

    /**
     * Show the application profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit_profile()
    {
        return view('user.account.edit_profile');
    }

    /**
     * Update profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function update_profile(Request $request)
    {
        return $this->UserAPI->update_profile($request);
    }

    /**
     * Show the application change password.
     *
     * @return \Illuminate\Http\Response
     */
    public function change_password()
    {
        return view('user.account.change_password');
    }

    /**
     * Change Password.
     *
     * @return \Illuminate\Http\Response
     */
    public function update_password(Request $request)
    {
        return $this->UserAPI->change_password($request);
    }

    /**
     * Trips.
     *
     * @return \Illuminate\Http\Response
     */
    public function trips(Request $request)
    {
        $url = str_replace(url('/'), '', url()->previous());
        $array = explode('?', $url);
        
        if($array[0]!='/PassengerSignin')
        {
            Session::forget('s_address');
            Session::forget('d_address');
        }
        
        $services = $this->UserAPI->services();
        $trips = $this->UserAPI->trips();

        $ip 	=   \Request::getClientIp(true);
        $ip_details = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip={$ip}"));
        //dd($ip_details);

		
        return view('user.ride.trips',compact('trips','services', 'ip_details' ));
    }

     /**
     * Payment.
     *
     * @return \Illuminate\Http\Response
     */
    public function payment()
    {
        $cards = Card::where('user_id',Auth::user()->id)->orderBy('created_at','desc')->get();
        $users = User::where('paypal_id','!=','')->orderBy('created_at','desc')->get();
       
        return view('user.account.payment',compact('cards','users')); 
    }

    /**
     * Wallet.
     *
     * @return \Illuminate\Http\Response
     */
    public function wallet(Request $request)
    {
        $cards = Card::where('user_id',Auth::user()->id)->orderBy('created_at','desc')->get();
        return view('user.account.wallet',compact('cards'));
    }

    /**
     * Promotion.
     *
     * @return \Illuminate\Http\Response
     */
    public function promotions_index(Request $request)
    {
        $promocodes = $this->UserAPI->promocodes();
		
		//dd( $promocodes );
		
		
        return view('user.account.promotions', compact('promocodes'));
    }

    /**
     * Add promocode.
     *
     * @return \Illuminate\Http\Response
     */
    public function promotions_store(Request $request)
    {
        return $this->UserAPI->add_promocode($request);
    }

    /**
     * Upcoming Trips.
     *
     * @return \Illuminate\Http\Response
     */
    public function upcoming_trips()
    {
        $trips = $this->UserAPI->upcoming_trips();
        return view('user.ride.upcoming',compact('trips'));
    }

    public function upcoming_trips_details(Request $request)
    {
        $trips = $this->UserAPI->upcoming_trip_details($request);
        return view('user.ride.upcoming_detail',compact('trips'));
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
        return view('user.help',compact('helps'));
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
        return view('user.termsandcondition',compact('terms'));
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
        return view('user.faq',compact('faqs'));
    }

    public function save_payment_mode(Request $request)
    {

        try{
        
                $hobby = implode(",",array_keys($request->except(['_method','_token'])));
                
    //Exclude the parameters from the $request using except() method
    //now in your $hobby variable, you will have "art,artitecture,business"

    $add_payment_mode = User::where('id',Auth::user()->id)->update(['multiple_payment_method'=>$hobby]);
   /* $add_payment_mode->mulitple_payment_method = $hobby;
    $add_payment_mode->save();*/
        return back()->with('flash_success','Payment mode saved Successfully');
        } catch(Exception $e){
            
            if($request->ajax()){
                return response()->json(['error' => $e->getMessage()], 500);
            }else{
                return back()->with('flash_error',$e->getMessage());
            }
        } 
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;
use Setting;
use Auth;
use Exception;
use Carbon\Carbon;
use App\Helpers\Helper;
use Session;

use App\User;
use App\Zones;
use App\CrmUser;
use App\Provider;
use App\UserRequests;
use App\RequestFilter;
use App\ProviderService;
use App\ServiceType;
use App\CorporateAccount;
use App\ContactUs;
use App\Complaint;
use App\LostItem;
use App\UserRequestPayment;

class CrmController extends Controller
{

    /**
     * Dispatcher Panel.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    protected $UserAPI;
	protected $ip_details = null;

     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserApiController $UserAPI)
    {
        //$this->middleware('auth');
        $this->UserAPI = $UserAPI;
		
	}


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
    public function dashboard()
    {
        
        
        $services  	= 	ServiceType::all();
		$companies  =	DB::table('fleets')->get();
		$totaluser 	= 	User::count();
		$totaldriver = Provider::count();
		$totaltrips = UserRequests::count();
		$totalcomtrips = UserRequests::where('status','COMPLETED')->count();
        $ip_details =	$this->ip_details;
		if(Auth::guard('admin')->user()){
            $data= "";
            return view('admin.crm',compact('data'));
			
        }elseif(Auth::guard('crm')->user()){
            return view('crm.dashboard', compact(['services', 'ip_details', 'companies', 'totaluser', 'totaldriver', 'totaltrips', 'totalcomtrips']));
        }
    }
	
    public function contact(){
        $data = ContactUs::all();
        return view('crm.users.contact', compact('data'));
    }

    public function destroy($id)
    {
        
        try {
            ContactUs::find($id)->delete();
            return back()->with('message', 'User deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'User Not Found');
        }
    }
     /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        return view('crm.account.profile');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function profile_update(Request $request)
    {
        if(Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error', 'Disabled for demo purposes! Please contact us at info@rommoz.com');
        }

        $this->validate($request,[
            'name' => 'required|max:255',
            'mobile' => 'required|digits_between:6,13',
        ]);

        try{
            $account = Auth::guard('crm')->user();
            $account->name = $request->name;
            $account->mobile = $request->mobile;
            $account->save();

            return redirect()->back()->with('flash_success','Profile Updated');
        }

        catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong!');
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function password()
    {
        return view('crm.account.change-password');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function password_update(Request $request)
    {
        if(Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error','Disabled for demo purposes! Please contact us at info@rommoz.com');
        }

        $this->validate($request,[
            'old_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        try {

           $Account = crmUser::find(Auth::guard('crm')->user()->id);

            if(password_verify($request->old_password, $Account->password))
            {
                $Account->password = bcrypt($request->password);
                $Account->save();

                return redirect()->back()->with('flash_success','Password Updated');
            }
        } catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong!');
        }
    }
	public function complaint(){

        $data = Complaint::where('status',1)->get();

        return view('crm.complaint', compact('data'));
    }
    public function complaintDetails($id){
        $data = Complaint::where('id',$id)->first();
        return view('crm.complaint_details', compact('data'));
    }
        public function lost_management(){
        $data = LostItem::get();
        return view('crm.lost_management', compact('data'));
    }
    public function lost_destroy($id){
       try {
            LostItem::find($id)->delete();
            return back()->with('message', 'User deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'User Not Found');
        }
    }
    public function transfer(Request $request, $id){
        
        $data = Complaint::where('id',$id)->first();
        $data->status = $request->status;
        $data->transfer = $request->transfer;
        $data->reply = $request->reply;
        $data->save();
        return redirect()->back()->with('flash_success','Ticket Updated');
    }

    public function openTicket(){      
        
            $data = Complaint::where(['transfer'=>1,'status'=>1])->get();
        
            return view('crm.open_ticket', compact('data'));
    }
    
        
        
    public function closeTicket(){

        $data = Complaint::where(['transfer'=>1,'status'=>0])->get();
        return view('crm.close_ticket', compact('data'));
    }
    public function openTicketDetails($id){

        $data = Complaint::where('id',$id)->first();
        return view('crm.open_ticket_details', compact('data'));
    }


     /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function payment()
    {
        try {
             $payments = UserRequests::where('paid', 1)
                    ->has('user')
                    ->has('provider')
                    ->has('payment')
                    ->orderBy('user_requests.created_at','desc')
                    ->get();
            
            return view('crm.payment.payment-history', compact('payments'));
        } catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong!');
        }
    }

    /**
     * account statements.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function statement($type = 'individual'){

        try{

            $page = 'Ride Statement';

            if($type == 'individual'){
                $page = 'Driver Ride Statement';
            }elseif($type == 'today'){
                $page = 'Today Statement - '. date('d M Y');
            }elseif($type == 'monthly'){
                $page = 'This Month Statement - '. date('F');
            }elseif($type == 'yearly'){
                $page = 'This Year Statement - '. date('Y');
            }

            $rides = UserRequests::with('payment')->orderBy('id','desc');
            $cancel_rides = UserRequests::where('status','CANCELLED');
            $revenue = UserRequestPayment::select(\DB::raw(
                           'SUM(fixed + distance) as overall, SUM(commision) as commission,SUM(tax) as tax,SUM(discount) as discount' 
                       ));
                      

            if($type == 'today'){

                $rides->where('created_at', '>=', Carbon::today());
                $cancel_rides->where('created_at', '>=', Carbon::today());
                $revenue->where('created_at', '>=', Carbon::today());

            }elseif($type == 'monthly'){

                $rides->where('created_at', '>=', Carbon::now()->month);
                $cancel_rides->where('created_at', '>=', Carbon::now()->month);
                $revenue->where('created_at', '>=', Carbon::now()->month);

            }elseif($type == 'yearly'){

                $rides->where('created_at', '>=', Carbon::now()->year);
                $cancel_rides->where('created_at', '>=', Carbon::now()->year);
                $revenue->where('created_at', '>=', Carbon::now()->year);

            }

            $rides = $rides->get();
            $cancel_rides = $cancel_rides->count();
            $revenue = $revenue->get();
 
            return view('crm.providers.statement', compact('rides','cancel_rides','revenue'))
                    ->with('page',$page);

        } catch (Exception $e) {
            return back()->with('flash_error','Something Went Wrong!');
        }
    }

    /**
     * account statements.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function statement_provider(){

        try{

            $Providers = Provider::all();

            foreach($Providers as $index => $Provider){

                $Rides = UserRequests::where('provider_id',$Provider->id)
                            ->where('status','<>','CANCELLED')
                            ->get()->pluck('id');

                $Providers[$index]->rides_count = $Rides->count();

                $Providers[$index]->payment = UserRequestPayment::whereIn('request_id', $Rides)
                                ->select(\DB::raw(
                                   'SUM(ROUND(fixed) + ROUND(distance)) as overall, SUM(ROUND(commision)) as commission' 
                                ))->get();
            }

            return view('crm.providers.provider-statement', compact('Providers'))->with('page','Providers Statement');

        } catch (Exception $e) {
            return back()->with('flash_error','Something Went Wrong!');
        }
    }

/**
     * account statements today.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function statement_today(){
        return $this->statement('today');
    }

    /**
     * account statements monthly.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function statement_monthly(){
        return $this->statement('monthly');
    }

     /**
     * account statements monthly.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function statement_yearly(){
        
        //return 1;
        return $this->statement('yearly');
    }

    public function changeuserpassword(Request $request)
    {
        if(Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error','Disabled for demo purposes! Please contact us at info@rommoz.com');
        }

        $this->validate($request,[
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6'
        ]);

        try {
            
            $provider = User::findOrFail($request->id);
            if( !$provider  ) {
                throw new Exception('Provider Not Found');
            }
            
            if($request->password!="")
            {
                $provider->password = bcrypt($request->password);
            }
            $provider->save();

            return redirect()->back()->with('flash_success', 'Password Updated Successfully'); 
           
        } catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong!');
        }
    }
    public function changeproviderpassword(Request $request)
    {
        if(Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error','Disabled for demo purposes! Please contact us at info@rommoz.com');
        }

        $this->validate($request,[
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6'
        ]);

        try {
            
            $provider = Provider::findOrFail($request->id);
            if( !$provider  ) {
                throw new Exception('Provider Not Found');
            }
            
            if($request->password!="")
            {
                $provider->password = bcrypt($request->password);
            }
            $provider->save();

            return redirect()->back()->with('flash_success', 'Password Updated Successfully'); 
           
        } catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong!');
        }
    }

}

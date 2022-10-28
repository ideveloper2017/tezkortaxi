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
use App\SupportUser;
use App\Provider;
use App\UserRequests;
use App\RequestFilter;
use App\ProviderService;
use App\ServiceType;
use App\CorporateAccount;
use App\Complaint;


class SupportController extends Controller
{

    /**
     * SupportUser Panel.
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
        $mytime = Carbon::now();
        $services  	= 	ServiceType::all();
// 		$all_zones	=	$this->getZonesWithProvider();
		$companies  =	DB::table('fleets')->get();
		$complaint  =   Complaint::count();
        $open_ticket =  Complaint::where('status','1')->count();
        $close_ticket =  Complaint::where('status','0')->count();
        $newticket = Complaint::whereDate('created_at',$mytime->toDateString())->where('status',1)->count();
        $ip_details =	$this->ip_details;
		if(Auth::guard('admin')->user()){
            $data= "";
            return view('admin.support',compact('data'));
			
        }elseif(Auth::guard('support')->user()){

            return view('support.dashboard', compact('services', 'ip_details', 'companies','complaint','open_ticket','close_ticket','newticket'));
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
        return view('support.account.profile');
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

            $account = Auth::guard('support')->user();
            $account->name = $request->name;
            $account->mobile = $request->mobile;
            $account->save(); 
            return redirect()->back()->with('flash_success','Profile Updated');
        }

        catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong!');
        }
        
    }

    public function openTicket($type){

        $mytime = Carbon::now();
        if($type == 'new'){

            $data = Complaint::whereDate('created_at',$mytime->toDateString())->where('status',1)->get();
            $title = "New Tickets";

        }
        if($type == 'open'){

            $data = Complaint::where('status',1)->get();
            $title = "Open Tickets";
        }

       
        return view('support.open_ticket', compact('data','title'));
    }
    
        /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function password()
    {
        return view('support.account.change-password');
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

           $Account = SupportUser::find(Auth::guard('support')->user()->id);

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
        
    public function closeTicket(){

        $data = Complaint::where('status',0)->get();
        return view('support.close_ticket', compact('data'));
    }
    public function openTicketDetails($id){

        $data = Complaint::where('id',$id)->first();
        return view('support.open_ticket_details', compact('data'));
    }
    public function transfer($id,Request $request){

        $data = Complaint::where('id',$id)->first();
        $data->status = $request->status;
        $data->transfer = $request->transfer;
        $data->reply = $request->reply;
        $data->save();
        // sendMail('Ticket System',$data->email,$data->name,'Ticket System');
        return redirect()->back()->with('flash_success','Ticket Updated');
       
    }
}

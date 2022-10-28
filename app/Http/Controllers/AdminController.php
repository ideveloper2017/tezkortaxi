<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\Helper;
use DateTime;
use Auth;
use Setting;
use Exception;
use \Carbon\Carbon;
use DB;
use App\User;
use App\Fleet;
use App\Admin;
use App\Provider;
use App\UserPayment;
use App\ServiceType;
use App\UserRequests;
use App\ProviderService;
use App\UserRequestRating;
use App\UserRequestPayment;
use App\Package;
use App\FareSetting;
use App\PeakAndNight;
use App\ContactUs;
use App\Zones;
class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
    }


    /**
     * Dashboard.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        try{
            $rides = UserRequests::with('user','provider','payment')->orderBy('id','desc')->get();
            $cancel_rides = UserRequests::where('status','CANCELLED')->get();
            $scheduled_rides = UserRequests::where('status','SCHEDULED')->count();
            $user_cancelled = $cancel_rides->where('cancelled_by','USER')->count();
            $provider_cancelled = $cancel_rides->where('cancelled_by','PROVIDER')->count();
            $cancel_rides = $cancel_rides->count();
            $service = ServiceType::count();
            $fleet = UserRequests::where('paid',1)->where('payment_mode','CARD')->count();
            $cash = UserRequests::where('paid',1)->where('payment_mode','CASH')->count();
            $paypal = UserRequests::where('paid',1)->where('payment_mode','PAYPAL')->count();
            $revenue = UserRequestPayment::sum('total');
            
            $providers = Provider::take(10)->orderBy('rating','desc')->get();
            $last7days_rides    = self::last7DaysTrip();
            $last7days_rides_r  = self::last7DaysTripRe();
            $completed_rides    = UserRequests::where('status','COMPLETED')->count(); 
            $ongoing_rides      = Provider::with('service')
                        ->whereHas('service', function( $query ) {
                            $query->where('provider_services.status', 'active')
                                    ->orWhere('provider_services.status', 'riding');
                        })->where('latitude', '!=', 0)->where('longitude', '!=', 0)
                        ->where('providers.status', 'approved')->count();             
            return view('admin.dashboard',compact('providers','fleet','cash','scheduled_rides','service','rides','user_cancelled','provider_cancelled','cancel_rides','revenue','last7days_rides','last7days_rides_r','completed_rides','ongoing_rides','paypal'));
        }
        catch(Exception $e){
            //dd($e->getMessage());
            return redirect()->route('admin.user.index')->with('flash_error','Something Went Wrong with Dashboard!');
        }
    }

    public function contact(){
        $data = ContactUs::all();
        return view('admin.users.contact', compact('data'));
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

    private static function last7DaysTrip(){

    //Carbon::parse($date->created_at)->format('1')

    $days_arr =array();

    $last7days_rides= UserRequests::whereDate('created_at','>=', Carbon::now()->subDays(7)) ->groupBy('created_at')->select('created_at', DB::raw('count(*) as total'))->get();

 
  if (!$last7days_rides->isEmpty()) {

     foreach ($last7days_rides as $key => $value) { 
          
          $day = Carbon::now()->format( 'F' );
          
         // $value->format('l'); total
         $day_name= Carbon::parse($value->created_at)->format('D'); 
         $days_arr[$day_name]= array($day_name, (float) $value->total);
     }
      
  }
     $timestamp = strtotime('next Sunday');
        $days = array();
        for ($i = 0; $i < 7; $i++) {

            $day_v = strftime('%a', $timestamp); 

             if (isset($days_arr[$day_v]) && in_array($day_v, $days_arr[$day_v]))
              {
                 $arr =array();
                 $arr = $days_arr[$day_v];
                 $arr[] ='silver';
                 $days[] =$arr; 
              }else{
                $days[] =array($day_v,0,'silver');
              }

            $timestamp = strtotime('+1 day', $timestamp);
        }

     return $days; 
    //print_r($days);die;

}



private static function last7DaysTripRe(){

    //Carbon::parse($date->created_at)->format('1')

    $days_arr =array();

    $last7days_rides= UserRequests::whereDate('created_at','>=', Carbon::now()->subDays(7)) ->groupBy('created_at','id')->select('created_at','id', DB::raw('count(*) as total'))->where('status','COMPLETED')->get();


 
  if (!$last7days_rides->isEmpty()) {

    
     foreach ($last7days_rides as $key => $value) { 
          
          $day = Carbon::now()->format( 'F' );

         // $value->format('l');
         $day_name= Carbon::parse($value->created_at)->format('D'); 

           
         $rdata  = UserRequestPayment::where('request_id',$value->id)->first();
         if(!empty($rdata)){
             $total =(float) $rdata->total;
         }else{
             $total =0;
         }


         $days_arr[$day_name]= array($day_name,$total);
     }
      
  }
     $timestamp = strtotime('next Sunday');
        $days = array();
        for ($i = 0; $i < 7; $i++) {

            $day_v = strftime('%a', $timestamp); 

             if (isset($days_arr[$day_v]) && in_array($day_v, $days_arr[$day_v]))
              {
                 $arr =array();
                 $arr = $days_arr[$day_v];
                 $arr[] ='silver';
                 $days[] =$arr; 
              }else{
                $days[] =array($day_v,0,'silver');
              }

            $timestamp = strtotime('+1 day', $timestamp);
        }

     return $days; 
    //print_r($days);die;


}

public function allocation_list()
    {
        
       $data = Package::orderBy('id','desc')->get();
       foreach($data as $k=>$v){
       $data[$k]['plan_name'] = FareSetting::where('id',$v->fare_setting_id)->value('fare_plan_name');
       $data[$k]['service_name'] = ServiceType::where('id',$v->cab_id)->value('name');
       
       }
        return view('admin.service.allocation_list',compact('data'));
    }
   
    public function deletePKG(Request $request){
        // dd($request->id);
    try {
            Package::find($request->id)->delete();
            return back()->with('message', 'Vehicle Mapped deleted successfully');
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Vehicle Mapped Not Found');
        } catch (Exception $e) {
            return back()->with('flash_error', 'Vehicle Mapped Not Found');
        }
    }

    public function delivery()
    {
    
        try{
    
            return view('admin.delivery');
        }
        catch(Exception $e){
            return redirect()->route('admin.user.index')->with('flash_error','Something Went Wrong with Dashboard!');
        }
    
    }
    
    public function rental()
    {
    
        try{
    
            return view('admin.rental');
        }
        catch(Exception $e){
            return redirect()->route('admin.user.index')->with('flash_error','Something Went Wrong with Dashboard!');
        }
    }
    
    public function airport()
    {
        try{
    
            return view('admin.airport');
        }
        catch(Exception $e){
            return redirect()->route('admin.user.index')->with('flash_error','Something Went Wrong with Dashboard!');
        }
    
    }
        /**
     * Heat Map.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function heatmap()
    {
        try{
            $rides = UserRequests::has('user')->orderBy('id','desc')->get();
            $providers = Provider::take(10)->orderBy('rating','desc')->get();
            return view('admin.heatmap',compact('providers','rides'));
        }
        catch(Exception $e){
            return redirect()->route('admin.user.index')->with('flash_error','Something Went Wrong with Dashboard!');
        }
    }

    /**
     * Map of all Users and Drivers.
     *
     * @return \Illuminate\Http\Response
     */
    public function map_index()
    {
        return view('admin.map.index');
    }
    
    public function notification()
    {
        return view('admin.notification');
    }

    /**
     * Map of all Users and Drivers.
     *
     * @return \Illuminate\Http\Response
     */
    public function map_ajax()
    {
        try {
            $Providers = Provider::where('latitude', '!=', 0)
                    ->where('longitude', '!=', 0)
                    ->with('service')
                    ->get();

            $Users = User::where('latitude', '!=', 0)
                    ->where('longitude', '!=', 0)
                    ->get();

            for ($i=0; $i < sizeof($Users); $i++) { 
                $Users[$i]->status = 'user';
            }

            $All = $Users->merge($Providers);

            return $All;

        }   catch (Exception $e) {
            return [];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function settings()
    {
        return view('admin.settings.application');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function settings_store(Request $request)
    {
        // if(Setting::get('demo_mode', 0) == 1) {
        //     return back()->with('flash_error','Disabled for demo purposes! Please contact us at info@rommoz.com');
        // }

        $this->validate($request,[
                'site_title' => 'required',
                'site_icon' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
                'site_logo' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
            ]);

        if($request->hasFile('site_icon')) {
            $site_icon = Helper::upload_picture($request->file('site_icon'));
            Setting::set('site_icon', $site_icon);
        }

        if($request->hasFile('site_logo')) {
            $site_logo = Helper::upload_picture($request->file('site_logo'));
            Setting::set('site_logo', $site_logo);
        }
        if($request->hasFile('site_email_logo')) {
            $site_email_logo = Helper::upload_picture($request->file('site_email_logo'));
            Setting::set('site_email_logo', $site_email_logo);
        }
        if($request->hasFile('personal_ad')) {
            $personal_ad = Helper::upload_picture($request->file('personal_ad'));
            Setting::set('personal_ad', $personal_ad);
        }
         
        Setting::set('site_title', $request->site_title);
        Setting::set('store_link_user', $request->store_link_user);
        Setting::set('store_link_provider', $request->store_link_provider);
        Setting::set('store_link_android', $request->store_link_android);
        Setting::set('store_link_ios', $request->store_link_ios);
        Setting::set('provider_select_timeout', $request->provider_select_timeout);
        Setting::set('provider_search_radius', $request->provider_search_radius);
        Setting::set('sos_number', $request->sos_number);
        Setting::set('contact_number', $request->contact_number);
        Setting::set('contact_email', $request->contact_email);
        Setting::set('site_copyright', $request->site_copyright);
        Setting::set('social_login', $request->social_login);
        Setting::set('map_type', $request->map_type);
        Setting::set('ad_setting', $request->ad_setting);
        // Setting::set('message', $request->message);
        Setting::set('driver_phone', $request->driver_phone);
        Setting::set('driver_email', $request->driver_email);
        Setting::set('user_email', $request->user_email);
        Setting::set('user_phone', $request->user_phone);
        Setting::set('ride_cancal_min', $request->ride_cancal_min);
        Setting::set('ride_cancal_chage', $request->ride_cancal_chage);
        /*Setting::set('schedule_time', $request->schedule_time);*/
        Setting::set('chat', $request->chat);
        Setting::set('unit', $request->unit);
        Setting::set('waiting_time_out', $request->waiting_time_out);
        Setting::set('schedule_req_time', $request->schedule_req_time);

        Setting::save();
        
        return back()->with('flash_success','Settings Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function settings_payment()
    {
        return view('admin.payment.settings');
    }

    /**
     * Save payment related settings.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function settings_payment_store(Request $request)
    {
        if(Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error', '');
        }

        $this->validate($request, [
                'CARD' => 'in:on',
                'CASH' => 'in:on',
                'PAYPAL'=> 'in:on',
                'stripe_secret_key' => 'required_if:CARD,on|max:255',
                'stripe_publishable_key' => 'required_if:CARD,on|max:255',
                'daily_target' => 'required|integer|min:0',
                /*'tax_percentage' => 'required|numeric|max:100',*/
                'surge_percentage' => 'required|numeric|min:0|max:100',
                /*'commission_percentage' => 'required|numeric|min:0|max:100',*/
                'surge_trigger' => 'required|integer|min:0',
                'currency' => 'required',
                'driver_tip' => 'nullable|numeric|min:0|max:30',
            ]);

        Setting::set('CARD', $request->has('CARD') ? 1 : 0 );
        Setting::set('CASH', $request->has('CASH') ? 1 : 0 );
        Setting::set('PAYPAL', $request->has('PAYPAL') ? 1 : 0 );
        Setting::set('RAZORPAY', $request->has('RAZORPAY') ? 1 : 0 );
        Setting::set('stripe_secret_key', $request->stripe_secret_key);
        Setting::set('stripe_publishable_key', $request->stripe_publishable_key);
        Setting::set('daily_target', $request->daily_target);
        Setting::set('tax_percentage', $request->tax_percentage);
        Setting::set('surge_percentage', $request->surge_percentage);
        Setting::set('commission_percentage', $request->commission_percentage);
        Setting::set('surge_trigger', $request->surge_trigger);
        Setting::set('currency', $request->currency);
        Setting::set('booking_prefix', $request->booking_prefix);
        Setting::set('driver_tip', $request->driver_tip);
        Setting::set('PAYPAL_CLIENT_ID', $request->PAYPAL_CLIENT_ID);
		Setting::set('PAYPAL_SECRET', $request->PAYPAL_SECRET);
		Setting::set('PAYPAL_MODE', $request->PAYPAL_MODE);
		Setting::set('RAZORPAY_CLIENT_ID', $request->RAZORPAY_CLIENT_ID);
		Setting::set('RAZORPAY_SECRET', $request->RAZORPAY_SECRET);
		Setting::set('RAZORPAY_MODE', $request->RAZORPAY_MODE);
        Setting::save();

        return back()->with('flash_success','Settings Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        return view('admin.account.profile');
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
            'email' => 'required',
            'picture' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
        ]);

        try{
            $admin = Auth::guard('admin')->user();
            $admin->name = $request->name;
            $admin->email = $request->email;
            if($request->hasFile('picture')){
                $admin->picture = $request->picture->store('admin/profile');  
            }
            $admin->save();

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
        return view('admin.account.change-password');
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

           $Admin = Admin::find(Auth::guard('admin')->user()->id);

            if(password_verify($request->old_password, $Admin->password))
            {
                $Admin->password = bcrypt($request->password);
                $Admin->save();

                return redirect()->back()->with('flash_success','Password Updated');
            }
        } catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong!');
        }
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
            
            return view('admin.payment.payment-history', compact('payments'));
        } catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function help()
    {
        try {
            $str = file_get_contents('http://rommoz.com/help.json');
            $Data = json_decode($str, true);
            return view('admin.help', compact('Data'));
        } catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong!');
        }
    }

    /**
     * User Rating.
     *
     * @return \Illuminate\Http\Response
     */
    public function user_review()
    {
        try {
            $Reviews = UserRequestRating::where('user_id', '!=', 0)->has('user', 'provider')->get();
            return view('admin.review.user_review',compact('Reviews'));
        } catch(Exception $e) {
            return redirect()->route('admin.setting')->with('flash_error','Something Went Wrong!');
        }
    }

    /**
     * Provider Rating.
     *
     * @return \Illuminate\Http\Response
     */
    public function provider_review()
    {
        try {
            $Reviews = UserRequestRating::where('provider_id','!=',0)->with('user','provider')->get();
            return view('admin.review.provider_review',compact('Reviews'));
        } catch(Exception $e) {
            return redirect()->route('admin.setting')->with('flash_error','Something Went Wrong!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProviderService
     * @return \Illuminate\Http\Response
     */
    public function destory_provider_service($id){
        try {
            ProviderService::find($id)->delete();
            return back()->with('message', 'Service deleted successfully');
        } catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong!');
        }
    }

    /**
     * Testing page for push notifications.
     *
     * @return \Illuminate\Http\Response
     */
    public function push_index()
    {
        $data = PushNotification::app('IOSUser')
            ->to('163e4c0ca9fe084aabeb89372cf3f664790ffc660c8b97260004478aec61212c')
            ->send('Hello World, i`m a push message');
        dd($data);

        $data = PushNotification::app('IOSProvider')
            ->to('a9b9a16c5984afc0ea5b681cc51ada13fc5ce9a8c895d14751de1a2dba7994e7')
            ->send('Hello World, i`m a push message');
        dd($data);
    }

    /**
     * Testing page for push notifications.
     *
     * @return \Illuminate\Http\Response
     */
    public function push_store(Request $request)
    {
        try {
            ProviderService::find($id)->delete();
            return back()->with('message', 'Service deleted successfully');
        } catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong!');
        }
    }

    /**
     * privacy.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */

    public function privacy(){
        return view('admin.pages.static')
            ->with('title',"Privacy Page")
            ->with('page', "privacy");
    }

    public function faq(){
        return view('admin.pages.faq');
    }

    /**
     * pages.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function pages(Request $request){
        $this->validate($request, [
                'page' => 'required|in:page_privacy',
                'content' => 'required',
            ]);

        Setting::set($request->page, $request->content);
        Setting::save();

        return back()->with('flash_success', 'Content Updated!');
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
 
            return view('admin.providers.statement', compact('rides','cancel_rides','revenue'))
                    ->with('page',$page);

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

            return view('admin.providers.provider-statement', compact('Providers'))->with('page','Providers Statement');

        } catch (Exception $e) {
            return back()->with('flash_error','Something Went Wrong!');
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function translation(){

        try{
            return view('admin.translation');
        }

        catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong!');
        }
    }
    

    
    public function changeprovidorpassword(Request $request)
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
    public function fare_settings()
    {
        
        $data = FareSetting::orderBy('id','desc')->get();
        return view('admin.fareSetting.application',compact('data'));
    }
    public function fare_settings_store(Request $request)
    {
       // dd($request->all());
        
         $this->validate($request,[
                'fare_plan_name'        =>  'required',
                'from_km'       =>  'required',
                'upto_km'       =>  'required',
                'price_per_km'  =>  'required',
                'waiting_price_per_min' =>  'required',
                'peak_hour' =>  'required',
                'late_night'    =>  'required',
            ]);

            
        $data = $request->all();
        $req = FareSetting::Create($data);
        if($request->peak_hour=='YES'){
        foreach($request->peak_day as $pk => $peak){
         if($request['peak_start_time'][$pk] !='' && $request['peak_end_time'][$pk] !='' && $peak !=''){   
         $peakNight = new PeakAndNight;
         $peakNight->fare_setting_id = $req->id;
         $peakNight->peak_night_id = uniqid();
         $peakNight->day = $peak;
         $peakNight->start_time = date('H:i:s',strtotime($request['peak_start_time'][$pk]));
         $peakNight->end_time = date('H:i:s',strtotime($request['peak_end_time'][$pk]));
         $peakNight->fare_in_percentage = $request['peak_fare'][$pk];
         $peakNight->peak_night_type = 'PEAK';
         $peakNight->save();
           }
         
         }

     }
     if($request->late_night=='YES'){
        if($request['night_start_time'] !='' && $request['night_end_time'] !=''){
        $peakNight = new PeakAndNight;
         $peakNight->fare_setting_id = $req->id;
         $peakNight->peak_night_id = uniqid();
         // $peakNight->day = $request['peak_day'];
         $peakNight->start_time = date('H:i:s',strtotime($request['night_start_time']));
         $peakNight->end_time = date('H:i:s',strtotime($request['night_end_time']));
         $peakNight->fare_in_percentage = $request['night_fare'];
         $peakNight->peak_night_type = 'NIGHT';
         $peakNight->save();
        }
     }
        // PeakAndNight::where('fare_setting_id',\Auth::guard('admin')->id())->update(['fare_setting_id'=>$req->id]);
        
        return redirect()->route('admin.fare_settings')->with('flash_success','Settings Created Successfully');
    }
    
     public function fare_settings_create()
    {
        
       $data = Package::orderBy('id','desc')->get();
       
        return view('admin.fareSetting.create',compact('data'));
    }

     public function destory_fare($id){
        try {
            
            //return $id;
            FareSetting::find($id)->delete();
            return back()->with('message', 'Data deleted successfully');
        } catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong!');
        }
    }
    
    public function deleteFare(Request $request){

        try {
            $id = $request->id;
           
            FareSetting::find($id)->delete();
            PeakAndNight::where('fare_setting_id',$id)->delete();
            
            return back()->with('message', 'Data deleted successfully');
        } catch (Exception $e) {
            // dd($e->getMessage());
             return back()->with('flash_error','Something Went Wrong!');
        }
    }
    
    public function editFare($id){
        //$id = $request->id;
        $data = FareSetting::where('id', $id)->with('peakNight')->orderBy('id')->first();        
        return view('admin.settings.fare_edit',compact('data'));
    }
    
    public function editFareAction(Request $request){

        if($faresetting = FareSetting::find($request->id)){
            $faresetting->fare_plan_name = $request->fare_plan_name;
            $faresetting->from_km = $request->from_km;
            $faresetting->upto_km = $request->upto_km;
            $faresetting->price_per_km = $request->price_per_km;
            $faresetting->waiting_price_per_min = $request->waiting_price_per_min;
            $faresetting->peak_hour = $request->peak_hour;
            $faresetting->late_night = $request->late_night;
            $faresetting->save();
            PeakAndNight::where('fare_setting_id',$request->id)->delete();
              if($request->peak_hour=='YES'){

            foreach($request->peak_day as $pk => $peak){
                if($request['peak_start_time'][$pk] !='' && $request['peak_end_time'][$pk] !='' && $peak !=''){
                 $peakstarttime = date('H:i:s',strtotime($request['peak_start_time'][$pk]));
                 $peakendtime = date('H:i:s',strtotime($request['peak_end_time'][$pk])); 
                 $peakNight = new PeakAndNight;
                 $peakNight->fare_setting_id = $request->id;
                 $peakNight->peak_night_id = uniqid();
                 $peakNight->day = $peak;
                 $peakNight->start_time = $peakstarttime;
                 $peakNight->end_time = $peakendtime;
                 $peakNight->fare_in_percentage = $request['peak_fare'][$pk];
                 $peakNight->peak_night_type = 'PEAK';
                 $peakNight->save();   
                }
             
             }

         }
         if($request->late_night=='YES'){
            if($request['night_start_time'] !='' && $request['night_end_time'] !=''){
             $starttime = date('H:i:s',strtotime($request['night_start_time']));
             $endtime = date('H:i:s',strtotime($request['night_end_time']));
             $peakNight = new PeakAndNight;
             $peakNight->fare_setting_id = $request->id;
             $peakNight->peak_night_id = uniqid();
             // $peakNight->day = $request['peak_day'];
             $peakNight->start_time = $starttime;
             $peakNight->end_time = $endtime;
             $peakNight->fare_in_percentage = $request['night_fare'];
             $peakNight->peak_night_type = 'NIGHT';
             $peakNight->save();
            }
            
         }
            return redirect()->route('admin.fare_settings')->with('flash_success','Data updated successfully');
        }else{
            return response()->json(['status' => 0, 'msg' => 'Fare Setting Not Found']);
        }
        return view('admin.settings.fare_edit',compact('data'));
    }
     public function addpeakAnight(Request $request){
        $fid = $request->fare_setting_id?:\Auth::guard('admin')->id();        
        $count = PeakAndNight::where('peak_night_type',$request->peak_night_type)
        ->where('fare_setting_id',$fid)
        ->where('day',$request->day)->count();
        
        if($count == 0){
        $data = [
                  'start_time'=>$request->start_time,
                  'end_time'=>$request->end_time,
                  'day'=>$request->day,
                  'fare_setting_id' => $fid,
                  'fare_in_percentage'=>$request->fare_in_percentage?:0,
                  'peak_night_type'=>$request->peak_night_type, //peak or night
                  'peak_night_id'=>uniqid(),
            
                    ];
             $res = PeakAndNight::Create($data);
              $all = PeakAndNight::where('peak_night_type',$request->peak_night_type)
              ->where('fare_setting_id',$fid)->get();
              return response()->json(['data'=>$all,'status'=>2]);
        }else{
             $all = PeakAndNight::where('peak_night_type',$request->peak_night_type)->get();
             return response()->json(['data'=>$all,'status'=>1]);
        }
             
        
    }
    public function cabAllocation(Request $request){
        
        $data = [
            
            'fare_setting_id'=>$request->fare_setting_id,
            // 'category'=>$request->category,
            'cab_id'=>$request->cab_id,
            'zone_id'=>$request->provider_id,
            'description'=>$request->description           
            
            ];
       $res = Package::Create($data);
        
        return redirect()->route('admin.allocation_list')->with('flash_success','Settings Created Successfully');
    }    
    
    public function cabAllocation_edit($id){
        $package = Package::where('id',$id)->orderBy('id','desc')->first();
       // foreach($package as $k=>$v){
       $package['plan_name'] = FareSetting::where('id',$package->fare_setting_id)->value('fare_plan_name');
       $package['service_name'] = ServiceType::where('id',$package->cab_id)->value('name');
       $services = ServiceType::select('id','name')->whereNotIn('name',['Pool'])->get();
       $fare = FareSetting::select('id','fare_plan_name','from_km','upto_km')->get();
       $zones = Zones::orderBy('created_at' , 'desc')->get();
       // }
        return view('admin.service.allocation_edit',compact('package','services','zones','fare'));
    }

     public function cabAllocation_update(Request $request){
       try {
       $package = Package::findOrFail($request->id);
       $package->fare_setting_id = $request->fare_setting_id;
       $package->cab_id = $request->cab_id;
       $package->zone_id = $request->provider_id;
       $package->description = $request->description;
       $package->save();
        return redirect()->route('admin.allocation_list')->with('flash_success','Settings Updated Successfully');
        } 

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Settings Updated Not Found');
        }
    }

    public function service_update(Request $request, $id){
        if(Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error','Disabled for demo purposes! Please contact us at info@rommoz.com');
        }
         $this->validate($request, [
            'name' => 'required|max:255',
            'capacity' => 'required|numeric',
            'fixed' => 'required|numeric',
            'distance' => 'required|numeric',
            'image' => 'mimes:ico,png,jpeg,jpg',
            'vehicle_image' => 'mimes:ico,png,jpeg,jpg'
        ]);

        try {
            
            $service =ServiceType::findOrFail($id);
            
            if($request->hasFile('image')) {
                $service['image'] = Helper::upload_picture($request->file('image'));
            }
            $service =ServiceType::findOrFail($id);
            
            if($request->hasFile('vehicle_image')) {
                $service['vehicle_image'] = Helper::upload_picture($request->file('vehicle_image'));
            }
            $service->name = $request->name;
            $service->fixed = $request->fixed;
            $service->distance = $request->distance;
            $service->minute = $request->minute;
            $service->price = $request->price;
            $service->capacity = $request->capacity;
            $service->description = $request->description;
            //dd($service);
            $service->save();
            return redirect()->route('admin.service.index')->with('flash_success', 'Service Type Updated Successfully'); 
              
        } 

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Service Type Not Found');
        }
    }
}

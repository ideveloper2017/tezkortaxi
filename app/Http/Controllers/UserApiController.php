<?php

namespace App\Http\Controllers;

use App\Http\Controllers\SendPushNotification;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Chatroom;
use DB;
use Log;
use Auth;
use Hash;
use Storage;
use Setting;
use Exception;
use Notification;
use Mail; 
use App\Chat;
use Carbon\Carbon;
use App\Notifications\ResetPasswordOTP;
use App\Helpers\Helper;
use App\PushNotification;
use App\Card;
use App\Zones;
use App\User;
use App\Provider;
use App\Settings;
use App\Promocode;
use App\ServiceType;
use App\UserRequests;
use App\RequestFilter;
use App\PromocodeUsage;
use App\ProviderService;
use App\UserRequestRating;
use App\Http\Controllers\ProviderResources\TripController;
use App\UserLocationType;
use App\UserComplaint;
use App\FareSetting;
use App\PeakAndNight;
use App\UserRequestPayment;

class UserApiController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth')->except('estimated_fare','signup','forgot_password','reset_password', 'getAdSetting', 'getPersonalAD', 'getDriverTip');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     * 
     */

    public function signup(Request $request)
    {
        $this->validate($request, [
                'social_unique_id' => ['required_if:login_by,facebook,google','unique:users'],
                'device_type' => 'required|in:android,ios',
                'device_token' => 'required',
                'device_id' => 'required',
                'login_by' => 'required|in:manual,facebook,google',
                'first_name' => 'required|max:255',
                //'last_name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users',
                'mobile' => 'required',
                'password' => 'required|min:6',
            ]);

        try{
            
            $User = $request->all();

            $User['payment_mode'] = 'CASH';
            $User['password'] = bcrypt($request->password);
            
            $r = User::where('mobile',$request->mobile)->count();
            if($r !== 0){
                return response()->json(['msg'=>'The mobile has already been taken.']);
            }
            $User = User::create($User);
            //sendMail('Registration',$request->email,$request->first_name,'Registration');
            return $User;
        }   catch (Exception $e) {
                return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function logout(Request $request)
    {
        try {
            User::where('id', $request->id)->update(['device_id'=> '', 'device_token' => '']);
            return response()->json(['message' => trans('api.logout_success')]);
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function change_password(Request $request){

        $this->validate($request, [
                'password' => 'required|confirmed|min:6',
                'old_password' => 'required',
            ]);

        $User = Auth::user();

        if(Hash::check($request->old_password, $User->password))
        {
            $User->password = bcrypt($request->password);
            $User->save();

            if($request->ajax()) {
                return response()->json(['message' => trans('api.user.password_updated')]);
            }else{
                return back()->with('flash_success', 'Password Updated');
            }

        } else {
            return response()->json(['error' => trans('api.user.incorrect_password')], 500);
        }

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function update_location(Request $request){

        $this->validate($request, [
                'latitude' => 'required',
                'longitude' => 'required',
            ]);

        if($user = User::find(Auth::user()->id)){

            $point[0] = $request->latitude;
            $point[1] = $request->longitude;
            $zone_id	=	$this->getLatlngZone_id($point);
            $user->latitude = $request->latitude;
            $user->longitude = $request->longitude;
            $user->zone_id =  $zone_id;
            $user->save();

            return response()->json(['message' => trans('api.user.location_updated')]);

        }else{

            return response()->json(['error' => trans('api.user.user_not_found')], 500);

        }

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function details(Request $request){

        $this->validate($request, [
            'device_type' => 'in:android,ios',
        ]);

        try{

            if($user = User::find(Auth::user()->id)){

                if($request->has('device_token')){
                    $user->device_token = $request->device_token;
                }

                if($request->has('device_type')){
                    $user->device_type = $request->device_type;
                }

                if($request->has('device_id')){
                    $user->device_id = $request->device_id;
                }

                $user->save();

                $user->currency = Setting::get('currency');
                $user->sos = Setting::get('sos_number', '911');
                $user->card = Setting::get('CARD');
                $user->paypal = Setting::get('PAYPAL');
                $user->cash = Setting::get('CASH');
                $user['location'] = UserLocationType::where('user_id',Auth::user()->id)->get(); 
                return $user;

            }   else {
                return response()->json(['error' => trans('api.user.user_not_found')], 500);
            }
        }   catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function update_profile(Request $request)
    {

        $this->validate($request, [
                'first_name' => 'required|max:255',
                'last_name' => 'max:255',
                'email' => 'email|unique:users,email,'.Auth::user()->id,
                
                'picture' => 'mimes:jpeg,bmp,png',
            ]);

         try {

            $user = User::findOrFail(Auth::user()->id);

            if($request->has('first_name')){ 
                $user->first_name = $request->first_name;
            }
            
            if($request->has('last_name')){
                $user->last_name = $request->last_name;
            }
            
            if($request->has('email')){
                $user->email = $request->email;
            }
        
            if($request->has('mobile')){
                $user->mobile = $request->mobile;
            }

            if ($request->picture != "") {
                Storage::delete($user->picture);
                $user->picture = $request->picture->store('user/profile');
            }

            $user->save();

            if($request->ajax()) {
                return response()->json($user);
            }else{
                return back()->with('flash_success', trans('api.user.profile_updated'));
            }
        }

        catch (ModelNotFoundException $e) {
            return response()->json(['error' => trans('api.user.user_not_found')], 500);
        }

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function services() {

        if($serviceList = ServiceType::all()) {
            return $serviceList;
        } else {
            return response()->json(['error' => trans('api.services_not_found')], 500);
        }

    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function send_request(Request $request) {
        
        $this->validate( $request, [
            's_latitude' => 'required|numeric',
            'd_latitude' => 'required|numeric',
            's_longitude' => 'required|numeric',
            'd_longitude' => 'required|numeric',
            'service_type' => 'required|numeric|exists:service_types,id',
            'promo_code' => 'exists:promocodes,promo_code',
            'distance' => 'required|numeric',
            'use_wallet' => 'numeric',
            'payment_mode' => 'required|in:CASH,CARD,PAYPAL,RAZORPAY',
            //'card_id' => ['required_if:payment_mode,CARD','exists:cards,card_id,user_id,'.Auth::user()->id],
        ]);

        Log::info('New Request from User: '.Auth::user()->id);
        Log::info('Request Details:', $request->all());
        
        $spoint[0]	=	$request->s_latitude; 
		$spoint[1]	=	$request->s_longitude;
		$dpoint[0]	=	$request->d_latitude; 
		$dpoint[1]	=	$request->d_longitude;
		$szone_id	=	$this->getLatlngZone_id($spoint);
		$dzone_id	=	$this->getLatlngZone_id($dpoint);
		
		$szones = Zones::select('status')->where('id',$szone_id)->where('status','active')->first();
        
		if(count($szones) > 0)
    	{
    	    
    	        $dzones = Zones::select('status')->where('id',$dzone_id)->where('status','active')->first();
    	        
        		if(count($dzones) > 0)
        		{
                        $ActiveRequests = UserRequests::PendingRequest(Auth::user()->id)->count();

                        if($ActiveRequests > 1) {
                            if($request->ajax()) {
                                return response()->json(['error' => trans('api.ride.request_inprogress')], 500);
                            } else {
                                return redirect('dashboard')->with('flash_error', 'Already request is in progress. Try again later.');
                            }
                        }
               
                        if($request->has('schedule_date') && $request->has('schedule_time')){
                            
                            
                                $booking_id = Helper::generate_booking_id();
                    
                                $UserRequest = new UserRequests;
                                $UserRequest->booking_id = $booking_id;
                                $UserRequest->user_id = Auth::user()->id;
                                $UserRequest->provider_id = 0;
                                $UserRequest->current_provider_id = 0;
                                $UserRequest->service_type_id = $request->service_type;
                                $UserRequest->payment_mode = $request->payment_mode;
                    			$UserRequest->promo_code =	$request->promo_code;
                                
                                $UserRequest->status = 'SCHEDULED';
                    
                                $UserRequest->s_address = $request->s_address ? : "";
                                $UserRequest->d_address = $request->d_address ? : "";
                    
                                $UserRequest->s_latitude = $request->s_latitude;
                                $UserRequest->s_longitude = $request->s_longitude;
                    
                                $UserRequest->d_latitude = $request->d_latitude;
                                $UserRequest->d_longitude = $request->d_longitude;
                                $UserRequest->distance = $request->distance;
                    
                                if(Auth::user()->wallet_balance > 0) {
                                    $UserRequest->use_wallet = $request->use_wallet ? : 0;
                                }
                    
                                $UserRequest->assigned_at = Carbon::now();
                                $UserRequest->route_key = '';
                    
                                /*if($Providers->count() <= Setting::get('surge_trigger') && $Providers->count() > 0) {
                                    $UserRequest->surge = 1;
                                }*/
                    
                                if($request->has('schedule_date') && $request->has('schedule_time')){
                                    $UserRequest->schedule_at = date("Y-m-d H:i:s",strtotime("$request->schedule_date $request->schedule_time"));
                                }
                                
                                $checkrequest = UserRequests::where('status','SEARCHING')->where('user_id', Auth::user()->id)->get();
                                if(count($checkrequest)==0)
                                {
                                    $UserRequest->save();
                                    if($UserRequest) {
                                        return response()->json(['message' => trans('api.ride.request_scheduled')], 200);
                                    }   else{
                                        return redirect('dashboard')->with('flash_error', 'Already request is Scheduled on this time.');
                                    }
                                }
                               
                            /*$beforeschedule_time = (new Carbon("$request->schedule_date $request->schedule_time"))->subHour(1);
                            $afterschedule_time = (new Carbon("$request->schedule_date $request->schedule_time"))->addHour(1);
                
                            $CheckScheduling = UserRequests::where('status','SCHEDULED')
                                                ->where('user_id', Auth::user()->id)
                                                ->whereBetween('schedule_at',[$beforeschedule_time,$afterschedule_time])
                                                ->count();
                
                            if($CheckScheduling > 0){
                                if($request->ajax()) {
                                    return response()->json(['error' => trans('api.ride.request_scheduled')], 500);
                                }   else{
                                    return redirect('dashboard')->with('flash_error', 'Already request is Scheduled on this time.');
                                }
                            }*/
                        }   else{
                            
                        $distance     = Setting::get('provider_search_radius', '10');
                        $latitude     = $request->s_latitude;
                        $longitude    = $request->s_longitude;
                        $service_type = $request->service_type;
                		$promo_code	  = $request->promo_code;
                
                        $Providers    = Provider::with('service')
                                        ->select(DB::Raw("(6387 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) AS distance"),'id')
                                        ->whereRaw("(6387 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) <= $distance")
                                        ->whereHas('service', function($query) use ($service_type){
                                                    $query->where('status','active');
                                                    $query->where('service_type_id',$service_type);
                                                })
                                        ->orderBy('distance','asc')
                                        //->where('id', 206)
                                        ->where('status', 'approved');
                                        
                                        if(!empty(Auth::user()->gender) && Auth::user()->gender == "female" && $request->has("isPerfectMatch") && $request->get("isPerfectMatch")) {
                                            $Providers->where("gender", Auth::user()->gender);
                                        }
                        $Providers = $Providers->get();
                                        
                                        
                        // dd($Providers);
                        //List Providers who are currently busy and add them to the filter list.
                        
                        if(count($Providers) == 0) {
                            if($request->ajax()) {
                                 //dd('hiiii');
                                // Push Notification to User
                                return response()->json(['message' => trans('api.ride.no_providers_found')]); 
                            }else{
                                return response()->json(['flash_error' => 'No Providers Found! Please try again.']); 
                               // return back()->with('flash_success', 'No Providers Found! Please try again.');
                            }
                        }
                   
                            try{
                    
                                $details = "https://maps.googleapis.com/maps/api/directions/json?origin=".$request->s_latitude.",".$request->s_longitude."&destination=".$request->d_latitude.",".$request->d_longitude."&mode=driving&key=".env('GOOGLE_MAP_KEY');
                    
                                $json = curl($details);
                    
                                $details = json_decode($json, TRUE);
                    
                                $route_key  = $details['routes'][0]['overview_polyline']['points'];
                                $booking_id = Helper::generate_booking_id();
                    
                                $UserRequest = new UserRequests;
                                $UserRequest->booking_id = $booking_id;
                                $UserRequest->user_id = Auth::user()->id;
                                $UserRequest->provider_id = $Providers[0]->id;
                                $UserRequest->current_provider_id = $Providers[0]->id;
                                $UserRequest->service_type_id = $request->service_type;
                                $UserRequest->payment_mode = $request->payment_mode;
                    			$UserRequest->promo_code =	$request->promo_code;
                                $UserRequest->card_id =  $request->card_id;
                                $UserRequest->status = 'SEARCHING';
                    
                                $UserRequest->s_address = $request->s_address ? : "";
                                $UserRequest->d_address = $request->d_address ? : "";
                    
                                $UserRequest->s_latitude = $request->s_latitude;
                                $UserRequest->s_longitude = $request->s_longitude;
                    
                                $UserRequest->d_latitude = $request->d_latitude;
                                $UserRequest->d_longitude = $request->d_longitude;
                                $UserRequest->distance = $request->distance;
                    
                                if(Auth::user()->wallet_balance > 0) {
                                    $UserRequest->use_wallet = $request->use_wallet ? : 0;
                                }
                    
                                $UserRequest->assigned_at = Carbon::now();
                                $UserRequest->route_key = $route_key;
                    
                                if($Providers->count() <= Setting::get('surge_trigger') && $Providers->count() > 0) {
                                    $UserRequest->surge = 1;
                                }
                    
                                if($request->has('schedule_date') && $request->has('schedule_time')){
                                    $UserRequest->schedule_at = date("Y-m-d H:i:s",strtotime("$request->schedule_date $request->schedule_time"));
                                }
                                $checkrequest = UserRequests::where('status','SEARCHING')->where('user_id', Auth::user()->id)->get();
                                if(count($checkrequest)==0)
                                {
                                    $UserRequest->save();
                                }
                                
                                $data = array(
                                    'username'      => Auth::user()->first_name,
                                    'usermobile'    => Auth::user()->mobile,
                                    'payment_mode'  => Auth::user()->payment_mode,
                                    'booking_id'    => $booking_id,
                                    'drivername'    => $Providers[0]->first_name,
                                    'drivermobile'  => $Providers[0]->mobile
                                );
                                
                                /*
                                 //Send New Request Email to Admin
                                Mail::send('emails.new_request_notification', [ 'data' => $data ] , function($message) use ($data) {
                                    $message->to( config('mail.admin.address') )->subject('New Request Accepted By Driver | Wedrive ');
                                    $message->from( config('mail.from.address' ) , config('mail.from.name') );
                                });
                                */
                                
                                Log::info('New Request id : '. $UserRequest->id .' Assigned to provider : '. $UserRequest->current_provider_id);
                    
                                // update payment mode
                    
                                User::where('id',Auth::user()->id)->update(['payment_mode' => $request->payment_mode]);
                    
                                if($request->has('card_id'))    {
                    
                                    Card::where('user_id',Auth::user()->id)->update(['is_default' => 0]);
                                    Card::where('card_id',$request->card_id)->update(['is_default' => 1]);
                                }
                    
                                (new SendPushNotification)->IncomingRequest($Providers[0]->id);
                                
                                
                                if(isset($UserRequest->id) && $UserRequest->id!=''):
                                    foreach ($Providers as $key => $Provider) {
                        
                                        $Filter = new RequestFilter;
                                        // Send push notifications to the first provider
                                        // incoming request push to provider
                                        $Filter->request_id = $UserRequest->id;
                                        $Filter->provider_id = $Provider->id; 
                                        $Filter->save();
                                    }
                                endif;
                    
                                if($request->ajax()) {  
                                    return response()->json([
                                            'message' => 'New request Created!',
                                            'request_id' => $UserRequest->id,
                                            'current_provider' => $UserRequest->provider_id,
                                            'providers' => $Providers->toArray()
                                        ]);
                                }else {
                                    return response()->json(['error' => 'dashboard'], 200);
                                    //return view('user.dashboard');
                                    //return redirect('dashboard');
                                    //dd('hi');
                                }
                    
                            }   catch (Exception $e) {
                                
                                if($request->ajax()) {
                                  
                                    return response()->json(['error' => trans('api.something_went_wrong')], 500);
                                }else{
                                    return back()->with('flash_error', 'Something went wrong while sending request. Please try again.');
                                }
                            } 
        		        }
        		}   else{
        		    return response()->json(['error' => 'Service is not available on this location.'], 200);
        		}
    	}   else{
    		    return response()->json(['error' => 'Service is not available on this location.'], 200);
    	}
    }
    
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function search_provider(Request $request) {

        //Log::info('New Request from User: '.Auth::user()->id);
        //Log::info('Request Details:', $request->all());
        
        $request = UserRequests::where('user_id',Auth::user()->id)->where('status','SCHEDULED')->orderBy('id', 'DESC')->first();
        
        $spoint[0]	=	$request['s_latitude']; 
		$spoint[1]	=	$request['s_longitude'];
		$dpoint[0]	=	$request['d_latitude']; 
		$dpoint[1]	=	$request['d_longitude'];
		$szone_id	=	$this->getLatlngZone_id($spoint);
		$dzone_id	=	$this->getLatlngZone_id($dpoint);
    	        
        /*$ActiveRequests = UserRequests::PendingRequest(Auth::user()->id)->count();

        if($ActiveRequests > 1) {
            if($request->ajax()) {
                return response()->json(['error' => trans('api.ride.request_inprogress')], 500);
            } else {
                return redirect('dashboard')->with('flash_error', 'Already request is in progress. Try again later.');
            }
        }*/
        
        
         
        //$ActiveRequests = UserRequests::PendingRequest(Auth::user()->id)->count();
        $distance       =   Setting::get('provider_search_radius', '10');
        $latitude	    =	$request['s_latitude']; 
		$longitude	    =	$request['s_longitude'];
		$service_type	=   $request['service_type_id']; 
		$promo_code	    =	$request['d_longitude'];
		
        /*$latitude     = $request->s_latitude;
        $longitude    = $request->s_longitude;
        $service_type = $request->service_type;
		$promo_code	  = $request->promo_code;*/

        $Providers    = Provider::with('service')
                        ->select(DB::Raw("(6387 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) AS distance"),'id')
                        ->where('status', 'approved')
                        ->whereRaw("(6387 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) <= $distance")
                        ->whereHas('service', function($query) use ($service_type){
                                    $query->where('status','active');
                                    $query->where('service_type_id',$service_type);
                                })
                        ->orderBy('distance','asc')
                        ->get();
        //dd(count($Providers));
       
        //List Providers who are currently busy and add them to the filter list.
        if(count($Providers) == 0) {
            return back()->with('flash_success', 'No Providers Found! Please try again.');
        }
 
            try{
    
                $details = "https://maps.googleapis.com/maps/api/directions/json?origin=".$request->s_latitude.",".$request->s_longitude."&destination=".$request->d_latitude.",".$request->d_longitude."&mode=driving&key=".env('GOOGLE_MAP_KEY');
    
                $json = curl($details);
    
                $details = json_decode($json, TRUE);
                
                $route_key  = $details['routes'][0]['overview_polyline']['points'];
                $booking_id = Helper::generate_booking_id();
                
                $UserRequest = UserRequests::findOrFail($request['id']);
                //$UserRequest = new UserRequests;
                $UserRequest->booking_id = $booking_id;
                //$UserRequest->user_id = Auth::user()->id;
                $UserRequest->provider_id = $Providers[0]->id;
                $UserRequest->current_provider_id = $Providers[0]->id;
                //$UserRequest->service_type_id = $request->service_type;
                //$UserRequest->payment_mode = $request->payment_mode;
    			//$UserRequest->promo_code =	$request->promo_code;
                
                $UserRequest->status = 'SEARCHING';
    
                //dd('hi');
    
                if(Auth::user()->wallet_balance > 0) {
                    //$UserRequest->use_wallet = $request->use_wallet ? : 0;
                }
    
                $UserRequest->assigned_at = Carbon::now();
                $UserRequest->route_key = $route_key;
    
                if($Providers->count() <= Setting::get('surge_trigger') && $Providers->count() > 0) {
                    $UserRequest->surge = 1;
                }
    
                
                $checkrequest = UserRequests::where('status','SEARCHING')->where('user_id', Auth::user()->id)->get();
                if(count($checkrequest)==0)
                {
                    $UserRequest->save();
                }
                
                $data = array(
                    'username'      => Auth::user()->first_name,
                    'usermobile'    => Auth::user()->mobile,
                    'payment_mode'  => Auth::user()->payment_mode,
                    'booking_id'    => $booking_id,
                    'drivername'    => $Providers[0]->first_name,
                    'drivermobile'  => $Providers[0]->mobile
                );
                
                /*
                 //Send New Request Email to Admin
                Mail::send('emails.new_request_notification', [ 'data' => $data ] , function($message) use ($data) {
                    $message->to( config('mail.admin.address') )->subject('New Request Accepted By Driver | Wedrive ');
                    $message->from( config('mail.from.address' ) , config('mail.from.name') );
                });
                */
                
                //Log::info('New Request id : '. $UserRequest->id .' Assigned to provider : '. $UserRequest->current_provider_id);
    
                // update payment mode
    
                /*User::where('id',Auth::user()->id)->update(['payment_mode' => $request->payment_mode]);
    
                if($request->has('card_id'))    {
    
                    Card::where('user_id',Auth::user()->id)->update(['is_default' => 0]);
                    Card::where('card_id',$request->card_id)->update(['is_default' => 1]);
                }*/
    
                (new SendPushNotification)->IncomingRequest($Providers[0]->id);
                
                
                if(isset($UserRequest->id) && $UserRequest->id!=''):
                    foreach ($Providers as $key => $Provider) {
        
                        $Filter = new RequestFilter;
                        // Send push notifications to the first provider
                        // incoming request push to provider
                        $Filter->request_id = $UserRequest->id;
                        $Filter->provider_id = $Provider->id; 
                        $Filter->save();
                    }
                endif;
    
                if($UserRequest) {  
                    return response()->json([
                            'message' => 'Providor assigned to sheduled request!',
                            'request_id' => $UserRequest->id,
                            'current_provider' => $UserRequest->provider_id,
                        ]);
                }else {
                    return response()->json(['error' => 'dashboard'], 200);
                    //return view('user.dashboard');
                    //return redirect('dashboard');
                    //dd('hi');
                }
    
            }   catch (Exception $e) {
                return response()->json(['error' => $e->getMessage() ]);
            } 
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function cancel_request(Request $request) {

        // $this->validate($request, [
        //     'request_id' => 'required|numeric|exists:user_requests,id,user_id,'.Auth::user()->id,
        // ]);
        $this->validate($request, [
            'request_id' => 'required|numeric',
        ]);
        try{

            $UserRequest = UserRequests::findOrFail($request->request_id);

            if($UserRequest->status == 'CANCELLED')
            {
                if($request->ajax()) {
                    return response()->json(['error' => trans('api.ride.already_cancelled')], 500); 
                }   else{
                    return back()->with('flash_error', 'Request is Already Cancelled!');
                }
            }
            /*if($UserRequest->status != 'SEARCHING')
            {
                (new SendPushNotification)->UserCancellRide($UserRequest);
            }*/
            
            if(in_array($UserRequest->status, ['SEARCHING','STARTED','ARRIVED','SCHEDULED'])) {

                if($UserRequest->status != 'SEARCHING'){
                    $this->validate($request, [
                        'cancel_reason'=> 'max:255',
                    ]);
                }

                $UserRequest->status = 'CANCELLED';
                $UserRequest->cancel_reason = $request->cancel_reason;
                $UserRequest->cancelled_by = 'USER';
                $UserRequest->save();

                RequestFilter::where('request_id', $UserRequest->id)->delete();

                if($UserRequest->status != 'SCHEDULED') {

                    if($UserRequest->provider_id != 0)  {

                        ProviderService::where('provider_id',$UserRequest->provider_id)->update(['status' => 'active']);

                    }
                }

                // Send Push Notification to User
                
                (new SendPushNotification)->UserCancellRide($UserRequest);

                if($request->ajax()) {
                    return response()->json(['message' => trans('api.ride.ride_cancelled')]); 
                }   else{
                    return redirect('dashboard')->with('flash_success','Request Cancelled Successfully');
                }

            }   else {
                if($request->ajax()) {
                    return response()->json(['error' => trans('api.ride.already_onride')], 500); 
                }else{
                    return back()->with('flash_error', 'Service Already Started!');
                }
            }
        }

        catch (ModelNotFoundException $e) {
            if($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')]);
            }else{
                return back()->with('flash_error', 'No Request Found!');
            }
        }

    }



    public function getCurrentTrips() {
        try{
			
            $UserRequests = UserRequests::UserOngoingTrips(Auth::user()->id)->get();
            if(!empty($UserRequests)){
                $s_map_icon = asset('asset/img/map-start2.png');
                $d_map_icon = asset('asset/img/marker-stop.png');
                foreach ($UserRequests as $key => $value) {
                    $UserRequests[$key]->static_map = "https://maps.googleapis.com/maps/api/staticmap?".
                            "autoscale=1".
                            "&size=320x130".
                            "&maptype=terrian".
                            "&format=png".
                            "&visual_refresh=true".
                            "&markers=icon:".$s_map_icon."%7C".$value->s_latitude.",".$value->s_longitude.
                            "&markers=icon:".$d_map_icon."%7C".$value->d_latitude.",".$value->d_longitude.
                            "&path=color:0x000000|weight:3|enc:".$value->route_key.
                            "&key=".env('GOOGLE_MAP_KEY');
                }
            }
			
            return $UserRequests;
        }
        catch (Exception $e) {
            return response()->json(['error' => $e->getMessage() ]);
        }
    }

    /**
     * Show the request status check.
     *
     * @return \Illuminate\Http\Response
     */

public function request_status_check1() {
    //dd($request->request_id);
        try{
            $check_status = ['CANCELLED', 'SCHEDULED'];

            $UserRequests = UserRequests::UserRequestStatusCheck(Auth::user()->id, $check_status)
                                        ->get()
                                        ->toArray();
                                
            if(isset($UserRequests[0]['payment']['promocode_id']))
            {
                $promocode_id =  $UserRequests[0]['payment']['promocode_id'];
                $promocode =  Promocode::where('id', $promocode_id)->first();
                $promocode_name = $promocode['promo_code'];
                $UserRequests[0]['payment']['promocode_name']=$promocode_name;
            }
            /*if(!empty($UserRequests)){

                $card =  Card::where('user_id', Auth::user()->id)->first();
                $card_id = $card['card_id'];

                $UserRequests[0]['card_id']=$card_id;
            }*/
            
            /*$promocode_id =  $UserRequests[0]['payment']['promocode_id'];
            $promocode =  Promocode::where('id', $promocode_id)->first();
            $promocode_name = $promocode['promo_code'];*/
            //$UserRequests[0]['payment']['promocode_name']="$promocode_name";
            $search_status = ['SEARCHING','SCHEDULED'];
            $UserRequestsFilter = UserRequests::UserRequestAssignProvider(Auth::user()->id,$search_status)->get(); 

            $Timeout = Setting::get('provider_select_timeout', 180);
            if(!empty($UserRequestsFilter)){
                for ($i=0; $i < sizeof($UserRequestsFilter); $i++) {
                    $ExpiredTime = $Timeout - (time() - strtotime($UserRequestsFilter[$i]->assigned_at));
                    if($UserRequestsFilter[$i]->status == 'SEARCHING' && $ExpiredTime < 0) {
                        $Providertrip = new TripController();
                        $Providertrip->assign_next_provider($UserRequestsFilter[$i]->id);
                    }else if($UserRequestsFilter[$i]->status == 'SEARCHING' && $ExpiredTime > 0){
                        break;
                    }
                }
            }
            //dd($request->request_id);
            // UserRequests::where('id',$request->request_id)->update(['special_note' => $request->special_note]);
            return response()->json(['data' => $UserRequests]);
            
        }   catch (Exception $e) {
            
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    public function request_status_check(Request $request) {
    
        try{
            $check_status = ['CANCELLED', 'SCHEDULED'];

            $UserRequests = UserRequests::UserRequestStatusCheck(Auth::user()->id, $check_status)
                                        ->get()
                                        ->toArray();
            //dd($UserRequests[0]['user']['first_name']);                            
            if(isset($UserRequests[0]['payment']['promocode_id']))
            {
                $promocode_id =  $UserRequests[0]['payment']['promocode_id'];
                $promocode =  Promocode::where('id', $promocode_id)->first();
                $promocode_name = $promocode['promo_code'];
                $UserRequests[0]['payment']['promocode_name']=$promocode_name;
            }
            /*$promocode_id =  $UserRequests[0]['payment']['promocode_id'];
            $promocode =  Promocode::where('id', $promocode_id)->first();
            $promocode_name = $promocode['promo_code'];*/
            //$UserRequests[0]['payment']['promocode_name']="$promocode_name";
            $search_status = ['SEARCHING','SCHEDULED'];
            $UserRequestsFilter = UserRequests::UserRequestAssignProvider(Auth::user()->id,$search_status)->get(); 

            $Timeout = Setting::get('provider_select_timeout', 180);
            if(!empty($UserRequestsFilter)){
                for ($i=0; $i < sizeof($UserRequestsFilter); $i++) {
                    $ExpiredTime = $Timeout - (time() - strtotime($UserRequestsFilter[$i]->assigned_at));
                    if($UserRequestsFilter[$i]->status == 'SEARCHING' && $ExpiredTime < 0) {
                        $Providertrip = new TripController();
                        $Providertrip->assign_next_provider($UserRequestsFilter[$i]->id);
                    }else if($UserRequestsFilter[$i]->status == 'SEARCHING' && $ExpiredTime > 0){
                        break;
                    }
                }
            }
            if($request->special_note!="")
            {
                $req_data = UserRequests::findOrFail($request->request_id);
                //dd($req_data['provider_id']);
                //$UserRequests[0]['user']['first_name']='user';
                (new SendPushNotification)->specialNoteNotifyProvider($req_data['provider_id'],$request->special_note,$request->request_id,$UserRequests[0]['user']['first_name']);
            }
            //(new SendPushNotification)->specialNoteNotifyProvider($UserRequests[0]['provider_id'],$request->message,$request->request_id,$UserRequests[0]['user']['first_name']);
            UserRequests::where('id',$request->request_id)->update(['special_note' => $request->special_note]);
            return response()->json(['data' => $UserRequests]);
            
        }   catch (Exception $e) {
            
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */


    public function rate_provider(Request $request) {


        $this->validate($request, [
                'request_id' => 'required|integer|exists:user_requests,id,user_id,'.Auth::user()->id,
                'rating' => 'required|integer|in:1,2,3,4,5',
                'comment' => 'max:255',
            ]);
        $tipPrice = $request->get('tipPrice');
    
        $UserRequests = UserRequests::where('id' ,$request->request_id)
                ->where('status' ,'COMPLETED')
                //->where('paid', 1)
                ->where('paid', 0)
                ->first();
        
        if (is_object($UserRequests)) {
            if(!empty($tipPrice) && is_object($UserRequests->payment)) {
                $UserRequests->payment->total = $UserRequests->payment->total + floatval($tipPrice);
                $UserRequests->payment->tip = floatval($tipPrice);
                $UserRequests->payment->save();
            }
            if($request->ajax()){
                return response()->json(['error' => trans('api.user.not_paid')], 500);
            } else {
                return back()->with('flash_error', 'Service Already Started!');
            }
        }

        try{

            $UserRequest = UserRequests::findOrFail($request->request_id);
            $review = UserRequestRating::where('request_id',$request->request_id)->count();
            
            if(!empty($tipPrice) && is_object($UserRequest->payment)) {
                $UserRequest->payment->total = $UserRequest->payment->total + floatval($tipPrice);
                $UserRequest->payment->tip = floatval($tipPrice);
                $UserRequest->payment->save();
            }
            if($review==0) {
                UserRequestRating::create([
                    'provider_id' => $UserRequest->provider_id,
                    'user_id' => $UserRequest->user_id,
                    'request_id' => $UserRequest->id,
                    'user_rating' => $request->rating,
                    'user_comment' => $request->comment,
                ]);
            } else {
                $UserRequest->rating->update([
                    'user_rating' => $request->rating,
                    'user_comment' => $request->comment,
                ]);
            }
            /*if($UserRequest->rating == null) {
                dd('UserRequestRating');
                    UserRequestRating::create([
                        'provider_id' => $UserRequest->provider_id,
                        'user_id' => $UserRequest->user_id,
                        'request_id' => $UserRequest->id,
                        'user_rating' => $request->rating,
                        'user_comment' => $request->comment,
                    ]);
            } else {
                    dd('else');
                    $UserRequest->rating->update([
                        'user_rating' => $request->rating,
                        'user_comment' => $request->comment,
                    ]);
            }*/

            $UserRequest->user_rated = 1;
            $UserRequest->save();
            
            $average = UserRequestRating::where('provider_id', $UserRequest->provider_id)->avg('user_rating');
            
            if($average=='null')
            {
                $average=0;
            } 
            
            Provider::where('id',$UserRequest->provider_id)->update(['rating' => $average]);

            // Send Push Notification to Provider 
            if($request->ajax()){
                return response()->json(['message' => trans('api.ride.provider_rated')]); 
            }else{
                return redirect('dashboard')->with('flash_success', 'Driver Rated Successfully!');
            }
        }   catch (Exception $e) {
                if($request->ajax()){
                    return response()->json(['error' => trans('api.something_went_wrong')], 500);
                }else{
                    return back()->with('flash_error', 'Something went wrong');
                }
        }

    } 

    public function check_rate_provider(Request $request) {
	     
	     $data = UserRequests::select('id as request_id','paid','user_rated','provider_id')->where('user_id',Auth::user()->id)->where('status','COMPLETED')->orderBy('id','desc')->first();
	     $data->user_name = Auth::user()->first_name;
	     $data->provider_name = Provider::where('id',$data->provider_id)->value('first_name');
	     $data->provider_picture = Provider::where('id',$data->provider_id)->value('avatar');
	     $payment = UserRequestPayment::where('request_id', $data->request_id)->first();
	     
        $data->total = is_object($payment) ? $payment->total : 0;

            $currency = Setting::get('currency');
            $spoint[0]	=	$data->s_latitude;
            $spoint[1]	=	$data->s_longitude;
            $szone_id	=	$this->getLatlngZone_id($spoint);

            $szone = Zones::select('status')->where('id',$szone_id)->where('status','active')->first();
            if(is_object($szone) && !empty($szone->currency)) {
                $currency = $szone->currency;
            }
        $data->currency = $currency;
	     //dd($data);
	     return $data;
	 }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function trips() {
    
        try{
            $UserRequests = UserRequests::UserTrips(Auth::user()->id)->get();
            if(!empty($UserRequests)){
                $s_map_icon = asset('asset/img/map-start2.png');
                $d_map_icon = asset('asset/img/marker-stop.png');
                foreach ($UserRequests as $key => $value) {
                    $UserRequests[$key]->static_map = "https://maps.googleapis.com/maps/api/staticmap?".
                            "autoscale=1".
                            "&size=320x130".
                            "&maptype=terrian".
                            "&format=png".
                            "&visual_refresh=true".
                            "&markers=icon:".$s_map_icon."%7C".$value->s_latitude.",".$value->s_longitude.
                            "&markers=icon:".$d_map_icon."%7C".$value->d_latitude.",".$value->d_longitude.
                            "&path=color:0x191919|weight:3|enc:".$value->route_key.
                            "&key=".env('GOOGLE_MAP_KEY');
                }
            }
            return $UserRequests;
        }

        catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')]);
        }
    }


public function alltrips() {
    
        try{
            $UserRequests = UserRequests::AllUserTrips(Auth::user()->id)->get();
            if(!empty($UserRequests)){
                $s_map_icon = asset('asset/img/map-start2.png');
                $d_map_icon = asset('asset/img/marker-stop.png');
                foreach ($UserRequests as $key => $value) {
                    $UserRequests[$key]->static_map = "https://maps.googleapis.com/maps/api/staticmap?".
                            "autoscale=1".
                            "&size=320x130".
                            "&maptype=terrian".
                            "&format=png".
                            "&visual_refresh=true".
                            "&markers=icon:".$s_map_icon."%7C".$value->s_latitude.",".$value->s_longitude.
                            "&markers=icon:".$d_map_icon."%7C".$value->d_latitude.",".$value->d_longitude.
                            "&path=color:0x191919|weight:3|enc:".$value->route_key.
                            "&key=".env('GOOGLE_MAP_KEY');
                }
            }
            return $UserRequests;
        }

        catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')]);
        }
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function estimated_fare(Request $request) {
        
        \Log::info('Estimate', $request->all());
        $this->validate($request,[
            's_latitude' => 'required|numeric',
            's_longitude' => 'required|numeric',
            'd_latitude' => 'required|numeric',
            'd_longitude' => 'required|numeric',
            'service_type' => 'required|numeric|exists:service_types,id',
        ]);

        try{

            $details = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$request->s_latitude.",".$request->s_longitude."&destinations=".$request->d_latitude.",".$request->d_longitude."&mode=driving&sensor=false&key=".env('GOOGLE_MAP_KEY');

            $json = curl($details);

            $details = json_decode($json, TRUE);

            @$meter = $details['rows'][0]['elements'][0]['distance']['value'];
            @$time = $details['rows'][0]['elements'][0]['duration']['text'];
            @$seconds = $details['rows'][0]['elements'][0]['duration']['value'];

            @$kilometer = round($meter/1000);
            @$minutes = round($seconds/60);

            $tax_percentage = Setting::get('tax_percentage');
            $currency = Setting::get('currency');
            $commission_percentage = Setting::get('commission_percentage');
            $service_type = ServiceType::findOrFail($request->service_type);
			$total_discount = 0;
            $price = $service_type->fixed;
            $currentDay = date('l');
            $Time  = Carbon::now(env('APP_TIMEZONE'));
            $booking_time = $Time->toTimeString();
            //return $service_type;exit;

            $spoint[0]	=	$request->s_latitude;
            $spoint[1]	=	$request->s_longitude;
            $szone_id	=	$this->getLatlngZone_id($spoint);
            $country = "India";
            $szone = Zones::select('status')->where('id',$szone_id)->where('status','active')->first();
            if(is_object($szone)) {
                if(!empty($szone->currency)) {
                    $currency = $szone->currency;
                }
                $country = $szone->country;
            }
            
            // condition
      
		    $fareSetting = FareSetting::where('from_km','<=',round($kilometer,0))->where('upto_km','>=',round($kilometer,0))
		    	//->where('peak_hour','YES')
		   		->where('status',1)
		     	->orderBy('id','DESC')
		     	->first();
		    if(!empty($fareSetting)){
		    	$peakAndNight  = new PeakAndNight;
		    	$peakAndNight = $peakAndNight->where('start_time','<=',$booking_time)
		    	->where('end_time','>=',$booking_time)
		    	->where('status',1);
		    	if($fareSetting->peak_hour=='YES' && $fareSetting->late_night=='YES'){
		    		$peakAndNight = $peakAndNight->where(function($q) use($currentDay){
		    			$q->where('day',$currentDay)
		    			  ->orWhere('day',null);
		    		});

		    	}else{
		    		$peakAndNight = $peakAndNight->where(['day'=>$currentDay]);	
		    	}
		    	$peakAndNight = $peakAndNight->where('fare_setting_id',$fareSetting->id);
		    	$peakAndNight = $peakAndNight->orderBy('id','DESC')
		    	->first();
		    
		    	if(!empty($peakAndNight)){

		    		$amount = (($service_type->fixed+($kilometer*$fareSetting->price_per_km))*1); //double price on two way ride
		    		$Commision     = $amount * ( $commission_percentage/100 );
		    		$extra_tax_price = ( $peakAndNight->fare_in_percentage/100 ) * $amount;
		    		$amount = $amount + $extra_tax_price;
		    		$tax_price = ( $tax_percentage/100 ) * $amount;
		    		$total = $amount + $Commision +$tax_price;	
		    	}else{
		    			// fare setting applied without peak day and time
		    			$amount = (($service_type->fixed+($kilometer*$fareSetting->price_per_km))*1); //double price on two way ride
		    			$Commision     = $amount * ( $commission_percentage/100 );
		    			$tax_price = ( $tax_percentage/100 ) * $amount;
		    			$total = $amount + $Commision + $tax_price;
		    	         
		    	}
		    } else{
				// else condition
	            if($service_type->calculator == 'MIN') {
	                $price += $service_type->minute * $minutes;
	            } else if($service_type->calculator == 'HOUR') {
	                $price += $service_type->minute * 60;
	            } else if($service_type->calculator == 'DISTANCE') {
	                $price += ($kilometer * $service_type->price);
	            } else if($service_type->calculator == 'DISTANCEMIN') {
	                $price += ($kilometer * $service_type->price) + ($service_type->minute * $minutes);
	            } else if($service_type->calculator == 'DISTANCEHOUR') {
	                $price += ($kilometer * $service_type->price) + ($service_type->minute * $minutes * 60);
	            } else {
	                $price += ($kilometer * $service_type->price);
	            }
	            $Commision     = $price * ( $commission_percentage/100 );
	            $tax_price = ( $tax_percentage/100 ) * $price;
	            $total = $price + $Commision + $tax_price;
				//
		    }		
      /////////////////////////////////////////////////////////////////////////////////////////////
			//sid
			if ( $request->has('promo_code') ) {
				// Apply  promo code
				if($promo_code =  Promocode::where('promo_code', $request->promo_code)->first() ) {
					$total_discount =  ($total * $promo_code->discount)/100;
					$total = $total - $total_discount; 
				}
			}
		
            $ActiveProviders = ProviderService::AvailableServiceProvider($request->service_type)->get()->pluck('provider_id');

            $distance = Setting::get('provider_search_radius', '10');
            $latitude = $request->s_latitude;
            $longitude = $request->s_longitude;

            $Providers = Provider::whereIn('id', $ActiveProviders)
                ->where('status', 'approved')
                ->whereRaw("(1.609344 * 3956 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) <= $distance")
                ->get();

            $surge = 0;
            
            // if($Providers->count() <= Setting::get('surge_trigger') && $Providers->count() > 0){
            //     $surge_price = (Setting::get('surge_percentage')/100) * $total;
            //     $total += $surge_price;
            //     $surge = 1;
            // }
            
            //$Total= $total+$commission_percentage;
            return response()->json([
                    //'estimated_fare' => bcdiv($total,1,2),
                    'estimated_fare' => $total,
                    'distance' => $kilometer,
                    'time' => $time,
                    //'surge' => $surge,
                    //'surge_value' => '1.4X',
                    //'tax_price' => bcdiv($tax_price,1,2),
                    'tax_price' => $tax_price,
                    'base_price' => $service_type->fixed,
                    'wallet_balance' => Auth::user()->wallet_balance,
					//'discount'		=> bcdiv($total_discount,1,2),
                    'discount'      => $total_discount,
					'currency'   => $currency,
					'country' => $country
                ]);

        }   catch(Exception $e) {
        	//return $e;
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function trip_details(Request $request) {

         $this->validate($request, [
                'request_id' => 'required|integer|exists:user_requests,id',
            ]);
    
        try{
            $UserRequests = UserRequests::UserTripDetails(Auth::user()->id,$request->request_id)->get();
            if(!empty($UserRequests)){
                $s_map_icon = asset('asset/img/map-start2.png');
                $d_map_icon = asset('asset/img/marker-stop.png');
                foreach ($UserRequests as $key => $value) {
                    $UserRequests[$key]->static_map = "https://maps.googleapis.com/maps/api/staticmap?".
                            "autoscale=1".
                            "&size=320x130".
                            "&maptype=terrian".
                            "&format=png".
                            "&visual_refresh=true".
                            "&markers=icon:".$s_map_icon."%7C".$value->s_latitude.",".$value->s_longitude.
                            "&markers=icon:".$d_map_icon."%7C".$value->d_latitude.",".$value->d_longitude.
                            "&path=color:0x191919|weight:3|enc:".$value->route_key.
                            "&key=".env('GOOGLE_MAP_KEY');
                }
            }
            return $UserRequests;
        }

        catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')]);
        }
    }


    public function alltrip_details(Request $request) {

             $this->validate($request, [
                    'request_id' => 'required|integer|exists:user_requests,id',
                ]);
        
            try{
                $UserRequests = UserRequests::AllUserTripDetails(Auth::user()->id,$request->request_id)->get();
                if(!empty($UserRequests)){
                    $s_map_icon = asset('asset/img/map-start2.png');
                    $d_map_icon = asset('asset/img/marker-stop.png');
                    foreach ($UserRequests as $key => $value) {
                        $UserRequests[$key]->static_map = "https://maps.googleapis.com/maps/api/staticmap?".
                                "autoscale=1".
                                "&size=320x130".
                                "&maptype=terrian".
                                "&format=png".
                                "&visual_refresh=true".
                                "&markers=icon:".$s_map_icon."%7C".$value->s_latitude.",".$value->s_longitude.
                                "&markers=icon:".$d_map_icon."%7C".$value->d_latitude.",".$value->d_longitude.
                                "&path=color:0x191919|weight:3|enc:".$value->route_key.
                                "&key=".env('GOOGLE_MAP_KEY');
                    }
                }
                return $UserRequests;
            }

        catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')]);
        }
    }
    /**
     * get all promo code.
     *
     * @return \Illuminate\Http\Response
     */

    public function promocodes() {
        try{
            $this->check_expiry();

            return PromocodeUsage::Active()
                    ->where('user_id', Auth::user()->id)
                    ->with('promocode')
                    ->get();

        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    } 


    public function check_expiry(){
        try{
            $Promocode = Promocode::all();
            foreach ($Promocode as $index => $promo) {
                if(date("Y-m-d") > $promo->expiration){
                    $promo->status = 'EXPIRED';
                    $promo->save();
                    PromocodeUsage::where('promocode_id', $promo->id)->update(['status' => 'EXPIRED']);
                }else{
                    PromocodeUsage::where('promocode_id', $promo->id)->update(['status' => 'ADDED']);
                }
            }
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }


    /**
     * add promo code.
     *
     * @return \Illuminate\Http\Response
     */

    public function add_promocode(Request $request) {

        $this->validate($request, [
                'promocode' => 'required|exists:promocodes,promo_code',
            ]);
        try{
            $find_promo = Promocode::where('promo_code',$request->promocode)->first();

            if($find_promo->status == 'EXPIRED' || (date("Y-m-d") > $find_promo->expiration)){

                if($request->ajax()){

                    return response()->json([
                        'message' => trans('api.promocode_expired'), 
                        'code' => 'promocode_expired'
                    ]);

                }   else{
                    return back()->with('flash_error', trans('api.promocode_expired'));
                }

            }   elseif(PromocodeUsage::where('promocode_id',$find_promo->id)->where('user_id', Auth::user()->id)->where('status','ADDED')->count() > 0){

                if($request->ajax()){

                    return response()->json([
                        'message' => trans('api.promocode_already_in_use'), 
                        'code' => 'promocode_already_in_use'
                        ]);

                }else{
                    return back()->with('flash_error', 'Promocode Already in use');
                }
            }   else{
                $promo = new PromocodeUsage;
                $promo->promocode_id = $find_promo->id;
                $promo->user_id = Auth::user()->id;
                $promo->status = 'ADDED';
                $promo->save();
                if($request->ajax()){
                    return response()->json([
                            'message' => trans('api.promocode_applied') ,
                            'code' => 'promocode_applied'
                         ]); 
                }else{
                    return back()->with('flash_success', trans('api.promocode_applied'));
                }
            }
        }
        catch (Exception $e) {
            if($request->ajax()){
                return response()->json(['error' => trans('api.something_went_wrong')], 500);
            }else{
                return back()->with('flash_error', 'Something Went Wrong');
            }
        }
    } 
    
    
    public function remove_promocode(Request $request)
    {
        $deletepromo = PromocodeUsage::where('promocode_id',$request->id)->delete();
        dd($deletepromo);
        //PromocodeUsage::where('promocode_id',$find_promo->id)
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function upcoming_trips() {
    
        try{
            $UserRequests = UserRequests::UserUpcomingTrips(Auth::user()->id)->get();
            if(!empty($UserRequests)){
                $s_map_icon = asset('asset/img/map-start2.png');
                $d_map_icon = asset('asset/img/marker-stop.png');
                foreach ($UserRequests as $key => $value) {
                    $UserRequests[$key]->static_map = "https://maps.googleapis.com/maps/api/staticmap?".
                            "autoscale=1".
                            "&size=320x130".
                            "&maptype=terrian".
                            "&format=png".
                            "&visual_refresh=true".
                            "&markers=icon:".$s_map_icon."%7C".$value->s_latitude.",".$value->s_longitude.
                            "&markers=icon:".$d_map_icon."%7C".$value->d_latitude.",".$value->d_longitude.
                            "&path=color:0x000000|weight:3|enc:".$value->route_key.
                            "&key=".env('GOOGLE_MAP_KEY');
                }
            }
            return $UserRequests;
        }

        catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')]);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function upcoming_trip_details(Request $request) {

         $this->validate($request, [
                'request_id' => 'required|integer|exists:user_requests,id',
            ]);
       
        try{
            $UserRequests = UserRequests::UserUpcomingTripDetails(Auth::user()->id,$request->request_id)->get();
            if(!empty($UserRequests)){
                $s_map_icon = asset('asset/img/map-start2.png');
                $d_map_icon = asset('asset/img/marker-stop.png');
                foreach ($UserRequests as $key => $value) {
                    $UserRequests[$key]->static_map = "https://maps.googleapis.com/maps/api/staticmap?".
                            "autoscale=1".
                            "&size=320x130".
                            "&maptype=terrian".
                            "&format=png".
                            "&visual_refresh=true".
                            "&markers=icon:".$s_map_icon."%7C".$value->s_latitude.",".$value->s_longitude.
                            "&markers=icon:".$d_map_icon."%7C".$value->d_latitude.",".$value->d_longitude.
                            "&path=color:0x000000|weight:3|enc:".$value->route_key.
                            "&key=".env('GOOGLE_MAP_KEY');
                }
            }
            return $UserRequests;
        }

        catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')]);
        }
    }


    /**
     * Show the nearby providers.
     *
     * @return \Illuminate\Http\Response
     */

    public function show_providers(Request $request) {

        $this->validate($request, [
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'service' => 'numeric|exists:service_types,id',
            ]);

        try{

            $distance = Setting::get('provider_search_radius', '10');
            $latitude = $request->latitude;
            $longitude = $request->longitude;

            if($request->has('service')){
                $ActiveProviders = ProviderService::AvailableServiceProvider($request->service)->get()->pluck('provider_id');
                $Providers = Provider::whereIn('id', $ActiveProviders)
                    ->where('status', 'approved')
                    ->whereRaw("(1.609344 * 3956 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) <= $distance")
                    ->get();
            } else {
                $Providers = Provider::where('status', 'approved')
                    ->whereRaw("(1.609344 * 3956 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) <= $distance")
                    ->get();
            }

            if(count($Providers) == 0) {
                if($request->ajax()) {
                    return response()->json(['message' => "No Providers Found"]); 
                }else{
                    return back()->with('flash_success', 'No Providers Found! Please try again.');
                }
            }
        
            return $Providers;

        } catch (Exception $e) {
            if($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')], 500);
            }else{
                return back()->with('flash_error', 'Something went wrong while sending request. Please try again.');
            }
        }
    }


    /**
     * Forgot Password.
     *
     * @return \Illuminate\Http\Response
     */


    public function forgot_password(Request $request){

        $this->validate($request, [
                'email' => 'required|email|exists:users,email',
            ]);

        try{  
            
            $user = User::where('email' , $request->email)->first();

            // $otp = mt_rand(100000, 999999);

            // $user->otp = $otp;
            // $user->save();

            // Notification::send($user, new ResetPasswordOTP($otp));

            return response()->json([
                'message' => 'OTP sent to your email!',
                'user' => $user
            ]);

        }catch(Exception $e){
                return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }


    /**
     * Reset Password.
     *
     * @return \Illuminate\Http\Response
     */

    public function reset_password(Request $request){

        $this->validate($request, [
                'password' => 'required|confirmed|min:6',
                'id' => 'required|numeric|exists:users,id'
            ]);

        try{

            $User = User::findOrFail($request->id);
            $User->password = bcrypt($request->password);
            $User->save();

            if($request->ajax()) {
                return response()->json(['message' => 'Password Updated']);
            }

        }catch (Exception $e) {
            if($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')]);
            }
        }
    }

    /**
     * help Details.
     *
     * @return \Illuminate\Http\Response
     */

    public function help_details(Request $request){

        try{

            if($request->ajax()) {
                return response()->json([
                    'contact_number' => Setting::get('contact_number',''), 
                    'contact_email' => Setting::get('contact_email','')
                     ]);
            }

        }catch (Exception $e) {
            if($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')]);
            }
        }
    }

    public function createDefaultLocation(Request $request){

        try{

                  $count = UserLocationType::where('user_id',Auth::user()->id)->where('location_type',$request->location_type)->count();

          if($count == 0){

           $data = UserLocationType::Create($request->all());

            }else{

              UserLocationType::where('user_id',Auth::user()->id)->where('location_type',$request->location_type)->update($request->all());


               }
            if($request->ajax()) {
                return response()->json(['status' =>1,'msg'=>'successfully created']);
            }

        }catch (Exception $e) {
            if($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')]);
            }
        }
    }

    public function create_complaint(Request $request) {
        
	
                UserComplaint::Create($request->all());
               
                return response()->json([ 'success' =>'yes','message' => 'Compalint Created Successfuly !' ]);

      

    }

	
	
	function test() {
	
	
		$user = User::find(31);
		
		$UserReq = UserRequests::find(370);
		
		 
		(new SendPushNotification)->WalletMoney(31, 50 ); 
		
	/*
		$message = 'sid jangra';
		$to = 'fMT0lMedUYs:APA91bFQt984-yy3U8OvcjrcIrmAWOOZh1KPIDmcWTtegVvKmG2EdYhQM7W2jvbI6sYY6moplk3IQx_GiNPrJBoV0OwKxJKQzY8VY5dQWSJo5vTjriVc4MnZTaf8xwY4LhcEVtDwLxXe';
		$url = 'https://fcm.googleapis.com/fcm/send';

		$fields = array (
				'registration_ids' => array (
						$id
				),
				'notification' => array (
						"message" => $message
				)
		);
		$fields = json_encode ( $fields );

		$headers = array (
				'Authorization: key=' . "AAAADy0gw_I:APA91bHJfnAsBLAqLymWggX-wL4Mej3Lp65JMekHfcCyojUqvwMG2CWrPm89Ggtb2BHifyFF1CETpGgShYm-n11Dtg340ZeWrM4XZD0kNASdlAjaSBaxoz06-od6bHKRClYSOMTv9KC0",
				'Content-Type: application/json'
		);

		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_POST, true );
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

		$result = curl_exec ( $ch );
		 echo $result;
		curl_close ( $ch );
	 
		
		shell_exec('curl -X POST --header "Authorization: key=AAAADy0gw_I:APA91bGBYuwoA6dF1x1bOdYjnyvKPo3IVglGq8SHBSOyOS9HaFr8L39n9P5yhksrNpO_gAgmR30Dahw4YCLMU1za84O-GHKKDJEr8zX--sE1CKr-rdbPhx_LWADCBoMymmoZqypuiaQX" --header "Content-Type: application/json" https://fcm.googleapis.com/fcm/send -d "{\"to\":\"'.$to.'\",\"priority\":\"high\",\"notification\":{\"body\": \"'.stripslashes($message).'\"}}"');
		
		 */
	}
	
	public function match_location(Request $request) {

        $this->validate($request, [
            
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
            ]);

        try{

            $zones = Zones::orderBy('created_at' , 'desc')->get();
            $Locations = unserialize($zones[0]['coordinate']);
         //   dd($Locations);
         $arr='';
         
            $match = $request->latitude.','.$request->longitude;
            $find = 0;
            foreach($Locations as $loc):
                
               if(strstr($match,substr($loc, 0, ((strpos($loc, '.')+1)+6))) !=false){
                   $find=1;
                   break;
                   
               }
            endforeach;
            
            //$Data = json_encode(array_values($Locations));
            //dd(json_decode($Data));
        
            
            if ($find==1)
            {
              return response()->json(['msg'=>'Data Found']);
            }else{
              return response()->json(['msg'=>'Data Not Found']);
            }
        }

        catch (Exception $e) {
            
            if($request->ajax()){
                return response()->json(['error' => trans('api.something_went_wrong')], 500);
            }else{
                return back()->with('flash_error', 'Something Went Wrong');
            }
        }

    } 
    
    public function getChat(Request $request){
		
        $this->validate($request, [
                'request_id' => 'required',              
            ]);
			
        $userName = Auth::user()->first_name;
  
        if($request->has('message') && $request->has('provider_id') && $request->has('user_id') && $request->has('type'))   {
            //push notification
          
			(new SendPushNotification)->chatNotifyProvider($request->provider_id,$request->message,$request->request_id,$userName);
			$message = $request->message;
			$msgCreate = Chat::Create([
						'request_id'=>$request->request_id,
						'provider_id'=>$request->provider_id,
						'user_id'=>$request->user_id,
						'message'=>$request->message,
						'type'=>$request->type,
				]);
			}
          
	 	$r = Chat::where('request_id',$request->request_id)->get();
	 	return response()->json(['status'=>1,"data"=>$r]);
	}
	
	public function getLatlngZone_id( $point ) {
		$id = 0;
		$zones = Zones::all(); 
		if( count( $zones ) ) {
			foreach( $zones as $zone ) {
				if( $zone->coordinate ) {
					$coordinate = unserialize( $zone->coordinate );
					$polygon = [];
					foreach( $coordinate as $coord ) {
						$new_coord = explode(",", $coord );
						$polygon[] = $new_coord;
					}
					
					if ( Helper::pointInPolygon($point, $polygon) ) {
						return $zone->id;
					}
				}
			}
		}		
		return $id;		
	}
	
	public function notification(Request $request)
    {
        $id = Auth::user()->id;
        /*$notifications = PushNotification::where('type',1)->whereRaw("find_in_set($id,to_user)")->get();
            
                return response()->json(['Data' =>$notifications]);*/
        try {
            //dd(date('Y-m-d'));
            $notifications = PushNotification::where('type',1)->whereRaw("find_in_set($id,to_user)")->whereDate('expiration_date', '>=', date('Y-m-d'))->orderBy('id','desc')->get();
            return response()->json(['Data' =>$notifications]);
            
        }   catch(Exception $e) {
            return response()->json(['error' => "Something Went Wrong"]);
        }
    }
    
    public function getvalidzone(Request $request)
    {
        $spoint[0]	=	$request->s_latitude; 
		$spoint[1]	=	$request->s_longitude;
		$dpoint[0]	=	$request->d_latitude; 
		$dpoint[1]	=	$request->d_longitude;
		$szone_id	=	$this->getLatlngZone_id($spoint);
		$dzone_id	=	$this->getLatlngZone_id($dpoint);
		
        $szones = Zones::select('status')->where('id',$szone_id)->where('status','active')->first();

		if(count($szones) > 0)
    	{
    	        $dzones = Zones::select('status')->where('id',$dzone_id)->where('status','active')->first();
        		if(count($dzones) > 0)
        		{
        		    return response()->json(['status'=>1,'success' => 'Valid Zones.'], 200);
        		} else{
        		    return response()->json(['status'=>0,'error' => 'Sorry we are not serveing this area.'], 200);
        		}
        }       else{
        		    return response()->json(['status'=>0,'error' => 'Sorry we are not serveing this area.'], 200);
        		}		
    }
    
    
    public function review(Request $request)
    {
        try{
            $review = UserRequestRating::select('user_rating','provider_comment','created_at')->where('user_id',Auth::user()->id)
                    ->orderBy('id', 'DESC')
                    ->get();
            
            return response()->json(['Data' =>$review]);
                
           
            
        } catch(Exception $e) {
            return response()->json(['error' => "Something Went Wrong"]);
        }
    }
    
    public function addOrchangeLocation(Request $request) {

        $this->validate($request,[
            'd_address' => 'required',
            'd_latitude' => 'required',
            'd_longitude' => 'required',
            'request_id' => 'required',
            'status'=>'required',
        ]);
 
        try {
            
            // $request = $request->all();
            
            if($request->status === 'CHANGE'){
                
                UserRequests::where('id',$request->request_id)->update([
                                                          'd_address'=>$request->d_address,
                                                          'd_latitude'=>$request->d_latitude,
                                                          'd_longitude'=>$request->d_longitude
                                                          ]);
                $UserRequests = UserRequests::where('id',$request->request_id)->first();
                $message='Destination Location changed By User.';
                $userName='TEST';
                (new SendPushNotification)->changeLocationNotifyProvider($UserRequests['provider_id'],$message,$request->request_id,$userName);                                          
                return response()->json(['status'=>1,'msg'=>'successfully location changed']);
            }
            else{
            
                $data = AddChangeLocation::Create($request->all());
                
                return response()->json(['status'=>1,'msg'=>'successfully added','data'=>$data]);
            }
           
        }   catch (Exception $e) {
            
        }
	}

	public function getAdSetting(Request $request)
    {
        if(Setting::get('ad_setting', 0) == 1) {//show
            $ad_show = true;
        } else {
            $ad_show = false;
        }
        if(Setting::get('map_type', 1) == 1) {//google
            $map_type = "google";
        } else {
            $map_type = "yandex";
        }
        return response()->json(['ad_show' => $ad_show, "map_type" => $map_type]);
    }

    public function getPersonalAD(Request $request)
    {
        if(Setting::get('ad_setting', 0) == 1 && !empty(Setting::get('personal_ad'))) {//show
            return response()->json(['result_code' => true, 'url' => url(Setting::get('personal_ad'))]);
        } else {
            return response()->json(['result_code' => false]);
        }
    }
    
    public function getDriverTip(Request $request)
    {
        return response()->json(['driver_tip' => Setting::get('driver_tip', 0)]);
    }
}

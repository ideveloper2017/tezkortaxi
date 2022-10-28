<?php

namespace App\Http\Controllers\ProviderResources;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Auth;
use Setting;
use Carbon\Carbon;
use App\Helpers\Helper;
use App\Http\Controllers\SendPushNotification;
use App\Chat;
use App\Chatroom;
use App\User;
use App\Zones;
use App\FareSetting;
use App\PeakAndNight;
use App\Admin;
use App\PushNotification;
use App\Promocode;
use App\UserRequests;
use App\RequestFilter;
use App\PromocodeUsage;
use App\ProviderService;
use App\UserRequestRating;
use App\UserRequestPayment;
use App\ServiceType;
use App\WithdrawalMoney;
use App\ProviderZone;
use Mail;
use DB;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //dd('hii');
        try{
            if($request->ajax()) {
                $Provider = Auth::user();
            } else {
                $Provider = Auth::guard('provider')->user();
            }   

            $provider = $Provider->id;

            $AfterAssignProvider = RequestFilter::with(['request.user', 'request.payment', 'request'])
                ->where('provider_id', $provider)
                ->whereHas('request', function($query) use ($provider) {
                        $query->where('status','<>', 'CANCELLED');
                        $query->where('status','<>', 'SCHEDULED');
                        $query->where('provider_id', $provider );
                        $query->where('current_provider_id', $provider);
                    });


            $BeforeAssignProvider = RequestFilter::with(['request.user', 'request.payment', 'request'])
                ->where('provider_id', $provider)
                ->whereHas('request', function($query) use ($provider){
                        $query->where('status','<>', 'CANCELLED');
                        $query->where('status','<>', 'SCHEDULED');
                        $query->where('current_provider_id',$provider);
                    });

            $IncomingRequests = $BeforeAssignProvider->union($AfterAssignProvider)->get();
            //$trips = UserRequests::where('status','COMPLETED')->where('provider_id',$provider )->count();
            $trips = UserRequests::where('provider_id',$provider)->count();
            $query = "SELECT SUM(user_request_payments.fixed + user_request_payments.distance + user_request_payments.tax ) as revenue FROM `user_requests` LEFT JOIN user_request_payments on user_requests.id=user_request_payments.request_id where provider_id=".$provider;
            $rev = collect(DB::select($query))->first();
            $totalride = UserRequests::where('provider_id',$provider)->pluck('id');       
            $totalEarning = UserRequestPayment::whereIn('request_id', $totalride)->sum('total');       
            $commission = UserRequestPayment::whereIn('request_id', $totalride)->sum('commision');
            $earnings = $totalEarning-$commission;
            $earnings = number_format($earnings, 2);
            //$earnings = $rev->revenue;
            //$commision = $rev->revenue;
            if(!empty($request->latitude)) {
                $point[0] = $request->latitude;
                $point[1] = $request->longitude;
                $zone_id =	$this->getLatlngZone_id($point);
                
                $Provider->update([
                        'latitude' => $request->latitude,
                        'longitude' => $request->longitude,
                        'zone_id' =>  $zone_id
                ]);
                ProviderZone::updateOrCreate(['driver_id'=>$provider],
                    [ 'driver_id' => $provider,
                      'zone_id' =>  $zone_id
                    ]
                );
               
            }

            $Timeout = Setting::get('provider_select_timeout', 180);
                if(!empty($IncomingRequests)){
                    for ($i=0; $i < sizeof($IncomingRequests); $i++) {
                        $IncomingRequests[$i]->time_left_to_respond = $Timeout - (time() - strtotime($IncomingRequests[$i]->request->assigned_at));
                        if($IncomingRequests[$i]->request->status == 'SEARCHING' && $IncomingRequests[$i]->time_left_to_respond < 0) {
                           $this->assign_next_provider($IncomingRequests[$i]->request->id);
                        }
                    }
                }


            $Response = [
                    'account_status' => $Provider->status,
                    'service_status' => $Provider->service ? Auth::user()->service->status : 'offline',
                    'trips' => $trips,
                    'earnings' => $earnings,
                    'commision' => $commission,
                    'requests' => $IncomingRequests,
                ];

            return $Response;
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Something went wrong']);
        }
    }

    /**
     * Cancel given request.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancel(Request $request)
    {
        $this->validate($request, [
            'cancel_reason'=> 'max:255',
        ]);
        try{
            $UserRequest = UserRequests::findOrFail($request->request_id);
            $Cancellable = ['SEARCHING', 'ACCEPTED', 'ARRIVED', 'STARTED', 'CREATED','SCHEDULED'];
        
            if(!in_array($UserRequest->status, $Cancellable)) {
                return back()->with(['flash_error' => 'Cannot cancel request at this stage!']);
            }

            $UserRequest->status = "CANCELLED";
            $UserRequest->cancel_reason = $request->cancel_reason;
            $UserRequest->cancelled_by = "PROVIDER";
            $UserRequest->save();

            RequestFilter::where('request_id', $UserRequest->id)->delete();

            ProviderService::where('provider_id',$UserRequest->provider_id)->update(['status' =>'active']);

             // Send Push Notification to User
            (new SendPushNotification)->ProviderCancellRide($UserRequest);

            return $UserRequest;

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Something went wrong']);
        }


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rate(Request $request, $id)
    {
        $this->validate($request, [
                'rating' => 'required|integer|in:1,2,3,4,5',
                'comment' => 'max:255',
            ]);
    
        try {

            $UserRequest = UserRequests::where('id', $id)
                                        ->where('status', 'COMPLETED')
                                        ->firstOrFail();
            $review = UserRequestRating::where('request_id',$id)->count();
            
            if($review==0) {
                UserRequestRating::create([
                    'provider_id' => $UserRequest->provider_id,
                    'user_id' => $UserRequest->user_id,
                    'request_id' => $UserRequest->id,
                    'provider_rating' => $request->rating,
                    'provider_comment' => $request->comment,
                ]);
            } else {
                $UserRequest->rating->update([
                    'provider_rating' => $request->rating,
                    'provider_comment' => $request->comment,
                ]);
            }
            /*if($UserRequest->rating == null) {
                UserRequestRating::create([
                    'provider_id' => $UserRequest->provider_id,
                    'user_id' => $UserRequest->user_id,
                    'request_id' => $UserRequest->id,
                    'provider_rating' => $request->rating,
                    'provider_comment' => $request->comment,
                ]);
            } else {
                $UserRequest->rating->update([
                    'provider_rating' => $request->rating,
                    'provider_comment' => $request->comment,
                ]);
            }*/

            $UserRequest->update(['provider_rated' => 1]);

            // Delete from filter so that it doesn't show up in status checks.
            RequestFilter::where('request_id', $id)->delete();

            ProviderService::where('provider_id',$UserRequest->provider_id)->update(['status' =>'active']);

            // Send Push Notification to Provider 
            $average = UserRequestRating::where('provider_id', $UserRequest->provider_id)->avg('provider_rating');

            $UserRequest->user->update(['rating' => $average]);

            return response()->json(['message' => 'Request Completed!']);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Request not yet completed!'], 500);
        }
    }

    /**
     * Get the trip history of the provider
     *
     * @return \Illuminate\Http\Response
     */
    public function scheduled(Request $request)
    {
        try{
            $Jobs = UserRequests::where('provider_id', Auth::user()->id)
                    ->where('status', 'SCHEDULED')
                    ->with('service_type')
                    ->get();

            if(!empty($Jobs)){
                
                $s_map_icon = asset('asset/img/map-start2.png');
                $d_map_icon = asset('asset/img/marker-stop.png');
                foreach ($Jobs as $key => $value) {
                    $Jobs[$key]->static_map = "https://maps.googleapis.com/maps/api/staticmap?".
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
            return $Jobs;
        } catch(Exception $e) {
            return response()->json(['error' => "Something Went Wrong"]);
        }
    }


        
        // Verify OTP
        public function verifyOtp(Request $request){
            

            $this->validate($request,[
                    'request_id'    => 'required|numeric',  
                    'otp'   => 'required|numeric',
                ]);

            try{

                $user_request = UserRequests::where('id', $request->request_id)->first();
                if($user_request->verification_code == $request->otp){
                    return response()->json(["status" => 1, "msg" => 'Verifed']);
                }else{
                    return response()->json(["status" => 0, "msg" => 'Unverifed']);
                }
                
            } catch(Exception $e) {
                
                return response()->json(['error' => $e->getMessage() ], 500);
            
            }
        }
        
    /**
     * Get the trip history of the provider
     *
     * @return \Illuminate\Http\Response
     */
    public function history(Request $request)
    {
        if($request->ajax()) {

            $Jobs = UserRequests::where('provider_id', Auth::user()->id)
                    ->orderBy('created_at','desc')
                    ->with('payment')
                    ->get();

            if(!empty($Jobs)){
                $s_map_icon = asset('asset/img/map-start2.png');
                $d_map_icon = asset('asset/img/marker-stop.png');
                foreach ($Jobs as $key => $value) {
                    $Jobs[$key]->static_map = "https://maps.googleapis.com/maps/api/staticmap?".
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
            return $Jobs;
        }
        $Jobs = UserRequests::where('provider_id', Auth::guard('provider')->user()->id)->with('user', 'service_type', 'payment', 'rating')->get();
        return view('provider.trip.index', compact('Jobs'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function accept(Request $request, $id)
    {
        try {

            $UserRequest = UserRequests::findOrFail($id);

            if($UserRequest->status != "SEARCHING") {
                return response()->json(['error' => 'Request already under progress!']);
            }
            
            $UserRequest->provider_id = Auth::user()->id;

            if($UserRequest->schedule_at != ""){

                $beforeschedule_time = strtotime($UserRequest->schedule_at."- 1 hour");
                $afterschedule_time = strtotime($UserRequest->schedule_at."+ 1 hour");

                $CheckScheduling = UserRequests::where('status','SCHEDULED')
                                    ->where('provider_id', Auth::user()->id)
                                    ->whereBetween('schedule_at',[$beforeschedule_time,$afterschedule_time])
                                    ->count();

                if($CheckScheduling > 0 ){
                    if($request->ajax()) {
                        return response()->json(['error' => trans('api.ride.request_already_scheduled')]);
                    }else{
                        return redirect('dashboard')->with('flash_error', 'If the ride is already scheduled then we cannot schedule/request another ride for the after 1 hour or before 1 hour');
                    }
                }

                RequestFilter::where('request_id',$UserRequest->id)->where('provider_id',Auth::user()->id)->update(['status' => 2]);

                $UserRequest->status = "SCHEDULED";
                $UserRequest->current_provider_id = Auth::user()->id;
                //$UserRequest->pro = "SCHEDULED";
                $UserRequest->save();

            }   else{
                
                $otp = mt_rand(1000, 9999);
                $UserRequest->verification_code = $otp;
                $UserRequest->status = "STARTED";
                $UserRequest->save();

                ProviderService::where('provider_id',$UserRequest->provider_id)->update(['status' =>'riding']);

                $Filters = RequestFilter::where('request_id', $UserRequest->id)->where('provider_id', '!=', Auth::user()->id)->get();
                // dd($Filters->toArray());
                foreach ($Filters as $Filter) {
                    $Filter->delete();
                }
            }

            $UnwantedRequest = RequestFilter::where('request_id','!=' ,$UserRequest->id)
                                ->where('provider_id',Auth::user()->id )
                                ->whereHas('request', function($query){
                                    $query->where('status','<>','SCHEDULED');
                                });

            if($UnwantedRequest->count() > 0){
                $UnwantedRequest->delete();
            }  

            // Send Push Notification to User
            (new SendPushNotification)->RideAccepted($UserRequest->user_id);

            return $UserRequest->with('user')->get();

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Unable to accept, Please try again later']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Connection Error']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
               
        $this->validate($request, [
            'status' => 'required|in:ACCEPTED,STARTED,ARRIVED,PICKEDUP,DROPPED,PAYMENT,COMPLETED',
        ]);

        try{

            $UserRequest = UserRequests::with('user')->findOrFail($id);

            if($request->status == 'DROPPED' && $UserRequest->payment_mode != 'CASH') {
                $UserRequest->status = $request->status;
            } else if ($request->status == 'COMPLETED' && $UserRequest->payment_mode == 'CASH') {
                $UserRequest->status = $request->status;
                $UserRequest->paid = 1;
                // ProviderService::where('provider_id',$UserRequest->provider_id)->update(['status' =>'active']);
            } else {
                $UserRequest->status = $request->status;
            }
			
			if($request->status == 'ARRIVED'){
				// Send Push Notification to User    
				(new SendPushNotification)->Arrived($UserRequest);
			}
		

            if($request->status == 'PICKEDUP'){
                $UserRequest->started_at = Carbon::now();
            }

            $UserRequest->save();

            if($request->status == 'DROPPED') {
                $UserRequest->finished_at = Carbon::now();
                $UserRequest->save();
                $UserRequest->with('user')->findOrFail($id);
                $UserRequest->invoice = $this->invoice($id);
            
				// Send Push Notification to User
				(new SendPushNotification)->Dropped($UserRequest->user_id);
		
				 $user = [ 
							'email'		=>	$UserRequest->user->email,
							'name'		=>	$UserRequest->user->first_name,
							'total_fee'	=>	Setting::get('currency').' '.abs( $UserRequest->invoice->total ),
							'status'	=>	'Pending',
							'invoice_id'=>	$UserRequest->booking_id,
							's_address'	=>	$UserRequest->s_address,
							'd_address'	=>	$UserRequest->d_address,
							'date'		=>	date('d-m-Y'),
		  
						];
		  

				Mail::send('emails.invoice', ['user' => $user], function ($m) use ($user) {
					$m->from(config('mail.from.address' ), '');
					$m->to($user['email'], $user['name'])->subject('ILYFT - INVOICE');
				});
				
			}
			
            return $UserRequest;

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Unable to update, Please try again later']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Connection Error']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $UserRequest = UserRequests::find($id);

        try {
            $this->reject_provider($UserRequest->id);   
            return $UserRequest->with('user')->get();

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Unable to reject, Please try again later']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Connection Error']);
        }
    }
    
    
    public function reject_provider($request_id) {

        try {
            $UserRequest = UserRequests::findOrFail($request_id);
        } catch (ModelNotFoundException $e) {
            // Cancelled between update.
            return false;
        }

        $RequestFilter = RequestFilter::where('provider_id',Auth::user()->id)
                        ->where('request_id', $UserRequest->id)
                        ->delete();
                        
       
        try {

            $next_provider = RequestFilter::where('request_id', $UserRequest->id)
                            ->orderBy('id','asc')
                            ->firstOrFail();

            $UserRequest->current_provider_id = $next_provider->provider_id;
            $UserRequest->provider_id = $next_provider->provider_id;
            $UserRequest->assigned_at = Carbon::now();
            $UserRequest->save();

            // incoming request push to provider
            (new SendPushNotification)->IncomingRequest($next_provider->provider_id);
            
        } catch (ModelNotFoundException $e) {

            UserRequests::where('id', $UserRequest->id)->update(['status' => 'CANCELLED']);

            // No longer need request specific rows from RequestMeta
            RequestFilter::where('request_id', $UserRequest->id)->delete();

            //  request push to user provider not available
            (new SendPushNotification)->ProviderNotAvailable($UserRequest->user_id);
        }
    }
    

    public function assign_next_provider($request_id) {

        try {
            $UserRequest = UserRequests::findOrFail($request_id);
        } catch (ModelNotFoundException $e) {
            // Cancelled between update.
            return false;
        }

        /*$RequestFilter = RequestFilter::where('provider_id', $UserRequest->provider_id)
                        ->where('request_id', $UserRequest->id)
                        ->delete();*/
                        
        DB::table('request_filters')->where('provider_id',$UserRequest->provider_id)->where('request_id',$UserRequest->id)->take(1)->delete();                
       
        try {

            $next_provider = RequestFilter::where('request_id', $UserRequest->id)
                            ->orderBy('id','asc')
                            ->firstOrFail();

            $UserRequest->current_provider_id = $next_provider->provider_id;
            $UserRequest->provider_id = $next_provider->provider_id;
            $UserRequest->assigned_at = Carbon::now();
            $UserRequest->save();

            // incoming request push to provider
            (new SendPushNotification)->IncomingRequest($next_provider->provider_id);
            
        } catch (ModelNotFoundException $e) {

            UserRequests::where('id', $UserRequest->id)->update(['status' => 'CANCELLED']);

            // No longer need request specific rows from RequestMeta
            RequestFilter::where('request_id', $UserRequest->id)->delete();

            //  request push to user provider not available
            (new SendPushNotification)->ProviderNotAvailable($UserRequest->user_id);
        }
    }

    //public function invoice($request_id)
    public function invoicebackup(Request $request)
    {
        try {
            $request_id= $request->request_id;
            $UserRequest = UserRequests::findOrFail($request_id);
            $tax_percentage = Setting::get('tax_percentage',10);
            $commission_percentage = Setting::get('commission_percentage',10);
            $service_type = ServiceType::findOrFail($UserRequest->service_type_id);
            
            $kilometer = $UserRequest->distance;
            $Fixed = $service_type->fixed;
            $Distance = 0;
            $minutes = 0;
            $Discount = 0; // Promo Code discounts should be added here.
            $Wallet = 0;
            $Surge = 0;

            $trip_started_time      =   strtotime($UserRequest->started_at);
            $trip_finished_time     =   strtotime($UserRequest->finished_at);
            $trip_time_in_minute    =   round( ( $trip_finished_time - $trip_started_time ) / 60, 2 );
            
            $minutes = $trip_time_in_minute;

            if($service_type->calculator == 'MIN') {
                $Distance = $service_type->minute * $minutes;
                
            } else if($service_type->calculator == 'HOUR') {
                $Distance = $service_type->minute * round( $minutes / 60, 2 );
                
            } else if($service_type->calculator == 'DISTANCE') {
                $Distance = ($kilometer - $service_type->distance )  * $service_type->price;
                
            } else if($service_type->calculator == 'DISTANCEMIN') {
                $Distance = ( ( $kilometer - $service_type->distance ) * $service_type->price) + ($service_type->minute * $minutes);
                
            } else if($service_type->calculator == 'DISTANCEHOUR') {
                $Distance = ( ( $kilometer - $service_type->distance ) * $service_type->price) + ($service_type->minute * round(  $minutes / 60, 2 ) );
                
            } else {
                $Distance =  ( $kilometer - $service_type->distance )  * $service_type->price;
            }

            if($PromocodeUsage = PromocodeUsage::where('user_id',$UserRequest->user_id)->where('status','ADDED')->orderBy('id', 'DESC')->first()){
                if($Promocode  = Promocode::find($PromocodeUsage->promocode_id)){
                    $Discount  = $Promocode->discount;
                    $PromocodeUsage->status = 'USED';
                    $PromocodeUsage->save();
                }
            }

            /*$Commision = ($Distance + $Fixed) * ( $commission_percentage/100 );
            $Tax = ($Distance + $Fixed) * ( $tax_percentage/100 );
            $Total = $Fixed + $Distance + $Commision + $Tax - $Discount;
            // $Total = $Fixed + $Commision + $Tax - $Discount;

            if($UserRequest->surge){
                $Surge = (Setting::get('surge_percentage')/100) * $Total;
                $Total += $Surge;
            }*/
            
            $calculatefare = $Distance + $Fixed;
            $Commision     = $calculatefare * ( $commission_percentage/100 );
            $finalfare     = $calculatefare - $Discount;
            $Tax           = $calculatefare * ($tax_percentage/100);
            $Total         = $Commision + $finalfare + $Tax;
            
            if($UserRequest->surge){
                $Surge = (Setting::get('surge_percentage')/100) * $Total;
                $Total += $Surge;
            }
            
            if($Total < 0){
                $Total = 0.00; // prevent from negative value
            }

            $Payment = new UserRequestPayment;
            $Payment->request_id = $UserRequest->id;
            $Payment->fixed = $Fixed;
            $Payment->distance = $Distance;
            $Payment->commision = $Commision;
            $Payment->surge = $Surge;
            if($Discount != 0 && $PromocodeUsage){
                $Payment->promocode_id = $PromocodeUsage->promocode_id;
            }
            $Payment->discount = $Discount;

            if($UserRequest->use_wallet == 1 && $Total > 0){

                $User = User::find($UserRequest->user_id);

                $Wallet = $User->wallet_balance;

                if($Wallet != 0){

                    if($Total > $Wallet) {

                        $Payment->wallet = $Wallet;
                        $Payable = $Total - $Wallet;
                        User::where('id',$UserRequest->user_id)->update(['wallet_balance' => 0 ]);
                        $Payment->total = abs($Payable);

                        // charged wallet money push 
                        (new SendPushNotification)->ChargedWalletMoney($UserRequest->user_id,currency($Wallet));

                    } else {

                        $Payment->total = 0;
                        $WalletBalance = $Wallet - $Total;
                        User::where('id',$UserRequest->user_id)->update(['wallet_balance' => $WalletBalance]);
                        $Payment->wallet = $Total;
                        
                        $Payment->payment_id = 'WALLET';
                        $Payment->payment_mode = $UserRequest->payment_mode;
                        $Payment->paid = 1;

                        $UserRequest->paid = 1;
                        $UserRequest->status = 'COMPLETED';
                        $UserRequest->save();

                        // charged wallet money push 
                        (new SendPushNotification)->ChargedWalletMoney($UserRequest->user_id,currency($Total));
                    }
                }

            } else {
                $Payment->total = abs($Total);
            }

            $Payment->tax = $Tax;
            $Payment->save();
            /*$deletedDuplicatePayment = UserRequestPayment::where('request_id',$Payment->request->id)->orderBy('id','DESC')->take(1);
            $deletedDuplicatePayment->delete();*/
			
        return $Payment;

        } catch (ModelNotFoundException $e) {
            return false;
        }
    }
    
    
    public function invoice($request_id)
    {
        try {
            $UserRequest = UserRequests::findOrFail($request_id);
            $tax_percentage = Setting::get('tax_percentage',10);
            $commission_percentage = Setting::get('commission_percentage',10);
            
            $details = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$UserRequest->s_latitude.",".$UserRequest->s_longitude."&destinations=".$rider->latitude.",".$rider->longitude."&mode=driving&sensor=false&key=".env('GOOGLE_MAP_KEY');

            $json = curl($details);

            $details = json_decode($json, TRUE);

            @$meter = $details['rows'][0]['elements'][0]['distance']['value'];
            @$time = $details['rows'][0]['elements'][0]['duration']['text'];
            @$seconds = $details['rows'][0]['elements'][0]['duration']['value'];

            @$kilometer = round($meter/1000);
            $service_type = ServiceType::findOrFail($UserRequest->service_type_id);
            
            $Fixed = $service_type->fixed;
            $Distance = 0;
            $minutes = 0;
            $Discount = 0; // Promo Code discounts should be added here.
            $Wallet = 0;
            $Surge = 0;
            $Commision = 0;
            
            //new
            $tax_percentage = Setting::get('tax_percentage');
            $currency = Setting::get('currency');
            $commission_percentage = Setting::get('commission_percentage');
            //$service_type = ServiceType::findOrFail($request->service_type);
			$total_discount = 0;
            $price = $service_type->fixed;
            $currentDay = date('l');
            $Time  = Carbon::now(env('APP_TIMEZONE'));
            $booking_time = $Time->toTimeString();

            $trip_started_time      =   strtotime($UserRequest->started_at);
            $trip_finished_time     =   strtotime($UserRequest->finished_at);
            $trip_time_in_minute    =   round( ( $trip_finished_time - $trip_started_time ) / 60, 2 );
            
            $minutes = $trip_time_in_minute;

             // condition
      
		    $fareSetting = FareSetting::where('from_km','<=',round($kilometer,0))
		    	->where('upto_km','>=',round($kilometer,0))
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
		    		$Total = $amount + $Commision +$tax_price;	
		    	}else{
		    			// fare setting applied without peak day and time
		    			$amount = (($service_type->fixed+($kilometer*$fareSetting->price_per_km))*1); //double price on two way ride
		    			$Commision     = $amount * ( $commission_percentage/100 );
		    			$tax_price = ( $tax_percentage/100 ) * $amount;
		    			$Total = $amount + $Commision + $tax_price;
		    	         
		    	}
		    } else{
				// else condition
	           // if($service_type->calculator == 'MIN') {
	           //     $price += $service_type->minute * $minutes;
	           // } else if($service_type->calculator == 'HOUR') {
	           //     $price += $service_type->minute * 60;
	           // } else if($service_type->calculator == 'DISTANCE') {
	           //     $price += ($kilometer * $service_type->price);
	           // } else if($service_type->calculator == 'DISTANCEMIN') {
	           //     $price += ($kilometer * $service_type->price) + ($service_type->minute * $minutes);
	           // } else if($service_type->calculator == 'DISTANCEHOUR') {
	           //     $price += ($kilometer * $service_type->price) + ($service_type->minute * $minutes * 60);
	           // } else {
	           //     $price += ($kilometer * $service_type->price);
	           // }
	            $price += ($kilometer * $service_type->price);
	            $Commision     = $price * ( $commission_percentage/100 );
	            $tax_price = ( $tax_percentage/100 ) * $price;
	            $Total = $price + $Commision + $tax_price;
				//
		    }
		    
		    
		  //  if($service_type->calculator == 'MIN') {
    //             $Distance = $service_type->minute * $minutes;
                
    //         } else if($service_type->calculator == 'HOUR') {
    //             $Distance = $service_type->minute * round( $minutes / 60, 2 );
                
    //         } else if($service_type->calculator == 'DISTANCE') {
    //             $Distance = ($kilometer - $service_type->distance )  * $service_type->price;
                
    //         } else if($service_type->calculator == 'DISTANCEMIN') {
    //             $Distance = ( ( $kilometer - $service_type->distance ) * $service_type->price) + ($service_type->minute * $minutes);
                
    //         } else if($service_type->calculator == 'DISTANCEHOUR') {
    //             $Distance = ( ( $kilometer - $service_type->distance ) * $service_type->price) + ($service_type->minute * round(  $minutes / 60, 2 ) );
                
    //         } else {
    //             $Distance =  ( $kilometer - $service_type->distance )  * $service_type->price;
    //         }
            $Distance =  ( $kilometer - $service_type->distance )  * $service_type->price;
            if($PromocodeUsage = PromocodeUsage::where('user_id',$UserRequest->user_id)->where('status','ADDED')->orderBy('id', 'DESC')->first()){
                if($Promocode  = Promocode::find($PromocodeUsage->promocode_id)){
                    
                    $total_discount =  ($Total * $Promocode->discount)/100;
					$Total = $Total - $total_discount; 
                    $PromocodeUsage->status = 'USED';
                    $PromocodeUsage->save();
                }
            }
            
            /*if ( $request->has('promo_code') ) {
				// Apply  promo code
				if($promo_code =  Promocode::where('promo_code', $request->promo_code)->first() ) {
					$total_discount =  ($total * $promo_code->discount)/100;
					$total = $total - $total_discount; 
				}
			}*/
            
            if($UserRequest->surge){
                $Surge = (Setting::get('surge_percentage')/100) * $Total;
                $Total += $Surge;
            }
            
            if($Total < 0){
                $Total = 0.00; // prevent from negative value
            }

            $Payment = new UserRequestPayment;
            $Payment->request_id = $UserRequest->id;
            $Payment->fixed = $price;
            $Payment->distance = $Distance;
            $Payment->commision = $Commision;
            $Payment->surge = $Surge;
            if($Discount != 0 && $PromocodeUsage){
                $Payment->promocode_id = $PromocodeUsage->promocode_id;
            }
            $Payment->discount = $Discount;

            if($UserRequest->use_wallet == 1 && $Total > 0){

                $User = User::find($UserRequest->user_id);

                $Wallet = $User->wallet_balance;

                if($Wallet != 0){

                    if($Total > $Wallet) {

                        $Payment->wallet = $Wallet;
                        $Payable = $Total - $Wallet;
                        User::where('id',$UserRequest->user_id)->update(['wallet_balance' => 0 ]);
                        $Payment->total = abs($Payable);

                        // charged wallet money push 
                        (new SendPushNotification)->ChargedWalletMoney($UserRequest->user_id,currency($Wallet));

                    } else {

                        $Payment->total = 0;
                        $WalletBalance = $Wallet - $Total;
                        User::where('id',$UserRequest->user_id)->update(['wallet_balance' => $WalletBalance]);
                        $Payment->wallet = $Total;
                        
                        $Payment->payment_id = 'WALLET';
                        $Payment->payment_mode = $UserRequest->payment_mode;
                        $Payment->paid = 1;

                        $UserRequest->paid = 1;
                        $UserRequest->status = 'COMPLETED';
                        $UserRequest->save();

                        // charged wallet money push 
                        (new SendPushNotification)->ChargedWalletMoney($UserRequest->user_id,currency($Total));
                    }
                }

            } else {
                $Payment->total = abs($Total);
            }

            $Payment->tax = $tax_price;
            $Payment->save();
            /*$deletedDuplicatePayment = UserRequestPayment::where('request_id',$Payment->request->id)->orderBy('id','DESC')->take(1);
            $deletedDuplicatePayment->delete();*/
			
        return $Payment;

        } catch (ModelNotFoundException $e) {
            return false;
        }
    }

    /**
     * Get the trip history details of the provider
     *
     * @return \Illuminate\Http\Response
     */
    public function history_details(Request $request)
    {
        $this->validate($request, [
                'request_id' => 'required|integer|exists:user_requests,id',
            ]);

        if($request->ajax()) {
            
            $Jobs = UserRequests::where('id',$request->request_id)
                                ->where('provider_id', Auth::user()->id)
                                ->with('payment','service_type','user','rating')
                                ->get();
            if(!empty($Jobs)){
                $s_map_icon = asset('asset/img/map-start2.png');
                $d_map_icon = asset('asset/img/marker-stop.png');
                foreach ($Jobs as $key => $value) {
                    $Jobs[$key]->static_map = "https://maps.googleapis.com/maps/api/staticmap?".
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

            return $Jobs;
        }

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function upcoming_trips() {
    
        try{
            $UserRequests = UserRequests::ProviderUpcomingRequest(Auth::user()->id)->get();
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
     * Get the trip history details of the provider
     *
     * @return \Illuminate\Http\Response
     */
    public function upcoming_details(Request $request)
    {

        $this->validate($request, [
                'request_id' => 'required|integer|exists:user_requests,id',
            ]);

        //if($request->ajax()) {
            $Jobs = UserRequests::where('id',$request->request_id)
                                ->where('provider_id', Auth::user()->id)
                                ->with('service_type','user')
                                ->get();
            if(!empty($Jobs)){
                $s_map_icon = asset('asset/img/map-start2.png');
                $d_map_icon = asset('asset/img/marker-stop.png');
                foreach ($Jobs as $key => $value) {
                    $Jobs[$key]->static_map = "https://maps.googleapis.com/maps/api/staticmap?".
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

            return $Jobs;
        //}

    }

    public function dailyearning_detail(Request $request)
    {

        $this->validate($request, [
                'request_id' => 'required|integer|exists:user_requests,id',
            ]);

        //if($request->ajax()) {
            $Jobs = UserRequests::where('provider_id',\Auth::guard('provider')->user()->id)
                    ->with('payment','service_type')
                    ->where('id',$request->request_id)
                    ->get();

            if(!empty($Jobs)){
                $s_map_icon = asset('asset/img/map-start2.png');
                $d_map_icon = asset('asset/img/marker-stop.png');
                foreach ($Jobs as $key => $value) {
                    $Jobs[$key]->static_map = "https://maps.googleapis.com/maps/api/staticmap?".
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

            return $Jobs;
        //}

    }

    /**
     * Get the trip history details of the provider
     *
     * @return \Illuminate\Http\Response
     */
    public function summary(Request $request)
    {
        try{
            if($request->ajax()) {
                $rides = UserRequests::where('provider_id', Auth::user()->id)->count();
                $revenue = UserRequestPayment::whereHas('request', function($query) use ($request) {
                                $query->where('provider_id', Auth::user()->id);
                            })
                        ->sum('total');
                $cancel_rides = UserRequests::where('status','CANCELLED')->where('provider_id', Auth::user()->id)->count();
                $scheduled_rides = UserRequests::where('status','SCHEDULED')->where('provider_id', Auth::user()->id)->count();

                return response()->json([
                    'rides' => $rides, 
                    'revenue' => $revenue,
                    'cancel_rides' => $cancel_rides,
                    'scheduled_rides' => $scheduled_rides,
                ]);
            }

        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')]);
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
	
	
	
	 public function test(){

		$UserRequest = UserRequests::with(['user' ,'payment'])->findOrFail(379);
		
		 $user = [ 
					'email'		=>	$UserRequest->user->email,
					'name'		=>	$UserRequest->user->first_name,
					'total_fee'	=>	Setting::get('currency').' '.abs( 45.45 ),
					'status'	=>	'Pending',
					'invoice_id'=>	$UserRequest->booking_id,
					's_address'	=>	$UserRequest->s_address,
					'd_address'	=>	$UserRequest->d_address,
					'date'		=>	date('d-m-Y'),
				];
  

		Mail::send('emails.invoice', ['user' => $user], function ($m) use ($user) {
			$m->from('support@rommoz.com', '');
			$m->to($user['email'], $user['name'])->subject('pintaxi - INVOICE');
		});

	
        
    }
    
    public function review(Request $request)
    {
        $this->validate($request, [
                'provider_id' => 'required|integer',
            ]);
            
        try{

            $review = UserRequestRating::select('user_rating','user_comment','created_at')->where('provider_id',Auth::user()->id)
                    ->orderBy('id', 'DESC')
                    ->get();
            
            return response()->json(['Data' =>$review]);
            
        } catch(Exception $e) {
            return response()->json(['error' => "Something Went Wrong"]);
        }
    }
    	//Get Chat
	public function getChat(Request $request){

		$this->validate($request, [
				'request_id' => 'required',              
			]);
	
		$userName = Auth::user()->first_name;
		if($request->has('message') && $request->has('provider_id') && $request->has('user_id') && $request->has('type')){

			//push notification
			(new SendPushNotification)->chatNotify($request->user_id,$request->message,$request->request_id,$userName);
		   
            $message = '$request->message';
			$msgCreate = Chat::Create([
				'request_id'	=>	$request->request_id,
				'provider_id'	=>	$request->provider_id,
				'user_id'		=>	$request->user_id,
				'message'		=>	$request->message,
				'type'			=>	$request->type,
			]);
		}

		$r = Chat::where('request_id',$request->request_id)->get();

		return response()->json(['status'=>1,"data"=>$r]);
	}
	
	public function dummyNotify()
	{
	    $msg= (new SendPushNotification)->chatNotify(43,'dummy',13,'john doe');
	    $msg2 = (new SendPushNotification)->chatNotifyProvider(80,'dummy',13,'john doe');
	    return $msg2;
	}
	
	public function notification(Request $request)
    {
        $id = Auth::user()->id;    
        try{
            
            $notifications = PushNotification::where('type',2)->whereRaw("find_in_set($id,to_user)")->whereDate('expiration_date', '>=', date('Y-m-d'))->orderBy('id','desc')->get();
            return response()->json(['Data' =>$notifications]);
            
        }   catch(Exception $e) {
            return response()->json(['error' => "Something Went Wrong"]);
        }
    }
    
    public function addNotification(Request $request)
    {
        $this->validate($request, [
            'type' => 'required',
            'title'=>'required',
            'notification_text'=>'required'
        ]);
        
            
        try {
                    
            $notification = new PushNotification;
            $notification->to_user = $request->to_user;
            $notification->type = $request->type;
            $notification->from_user = Auth::user()->id;
            $notification->title = $request->title;
            $notification->notification_text = $request->notification_text;
            $notification->url = $request->url;
            $notification->expiration_date = $request->expiration_date;
            $notification->zone = $request->zone;
            if($request->hasFile('image')) {
                
                $notification['image'] = $request->image->store('user/profile');
            }
            $notification->save();
            return response()->json(['message' =>'Notification saved.']);
                
        }   catch(Exception $e) {
            return response()->json(['error' => "Something Went Wrong."]);
        }
    }
    
    public function withdrawRequestList(Request $request){

        $Earn = UserRequestPayment::whereHas('request', function($query) use ($request) {
                                $query->where('provider_id', Auth::user()->id);
                            })
                        ->sum('total');
        $commision = UserRequestPayment::whereHas('request', function($query) use ($request) {
                                $query->where('provider_id', Auth::user()->id);
                            })
                        ->sum('commision');
        $totalEarn =    $Earn-$commision;
        $totalEarn? : 0;            

        $r =  WithdrawalMoney::where('provider_id',Auth::user()->id)->whereIn('status',['APPROVED','WAITING'])->orderBy('id','desc')->get();
        
      //  $takenAmount =  WithdrawalMoney::where('provider_id',Auth::user()->id)->where('status','APPROVED')->sum('amount');
      $takenAmount =  WithdrawalMoney::where('provider_id',Auth::user()->id)->whereIn('status',['APPROVED','WAITING'])->sum('amount');
    

        if(count($r) != 0){
            $res = $r;
            $status = 1;
            $afterdeduct = ($totalEarn-$takenAmount);
            $amount = $afterdeduct;
    
        }   else{
            $res = 0;
            $status = 0;
            $amount = $totalEarn;
        }
    
        return response()->json(["status"=>$status,"data"=>$res,'totalEarn'=>round($amount,2)]);
}
    
    public function withdrawalRequest(Request $request){

        $this->validate($request, [
            'provider_id' => 'required|int',
            'bank_account_id' => 'required',
            'amount' => 'required|int',  //bank token btok_we32e3
           
        ]);
   
        $Earn = UserRequestPayment::whereHas('request', function($query) use ($request) {
                                $query->where('provider_id', Auth::user()->id);
                            })
                        ->sum('total');
                        
        $commision = UserRequestPayment::whereHas('request', function($query) use ($request) {
                                $query->where('provider_id', Auth::user()->id);
                            })
                        ->sum('commision');
        $totalEarn =    $Earn-$commision;
        $totalEarn? : 0;  

        $r =  WithdrawalMoney::where('provider_id',Auth::user()->id)->whereIn('status',['APPROVED','WAITING'])->orderBy('id','desc')->get();
        
        $takenAmount =  WithdrawalMoney::where('provider_id',Auth::user()->id)->whereIn('status',['APPROVED','WAITING'])->sum('amount');
    

        if(count($r) != 0){
            $res = $r;
            $afterdeduct = ($totalEarn-$takenAmount);
            $amount = $afterdeduct;
    
        }   else{
            $res = 0;
            $amount = $totalEarn;
        }
   
    if($request->amount <= $amount){              
   $r = WithdrawalMoney::Create($request->all()); 
   $status = 1;
   $msg = 'successfully requested for withdrawal';
}
else
{
    $r = [];
    $status = 0;
   $msg = 'You do not have sufficient balance .please take less amount from your earned amount';

}

    if($request->ajax()) {
                   return response()->json(['status'=>$status,'data'=>$r,'msg'=>$msg]);
                } 
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
}

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
use App\Dispatcher;
use App\Provider;
use App\UserRequests;
use App\RequestFilter;
use App\ProviderService;
use App\ServiceType;
use App\CorporateAccount;
use App\Complaint;


class DispatcherController extends Controller
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
    
    public function index()
    {	//dd('hii');
        $services  	= 	ServiceType::all();
		$all_zones	=	$this->getZonesWithProvider();
		$companies  =	DB::table('fleets')->get();
		
        $ip_details =	$this->ip_details;
		if(Auth::guard('admin')->user()){
            $data= "";
            return view('admin.dispatcher',compact('data'));
			
        }elseif(Auth::guard('dispatcher')->user()){
            return view('dispatcher.dispatcher', compact('services', 'ip_details', 'all_zones', 'companies'));
        }
    }

  
    public function new_booking( ){		
        $services  = ServiceType::all();    
		$ip_details =	$this->ip_details;
		$user_id = Auth::user()->id;
		$corporates = CorporateAccount::all();
		$all_zones		=	$this->getZonesWithProvider();
		$payment_methods = DB::table('payment_methods')->get();
	
		
        if(Auth::guard('admin')->user()){
            $data= "";
            return view('admin.dispatcher',compact('data'));
			
        }elseif(Auth::guard('dispatcher')->user()){
            return view('dispatcher.new_booking', compact('services', 'ip_details','user_id','corporates' , 'all_zones', 'payment_methods' ));
        }
    }
	

    public function indexcreate($dispatch="")
    {
        if(Auth::guard('admin')->user()){
            $data= "dispatch-map";
            return view('admin.dispatcher_create',compact('data'));
        }elseif(Auth::guard('dispatcher')->user()){
            return view('dispatcher.dispatcher');
        }
    }

    /**
     * Display a listing of the active trips in the application.
     *
     * @return \Illuminate\Http\Response
     */

    public function cancel_ride(Request $request){
        $request->type='dispatcher';
		$request->dispatcher_id = Auth::user()->id; 
		
		$input = [
			'dispatcher_id' => Auth::user()->id,
			'type'			=> 'dispatcher'
		];
		
		array_merge( $request->all(), $input );
		
		
		if( $request->cancel_status == 'cancel' ||  $request->cancel_status == 'dead' ) {
			return $this->UserAPI->cancel_request($request);
		}
		
		if( $request->cancel_status == 'reassign' ||  $request->cancel_status == 'assign' ) {
			
			return $this->assign( $request );
		}
		
    
	}
	
	public function assignCompany(Request $request) {
		  $this->validate($request, [
			'trip_id'		=> 'required|numeric',
			'cab_company_id'	=> 'required|numeric',
		]);
	
		try {
			
			$json = array();
			$Request 	= UserRequests::where('id', $request->trip_id)->first();
			
			if( !$Request ) {
				throw new Exception('No trip Found!');
			}
			
			$company  	= DB::table('fleets')->where('id', $request->cab_company_id)->first();
			if( ! $company ) {
				throw new Exception('choosen company not found!');
			}
			
			$Request->status = 'COMPLETED';
			$Request->cab_company_id	=	$company->id;
			$Request->paid				=	1;
			
			if( $request->input('special_note') ) {
				$Request->company_note	=	trim ($request->special_note );
				
				(new SendPushNotification)->sendDriverDetailToUser($Request->user_id , $Request->company_note ); 
			}
			
			$Request->save();
			
			
			Log::useFiles(storage_path().'/logs/dispatcher.log');
			Log::info( $Request->booking_id.' id is assigned to co-partner by dispatcher panel!');
			
			$json['trip'] = $Request;
			return response()->json($json);
		
		} catch (Exception $e) {
			
			return response()->json(['error' => $e->getMessage()]);
		} 
    
	}

	
	
	public function update_trip(Request $request ) {
		
		$this->validate($request, [
			'first_name'	=>	'required',
			'special_note'	=>	'required',
			's_address'		=>	'required',
			'd_address'		=>	'required',
			'email'			=>	'required',
			'mobile'		=>	'required',
			'request_id'	=>	'required|numeric',
			's_latitude'	=>	'required|numeric',
			's_longitude'	=>	'required|numeric',
			'd_latitude'	=>	'required|numeric',
			'd_longitude'	=>	'required|numeric',
		]);
		
		$json = array('status' => false );
		
		try {
			
			$UserRequest = UserRequests::find($request->request_id);
			
			if( ! $UserRequest ) {
				throw new Exception('Unauthorized Request!');
			}
			
			if( $UserRequest->status == 'ACCEPTED' ||  $UserRequest->status == 'STARTED' ||  $UserRequest->status == 'ARRIVED'  || $UserRequest->status == 'PICKEDUP' || $UserRequest->status == 'PENDING' ) {
				
				$User = User::find( $UserRequest->user_id );
				
				if( ! $User ) {
					throw new Exception('User not found!');
				}
				
				if( $User->email != $request->email  ) {
					if ( User::where('email', $request->email)->first() ) {
						throw new Exception('User email already registered!');
					}
				}
				
				if( $User->mobile != $request->mobile  ) {
					if ( User::where('mobile', $request->mobile)->first() ) {
						throw new Exception('User Mobile already registered!');
					}
				}
				
				
				//Update User			
				$User->first_name 	= $request->first_name;
				$User->email 		= $request->email;
				$User->mobile 		= $request->mobile;
				$User->save();
				
				
				$R_coordiantes =  [ $UserRequest->s_latitude,  $UserRequest->s_longitude, $UserRequest->d_latitude, $UserRequest->d_longitude ];
				$F_coordinates =  [ $request->s_latitude, $request->s_longitude, $request->d_latitude, $request->d_longitude ];
				//Udate Request Info
				
				$flag = false;
				
				if( $R_coordiantes &&  $F_coordinates ) {
					foreach( $F_coordinates as $coord ) {
						if( in_array( $coord, $R_coordiantes )  === FALSE ) {
							$flag = true;
						}
					}
				}
				
				if( $flag ) {
					
					$fare = Helper::getEstimatedFare( $request->all() );			
					if( isset($fare['error']) ) {
						throw new Exception( $fare['error'] );
					}
					
					if( $fare ) {
						$UserRequest->s_latitude	=	$request->s_latitude;
						$UserRequest->s_longitude 	=	$request->s_longitude;
						$UserRequest->d_latitude 	=	$request->d_latitude;
						$UserRequest->d_longitude 	=	$request->d_longitude;
						$UserRequest->d_address 	=	$request->d_address;
						$UserRequest->s_address 	=	$request->s_address;
						$UserRequest->estimated_fare =	$fare['estimated_fare'];
						$UserRequest->distance 		=	$fare['distance'];
					}
				
				}
				
				$UserRequest->special_note 	=	$request->special_note;
				$UserRequest->save();
				$json['status'] = true;
				
				Log::useFiles(storage_path().'/logs/dispatcher.log');
				Log::info( $UserRequest->booking_id.' id updated by dispatcher panel');
			
			    return response()->json( $json );
			}
	
		}   catch (Exception $e) {
			
			return response()->json(['error' => $e->getMessage()]);
		} 
		
		
	}
	

    public function trips(Request $request)
    {
		$post = $request->all(); 
        $user_id = Auth::user()->id;
		
        if(isset($post['filter']) && $post['filter']!='' ){

            $filter = $post['filter']; 
            
			switch ($filter) {

				case 'dispatch-new':
					$status = ['PENDING'];
					break;
				case 'dispatch-dispatching':
					$status = ['PICKEDUP', 'ACCEPTED' ,'STARTED', 'ARRIVED', 'DROPPED' ];
					break;
				 case 'dispatch-cancelled':
					$status = ['CANCELLED'];
					break;
				 case 'dispatch-completed':
					$status = ['COMPLETED'];
					break;
				case 'dispatch-scheduled':
					$status = ['SCHEDULED'];
					break;
				case 'dispatcher-dead':
					$status = ['DEAD'];
					break;
				default:
					$status = ['SEARCHING']; 
					break;
        }          
		    $Trips = UserRequests::whereIn('status',$status)->with('user','provider','payment')->orderBy('id','desc')->get(); 
        } else {
            $Trips = UserRequests::with('user','provider','payment')->orderBy('id','desc')->get();
        }   
        return $Trips;
    }

    /**
     * Display a listing of the users in the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function users(Request $request)
    {
        $Users = new User;

        if($request->has('mobile')) {
            $Users->where('mobile', 'like', $request->mobile."%");
        }

        if($request->has('first_name')) {
            $Users->where('first_name', 'like', $request->first_name."%");
        }

        if($request->has('last_name')) {
            $Users->where('last_name', 'like', $request->last_name."%");
        }

        if($request->has('email')) {
            $Users->where('email', 'like', $request->email."%");
        }

        return $Users->paginate(10);
    }



     public function zones(Request $request)
    {
        $Zones = new Zones;

        if($request->has('name')) {
            $Zones->where('name', 'like', $request->name."%");
        }

        if($request->has('county')) {
            $Zones->where('county', 'like', $request->county."%");
        }

        if($request->has('province')) {
            $Zones->where('province', 'like', $request->province."%");
        }

        if($request->has('postcode_area')) {
            $Zones->where('postcode_area', 'like', $request->postcode_area."%");
        }

        return $Zones->paginate(10);
    }

    /**
     * Display a listing of the active trips in the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function providers(Request $request)
    {
        $Providers = new Provider;
		
		if( $request->has('latitude') && $request->has('longitude') && $request->has('service_type_id') ) {
		
			$Providers = Helper::availableProviders($request->service_type_id , $request->latitude , $request->longitude );
			
		}

        return $Providers;
    }

	
	public function providerList(Request $request) {
        $Providers = new Provider;

        if($request->has('s_latitude') && $request->has('s_longitude')) {
			$point[0]		=	$request->input('s_latitude'); 
			$point[1]		=	$request->input('s_longitude'); 
			$service_type	=	$request->service_type;
			$zone_id 		=	Helper::getLatlngZone_id( $point );
			if ( $zone_id ) {				
				$Providers = $this->getAvailableProviders( (int)$service_type, (int)$zone_id );
			}
	
        } else { 
		
			$Providers = $this->getAvailableProviders();
			
		}

        return $Providers;
    }
    /**
     * Create manual request.
     *
     * @return \Illuminate\Http\Response
     */
    public function assign(Request $request )
    {
		
		$this->validate($request, [
                'provider_id' => 'required|numeric',
                'request_id' => 'required|numeric',
            ]);
		
        try {
			
			$Request 	= UserRequests::findOrFail( $request->request_id );
            $Provider 	= Provider::findOrFail( $request->provider_id );
			
			$Providers	=	Helper::availableProviders($Request->service_type_id, $Request->s_latitude , $Request->s_longitude );
			
			$old_request = $Request;
			
			if( $request->cancel_status == 'assign' ) {
				
				if( $Request->status != 'SCHEDULED' ) {
					$Request->status = 'SEARCHING';
				}
				
				$Request->provider_id 			=	0;
				$Request->assigned_at 			=	Carbon::now();
				$Request->cancel_reason			=	$request->cancel_reason;
				$Request->current_provider_id 	=	$Provider->id;
				$Request->save();	
				
				
				Log::useFiles(storage_path().'/logs/dispatcher.log');
				Log::info( $Request->booking_id.' manually assigned to driver!' );
				
				
			} else if( $request->cancel_status == 'reassign' ) {
			
				$UserRequest 					= new UserRequests;
				$UserRequest->booking_id 		= Helper::generate_booking_id();
				$UserRequest->user_id 			= $Request->user_id;
				$UserRequest->dispatcher_id 	= ( isset($request->dispatcher_id) && !empty($request->dispatcher_id) ) ? $request->dispatcher_id : 0; 
				$UserRequest->req_type 			= $Request->req_type;
				$UserRequest->current_provider_id = $Provider->id; 			
				$UserRequest->service_type_id 	=	$Request->service_type_id;
				$UserRequest->payment_mode		=	$Request->payment_mode;
				$UserRequest->status 			=	( $Request->status  != 'SCHEDULED' ) ? 'SEARCHING' : 'SCHEDULED'; 
				$UserRequest->s_address 		=	$Request->s_address;
				$UserRequest->corporate_id		=	$Request->corporate_id;
				$UserRequest->amount_customer 	=	$Request->amount_customer;				
				$UserRequest->estimated_fare 	=	$Request->estimated_fare;
				$UserRequest->cancel_reason 	=	$request->cancel_reason;
				$UserRequest->special_note 		=	$request->special_note;
				$UserRequest->s_latitude 		=	$Request->s_latitude;
				$UserRequest->s_longitude 		=	$Request->s_longitude;

				$UserRequest->d_address 		=	$Request->d_address;
				$UserRequest->d_latitude 		=	$Request->d_latitude;
				$UserRequest->d_longitude 		=	$Request->d_longitude;
				$UserRequest->route_key 		=	$Request->route_key;
				$UserRequest->distance 			=	$Request->distance;
				$UserRequest->assigned_at 		=	Carbon::now();
				$UserRequest->use_wallet 		=	$Request->use_wallet;
				$UserRequest->req_zone_id		=	$Request->req_zone_id;
				$UserRequest->surge				=	$Request->surge;
				$UserRequest->payment_method_id =	$Request->payment_method_id;
				$UserRequest->schedule_at		=	$Request->schedule_at;
				
				$UserRequest->save();	
				$Request = $UserRequest;
				
				DB::table('user_requests')->where('id', $old_request->id )->delete();
				
				Log::useFiles(storage_path().'/logs/dispatcher.log');
				Log::info( $UserRequest->booking_id.' re-assign to  new driver!' );
		
			}
			
    
			$ids = DB::table('request_filters')->where('request_id', $old_request->id )->get()->pluck('id')->toArray();	
			DB::table('request_filters')->whereIn('id', $ids )->delete();
			ProviderService::where('provider_id',$old_request->provider_id)->update(['status' =>'active']);
		
			
			if( $Request->current_provider_id ) {
				if( $Request->status != 'SCHEDULED' ) {
					if( $Providers->count() ) {
						$inserted_data = [];
						foreach ($Providers as $Pr) {
							$inserted_data[] = [
								'request_id'	=> $Request->id,
								'provider_id'	=> $Pr->id
							];
						}
						
						if( $inserted_data ) {
							DB::table('request_filters')->insert( $inserted_data );
						}
						
					} else {
						
						$Filter = new RequestFilter;
						$Filter->request_id = $Request->id;
						$Filter->provider_id = $Provider->id; 
						$Filter->save();
						
					}
				
					(new SendPushNotification)->IncomingRequest($Request->current_provider_id);		
				}	
			}
			
			
			if($request->ajax()) {
				
				return response()->json(['message' => 'Request Assigned to Provider!'] );
			
			} else {
				
				if(Auth::guard('admin')->user()){
					return redirect()
							->route('admin.dispatcher.index')
							->with('flash_success', 'Request Assigned to Provider!');

				} elseif(Auth::guard('dispatcher')->user()){
					return redirect()
							->route('dispatcher.index')
							->with('flash_success', 'Request Assigned to Provider!');
				}
			}
			
        } catch (Exception $e) {
			
			if($request->ajax()) {
				
			    return response()->json(['message' => $e->getMessage()], 500);
			} else {
				
				if(Auth::guard('admin')->user()){
					return redirect()->route('admin.dispatcher.index')->with('flash_error', 'Something Went Wrong!');
				}elseif(Auth::guard('dispatcher')->user()){
					return redirect()->route('dispatcher.index')->with('flash_error', 'Something Went Wrong!');
				}
				
			}			
        }
    }


    /**
     * Create manual request.
     *
     * @return \Illuminate\Http\Response
     */


    public function store(Request $request) {

        $this->validate($request, [
                's_latitude' => 'required|numeric',
                's_longitude' => 'required|numeric',
                'd_latitude' => 'required|numeric',
                'd_longitude' => 'required|numeric',
                'service_type' => 'required|numeric|exists:service_types,id',
                'distance' => 'required|numeric',
            ]);
		
		
		if( $request->has('booking_type') && $request->booking_type == 2 ) {
			$this->validate($request, [
                'amount_customer' => 'required|numeric',
            ]);
		}
		 
		try {
			
            $User = User::where('mobile', $request->mobile)->firstOrFail();

        } catch (Exception $e) {
			
            try {
                $User = User::where('email', $request->email)->firstOrFail();
            } catch (Exception $e) {
                $User = User::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'password' => bcrypt($request->mobile),
                    'payment_mode' => 'CASH'
                ]);
            }
        }
		
		//Modified
		$ActiveRequests = UserRequests::PendingRequest( $User->id )->count();
		
		
		if($ActiveRequests > 0) {
			if($request->ajax()) {
				return response()->json(['flash_error' => trans('api.ride.request_inprogress')] );
			} else {
				return redirect('dashboard')->with('flash_error', 'Already request is in progress of this user. Try again later');
			}
		}
		
		
        if($request->has('schedule_time')) {
            
			try {
				
				$current = time();
				$schedule_time = strtotime( Carbon::parse( $request->schedule_time ) );
				$req_start = ( Setting::get('schedule_req_time') * 60 );
				$time = $current + $req_start;
				
				if( $schedule_time  < $time ) {
					if($request->ajax()) {
						return response()->json(['flash_error' => 'Please enter a schedule time as per admin guidelines!'] );
					} else {
						return redirect('dashboard')->with('flash_error', 'Please enter a schedule time as per admin guidelines!');
					}
				}
				
				
                $CheckScheduling = UserRequests::where('status', 'SCHEDULED')
                        ->where('user_id', $User->id)
                        ->where('schedule_at', '>', strtotime($request->schedule_time." - 1 hour"))
                        ->where('schedule_at', '<', strtotime($request->schedule_time." + 1 hour"))
                        ->firstOrFail();
                
			    if($request->ajax()) {
                    return response()->json(['error' => trans('api.ride.request_scheduled')], 500);
                } else {
                    return redirect('dashboard')->with('flash_error', 'Already request is Scheduled on this time.');
                }

            } catch (Exception $e) {
                // Do Nothing
            }
        }
        

        try{
			
            Session::set('DispatcherUserId', $User->id);
			$service_type = $request->service_type;
			
			if( $request->has('request_id') ) {
				$UserRequest = UserRequests::where('id' , $request->request_id )->where('status', 'SEARCHING')->first();
				$req_filters = DB::table('request_filters')->where('request_id', $UserRequest->id )->get()->pluck('id')->toArray();
				if( $req_filters ) {
					DB::table('request_filters')->whereIn('id', $req_filters)->delete();
				}
				
				$UserRequest->delete();	
			}
			
			
			$point[0]	=	$request->s_latitude; 
			$point[1]	=	$request->s_longitude;
			$zone_id	=	Helper::getLatlngZone_id( $point );
			
			$Providers	=	Helper::availableProviders($service_type, $point[0], $point[1]);
			
			
			
			if( ! $request->has('provider_auto_assign') ) {
				$availables_drivers = $Providers->pluck('id')->toArray();
				if( ! in_array($request->provider_id , $availables_drivers) ) {
					if($request->ajax()) {
						return response()->json(['flash_error' => 'Assigned provider not found in Zone! Please try again.' ]);
					} else {
						return back()->with('flash_error', 'Assigned provider not found in Zone! Please try again.');
					}
				}
			}
	

			
			$req_url = "https://maps.googleapis.com/maps/api/directions/json?origin=".$request->s_latitude.",".$request->s_longitude."&destination=".$request->d_latitude.",".$request->d_longitude."&mode=driving&key=".env('GOOGLE_MAP_KEY');
			$details =  (array) Helper::getDataByCurl( $req_url );
			$route_key = ( $details['status'] == 'OK' ) ?  $details['routes'][0]['overview_polyline']['points'] : '';
	

			
			$UserRequest = new UserRequests;
			$UserRequest->booking_id = Helper::generate_booking_id();
			$UserRequest->user_id = $User->id;
			$UserRequest->dispatcher_id =(isset($request->dispatcher_id) && !empty($request->dispatcher_id)) ?$request->dispatcher_id : 0;   ; 
			
			
			if($request->has('provider_auto_assign') ) {
				$UserRequest->current_provider_id = ( $Providers->count() ) ? $Providers[0]->id : 0; 
			} else {
				$UserRequest->req_type = 'MANUAL';
				$UserRequest->current_provider_id = $request->provider_id;
			}
		
			$UserRequest->service_type_id = $service_type;
			$UserRequest->payment_mode = 'CASH';
			
			
			$UserRequest->status =	( $Providers->count() ) ? 'SEARCHING' : 'PENDING';
			
			$UserRequest->s_address = $request->s_address ? : "";
			
			if( $request->has('booking_type') && $request->booking_type == 2 ) {
				
				$UserRequest->corporate_id = ( $request->corporate_id ) ? $request->corporate_id : 0; 
				$UserRequest->amount_customer = (int)$request->amount_customer;
				
			} else {
				$UserRequest->corporate_id = 0;
			}
			
			$UserRequest->estimated_fare 	=	$request->estimated_price;
			$UserRequest->special_note 		=	$request->special_note;
			$UserRequest->s_latitude 		=	$request->s_latitude;
			$UserRequest->s_longitude 		=	$request->s_longitude;

			$UserRequest->d_address = $request->d_address ? : "";
			$UserRequest->d_latitude = $request->d_latitude;
			$UserRequest->d_longitude = $request->d_longitude;
			$UserRequest->route_key = $route_key;

			$UserRequest->distance = $request->distance;
			
			if( $UserRequest->current_provider_id ) {
				
				$UserRequest->assigned_at = Carbon::now();
			}

			
			$UserRequest->use_wallet = 0;
			$UserRequest->req_zone_id = ($zone_id) ? $zone_id : 0;
			$UserRequest->surge = 0;        // Surge is not necessary while adding a manual dispatch
			$UserRequest->payment_method_id =  ( $request->payment_method ) ? $request->payment_method : 0 ;
			
			if($request->has('schedule_time')) {
				$UserRequest->schedule_at = Carbon::parse($request->schedule_time);
				$UserRequest->status = 'SCHEDULED';
			}
			
			$UserRequest->save();
			
			Log::useFiles(storage_path().'/logs/dispatcher.log');
			Log::info('New Request Created by dispatcher : ' . $UserRequest->booking_id);
		
			
			if( $UserRequest->current_provider_id ) {
				if( $UserRequest->status != 'SCHEDULED' ) {
					if( $Providers->count() ) {
						$inserted_data = [];
						foreach ($Providers as $Provider) {
							$inserted_data[] = [
								'request_id'	=> $UserRequest->id,
								'provider_id'	=> $Provider->id
							];
						}
						
						if( $inserted_data ) {
							DB::table('request_filters')->insert( $inserted_data );
						}
						
					}
					
					if( ! $request->has('provider_auto_assign') ) {
						$Filter = new RequestFilter;
						$Filter->request_id = $UserRequest->id;
						$Filter->provider_id = $UserRequest->current_provider_id; 
						$Filter->save();
					}
					
					(new SendPushNotification)->IncomingRequest($UserRequest->current_provider_id);
					
				}
				
			} else {
				
				(new SendPushNotification)->ProviderNotAvailable($UserRequest->user_id);
				
			}
			
			if( $UserRequest) {
				return ( $request->ajax() ) ? $UserRequest : redirect('dashboard');
			} else {
                return redirect('dashboard');
            }
			

        } catch (Exception $e) {
			
            if($request->ajax()) {
                return response()->json(['message' => $e->getMessage()], 500);
            }else{
                return back()->with('flash_error', $e->getMessage() );
            }
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
        return view('dispatcher.account.profile');
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
            return back()->with('flash_error', 'Disabled for demo purposes! Please contact us at info@swver.com');
        }

        $this->validate($request,[
            'name' => 'required|max:255',
            'mobile' => 'required|digits_between:6,13',
        ]);

        try{
            $dispatcher = Auth::guard('dispatcher')->user();
            $dispatcher->name = $request->name;
            $dispatcher->mobile = $request->mobile;
            $dispatcher->save();

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
        return view('dispatcher.account.change-password');
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

           $Dispatcher = Dispatcher::find(Auth::guard('dispatcher')->user()->id);

            if(password_verify($request->old_password, $Dispatcher->password))
            {
                $Dispatcher->password = bcrypt($request->password);
                $Dispatcher->save();

                return redirect()->back()->with('flash_success','Password Updated');
            }
        } catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong!');
        }
    }


     public function map_index()
    {
		$data 				=	array();
		$zones 				=	Zones::all()->toArray();
		$data['postcoes']	=   Zones::all()->pluck('postcode_area', 'id')->toArray();
		$data['zones']		=	$this->getZonesWithProvider();
	
        return view('dispatcher.map.index', ['data' => $data ]);
    }
	
	
    /**
     * Map of all Users and Drivers.
     *
     * @return \Illuminate\Http\Response liveMap_ajax
     */
    public function map_ajax(Request $request)
    {
    	 	
        try {
			return Provider::with('service')
						->whereHas('service', function( $query ) use ($request) {
							if($request->header('driver')=='true'){
								$query->where('provider_services.status', 'active');
							}elseif($request->header('ongoing')=='true'){
								$query->where('provider_services.status', 'riding');
							}else{
								$query->where('provider_services.status', 'active')
									->orWhere('provider_services.status', 'riding');
							}
						
						})->where('latitude', '!=', 0)->where('longitude', '!=', 0)
						->where('providers.status', 'approved')->get();
						
        }   catch (Exception $e) {
            return [];
        }
    }


    public function get_ride_fare(Request $request)
    {
        $this->validate($request,[
                's_latitude' => 'required|numeric',
                's_longitude' => 'required|numeric',
                'd_latitude' => 'required|numeric',
                'd_longitude' => 'required|numeric',
                'service_type' => 'required|numeric|exists:service_types,id',
            ]);

        try{
			
			$result = Helper::getEstimatedFare( $request->all() );
			
			if( isset($result['error']) ) {
				throw new Exception( serialize(['error'=> $result['error'] ] ) );
			}
			
			if($request->ajax()) {
                return response()->json( $result );
            }else{
                return $result;
            }
			
			
            
			

			
        } catch(Exception $e) {
			
			$errors = unserialize($e->getMessage());
			
			if($request->ajax()) {
                return response()->json($errors , 500);
            }else{
                return back()->with('flash_error', $errors['error'] );
            }
			
            
        }
        
 
    }

     public function singleTrip(Request $request)
    {
        
		$user_id = Auth::user()->id;
		 $model =  new UserRequests();
		// echo $user_id;die;
		$Trips   = $model->where('dispatcher_id',$user_id)->orderBy('id','desc')->first();

		if(!empty($Trips)){
			return response()->json(['status'=>1,'msg'=>'get data','data'=>$Trips]);
		}else{
			return response()->json(['status'=>0,'msg'=>'no record found','data'=>'']);
		}
    }
	
	function trip_data(Request $request ) {
		
		$trip = array();
		if( $request->input('trip_id') ) {
			$trip = UserRequests::where('id', $request->input('trip_id') )->first()->toArray();
			return $trip;
		} else {
			return $trip;
		}
	}
	
	
	public function getZonesWithProvider() {
		
		$data_zones = array();
		$zones	=	Zones::all()->toArray();
		
		if( $zones ) {
			
			foreach( $zones  as  $zone ) {
				
				$drivers	= DB::table('providers')
										->join('provider_zone', 'provider_zone.driver_id', '=', 'providers.id')
										->join('provider_services', 'provider_services.provider_id', '=', 'providers.id')
										->select(DB::raw('providers.*, DATE_FORMAT(provider_zone.created_at , "%b %d %h:%i %p") as enter_time, provider_zone.id as driver_position, provider_services.service_number, provider_services.service_model, provider_services.status as provider_status') )
										->where('provider_zone.zone_id', $zone['id'])
										->orderBy('provider_zone.id', 'asc')->get()->toArray();
				
				$zone['drivers'] = $drivers;
				$zone['coordinate'] = unserialize( $zone['coordinate'] );
				$data_zones[] = $zone;
			}			
		}		
		return $data_zones;		
	}
	
	
	public function getAvailableProviders($service_type = 0, $zone_id = 0) {
		
		$Providers = new Provider;
		
		if($service_type && $zone_id) {
			
			$Providers = DB::table('providers')
			->join('provider_zone', 'provider_zone.driver_id', '=', 'providers.id')
			->join('provider_services', 'provider_services.provider_id', '=', 'providers.id')
			->select(DB::raw('providers.*, provider_zone.created_at as enter_time, provider_zone.id as driver_position, provider_services.service_number, provider_services.service_model, provider_services.service_type_id, provider_services.status AS provider_current_status') )
			->where('providers.status', 'approved')
			->where('provider_services.status', 'active')
			->where('provider_services.service_type_id', $service_type)	
			->where('provider_zone.zone_id', $zone_id )
			->orderBy('provider_zone.id')
			->get();
			
		} else {
			
			$Providers = DB::table('providers')
			->join('provider_zone', 'provider_zone.driver_id', '=', 'providers.id')
			->join('provider_services', 'provider_services.provider_id', '=', 'providers.id')
			->select(DB::raw('providers.*, provider_zone.created_at as enter_time, provider_zone.id as driver_position, provider_services.service_number, provider_services.service_model, provider_services.service_type_id, provider_services.status AS provider_current_status') )
			->where('providers.status', 'approved')
			->where('provider_services.status', 'active')
			->orderBy('provider_zone.id' )
			->get();
			
		}
		
		return $Providers;

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

	
	function getUserDetail(Request $request ) {
		$user = [];
		
		if( $request->has('id') ) {
			if( $u = User::find( $request->id ) ) {
				$user = [
					'id'			=>	$u->id,
					'first_name'	=>	$u->first_name,
					'last_name'		=>	$u->last_name,
					'email'			=>	$u->email,
					'mobile'		=>	$u->mobile
				];
			}
		}		
		return $user;		
	}
	
	
	function getlogs() {
		return file( storage_path('logs/dispatcher.log') );
	}
	
	
	public function  test( ) {
		
		$data['service_type'] = 1;
		$data['s_latitude'] = 30.033333;
		$data['s_longitude'] = 31.233334;
		$data['d_latitude'] = 30.033333;
		$data['d_longitude'] = 31.233334;
		
		$result =	Helper::getEstimatedFare( $data );
	}

	public function openTicket($type){
        
        $mytime = Carbon::now();

		if($type == 'new'){

            $data = Complaint::whereDate('created_at',$mytime->toDateString())->where('transfer',2)->where('status',1)->get();
            $title = 'New Ticket';
		}
		if($type == 'open'){

		    $data = Complaint::where('transfer',2)->where('status',1)->get();
		     $title = 'Open Ticket';
		}

        return view('dispatcher.open_ticket', compact('data','title'));
    }
    public function closeTicket(){

        $data = Complaint::where('transfer',2)->where('status',0)->get();

        return view('dispatcher.close_ticket', compact('data'));
    }
    public function openTicketDetail($id){
        $data = Complaint::where('id',$id)->first();
        return view('dispatcher.open_ticket_details', compact('data'));
    }
        public function lost_management(){
        $data = LostItem::get();
        return view('crm.lost_management', compact('data'));
    }

    public function transfer($id,Request $request){

        $data = Complaint::where('id',$id)->first();
        $data->status = $request->status;
        $data->transfer = $request->transfer;
        $data->reply = $request->reply;
        $data->save();
        return redirect()->back()->with('flash_success','Ticket Updated');
       
    }
	
}

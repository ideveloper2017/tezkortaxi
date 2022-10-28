<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use DB;
use Exception;
use Setting;
use Storage;

use App\Provider;
use App\Document;
use App\ServiceType;
use App\ProviderService;
use App\UserRequestPayment;
use App\UserRequests;
use App\Helpers\Helper;

class ProviderResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $AllProviders = Provider::with('service','accepted','cancelled', 'documents')
                    ->orderBy('id', 'DESC');
		
        if(request()->has('fleet')){
            $providers = $AllProviders->where('fleet',$request->fleet)->get();
        }else{
            $providers = $AllProviders->get();
        }
		 
		
        return view('admin.providers.index', compact('providers', 'documents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		
		$services = ServiceType::all();
		
        return view('admin.providers.create', compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error', 'Disabled for demo purposes! Please contact us at Admin.com');
        }

        $this->validate($request, [
            'first_name' 	=> 'required|max:255',
            //'last_name' 	=> 'required|max:255',
            'email' 		=> 'required|unique:providers,email|email|max:255',
            'mobile' 		=> 'digits_between:6,13',
            //'avatar' 		=> 'mimes:jpeg,jpg,bmp,png|max:5242880',
            'password' 		=> 'required|min:6|confirmed',
			'service_type'	=> 'required',
			'service_number'=>	'required',
			'service_model'	=>	'required',
			
        ]);

        try{

            $provider = $request->all();

            $provider['password'] = bcrypt($request->password);
            if($request->hasFile('avatar')) {
                $provider['avatar'] = $request->avatar->store('provider/profile');
            }

            $Provider = Provider::create($provider);
			
			if( $Provider ) {
				
				$provider_service = ProviderService::create([
					'provider_id' 	=> $Provider->id,
					'status'		=> 'offline',	
					'service_type_id' => $provider['service_type'],
					'service_number' => $provider['service_number'],
					'service_model' => $provider['service_model'],
                    'service_year' => isset($provider['service_year']) ? $provider['service_year'] : '',
                    'service_make' => isset($provider['service_make']) ? $provider['service_make'] : '',
                    'service_name' => isset($provider['service_name']) ? $provider['service_name'] : '',
                    'service_ac' => isset($provider['service_ac']) ? $provider['service_ac'] : '',
				]);
		
				
				
			}
			
            return back()->with('flash_success','Driver Details Saved Successfully');

        } 

        catch (Exception $e) {
            return back()->with('flash_error', 'Driver Not Found');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $provider = Provider::findOrFail($id);
            return view('admin.providers.provider-details', compact('provider'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try { 
			
            $provider = Provider::with('service')
						->whereHas('service', function( $query ) use($id) {
							$query->where('provider_id', $id );
						})->where('id', $id)->first();
			
			if( ! $provider ) {
				throw new Exception('Driver Not Found!');
			}
			
            $services = ServiceType::all();
			
			
			//$provder_service = ProviderService::where('');
			return view('admin.providers.edit',compact('provider', 'services'));
        } catch (Exception $e) {
			 return back()->with('flash_error',  $e->getMessage() );
			//return back()->with( $e->getMessage() );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error', 'Disabled for demo purposes! Please contact us at info@rommoz.com');
        }

        $this->validate($request, [
            'first_name' => 'required|max:255',
            //'last_name' => 'required|max:255',
            'mobile' => 'digits_between:6,17',
            //'avatar' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
			'service_type'	=> 'required',
			'service_number'=>	'required',
			'email'	=> 'required',
			//'password'	=> 'confirmed|min:6',
			'service_number'=>	'required',
			'service_model'	=>	'required',
        ]);

        try {

            $provider = Provider::findOrFail($id);
			if( !$provider  ) {
				throw new Exception('Driver Not Found');
			}
			
			$provider_service = ProviderService::where('provider_id', $id)->first();
			if( !$provider_service ) {
				throw new Exception('Driver Service Not Found');
			}
			
			
            if($request->hasFile('avatar')) {
                if($provider->avatar) {
                    Storage::delete($provider->avatar);
                }
                $provider->avatar = $request->avatar->store('provider/profile');                    
            }

            $provider->first_name = $request->first_name;
            //$provider->last_name = $request->last_name;
            $provider->mobile = $request->mobile;
            $provider->email = $request->email;
            if($request->password!="")
            {
                $provider->password = bcrypt($request->password);
            }
            $provider->save();

			$provider_service->service_type_id = $request->service_type;
			$provider_service->service_number = $request->service_number;
			$provider_service->service_model = $request->service_model;
            $provider_service->service_year = $request->service_year;
            $provider_service->service_make = $request->service_make;
            $provider_service->service_name = $request->service_name;
            $provider_service->service_ac = $request->service_ac;
			
			$provider_service->save();
			
			return redirect()->route('admin.provider.index')->with('flash_success', 'Driver Updated Successfully');    
        } 

        catch (Exception $e) {
            return back()->with('flash_error', $e->getMessage() );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error', 'Disabled for demo purposes! Please contact us at info@rommoz.com');
        }

        try {
			
            Provider::find($id)->delete();
			
			ProviderService::where('provider_id', $id)->delete();
			DB::table('provider_devices')->where('provider_id', $id)->delete();
			
			$provider_documents = DB::table('provider_documents')->where('provider_id', $id)->get();
			
			if( $provider_documents ) {
				
			}
			
			DB::table('provider_profiles')->where('provider_id', $id)->delete();
			DB::table('provider_zone')->where('driver_id', $id)->delete();
			
            return back()->with('message', 'Driver deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'Driver Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function approve($id)
    {
        try {
            $Provider = Provider::findOrFail($id);
            if($Provider->service) {
                $Provider->update(['status' => 'approved']);
                return back()->with('flash_success', "Driver Approved");
            } else {
                return redirect()->route('admin.provider.document.index', $id)->with('flash_error', "Driver has not been assigned a vehicle!");
            }
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', "Something went wrong! Please try again later.");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function disapprove($id)
    {
        if(Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error', 'Disabled for demo purposes! Please contact us at info@rommoz.com');
        }
        
        Provider::where('id',$id)->update(['status' => 'banned']);
        return back()->with('flash_success', "Driver Disapproved");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function request($id){

        try{

            $requests = UserRequests::where('user_requests.provider_id',$id)->RequestHistory()->get();

            return view('admin.request.index', compact('requests'));
        }   catch (Exception $e) {
            return back()->with('flash_error','Something Went Wrong!');
        }
    }

    /**
     * account statements.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function statement($id){
    
        try{

            $requests = UserRequests::where('provider_id',$id)
                        ->where('status','COMPLETED')
                        ->with('payment')
                        ->get();

            $rides = UserRequests::where('provider_id',$id)->with('payment')->orderBy('id','desc')->paginate(10);
            $cancel_rides = UserRequests::where('status','CANCELLED')->where('provider_id',$id)->count();
            $Provider = Provider::find($id);
            $revenue = UserRequestPayment::whereHas('request', function($query) use($id) {
                                    $query->where('provider_id', $id );
                                })->select(\DB::raw(
                                   'SUM(ROUND(fixed) + ROUND(distance)) as overall, SUM(ROUND(commision)) as commission' 
                               ))->get();

            $revenues = UserRequestPayment::sum('total');
            $commision = UserRequestPayment::sum('commision');
            $Joined = $Provider->created_at ? '- Joined '.$Provider->created_at->diffForHumans() : '';

            return view('admin.providers.statement', compact('rides','cancel_rides','revenue','revenues','commision'))
                        ->with('page',$Provider->first_name."'s Overall Statement ". $Joined);

        } catch (Exception $e) {
            return back()->with('flash_error','Something Went Wrong!');
        }
    }

    public function Accountstatement($id){

        try{

            $requests = UserRequests::where('provider_id',$id)
                        ->where('status','COMPLETED')
                        ->with('payment')
                        ->get();

            $rides = UserRequests::where('provider_id',$id)->with('payment')->orderBy('id','desc')->paginate(10);
            $cancel_rides = UserRequests::where('status','CANCELLED')->where('provider_id',$id)->count();
            $Provider = Provider::find($id);
            $revenue = UserRequestPayment::whereHas('request', function($query) use($id) {
                                    $query->where('provider_id', $id );
                                })->select(\DB::raw(
                                   'SUM(ROUND(fixed) + ROUND(distance)) as overall, SUM(ROUND(commision)) as commission' 
                               ))->get();


            $Joined = $Provider->created_at ? '- Joined '.$Provider->created_at->diffForHumans() : '';

            return view('account.providers.statement', compact('rides','cancel_rides','revenue'))
                        ->with('page',$Provider->first_name."'s Overall Statement ". $Joined);

        } catch (Exception $e) {
            return back()->with('flash_error','Something Went Wrong!');
        }
    }
}

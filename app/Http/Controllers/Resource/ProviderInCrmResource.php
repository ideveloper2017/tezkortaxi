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

class ProviderInCrmResource extends Controller
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
		$totaldrivers = Provider::count();
		$totaltrips = UserRequests::count();
		$totalcanceltrips = UserRequests::where('status', 'CANCELLED')->count(); 
        if(request()->has('fleet')){
            $providers = $AllProviders->where('fleet',$request->fleet)->get();
        }else{
            $providers = $AllProviders->get();
        }
		 
        return view('crm.providers.index', compact(['providers', 'documents', 'totaldrivers', 'totaltrips', 'totalcanceltrips']));
		
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		
		$services = ServiceType::all();
		
        return view('crm.providers.create', compact('services'));
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
            return back()->with('flash_error', 'Disabled for demo purposes! Please contact us at crm.com');
        }

        $this->validate($request, [
            'first_name' 	=> 'required|max:255',
            // 'last_name' 	=> 'required|max:255',
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
				]);
		
				
				
			}
			
            return back()->with('flash_success','Provider Details Saved Successfully');

        } 

        catch (Exception $e) {
            return back()->with('flash_error', 'Provider Not Found');
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
            return view('crm.providers.provider-details', compact('provider'));
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
			
			$services = ServiceType::all();
           $provider = Provider::with('service')
						->whereHas('service', function( $query ) use($id) {
							$query->where('provider_id', $id );
						})->where('id', $id)->first();
			
			if( ! $provider ) {
				$provider = Provider::findOrFail($id);
			}
			
			if( ! $provider ) {
				throw new Exception('Provider Not Found!');
			}
			
			
			//$provder_service = ProviderService::where('');
			return view('crm.providers.edit',compact('provider', 'services'));
        } catch (Exception $e) {
            return redirect('crm/provider')->with('flash_error', $e->getMessage() );
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
            'mobile' => 'digits_between:6,13',
           // 'avatar' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
			'service_type'	=> 'required',
			'service_number'=>	'required',
			'service_model'	=>	'required',
        ]);

        try {

            $provider = Provider::findOrFail($id);
			if( !$provider  ) {
				throw new Exception('Provider Not Found');
			}
			
			$provider_service = ProviderService::where('provider_id', $id)->first();
			if( !$provider_service ) {
					$provider_service =  new ProviderService;
			}
			
			
            if($request->hasFile('avatar')) {
                if($provider->avatar) {
                    Storage::delete($provider->avatar);
                }
                $provider->avatar = $request->avatar->store('provider/profile');                    
            }

            $provider->first_name = $request->first_name;
            $provider->last_name = '';//$request->last_name;
            $provider->mobile = $request->mobile;
            $provider->email = $request->email;
            $provider->save();

			
			$provider_service->service_type_id = $request->service_type;
			$provider_service->provider_id = $provider->id;
			$provider_service->service_number = $request->service_number;
			$provider_service->service_model = $request->service_model;
			
			$provider_service->save();
			
			return redirect()->route('crm.provider.index')->with('flash_success', 'Provider Updated Successfully');    
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
			
            return back()->with('message', 'Provider deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'Provider Not Found');
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
                return back()->with('flash_success', "Provider Approved");
            } else {
                return redirect()->route('crm.provider.document.index', $id)->with('flash_error', "Provider has not been assigned a service type!");
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
        return back()->with('flash_success', "Provider Disapproved");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function request($id){

        try{

            $requests = UserRequests::where('user_requests.provider_id',$id)
                    ->RequestHistory()
                    ->get();

            return view('crm.request.index', compact('requests'));
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


            $Joined = $Provider->created_at ? '- Joined '.$Provider->created_at->diffForHumans() : '';

            return view('crm.providers.statement', compact('rides','cancel_rides','revenue'))
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

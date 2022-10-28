<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use DB;
use Exception;
use Auth;
use App\ServiceType;
use App\Provider;
use App\UserRequests;
use App\Helpers\Helper;
use App\ProviderService;

class ProviderFleetResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $providers = Provider::with('service','accepted','cancelled')
                    ->where('fleet', Auth::user()->id )
                    ->orderBy('id', 'DESC')
                    ->get();

        return view('fleet.providers.index', compact('providers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('fleet.providers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|max:255',
            // 'last_name' => 'required|max:255',
            'email' => 'required|unique:providers,email|email|max:255',
            'mobile' => 'digits_between:6,13',
            'avatar' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
            'password' => 'required|min:6|confirmed',
        ]);

        try{

            $provider = $request->all();

            $provider['password'] = bcrypt($request->password);
            $provider['fleet'] = Auth::user()->id;
            if($request->hasFile('avatar')) {
                $provider['avatar'] = $request->avatar->store('provider/profile');
            }

            $provider = Provider::create($provider);

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
            return view('fleet.providers.provider-details', compact('provider'));
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
            /*$provider = Provider::findOrFail($id);*/
            $provider = Provider::with('service')
                        ->whereHas('service', function( $query ) use($id) {
                            $query->where('provider_id', $id );
                        })->where('id', $id)->first();
            
            if( ! $provider ) {
                throw new Exception('Driver Not Found!');
            }
            
            $services = ServiceType::all();
            return view('fleet.providers.edit',compact('provider','services'));
        } catch (ModelNotFoundException $e) {
            return $e;
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
        $this->validate($request, [
            'first_name' => 'required|max:255',
            // 'last_name' => 'required|max:255',
            'mobile' => 'digits_between:6,13',
            'avatar' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
            'service_type'  => 'required',
            'service_number'=>  'required',
            'email' => 'required',
            'service_model' =>  'required'
        ]);

        try {

            $provider = Provider::findOrFail($id);
            if( !$provider  ) {
                throw new Exception('Provider Not Found');
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
            // $provider->last_name = $request->last_name;
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
            $provider_service->save();

            return redirect()->route('fleet.provider.index')->with('flash_success', 'Driver Updated Successfully');    
        } 

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Driver Not Found');
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
        try {
            Provider::find($id)->delete();
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
                return back()->with('flash_success', "Provider Approved");
            } else {
                return redirect()->route('fleet.provider.document.index', $id)->with('flash_error', "Driver has not been assigned a vehicle!");
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

            return view('fleet.request.index', compact('requests'));
        } catch (Exception $e) {
            return back()->with('flash_error','Something Went Wrong!');
        }
    }
}

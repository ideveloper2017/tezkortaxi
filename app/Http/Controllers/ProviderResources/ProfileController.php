<?php

namespace App\Http\Controllers\ProviderResources;

use App\BankAccount;
use App\Document;
use App\ProviderDocument;
use App\ServiceType;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Http\Controllers\Controller;

use Auth;
use Setting;
use Storage;
use Exception;
use Carbon\Carbon;
use App\Provider;
use App\ProviderProfile;
use App\UserRequests;
use App\ProviderService;
use App\Fleet;
use App\UserRequestPayment;

class ProfileController extends Controller
{
    /**
     * Create a new user instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('provider.api', ['except' => ['show', 'store', 'available', 'location_edit', 'location_update']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            Auth::user()->service = ProviderService::where('provider_id',Auth::user()->id)
                                            ->with('service_type')
                                            ->first();
            Auth::user()->fleet = Fleet::find(Auth::user()->fleet);
            Auth::user()->currency = Setting::get('currency', '$');
            Auth::user()->sos = Setting::get('sos_number', '911');

            return Auth::user();

        }   catch (Exception $e) {
            return $e->getMessage();
        }
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
                'mobile' => 'required',
                'avatar' => 'mimes:jpeg,bmp,png',
                'language' => 'max:255',
                'address' => 'max:255',
                'address_secondary' => 'max:255',
                'city' => 'max:255',
                'country' => 'max:255',
                'postal_code' => 'max:255',
                'description'   => 'max:1000', 
            ]);

        try {

            $Provider = Auth::user();

            if($request->has('first_name')) 
                $Provider->first_name = $request->first_name;

            // if($request->has('last_name')) 
            //     $Provider->last_name = $request->last_name;

            if ($request->has('mobile'))
                $Provider->mobile = $request->mobile;

            if ($request->hasFile('avatar')) {
                Storage::delete($Provider->avatar);
                $Provider->avatar = $request->avatar->store('provider/profile');
            }

            if($request->has('service_type')) {
                if($Provider->service) {
                    if($Provider->service->service_type_id != $request->service_type) {
                        $Provider->status = 'banned';
                    }
                    //$ProviderService = ProviderService::find(Auth::user()->id);
                    $ProviderService = ProviderService::find($Provider->service->id);
                    $ProviderService->service_type_id = $request->service_type;
                    $ProviderService->service_number = $request->service_number;
                    $ProviderService->service_model = $request->service_model;
                    $ProviderService->service_year = $request->service_year;
                    $ProviderService->service_make = $request->service_make;
                    $ProviderService->service_name = $request->service_name;
                    $ProviderService->service_color = $request->service_color;
                    $ProviderService->service_ac = $request->service_ac;
                    $ProviderService->save();

                } else {
                    ProviderService::create([
                        'provider_id' => $Provider->id,
                        'service_type_id' => $request->service_type,
                        'service_number' => $request->service_number,
                        'service_model' => $request->service_model,
                        'service_year' => $request->service_year,
                        'service_make' => $request->service_make,
                        'service_name' => $request->service_name,
                        'service_ac' => $request->service_ac,
                        'service_color' => $request->service_color,
                    ]);
                    $Provider->status = 'banned';
                }
            }

            if($Provider->profile) {
                $Provider->profile->update([
                        'language' => $request->language ? : $Provider->profile->language,
                        'address' => $request->address ? : $Provider->profile->address,
                        'address_secondary' => $request->address_secondary ? : $Provider->profile->address_secondary,
                        'city' => $request->city ? : $Provider->profile->city,
                        'country' => $request->country ? : $Provider->profile->country,
                        'postal_code' => $request->postal_code ? : $Provider->profile->postal_code,
                        'description' => trim($request->description) ? : $Provider->profile->description,
                    ]);
            } else {
                ProviderProfile::create([
                        'provider_id' => $Provider->id,
                        'language' => $request->language,
                        'address' => $request->address,
                        'address_secondary' => $request->address_secondary,
                        'city' => $request->city,
                        'country' => $request->country,
                        'postal_code' => $request->postal_code,
                        'description' => trim($request->description),
                    ]);
            }


            $Provider->save();

            return redirect(route('provider.profile.index'));
        }

        catch (ModelNotFoundException $e) {
            // (dd($e->getMessage()));
            return response()->json(['error' => 'Driver Not Found!'], 404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $fully = UserRequests::where('provider_id',\Auth::guard('provider')->user()->id)
                    ->with('payment','service_type')
                    ->get();

        $status = ( Auth::guard('provider')->user()->status == 'approved' ) ? 1 : 0;

        return view('provider.profile.profile',compact('fully', 'status' ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
                'first_name' => 'required|max:255',
                // 'last_name' => 'required|max:255',
                'mobile' => 'required',
                'avatar' => 'mimes:jpeg,bmp,png',
                'language' => 'max:255',
                'address' => 'max:255',
                'address_secondary' => 'max:255',
                'city' => 'max:255',
                'country' => 'max:255',
                'postal_code' => 'max:255',
                'description'   => 'max:1000', 
            ]);

        try {

            $Provider = Auth::user();

            if($request->has('first_name')) 
                $Provider->first_name = $request->first_name;

            if($request->has('last_name')) 
                $Provider->last_name = $request->last_name;

            if ($request->has('mobile'))
                $Provider->mobile = $request->mobile;

            if ($request->hasFile('avatar')) {
                Storage::delete($Provider->avatar);
                $Provider->avatar = $request->avatar->store('provider/profile');
            }

            if($Provider->profile) {
                $Provider->profile->update([
                        'language' => $request->language ? : $Provider->profile->language,
                        'address' => $request->address ? : $Provider->profile->address,
                        'address_secondary' => $request->address_secondary ? : $Provider->profile->address_secondary,
                        'city' => $request->city ? : $Provider->profile->city,
                        'country' => $request->country ? : $Provider->profile->country,
                        'postal_code' => $request->postal_code ? : $Provider->profile->postal_code,
                        'description' => $request->description ? : $Provider->profile->description,
                    ]);
            } else {
                ProviderProfile::create([
                        'provider_id' => $Provider->id,
                        'language' => $request->language,
                        'address' => $request->address,
                        'address_secondary' => $request->address_secondary,
                        'city' => $request->city,
                        'country' => $request->country,
                        'postal_code' => $request->postal_code,
                        'description' => $request->description,
                    ]);
            }


            $Provider->save();

            return $Provider;
            
        }

        catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Driver Not Found!'], 404);
        }
    }

    /**
     * Update latitude and longitude of the user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function location(Request $request)
    {
        $this->validate($request, [
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
            ]);

        if($Provider = Auth::user()){

            $Provider->latitude = $request->latitude;
            $Provider->longitude = $request->longitude;
            $Provider->save();

            return response()->json(['message' => 'Location Updated successfully!']);

        } else {
            return response()->json(['error' => 'Driver Not Found!']);
        }
    }

    /**
     * Toggle service availability of the provider.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function available(Request $request)
    {
        $this->validate($request, [
                'service_status' => 'required|in:active,offline',
            ]);

        $Provider = Auth::user();
        
        if($Provider->service) {
            $Provider->service->update(['status' => $request->service_status]);
        } else {
            return response()->json(['error' => 'You account has not been approved for driving']);
        }

        return $Provider;
    }

    /**
     * Update password of the provider.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function password(Request $request)
    {
        $this->validate($request, [
                'password' => 'required|confirmed',
                'password_old' => 'required',
            ]);

        $Provider = Auth::user();

        if(password_verify($request->password_old, $Provider->password))
        {
            $Provider->password = bcrypt($request->password);
            $Provider->save();

            return response()->json(['message' => 'Password changed successfully!']);
        } else {
            return response()->json(['error' => 'Please enter correct password'], 422);
        }
    }

    /**
     * Show providers daily target.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     
    /*public function target(Request $request)
    {
       
        try{
 
            $Providers = Provider::all();

            foreach($Providers as $index => $Provider)
            {
                
                if($Provider->id==Auth::user()->id)
                {
                    $Rides = UserRequests::where('provider_id',$Provider->id)
                            ->where('status','<>','CANCELLED')
                            ->get()->pluck('id');

                    $Providers[1]->rides_count = $Rides->count();
    
                    $Providers[1]->payment = UserRequestPayment::whereIn('request_id', $Rides)
                                    ->select(\DB::raw(
                                       'SUM(ROUND(fixed) + ROUND(distance)) as overall, SUM(ROUND(commision)) as commission' 
                                    ))->get();
                }
                
            }
            
            return response()->json([
                    'rides' => $Rides,
                    'rides_count' => $Providers,
                    'target' => Setting::get('daily_target','0'),
					'user' => Auth::user()->id,
                ]);

            //return view('admin.providers.provider-statement', compact('Providers'))->with('page','Providers Statement');

        } catch (Exception $e) {
            return back()->with('flash_error','Something Went Wrong!');
        }
    }*/
    public function target(Request $request)
    {
        try {
            
            $Ridesdata = UserRequests::where('provider_id', Auth::user()->id)
                    ->where('status', 'COMPLETED')
                    //->where('created_at', '>=', Carbon::today())
                    ->with('payment', 'service_type')
                    ->get();
                    
            $Rides = UserRequests::where('provider_id',Auth::user()->id)
                            ->where('status','<>','CANCELLED')
                            ->get()->pluck('id');

            //$Providers->rides_count = $Rides->count();

           /* $Providers  = UserRequestPayment::whereIn('request_id', $Rides)
                            ->select(\DB::raw(
                               'SUM(ROUND(fixed) + ROUND(distance)) as overall, SUM(ROUND(commision)) as commission' 
                            ))->get();
                            */
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
            
            //dd($Providers);
            return response()->json([
                    'rides' => $Ridesdata,
                    'rides_count' => ['overall'=>round($totalEarn,2),'commission'=>round($commision,2)],
                    'target' => Setting::get('daily_target','0'),
					'user' => Auth::user()->id,
                ]);

        } catch(Exception $e) {
            return response()->json(['error' => "Something Went Wrong"]);
        }
    }

    public function updateProfile(Request $request)
    {
        $this->validate($request, [
            'avatar' => 'mimes:jpeg,bmp,png',
            'address' => 'max:255',
            'city' => 'max:255',
            'country' => 'max:255',
            'postal_code' => 'max:255',
            'state' => 'max:255',
        ]);

        try {

            $Provider = Auth::user();

            if ($request->hasFile('avatar')) {
                Storage::delete($Provider->avatar);
                $Provider->avatar = $request->avatar->store('provider/profile');
            }

            if($Provider->profile) {
                $Provider->profile->update([
                    'language' => $request->language ? : $Provider->profile->language,
                    'address' => $request->address ? : $Provider->profile->address,
                    'address_secondary' => $request->address_secondary ? : $Provider->profile->address_secondary,
                    'city' => $request->city ? : $Provider->profile->city,
                    'country' => $request->country ? : $Provider->profile->country,
                    'postal_code' => $request->postal_code ? : $Provider->profile->postal_code,
                    'description' => $request->description ? : $Provider->profile->description,
                ]);
            } else {
                ProviderProfile::create([
                    'provider_id' => $Provider->id,
                    'language' => $request->language,
                    'address' => $request->address,
                    'address_secondary' => $request->address_secondary,
                    'city' => $request->city,
                    'country' => $request->country,
                    'postal_code' => $request->postal_code,
                    'description' => $request->description,
                ]);
            }


            $Provider->save();

            return $Provider;

        }

        catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Driver Not Found!'], 404);
        }
    }

    public function updateVehicle(Request $request)
    {
        $this->validate($request, [
            'vehicle_image' => 'mimes:jpeg,bmp,png',
        ]);

        try {
            $Provider = Auth::user();
            $service = ServiceType::where('name', $request->vehicle_type)->first();
            if($Provider->service) {
                $Provider->service->update([
                    'service_type_id' => is_object($service) ? $service->id : $Provider->service->service_type_id,
                    'service_model' => $request->model ? : $Provider->service->service_model,
                    'service_year' => $request->year ? : $Provider->service->service_year,
                    'service_make' => $request->make ? : $Provider->service->service_make,
                    'service_name' => $request->vehicle_name ? : $Provider->service->service_name,
                    'service_ac' => $request->ac ? : $Provider->service->service_ac,
                    'service_capacity' => is_object($service) ? $service->capacity : $Provider->service->service_capacity,
                ]);
            } else {
                ProviderService::create([
                    'provider_id' => $Provider->id,
                    'service_type_id' => is_object($service) ? $service->id : 18,
                    'service_model' => $request->model,
                    'service_year' => $request->year,
                    'service_make' => $request->make,
                    'service_name' => $request->vehicle_name,
                    'service_ac' => $request->ac,
                    'service_capacity' => is_object($service) ? $service->capacity : 4,
                ]);
            }

            if ($request->hasFile('vehicle_image')) {
                $Document = $Provider->document(18);//Vehicle Image
                if(is_object($Document)) {
                    $Document->update([
                        'url' => $request->vehicle_image->store('provider/documents'),
                        'expires_at' => Carbon::now()->addYear()->format('Y-m-d H:i:s'),
                        'status' => 'ASSESSING',
                    ]);
                } else {
                    $Document = ProviderDocument::create([
                        'url' => $request->vehicle_image->store('provider/documents'),
                        'provider_id' => $Provider->id,
                        'document_id' => 18,
                        'expires_at' => Carbon::now()->addYear()->format('Y-m-d H:i:s'),
                        'status' => 'ASSESSING',
                    ]);
                }
            }
            $Provider->service->save();
            return $Provider;

        }

        catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Driver Not Found!'], 404);
        }
    }


    public function updateBank(Request $request)
    {
        try {
            $Provider = Auth::user();
            $params = $request->all();
            $params['provider_id'] = $Provider->id;
            if($Provider->bank) {
                $Provider->bank->update([
                    'account_name' => $request->account_name ? : $Provider->bank->account_name,
                    'bank_name' => $request->bank_name ? : $Provider->bank->bank_name,
                    'account_number' => $request->account_number ? : $Provider->bank->account_number,
                    'routing_number' => $request->routing_number ? : $Provider->bank->routing_number,
                    'country' => $request->country ? : $Provider->bank->country,
                    'swift_code' => $request->swift_code ? : $Provider->bank->swift_code,
                    'branch_name' => $request->branch_name ? : $Provider->bank->branch_name,
                    'bank_address' => $request->bank_address ? : $Provider->bank->bank_address,
                ]);
            } else {
                BankAccount::create([
                    'provider_id' => $Provider->id,
                    'account_name' => $request->account_name,
                    'bank_name' => $request->bank_name,
                    'account_number' => $request->account_number,
                    'routing_number' => $request->routing_number,
                    'country' => $request->country,
                    'swift_code' => $request->swift_code,
                    'branch_name' => $request->branch_name,
                    'bank_address' => $request->bank_address,
                ]);
            }
            return $Provider;
        }

        catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Driver Not Found!'], 404);
        }
    }

    public function getRegisterStep(Request $request)
    {
        try {

            $Provider = Auth::user();
            if(!is_object($Provider->profile)) {
                return response()->json(['step' => 'Personal']);
            }
            if(!is_object($Provider->service)) {
                return response()->json(['step' => 'Vehicle']);
            }
            if(($Provider->documents->count() < 8)) {
                return response()->json(['step' => 'Document']);
            }
            if(!is_object($Provider->bank)) {
                return response()->json(['step' => 'Bank']);
            }

            return response()->json(['step' => 'Complete']);

        }

        catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Driver Not Found!'], 404);
        }
    }

    public function getDocument(Request $request)
    {
        $Provider = Auth::user();
        $documents = Document::where('id', '<>', 18)->get();
        foreach($documents as $k=>$v){
            if(is_object($Provider->document($v->id))) {
                $documents[$k] = $Provider->document($v->id);
            }
            $documents[$k]['document_name'] = $v->name;
            $documents[$k]['document_id'] = $v->id;
        }
        return $documents;
    }

    public function updateDocument(Request $request)
    {
        $this->validate($request, [
            'document' 		=> 'required|mimes:jpg,jpeg,png,pdf',
            'document_id'	   		=> 'required|numeric',
        ]);

        try {
            $Provider = Auth::user();

            if ($request->hasFile('document')) {
                $Document = $Provider->document($request->document_id);
                if(is_object($Document)) {
                    $Document->update([
                        'url' => $request->document->store('provider/documents'),
                        'expires_at' => Carbon::now()->addYear()->format('Y-m-d H:i:s'),
                        'status' => 'ASSESSING',
                    ]);
                } else {
                    $Document = ProviderDocument::create([
                        'url' => $request->document->store('provider/documents'),
                        'provider_id' => $Provider->id,
                        'document_id' => $request->document_id,
                        'expires_at' => Carbon::now()->addYear()->format('Y-m-d H:i:s'),
                        'status' => 'ASSESSING',
                    ]);
                }
            }
            $Provider->service->save();
            return $Provider;

        }

        catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Driver Not Found!'], 404);
        }
    }
}

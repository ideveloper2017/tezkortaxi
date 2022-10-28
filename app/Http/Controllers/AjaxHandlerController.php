<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Setting;
use Validator;
use App\Promocode;
use App\PromocodeUsage;
use Auth;

use App\ProviderService;
use App\Provider;
use App\Settings;
use App\ServiceType;

class AjaxHandlerController extends Controller
{
	
	protected $UserAPI;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserApiController $UserAPI)
    {
        $this->UserAPI = $UserAPI;
    }


  /**
     *  Apply Promo codes promo code.
     *
     * @return \Illuminate\Http\Response
     */

    public function applyPromoCodeOnEstimatedFare(Request $request) {
		
		$validator = Validator::make($request->all(), [
             'promo_code' => 'required|exists:promocodes,promo_code',
           
        ]);
		
		if ($validator->fails()) {
			
				return response()->json([
                        'errors' => $validator->getMessageBag()->toArray(), 
                        'code' => 'request_error'
                    ]);
					
			}
		
		
        try{

            $find_promo = Promocode::where('promo_code',$request->promo_code)->first();

            if($find_promo->status == 'EXPIRED' || (date("Y-m-d") > $find_promo->expiration)){

                if($request->ajax()){

                    return response()->json([
                        'message' => trans('api.promocode_expired'), 
                        'code' => 'promocode_expired'
                    ]);

                }else{
                    return back()->with('flash_error', trans('api.promocode_expired'));
                }

            }elseif(PromocodeUsage::where('promocode_id',$find_promo->id)->where('user_id', Auth::user()->id)->where('status','ADDED')->count() > 0){

                if($request->ajax()) {

                    return response()->json([
                        'message' => trans('api.promocode_already_in_use'), 
                        'code' => 'promocode_already_in_use'
                        ]);

                } else {
                    return back()->with('flash_error', 'Promocode Already in use');
                }

            } else {
				
                // apply promo_code on estimated fare
                $fare =   $this->getEstimatedFare( $request );
                
                if($request->ajax()){

                    return response()->json([
                            'message' 	=> trans('api.promocode_applied') ,
							'fare'		=> $fare,
                            'code' 		=> 'promocode_applied'
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


    public function getEstimatedFare(Request $request ) {
        
        $fare = $this->UserAPI->estimated_fare($request)->getData();	
        $fare->estimated_fare = currency( $fare->estimated_fare );
        
        return $fare;
    
    }



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

            $meter = $details['rows'][0]['elements'][0]['distance']['value'];
            $time = $details['rows'][0]['elements'][0]['duration']['text'];
            $seconds = $details['rows'][0]['elements'][0]['duration']['value'];

            $kilometer = round($meter/1000);
            $minutes = round($seconds/60);

            $tax_percentage = Setting::get('tax_percentage');
            $commission_percentage = Setting::get('commission_percentage');
            $service_type = ServiceType::findOrFail($request->service_type);
			$total_discount = 0;
            
            $price = $service_type->fixed;

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

            $tax_price = ( $tax_percentage/100 ) * $price;
            $total = $price + $tax_price;
			
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
            
            if($Providers->count() <= Setting::get('surge_trigger') && $Providers->count() > 0){
                $surge_price = (Setting::get('surge_percentage')/100) * $total;
                $total += $surge_price;
                $surge = 1;
            }
            
                
            return response()->json([
                    'estimated_fare' => currency( round($total,2) ), 
                    'distance' => $kilometer,
                    'time' => $time,
                    'surge' => $surge,
                    'surge_value' => '1.4X',
                    'tax_price' => $tax_price,
                    'base_price' => $service_type->fixed,
					'discount'		=> round($total_discount,2)
                ]);

        } catch(Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }


    public function estimated_fare_yandex(Request $request) {

        try{

            $meter = $request->get('distance');
            $time = $request->get('duration');
            $seconds = $request->get('seconds');

            $kilometer = round($meter/1000);
            $minutes = round($seconds/60);

            $tax_percentage = Setting::get('tax_percentage');
            $commission_percentage = Setting::get('commission_percentage');
            $service_type = ServiceType::findOrFail($request->service_type);
            $total_discount = 0;

            $price = $service_type->fixed;

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

            $tax_price = ( $tax_percentage/100 ) * $price;
            $total = $price + $tax_price;

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

            if($Providers->count() <= Setting::get('surge_trigger') && $Providers->count() > 0){
                $surge_price = (Setting::get('surge_percentage')/100) * $total;
                $total += $surge_price;
                $surge = 1;
            }


            return response()->json([
                'estimated_fare' => currency( round($total,2) ),
                'distance' => $kilometer,
                'time' => $time,
                'surge' => $surge,
                'surge_value' => '1.4X',
                'tax_price' => $tax_price,
                'base_price' => $service_type->fixed,
                'discount'		=> round($total_discount,2)
            ]);

        } catch(Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

}

?>
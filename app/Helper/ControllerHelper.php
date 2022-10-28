<?php 

namespace App\Helpers;

use File;
use Setting;
use DB;
use Exception;
use App\Provider;
use App\ServiceType;
use App\UserRequests;
use App\RequestFilter;
use App\Http\Controllers\SendPushNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Zones;
use App\Http\Controllers\Resource\ServiceResource;

class Helper
{

    public static function upload_picture($picture)
    {
        $file_name = time();
        $file_name .= rand();
        $file_name = sha1($file_name);
        if ($picture) {
            $ext = $picture->getClientOriginalExtension();
            $picture->move(public_path() . "/uploads", $file_name . "." . $ext);
            $local_url = $file_name . "." . $ext;

            $s3_url = 'public/uploads/'.$local_url;
            
            return $s3_url;
        }
        return "";
    }
    
    public static function upload_picture_user($picture)
    {
        $file_name = time();
        $file_name .= rand();
        $file_name = sha1($file_name);
        if ($picture) {
            $ext = $picture->getClientOriginalExtension();
            $picture->move(public_path() . "/user/profile/", $file_name . "." . $ext);
            $local_url = $file_name . "." . $ext;

            $s3_url = 'public/user/profile/'.$local_url;
            
            return $s3_url;
        }
        return "";
    }


    public static function delete_picture($picture) {
        File::delete( public_path() . "/uploads/" . basename($picture));
        return true;
    }

    public static function generate_booking_id() {
        return Setting::get('booking_prefix').mt_rand(100000, 999999);
    }
    
    
    
    public static function pointInPolygon($point, $polygon) { 
        $return = false;
        foreach($polygon as $k=>$p) {
             
            if( !$k ) $k_prev = count($polygon)-1;
            else $k_prev = $k-1;

            if(($p[1]< $point[1] && $polygon[$k_prev][1]>=$point[1] || $polygon[$k_prev][1]< $point[1] && $p[1]>=$point[1]) && ($p[0]<=$point[0] || $polygon[$k_prev][0]<=$point[0])){
               if($p[0]+($point[1]-$p[1])/($polygon[$k_prev][1]-$p[1])*($polygon[$k_prev][0]-$p[0])<$point[0]){
                  $return = !$return;
                }
            }
        }
    
        return $return;
    }
    
    public  static function getAvailableProviders($service_type , $zone_id ) {
        
        return DB::table('providers')
        ->join('provider_zone', 'provider_zone.driver_id', '=', 'providers.id')
        ->join('provider_services', 'provider_services.provider_id', '=', 'providers.id')
        ->select(DB::raw('providers.*, provider_zone.created_at as enter_time, provider_zone.id as driver_position, provider_services.service_number, provider_services.service_model, provider_services.service_type_id, provider_services.status AS provider_current_status') )
        ->where('providers.status', 'approved')
        ->where('provider_services.status', 'active')
        ->where('provider_services.service_type_id', $service_type) 
        ->where('provider_zone.zone_id', $zone_id )
        ->orderBy('provider_zone.id')
        ->get();

    }
    
    
    public static function getAvailableProvidersByRadius($service_type, $latitude, $longitude) {
        
        $distance = Setting::get('provider_search_radius', '10');
        
        return Provider::with('service')
            ->select(DB::Raw("(6371 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) AS distance"),'providers.*')
            ->where('status', 'approved')
            ->whereRaw("(6371 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) <= $distance")
            ->whereHas('service', function($query) use ($service_type){
                        $query->where('status','active');
                        $query->where('service_type_id',$service_type);
                    })
            ->orderBy('distance')
            ->get();
            
    }
    
    
    
    
    public static function getLatlngZone_id( $point ) {
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
    
    
    



public function assign_next_provider($request_id) {

        try {
            $UserRequest = UserRequests::findOrFail($request_id);
        } catch (ModelNotFoundException $e) {
            // Cancelled between update.
            return false;
        }

        $RequestFilter = RequestFilter::where('provider_id', $UserRequest->current_provider_id)
            ->where('request_id', $UserRequest->id)
            ->delete();

        try {

            $next_provider = RequestFilter::where('request_id', $UserRequest->id)
                            ->orderBy('id')
                            ->firstOrFail();

            $UserRequest->current_provider_id = $next_provider->provider_id;
            $UserRequest->assigned_at = Carbon::now();
            $UserRequest->save();

            // incoming request push to provider
           # (new SendPushNotification)->IncomingRequest($next_provider->provider_id);
            
        } catch (ModelNotFoundException $e) {

            UserRequests::where('id', $UserRequest->id)->update(['status' => 'CANCELLED']);

            // No longer need request specific rows from RequestMeta
            RequestFilter::where('request_id', $UserRequest->id)->delete();

            //  request push to user provider not available
            (new SendPushNotification)->ProviderNotAvailable($UserRequest->user_id);
        }
    }


    
    public static function availableProviders($service_type, $latitude, $longitude) {
        
        
        $zone_id        =   Helper::getLatlngZone_id([ $latitude ,$longitude ]);
        $Providers      =   Helper::getAvailableProvidersByRadius((int)$service_type , $latitude, $longitude);
        
        if( $zone_id ) {
            $providers =  Helper::getAvailableProviders((int)$service_type , (int) $zone_id );
            if( $providers->count() ) {
                $Providers = $providers;
            }
        }
        
        return $Providers;
        
    }
    
    public static function getDistanceBetweenCoords($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000) {
              // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
        cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    
    }
    
    
    
    public static function getEstimatedFare( $data ) {
        
        try{

            if( ! $data ) {
                throw new Exception('Detail not found!');
            }
            
            
            $req_url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$data['s_latitude'].",".$data['s_longitude']."&destinations=".$data['d_latitude'].",".$data['d_longitude']."&mode=driving&sensor=false&key=".env('GOOGLE_MAP_KEY');
            $details =  (array)Helper::getDataByCurl( $req_url );
            
            if( $details['status'] != 'OK') {
                throw new Exception( serialize(['error'=>'GOOGLE_MAP_KEY failed due to: '. $details['error_message']] ) );
            }
            
            $meter = $details['rows'][0]['elements'][0]['distance']['value'];
            $time = $details['rows'][0]['elements'][0]['duration']['text'];
            $seconds = $details['rows'][0]['elements'][0]['duration']['value'];

            $kilometer = number_format( ( $meter / 1000 ), 3, '.', '');
            $minutes = round($seconds/60);

            $tax_percentage = Setting::get('tax_percentage');
            $commission_percentage = Setting::get('commission_percentage');
            $service_type = ServiceType::findOrFail($data['service_type']);
            
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
            $surge_price = (Setting::get('surge_percentage')/100) * $total;
            $total += $surge_price;
            $surge = 1;
            
            $service = (new ServiceResource)->show($data['service_type']);

            $result = [
                    'estimated_fare' => round($total,1), 
                    'distance' => $kilometer,
                    'service'=>$service,
                    'time' => $time,
                    'surge' => $surge,
                    'surge_value' => '1.4X',
                    'tax_price' => $tax_price,
                    'base_price' => $service_type->fixed
                    
                ];
            
            
            return $result;
            
        
        } catch(Exception $e ) {
            return unserialize($e->getMessage());
        }
        
    }
    
    
    public static function getDataByCurl( $url ) {
        
        $ch = curl_init( $url ); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
        $results =   curl_exec( $ch );  
        curl_close( $ch );
        
        return json_decode( $results, TRUE );
    }
    
    
    
    
}

<?php

use App\Models\PromocodeUsage;
use App\Models\ServiceType;
use App\Models\User;
use App\Models\Provider;
use App\Models\Page;
use App\Models\ProviderService;
use App\Models\EmailTemplate;
use App\Models\Complaint;
use App\Models\Zones;
function currency($value = '')
{
	if($value == ""){
		return Setting::get('currency')."0.00";
	} else {
		return Setting::get('currency')." ".$value;
	}
}

function distance($value = '')
{
    if($value == ""){
        return "0".Setting::get('distance', 'Km');
    }else{
        return $value.Setting::get('distance', 'Km');
    }
}

function providerTimeout($value = '')
{
    if($value == ""){
        return  Setting::get('provider_select_timeout');
    }else{
        return $value.Setting::get('provider_select_timeout', '50');
    }
}

function img($img){
	if($img == ""){
		return asset('storage/app/public/user/profile/ic_dummy_user.png');
	}else if (strpos($img, 'http') !== false) {
        return $img;
    }else{
		return asset('storage/app/public/'.$img);
	}
}

function image($img){
	if($img == ""){
		return asset('main/avatar.jpg');
	}else{
		return asset($img);
	}
}

function promo_used_count($promo_id)
{
	return PromocodeUsage::where('status','ADDED')->where('promocode_id',$promo_id)->count();
}

function curl($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $return = curl_exec($ch);
    curl_close ($ch);
    return $return;
}

function get_all_service_types()
{
	return ServiceType::all();
}

function get_new_user()
{

	return User::orderBy('id', 'desc')->limit(3)->get();
}

function top_countries(){

    return Zones::select('country')->groupBy('country')->limit(5)->get();

   }

function get_new_provider()
{

	return Provider::orderBy('id', 'desc')->limit(3)->get();
}

function get_complaint(){
    return Complaint::orderBy('id', 'desc')->limit(3)->get();
}

function get_new_complaint(){
    return Complaint::all();
}

function getPages(){
	return $pageList   = Page::all();
}

function getLatlngZone_id( $point ) {
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


function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
    $output = NULL;
    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    }
    $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
    $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
    $continents = array(
        "AF" => "Africa",
        "AN" => "Antarctica",
        "AS" => "Asia",
        "EU" => "Europe",
        "OC" => "Australia (Oceania)",
        "NA" => "North America",
        "SA" => "South America"
    );
    if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
        if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
            switch ($purpose) {
                case "location":
                    $output = array(
                        "city"           => @$ipdat->geoplugin_city,
                        "state"          => @$ipdat->geoplugin_regionName,
                        "country"        => @$ipdat->geoplugin_countryName,
                        "country_code"   => @$ipdat->geoplugin_countryCode,
                        "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                        "continent_code" => @$ipdat->geoplugin_continentCode
                    );
                    break;
                case "address":
                    $address = array($ipdat->geoplugin_countryName);
                    if (@strlen($ipdat->geoplugin_regionName) >= 1)
                        $address[] = $ipdat->geoplugin_regionName;
                    if (@strlen($ipdat->geoplugin_city) >= 1)
                        $address[] = $ipdat->geoplugin_city;
                    $output = implode(", ", array_reverse($address));
                    break;
                case "city":
                    $output = @$ipdat->geoplugin_city;
                    break;
                case "state":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "region":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "country":
                    $output = @$ipdat->geoplugin_countryName;
                    break;
                case "countrycode":
                    $output = @$ipdat->geoplugin_countryCode;
                    break;
            }
        }
    }
    return $output;
}

function getLatAndLongByLocation($locaton){

	$country =ip_info("Visitor", $locaton);

    //$address =$country['city'].",".$country['country'];
	//echo "<pre/>";
        //  print_r($country);die;
	$latLngs  =array();
	$geocode_stats = file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?address=".$country."&sensor=false");
	$output_deals = json_decode($geocode_stats);

         if(isset($output_deals->results[0]) && !empty($output_deals->results[0])){
            	$latLng = $output_deals->results[0]->geometry->location;
            	$latLngs['lat'] = $latLng->lat;
            	$latLngs['lng'] = $latLng->lng;
          }else{
                $latLngs['lat'] ='28.644800';
                $latLngs['lng'] ='77.216721';
          }
          //echo "<pre/>";
          //print_r($latLngs);die;

	return $latLngs;

}
function activeOffline(){

        return $status = ProviderService::select('status')->where('provider_id',Auth::guard('provider')->user()->id)->first();
        }

function sendMail($type,$email,$name,$subject){
    $temp_data = EmailTemplate::where('type',$type)->first();
    $template =  str_ireplace('{{USER_NAME}}',$name,$temp_data->template);
    $template =  str_ireplace('{{DATE}}',date('d-m-Y'),$template);
    $sender_email = $email; //sender email
    $sender_name = $name;
    $subject = $subject;
    if($sender_email!=''){
        Mail::send('emails.test',array('data'=>$template), function($message) use($sender_email, $subject, $sender_name){
         $message->to($sender_email, $sender_name)->subject
            ($subject);
         $message->from(config('mail.from.address' ) , config('mail.from.name'));
     });
   }
   function strReplaceFunc($search,$replace,$data){

   return str_ireplace($search,$replace, $data);
   }

}

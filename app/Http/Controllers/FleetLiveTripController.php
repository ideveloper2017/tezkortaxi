<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\Helper;

use Auth;
use Setting;
use Exception;
use \Carbon\Carbon;

use App\User;
use App\UserLocationType;
use App\Provider;
use App\ServiceType;
use App\UserRequests;

class FleetLiveTripController extends Controller
{
    public function index($type = null){
        
        switch($type){
            case "user":
                return $this->users();
                break;
            case "driver":
                return $this->driver();
                break;
            case "ongoing":
                return $this->ongoing();
                break;
            case "complete":
                return $this->complet();
                break;
            default:
                return array_merge($this->users(),$this->driver(),$this->ongoing(),$this->complet());

        }
        
    }
    public function users(){
        $data =  UserLocationType::rightJoin('users','users.id','=','user_location_types.user_id')
                ->select(['user_location_types.id','user_location_types.latitude','user_location_types.longitude'])
                ->where('user_location_types.latitude','!=',0)
                ->where('user_location_types.longitude','!=',0)
                ->get();

        $data = $data->map(function($item){
            $lt = $item->latitude;
            $item->latitude = (double)$item->longitude;
            $item->longitude = (double)$lt;
            $item['icon'] = 'user';
            // $item['id'] = $item->user_id;
            return $item;
        });
        return $data->toArray();
    }

    public function driver(){
        $data =  Provider::where('status','!=','banned')->where('fleet',Auth::user()->id)->get(['id','latitude','longitude']);
        $data = $data->map(function($item){
            $item->latitude = (double)$item->latitude;
            $item->longitude = (double)$item->longitude;
            $item['icon'] = 'active';
            return $item;
        });
        return $data->toArray();
    }
    public function ongoing(){
        $data =  UserRequests::whereHas('provider', function($query) {
                            $query->where('fleet', Auth::user()->id );
                        })->whereIn('status',['ACCEPTED','STARTED'])->get(['id','s_latitude','s_longitude']);
        $data =  $data->map(function($item){
            $s = [];
            $s['latitude']  = (double)$item->s_latitude;
            $s['id']        = $item->id;
            $s['longitude'] = (double)$item->s_longitude;
            $s['icon']      = 'riding';
            return $s;
        });
        return $data->toArray();
    }
    public function complet(){
        $data =  UserRequests::whereHas('provider', function($query) {
                            $query->where('fleet', Auth::user()->id );
                        })->where('status','COMPLETED')->get(['id','d_latitude','d_longitude']);
        $data = $data->map(function($item){
            $s = [];
            $s['id'] = $item->id;
            $s['latitude'] = (double)$item->d_latitude;
            $s['longitude'] = (double)$item->d_longitude;
            $s['icon'] = 'complete';
            return $s;
        });
        return $data->toArray();
    }

    public function getDetails($type,$id){
        //return $type;die;
        switch($type){
            case "user":
                return $this->usersDetails($id);
                break;
            case "active":
                return $this->driverDetails($id);
                break;
            case "ongoing":
                return $this->ongoingDetail($id);
                break;
            case "riding":
                return $this->ongoingDetail($id);
                break;
            case "complete":
                return $this->completDetails($id);
                break;
            default:
                return null;
        }
    }

    public function usersDetails($id){
        $detals = UserLocationType::select(['user_id','address'])->find($id);
        $user = User::find($detals->user_id);
            return   '<div id="siteNotice">'.
            '</div>'.
            '<div id="bodyContent" style="width:250px;">'.
            '<div style="width:100%" class="row">'.
                '<div style="width:100px%;float:left;padding-left:20px;">'.
                '<img src="'.($user->picture ?? base_url+'/storage/app/public/provider/profile/user.png').'" style="width:70px;height:70px;border-radius:50%;">'.
                '</div>'.
                '<div style="position: relative; top: 16px; text-align:center;">'.
                    "<h5>{$user->first_name}</h5>".
                    "<p>{$detals->address}</p>".
                '</div>'.
            '</div><br>'.
            '<div style="width:100%;padding-left:20px;" class="row">'.
                 '<h5 style="text-align:center;">'.
                 "<a href='".url('admin/user/'.$user->id.'/edit')."'>".
                 'View Profile</a> '.
                '</h5>'.
            '</div>'.
            
            '</div>'.
            '</div>';
    }
    
    public function driverDetails($id){
        $provider = Provider::find($id);
        $details = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$provider->latitude},{$provider->longitude}&key=".env('GOOGLE_MAP_KEY');

            $json = curl($details);

            $details = json_decode($json, TRUE);
            $add = $details['results'][0]['formatted_address'];
        return   '<div id="siteNotice">'.
            '</div>'.
            '<div id="bodyContent" style="width:250px;">'.
            '<div style="width:100%" class="row">'.
                '<div style="width:100px%;float:left;padding-left:20px;">'.
                    '<img src="'.(base_url+'/storage/app/public/'.$provider->avatar ?? base_url+'/storage/app/public/provider/profile/user.png').'" style="width:70px;height:70px;border-radius:50%;">'.
                '</div>'.
                '<div style="position: relative; top: 16px; text-align:center;">'.
                    "<h5>{$provider->first_name}</h5>".
                    "<p>{$add}</p>".
                '</div>'.
            '</div><br>'.
            '<div style="width:100%;padding-left:20px;" class="row">'.
                 '<h5 style="text-align:center;">'.
                 "<a href='".url('admin/provider/'.$provider->id.'/edit')."'>".
                 'View Profile</a> '.
                '</h5>'.
            '</div>'.
            
            '</div>'.
            '</div>';
    }
    
    public function ongoingDetail($id){
        $rq =  UserRequests::find($id);
        $user = User::find($rq->user_id);
        $provider = Provider::rightJoin('request_filters', 'request_filters.provider_id','=','providers.id')
         ->where('request_filters.request_id', $rq->id)->first();

        return   '<div id="siteNotice">'.
            '</div>'.
            '<div id="bodyContent" style="width:250px;">'.
            '<div style="width:100%" class="row">'.
                '<div style="width:100px%;float:left;padding-left:20px;">'.
                    '<img src="'.($user->picture ?? base_url+'/storage/app/public/provider/profile/user.png').'" style="width:70px;height:70px;border-radius:50%;">'.
                '</div>'.
                '<div style="position: relative; top: 16px; text-align:center;">'.
                    "<h5>".($user->first_name ?? 'Passanger')."</h5>".
                    "<p>Passanger</p>".
                '</div>'.
            '</div><br>'.
            '<div style="width:100%" class="row">'.
                '<div style="width:100px%;float:left;padding-left:20px;">'.
                    '<img src="'.($provider->avatar ?? base_url+'/storage/app/public/provider/profile/user.png').'" style="width:70px;height:70px;border-radius:50%;">'.
                '</div>'.
                '<div style="position: relative; top: 16px; text-align:center;">'.
                    "<h5>".($provider->first_name ?? "Driver")."</h5>".
                    "<p>Driver</p>".
                '</div>'.
            '</div><br>'.
            '<div style="width:100%;padding-left:20px;" class="row">'.
                "<p><b>From:</b> {$rq->s_address} </p>".
                "<p><b>To:</b> {$rq->d_address}</p>".
                "<p><b>Status:</b> {$rq->status}</p>".
                 '<h5 style="text-align:center;">'.
                 "<a href='".url('admin/requests/'.($rq->id))."'>".
                 'View Details</a> '.
                '</h5>'.
            '</div>'.
            '</div>'.
            '</div>';

    }
    public function completDetails($id){
        $rq =  UserRequests::find($id);
        $user = User::find($rq->user_id);
        $provider = Provider::rightJoin('request_filters', 'request_filters.provider_id','=','providers.id')
         ->where('request_filters.request_id', $rq->id)->first();

         return   '<div id="siteNotice">'.
            '</div>'.
            '<div id="bodyContent" style="width:250px;">'.
            '<div style="width:100%" class="row">'.
                '<div style="width:100px%;float:left;padding-left:20px;">'.
                    '<img src="'.($user->picture ?? base_url+'/storage/app/public/provider/profile/user.png').'" style="width:70px;height:70px;border-radius:50%;">'.
                '</div>'.
                '<div style="position: relative; top: 16px; text-align:center;">'.
                    "<h5>".($user->first_name ?? 'Passanger')."</h5>".
                    "<p>Passanger</p>".
                '</div>'.
            '</div><br>'.
            '<div style="width:100%" class="row">'.
                '<div style="width:100px%;float:left;padding-left:20px;">'.
                    '<img src="'.($provider->avatar ?? base_url+'/storage/app/public/provider/profile/user.png').'" style="width:70px;height:70px;border-radius:50%;">'.
                '</div>'.
                '<div style="position: relative; top: 16px; text-align:center;">'.
                    "<h5>".($provider->first_name ?? "Driver")."</h5>".
                    "<p>Driver</p>".
                '</div>'.
            '</div><br>'.
            '<div style="width:100%;padding-left:20px;" class="row">'.
                "<p><b>From:</b> {$rq->s_address} </p>".
                "<p><b>To:</b> {$rq->d_address}</p>".
                "<p><b>Status:</b> {$rq->status}</p>".
                 '<h5 style="text-align:center;">'.
                 "<a href='".url('admin/requests/'.($rq->id))."'>".
                 'View Details</a> '.
                '</h5>'.
            '</div>'.
            '</div>'.
            '</div>';
    }






    public function getDetailsD($type,$id){
            //return $type;die;
            switch($type){
                case "user":
                    return $this->usersDetailsD($id);
                    break;
                case "active":
                    return $this->driverDetailsD($id);
                    break;
                case "ongoing":
                    return $this->ongoingDetailD($id);
                    break;
                case "riding":
                    return $this->ongoingDetailD($id);
                    break;
                case "complete":
                    return $this->completDetailsD($id);
                    break;
                default:
                    return null;
            }
        }

        public function usersDetailsD($id){
            $detals = UserLocationType::select(['user_id','address'])->find($id);
            $user = User::find($detals->user_id);
                return   '<div id="siteNotice">'.
                '</div>'.
                '<div id="bodyContent" style="width:250px;">'.
                '<div style="width:100%" class="row">'.
                    '<div style="width:100px%;float:left;padding-left:20px;">'.
                    '<img src="'.($user->picture ?? base_url+'/storage/app/public/provider/profile/user.png').'" style="width:70px;height:70px;border-radius:50%;">'.
                    '</div>'.
                    '<div style="position: relative; top: 16px; text-align:center;">'.
                        "<h5>{$user->first_name}</h5>".
                        "<p>{$detals->address}</p>".
                    '</div>'.
                '</div><br>'.
                '<div style="width:100%;padding-left:20px;" class="row">'.
                     '<h5 style="text-align:center;">'.
                    '</h5>'.
                '</div>'.
                
                '</div>'.
                '</div>';
        }
        
        public function driverDetailsD($id){
            $provider = Provider::find($id);
            $details = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$provider->latitude},{$provider->longitude}&key=".env('GOOGLE_MAP_KEY');

                $json = curl($details);

                $details = json_decode($json, TRUE);
                $add = $details['results'][0]['formatted_address'];
            return   '<div id="siteNotice">'.
                '</div>'.
                '<div id="bodyContent" style="width:250px;">'.
                '<div style="width:100%" class="row">'.
                    '<div style="width:100px%;float:left;padding-left:20px;">'.
                        '<img src="'.(base_url+'/storage/app/public/'.$provider->avatar ?? base_url+'/storage/app/public/provider/profile/user.png').'" style="width:70px;height:70px;border-radius:50%;">'.
                    '</div>'.
                    '<div style="position: relative; top: 16px; text-align:center;">'.
                        "<h5>{$provider->first_name}</h5>".
                        "<p>{$add}</p>".
                    '</div>'.
                '</div><br>'.
                '<div style="width:100%;padding-left:20px;" class="row">'.
                     '<h5 style="text-align:center;">'.
                    '</h5>'.
                '</div>'.
                
                '</div>'.
                '</div>';
        }
        

        
        public function ongoingDetailD($id){
            $rq =  UserRequests::find($id);
            $user = User::find($rq->user_id);
            $provider = Provider::rightJoin('request_filters', 'request_filters.provider_id','=','providers.id')
             ->where('request_filters.request_id', $rq->id)->first();

            return   '<div id="siteNotice">'.
                '</div>'.
                '<div id="bodyContent" style="width:250px;">'.
                '<div style="width:100%" class="row">'.
                    '<div style="width:100px%;float:left;padding-left:20px;">'.
                        '<img src="'.($user->picture ?? base_url+'/storage/app/public/provider/profile/user.png').'" style="width:70px;height:70px;border-radius:50%;">'.
                    '</div>'.
                    '<div style="position: relative; top: 16px; text-align:center;">'.
                        "<h5>".($user->first_name ?? 'Passanger')."</h5>".
                        "<p>Passanger</p>".
                    '</div>'.
                '</div><br>'.
                '<div style="width:100%" class="row">'.
                    '<div style="width:100px%;float:left;padding-left:20px;">'.
                        '<img src="'.($provider->avatar ?? base_url+'/storage/app/public/provider/profile/user.png').'" style="width:70px;height:70px;border-radius:50%;">'.
                    '</div>'.
                    '<div style="position: relative; top: 16px; text-align:center;">'.
                        "<h5>".($provider->first_name ?? "Driver")."</h5>".
                        "<p>Driver</p>".
                    '</div>'.
                '</div><br>'.
                '<div style="width:100%;padding-left:20px;" class="row">'.
                    "<p><b>From:</b> {$rq->s_address} </p>".
                    "<p><b>To:</b> {$rq->d_address}</p>".
                    "<p><b>Status:</b> {$rq->status}</p>".
                     '<h5 style="text-align:center;">'.
                    '</h5>'.
                '</div>'.
                '</div>'.
                '</div>';

        }
        public function completDetailsD($id){
            $rq =  UserRequests::find($id);
            $user = User::find($rq->user_id);
            $provider = Provider::rightJoin('request_filters', 'request_filters.provider_id','=','providers.id')
             ->where('request_filters.request_id', $rq->id)->first();

             return   '<div id="siteNotice">'.
                '</div>'.
                '<div id="bodyContent" style="width:250px;">'.
                '<div style="width:100%" class="row">'.
                    '<div style="width:100px%;float:left;padding-left:20px;">'.
                        '<img src="'.($user->picture ?? base_url+'/storage/app/public/provider/profile/user.png').'" style="width:70px;height:70px;border-radius:50%;">'.
                    '</div>'.
                    '<div style="position: relative; top: 16px; text-align:center;">'.
                        "<h5>".($user->first_name ?? 'Passanger')."</h5>".
                        "<p>Passanger</p>".
                    '</div>'.
                '</div><br>'.
                '<div style="width:100%" class="row">'.
                    '<div style="width:100px%;float:left;padding-left:20px;">'.
                        '<img src="'.($provider->avatar ?? base_url+'/storage/app/public/provider/profile/user.png').'" style="width:70px;height:70px;border-radius:50%;">'.
                    '</div>'.
                    '<div style="position: relative; top: 16px; text-align:center;">'.
                        "<h5>".($provider->first_name ?? "Driver")."</h5>".
                        "<p>Driver</p>".
                    '</div>'.
                '</div><br>'.
                '<div style="width:100%;padding-left:20px;" class="row">'.
                    "<p><b>From:</b> {$rq->s_address} </p>".
                    "<p><b>To:</b> {$rq->d_address}</p>".
                    "<p><b>Status:</b> {$rq->status}</p>".
                     '<h5 style="text-align:center;">'.
                    '</h5>'.
                '</div>'.
                '</div>'.
                '</div>';
        }
}
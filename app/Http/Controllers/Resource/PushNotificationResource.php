<?php

namespace App\Http\Controllers\Resource;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Setting;
use Carbon\Carbon;
use App\PushNotification;
use App\Http\Controllers\SendPushNotification;
use App\User;
use App\Provider;
use App\Zones;
use App\Helpers\Helper;
use Auth;
use App\UserRequests;
class PushNotificationResource  extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
	    $notifications = PushNotification::orderBy('created_at' , 'desc')->get();
        return view('admin.pushnotification.index',compact('notifications'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_old(Request $request,$zone_id='')
    {
        //dd($request['zone_id']);
		$users = User::orderBy('first_name' , 'asc')->get();
		$zones = Zones::orderBy('id' , 'asc')->get();
        $providers = Provider::orderBy('first_name' , 'asc')->get();
        
        if(!empty($request->zone_id)){
        
        $user_request = UserRequests::all()->last();
        
            $spoint[0]	=	$user_request->s_latitude;
    		$spoint[1]	=	$user_request->s_longitude;
    		$dpoint[0]	=	$user_request->d_latitude; 
    		$dpoint[1]	=	$user_request->d_longitude;
    		$szone_id	=	$this->getLatlngZone_id($spoint,$request->zone_id);
    		$dzone_id	=	$this->getLatlngZone_id($dpoint,$request->zone_id);
            $szones = Zones::select('status')->where('id',$szone_id)->where('status','active')->first();
            
    		if(count($szones) > 0)
        	{
        	        $dzones = Zones::select('status','id')->where('id',$dzone_id)->where('status','active')->first();
        	        
            		if(count($dzones) > 0)
            		{
            		    $zone_provider = Provider::where('zone_id',$dzones->id)->get();
            		    //dd($zone_provider);
            		    
            		} else{
            		    
            		    return redirect('admin/pushnotification/create')->with('flash_error', 'Sorry we are not serveing this area.');
            		    
            		}
            }else{
                
               return redirect('admin/pushnotification/create')->with('flash_error', 'Sorry we are not serveing this area.');
            }
            
        }
        $zone_provider =[];
        return view('admin.pushnotification.create',compact('users','providers','zones','zone_provider'));
    }
    
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,$zone_id='')
    {
        //dd($request['zone_id']);
		$users = User::orderBy('first_name' , 'asc')->get();
		$zones = Zones::orderBy('id' , 'asc')->get();
        $providers = Provider::orderBy('first_name' , 'asc')->get();
        return view('admin.pushnotification.create',compact('users','providers','zones')); 
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
            /*'type' => 'required|not_in:-- Choose Type --',
			'title'=>'required',
			'notification_text'=>'required',*/
			//'image'=>'mimes:jpeg,jpg,bmp,png|max:5242880',
			/*'expiration_date'=>'required'*/
        ]);

        try{
            
            $admin = 'admin';
            $data = User::select('id')->get()->toArray();
            $expected = array();

            foreach($data as $d)    {
                $expected[] = $d['id'];
                //push notification
                if($request->all == "all" && $request->type == 1){
                    //(new SendPushNotification)->userNotify($d['id'],$request->title,$request->notification_text,$admin);
                }
            }
            
            $ids = implode(',',$expected);
            //dd($ids);
           
            $provider = Provider::select('id')->get()->toArray();
            $providerNew = array();

            foreach($provider as $d)    {
                $providerNew[] = $d['id'];
                 //push notification
                if($request->all == "all" && $request->type == 2){
                    
			       //(new SendPushNotification)->notifyProvider($d['id'],$request->title,$request->notification_text,$admin);
                }
            }
            
            $providerids = implode(',',$providerNew);
            
            $notification = new PushNotification;
            $notification->type = $request->type;
            $notification->title = $request->title;
            $notification->zone = $request->zone;
            $notification->notification_text = $request->notification_text;
            $notification->from_user = Auth::user()->id;
            
            if($request->all == 'all' && $request->type == 1)   {
                
                //dd($users->id);
                $notification->all = $request->all;
                $notification->to_user = $ids;
                
               
                   
            }   elseif($request->all == 'all' && $request->type == 2)   {
                
                //dd($users->id);
                $notification->all = $request->all;
                $notification->to_user = $providerids;
                   
            }   else{
                $notification->to_user = ($request->type == 1)?$request->users:$request->providers;
            }
           
            $notification->show_in_promotion = isset($request->show_in_promotion)?$request->show_in_promotion:0;
            $notification->url = $request->url;
            $notification->expiration_date = Carbon::parse($request->expiration_date);
            if($request->hasFile('image')) {
                $image = $request->file('image');
                $name = time().'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('user/profile');
                $imagePath = $destinationPath. "/".  $name;
                $image->move($destinationPath, $name);
                $notification['image'] = $name;
            }
           
            $savedNotification = $notification->save();
            $notification_images = $notification['image'];
            
            
            
            
            if($savedNotification){
                if($request->type == 1 && $request->all != "all"){

                //push notification
			    (new SendPushNotification)->userNotify($request->users,$request->title,$request->notification_text,$admin,$notification_images);
			    
            }   elseif($request->type == 2 && $request->all != "all"){
                
                //push notification
			    (new SendPushNotification)->notifyProvider($request->providers,$request->title,$request->notification_text,$admin,$notification_images);
			
            }
            
            /////////////////////////////////////////
             if($request->has('zones')){
                $dataD = User::where('zone_id',$request->zones)->get(); 
            }else{
                $dataD = User::all();
            }
            
            $expected = array();
        
            if($request->all == "all" && $request->type == 1){
                foreach($dataD as $du)    {
                         (new SendPushNotification)->userNotify($du->id,$request->title,$request->notification_text,$admin,$notification_images);
                }
            }
            $ids = implode(',',$expected);
            //dd($ids);
            if($request->has('zones')){
                $provider = Provider::select('id')->where('zone_id',$request->zones)->get()->toArray();   
            }else{
                $provider = Provider::select('id')->get()->toArray();
            }
            
            $providerNew = array();
                if($request->all == "all" && $request->type == 2){
                    foreach($provider as $d)    {
                        //$providerNew[] = $d['id'];
                         //push notification
        			       (new SendPushNotification)->notifyProvider($d['id'],$request->title,$request->notification_text,$admin,$notification_images);
                        }
                }
            ////////////////////////////////////////////////
            }
            
            
            return back()->with('flash_success','Push Notification Details Saved Successfully');

        } 

        catch (Exception $e) {
           //dd($e->getMessage());
            return back()->with('flash_error', 'Push Notification Not Found');
        }
		
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Document  $providerDocument
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    { 
        try {
            $users = User::orderBy('first_name' , 'asc')->get();
            $providers = Provider::orderBy('first_name' , 'asc')->get();
            $notifications  = PushNotification::where('id',$id)->first();
            $data = array('users'=>$users, 'providers'=>$providers,'notifications'=>$notifications);
            return view('admin.pushnotification.edit')->with($data);
        }   catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Document  $providerDocument
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
	/*	$this->validate($request, [
            'type' => 'required|not_in:-- Choose Type --',
			'title'=>'required',
			'notification_text'=>'required',
			'image'=>'mimes:jpeg,jpg,bmp,png|max:5242880',
			'expiration_date'=>'required'
        ]);*/

        try{
            
            $notification = PushNotification::findOrFail($id);
            $notification->type = $request->type;
            $notification->title = $request->title;
            $notification->zone = $request->zone;
            $notification->notification_text = $request->notification_text;
            $notification->from_user = Auth::user()->id;
            $notification->to_user = ($request->type == 1)?$request->users:$request->providers;
            $notification->show_in_promotion = $request->show_in_promotion;
            $notification->url = $request->url;
            $notification->expiration_date = Carbon::parse($request->expiration_date);
            if($request->hasFile('image')) {
                
                $notification['image'] = $request->image->store('user/profile');
            }
            $savedNotification = $notification->save();

            return back()->with('flash_success','Push Notification Details Updated Successfully');

        } 

        catch (Exception $e) {
           dd($e);
            return back()->with('flash_error', 'Push Notification Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Document  $providerDocument
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error', 'Disabled for demo purposes! Please contact us at info@rommoz.com');
        }
		
        try {

            PushNotification::find($id)->delete();
            return back()->with('message', 'Push Notification deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'Push Notification Not Found');
        }
    }
    
    
    public function getLatlngZone_id( $point,$zone_id) {
		//$id = 0;
		
		$zone = Zones::where('id',$zone_id)->first(); 
	
		/*if( count( $zones ) ) {
			foreach( $zones as $zone ) {*/
				if( $zone['coordinate'] ) {
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
			/*}
		}	*/	
		//return $id;	
	}
	public function getZoneId()
    {
        $id = $_GET['zone_id'];
        //dd($id);
        
    }
    
    public function getZonesProviders($id,$type){
            //for provider (driver)
              
           
            if($type==2){
                    $zone_provider = Provider::where('zone_id',$id)->get();
                    if(count($zone_provider)>0){
                         return view('admin.pushnotification.zoneprovider',compact('zone_provider')); 
                    }else{
                       return 'No Driver Found'; 
                    }
                  
        //         $user_request = UserRequests::all()->last();
        //         $spoint[0]	=	$user_request->s_latitude;
        // 		$spoint[1]	=	$user_request->s_longitude;
        // 		$dpoint[0]	=	$user_request->d_latitude; 
        // 		$dpoint[1]	=	$user_request->d_longitude;
        		
        // 		$szone_id	=	$this->getLatlngZone_id($spoint,$id);
        		
        // 		$dzone_id	=	$this->getLatlngZone_id($dpoint,$id);
        	
        //         $szones = Zones::select('status')->where('id',$szone_id)->where('status','active')->first();
        //         if(count($szones) > 0 )
        //     	{
            	  
        //     	        $dzones = Zones::select('status','id')->where('id',$dzone_id)->where('status','active')->first();
            	       
        //         		if(count($dzones) > 0)
        //         		{
        //         		    $zone_provider = Provider::where('zone_id',$dzones->id)->get();
                		
        //         		    return view('admin.pushnotification.zoneprovider',compact('zone_provider'));
        //         		}else{
        //         		    return 'No Driver Found';
        //         		}
            // 	}else{ 
            //	   return 'No Driver Found';
            	    
             //	}
            }else{
                
                $zone_user = User::where('zone_id',$id)->get();
                		
                if(count($zone_user) > 0)
                		{
                		    return view('admin.pushnotification.zoneuser',compact('zone_user'));
                		}else{
                		    return 'No User Found';
                		}
                // for user
                     /*$user_request = UserRequests::all()->last();
                
                $spoint[0]	=	$user_request->s_latitude;
        		$spoint[1]	=	$user_request->s_longitude;
        		$dpoint[0]	=	$user_request->d_latitude; 
        		$dpoint[1]	=	$user_request->d_longitude;
        		
        		$szone_id	=	$this->getLatlngZone_id($spoint,$id);
        		
        		$dzone_id	=	$this->getLatlngZone_id($dpoint,$id);
        	
                $szones = Zones::select('status')->where('id',$szone_id)->where('status','active')->first();
                if(count($szones) > 0 )
            	{
            	  
            	        $dzones = Zones::select('status','id')->where('id',$dzone_id)->where('status','active')->first();
            	       
                		if(count($dzones) > 0)
                		{
                		    $zone_user = User::where('zone_id',$dzones->id)->get();
                		
                		    return view('admin.pushnotification.zoneuser',compact('zone_user'));
                		}else{
                		    return 'No User Found';
                		}
            	}else{ 
            	   return 'No User Found';
            	    
            	}*/
            }
    }
    
    
    public function getZones(){
        $zones = Zones::orderBy('id' , 'asc')->get();
        return view('admin.pushnotification.zones',compact('zones'));
    }
    
    public function getProvidersAndUsers($typeid){
        if($typeid==1){
          	$zone_user = User::orderBy('first_name' , 'asc')->get();
          	return view('admin.pushnotification.zoneuser',compact('zone_user'));
        }
        if($typeid==2){
           $zone_provider = Provider::orderBy('first_name' , 'asc')->get(); 
           return view('admin.pushnotification.zoneprovider',compact('zone_provider'));
        }
       
        
    }
	
	
}

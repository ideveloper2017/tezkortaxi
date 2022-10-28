<?php

namespace App\Http\Controllers\Resource;

use App\Zones;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Setting;
use App\City;
use App\Country;
use App\State;
class ZoneResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
	    $zones = Zones::orderBy('created_at' , 'desc')->get();
	   
        return view('admin.zone.index', compact('zones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		
		$all_zones = $this->makeZoesArray();
		//$country = Country::where('id',101)->with('states')->get();
        return view('admin.zone.create', compact('all_zones'));
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
            'name' => 'required',
			'coordinate'=>'required',
        ]);

        try{
		
			$json = array();
			$id = 0;
			$country = Country::find($request->country,['name']);
			$state = State::find($request->state,['name']);
			$city = City::find($request->city,['name']);
			if( $request->id >  0 ) {
				$zone = Zones::where('id', $request->id )->first();
				if( $zone ) {
					$zone->zone_name 	=	$request->name;
					$zone->country 		=	$country->name;
					$zone->state 		=	$state->name;
					$zone->city 		=	$city->name;
					$zone->status 		=	$request->status;
					$zone->currency 	=	$request->currency;
					$zone->coordinate	=	serialize( $request->coordinate);
					$zone->save();

					$id = $zone;
				}
				
			} else {
		
				$zone 				=	new Zones;
				$zone->zone_name 	=	$request->name;
				$zone->country 		=	$country->name;
				$zone->state 		=	$state->name;
				$zone->city 		=	$city->name;
				$zone->status 		=	$request->status;
				$zone->currency 	=	$request->currency;
				$zone->coordinate	=	serialize($request->coordinate);

				
				$zone->save();
				
				$id = $zone;
				
			}
			
			$json['status'] = $id;
			
			
           return response()->json($json);

        }catch (Exception $e) {
			
            return response()->json(['error' => $e->getMessage() ]);
        
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
			
            $zone = Zones::findOrFail($id);
			$zone->coordinate = $this->makeCoordinate( $zone->coordinate );
			
			$all_zones = $this->makeZoesArray();
			
			
			
            return view('admin.zone.create',compact('zone', 'all_zones'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('admin.zone.index')->with('flash_error', 'No result found'); 
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
		try {
			
			return view('admin.zone.create');

        } catch (Exception $e) {
            return back()->with('flash_error', 'Zone Not Found');
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
			
			$zone = Zones::where('id', $id )->first();
			
			if(!$zone) {
				return back()->with('flash_error', 'Zone Not Found');
			}
			
			$provider_zones = DB::table('zones')->where('id', $zone->id )->get()->pluck('id')->toArray();
		
			if( $provider_zones ) {
				
				DB::table('zones')->whereIn('id', $provider_zones)->delete();
			
			}
			
			$zone->delete();
			
            return back()->with('flash_success', 'Zone deleted successfully');
        } 
        catch (Exception $e) {
            
            return back()->with('flash_error', 'Zone Not Found');
        }
    }
	
	
	public function makeZoesArray( ) {
		$all_zones = [];
		$zones_obj = Zones::orderBy('created_at' , 'desc')->get();
		if( $zones_obj ) {
			foreach( $zones_obj as $zone ) {
				$all_zones[] = [ 'id' => $zone->id, 'name' => $zone->name, 'latlng' =>  $this->makeCoordinate( $zone->coordinate ) ];
			}
		}
		
		return $all_zones;
		
	}
	
	public function makeCoordinate( $path ) {
		$new_coordiante  = array();
		$coordinate = unserialize( $path );
		foreach( $coordinate as $coord ) {
			$new_coordiante[] = $this->makeLatlng( $coord );
		}
		
		return $new_coordiante;
		
	}
	
	public function makeLatlng( $coord ) {
		$path = explode(',', $coord);
		$latlng['lat'] = $path[0];
		$latlng['lng'] = $path[1];
		
		return  $latlng;
		
		
	}
	public function getCountry(){
		return $country = Country::get();
	}
	public function getState(Request $request){
		
		return State::where('country_id',$request->country_id)->get();
	}
	public function getCity(Request $request){
        
		return City::where('state_id',$request->state_id)->get();
	}
	
	
}

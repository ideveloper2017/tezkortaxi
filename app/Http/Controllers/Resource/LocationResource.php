<?php

namespace App\Http\Controllers\Resource;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Country;
use App\State;
use App\City;
class LocationResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    
    public function allCountry(){
        $countries = Country::all();
        return view('admin.location.countries',compact('countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.location.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'country_name' => 'required|not_in:0|unique:countries,name',
            'state_name' => 'required|not_in:0|unique:states,name',
            'city_name'  => 'required|unique:city,name'
        ],[
            'country_name.not_in' => 'Country name field is empty.',
            'state_name.not_in' => 'State name field is empty.',
        ]);
        try {

                if(is_numeric($request->country_name)){
                    if(is_numeric($request->state_name)){
                        $state = State::find($request->state_name);
                        $this->saveCity($state,$request);
                    }else{
                        $country = Country::find($request->country_name);
                        $state = new State;
                        $state->name = $request->state_name;
                        if($country->states()->save($state)){
                            $this->saveCity($state,$request);     
                        }      
                    }
                }else{
                    $country = new Country;
                    $country->name = $request->country_name;
                    $country->sortname = $request->country_name;
                    if($country->save()){
                        $state = new State;
                        $state->name = $request->state_name;
                        if($country->states()->save($state)){
                            $this->saveCity($state,$request); 
                        }
                    } 
                }
                return back()->with('flash_success','Location Saved Successfully');
            
        } catch (Exception $e) {
            return back()->with('flash_error', 'Something went wrong');
        }

    }

    public function saveCity($state,$request){
        $city = new City;
        $city->name=$request->city_name;
        $state->city()->save($city);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

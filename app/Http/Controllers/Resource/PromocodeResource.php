<?php

namespace App\Http\Controllers\Resource;

use App\Promocode;
use App\Zones;
use App\PromocodeUsage;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;

class PromocodeResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $promocodes = Promocode::orderBy('created_at' , 'desc')->get();
        return view('admin.promocode.index', compact('promocodes'));
    }
    
    public function getPromoCodes(){
        return Promocode::orderBy('created_at' , 'desc')->get();
    }
    
    public function userPromoCode(){
        $promocodes = Promocode::orderBy('created_at' , 'desc')->get();
        return view('admin.promocode.users', compact('promocodes'));
    }
    
    public function getPromocodeUser(Request $request){
        return PromocodeUsage::where('promocode_id', $request->promocode_id)->with('promocode','promouser')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $zones = Zones::orderBy('created_at' , 'desc')->get();
        return view('admin.promocode.create',compact('zones'));
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
            'promo_code' => 'required|max:100|unique:promocodes',
            'discount' => 'required|numeric',
            'expiration' => 'required',
            'set_limit' => 'required',
            'number_of_time' => 'required',
            'user_type' => 'required|not_in:-- Choose User Type --',
            'zone' => 'required',
        ]);

        try{

            Promocode::create($request->all());
            return back()->with('flash_success','Promocode Saved Successfully');

        } 

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Promocode Not Found');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Promocode  $promocode
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            return Promocode::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Promocode  $promocode
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $promocode = Promocode::findOrFail($id);
            return view('admin.promocode.edit',compact('promocode'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Promocode  $promocode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'promo_code' => 'required|max:100',
            'discount' => 'required|numeric',
            'expiration' => 'required',
        ]);

        try {

           $promo = Promocode::findOrFail($id);

            $promo->promo_code = $request->promo_code;
            $promo->discount = $request->discount;
            $promo->expiration = $request->expiration;
            $promo->save();

            return redirect()->route('admin.promocode.index')->with('flash_success', 'Promocode Updated Successfully');    
        } 

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Promocode Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Promocode  $promocode
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Promocode::find($id)->delete();
            return back()->with('message', 'Promocode deleted successfully');
        } 
        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Promocode Not Found');
        }
    }
}

<?php

namespace App\Http\Controllers\Resource;

use App\CmsUser;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Exception;
use Setting;

class CmsResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cmss = CmsUser::orderBy('created_at' , 'desc')->get();
        return view('admin.cms.index', compact('cmss'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.cms.create');
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
            'name' => 'required|max:255',
            'mobile' => 'digits_between:6,13',
            'email' => 'required|unique:cms_users,email|email|max:255',
            'password' => 'required|min:6|confirmed',
        ]);

        try{

            $CmsUser = $request->all();
            $CmsUser['password'] = bcrypt($request->password);

            $CmsUser = CmsUser::create($CmsUser);

            return back()->with('flash_success','CmsUser Details Saved Successfully');

        } 

        catch (Exception $e) {
            return back()->with('flash_error', 'CmsUser Not Found');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CmsUser  $cms
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CmsUser  $cms
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $cms = CmsUser::findOrFail($id);
            return view('admin.cms.edit',compact('cms'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Dispatcher  $dispatcher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error', 'Disabled for demo purposes! Please contact us at info@rommoz.com');
        }
        

        $this->validate($request, [
            'name' => 'required|max:255',
            'mobile' => 'digits_between:6,13',
        ]);

        try {

            $dispatcher = CmsUser::findOrFail($id);
            $dispatcher->name = $request->name;
            $dispatcher->mobile = $request->mobile;
            $dispatcher->save();

            return redirect()->route('admin.cms.index')->with('flash_success', 'CmsUser Updated Successfully');    
        } 

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'CmsUser Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Dispatcher  $dispatcher
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error', 'Disabled for demo purposes! Please contact us at info@rommoz.com');
        }
        

        try {
            CmsUser::find($id)->delete();
            return back()->with('message', 'CmsUser deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'CmsUser Not Found');
        }
    }

}

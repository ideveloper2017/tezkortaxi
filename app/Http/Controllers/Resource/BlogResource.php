<?php

namespace App\Http\Controllers\Resource;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Setting;
use Exception;
use App\Helpers\Helper;

use App\ServiceType;
use App\Blog;

class BlogResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $blog = Blog::all();
        if($request->ajax()) {
            return $blog;
        } else {
            return view('cms.blog.index', compact('blog'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cms.blog.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error','Disabled for demo purposes! Please contact us at info@rommoz.com');
        }

        $this->validate($request, [
            'en_title' => 'required',
            'en_description' => 'required',
            'ar_title' => 'required',
            'ar_description' => 'required',
            'fr_title' => 'required',
            'fr_description' => 'required',
            'ru_title' => 'required',
            'ru_description' => 'required',
            'sp_title' => 'required',
            'sp_description' => 'required',
            'image' => 'mimes:ico,png,jpeg,jpg'
        ]);

        try {
            $service = $request->all();

            if($request->hasFile('image')) {
                //$service['image'] = Helper::upload_picture($request->image);
                $service['image'] = \URL::to('/storage/app/public/').'/'.$request->image->store('uploads');
            }

            $service = Blog::create($service);

            return back()->with('flash_success','New Blog post created Successfully');
        } catch (Exception $e) {
            dd("Exception", $e);
            return back()->with('flash_error', 'Blog  Not Found');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ServiceType  $serviceType
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            return Blog::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Service Type Not Found');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ServiceType  $serviceType
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $service = Blog::findOrFail($id);
            return view('cms.blog.edit',compact('service'));
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Service Type Not Found');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ServiceType  $serviceType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error','Disabled for demo purposes! Please contact us at info@rommoz.com');
        }

        $this->validate($request, [
            'en_title' => 'required',
            'en_description' => 'required',
            'ar_title' => 'required',
            'ar_description' => 'required',
            'fr_title' => 'required',
            'fr_description' => 'required',
            'ru_title' => 'required',
            'ru_description' => 'required',
            'sp_title' => 'required',
            'sp_description' => 'required',
            'image' => 'mimes:ico,png,jpeg,jpg'
            
        ]);

        try {

            $service = Blog::findOrFail($id);

            if($request->hasFile('image')) {
                if($service->image) {
                    //Helper::delete_picture($service->image);
                    \Storage::delete($service->image);
                }
                //$service->image = Helper::upload_picture($request->image);
                $service->image = \URL::to('/storage/app/public/').'/'.$request->image->store('uploads');
            }

            $service->en_title = $request->en_title;
            $service->en_description = $request->en_description;
            $service->ar_title = $request->ar_title;
            $service->ar_description = $request->ar_description;
            $service->fr_title = $request->fr_title;
            $service->fr_description = $request->fr_description;
            $service->ru_title = $request->ru_title;
            $service->ru_description = $request->ru_description;
            $service->sp_title = $request->sp_title;
            $service->sp_description = $request->sp_description;
            $service->save();

            return redirect()->route('cms.blog.index')->with('flash_success', 'Blog Updated Successfully');    
        } 

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Blog Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ServiceType  $serviceType
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error','Disabled for demo purposes! Please contact us at info@rommoz.com');
        }
        
        try {
            Blog::find($id)->delete();
            return back()->with('message', 'Blog deleted successfully');
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Blog Not Found');
        } catch (Exception $e) {
            return back()->with('flash_error', 'Blog Not Found');
        }
    }
}
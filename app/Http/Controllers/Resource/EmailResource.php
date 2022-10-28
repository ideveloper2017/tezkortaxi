<?php

namespace App\Http\Controllers\Resource;

use App\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Exception;
use Mail;
use Setting;
class EmailResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $email = EmailTemplate::orderBy('created_at' , 'desc')->get();
        return view('cms.email.index', compact('email'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cms.email.create');
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
            'type' => 'required|unique:email_templates',
            'template' => 'required|unique:email_templates'
        ]);

        try{
              $email_template = $request->all(); 
              $email_template = EmailTemplate::create($email_template);
                      
            return back()->with('flash_success','Add Email Template');

        } 

        catch (Exception $e) {
            // dd($e->getMessage());
            return back()->with('flash_error', 'Email Template Not Found');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CrmUser  $crm
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CrmUser  $crm
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $email= EmailTemplate::findOrFail($id);
            return view('cms.email.edit',compact('email'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CrmUser  $crm
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error', 'Disabled for demo purposes! Please contact us at info@rommoz.com');
        }
        
          $this->validate($request, [
            'type' => 'required',
            'template' => 'required',
        ]);

        try {

            $crm = EmailTemplate::findOrFail($id);
            $crm->type = $request->type;
            $crm->template = $request->template;
            $crm->save();

            return back()->with('flash_success', 'Email Template Updated Successfully');    
        } 

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Email Template Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CrmUser  $crm
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error', 'Disabled for demo purposes! Please contact us at info@rommoz.com');
        }
        

        try {
            EmailTemplate::find($id)->delete();
            return back()->with('message', 'Email Template deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'Email Template Not Found');
        }
    }

}

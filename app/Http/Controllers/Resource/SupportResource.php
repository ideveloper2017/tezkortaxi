<?php

namespace App\Http\Controllers\Resource;

use App\SupportUser;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Exception;
use Setting;
use App\Complaint;

class SupportResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $supports = SupportUser::orderBy('created_at' , 'desc')->get();
        return view('admin.support.index', compact('supports'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.support.create');
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
            'email' => 'required|unique:support_users,email|email|max:255',
            'password' => 'required|min:6|confirmed',
        ]);

        try{

            $support = $request->all();
            $support['password'] = bcrypt($request->password);

            $support = SupportUser::create($support);

            return back()->with('flash_success','SupportUser Details Saved Successfully');

        } 

        catch (Exception $e) {
            return back()->with('flash_error', 'SupportUser Not Found');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Dispatcher  $dispatcher
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Dispatcher  $dispatcher
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $support = SupportUser::findOrFail($id);
            return view('admin.support.edit',compact('support'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SupportUser  $support
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

            $support = SupportUser::findOrFail($id);
            $support->name = $request->name;
            $support->mobile = $request->mobile;
            $support->save();

            return redirect()->back()->with('flash_success', 'SupportUser Updated Successfully');    
        } 

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'SupportUser Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SupportUser  $support
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error', 'Disabled for demo purposes! Please contact us at info@rommoz.com');
        }
        

        try {
            SupportUser::find($id)->delete();
            return back()->with('message', 'SupportUser deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'SupportUser Not Found');
        }
    }

    public function openTicket(){

        $data = Complaint::where('status',1)->get();
       
        return view('admin.support.open_ticket', compact('data'));
    }
    public function openTicketDetails($id){

        $data = Complaint::where('id',$id)->first();
        return view('admin.support.open_ticket_details', compact('data'));
    }
    public function transfer($id,Request $request){

        $data = Complaint::where('id',$id)->first();
        $data->status = $request->status;
        $data->transfer = $request->transfer;
        $data->reply = $request->reply;
        $data->save();
        return redirect()->back()->with('flash_success','Ticket Updated');
       
    }
    public function closeTicket(){

        $data = Complaint::where('status',0)->get();
        
        return view('admin.support.close_ticket', compact('data'));
    }

}

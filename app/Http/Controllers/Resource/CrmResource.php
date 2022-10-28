<?php

namespace App\Http\Controllers\Resource;

use App\CrmUser;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Exception;
use Setting;
use App\Complaint;

class CrmResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $crms = CrmUser::orderBy('created_at' , 'desc')->get();
        return view('admin.crm.index', compact('crms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.crm.create');
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
            'email' => 'required|unique:crm_users,email|email|max:255',
            'password' => 'required|min:6|confirmed',
        ]);

        try{

            $crm = $request->all();
            $crm['password'] = bcrypt($request->password);

            $crm = CrmUser::create($crm);

            return back()->with('flash_success','CrmUser Details Saved Successfully');

        } 

        catch (Exception $e) {
            return back()->with('flash_error', 'CrmUser Not Found');
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
            $crm= CrmUser::findOrFail($id);
            return view('admin.crm.edit',compact('crm'));
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
            'name' => 'required|max:255',
            'mobile' => 'digits_between:6,13',
        ]);

        try {

            $crm = CrmUser::findOrFail($id);
            $crm->name = $request->name;
            $crm->mobile = $request->mobile;
            $crm->save();

            return redirect()->route('admin.crm-manager.index')->with('flash_success', 'CrmUser Updated Successfully');    
        } 

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'CrmUser Not Found');
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
            CrmUser::find($id)->delete();
            return back()->with('message', 'CrmUser deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'CrmUser Not Found');
        }
    }

    public function openTicket($type){

        if($type == 'all'){

            $data = Complaint::where('transfer',1)->get();
            $title = 'All Tickets';

        }
        if($type == 'open'){

            $data = Complaint::where('transfer',1)->where('status',1)->get();
            $title = 'Open Tickets';
        }

       
        return view('admin.crm.open_ticket', compact('data','title'));
    }
    public function openTicketDetails($id){

        $data = Complaint::where('id',$id)->first();
        return view('admin.crm.open_ticket_details', compact('data'));
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

        $data = Complaint::where('transfer',1)->where('status',0)->get();
        
        return view('admin.crm.close_ticket', compact('data'));
    }


}

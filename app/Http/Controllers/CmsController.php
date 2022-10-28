<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;
use Setting;
use Auth;
use Exception;
use Carbon\Carbon;
use App\Helpers\Helper;
use Session;

use App\User;
use App\Zones;
use App\CmsUser;
use App\Provider;
use App\UserRequests;
use App\RequestFilter;
use App\ProviderService;
use App\ServiceType;
use App\Blog;
use App\Page;
 


class CmsController extends Controller
{

    /**
     * Dispatcher Panel.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    protected $UserAPI;
	protected $ip_details = null;

     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserApiController $UserAPI)
    {
        //$this->middleware('auth');
        $this->UserAPI = $UserAPI;
		
	}


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
    public function dashboard()
    {
        $services  	= 	ServiceType::all();
		$blogcount = Blog::count();
		$pagecount = Page::count();
		$faqcount = Page::where('en_title','faq')->count();

		$companies  =	DB::table('fleets')->get();
		
        $ip_details =	$this->ip_details;
		if(Auth::guard('admin')->user()){
            $data= "";
            return view('admin.cms',compact('data'));
			
        }elseif(Auth::guard('cms')->user()){
            return view('cms.dashboard', compact('services', 'ip_details', 'companies', 'blogcount', 'pagecount','faqcount'));
        }
    }
	

     /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        return view('cms.account.profile');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function profile_update(Request $request)
    {
        if(Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error', 'Disabled for demo purposes! Please contact us at info@rommoz.com');
        }

        $this->validate($request,[
            'name' => 'required|max:255',
            'mobile' => 'required|digits_between:6,13',
        ]);

        try{
            $account = Auth::guard('cms')->user();
            $account->name = $request->name;
            $account->mobile = $request->mobile;
            $account->save();

            return redirect()->back()->with('flash_success','Profile Updated');
        }

        catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong!');
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function password()
    {
        return view('cms.account.change-password');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function password_update(Request $request)
    {
        if(Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error','Disabled for demo purposes! Please contact us at info@rommoz.com');
        }

        $this->validate($request,[
            'old_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        try {

           $Account = CmsUser::find(Auth::guard('cms')->user()->id);

            if(password_verify($request->old_password, $Account->password))
            {
                $Account->password = bcrypt($request->password);
                $Account->save();

                return redirect()->back()->with('flash_success','Password Updated');
            }
        } catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong!');
        }
    }

    public function translation(){
        try{
            return view('cms.translation');
        }
        catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong!');
        }
    }

    public function faq_remove($id){
    try {
            Page::find($id)->delete();
            return back()->with('message', 'FAQ deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'FAQ Not Found');
        }
    }
}

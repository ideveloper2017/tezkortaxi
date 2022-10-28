<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use Setting;
use App\Card;

class RideController extends Controller
{
    protected $UserAPI;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserApiController $UserAPI)
    {
        $this->middleware('auth');
        $this->UserAPI = $UserAPI;
    }


    /**
     * Ride Confirmation.
     *
     * @return \Illuminate\Http\Response
     */
    public function confirm_ride( Request $request )
    {

        $fare = $this->UserAPI->estimated_fare($request)->getData();		
        
        $service = (new Resource\ServiceResource)->show($request->service_type);
        $cards =  Card::where('user_id', Auth::user()->id)->get();
		
		$ip 	=   \Request::getClientIp(true);
        $ip_details = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip={$ip}"));
		
		
		
        if($request->has('current_longitude') && $request->has('current_latitude'))
        {
            User::where('id',Auth::user()->id)->update([
                'latitude' => $request->current_latitude,
                'longitude' => $request->current_longitude
            ]);
        }

        return view('user.ride.confirm_ride',compact('request','fare','service','cards', 'ip_details'));
    }

    /**
     * Create Ride.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_ride(Request $request)
    {	
        //dd('hii');
        $data = $this->UserAPI->send_request($request);
        
        if(isset($data->getData()->error) && $data->getData()->error=='dashboard')
        {
            return redirect('dashboard');
        }
        return redirect()->back()->with('message', $data->getData()->flash_error ? $data->getData()->flash_error:$data->getData()->error);
    }

    /**
     * Get Request Status Ride.
     *
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request)
    {
        return $this->UserAPI->request_status_check($request);
    }

    /**
     * Cancel Ride.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancel_ride(Request $request)
    {
        return $this->UserAPI->cancel_request($request);
    }

    /**
     * Rate Ride.
     *
     * @return \Illuminate\Http\Response
     */
    public function rate(Request $request)
    {
        return $this->UserAPI->rate_provider($request);
    }
}

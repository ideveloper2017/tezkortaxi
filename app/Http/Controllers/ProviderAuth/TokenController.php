<?php

namespace App\Http\Controllers\ProviderAuth;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Controller;

use Tymon\JWTAuth\Exceptions\JWTException;
use App\Notifications\ResetPasswordOTP;

use Auth;
use Config;
use JWTAuth;
use Setting;
use Notification;
use Validator;
use Socialite;
use App\Helpers\Helper;
use App\Provider;
use App\ProviderDevice;
use App\ProviderService;

class TokenController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    
    
    public function checkEmail(Request $request){
        $this->validate($request, [
            'email' => 'required'
        ]);
        $pr = Provider::where('email',$request->email)->count();
            if($pr != 0){
                return response()->json(['status'=>0,'msg'=>'The email has already been taken.']);
            }else{
                return response()->json(['status'=>1,'msg'=>'You can use this email.']); 
            }
    }
    
     public function checkMobile(Request $request){
        $this->validate($request, [
            'mobile' => 'required'
        ]);
        $pr = Provider::where('mobile',$request->mobile)->count();
        if($pr != 0){
            return response()->json(['status'=>0,'msg'=>'The mobile number has already been taken.']);
        }else{
            return response()->json(['status'=>1,'msg'=>'You can use this mobile number.']); 
        }
    }
    
    
     public function register(Request $request)
    {
        $this->validate($request, [
                'device_id' => 'required',
                'device_type' => 'required|in:android,ios',
                'device_token' => 'required',
                'first_name' => 'required|max:255',
               // 'last_name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:providers',
                'mobile' => 'required',
                'password' => 'required|min:6',
               
            ]);

        try{
            
             $pr = Provider::where('mobile',$request->mobile)->count();
            if($pr != 0){

                return response()->json(['msg'=>'The mobile has already been taken.']);
              }

            $Provider = $request->all();
            $Provider['password'] = bcrypt($request->password);
          
            $Provider = Provider::create($Provider);

            if(Setting::get('demo_mode', 0) == 1) {
                $Provider->update(['status' => 'approved']);
                ProviderService::create([
                    'provider_id' => $Provider->id,
                    'service_type_id' => '1',
                    'status' => 'active',
                    'service_number' => '4pp03ets',
                    'service_model' => 'Audi R8',
                ]);
            }

            ProviderDevice::create([
                    'provider_id' => $Provider->id,
                    'udid' => $request->device_id,
                    'token' => $request->device_token,
                    'type' => $request->device_type,
                ]);
            //sendMail('Registration',$request->email,$request->first_name,'Registration'); 
            return $Provider;


        } catch (QueryException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Something went wrong, Please try again later!'], 500);
            }
            return abort(500);
        }
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function authenticate(Request $request)
    {
        $this->validate($request, [
                'device_id' => 'required',
                'device_type' => 'required|in:android,ios',
                'device_token' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:6',
                //'logged_in' => 'required'
            ]);

        Config::set('auth.providers.users.model', 'App\Provider');
        
        $credentials = $request->only('email', 'password');
        $User = Provider::with('service', 'device')->where('email',$request->email)->first();
        try {
            
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'The email address or password you entered is incorrect.'], 401);
            }
            
            // if(isset($User->device) && $User->device->udid != $request->device_id){
                
            //     return response()->json(['error' => 'Provider is not allowed to login on multiple devices .'], 401);
            // }
            /*Provider::where('id',Auth::user()->id)->update([
        
                'logged_in' => $request->logged_in,
            ]);*/
            
        } catch (JWTException $e) {
            return response()->json(['error' => 'Something went wrong, Please try again later!'], 500);
        }
        /*$User = Provider::with('service', 'device')->find(Auth::user()->id);*/
        $User->access_token = $token;
        $User->currency = Setting::get('currency', '$');
        $User->sos = Setting::get('sos_number', '911');
        
        if($User->device) {
            ProviderDevice::where('id',$User->device->id)->update([
        
                'udid' => $request->device_id,
                'token' => $request->device_token,
                'type' => $request->device_type,
            ]);
            
            
        } else {
            ProviderDevice::create([
                    'provider_id' => $User->id,
                    'udid' => $request->device_id,
                    'token' => $request->device_token,
                    'type' => $request->device_type,
                ]);
            
        }
        
        return response()->json($User);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function logout(Request $request)
    {
        
      
        try {
            ProviderDevice::where('provider_id', $request->id)->delete();
            //Provider::where('id', $request->id)->where('logged_in',1)->update(['logged_in'=> 0]);
            ProviderService::where('provider_id',$request->id)->update(['status' => 'offline']);
            return response()->json(['message' => trans('api.logout_success')]);
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

 /**
     * Forgot Password.
     *
     * @return \Illuminate\Http\Response
     */


    public function forgot_password(Request $request){

        $this->validate($request, [
                'email' => 'required|email|exists:providers,email',
            ]);

        try{  
            
            $provider = Provider::where('email' , $request->email)->first();

            // $otp = mt_rand(100000, 999999);

            // $provider->otp = $otp;
            // $provider->save();

            // Notification::send($provider, new ResetPasswordOTP($otp));

            return response()->json([
                'message' => 'OTP sent to your email!',
                'provider' => $provider
            ]);

        }catch(Exception $e){
                return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }


    /**
     * Reset Password.
     *
     * @return \Illuminate\Http\Response
     */

    public function reset_password(Request $request){

        $this->validate($request, [
                'password' => 'required|confirmed|min:6',
                'id' => 'required|numeric|exists:providers,id'
            ]);

        try{

            $Provider = Provider::findOrFail($request->id);
            $Provider->password = bcrypt($request->password);
            $Provider->save();

            if($request->ajax()) {
                return response()->json(['message' => 'Password Updated']);
            }

        }catch (Exception $e) {
            if($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')]);
            }
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function facebookViaAPI(Request $request) { 

        $validator = Validator::make(
            $request->all(),
            [
                'device_type' 	=>	'required|in:android,ios',
                'device_token' 	=>	'required',
                'accessToken'	=>	'required',
                'device_id' 	=>	'required',
                'login_by' 		=>	'required|in:manual,facebook,google'
            ]
        );
		
        
        if($validator->fails()) {
            return response()->json(['status'=>false,'message' => $validator->messages()->all()]);
        }

	   
        try{
			
			$first_name			=	$request->first_name;
			$last_name			=	($request->last_name ) ? $request->last_name  : '';
			$email				= 	$request->email;
			$social_unique_id 	= 	$request->id;
			$avatar 			=	$request->avatar;
			$login_by			=	$request->login_by;
			
			$AuthUser 			=	Provider::where('email',  $email )->first();
			
            if($AuthUser){ 
			 
				$AuthUser->social_unique_id		=	$social_unique_id;
				$AuthUser->device_type			=	$request->device_type;
                $AuthUser->device_token			=	$request->device_token;
                $AuthUser->device_id			=	$request->device_id;
                $AuthUser->login_by				=	"facebook";
                $AuthUser->save(); 
				
            }else{   
			
				$AuthUser= new Provider();
				$AuthUser->email			=	$email;
				$AuthUser->first_name		= 	$first_name;
				$AuthUser->last_name		= 	$last_name;
				
                
				$AuthUser->password			=	bcrypt( $social_unique_id );
				$AuthUser->social_unique_id	=	$social_unique_id;
				$AuthUser->device_type		=	$request->device_type;
				$AuthUser->device_token		=	$request->device_token;
				$AuthUser->device_id		=	$request->device_id;
				
				$AuthUser->avatar			= 	 $avatar;
				$AuthUser->login_by			=	"facebook";
				
                $AuthUser->save();	
				
                if(Setting::get('demo_mode', 0) == 1) {
                    $AuthUser->update(['status' => 'approved']);
                    ProviderService::create([
                        'provider_id' => $AuthUser->id,
                        'service_type_id' => '1',
                        'status' => 'active',
                        'service_number' => '4pp03ets',
                        'service_model' => 'Audi R8',
                    ]);
                }
            }    
            if($AuthUser){ 
                $userToken = JWTAuth::fromUser($AuthUser);
                $User = Provider::with('service', 'device')->find($AuthUser->id);
                if($User->device) {
                    ProviderDevice::where('id',$User->device->id)->update([
                        
                        'udid' => $request->device_id,
                        'token' => $request->device_token,
                        'type' => $request->device_type,
                    ]);
                    
                } else {
                    ProviderDevice::create([
                        'provider_id' => $User->id,
                        'udid' => $request->device_id,
                        'token' => $request->device_token,
                        'type' => $request->device_type,
                    ]);
                }
                return response()->json([
                            "status" => true,
                            "token_type" => "Bearer",
                            "access_token" => $userToken,
                            'currency' => Setting::get('currency', '$'),
                            'sos' => Setting::get('sos_number', '911')
                        ]);
            }else{
				
                return response()->json(['status'=>false,'message' => "Invalid credentials!"]);
				
            }  
        } catch (Exception $e) {
            return response()->json(['status'=>false,'message' => trans('api.something_went_wrong')]);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function googleViaAPI(Request $request) { 

        $validator = Validator::make(
            $request->all(),
            [
                'device_type' => 'required|in:android,ios',
                'device_token' => 'required',
                'accessToken'=>'required',
                'device_id' => 'required',
                'login_by' => 'required|in:manual,facebook,google'
            ]
        );
        
        if($validator->fails()) {
            return response()->json(['status'=>false,'message' => $validator->messages()->all()]);
        }
        $user = Socialite::driver('google')->stateless();
        $GoogleDrive = $user->userFromToken( $request->accessToken);
       
        try{
            $GoogleSql = Provider::where('social_unique_id',$GoogleDrive->id);
            if($GoogleDrive->email !=""){
                $GoogleSql->orWhere('email',$GoogleDrive->email);
            }
            $AuthUser = $GoogleSql->first();
            if($AuthUser){
                $AuthUser->social_unique_id=$GoogleDrive->id;  
                $AuthUser->login_by="google";
                $AuthUser->save();
            }else{   
                $AuthUser["email"]=$GoogleDrive->email;
                $name = explode(' ', $GoogleDrive->name, 2);
                $AuthUser["first_name"]=$name[0];
                $AuthUser["last_name"]=isset($name[1]) ? $name[1] : '';
                $AuthUser["password"]=($GoogleDrive->id);
                $AuthUser["social_unique_id"]=$GoogleDrive->id;
                $AuthUser["avatar"]=$GoogleDrive->avatar;
                $AuthUser["login_by"]="google";
                $AuthUser = Provider::create($AuthUser);

                if(Setting::get('demo_mode', 0) == 1) {
                    $AuthUser->update(['status' => 'approved']);
                    ProviderService::create([
                        'provider_id' => $AuthUser->id,
                        'service_type_id' => '1',
                        'status' => 'active',
                        'service_number' => '4pp03ets',
                        'service_model' => 'Audi R8',
                    ]);
                }
            }    
            if($AuthUser){
                $userToken = JWTAuth::fromUser($AuthUser);
                $User = Provider::with('service', 'device')->find($AuthUser->id);
                if($User->device) {
                    ProviderDevice::where('id',$User->device->id)->update([
                        
                        'udid' => $request->device_id,
                        'token' => $request->device_token,
                        'type' => $request->device_type,
                    ]);
                    
                } else {
                    ProviderDevice::create([
                        'provider_id' => $User->id,
                        'udid' => $request->device_id,
                        'token' => $request->device_token,
                        'type' => $request->device_type,
                    ]);
                }
                return response()->json([
                            "status" => true,
                            "token_type" => "Bearer",
                            "access_token" => $userToken,
                            'currency' => Setting::get('currency', '$'),
                            'sos' => Setting::get('sos_number', '911')
                        ]);
            }else{
                return response()->json(['status'=>false,'message' => "Invalid credentials!"]);
            }  
        } catch (Exception $e) {
            return response()->json(['status'=>false,'message' => trans('api.something_went_wrong')]);
        }
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function refresh_token(Request $request)
    {

        Config::set('auth.providers.users.model', 'App\Provider');

        $Provider = Provider::with('service', 'device')->find(Auth::user()->id);

        try {
            if (!$token = JWTAuth::fromUser($Provider)) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }

        $Provider->access_token = $token;

        return response()->json($Provider);
    }
}

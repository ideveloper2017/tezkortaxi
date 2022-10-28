<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use App\Http\Controllers\SendPushNotification;
use App\Chat;
use App\SecurityCheckList;
use Auth;
use App\User;
use App\UserRequests;
use App\Provider;
use DB;
class FirebaseController extends Controller
{
    public function msg()
    {
		// This assumes that you have placed the Firebase credentials in the same directory  as this PHP file.
		$serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/firebase.json');

		
		/* The following line is optional if the project id in your credentials file
		// is identical to the subdomain of your Firebase project. If you need it,
		make sure to replace the URL with the URL of your project. */
		
		$firebase = (new Factory)->withServiceAccount($serviceAccount)		
		->withDatabaseUri('https://laravel-420a9.firebaseio.com/')
		->create();

		$database = $firebase->getDatabase();

		$newPost = $database->getReference('chats')
					->set([ 'client_msg' => 'Post title',]);

	}


  public function chatHistory(Request $request){
	 
	   $sub = Chat::orderBy('id','DESC');
		$r = Chat::where('user_id',\Auth::id())->groupBy('request_id')->orderby('id', 'desc')->get();
		
		if( $r->count() ) {
			$status = 1;
			foreach($r as $key=>$value){
				
				$r[$key]['provider'] =  Provider::select('first_name','last_name','avatar')->where('id',$value->provider_id)->get();
			}
		
		} else { 
			
			$r = [];
			$status = 0;
			
		}
		

		return response()->json(['status'=> $status ,"data"=>$r ] );

	}

  
    public function chatHistoryProvider(Request $request){
		
	 	$r = Chat::where('provider_id',\Auth::id())->groupBy('request_id')->get();
		
		if( $r->count() ) {
			$status = 1;
			foreach($r as $key=>$value){	
                $r[$key]['provider'] = User::select('first_name','last_name','picture')->where('id',$value->user_id)->get();
			}
		
		} else {
			$r = [];
			$status = 0;
			
		}

	   	return response()->json(['status'=> $status ,"data"=>$r ] );

	}

}

<?php

namespace App\Http\Controllers\Resource;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\Card;
use Exception;
use Auth;
use Setting;

class PaypalResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try{
			
			$cards = Card::where('user_id',Auth::user()->id)->orderBy('created_at','desc')->get();
			
			$card_count = $cards->count();
			
			if( ! $card_count ) {	
				throw new Exception('No Card added yet!');
			}
			
			if($request->ajax()){
				return response()->json($cards, 200); 
			}else{
				
				return view('user.account.payment',compact('cards')); 
			}
			
        } catch(Exception $e){
			
			if($request->ajax()){
				return response()->json(['error' => $e->getMessage()]);
			}else{
				return back()->with('flash_error',$e->getMessage());
			}			
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //  
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $this->validate($request,[
                'paypal_id' => 	'required',
            ]);

        try{
		
                
				
				$card_list = User::where('id',Auth::user()->id)->where('paypal_id',  Auth::user()->paypal_id )->get();
				$user = Auth::user();
                $user->paypal_id = $request->paypal_id;
                $user->save();
				$flag = false;
				if( $card_list->count() ) {
					foreach( $card_list as  $added_card ) {
						if( $added_card->paypal_id == $request->paypal_id ) {
							$flag = true;
						}
					}
				}
				
				if( !$flag ) {
					
					
					$user = Auth::user();
                    $user->paypal_id = $request->paypal_id;
                    $user->save();
				
				} else {
					
					//$customer->sources->retrieve( $card->id )->delete();
					
					if($request->ajax()){
						return response()->json(['message' => 'Paypal Id Already Added']); 	
					} else {
						return back()->with('flash_error','Paypal Id Already Added');
					}						
				}
				
            if($request->ajax()){
                return response()->json(['message' => 'Paypal Id Added']); 
            }else{
                return back()->with('flash_success','Paypal Id Added');
            }

        } catch(Exception $e){
           // dd($e);
            if($request->ajax()){
                return response()->json(['error' => $e->getMessage()], 500);
            }else{
                return back()->with('flash_error',$e->getMessage());
            }
        } 
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {

        /*$this->validate($request,[
                'id' => 'required',
            ]);*/
			

        try{
			
			$paypal = User::where('id', Auth::user()->id)->where('paypal_id', '=',$id)->first();
            
			if(!$paypal) {
				throw new Exception('Paypal id Not Found');
			}
			
			
			$paypal->paypal_id = '';
            $paypal->save();
			
			
            if($request->ajax()){
                return response()->json(['message' => 'Paypal id Deleted']); 
            }else{
                return back()->with('flash_success','Paypal id Deleted');
            }

        } catch(Exception $e){
            if($request->ajax()){
                return response()->json(['error' => $e->getMessage()]);
            }else{
                return back()->with('flash_error',$e->getMessage());
            }
        }
    }

    /**
     * setting stripe.
     *
     * @return \Illuminate\Http\Response
     */
    public function set_stripe(){
        return \Stripe\Stripe::setApiKey(Setting::get('stripe_secret_key'));
    }

    /**
     * Get a stripe customer id.
     *
     * @return \Illuminate\Http\Response
     */
    public function customer_id()
    {
        if(Auth::user()->stripe_cust_id != null){

            return Auth::user()->stripe_cust_id;

        }else{

            try{

                $stripe = $this->set_stripe();

                $customer = \Stripe\Customer::create([
                    'email' => Auth::user()->email,
                ]);

                User::where('id',Auth::user()->id)->update(['stripe_cust_id' => $customer['id']]);
                return $customer['id'];

            } catch(Exception $e){
                return $e;
            }
        }
    }

}

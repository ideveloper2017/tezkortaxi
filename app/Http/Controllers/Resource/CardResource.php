<?php

namespace App\Http\Controllers\Resource;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\Card;
use Exception;
use Auth;
use Setting;

class CardResource extends Controller
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
        $this->validate($request,[
                'stripe_token' => 	'required',
            ]);

        try{
		
                $this->set_stripe();
				
				if( Auth::user()->stripe_cust_id != null ) {
					$customer 	= \Stripe\Customer::retrieve( Auth::user()->stripe_cust_id );
					$card 		= $customer->sources->create(["source" => $request->stripe_token]);
					
				} else {
					
					$customer 	= \Stripe\Customer::create(["email" => Auth::user()->email]);
					$card 		= $customer->sources->create(["source" => $request->stripe_token]);

					$user = Auth::user();
					$user->stripe_cust_id = $customer->id;
					$user->save();
				
				}
				
				$card_list = Card::where('user_id',Auth::user()->id)->where('stripe_cust_id',  Auth::user()->stripe_cust_id )->get();
				
				$flag = false;
				if( $card_list->count() ) {
					foreach( $card_list as  $added_card ) {
						if( $added_card->card_fingerprint == $card->fingerprint ) {
							$flag = true;
						}
					}
				}
				
				if( !$flag ) {
					
					$create_card = new Card;
					$create_card->card_fingerprint = $card->fingerprint;
					$create_card->user_id = Auth::user()->id;
					$create_card->card_id = $card->id;
					$create_card->stripe_cust_id = $customer->id;
					$create_card->last_four = $card->last4;
					$create_card->brand = $card->brand;
					if( ! $card_list->count() ) {
						$create_card->is_default = 1;
					}
					$create_card->save();
				
				} else {
					
					$customer->sources->retrieve( $card->id )->delete();
					
					if($request->ajax()){
						return response()->json(['message' => 'Card Already Added']); 	
					} else {
						return back()->with('flash_error','Card Already Added');
					}						
				}
				
            if($request->ajax()){
                return response()->json(['message' => 'Card Added']); 
            }else{
                return back()->with('flash_success','Card Added');
            }

        } catch(Exception $e){
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
        

       /* $this->validate($request,[
                'card_id' => 'required|exists:cards,card_id,user_id,'.Auth::user()->id,
            ]);
			*/

        try{
			
			$card = Card::where('user_id', Auth::user()->id)->where('card_id', $id )->first();
            
			if( !$card ) {
				throw new Exception('Card Not Found');
			}
			
			$this->set_stripe();
            $customer = \Stripe\Customer::retrieve( $card->stripe_cust_id );
            $customer->sources->retrieve($id)->delete();
			$customer = Card::where('card_id',$id)->delete();
			
			
            if($request->ajax()){
                return response()->json(['message' => 'Card Deleted']); 
            }else{
                return back()->with('flash_success','Card Deleted');
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

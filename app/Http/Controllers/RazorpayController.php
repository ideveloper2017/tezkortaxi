<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Razorpay\Api\Api;
use Session;
use Auth;
use Redirect;
use App\UserRequestPayment;

class RazorpayController extends Controller
{    
    public function payWithRazorpay()
    {        
        return view('payWithRazorpay');
    }
    public function payThankYou()
    {        
        return view('thankyou');
    }

    public function payment()
    {
        //Input items of form
        $input = Input::all();
        
        //get API Configuration 
        $api = new Api(env('RAZORPAY_KEY') ,env('RAZORPAY_SECRET') );
        //Fetch payment information by razorpay_payment_id
        $payment = $api->payment->fetch($input['razorpay_payment_id']);

        if(count($input)  && !empty($input['razorpay_payment_id'])) {
            try {
                
                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount'=>$payment['amount']));
                
                if($response){

                    $payment = new UserRequestPayment;
                    $payment->payment_id = $input['razorpay_payment_id'];
                    $payment->payment_mode = 'RAZORPAY';
                    $payment->total = $response['amount'];
                    $payment->save();
                }
                

            } catch (\Exception $e) {
                return  $e->getMessage();
                \Session::put('error',$e->getMessage());
                return redirect()->back();
            }

            // Do something here for store payment details in database...
        }
        
        \Session::put('success', 'Payment successful, your order will be despatched in the next 48 hours.');
        return redirect('/payThankYou');
    }
}
?>
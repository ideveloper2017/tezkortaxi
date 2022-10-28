@extends('user.layout.base')

@section('title', 'Payment')

@section('content')

<div class="col-md-12" style="margin-bottom: 20px;">
    <div class="dash-content">
        <div class="row no-margin">
            <div class="col-md-12">
                <h4 class="page-title"><span class="s-icon"><i class="ti-money user-sidebaricon" style="color: rgb(0, 0, 0);"></i></span>&nbsp;@lang('user.payment')</h4>
            </div>
        </div>
        @include('common.notify')
        <div class="row no-margin payment">
            <form method="POST" action="{{url('/paymentmode')}}">  
                 {{ csrf_field() }}
            <input type="hidden" name="_method" value="PATCH">
                            
            <div class="col-md-12">
                <h5 class="btm-border"><strong>@lang('user.payment_method')</strong> 
                @if(Setting::get('CARD') == 1)
                <a href="#" class="sub-right pull-right" data-toggle="modal" data-target="#add-card-modal">@lang('user.card.add_card')</a>
                @endif
            
                @if(Setting::get('PAYPAL') == 1)
            <a href="#" class="sub-right pull-right" data-toggle="modal" data-target="#add-paypal-modal" style="padding-right: 10px;">
            @lang('user.add_paypal')</a>
                @endif
                

                <div class="pay-option">
                    <h6> <input type="checkbox" name="cash" value="CASH" @if(in_array('cash',explode(',',Auth::user()->multiple_payment_method))) checked @endif>@lang('user.cash') </h6>
                </div>
                 <div class="pay-option">
                    <h6> <input type="checkbox" name="razorpay" value="RAZORPAY" @if(in_array('razorpay',explode(',',Auth::user()->multiple_payment_method))) checked @endif>RAZORPAY </h6>
                </div> 
                @if(Setting::get('CARD') == 1)
                @foreach($cards as $card)

                <div class="pay-option">
                    <h6>
                        <input type="checkbox" name="card" value="CARD" @if(in_array('card',explode(',',Auth::user()->multiple_payment_method))) checked @endif> {{$card->brand}} **** **** **** {{$card->last_four}} 
                        @if($card->is_default)
                            <a href="#" class="default">@lang('user.card.default')</a>
                        @endif
                        <a href="{{ route('card.delete', $card->card_id) }}" onclick="return confirm('Are you sure?')" >@lang('user.card.delete')</a>
                        
                        
                        
                    </h6>
                </div>
                @endforeach
                @endif
                 @if(Setting::get('PAYPAL') == 1)
                @foreach($users as $user)
                <div class="pay-option">
                    <h6>
                         <input type="checkbox" name="paypal" value="PAYPAL" @if(in_array('paypal',explode(',',Auth::user()->multiple_payment_method))) checked @endif><p> {{$user->paypal_id}}</p> 
                        @if(@$card->is_default)
                            <a href="#" class="default">@lang('user.card.default')</a>
                        @endif
                        <a href="{{ route('paypal.delete', Auth::user()->paypal_id) }}" onclick="return confirm('Are you sure?')" >@lang('user.card.delete')</a>
                </h5>
                        
                    </h6>
                </div>
                @endforeach
                @endif
                
          <button type="submit" class="form-sub-btn btn-success small box-shadow" style="color: white;">@lang('user.profile.save')</button>
            </div>

            </form>
        </div>

    </div>
</div>
@if(Setting::get('PAYPAL') == 1)

    <!-- Add Card Modal -->
    <div id="add-paypal-modal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" >
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">@lang('user.add_paypal')</h4>
          </div>
        <form id="payment-form_paypal" action="{{ route('paypal.store') }}" method="POST" >
                {{ csrf_field() }}
          <div class="modal-body">
            <div class="row no-margin" id="card-payment">
               <div class="form-group col-md-12 col-sm-12">
                    <label>@lang('user.card.paypal_id')</label>
                    <input  required type="text" name="paypal_id" class="form-control" placeholder="@lang('user.card.paypal_id')">
                </div> 
                
            </div>
          </div>

          <div class="modal-footer">
            <button type="submit" class="full-primary-btn btn-success box-shadow fare-btn">@lang('user.add_paypal')</button>
          </div>
        </form>

        </div>

      </div>
    </div>

    @endif

@if(Setting::get('CARD') == 1)

    <!-- Add Card Modal -->
    <div id="add-card-modal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" >
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">@lang('user.card.add_card')</h4>
          </div>
        <form id="payment-form" action="{{ route('card.store') }}" method="POST" >
                {{ csrf_field() }}
          <div class="modal-body">
            <div class="row no-margin" id="card-payment">
                <div class="form-group col-md-12 col-sm-12">
                    <label>@lang('user.card.fullname')</label>
                    <input data-stripe="name" autocomplete="off" required type="text" class="form-control" placeholder="@lang('user.card.fullname')">
                </div>
                <div class="form-group col-md-12 col-sm-12">
                    <label>@lang('user.card.card_no')</label>
                    <input data-stripe="number" type="text" onkeypress="return isNumberKey(event);" required autocomplete="off" maxlength="16" class="form-control" placeholder="@lang('user.card.card_no')">
                </div>
                <div class="form-group col-md-4 col-sm-12">
                    <label>@lang('user.card.month')</label>
                    <input type="text" onkeypress="return isNumberKey(event);" maxlength="2" required autocomplete="off" class="form-control" data-stripe="exp-month" placeholder="MM">
                </div>
                <div class="form-group col-md-4 col-sm-12">
                    <label>@lang('user.card.year')</label>
                    <input type="text" onkeypress="return isNumberKey(event);" maxlength="2" required autocomplete="off" data-stripe="exp-year" class="form-control" placeholder="YY">
                </div>
                <div class="form-group col-md-4 col-sm-12">
                    <label>@lang('user.card.cvv')</label>
                    <input type="text" data-stripe="cvc" onkeypress="return isNumberKey(event);" required autocomplete="off" maxlength="4" class="form-control" placeholder="@lang('user.card.cvv')">
                </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="submit" class="full-primary-btn btn-success box-shadow fare-btn">@lang('user.card.add_card')</button>
          </div>
        </form>

        </div>

      </div>
    </div>

    @endif

@endsection

@section('scripts')
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>

    <script type="text/javascript">
        Stripe.setPublishableKey("{{ Setting::get('stripe_publishable_key')}}");

         var stripeResponseHandler = function (status, response) {
            var $form = $('#payment-form');

            console.log(response);

            if (response.error) {
                // Show the errors on the form
                $form.find('.payment-errors').text(response.error.message);
                $form.find('button').prop('disabled', false);
                alert(response.error.message);

            } else {
                // token contains id, last4, and card type
                var token = response.id;
                // Insert the token into the form so it gets submitted to the server
                $form.append($('<input type="hidden" id="stripeToken" name="stripe_token" />').val(token));
                jQuery($form.get(0)).submit();
            }
        };
        
        $('#payment-form').submit(function (e) {
            
            if ($('#stripeToken').length == 0)
            {
                console.log('ok');
                var $form = $(this);
                $form.find('button').prop('disabled', true);
                console.log($form);
                Stripe.card.createToken($form, stripeResponseHandler);
                return false;
            }
        });

    </script>
    <script type="text/javascript">
        function isNumberKey(evt)
        {
            var charCode = (evt.which) ? evt.which : event.keyCode;
            if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
                return false;

            return true;
        }
    </script>
@endsection
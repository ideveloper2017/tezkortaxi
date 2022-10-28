@extends('website.app')

@section('title', 'Complaint Form')
@section('content')
<?php
$locale = session()->get('locale');
   if($locale){

      $locale;

   }else{

      $locale = 'en';

   }
 ?>
<style type="text/css">
    .form-control{
        background-color: #ffff !important;
    }
</style>
<div id="contact-page" class="page">    
    <section class="page-content">
        <div class="container">
            <div class="row">                   
                <div class="col-sm-12">
                    <h3>{{ trans('contact_us.contact_us') }}</h3>
                </div>
            </div>
            <div class="row">                   
                <div class="col-sm-6">
                    <h3>{{ trans('contact_us.head') }}</h3>
                    <p>{{ trans('contact_us.para') }}</p>
                    @if(session()->has('message'))
                        <div class="alert alert-success">
                            {{ session()->get('message') }}
                        </div>
                     @endif
                    <div id="contact-form1">
                        <div class="panel-body">
                            <form class="form-horizontal" method="POST" action="{{route('complaint')}}">
                                    {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="your_name">{{ trans('contact_us.name') }}*</label>
                                    <input type="text"  name="name" id="input-name" required  @if( Auth::check() ) value="{{  Auth::user()->first_name }}"  @endif class="form-control" placeholder="{{ trans('contact_us.name') }}*" />                           
                                </div>
                                <div class="form-group">
                                    <label for="your_email">{{ trans('contact_us.email') }}*</label>
                                    <input type="email"  name="email" required @if( Auth::check() ) value="{{  Auth::user()->email }}"  @endif  id="input-email" class="form-control" placeholder="{{ trans('contact_us.email') }}*" />                                                
                                </div>
                  

                                
                                @if( $data['message_cats'] )
                                <div class="form-group">
                                <label for="subject">{{ trans('contact_us.query') }}*</label>
                                <select class="form-control" name="transfer" id="transfer" required>
                                <option value=""> {{ trans('contact_us.please_select') }} </option>
                                <option value="1">{{ trans('contact_us.customer') }}</option>
                                <option value="2">{{ trans('contact_us.dispatcher') }}</option>
                                <option value="3">{{ trans('contact_us.account') }}</option>
                                <option value="4">{{ trans('contact_us.general') }}</option>
                            </select>                         
                                </div>
                                @endif
                                
                                <div class="form-group">
                                    <label for="your_message">{{ trans('contact_us.message') }}*</label>
                                    <textarea class="form-control" required name="message" id="input-message" rows="8" placeholder="{{ trans('contact_us.enter_description') }}*"></textarea>                                                      
                                </div>
                 
                                <div class="buttons">
                                    <input  type="submit" value="{{ trans('contact_us.send_message') }}" data-loading-text="Sending..."  class="btn btn-info btn-block">
                                </div>
                            </form>                    
                        </div>
                    </div>
                </div>
                <div class="col-sm-5 col-sm-offset-1">
                    <div class="contact-info">
                        <h3>{{ trans('contact_us.contact_info') }}</h3>                        
                        <ul class="ul">
                            
                            <li>
                                <p><i class="fa fa-phone"></i> {{ trans('contact_us.call_us') }}</p>
                                <p>xxxxxxxxxx </p>
                            </li>
                            <li>
                                <p><i class="fa fa-comments-o"></i> {{ trans('contact_us.chat') }}</p>
                                <p>xyz</p>
                            </li>
                            <li>
                                <p><i class="fa fa-envelope"></i> {{ trans('contact_us.email1') }}</p>
                                <p>xyz@gmail.com</p>
                            </li>
                        </ul>
                    </div>
                </div> 
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script type="text/javascript">var route='{{route("complaint")}}';</script>
<script type="text/javascript" src="{{ url('/asset/front/js/contact.js') }}"></script>
@endsection
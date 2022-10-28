@extends('website.app')

@section('title', 'Lost Item')
@section('content')
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
                    <h3>Lost Item</h3>
                </div>
            </div>
            <div class="row">                   
                <div class="col-sm-6">
                    <h3>Let's get in touch!</h3>
                    <p>We'd love to hear from you. Tell us a little about yourself and what type of question you have.</p>
                    <div id="contact-form1">
                        <div class="panel-body">
                            <form class="form-horizontal" id="lost_item" method="POST">
                                    {{ csrf_field() }}
                                
                                
                                <div class="form-group">
                                    <label for="your_name">Your Name*</label>
                                    <input type="text"  name="name" id="input-name" required  @if( Auth::check() ) value="{{  Auth::user()->first_name }}"  @endif class="form-control" placeholder="Your Name*" />                           
                                </div>
                                <div class="form-group">
                                    <label for="your_email">Your Email*</label>
                                    <input type="email"  name="email" required @if( Auth::check() ) value="{{  Auth::user()->email }}"  @endif  id="input-email" class="form-control" placeholder="Your Email*" />                                                
                                </div>
                                <div class="form-group">
                                    <label for="your_phone">Lost Item*</label>
                                    <input type="text"  name="lost_item"  required id="lost_item" class="form-control" placeholder="Lost Item*" />                                                       
                                </div>
                                <div class="form-group">
                                    <label for="your_phone">Your Phone*</label>
                                    <input type="text"  name="phone"  required  @if( Auth::check() ) value="{{  Auth::user()->mobile }}"  @endif   id="input-phone" class="form-control" placeholder="e.g( 96-9876543212 )" />                                                       
                                </div>
                                
                                <div class="buttons">
                                    <input  type="button" id="btn-lost-item"  value="SEND REQUEST" data-loading-text="Sending..."  class="btn btn-info btn-block">
                                </div>
                            </form>                    
                        </div>
                    </div>
                </div>
                <div class="col-sm-5 col-sm-offset-1">
                    <div class="contact-info">
                        <h3>Contact Info</h3>
                        <p>We'd love to hear from you. Tell us a little about yourself and what type of question you have.</p>

                        <ul class="ul" style="margin-top: 70px">
                            
                            <li>
                                <p><i class="fa fa-phone"></i> Call Us</p>
                                <p>xxxxxxxxxx </p>
                            </li><hr>
                            <li>
                                <p><i class="fa fa-comments-o"></i> Chat</p>
                                <p>xyz</p>
                            </li><hr>
                            <li>
                                <p><i class="fa fa-envelope"></i> Email</p>
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
<script type="text/javascript">var route='{{route("lost_item_form")}}';</script>
<script type="text/javascript" src="{{ url('/asset/front/js/contact.js') }}"></script>
@endsection
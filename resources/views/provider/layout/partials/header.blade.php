<!-- Header -->
<?php if(@activeOffline()->status=='offline'){
  $status = @activeOffline()->status;
  $check = '';
}else{ $status = 'active';
       $check = 'checked="checked"';} ?>
<div class="site-header">
  <nav class="navbar navbar-light">
    <div class="container-fluid">
      <div class="col-sm-1 col-xs-1">
        <div class="hamburger hamburger--3dy-r">
           <div class="hamburger-box">
            <div class="hamburger-inner"></div>
           </div>
        </div>
      </div>
      <div class="col-sm-2 col-xs-2">
        <div class="navbar-left" style="background-color: #fff;">
          <a class="navbar-brand" href="{{url('provider')}}" style="background:white;">
          <div class="logo">
            <img  style="width: 132px;height: 40px; margin-left: -85px;" src=" {{ url(Setting::get('site_logo', asset('logo-black.png'))) }}">
          </div>
          </a>
        </div>
      </div>
      <div class="col-sm-9 col-xs-9">
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">            
             <ul class="nav navbar-nav navbar-right">                  
               <li class="menu-drop" style="margin-top: 10px;">
                 <div class="dropdown">
                    <form id="form_online" method="POST" action="{{url('/provider/profile/available')}}">
                             <label class="btn-primary" style="background: transparent;color: black;"> @lang('provider.total_revenue')</label>
                             <label class="btn-primary" style="background: transparent;color: black;" id="set_fully_sum"> 00.00</label>
                            <input type="text" value="{{@$status}}" name="service_status" id="active_offline_hdn" readonly />
                            <label class="switch" id="stripe_check">
                            <input type="checkbox" name="CARD" {{$check}}>
                            <span class="slider round"></span>
                          </label>                             
                    </form>
              </div>
          </li>
          <li class="menu-drop">
                     <div class="sl-nav">
                    <ul>
                        <li>
    
                            @php $locale = app()->getLocale(); @endphp
                            @if($locale == 'ar')
                            {{ trans('header.arabic-short') }}
                            @elseif($locale == 'ru')
                            {{ trans('header.russian-short') }}
                            @elseif($locale == 'fr')
                            {{ trans('header.french-short') }}                              
                            @elseif($locale == 'sp')
                            {{ trans('header.spanish-short') }}
                            @else                          
                            {{ trans('header.english-short') }}
                            @endif
    
                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                            <div class="triangle"></div>
                            <ul style=" margin-top: 10px;">
                                <li class="licss"><a href="{{url('lang/en')}}">
                                        <i class="sl-flag fas fa-globe-americas">
                                            <div id="English"></div>
                                        </i>
                                        <span>{{ trans('header.english-short') }}</span>
                                    </a>
                                </li>
                                <li class="licss">
                                    <a href="{{url('lang/ar')}}">
                                        <i class="sl-flag fas fa-globe-americas">
                                            <div id="Arabic"></div>
                                        </i>
                                        <span>{{ trans('header.arabic-short') }}</span>
                                    </a>
                                </li>
                                <li class="licss">
                                    <a href="{{url('lang/fr')}}">
                                        <i class="sl-flag fas fa-globe-americas">
                                            <div id="Arabic"></div>
                                        </i>
                                        <span>{{ trans('header.french-short') }}</span>
                                    </a>
                                </li>
                                <li class="licss">
                                    <a href="{{url('lang/ru')}}">
                                        <i class="sl-flag fas fa-globe-americas">
                                            <div id="Arabic"></div>
                                        </i>
                                        <span>{{ trans('header.russian-short') }}</span>
                                    </a>
                                </li>
                                <li class="licss">
                                    <a href="{{url('lang/sp')}}">
                                        <i class="sl-flag fas fa-globe-americas">
                                            <div id="Arabic"></div>
                                        </i>
                                        <span>{{ trans('header.spanish-short') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                  </li>
        </ul>
        </div>
      </div>
    </div>
  </nav>
</div>
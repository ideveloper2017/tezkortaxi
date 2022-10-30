<?php
$services = get_all_service_types();
?>
<header>

   <div class="container">

      <div class="row">

         <div class="col-md-1 right-padding">

            <div class="manu">

               <div class="hamburger hamburger--3dy-r " style="position: static;">

                  <div class="hamburger-box">

                     <div class="hamburger-inner"></div>

                  </div>

               </div>

               <div class="side_menu" id="left_menu">

                  <div class="menu-list">

                     <div class="container">

                        <div class="border-bottom">

                           <div class="row">

                              <div class="col-md-6">

                                 <div class="right-navbar">

                                    <h3>{{ trans('header.ride_name') }}</h3>

                                    <div class="nav-bar">

                                       <ul>

                                          <li><a href="{{url('ride_overview')}}">{{ trans('header.ride.overview') }}</a></li>

                                          <li><a href="{{url('how_it_works')}}">{{ trans('header.ride.how_it_work') }}</a></li>

                                          <li><a href="{{url('fare_estimate')}}">{{ trans('header.ride.fare_estimator') }}</a></li>

                                          <li><a href="{{url('ride_safety')}}">{{ trans('header.ride.safety') }}</a></li>

                                          <li><a href="{{url('cities')}}">{{ trans('header.ride.cities') }}</a></li>

                                          <li><a href="airports">{{ trans('header.ride.airports') }}</a></li>

                                       </ul>

                                    </div>

                                 </div>

                              </div>

                              <div class="col-md-6">

                                 <div class="left-navbar">

                                    <h3>{{ trans('header.drive_name') }}</h3>

                                    <div class="nav-bar">

                                       <ul>

                                          <li><a href="{{url('drive_overview')}}">{{ trans('header.drive.overview') }}</a></li>

                                          <li><a href="{{url('requirements')}}">{{ trans('header.drive.requirements') }}</a></li>

                                          <li><a href="{{url('driver_app')}}">{{ trans('header.drive.driver_app') }}</a></li>

                                          <li><a href="{{url('vehicle_solutions')}}">{{ trans('header.drive.vehicle_solutions') }}</a></li>

                                          <li><a href="{{url('drive_safety')}}">{{ trans('header.drive.safety') }}</a></li>

                                          <li><a href="{{url('local')}}">{{ trans('header.drive.local_driver_guide') }}</a></li>

                                       </ul>

                                    </div>

                                 </div>

                              </div>

                           </div>

                        </div>

                     </div>

                  </div>

                  <div class="eats">

                     <div class="container">

                        <h3>{{ trans('header.cabs_name') }}</h3>

                        <ul>
                           @foreach($services as $service)
                           <li><a href="{{url('mylift/'.strtolower($service->name))}}">{{ trans('header.mylift') }} {{$service->name}}</a></li>
                           @endforeach

                        </ul>

                     </div>

                  </div>

                  <div class="delhi-eng">

                     <div class="container">

                        <div class="language-city">

                           <div class="row">

                              <div class="col-md-6">

                                 <div class="city1">

                                    <svg viewBox="0 0 64 64" width="20px" height="20px" class="_style_30FFBp _style_4wJp4e" data-reactid="154">

                                       <path d="M32.0000114,4c-11.0449219,0-20,8.8027344-20,20c0,6.0996094,2,11.03125,6,16.9375l13.1807709,18.6359062 C31.3796368,59.8575249,31.6893368,59.9997215,31.9991455,60c0.3104134,0.0002785,0.6209373-0.141922,0.820179-0.4265938 L46.0000114,40.9375c4-5.90625,6-10.8378906,6-16.9375C52.0000114,12.8027344,43.0449333,4,32.0000114,4z M32.0000114,36.25 c-6.7655029,0-12.25-5.484375-12.25-12.25s5.4844971-12.25,12.25-12.25s12.25,5.484375,12.25,12.25 S38.7655144,36.25,32.0000114,36.25z" data-reactid="155" fill="#fff"></path>

                                    </svg>

                                    <h3>Toronto</h3>

                                 </div>

                              </div>

                              <div class="col-md-6">

                                 <div class="city1">

                                    <a class="_style_1wF5gW" href="#" data-reactid="159">

                                       <svg viewBox="0 0 64 64" width="20px" height="20px" class="_style_30FFBp _style_4wJp4e" data-reactid="160">

                                          <path d="M33.7021484,4.5913086c-0.3100586-0.0356445-0.6191406-0.0869141-0.934082-0.0869141 c-0.0917969,0-0.1821289,0.0224609-0.2734375,0.0258789c-0.1640625-0.003418-0.3251953-0.0258789-0.4907227-0.0258789 c-15.4384766,0-28,12.5615234-28,28c0,15.4404297,12.5615234,28,28,28c0.1655273,0,0.3266602-0.0224609,0.4907227-0.0258789 c0.0913086,0.003418,0.1816406,0.0258789,0.2734375,0.0258789c0.3149414,0,0.6240234-0.0512695,0.934082-0.0869141 c14.6474609-0.8842773,26.3017578-13.046875,26.3017578-27.9130859S48.3496094,5.4755859,33.7021484,4.5913086z M23.6225586,9.4985352c-2.8369141,3.2744141-5.1401367,7.9755859-6.4057617,13.527832 c-3.934082-0.4277344-6.3081055-0.9248047-7.2651367-1.1547852C12.7148438,16.1616211,17.6171875,11.6938477,23.6225586,9.4985352z M7.4907227,32.5043945c0-2.5683594,0.4008789-5.0439453,1.1357422-7.3710938 c0.6674805,0.1816406,3.2211914,0.8120117,7.9482422,1.3364258c-0.2714844,1.9399414-0.4262695,3.9550781-0.4262695,6.034668 c0,2.6425781,0.2529297,5.1772461,0.6821289,7.5864258c-4.0546875,0.4394531-6.5522461,0.9589844-7.6733398,1.2304688 C8.0957031,38.5810547,7.4907227,35.6152344,7.4907227,32.5043945z M10.6801758,44.5527344 c1.269043-0.2705078,3.5415039-0.6787109,6.8984375-1.0292969c1.3217773,4.8876953,3.4707031,9.0180664,6.0439453,11.9868164 C18.1206055,53.5,13.5366211,49.5869141,10.6801758,44.5527344z M30,56.6972656 c-3-1.2558594-7.4179688-6.4589844-9.2285156-13.4731445C23.375,43.03125,27,42.8925781,30,42.8574219V56.6972656z M30,39.3706055 c-3,0.0385742-7.0830078,0.1928711-9.8671875,0.4086914C19.75,37.4692383,19.640625,35.0249023,19.640625,32.5043945 c0-1.9604492,0.0385742-3.8681641,0.2749023-5.7114258C22.7431641,27.0166016,27,27.1777344,30,27.2177734V39.3706055z M30,23.730957c-3-0.0371094-6.9223633-0.1884766-9.6035156-0.394043C22.0708008,15.5424805,27,9.6606445,30,8.3115234V23.730957z M53.9663086,21.6801758c-0.546875,0.1689453-2.7631836,0.7866211-7.1459961,1.3061523 c-0.9892578-5.2597656-2.7744141-9.7421875-5.1020508-12.9780273 C47.0551758,12.3212891,51.3945312,16.4853516,53.9663086,21.6801758z M34,8.2597656 c3,1.2451172,7.9438477,7.1796875,9.5874023,15.0722656C40.9482422,23.5424805,37,23.6953125,34,23.7324219V8.2597656z M34,27.2177734c3-0.0385742,7.2045898-0.2041016,9.996582-0.4360352c0.2299805,1.8466797,0.2885742,3.7573242,0.2885742,5.7226562 c0,2.5234375-0.0893555,4.9711914-0.4624023,7.2841797C41.0742188,39.5668945,37,39.4072266,34,39.3706055V27.2177734z M34,56.7490234V42.8574219c3,0.0351562,6.6523438,0.1767578,9.222168,0.375C41.4418945,50.3393555,37,55.5878906,34,56.7490234z M41.7182617,55.0019531c2.0976562-2.9155273,3.7685547-6.8291016,4.7993164-11.4272461 c3.4487305,0.3974609,5.6196289,0.8588867,6.715332,1.1357422C50.6064453,49.2602539,46.5742188,52.8969727,41.7182617,55.0019531z M47.1386719,40.1313477c0.3535156-2.4189453,0.5566406-4.9682617,0.5566406-7.6269531 c0-2.0942383-0.1254883-4.1206055-0.3476562-6.0732422c5.3911133-0.6464844,7.7763672-1.4570312,7.934082-1.5131836 L54.71875,23.3271484c1.1518555,2.8388672,1.7998047,5.9316406,1.7998047,9.1772461 c0,3.1831055-0.6289062,6.2177734-1.7387695,9.0102539C53.8164062,41.2329102,51.3945312,40.6313477,47.1386719,40.1313477z" data-reactid="161" fill="#fff"></path>

                                       </svg>

                                    </a>

                                    <h3>English</h3>

                                 </div>

                              </div>

                           </div>

                        </div>

                     </div>

                  </div>

               </div>

            </div>

         </div>

         <div class="col-md-2 right-padding">

            <div class="logo" >

            <a href="{{ url('/') }}"><img src="{{ Setting::get('site_logo') }}" height="100%" style="margin-top: 7%;"/></a>

            </div>

         </div>

         <div class="col-md-4 left-padding hidden-xs">

            <div class="left-header">

               <div class="left-header">

               </div>

               <div class="left-header drive">

               </div>

            </div>

         </div>

         <div class="col-md-5 help">

            <div class="right"  style="float:right;">

               <ul class="hidden-xs">

                  @if(empty(Auth::user()->id))

                  <li><a href="{{url('/PassengerSignin')}}">{{ strtoupper(trans('header.li_sign_in')) }}</a></li>

                  @else

                  <li><a href="{{url('/logout')}}" onclick="event.preventDefault();

                     document.getElementById('logout-form').submit();">{{ trans('header.logout') }}</a></li>

                  @endif

                  <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">

                     {{ csrf_field() }}

                  </form>

               </ul>



               <div class="driver-btn">

               <a href="{{ url('/provider/register') }}" class="btn btn-default button-shadow" style="background-color: #000000;border-color:#000000;">{{ strtoupper(trans('header.li_became_a_driver')) }}</a>

               </div>
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
                            <ul>
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
            </div>


         </div>

      </div>

   </div>

</header>

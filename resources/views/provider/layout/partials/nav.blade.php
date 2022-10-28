<div class="site-sidebar">

   <div class="custom-scroll custom-scroll-light">

      <div style="margin0: 30px;">

         <div class="user-img">

            <?php $profile_image = img(Auth::user()->picture); ?>

            <div class="pro-img" style="background-image: url({{ Auth::guard('provider')->user()->avatar ? asset('storage/app/public/'.Auth::guard('provider')->user()->avatar) : asset('asset/front_dashboard/img/provider.jpg') }});margin-top: 15px;"></div>

            <h4 style="color: #ffffff;text-align: center;">{{Auth::user()->first_name}}</h4>

         </div>

         <div style="margin-top: 30px;">

            <ul class="sidebar-menu">

               <li>

                  <a href="{{ route('provider.index') }}" class="waves-effect waves-light">

                  <span class="s-icon"><i class="ti-control-record user-sidebaricon"></i></span>

                  <span class="s-text user-dashboard">@lang('provider.dashboard')</span>

                  </a>

               </li>

               <li>

                  <a href="{{ route('provider.dailyearning') }}" class="waves-effect waves-light">

                  <span class="s-icon"><i class="ti-car user-sidebaricon"></i></span>

                  <span class="s-text user-dashboard">@lang('provider.list_ride')</span>

                  </a>

               </li>

               <li class="with-sub">

                  <a href="#" class="waves-effect waves-light">

                  <span class="s-caret"><i class="fa fa-angle-down user-sidebaricon"></i></span>

                  <span class="s-icon"><i class="ti-money user-sidebaricon"></i></span>

                  <span class="s-text user-dashboard">@lang('provider.earnings')</span>

                  </a>

                  <ul>

                     <li><a href="{{ route('provider.weeklyearning') }}"><span class="s-text user-dashboard">@lang('provider.week_erning')</span></a></li>

                     <li><a href="{{ route('provider.yearlyearning') }}"><span class="s-text user-dashboard">@lang('provider.daily_erning')</span></a></li>

                  </ul>

               </li>

               <li>

                  <a href="{{ route('provider.upcoming')}}" class="waves-effect waves-light">

                  <span class="s-icon"><i class="ti-palette user-sidebaricon"></i></span>

                  <span class="s-text user-dashboard">@lang('provider.scheduled_rides')</span>

                  </a>

               </li>

               <li>

                  <a href="{{ route('provider.profile.profile') }}" class="waves-effect waves-light">

                  <span class="s-icon"><i class="ti-user user-sidebaricon"></i></span>

                  <span class="s-text user-dashboard">@lang('provider.my_profile')</span>

                  </a>

               </li>

               <li>

                  <a href="{{ route('provider.documents.index') }}" class="waves-effect waves-light">

                  <span class="s-icon"><i class="ti-layout-tab user-sidebaricon"></i></span>

                  <span class="s-text user-dashboard">@lang('provider.my_documents')</span>

                  </a>

               </li>

               <li>

                  <a href="{{ url('/provider/logout') }}"

                     onclick="event.preventDefault();

                     document.getElementById('logout-form').submit();" class="waves-effect waves-light">

                  <span class="s-icon"><i class="ti-power-off user-sidebaricon"></i></span>

                  <span class="s-text user-dashboard">@lang('provider.logout')</span>

                  </a>

               </li>

               <form id="logout-form" action="{{ url('/provider/logout') }}" method="POST" style="display: none;">

                  {{ csrf_field() }}

               </form>

            </ul>

         </div>

      </div>

   </div>

</div>
<div class="site-sidebar">

   <div class="custom-scroll">

      <ul class="sidebar-menu">

         <li>

            <a href="{{ route('admin.dashboard') }}" class="waves-effect waves-light">

            <span class="s-icon"><i class="ti-control-record"></i></span>

            <span class="s-text">Dashboard</span>

            </a>

         </li>

         <li class="with-sub">

            <a href="#" class="waves-effect  waves-light">

            <span class="s-caret"><i class="fa fa-angle-down"></i></span>

            <span class="s-icon"><i class="ti-stats-up"></i></span>

            <span class="s-text">Settlement</span>

            </a>

            <ul>

               <li class="with-sub">

                  <a href="#" class="waves-effect  waves-light">

                  <span class="s-text">Account Info</span>

                  <span class="s-caret"><i class="fa fa-angle-down"></i></span></a>

                  <ul>

                     <li><a href="{{ url('admin/new_account') }}">New Account</a></li>

                     <li><a href="{{ url('admin/approved_account') }}">Approved Account</a></li>

                  </ul>

               </li>

               <li class="with-sub">

                  <a href="#" class="waves-effect  waves-light">

                  <span class="s-text">Withdraw Info</span>

                  <span class="s-caret"><i class="fa fa-angle-down"></i></span></a>

                  <ul>

                     <li><a href="{{ url('admin/new_withdraw') }}">Withdraw Requests</a></li>

                     <li><a href="{{ url('admin/approved_withdraw') }}">Approved Withdraw</a></li>

                  </ul>

               </li>

            </ul>

         </li>

         <li class="with-sub">

            <a href="#" class="waves-effect  waves-light">

            <span class="s-caret"><i class="fa fa-angle-down"></i></span>

            <span class="s-icon"><i class="ti-zoom-in"></i></span>

            <span class="s-text">Zone</span>

            </a>

            <ul>

               <li><a href="{{ route('admin.zone.index') }}">All Zone</a></li>

               <li><a href="{{ route('admin.zone.create') }}">Add Zone</a></li>

            </ul>

         </li>

         <li class="with-sub">

            <a href="#" class="waves-effect  waves-light">

            <span class="s-caret"><i class="fa fa-angle-down"></i></span>

            <span class="s-icon"><i class="ti-comments"></i></span>

            <span class="s-text">Push Notification</span>

            </a>

            <ul>

               <li><a href="{{ route('admin.pushnotification.index') }}">All Push Notification</a></li>

               <li><a href="{{ route('admin.pushnotification.create') }}">Add Push Notification</a></li>

            </ul>

         </li>

         <li>

            <a href="{{ route('admin.heatmap') }}" class="waves-effect waves-light">

            <span class="s-icon"><i class="ti-flickr-alt"></i></span>

            <span class="s-text">Bird Eye</span>

            </a>

         </li>

         <li class="with-sub">

            <a href="#" class="waves-effect  waves-light">

            <span class="s-caret"><i class="fa fa-angle-down"></i></span>

            <span class="s-icon"><i class="ti-user"></i></span>

            <span class="s-text">Users</span>

            </a>

            <ul>

               <li><a href="{{ route('admin.user.index') }}">All User</a></li>

               <li><a href="{{ route('admin.user.create') }}">Add User</a></li>

            </ul>

         </li>

         <li class="with-sub">

            <a href="{{ route('admin.contact') }}" class="waves-effect  waves-light">

            <span class="s-icon"><i class="ti-comment-alt"></i></span>

            <span class="s-text">Query</span>

            </a>

         </li>

         <li class="with-sub">

            <a href="#" class="waves-effect  waves-light">

            <span class="s-caret"><i class="fa fa-angle-down"></i></span>

            <span class="s-icon"><i class="ti-infinite"></i></span>

            <span class="s-text">Driver</span>

            </a>

            <ul>

               <li><a href="{{ route('admin.provider.index') }}">All Driver</a></li>

               <li><a href="{{ route('admin.provider.create') }}">Add Driver</a></li>

            </ul>

         </li>

         <li class="with-sub">

            <a href="#" class="waves-effect  waves-light">

            <span class="s-caret"><i class="fa fa-angle-down"></i></span>

            <span class="s-icon"><i class="ti-headphone"></i></span>

            <span class="s-text">Dispatcher</span>

            </a>

            <ul>

               <li><a href="{{ route('admin.dispatch-manager.index') }}">All Dispatcher</a></li>

               <li><a href="{{ route('admin.dispatch-manager.create') }}">Add Dispatcher</a></li>

            </ul>

         </li>

         <li class="with-sub">

            <a href="#" class="waves-effect  waves-light">

            <span class="s-caret"><i class="fa fa-angle-down"></i></span>

            <span class="s-icon"><i class="ti-rocket"></i></span>

            <span class="s-text">Vendor</span>

            </a>

            <ul>

               <li><a href="{{ route('admin.fleet.index') }}">List Vendors</a></li>

               <li><a href="{{ route('admin.fleet.create') }}">Add New Vendor</a></li>

            </ul>

         </li>

         <li class="with-sub">

            <a href="#" class="waves-effect  waves-light">

            <span class="s-caret"><i class="fa fa-angle-down"></i></span>

            <span class="s-icon"><i class="ti-layout-grid2-thumb"></i></span>

            <span class="s-text">Account</span>

            </a>

            <ul>

               <li><a href="{{ route('admin.account-manager.index') }}">All Accounts</a></li>

               <li><a href="{{ route('admin.account-manager.create') }}">Add Account</a></li>

            </ul>

         </li>

         <li class="with-sub">

            <a href="#" class="waves-effect  waves-light">

            <span class="s-caret"><i class="fa fa-angle-down"></i></span>

            <span class="s-icon"><i class="ti-files"></i></span>

            <span class="s-text">Finance</span>

            </a>

            <ul>

               <li><a href="{{ route('admin.ride.statement') }}">Trip Revenue</a></li>

               <li><a href="{{ route('admin.payment') }}">Trip History</a></li>

               <li><a href="{{ route('admin.ride.statement.provider') }}">Driver Earning</a></li>

               <li><a href="{{ route('admin.ride.statement.today') }}">Daily Revenue</a></li>

               <li><a href="{{ route('admin.ride.statement.monthly') }}">Monthly Revenue</a></li>

               <li><a href="{{ route('admin.ride.statement.yearly') }}">Yearly Revenue</a></li>

            </ul>

         </li>

         <li>

            <a href="{{ route('admin.map.index') }}" class="waves-effect waves-light">

            <span class="s-icon"><i class="ti-map-alt"></i></span>

            <span class="s-text">Live Location</span>

            </a>

         </li>

         <li class="with-sub">

            <a href="#" class="waves-effect  waves-light">

            <span class="s-caret"><i class="fa fa-angle-down"></i></span>

            <span class="s-icon"><i class="ti-star"></i></span>

            <span class="s-text">Ratings &amp; Reviews</span>

            </a>

            <ul>

               <li><a href="{{ route('admin.user.review') }}">User Ratings</a></li>

               <li><a href="{{ route('admin.provider.review') }}">Driver Ratings</a></li>

            </ul>

         </li>

         <li class="with-sub">
				<a href="#" class="waves-effect  waves-light">
					<span class="s-caret"><i class="fa fa-angle-down"></i></span>
					<span class="s-icon"><i class="ti-pie-chart"></i></span>
					<span class="s-text">All Rides</span>
				</a>
				<ul>
					<li><a href="{{ route('admin.requests.index') }}">Ongoing Ride</a></li>
					
					<li><a href="{{ route('admin.requests.cancel') }}">Cancelled Ride</a></li>
					<li><a href="{{ route('admin.requests.completed') }}">Completed Ride</a></li>
				</ul>
			</li> 
         <li>

            <a href="{{ route('admin.requests.scheduled') }}" class="waves-effect  waves-light">

            <span class="s-icon"><i class="ti-timer"></i></span>

            <span class="s-text">Scheduled Ride</span>

            </a>

         </li>

         <li class="with-sub">

            <a href="#" class="waves-effect  waves-light">

            <span class="s-caret"><i class="fa fa-angle-down"></i></span>

            <span class="s-icon"><i class="ti-car"></i></span>

            <span class="s-text">Vehicle</span>

            </a>

            <ul>

               <li><a href="{{ route('admin.service.index') }}">All Vehicle</a></li>

               <li><a href="{{ route('admin.service.create') }}">Add Vehicle</a></li>

            </ul>

         </li>

         <li class="with-sub">

            <a href="#" class="waves-effect  waves-light">

            <span class="s-caret"><i class="fa fa-angle-down"></i></span>

            <span class="s-icon"><i class="ti-layout-media-overlay-alt-2"></i></span>

            <span class="s-text">Vehicle Mapping</span>

            </a>

            <ul>

               <li><a href="{{ url('admin/allocation_list') }}">Mapped Vehicle</a></li>

               <li><a href="{{ url('admin/allocation') }}">New Map</a></li>

            </ul>

         </li>

         <li class="with-sub">

            <a href="#" class="waves-effect  waves-light">

            <span class="s-caret"><i class="fa fa-angle-down"></i></span>

            <span class="s-icon"><i class="ti-layout-media-overlay"></i></span>

            <span class="s-text">Fare Settings</span>

            </a>

            <ul>

               <li><a href="{{ route('admin.fare_settings') }}">Fare Plan List</a></li>

               <li><a href="{{ route('admin.fare.settings.create') }}">Add New Fare Plan</a></li>

            </ul>

         </li>

         <li class="with-sub">

            <a href="#" class="waves-effect  waves-light">

            <span class="s-caret"><i class="fa fa-angle-down"></i></span>

            <span class="s-icon"><i class="ti-exchange-vertical"></i></span>

            <span class="s-text">Support</span>

            </a>

            <ul>

               <li><a href="{{ route('admin.support-manager.index') }}">All Executive</a></li>

               <li><a href="{{ route('admin.support-manager.create') }}">Add New Executive</a></li>

            </ul>

            <ul>


               <li><a href="{{ url('admin/support/open-ticket') }}">Open Ticket</a></li>

               <li><a href="{{ url('admin/support/close-ticket') }}">Close Ticket</a></li>


            </ul>

         </li>

         <li class="with-sub">

            <a href="#" class="waves-effect  waves-light">

            <span class="s-caret"><i class="fa fa-angle-down"></i></span>

            <span class="s-icon"><i class="ti-layout-tab"></i></span>

            <span class="s-text">Documents</span>

            </a>

            <ul>

               <li><a href="{{ route('admin.document.index') }}">All Documents</a></li>

               <li><a href="{{ route('admin.document.create') }}">Add Document</a></li>

            </ul>

         </li>

         <li class="with-sub">

            <a href="#" class="waves-effect  waves-light">

            <span class="s-caret"><i class="fa fa-angle-down"></i></span>

            <span class="s-icon"><i class="ti-map"></i></span>

            <span class="s-text">Location</span>

            </a>

            <ul>

               <li><a href="{{ route('admin.country.index') }}">All Countries</a></li>

               <li><a href="{{ route('admin.state.index') }}">All States</a></li>

               <li><a href="{{ route('admin.city.index') }}">All Cities</a></li>

               <li><a href="{{ route('admin.location.create') }}">Add New</a></li>

            </ul>

         </li>

         <li class="with-sub">

            <a href="#" class="waves-effect  waves-light">

            <span class="s-caret"><i class="fa fa-angle-down"></i></span>

            <span class="s-icon"><i class="ti-layout-media-left-alt"></i></span>

            <span class="s-text">CMS</span>

            </a>

            <ul>

               <li><a href="{{ url('/admin/page') }}">All Pages</a></li>

               <li><a href="{{ url('/admin/page/create') }}">Add Pages</a></li>

               <li><a href="{{ route('admin.cms-manager.index') }}">CMS Executive</a></li>

               <li><a href="{{ route('admin.cms-manager.create') }}">Add New Executive</a></li>

               <!--<li><a href="#">CMS Users</a></li>

                  <li><a href="#">Add User</a></li>-->

               <li><a href="{{url('admin/translation')}}">Translation</a></li>

               <li><a href="{{url('admin/translation')}}">Add New Translation</a></li>

               <li><a href="{{ url('/admin/blog') }}">All Blog</a></li>

               <li><a href="{{ url('/admin/page/create') }}">Add New Blogs</a></li>

               <li><a href="{{ url('admin/how-it-work/') }}">All How it Work</a></li>

               <li><a href="{{ url('admin/how-it-work/create') }}">Add How it Work</a></li>

               <li><a href="{{ url('admin/faqs/') }}">All FAQ</a></li>

               <li><a href="{{ url('admin/faqs/create') }}">Add New</a></li>
            </ul>

         </li>

         <li class="with-sub">

            <a href="#" class="waves-effect  waves-light">

            <span class="s-caret"><i class="fa fa-angle-down"></i></span>

            <span class="s-icon"><i class="ti-layout-media-right"></i></span>

            <span class="s-text">CRM</span>

            </a>

            <ul>

               <li><a href="{{ route('admin.crm-manager.index') }}">CRM Executive</a></li>

               <li><a href="{{ route('admin.crm-manager.create') }}">Add New Executive</a></li>

               <li><a href="{{ url('admin/crm/open-ticket/all') }}">All Ticket</a></li> 

               <li><a href="{{ url('admin/crm/open-ticket/open') }}">Open Ticket</a></li>

               <li><a href="{{ url('admin/crm/close-ticket') }}">Close Ticket</a></li>

               <!-- <li><a href="#">Activity</a></li> -->

            </ul>

         </li>

         <li class="with-sub">

            <a href="#" class="waves-effect  waves-light">

            <span class="s-caret"><i class="fa fa-angle-down"></i></span>

            <span class="s-icon"><i class="ti-bookmark-alt"></i></span>

            <span class="s-text">Promo Code</span>

            </a>

            <ul>

               <li><a href="{{ route('admin.promocode.index') }}">List Promo Code</a></li>

               <li><a href="{{ route('admin.promocode.create') }}">Add New Promo Code</a></li>

               <li><a href="{{ route('admin.promocode.users') }}">Promo Code User</a></li>

            </ul>

         </li>

         <li>

            <a href="{{ route('admin.payment') }}" class="waves-effect  waves-light">

            <span class="s-icon"><i class="ti-download"></i></span>

            <span class="s-text">Payment History</span>

            </a>

         </li>

         <li>

            <a href="{{ route('admin.settings.payment') }}" class="waves-effect  waves-light">

            <span class="s-icon"><i class="ti-money"></i></span>

            <span class="s-text">Payment Settings</span>

            </a>

         </li>

         <li>

            <a href="{{ route('admin.settings') }}" class="waves-effect  waves-light">

            <span class="s-icon"><i class="ti-settings"></i></span>

            <span class="s-text">Business Settings</span>

            </a>

         </li>

         <li class="with-sub">

            <a href="#" class="waves-effect  waves-light">

            <span class="s-caret"><i class="fa fa-angle-down"></i></span>

            <span class="s-icon"><i class="ti-themify-favicon"></i></span>

            <span class="s-text">Testimonials</span>

            </a>

            <ul>

               <li><a href="{{ route('admin.testimonial.index') }}">List Testimonials</a></li>

               <li><a href="{{ route('admin.testimonial.create') }}">Add New Testimonial</a></li>

            </ul>

         </li>

         <li class="compact-hide">

            <a href="{{ url('/admin/logout') }}"

               onclick="event.preventDefault();

               document.getElementById('logout-form').submit();">

            <span class="s-icon"><i class="ti-power-off"></i></span>

            <span class="s-text">Logout</span>

            </a>

            <form id="logout-form" action="{{ url('/admin/logout') }}" method="POST" style="display: none;">

               {{ csrf_field() }}

            </form>

         </li>

      </ul>

   </div>

</div>
<div class="site-sidebar">
	<div class="custom-scroll custom-scroll-light">
		<ul class="sidebar-menu">
			<li class="active">
				<a href="{{ route('support.dashboard') }}" class="waves-effect waves-light">
					<span class="s-icon"><i class="ti-control-record"></i></span>
					<span class="s-text">Dashboard</span>
				</a>
			</li> 
			<li> 
				<a href="{{ route('support.openTicket', 'new') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="ti-id-badge"></i></span>
					<span class="s-text">New Ticket</span>
				</a>  
			</li>
			<li>

				<a href="{{ route('support.openTicket', 'open') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="ti-receipt"></i></span>
					<span class="s-text">Open Ticket</span>
				</a>  
			</li>
			<li>
			    <a href="{{ route('support.closeTicket') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="ti-agenda"></i></span>
					<span class="s-text">Close Ticket</span>
				</a>  
			</li>
			<li>
			    <a href="{{ route('support.profile') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="ti-user"></i></span>
					<span class="s-text">Profile</span>
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

                     <li><a href="{{ url('support/new_account') }}">New Account</a></li>

                     <li><a href="{{ url('support/approved_account') }}">Approved Account</a></li>

                  </ul>

               </li>

               <li class="with-sub">

                  <a href="#" class="waves-effect  waves-light">

                  <span class="s-text">Withdraw Info</span>

                  <span class="s-caret"><i class="fa fa-angle-down"></i></span></a>

                  <ul>

                     <li><a href="{{ url('support/new_withdraw') }}">Withdraw Requests</a></li>

                     <li><a href="{{ url('support/approved_withdraw') }}">Approved Withdraw</a></li>

                  </ul>

               </li>

            </ul>

         </li>
			<li class="compact-hide" style="margin-bottom: -16px;">
				<a href="{{ url('/support/logout') }}"
                            onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
					<span class="s-icon"><i class="ti-power-off"></i></span>
					<span class="s-text">Logout</span>
                </a>

                <form id="logout-form" action="{{ url('/support/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
			</li>
			
		</ul>
	</div>
</div>
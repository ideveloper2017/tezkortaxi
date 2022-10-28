<div class="site-sidebar">
	<div class="custom-scroll custom-scroll-light">
		<ul class="sidebar-menu">
			<li class="menu-title">Account Dashboard</li>
			<li>
				<a href="{{ route('account.dashboard') }}" class="waves-effect waves-light">
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

                     <li><a href="{{ url('account/new_account') }}">New Account</a></li>

                     <li><a href="{{ url('account/approved_account') }}">Approved Account</a></li>

                  </ul>

               </li>

               <li class="with-sub">

                  <a href="#" class="waves-effect  waves-light">

                  <span class="s-text">Withdraw Info</span>

                  <span class="s-caret"><i class="fa fa-angle-down"></i></span></a>

                  <ul>

                     <li><a href="{{ url('account/new_withdraw') }}">Withdraw Requests</a></li>

                     <li><a href="{{ url('account/approved_withdraw') }}">Approved Withdraw</a></li>

                  </ul>

               </li>

            </ul>

         </li>
			<!--<li class="menu-title">Accounts Statements</li>-->
			<li>
				<a href="{{ route('account.ride.statement') }}" class="waves-effect waves-light">
					<span class="s-icon"><i class="ti-harddrives"></i></span>
					<span class="s-text">Ride Statments</span>
				</a>
			</li>
			<li>
				<a href="{{ route('account.ride.statement.provider') }}" class="waves-effect waves-light">
					<span class="s-icon"><i class="ti-book"></i></span>
					<span class="s-text">Driver Statement</span>
				</a>
			</li>
			<li>
				<a href="{{ route('account.ride.statement.today') }}" class="waves-effect waves-light">
					<span class="s-icon"><i class="ti-layers-alt"></i></span>
					<span class="s-text">Daily Statement</span>
				</a>
			</li>
			<li>
				<a href="{{ route('account.ride.statement.monthly') }}" class="waves-effect waves-light">
					<span class="s-icon"><i class="ti-pie-chart"></i></span>
					<span class="s-text">Monthly Statement</span>
				</a>
			</li>
			<li>
				<a href="{{ route('account.ride.statement.yearly') }}" class="waves-effect waves-light">
					<span class="s-icon"><i class="ti-package"></i></span>
					<span class="s-text">Yearly Statement</span>
				</a>
			</li>
			<li>
				<a href="{{ route('account.profile') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="ti-user"></i></span>
					<span class="s-text">Account Settings</span>
				</a>
			</li>
			<li>
				<a href="{{ route('account.openTicket','new') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="ti-id-badge"></i></span>
					<span class="s-text">New Ticket</span>
				</a>
			</li>
			<li>
				<a href="{{ route('account.openTicket','open') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="ti-receipt"></i></span>
					<span class="s-text">Open Ticket</span>
				</a>
			</li>
			<li>
				<a href="{{ route('account.closeTicket') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="ti-agenda"></i></span>
					<span class="s-text">Close Ticket</span>
				</a>
			</li>
			<li>
				<a href="{{ route('account.password') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="ti-exchange-vertical"></i></span>
					<span class="s-text">Change Password</span>
				</a>
			</li>
			<li class="compact-hide">
				<a href="{{ url('/account/logout') }}"
                            onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
					<span class="s-icon"><i class="ti-power-off"></i></span>
					<span class="s-text">Logout</span>
                </a>

                <form id="logout-form" action="{{ url('/account/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
			</li>
			
		</ul>
	</div>
</div>
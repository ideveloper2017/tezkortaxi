<div class="site-sidebar">
	<div class="custom-scroll custom-scroll-light">
		<ul class="sidebar-menu">
			<li class="active">
				<a href="{{ url('cms/dashboard/') }}" class="waves-effect waves-light">
					<span class="s-icon"><i class="ti-control-record"></i></span>
					<span class="s-text">Dashboard</span>
				</a>
			</li>

			<li class="with-sub">
				<a href="#" class="waves-effect  waves-light">
					<span class="s-caret"><i class="fa fa-angle-down"></i></span>
					<span class="s-icon"><i class="ti-layout"></i></span>
					<span class="s-text">Pages</span>
				</a>
				<ul>
					<li><a href="{{ url('cms/page/') }}">All Pages</a></li>
					<li><a href="{{ url('cms/page/create') }}">Add Pages</a></li>
                    <li><a href="{{ url('cms/how-it-work/') }}">All How it Work</a></li>
					<li><a href="{{ url('cms/how-it-work/create') }}">Add How it Work</a></li>
                    
				</ul>
			</li>
			
			<li class="with-sub">
				<a href="#" class="waves-effect  waves-light">
					<span class="s-caret"><i class="fa fa-angle-down"></i></span>
					<span class="s-icon"><i class="ti-thought"></i></span>
					<span class="s-text">Blogs</span>
				</a>
				<ul>
					<li><a href="{{ url('cms/blog/') }}">All Blogs</a></li>
					<li><a href="{{ url('cms/blog/create') }}">Add Blog</a></li>
                    
                    
				</ul>
			</li>
			
			<li class="with-sub">
				<a href="#" class="waves-effect  waves-light">
					<span class="s-caret"><i class="fa fa-angle-down"></i></span>
					<span class="s-icon"><i class="ti-email"></i></span>
					<span class="s-text">Email Template</span>
				</a>
				<ul>
					<li><a href="{{ url('cms/email/') }}">All Email Template</a></li>
					<li><a href="{{ url('cms/email/create') }}">Add Email Template</a></li>
                    
                    
				</ul>
			</li>

			<li class="with-sub">
				<a href="#" class="waves-effect  waves-light">
					<span class="s-caret"><i class="fa fa-angle-down"></i></span>
					<span class="s-icon"><i class="ti-themify-favicon"></i></span>
					<span class="s-text">Testimonial</span>
				</a>
				<ul>
					<li><a href="{{ url('cms/testimonial/') }}">All Testimonial</a></li>
					<li><a href="{{ url('cms/testimonial/create') }}">Add Testimonial</a></li>
                    
                    
				</ul>
			</li>
			

			<li class="with-sub">
				<a href="#" class="waves-effect  waves-light">
					<span class="s-caret"><i class="fa fa-angle-down"></i></span>
					<span class="s-icon"><i class="ti-layout"></i></span>
					<span class="s-text">FAQ</span>
				</a>
				<ul>
					<li><a href="{{ url('cms/faq/') }}">All FAQ</a></li>
					<li><a href="{{ url('cms/faq/create') }}">Add New</a></li>
                    
                    
				</ul>
			</li>
			
			<li class="with-sub">
				<a href="#" class="waves-effect  waves-light">
					<span class="s-caret"><i class="fa fa-angle-down"></i></span>
					<span class="s-icon"><i class="ti-agenda"></i></span>
					<span class="s-text">Profile</span>
				</a>
				<ul>
					<li><a href="{{ url('cms/profile/') }}">Account Settings</a></li>
					<li><a href="{{ url('cms/password/') }}">Change Password</a></li>
                    
                    
				</ul>
			</li>

			<li class="compact-hide">
				<a href="{{ url('/cms/logout') }}"
                            onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
					<span class="s-icon"><i class="ti-power-off"></i></span>
					<span class="s-text">Logout</span>
                </a>

                <form id="logout-form" action="{{ url('/cms/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
			</li>
			
		</ul>
	</div>
</div>
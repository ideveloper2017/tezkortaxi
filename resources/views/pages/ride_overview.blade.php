@extends('website.app')

@section('content')
<div class="signup" style="background-image: url(img/about-background.png);">
	<div class="signup_box">
		<h3 style="font-size: 32px;margin: 21px 0 0 21px;color:black;">{{ucwords($data->title)}}</h3>
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div>
						{{strip_tags($data->description)}}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
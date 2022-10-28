@extends('dynamicpage.app')

@section('meta')
<meta name="description" content="{{ $page->meta_description }}">
<meta name="keywords" content="{{ $page->meta_keys }}">
@endsection

@section('content')
<div class="page" style="background-image: url(img/about-background.png); font-family: Georgia, 'Times New Roman', Times, serif; margin-bottom: 30px;">
	<div class="page_box">
		<div class="container">
			<h3 style="font-size: 32px;margin: 21px 0 20px 0px;color:black;">{{ $page->title }}</h3>  	
			<div class="row">
				<div class="col-md-12">
					{!! $page->description !!}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
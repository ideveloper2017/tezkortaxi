@extends('website.app')

@section('content')
<?php
$locale = session()->get('locale');
        if($locale){

             $locale;

           }else{

             $locale = 'en';

        }
        $title = $locale.'_title';
        $des = $locale.'_description';
 ?>
<div class="blog" style="margin-top:20px">
	<div class="blog_box">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
                <h2 class="page-title">Blogs</h2>
            </div>
				@foreach($blogs as $blog)
					<div class="col-md-4" style="min-height: 420px;display: inline-block;"> 
						<a href="{{url('/blog/').'/'.$blog->id }}">
							<img src="{{ $blog->image }}" class="img-responsive" style="height: 275px !important;width: 340px !important"/>
						<h3>{{ $blog->$title }}</h3></a>
						<h6>{{ $blog->created_at }}</h6>
						{!! str_limit($blog->$des, 300) !!} 
					</div>
				@endforeach
			</div>
		</div>
	</div>
</div>
@endsection
@extends('crm.layout.base')

@section('title', 'Dashboard ')

@section('styles')
	<link rel="stylesheet" href="{{asset('main/vendor/jvectormap/jquery-jvectormap-2.0.3.css')}}">
@endsection

@section('content')

<div class="content-area py-1">
	<div class="container-fluid">
		<div class="row row-md">
			<div class="col-lg-4 col-md-6 col-xs-12">
				<div class="box box-block bg-success mb-2">
					<div class="t-content">
						<h4 class="text-uppercase mb-1">All Rides</h4>
						<h1 class="mb-1">{{ $totaltrips }}</h1>
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-6 col-xs-12">
				<div class="box box-block bg-primary mb-2">
					<div class="t-content">
						<h4 class="text-uppercase mb-1">All User</h4>
						<h1 class="mb-1">{{ $totaluser }}</h1>
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-6 col-xs-12">
				<div class="box box-block bg-info mb-2">
					<div class="t-content">
						<h4 class="text-uppercase mb-1">All Driver</h4>
						<h1 class="mb-1">{{ $totaldriver }}</h1>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
@endsection 
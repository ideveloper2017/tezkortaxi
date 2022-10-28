@extends('support.layout.base')

@section('title', 'Dashboard ')

@section('styles')
	<link rel="stylesheet" href="{{asset('main/vendor/jvectormap/jquery-jvectormap-2.0.3.css')}}">
@endsection

@section('content')

<div class="content-area py-1">
	<div class="container-fluid">
		<div class="row row-md">
			<div class="col-lg-3 col-md-6 col-xs-12">
				<div class="box box-block bg-warning mb-2">
					<div class="t-content">
						<h4 class="text-uppercase mb-1">All Tickets</h4>
						<h1 class="mb-1">{{ $complaint }}</h1>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-6 col-xs-12">
				<div class="box box-block bg-success mb-2">
					<div class="t-content">
						<h4 class="text-uppercase mb-1">New Tickets</h4>
						<h1 class="mb-1">{{ $newticket }}</h1>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-6 col-xs-12">
				<div class="box box-block bg-primary mb-2">
					<div class="t-content">
						<h4 class="text-uppercase mb-1">Open Tickets</h4>
						<h1 class="mb-1">{{ $open_ticket }}</h1>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-6 col-xs-12">
				<div class="box box-block bg-info mb-2">
					<div class="t-content">
						<h4 class="text-uppercase mb-1">Close Tickets</h4>
						<h1 class="mb-1">{{ $close_ticket }}</h1>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection 
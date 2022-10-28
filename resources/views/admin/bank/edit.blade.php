@extends('admin.layout.base')

@section('title', 'Update Service Type ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <a href="{{url('admin/approved_account')}}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> Back</a>

            <h5 style="margin-bottom: 2em;">Update User</h5>

            <form class="form-horizontal" action="{{route('admin.bank.update', $service->id )}}" method="POST" enctype="multipart/form-data" role="form">
                {{csrf_field()}}
				


                <input type="hidden" name="_method" value="PATCH">
                @if($service->type =='bank')
                <div class="form-group row">
                    <label for="name" class="col-xs-2 col-form-label">Account Name</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ $service->account_name }}" name="account_name" required id="account_name" placeholder="Account Name">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="provider_name" class="col-xs-2 col-form-label">Bank Name</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ $service->bank_name }}" name="bank_name" required id="bank_name" placeholder="Bank Name">
                    </div>
                </div>
                @endif
                @if($service->type =='paypal')
                <div class="form-group row">
                    <label for="name" class="col-xs-2 col-form-label">Paypal Id</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ $service->paypal_id }}" name="account_name" required id="paypal_id" placeholder="Paypal Id">
                    </div>
                </div>

                
                @endif
             
                
                <div class="form-group row">
                    <label for="capacity" class="col-xs-2 col-form-label">Account Number</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="number" value="{{ $service->account_number }}" name="account_number" required id="capacity" placeholder="Account Number">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="capacity" class="col-xs-2 col-form-label">Rounting Number</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ $service->routing_number }}" name="routing_number" required id="routing_number" placeholder="Rounting Number">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="capacity" class="col-xs-2 col-form-label">Country</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ $service->country }}" name="country" required id="country" placeholder="Country">
                    </div>
                </div>

              

                <div class="form-group row">
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <a href="{{url('admin/approved_account')}}" class="btn btn-danger btn-block">Cancel</a>
                    </div>
                    <div class="col-xs-12 col-sm-6 offset-md-6 col-md-3">
                        <button type="submit" class="btn btn-primary btn-block">Update Account</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
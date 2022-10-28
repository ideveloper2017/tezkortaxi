@extends('admin.layout.base')

@section('title', 'Add Service Type ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">

            <h5 style="margin-bottom: 2em;"><i class="ti-car"></i>&nbsp;Add Vehicle</h5><hr>

            <form class="form-horizontal" action="{{route('admin.service.store')}}" method="POST" enctype="multipart/form-data" role="form">
                {{ csrf_field() }}
                <div class="form-group row">
                    <label for="name" class="col-xs-12 col-form-label">Vehicle Name</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ old('name') }}" name="name" required id="name" placeholder="Cab Name">
                    </div>
                </div>

              

                <div class="form-group row">
                    <label for="picture" class="col-xs-12 col-form-label">Vehicle Icon</label>
                    <div class="col-xs-10">
                        <input type="file" accept="image/*" name="image" class="dropify form-control-file" id="picture" aria-describedby="fileHelp">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="picture" class="col-xs-12 col-form-label">Vehicle Image</label>
                    <div class="col-xs-10">
                        <input type="file" accept="image/*" name="vehicle_image" class="dropify form-control-file" id="picture" aria-describedby="fileHelp">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="fixed" class="col-xs-12 col-form-label">Base Price ({{ currency() }})</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ old('fixed') }}" name="fixed" required id="fixed" placeholder="Base Price">
                    </div>
                </div>
                <div class="form-group row" >
                    <label for="distance" class="col-xs-12 col-form-label">Base Distance (KM)</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="0" name="distance" required id="distance" placeholder="Base Distance">
                    </div>
                </div>
                <span style="display:none">
                <div class="form-group row">
                    <label for="minute" class="col-xs-12 col-form-label">Unit Time Pricing ({{ currency() }})</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="hidden" value="0" name="minute" required id="minute" placeholder="Unit Time Pricing">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="price" class="col-xs-12 col-form-label">Unit Distance Price (KM)</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="hidden" value="0" name="price" required id="price" placeholder="Unit Distance Price">
                    </div>
                </div>
                 </span>
                <div class="form-group row">
                    <label for="capacity" class="col-xs-12 col-form-label">Seat Capacity</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="number" value="{{ old('capacity') }}" name="capacity" required id="capacity" placeholder="Capacity">
                    </div>
                </div>


                <input class="form-control" type="hidden" value="MIN" name="calculator" >
                
              

                <div class="form-group row">
                    <label for="description" class="col-xs-12 col-form-label">Description</label>
                    <div class="col-xs-10">
                        <textarea class="form-control" type="text" value="{{ old('description') }}" name="description" required id="description" placeholder="Description" rows="4"></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-xs-10">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-3">
                                <button type="submit" class="btn btn-success shadow-box btn-block">Add Vehicle</button>
                            </div>
                            <div class="col-xs-12 col-sm-6 offset-md-6 col-md-3">
                                <a href="{{ route('admin.service.index') }}" class="btn btn-danger btn-block shadow-box">Cancel</a>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

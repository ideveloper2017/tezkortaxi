@extends('admin.layout.base')

@section('title', 'Add Document ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">

            <h5 style="margin-bottom: 2em;"><i class="ti-layout-tab"></i>&nbsp;Add Document</h5><hr>

            <form class="form-horizontal" action="{{route('admin.document.store')}}" method="POST" enctype="multipart/form-data" role="form">
                {{csrf_field()}}
                <div class="form-group row">
                    <label for="name" class="col-xs-12 col-form-label">Document Name</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ old('name') }}" name="name" required id="name" placeholder="Document Name">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="name" class="col-xs-12 col-form-label">Document Type</label>
                    <div class="col-xs-10">
                        <select name="type">
                            <option value="DRIVER">Driver</option>
                            <option value="VEHICLE">Vehicle</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="name" class="col-xs-12 col-form-label">Document Expire in Day(s)</label>
                    <div class="col-xs-10">
                        <select name="expire">
                            @for($i=1;$i<=30;$i++)
                            <option value="{{$i}}">{{$i}} </option>
                            @endfor
                        </select>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="zipcode" class="col-xs-12 col-form-label"></label>
                    <div class="col-xs-10">
                        <button type="submit" class="btn btn-success shadow-box">Add Document</button>
                        <a href="{{route('admin.document.index')}}" class="btn btn-default">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

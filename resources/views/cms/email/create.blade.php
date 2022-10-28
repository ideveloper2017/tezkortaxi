@extends('cms.layout.base')

@section('title', 'Add  New')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <a href="{{ route('cms.email.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i>Back</a>

            <h5 style="margin-bottom: 2em;"><span class="s-icon"><i class="ti-email"></i></span> &nbsp;Add Email Template</h5><hr>

            <form class="form-horizontal" action="{{route('cms.email.store')}}" method="POST" enctype="multipart/form-data" role="form">
                {{ csrf_field() }}
                   
                <div class="form-group row">
                    <label for="type" class="col-xs-12 col-form-label">Type</label>
                    <div class="col-xs-10">
                        <input type="text" name="type" class="form-control" id="type" value="{{ old('type') }}" placeholder="Type">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="template" class="col-xs-12 col-form-label">Template</label>
                    <div class="col-xs-10">
                        <textarea class="form-control" id="myeditor"  type="text"  name="template" required  placeholder="Description" rows="4">{{ old('template') }}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-xs-10">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-3">
                                <button type="submit" class="btn btn-success shadow-box btn-block">Save</button>
                            </div>
                            <div class="col-xs-12 col-sm-6 offset-md-6 col-md-3">
                                <a href="{{ route('cms.email.index') }}" class="btn btn-danger shadow-box btn-block">Cancel</a>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
<script type="text/javascript">
    CKEDITOR.replace('myeditor');
</script>
@endsection


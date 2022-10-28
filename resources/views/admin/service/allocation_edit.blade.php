<?php use App\Zones;
use App\ServiceType; ?>
@extends('admin.layout.base')

@section('title', 'Update Cab Allocation')

@section('content')
<?php @$zonename=Zones::select('*')->where('id' ,@$package->zone_id)->first();?>
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">

            <h5 style="margin-bottom: 2em;"><i class="ti-layout-media-overlay-alt-2"></i>&nbsp; Update Map Vehicle</h5><hr>
            <form class="form-horizontal" action="{{route('admin.cabAllocation_update')}}" method="GET" enctype="multipart/form-data" role="form">
                {{ csrf_field() }}                
                <input type="hidden" name="id" value="{{$package->id}}">
                <div class="form-group row hidable" style="display:none">
                    <label for="provider_name" class="col-xs-12 col-form-label">One Person Fare Percentage</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="number" value="{{ old('one_passanger_percent') }}" name="one_passanger_percent" id="one_passanger_percent" >
                    </div>
                </div>
                <div class="form-group row hidable" style="display:none">
                    <label for="provider_name" class="col-xs-12 col-form-label">Two Person Fare Percentage</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="number" value="{{ old('two_passanger_percent') }}" name="two_passanger_percent" id="two_passanger_percent" >
                    </div>
                </div>
                 <div class="form-group row">
                    <label for="provider_name" class="col-xs-12 col-form-label">Vehicle List</label>
                    <div class="col-xs-10">
                        <select class="form-control" type="text" name="cab_id" id="serviceType" >
                            <option value="select">------Select-------</option>
                            @foreach($services as $k=>$v)
                            <option value="{{$v->id}}" <?php if(@$package->cab_id==$v->id){echo "Selected";}?>>{{$v->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="provider_name" class="col-xs-12 col-form-label">Fare Plan</label>
                    <div class="col-xs-10">
                        <select class="form-control" type="text" name="fare_setting_id" id="serviceType" >
                            <option value="select">------Select-------</option>
                            @foreach($fare as $k=>$v)
                            <option value="{{$v->id}}" <?php if(@$package->fare_setting_id==$v->id){echo "Selected";}?>>{{$v->fare_plan_name}} : {{ $v->from_km }} KM To {{ $v->upto_km }} KM</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-xs-10">
                    <div class="form-group hide row" id="provider_list">
                    <label htmlfor="provider_id">Select Zone</label>
                    <input name="provider_id" type="hidden" value="{{$zonename['id']}}" />                    
                        <div class="provider_fl_wrapper">
                            <div class="form-control" id="provider_wrapper">
                                <div class="dr_search_txt">Select Zone</div>
                                <div class="dr_icon"><i class="fa fa-sort-down" aria-hidden="true"></i></div>
                            </div>
                            <div id="dr_list_wrapper">
                                <div id="input_wrapper">
                                    <input type="text" placeholder="Search..." value="{{$zonename['zone_name']}}" id="dr_seach_input" class="form-control" onkeyup="filterFunction()" />
                                </div>
                                <div id="dr_list">
                                    @foreach($zones as $k=>$v)
                                    <a class="dr_item" pr_id="{{ $v->id }}" <?php if(@$package->zone_id==$v->id){echo "Selected";}?>>{{ $v->zone_name }}</a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                 </div>

                <div class="form-group row">
                    <label for="description" class="col-xs-12 col-form-label">Description</label>
                    <div class="col-xs-10">
                        <textarea class="form-control" type="text"  name="description" required id="description" placeholder="Description" rows="4">{{@$package->description}}</textarea>
                    </div>
                </div>

                <div class="form-group row" id="s_button">
                    <div class="col-xs-10">
                        <div class="row">
                              <div class="col-xs-12 col-sm-6 col-md-3">
                                <button type="submit" class="btn btn-success shadow-box btn-block">Update</button>
                            </div>
                            <div class="col-xs-12 col-sm-6 offset-md-6 col-md-3">
                                <a href="{{ route('admin.allocation_list') }}" class="btn btn-danger shadow-box btn-block">Cancel</a>
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
<script type="text/javascript">
    function filterFunction() {
        var input, filter, ul, li, a, i;
        input = document.getElementById("dr_seach_input");
        filter = input.value.toUpperCase();
        div = document.getElementById("dr_list");
        a = div.getElementsByTagName("a");
          for (i = 0; i < a.length; i++) {
            if (a[i].innerHTML.toUpperCase().indexOf(filter) > -1) {
              a[i].style.display = "";
            } else {
              a[i].style.display = "none";
            }
          }
    }

    //Document ready function define here
    $(function () {     

        $('#provider_wrapper').on('click', function() {
            $('#dr_list_wrapper').slideToggle('slow');
        });
        
        $(document).on('click','#dr_list .dr_item',function() {
            $('#provider_list input[name=provider_id]').val($(this).attr('pr_id'));
            $('#provider_list .dr_search_txt').text($(this).text());
            $('#dr_list_wrapper').slideUp('slow');
        });
        
    });
    

</script>
@endsection
@section('styles')
<style type="text/css">

/* by sid */

.provider_fl_wrapper {
    position: relative;
}

div#provider_wrapper {
    position: relative;
    cursor: pointer;
}

.dr_icon {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
}

#dr_list_wrapper {
    display: none;
    position: absolute;
    left: 0;
    right: 0;
    top: 100%;
    width: 100%;
    z-index: 99;
    padding: 20px;
    border: 1px solid #c1c1c1;
    border-radius: 0 0 5px 5px;
    background-color: #f1f1f1;
}

#dr_list_wrapper input {
    border-radius: 6px;
}

#dr_list .dr_item {
    display: block;
    padding: 5px 0;
    cursor: pointer;
    font-weight: bold;
}

div#dr_list {
    height: 180px;
    margin-top: 20px;
    overflow-y: scroll;
}
</style>

<script>
    document.addEventListener("DOMContentLoaded", function(event) { 
        detectCategory()
    });
    function detectCategory(){
        let val = $("#serviceType").val();
        if(val == "Pool"){
            $('.hidable').show();
        }else{
            $('.hidable').hide();
            $('#one_passanger_percent, #two_passanger_percent').val(null);
        }
    }
</script>
@endsection

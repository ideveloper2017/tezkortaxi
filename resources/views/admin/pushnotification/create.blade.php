@extends('admin.layout.base')

@section('title', 'Send Notification')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">

			<h5 style="margin-bottom: 2em;"><i class="ti-comments"></i>&nbsp;Send Notification</h5><hr>

            <form class="form-horizontal" action="{{route('admin.pushnotification.store')}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
            	
            	<div class="form-group row">
					<label for="type" class="col-xs-12 col-form-label">Type</label>
					<div class="col-xs-10">
						<select name="zone" class="form-control" id="zone" required="">
						    <option value="0">Choose One</option>
							<option value="1">General</option>
							<option value="2">Location Based</option>
					    </select>
					</div>
				</div>
				
				
			
				
				<div class="form-group zones row" id="locationbasedzone"></div>
				
					<div class="form-group row">
					<label for="type" class="col-xs-12 col-form-label">User Type</label>
					<div class="col-xs-10">
						<select name="type" class="form-control" id="type" required="" onChange="getZonefromtype(this.value)">
						    <option value="0">Choose One</option>
							<option value="1">User</option>
							<option value="2">Driver</option>
					    </select>
					</div>
				</div>
			    
			    
				<div class="col-xs-10 col-xs-offset-2" id="users-and-providers"></div>


				<div class="form-group row">
					<label for="email" class="col-xs-12 col-form-label">Title</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" name="title" value="{{old('title')}}" id="title" placeholder="Title">
					</div>
				</div>

				<div class="form-group row">
					<label for="notification_text" class="col-xs-12 col-form-label">Notification Text</label>
					<div class="col-xs-10">
						<textarea class="form-control" rows="5" id="notification_text"  name="notification_text" placeholder="Notification Text"></textarea>
					</div>
				</div>

				<div class="form-group row">
					<label for="picture" class="col-xs-12 col-form-label">Image</label>
					<div class="col-xs-10">
						<input type="file" accept="application/pdf, image/*,video/mp4,video/x-m4v,video/*" name="image" class="dropify form-control-file" id="image" aria-describedby="fileHelp">
					</div>
				</div>
				
                <div class="form-group row">
					<label for="email" class="col-xs-12 col-form-label">Url</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" name="url" value="{{old('url')}}" id="url" placeholder="Url">
					</div>
				</div>
				
				<div class="form-group row">
					<label for="email" class="col-xs-12 col-form-label">Expiration Date</label>
					<div class="col-xs-10">
						<input class="form-control" type="date" name="expiration_date" value="{{old('expiration_date')}}" id="expiration_date" placeholder="Expiration Date">
					</div>
				</div>
				<div class="form-group row">
					<label for="email" class="col-xs-12 col-form-label"><div class="col-xs-10"><input type="checkbox" class="form-check-input" id="show_in_promotion" name="show_in_promotion"  value="1">Show in promotion</div></label>
					
				</div>
				<input class="form-control" type="hidden" name="zone_id" value="" id="something" placeholder="">
                
				<div class="form-group row">
					<label for="zipcode" class="col-xs-12 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-success shadow-box">Send Notification</button>
						<a href="{{route('admin.pushnotification.index')}}" class="btn btn-default">Cancel</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>
@endsection
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script>
    $(document).ready(function(){
        $('body').on('click','#all',function(){
                    if($(this).is(":checked")){
                        $(".zone_provider").hide();
                    }
                    else if($(this).is(":not(:checked)")){
                       $(".zone_provider").show();
                    }
                       
                });
        
        $('#zone').change(function(){
            
            $('#locationbasedzone').html('');
            if($('#zone').val()==1){
                if($("#type").val()>0){
                    var url = "{{url('admin/pushnotification/getProvidersAndUsers')}}";
                    var typeid = $("#type").val();
                    $.ajax({
                        type: 'get',
                        url: url+'/'+typeid,
                        dataType: 'html',      //return data will be json
                        success: function(html) {
                             $('#users-and-providers').html(html);
                        },
                        error:function(){
                         $('#users-and-providers').html('');
                        }
                    });
                }else{
                    $('#users-and-providers').html('');
                    alert("Please Select User Type.");
                }
            }
            if($('#zone').val()==2){
            //get zones 
                var url = "{{url('admin/pushnotification/getZones')}}";
                $.ajax({
                    type: 'get',
                    url: url,
                    dataType: 'html',      //return data will be json
                    beforeSend:function(){
                        $('#locationbasedzone').html('<label for="type" class="col-xs-12 col-form-label">Getting Locations...</label>');
                    },
                    success: function(html) {
                        $('#locationbasedzone').html(html);
                    },
                    error:function(){
                    }
                });
            }
        });
     
    });
    
     function getZone(id){
        $('#users-and-providers').html('');
        if($("#type").val()>0){
                var url = "{{url('admin/pushnotification/getZoneProviders')}}";
                var typeid = $("#type").val();
            if(id>0){
                $.ajax({
                    type: 'get',
                    url: url+'/'+id+'/'+typeid,
                    dataType: 'html',      //return data will be json
                    success: function(html) {
                         $('#users-and-providers').html(html);
                    },
                    error:function(){
                     // alert('ddd');
                    }
                });
            }
        }else{
            $('#users-and-providers').html('');
            alert("Please Select User Type.");
        }
    }
    
    
    function getZonefromtype(typeid){
        $('#users-and-providers').html('');
        if($('#zone').val()==2){
            if($("#zones").val()>0){
                    var url = "{{url('admin/pushnotification/getZoneProviders')}}";
                    var id = $("#zones").val();
                if(typeid>0){
                    $.ajax({
                        type: 'get',
                        url: url+'/'+id+'/'+typeid,
                        dataType: 'html',      //return data will be json
                        success: function(html) {
                             $('#users-and-providers').html(html);
                        },
                        error:function(){
                          $('#users-and-providers').html('');
                        }
                    });
                }
            }else{
                $('#users-and-providers').html('');
                alert("Please Select Location.");
            }
        }
        
        if($('#zone').val()==1){
                if(typeid>0){
                    var url = "{{url('admin/pushnotification/getProvidersAndUsers')}}";
                    var typeid = $("#type").val();
                    $.ajax({
                        type: 'get',
                        url: url+'/'+typeid,
                        dataType: 'html',      //return data will be json
                        success: function(html) {
                             $('#users-and-providers').html(html);
                        },
                        error:function(){
                         $('#users-and-providers').html('');
                        }
                    });
                }else{
                    $('#users-and-providers').html('');
                    alert("Please Select User Type.");
                } 
            }
        
    }
</script>
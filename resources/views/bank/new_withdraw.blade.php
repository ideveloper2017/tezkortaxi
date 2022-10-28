@extends('admin.layout.base')

@section('title', 'Withdraw')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <h5 class="mb-1"><span class="s-icon"><i class="ti-stats-up"></i></span>&nbsp;New Withdraw Request</h5>
            <hr/>
            <table class="table table-striped table-bordered dataTable" id="table-2" style="width:100%;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Request ID </th>
                        <th >Amount</th>
                        <th >Request_date</th>
                        <th >status</th>
                        <th >Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($bank as $index => $service)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>ReqXc_{{ $service->id }}</td>
                        <td>{{ $service->amount }}</td>
                        <td>{{ $service->created_at }}</td>
                        <td>{{ $service->status }}</td>
                        <td>
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button class="btn shadow-box btn-success btn-rounded w-min-sm m-b-0-25 waves-effect waves-light" data="{{ $service->id }}" id="approve_popup" 
                                    onclick="approve_popup({{$service->id}});">Details</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<input type="hidden" value="" id="hiidenid">
 <!-- Modal -->
<div class="modal fade" id="myModalpopup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <a type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></a>
        <h4 class="modal-title" >Requested for withdraw</h4>
      </div>
      <div id="msg" style="text-align:center;font-size:19px;color: #01eb01;"></div>
<div class="modal-body">
<div class="container">
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-3 toppad" style="width: 100%;left: 28px;">
<div class="panel panel-info">
<div class="panel-body">
<div class="row">
<div class=" col-md-12 col-lg-12 "> 
<table class="table table-user-information">
<tbody>
<tr>
<td>Account Name:</td>
<td id="account_name"></td>
</tr>
<tr>
<td>Bank Name:</td>
<td id="bank_name"></td>
</tr>
<tr>
<td>Account Number</td>
<td id="account_number"></td>
</tr>
<tr>
<tr>
<td>Routing Number</td>
<td id="routing_number"></td>
</tr>
<tr>
<td>Withdraw Amount</td>
<td id="amount"></td>
</tr>
<tr>
<td>Request Date</td>
<td id="date"></td>
</tr>
<td>Country</td>
<td id="country"></td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

</div>
      <div class="modal-footer">
        <a type="button" id="ApprovedId" class="btn btn-primary " onclick="ApprovedId();"><span aria-hidden="true">Approved</a>
      </div>
    </div>
  </div>
</div>
@endsection
    <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script>
    function approve_popup(id)
    {
        
        
        $('#myModalpopup').modal('show');

        $.ajax({
            url: '{{url("/admin/new_withdraw?id=")}}'+id,
            beforeSend: function(xhr){xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');},
            success: function(result){
                //alert(result[0].account_name);
            $("#account_name").text(result[0].account_name);
            $("#bank_name").text(result[0].bank_name);
            $("#account_number").text(result[0].account_number);
            $("#routing_number").text(result[0].routing_number);
            $("#amount").text(result[0].withdraw_request_amount);
            $("#request_date").text(result[0].request_date);
            $("#country").text(result[0].country);
            $("#withdrawId").text(result[0].withdrawId);
             wId = result[0].withdrawId;
             
            $("#hiidenid").val(wId);
            console.log(result);
        }});

    }
       /* $('#approve_popup').click(function(){
        
            var id = $(this).attr('data');
            //alert(id);
            $('#myModalpopup').modal('show');

            $.ajax({
                url: "/admin/new_withdraw?id="+id,
                beforeSend: function(xhr){xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');},
                 success: function(result){
                $("#account_name").text(result[0].account_name);
                $("#bank_name").text(result[0].bank_name);
                $("#account_number").text(result[0].account_number);
                $("#routing_number").text(result[0].routing_number);
                $("#amount").text(result[0].withdraw_request_amount);
                $("#request_date").text(result[0].request_date);
                $("#country").text(result[0].country);
                $("#withdrawId").text(result[0].withdrawId);
                 wId = result[0].withdrawId;
                console.log(result);
            }});

        });*/
        
        
        function ApprovedId()
        {
            //var wId = $("#hiidenid").val();
            //alert(wId);
            $.ajax({
                    url: '{{url("/admin/approved_withdraw?id=")}}'+wId,
                    beforeSend: function(xhr){xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');},
                     success: function(result){
        
                    $("#msg").html('you have successfully approved');
        
                    setTimeout(function(){
               window.location.reload();
            }, 2000);
       
            console.log(result);

            }});

        }
        /*$('#ApprovedId').click(function(){

            $.ajax({
                        url: "/admin/approved_withdraw?id="+wId,
                        beforeSend: function(xhr){xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');},
                         success: function(result){
            
                        $("#msg").html('you have successfully approved');
            
                        setTimeout(function(){
               window.location.reload();
            }, 2000);
       
            console.log(result);

            }});

        });*/
    </script>
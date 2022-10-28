@extends('crm.layout.base')
@section('title', 'Lost Management')
@section('content')
<div class="content-area py-1">
   <div class="container-fluid">
      <div class="box box-block bg-white">
      	<h5 class="mb-1">
            <i class="ti-receipt"></i> &nbsp;Lost Management
         </h5><hr>
         <table class="table table-striped table-bordered dataTable" id="table-2"style="width:100%;">
            <thead>
               <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Lost Item</th>
                  <th>Action</th>
               </tr>
            </thead>
            <tbody>
               @foreach($data as $index => $user)
               <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $user->name }}</td>
                  <td>{{ $user->email }}</td>
                  <td>{{ $user->phone }}</td>
                  <td>{{ $user->lost_item }}</td>
                  <td>
                    <form action="{{ route('crm.lost-destroy', $user->id) }}" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="DELETE"/>
                        <button class="btn btn-danger shadow-box" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i></button>
                    </form>
                  </td>
               </tr>
               @endforeach
            </tbody>
         </table>
      </div>
   </div>
</div>
@endsection
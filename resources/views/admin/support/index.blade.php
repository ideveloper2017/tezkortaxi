@extends('admin.layout.base')
@section('title', 'Support ')
@section('content')
<div class="content-area py-1">
   <div class="container-fluid">
      <div class="box box-block bg-white">
         <h5 class="mb-1">
            <i class="ti-exchange-vertical"></i>&nbsp;Support User
            @if(Setting::get('demo_mode', 0) == 1)
            <span class="pull-right">(*personal information hidden in demo)</span>
            @endif
         </h5>
         <hr>
         <a href="{{ route('admin.support-manager.create') }}" style="margin-left: 1em;" class="btn shadow-box btn-success btn-rounded pull-right"><i class="fa fa-plus"></i> Add New</a>
         <table class="table table-striped table-bordered dataTable" id="table-2" style="width:100%;">
            <thead>
               <tr>
                  <th>ID</th>
                  <th>Full Name</th>
                  <th>Email</th>
                  <th>Mobile</th>
                  <th>Action</th>
               </tr>
            </thead>
            <tbody>
               @foreach($supports as $index => $support)
               <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $support->name }}</td>
                  @if(Setting::get('demo_mode', 0) == 1)
                  <td>{{ substr($support->email, 0, 3).'****'.substr($support->email, strpos($support->email, "@")) }}</td>
                  @else
                  <td>{{ $support->email }}</td>
                  @endif
                  @if(Setting::get('demo_mode', 0) == 1)
                  <td>+919876543210</td>
                  @else
                  <td>{{ $support->mobile }}</td>
                  @endif
                  <td>
                     <form action="{{ route('admin.support-manager.destroy', $support->id) }}" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="DELETE">
                        <a href="{{ route('admin.support-manager.edit', $support->id) }}" class="btn shadow-box btn-success"><i class="fa fa-pencil"></i></a>
                        <button class="btn btn-danger shadow-box" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i></button>
                     </form>
                  </td>
               </tr>
               @endforeach
            </tbody>
            <tfoot>
               <tr>
                  <th>ID</th>
                  <th>Full Name</th>
                  <th>Email</th>
                  <th>Mobile</th>
                  <th>Action</th>
               </tr>
            </tfoot>
         </table>
      </div>
   </div>
</div>
@endsection
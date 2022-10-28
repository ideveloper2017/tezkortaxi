@extends('admin.layout.base')
@section('title', 'Cms ')
@section('content')
<div class="content-area py-1">
   <div class="container-fluid">
      <div class="box box-block bg-white">
         <h5 class="mb-1">
            <i class="ti-layout-media-left-alt"></i>&nbsp;CMS User
            @if(Setting::get('demo_mode', 0) == 1)
            <span class="pull-right">(*personal information hidden in demo)</span>
            @endif
         </h5>
         <hr>
         <a href="{{ route('admin.cms-manager.create') }}" style="margin-left: 1em;" class="btn shadow-box btn-rounded btn-success pull-right"><i class="fa fa-plus"></i> Add New</a>
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
               @foreach($cmss as $index => $cms)
               <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $cms->name }}</td>
                  @if(Setting::get('demo_mode', 0) == 1)
                  <td>{{ substr($cms->email, 0, 3).'****'.substr($cms->email, strpos($cms->email, "@")) }}</td>
                  @else
                  <td>{{ $cms->email }}</td>
                  @endif
                  @if(Setting::get('demo_mode', 0) == 1)
                  <td>+919876543210</td>
                  @else
                  <td>{{ $cms->mobile }}</td>
                  @endif
                  <td>
                     <form action="{{ route('admin.cms-manager.destroy', $cms->id) }}" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="DELETE">
                        <a href="{{ route('admin.cms-manager.edit', $cms->id) }}" class="btn shadow-box btn-success"><i class="fa fa-pencil"></i></a>
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
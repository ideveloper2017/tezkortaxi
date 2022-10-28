@extends('cms.layout.base')

@section('title', 'Blog')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <h5 class="mb-1"><span class="s-icon"><i class="ti-thought"></i></span> &nbsp;Blog Post</h5><hr>
            <a href="{{ route('cms.blog.create') }}" style="margin-left: 1em;" class="btn btn-success shadow-box btn-rounded pull-right"><i class="fa fa-plus"></i>Add New</a>
            <a href="{{ url('/blogs') }}" style="margin-left: 1em;" class="btn btn-success shadow-box btn-rounded pull-right" target="_blank"><i class="fa fa-plus"></i>View Blog</a>
            <table class="table table-striped table-bordered dataTable" id="table-2" style="width:100%;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th style="width: 300px;">En Title</th>
                        <th style="width: 800px;">En Description</th>

                        <th style="width: 300px;">Ar Title</th>
                        <th style="width: 800px;">Ar Description</th>

                        <th style="width: 300px;">Fr Title</th>
                        <th style="width: 800px;">Fr Description</th>

                        <th style="width: 300px;">Ru Title</th>
                        <th style="width: 800px;">Ru Description</th>

                        <th style="width: 300px;">Sp Title</th>  
                        <th style="width: 800px;">Sp Description</th>
                       
                        <th> Post Image</th>
                        <th style="width: 80px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($blog as $index => $service)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $service->en_title }}</td>
                        <td>{!! str_limit($service->en_description, 80) !!}</td>

                        <td>{{ $service->ar_title }}</td>
                        <td>{!! str_limit($service->ar_description, 80) !!}</td>

                        <td>{{ $service->fr_title }}</td>
                        <td>{!! str_limit($service->fr_description, 80) !!}</td>

                        <td>{{ $service->ru_title }}</td>
                        <td>{!! str_limit($service->ru_description, 80) !!}</td>

                        <td>{{ $service->sp_title }}</td>
                        <td>{!! str_limit($service->sp_description, 80) !!}</td>
                       
                        <td>
                            @if($service->image) 
                                <img src="{{$service->image}}" style="height: 50px" >
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('cms.blog.destroy', $service->id) }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <a href="{{ route('cms.blog.edit', $service->id) }}" class="btn btn-success shadow-box">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <button class="btn btn-danger shadow-box" onclick="return confirm('Are you sure?')">
                                    <i class="fa fa-trash"></i>
                                </button>
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
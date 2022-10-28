@extends('cms.layout.base')

@section('title', 'FAQ List')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <h5 class="mb-1"><span class="s-icon"><i class="ti-layout"></i></span>&nbsp;FAQ List</h5><hr>
            <a href="{{ route('cms.how-it-work.create') }}" style="margin-left: 1em;" class="btn btn-success shadow-box btn-rounded pull-right"><i class="fa fa-plus"></i>Add New</a>
            
            <table class="table table-striped table-bordered dataTable" id="table-2" style="width:100%;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <!-- <th style="width: 300px;">En Title</th> -->               
                        <th style="width: 200px;">En Question</th> 
                        <th style="width: 800px;">En Description</th>

                        <!-- <th style="width: 300px;">Ar Title</th> -->                       
                        <th style="width: 200px;">Ar Question</th>
                        <th style="width: 800px;">Ar Description</th>

                        <!-- <th style="width: 300px;">Fr Title</th>  -->                      
                        <th style="width: 200px;">Fr Question</th>
                        <th style="width: 800px;">Fr Description</th>

                        <!-- <th style="width: 300px;">Ru Title</th> -->                       
                        <th style="width: 200px;">Ru Question</th>
                        <th style="width: 800px;">Ru Description</th>

                        <!-- <th style="width: 300px;">Sp Title</th> -->                       
                        <th style="width: 200px;">Sp Question</th> 
                        <th style="width: 800px;">Sp Description</th>
                       
                        <!-- <th> Page Image</th>-->
                        <th style="width: 80px !important;">Action</th> 
                    </tr>
                </thead>
                <tbody>
                @foreach($data as $index => $service)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $service->en_question }}</td>
                        <td>{!! str_limit($service->en_description, 80) !!}</td>

                        <td>{{ $service->ar_question }}</td>
                        <td>{!! str_limit($service->ar_description, 80) !!}</td>

                        <td>{{ $service->fr_question }}</td>
                        <td>{!! str_limit($service->fr_description, 80) !!}</td>

                        <td>{{ $service->ru_question }}</td>
                        <td>{!! str_limit($service->ru_description, 80) !!}</td>

                        <td>{{ $service->sp_question }}</td>
                        <td>{!! str_limit($service->sp_description, 80) !!}</td>
                       
           
                        <td>
                            <form action="{{ route('cms.how-it-work.destroy', $service->id) }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <a href="{{ route('cms.how-it-work.edit', $service->id) }}" class="btn btn-success shadow-box">
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
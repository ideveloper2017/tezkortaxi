@extends('crm.layout.base')

@section('title', 'Provider Documents ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        
        <div class="box box-block bg-white">
            <h5 class="mb-1">Driver Name: {{ $Document['provider']['first_name'] }} {{ $Document['provider']['last_name'] }}</h5>
            <h5 class="mb-1">Document: {{ $Document['document']['name'] }}</h5>
            <embed src="{{ asset('storage/app/public/'.$Document['url']) }}" width="300px" height="300px" />

            <div class="row">
                <div class="col-xs-6">
                    <form action="{{ route('crm.provider.document.update', [$Document['provider_id'], $Document['document_id'] ]) }}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('PATCH') }}
                        <button class="btn btn-block btn-success shadow-box btn-rounded" type="submit">Approve</button>
                    </form>
                </div>
                <div class="col-xs-6">
                    <form action="{{ route('crm.provider.document.destroy', [$Document->provider_id, $Document['document_id']]) }}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button class="btn btn-block btn-danger shadow-box btn-rounded" type="submit">Delete</button>
                    </form>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection
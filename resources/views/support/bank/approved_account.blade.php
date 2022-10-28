@extends('support.layout.base')

@section('title', 'Bank')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <h5 class="mb-1"><span class="s-icon"><i class="ti-stats-up"></i></span>&nbsp; Approved Account</h5>
            <hr/>
            <table class="table table-striped table-bordered dataTable" id="table-2" style="width:100%;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th ">Account Name </th>
                        <th >Paypal Id</th>
                        <th >Bank Name</th>
                        <th >Account Number</th>
                        <th >Routing Number</th>
                        <th >Type</th>
                        <th >status</th>
                        <th >Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($bank as $index => $service)
					
				
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $service->account_name }}</td>
                        <td>{{ $service->paypal_id }}</td>
                        <td>{{ $service->bank_name }}</td>
                        <td>{{ $service->account_number }}</td>
                        <td>{{ $service->routing_number }}</td>
                        <td>{{ $service->type }}</td>
                        <td>{{ $service->status }}</td>
                       
                    
                        <td>
                           <form action="{{ route('support.bank.destroy', $service->id) }}" method="POST">

                             <form action="#" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                             <a href="{{ route('support.bank.edit', $service->id) }}" class="btn btn-success shadow-box">
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
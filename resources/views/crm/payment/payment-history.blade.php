@extends('crm.layout.base')

@section('title', 'Payment History ')

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <h5 class="mb-1"><i class="ti-files"></i>&nbsp;Payment History<hr></h5>
                <table class="table table-striped table-bordered dataTable" id="table-2" style="width:100%">
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Transaction ID</th>
                            <th>User</th>
                            <th>Driver</th>
                            <th>Total Amount</th>
                            <th>Payment Mode</th>
                            <th>Payment Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($payments as $index => $payment)
                        <tr>
                            <td>{{$payment->booking_id}}</td>
                            @if($payment->payment->payment_id)
                            <td>{{$payment->payment->payment_id}}</td>
                            @else
                            <td>--</td>
                            @endif
                            <td>{{$payment->user->first_name}}</td>
                            <td>{{$payment->provider->first_name}}</td>
                            <td>{{currency($payment->payment->total)}}</td>
                            <td>{{$payment->payment_mode}}</td>
                            <td>
                                @if($payment->paid)
                                    Paid
                                @else
                                    Not Paid
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Request ID</th>
                            <th>Transaction ID</th>
                            <th>User</th>
                            <th>Driver</th>
                            <th>Total Amount</th>
                            <th>Payment Mode</th>
                            <th>Payment Status</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
        </div>
    </div>
@endsection
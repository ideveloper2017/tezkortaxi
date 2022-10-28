@extends('user.layout.base')

@section('title', 'Upcoming Trips ')

@section('content')

<div class="col-md-12" style="margin-bottom: 20px;">
    <div class="dash-content">
        <div class="row no-margin">
            <div class="col-md-12">
                <h4 class="page-title">@lang('user.upcoming_rides')</h4>
            </div>
        </div>

        <div class="row no-margin ride-detail">
            <div class="col-md-12">
            @if($trips->count() > 0)

                <table class="table table-condensed" style="border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th>@lang('user.booking_id')</th>
                            <th>@lang('user.schedule_date')</th>
                            <th>@lang('user.type')</th>
                            <th>@lang('user.payment')</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                    @foreach($trips as $trip)

                        <tr data-toggle="collapse" data-target="#trip_{{$trip->id}}" class="accordion-toggle collapsed">
                            <td>{{$trip->booking_id}}</td>
                            <td>{{date('d-m-Y H:i:s',strtotime($trip->schedule_at))}}</td>
                            @if($trip->service_type)
                                 <td>{{$trip->service_type->name}}</td>
                            @else
                                <td>-</td>
                            @endif
                            <td>@lang('user.paid_via') {{$trip->payment_mode}}</td>
                            <td>
                                <form action ="{{url('/upcoming/trips/detail')}}">
                                    <input type="hidden" value="{{$trip->id}}" name="request_id">
                                    <button type="submit" style="margin-top: 0px;" class="full-primary-btn fare-btn">Detail</button>
                                </form>
                            </td>
                        </tr>

                        @endforeach


                    </tbody>
                </table>
                @else
                    <hr>
                    <p style="text-align: center;">@lang('user.no_ride_available')</p>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
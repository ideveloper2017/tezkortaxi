@extends('admin.layout.base')

@section('title', 'User Reviews ')

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">
            
            <div class="box box-block bg-white">
                <h5 class="mb-1"><i class="ti-star"></i>&nbsp;User Reviews<hr></h5>
                <table class="table table-striped table-bordered dataTable" id="table-2" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Request ID</th>
                            <th>User Name</th>
                            <th>Provider Name</th>
                            <th>Rating</th>
                            <th>Date & Time</th>
                            <th>Comments</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($Reviews as $index => $review)
                        <tr>
                            <td>{{$index + 1}}</td>
                            <td>{{$review->request_id}}</td>
                            <td>{{$review->user['first_name']}}</td>
                            <td>{{$review->provider['first_name']}}</td>
                            <td>
                                <div className="rating-outer">
                                    <input type="hidden" value="{{$review->user_rating}}" name="rating" class="rating"/>
                                </div>
                            </td>
                            <td>{{$review->created_at}}</td>
                            <td>{{$review->user_comment}}</td>
                            
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Request ID</th>
                            <th>User Name</th>
                            <th>Provider Name</th>
                            <th>Rating</th>
                            <th>Date & Time</th>
                            <th>Comments</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
        </div>
    </div>
@endsection
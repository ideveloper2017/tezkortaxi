@extends('user.layout.base')

@section('title', 'Terms and Condition')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <div class="row no-margin ride-detail">
                <div class="col-md-12"> 
                @if($terms->count() > 0)

                    <table class="table table-condensed" style="border-collapse:collapse;">

                        <thead>
                            <tr>
                                <th colspan="2"><h5>Terms and Conditions</h5></th>
                            </tr>
                        </thead>

                        <tbody>
                     
                        @foreach($terms as $term)

                            <tr data-toggle="collapse" data-target="#term_{{$term->terms_id}}" class="accordion-toggle collapsed">
                                <td class="col-md-11">{{ strip_tags($term->description) }}</td>
            
                            </tr>

                        @endforeach

                        </tbody>
                    </table>
                    @else
                        <hr>
                        <p style="text-align: center;">No terms Available</p>
                    @endif
                </div>
            </div>
        </div>    

    </div>
</div>
@endsection

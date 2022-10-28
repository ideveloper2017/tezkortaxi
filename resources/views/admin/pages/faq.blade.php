@extends('admin.layout.base')

@section('title', 'FAQ ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <h5 style="margin-bottom: 2em;">FAQ</h5>

            <form class="form-horizontal" action="{{ url('/admin/faq') }}" method="POST" enctype="multipart/form-data" role="form">
                {{csrf_field()}}
                <div class="form-group row">
                    <label for="question" class="col-xs-12 col-form-label">Question</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" name="question" value="{{ old('question') }}" required id="question" placeholder="Question">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="answer" class="col-xs-12 col-form-label">Answer</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" name="answer" value="{{ old('answer') }}" required id="answer" placeholder="Answer">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-xs-12 col-form-label"></label>
                    <div class="col-xs-10">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>    

        </div>

        <div class="box box-block bg-white">
            <div class="row no-margin ride-detail">
            <div class="col-md-12">
            @if($faqs->count() > 0)

                <table class="table table-condensed" style="border-collapse:collapse;">

                    <thead>
                        <tr>
                            <th colspan="2"><h5>Recent FAQ</h5></th>
                        </tr>
                    </thead>

                    <tbody>
                    @foreach($faqs as $faq)

                        <tr data-toggle="collapse" data-target="#faq_{{$faq->faq_id}}" class="accordion-toggle collapsed">
                            <td class="col-md-1"><span class="arrow-icon fa fa-chevron-right"></span></td>
                            <th class="col-md-11">{{ $faq->question }}</th>
        
                        </tr>

                        <tr class="hiddenRow">
                            <td class="col-md-1"></td>
                            <td class="col-md-11">
                                <div class="accordian-body collapse row" id="faq_{{$faq->faq_id}}">
                                    <div class="col-md-12">
                                            {{ $faq->answer }}
                                    </div>
                                </div>            
                            </td>
                        </tr>
                        @endforeach


                    </tbody>
                </table>
                @else
                    <hr>
                    <p style="text-align: center;">No faqs Available</p>
                @endif
            </div>
            </div>
        </div>    

    </div>
</div>
@endsection
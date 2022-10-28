@extends('admin.layout.base')



@section('title', 'Testimonials')



@section('content')



    <div class="content-area py-1">

        <div class="container-fluid">

            

            <div class="box box-block bg-white">

                <h5 class="mb-1"><i class="ti-themify-favicon"></i>&nbsp;Testimonials</h5><hr>

                <a href="{{ route('admin.testimonial.create') }}" style="margin-left: 1em;" class="btn shadow-box btn-success btn-rounded pull-right"><i class="fa fa-plus"></i> Add New</a>

                

                <table class="table table-striped table-bordered dataTable no-footer dtr-inline collapsed table-responsive" id="table-2" style="width:100%">

                    <thead>

                        <tr>

                            <th>Image</th>

                            <th>En Name</th>
                            <th>En Type</th>
                            <th>En Description</th>

                            <th>Ar Name</th>
                            <th>Ar Type</th>
                            <th>Ar Description</th>

                            <th>Fr Name</th>
                            <th>Fr Type</th>
                            <th>Fr Description</th>

                            <th>Ru Name</th>
                            <th>Ru Type</th>
                            <th>Ru Description</th>

                            <th>Sp Name</th>
                            <th>Sp Type</th>
                            <th>Sp Description</th>

                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody>

                        @if( count( $testimonials ) > 0 )

                            @foreach( $testimonials as $testimonial )

                                <tr>

                                    <td><img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{ asset('storage/app/public/'.$testimonial->image ) }}"></td>

                                    <td>{{ $testimonial->en_name }}</td>
                                    <td>{{ $testimonial->en_type }}</td>
                                    <td>{{ $testimonial->en_description }}</td>

                                    <td>{{ $testimonial->ar_name }}</td>
                                    <td>{{ $testimonial->ar_type }}</td>
                                    <td>{{ $testimonial->ar_description }}</td>

                                    <td>{{ $testimonial->fr_name }}</td>
                                    <td>{{ $testimonial->fr_type }}</td>
                                    <td>{{ $testimonial->fr_description }}</td>

                                    <td>{{ $testimonial->ru_name }}</td>
                                    <td>{{ $testimonial->ru_type }}</td>
                                    <td>{{ $testimonial->ru_description }}</td>

                                    <td>{{ $testimonial->sp_name }}</td>
                                    <td>{{ $testimonial->sp_type }}</td>
                                    <td>{{ $testimonial->sp_description }}</td>

                                    <td style="width: 100px">

                                        <form action="{{ route('admin.testimonial.destroy', $testimonial->id) }}" method="POST">

                                            {{ csrf_field() }}

                                            <input type="hidden" name="_method" value="DELETE">

                                            <a href="{{ route('admin.testimonial.edit', $testimonial->id) }}" class="btn shadow-box btn-success"><i class="fa fa-pencil"></i></a>

                                            <button class="btn btn-danger shadow-box" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i></button>

                                        </form>

                                    </td>

                                </tr>

                            @endforeach

                        @else

                            <tr>

                                <td colspan="6">No Testimonial found</td>

                            </tr>

                        @endif

                    </tbody>

                    <tfoot>

                        <tr>

                            <td>Image</td>

                            <th>En Name</th>
                            <th>En Type</th>
                            <th>En Description</th>

                            <th>Ar Name</th>
                            <th>Ar Type</th>
                            <th>Ar Description</th>

                            <th>Fr Name</th>
                            <th>Fr Type</th>
                            <th>Fr Description</th>

                            <th>Ru Name</th>
                            <th>Ru Type</th>
                            <th>Ru Description</th>

                            <th>Sp Name</th>
                            <th>Sp Type</th>
                            <th>Sp Description</th> 

                            <td>Action</td>

                        </tr>

                    </tfoot>

                </table>

            </div>

            

        </div>

    </div>

@endsection
@extends('provider.layout.app')

@section('content')


    <div class="col-md-12" style="margin-bottom: 20px">
    <div class="dash-content">
        <div class="row no-margin">
            <div class="col-md-12">
                <h4 class="page-title">@lang('provider.update_profile')</h4>
            </div>
        </div>
            <!-- Pro-dashboard-content -->
            <div class="pro-dashboard-content">
                <div class="profile">
                    <!-- Profile head -->
                    
                    <div class="container-fluid">
                        <div class="profile-head white-bg row no-margin">
                            <div class="prof-head-left col-lg-2 col-md-12 col-sm-3 col-xs-12" style="margin-top: -20px;">
                                <div class="profile-img-blk">
                                    <div class="img_outer">
                                        <img class="profile_preview" id="profile_image_preview" src="{{ Auth::guard('provider')->user()->avatar ? asset('storage/app/public/'.Auth::guard('provider')->user()->avatar) : asset('asset/front/img/provider.jpg') }}" alt="your image"/>
                                    </div>
                                </div>
                            </div> 
                            <div class="col-md-12">
                                <h3 style="font-weight: bold;">{{ @Auth::guard('provider')->user()->first_name }}</h3>
                                <p style="margin-left: 27px;font-weight: bold;"> @if(@Auth::guard('provider')->user()->status == 'approved') @lang('provider.approved') @else @lang('provider.not_approved') @endif</p>
                            </div>
                        </div>
                    </div>

                      <!-- Profile-content -->
                    <div class="profile-content">
                        <div class="container">
                            <div class="row no-margin">
                                <div class="col-lg-7 col-md-7 col-sm-8 col-xs-12 no-padding">
                                    <form class="profile-form" action="{{route('provider.profile.update')}}" method="POST" enctype="multipart/form-data" role="form">
                                        {{csrf_field()}}
                                        <!-- Prof-form-sub-sec -->
                                        <div class="prof-form-sub-sec">
                                            <div class="row no-margin">
                                                <div class="prof-sub-col col-xs-12 no-left-padding">
                                                    <div class="form-group">
                                                        <label>@lang('provider.name')</label>
                                                        <input type="text" class="form-control" style="width: 663px;" placeholder="@lang('provider.name')" name="first_name" value="{{ Auth::guard('provider')->user()->first_name }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="prof-sub-col prof-1 col-xs-12">
                                                    <div class="form-group">
                                                        <label>@lang('provider.picture')</label>
                                                        <input type="file" class="form-control" name="avatar">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row no-margin">
                                                <div class="prof-sub-col col-sm-6 col-xs-12 no-left-padding">
                                                    <div class="form-group">
                                                        <label>@lang('provider.phone')</label>
                                                        <input type="text" class="form-control" required placeholder="@lang('provider.phone')" name="mobile" value="{{ Auth::guard('provider')->user()->mobile }}">
                                                    </div>
                                                </div>
                                                <div class="prof-sub-col col-sm-6 col-xs-12 no-right-padding">
                                                    <div class="form-group no-margin">
                                                        <label for="exampleSelect1">@lang('provider.language')</label>
                                                        <select class="form-control" name="language" style="height: 44px;">
                                                            <option value="English">English</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End of prof-sub-sec -->

                                        <!-- Prof-form-sub-sec -->
                                        <div class="prof-form-sub-sec border-top">
                                            <div class="form-group">
                                                <label>@lang('provider.address')</label>
                                                <input type="text" class="form-control" placeholder="@lang('provider.address')" name="address" value="{{ Auth::guard('provider')->user()->profile ? Auth::guard('provider')->user()->profile->address : "" }}">
                                                <input type="text" class="form-control" placeholder="@lang('provider.address1')" style="border-top: none;" name="address_secondary" value="{{ Auth::guard('provider')->user()->profile ? Auth::guard('provider')->user()->profile->address_secondary : "" }}">
                                            </div>

                                            <div class="form-group">
                                                <label>@lang('provider.description')</label>
                                                <textarea class="form-control"  name="description"  placeholder="@lang('provider.enter_story')" style="width:100%; height:100px;">{{ @Auth::guard('provider')->user()->profile ? @trim(Auth::guard('provider')->user()->profile->description) : "" }}</textarea>
                                            </div>

                                            <div class="row no-margin">
                                                <div class="prof-sub-col col-sm-6 col-xs-12 no-left-padding">
                                                    <div class="form-group no-margin">
                                                        <label>@lang('provider.city')</label>
                                                        <input type="text" class="form-control" placeholder="@lang('provider.city')" name="city" value="{{ Auth::guard('provider')->user()->profile ? Auth::guard('provider')->user()->profile->city : "" }}">
                                                    </div>
                                                </div>
                                                <div class="prof-sub-col col-sm-6 col-xs-12 no-right-padding">
                                                    <div class="form-group">
                                                        <label>@lang('provider.country')</label>
                                                        <select class="form-control" name="country" style="height: 44px;>
                                                            <option value="US">United States</option>
                                                            <option value="IN">India</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End of prof-sub-sec -->

                                        <div class="row no-margin">
                                            <div class="prof-sub-col col-sm-6 col-xs-12 no-left-padding">
                                                <div class="form-group no-margin">
                                                    <label>@lang('provider.postal_code')</label>
                                                    <input type="text" class="form-control" placeholder="@lang('provider.postal_code')" name="postal_code" value="{{ Auth::guard('provider')->user()->profile ? Auth::guard('provider')->user()->profile->postal_code : "" }}">
                                                </div>
                                            </div>
                                            <div class="prof-sub-col col-sm-6 col-xs-12 no-right-padding">
                                                <div class="form-group">
                                                    <label>@lang('provider.vehicle')</label>
                                                    <select class="form-control" name="service_type" style="height: 44px;>
                                                        <option value="">Select Service</option>
                                                        @foreach(get_all_service_types() as $type)
                                                            <option @if(@Auth::guard('provider')->user()->service->service_type->id == $type->id) selected="selected" @endif value="{{$type->id}}">{{$type->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row no-margin">
                                            <div class="prof-sub-col col-sm-6 col-xs-12 no-left-padding">
                                                <div class="form-group no-margin">
                                                    <label>@lang('provider.plate_no')</label>
                                                    <input type="text" class="form-control" placeholder="@lang('provider.driving_license_no')" name="service_number" value="{{ @Auth::guard('provider')->user()->service->service_number ? @Auth::guard('provider')->user()->service->service_number : "" }}">
                                                </div>
                                            </div>
                                            <div class="prof-sub-col col-sm-6 col-xs-12 no-right-padding">
                                                <div class="form-group">
                                                    <label>@lang('provider.vehicle_make')</label>
                                                    <input type="text"  placeholder="@lang('provider.vehicle_make')" class="form-control" name="service_make" value="{{ @Auth::guard('provider')->user()->service->service_make ? @Auth::guard('provider')->user()->service->service_make : "" }}">
                                                </div>
                                            </div>
                                            <div class="prof-sub-col col-sm-6 col-xs-12 no-left-padding">
                                                <div class="form-group">
                                                    <label>@lang('provider.vehicle_model')</label>
                                                    <input type="text"  placeholder="@lang('provider.vehicle_model')" class="form-control" name="service_model" value="{{ @Auth::guard('provider')->user()->service->service_model ? @Auth::guard('provider')->user()->service->service_model : "" }}">
                                                </div>
                                            </div>
                                            <div class="prof-sub-col col-sm-6 col-xs-12 no-right-padding">
                                                <div class="form-group">
                                                    <label>@lang('provider.vehicle_year')</label>
                                                    <select name="service_year" class="form-control" style="height: 44px;">
                                                        @foreach (range(1940, \Carbon\Carbon::now()->format('Y')) as $year)
                                                            <option value="{{ $year }}" {{ (@Auth::guard('provider')->user()->service->service_year ? @Auth::guard('provider')->user()->service->service_year : "") == $year ? 'selected' : '' }}>{{ $year }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row no-margin">
                                            <div class="prof-sub-col col-sm-6 col-xs-12 no-left-padding">
                                                <div class="form-group">
                                                    <label>@lang('provider.vehicle_ac')</label>
                                                    <input type="text"  placeholder="@lang('provider.vehicle_ac')" class="form-control" name="service_ac" value="{{ @Auth::guard('provider')->user()->service->service_ac ? @Auth::guard('provider')->user()->service->service_ac : "" }}">
                                                </div>
                                            </div>
                                            <div class="prof-sub-col col-sm-6 col-xs-12 no-right-padding">
                                                <div class="form-group">
                                                    <label>@lang('provider.vehicle_name')</label>
                                                    <input type="text"  placeholder="@lang('provider.vehicle_name')" class="form-control" name="service_name" value="{{ @Auth::guard('provider')->user()->service->service_name ? @Auth::guard('provider')->user()->service->service_name : "" }}">
                                                </div>
                                            </div>
                                        </div>
                                            <div class="row no-margin">
                                                <div class="prof-sub-col col-sm-6 col-xs-12 no-left-padding">
                                                    <div class="form-group">
                                                        <label>@lang('provider.vehicle_color')</label>
                                                        <input type="text"  placeholder="@lang('provider.vehicle_color')" class="form-control" name="service_color" value="{{ old('service_color', @Auth::guard('provider')->user()->service->service_color ? @Auth::guard('provider')->user()->service->service_color : "") }}">
                                                    </div>
                                                </div>
                                            </div>

                                         <!-- Prof-form-sub-sec -->
                                        <div class="prof-form-sub-sec">
                                            <div class="col-xs-12 col-md-6">
                                                <button type="submit" class="btn btn-block btn-success shadow-box" style="margin-left: -15px;">@lang('provider.update')</button>
                                            </div>
                                            <div class="col-xs-12 col-md-6">
                                                <a href="{{url('provider/profile')}}" class="btn btn-block btn-danger shadow-box" style="margin-left: -15px;">@lang('provider.cancel')</a>
                                            </div>
                                        </div>
                                        <!-- End of prof-sub-sec -->
                                        
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                  
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
</div>            
@endsection
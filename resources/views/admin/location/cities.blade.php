@extends('admin.layout.base')

@section('title', 'City')

@section('content')
<style>.dataTables_empty{display:none!important;}</style>
    <div class="content-area py-1" id="zoneModel">
        <div class="container-fluid">
            
            <div class="box box-block bg-white">
                <h5 class="mb-1"><i class="ti-map"></i>&nbsp;Cities <hr></h5>
                   <div class="row" style="display:block">
                <div class="col-sm-3">
                	<div class="form-group">
						
						<input type="hidden" name="country_id" id="country_id"/>
						<select class='form-control' v-model='country' @change='getStates()' name="country_name" id="country_name">
							<option value='0' >Select Country</option>
							<option v-for='data in countries' :value='data.id'>@{{ data.name }}</option>
						</select>
					</div>
				</div>
				 <div class="col-sm-3">
					<div class="form-group">
					
						<input type="hidden" name="state_id" id="state_id"/>
					    <select class='form-control' v-model='state' @change='getCities()' name="state_name" id="state_name">
					    	<option value='0' >Select State</option>
							<option v-for='data in states' :value='data.id'>@{{ data.name }}</option>
						</select>
					</div>
				 </div>
				 </div>
				 <div class="clearfix"></div>
                <a href="{{ route('admin.location.create') }}" style="margin-left: 1em;" class="btn shadow-box btn-success btn-rounded pull-right"><i class="fa fa-plus"></i> Add City</a>
                
                <table class="table table-striped table-bordered dataTable" id="table-2" style="width:100%;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Created at</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
      
                    
                        <tr v-for="(city,index) in cities">
                            <td>@{{index + 1}}</td>
                            <td>@{{city.name}}</td>
                            <td>@{{city.created_at}}</td>
                            <td>
                                <form :action="'{{url('admin/city')}}/'+city.id" method="POST">
                                    {{ csrf_field() }}
                                    
                                    <input type="hidden" name="_method" value="DELETE">
                                    <a :href="'{{url('admin/city')}}/'+city.id+'/edit'" class="btn shadow-box btn-success"><i class="fa fa-edit"></i></a>
                                    <button class="btn btn-danger shadow-box" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                  
                    </tbody>
                    <tfoot>
                        <tr>
                           <th>ID</th>
                            <th>Name</th>
                            <th>Created at</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
        </div>
    </div>
@endsection
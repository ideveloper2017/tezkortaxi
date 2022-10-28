
							
							
			<div class="form-group all row">
					<label for="all" class="col-xs-12 col-form-label">
					    <div class="col-xs-10">
					        <input type="checkbox" class="form-check-input" name="all"  value="all" id="all">All</div></label>
				</div>
				<div class="form-group zone_provider  row">
					<label for="type" class="col-xs-12 col-form-label">Choose Driver</label>
					<div class="col-xs-10">
						<select name="providers" class="form-control">
						    <option value="0">Choose One</option>
							@forelse($zone_provider as $zone)
							<option value="{{$zone->id}}">{{$zone->first_name}}</option>
							@empty
							@endforelse
						</select>
					</div>
				</div>
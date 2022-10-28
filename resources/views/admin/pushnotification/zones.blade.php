                    <label for="type" class="col-xs-12 col-form-label">Location</label>
					<div class="col-xs-10">
						<select name="zones" class="form-control" id="zones" required="" onChange="getZone(this.value)">
						    <option value="0">Choose One</option>
						    @foreach($zones as $zone)
							<option value="{{$zone->id}}">{{$zone->zone_name}}</option>
							@endforeach
					    </select>
					</div>
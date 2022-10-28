/***************************
All Common Funciton here
****************************/
var destination_latitude=0, destination_longitude=0, source_latitude=0, source_longitude=0;

var map, mapMarkers = [];
var googleMarkers = [];
var source, destination;
var infowindow;

var s_input, d_input;
var s_latitude, s_longitude;
var d_latitude, d_longitude;
var distance;
var index;
var trip_data = null;
var list_status = '';
var click_counter = 0;
var fn_counter = 0;
var allProviders = [];
var user_data = null;

var ride_form = $('#form-create-ride');
var s_input = document.getElementById('s_address');
var d_input = document.getElementById('d_address');

var s_latitude = document.getElementById('s_latitude');
var s_longitude = document.getElementById('s_longitude');

var d_latitude = document.getElementById('d_latitude');
var d_longitude = document.getElementById('d_longitude');

var distance = document.getElementById('distance');


//live tracking

var Drivers = [];
var time	= 5000;
var users;
var providers;
var flag =  false;
var ajaxMarkers = [];

function initMap() {
	
	map = new google.maps.Map(document.getElementById('map'), {
        mapTypeControl: false,
        zoomControl: true,
        center: {lat: parseInt(current_lat), lng: parseInt(current_long)},
        zoom: 5,		
        styles : [{"elementType":"geometry","stylers":[{"color":"#f5f5f5"}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#f5f5f5"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#bdbdbd"}]},{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"color":"#e4e8e9"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#7de843"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#ffffff"}]},{"featureType":"road.arterial","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#dadada"}]},{"featureType":"road.highway","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},{"featureType":"transit.station","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#c9c9c9"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#9bd0e8"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]}]
    });
	
	marker = new google.maps.Marker({
        map: map,
        draggable: true,
        anchorPoint: new google.maps.Point(0, -29),
        icon: bases_url+'/asset/img/c.png'
    });

	
    markerSecond = new google.maps.Marker({
        map: map,
        draggable: true,
        anchorPoint: new google.maps.Point(0, -29),
        icon: bases_url+'/asset/img/d.png'
    });
	
	
	setInterval(ajaxMapData, 3000);
		
	directionsService = new google.maps.DirectionsService();
	directionsDisplay = new google.maps.DirectionsRenderer({suppressMarkers: true});


	new AutocompleteDirectionsHandler(map);
}

function autocomplete_init (input ) {
	return new google.maps.places.Autocomplete(input);
}

function AutocompleteDirectionsHandler(map) {

	this.map = map;
    this.originPlaceId = null;
    this.destinationPlaceId = null;
    this.travelMode = 'DRIVING';
	
	directionsDisplay.setMap(map);
	/* var searchid  = document.getElementById("searchid");
	if(searchid!='undefined') {
		autocomplete_search = autocomplete_init(searchid);
		console.log(autocomplete_search);
		autocomplete_search.bindTo('bounds', map);

		autocomplete_search.addListener('place_changed', function(event) {
			var place = autocomplete_search.getPlace();
			if (place.hasOwnProperty('place_id')) {
				if (!place.geometry) {
					window.alert("Autocomplete's returned place contains no geometry");
					return;
				}

				s_latitude.value = place.geometry.location.lat();
				s_longitude.value = place.geometry.location.lng();
				
			} else {
				service.textSearch({
					query: place.name
				}, function(results, status) {
					if (status == google.maps.places.PlacesServiceStatus.OK) {
						console.log('Autocomplete Has No Property');
						s_latitude.value = results[0].geometry.location.lat();
						s_longitude.value = results[0].geometry.location.lng();
						s_input.value = results[0].formatted_address;
						
					}
				});
			}
			
		});
		
	} */
	
	if(s_input && d_input  ) {
		
		autocomplete_source = autocomplete_init(s_input);
		autocomplete_source.bindTo('bounds', map);

		autocomplete_destination = autocomplete_init(d_input);
		autocomplete_destination.bindTo('bounds', map);	

			

		autocomplete_source.addListener('place_changed', function(event) {
			var place = autocomplete_source.getPlace();
			if (place.hasOwnProperty('place_id')) {
				if (!place.geometry) {
					window.alert("Autocomplete's returned place contains no geometry");
					return;
				}

				s_latitude.value = place.geometry.location.lat();
				s_longitude.value = place.geometry.location.lng();
				
			} else {
				service.textSearch({
					query: place.name
				}, function(results, status) {
					if (status == google.maps.places.PlacesServiceStatus.OK) {
						console.log('Autocomplete Has No Property');
						s_latitude.value = results[0].geometry.location.lat();
						s_longitude.value = results[0].geometry.location.lng();
						s_input.value = results[0].formatted_address;
						
					}
				});
			}
			
			updateProviders();
			fare();
			
		});
		

		autocomplete_destination.addListener('place_changed', function(event) {
			
			var place = autocomplete_destination.getPlace();
			if (place.hasOwnProperty('place_id')) {
				if (!place.geometry) {
					window.alert("Autocomplete's returned place contains no geometry");
					return;
				}
				
			   d_latitude.value = place.geometry.location.lat();
			   d_longitude.value = place.geometry.location.lng();
				
			} else {
				service.textSearch({
					query: place.name
				}, function(results, status) {
					if (status == google.maps.places.PlacesServiceStatus.OK) {   
						d_latitude.value = results[0].geometry.location.lat();
						d_longitude.value = results[0].geometry.location.lng();
						d_input.value = results[0].formatted_address;
					}
				});
			}
			
			fare();
			
		});
		
		this.setupPlaceChangedListener(autocomplete_source, 'ORIG');
		this.setupPlaceChangedListener(autocomplete_destination, 'DEST');
		
	}
	
}


AutocompleteDirectionsHandler.prototype.setupPlaceChangedListener = function(autocomplete, mode) {
    var me = this;
    autocomplete.bindTo('bounds', this.map);
    autocomplete.addListener('place_changed', function() {
        var place = autocomplete.getPlace();
        if (!place.place_id) {
            // window.alert("Please select an option from the dropdown list.");
            return;
        }
        if (mode === 'ORIG') {
            me.originPlaceId = place.place_id;
        } else {
            me.destinationPlaceId = place.place_id;
        }
        me.route();
    });
};



AutocompleteDirectionsHandler.prototype.route = function() {
    if (!this.originPlaceId || !this.destinationPlaceId) {
        return;
    }
    
    var me = this;

    directionsService.route({
        origin: {'placeId': this.originPlaceId},
        destination: {'placeId': this.destinationPlaceId},
        travelMode: this.travelMode
    }, function(response, status) {
        if (status === 'OK') {
            directionsDisplay.setDirections(response);
			  marker.setPosition(response.routes[0].legs[0].start_location);
              markerSecond.setPosition(response.routes[0].legs[0].end_location);
              //distance.value = response.routes[0].legs[0].distance.value / 1000;
				
        } else {
            window.alert('Directions request failed due to ' + status);
        }
    });
};


function stopRKey(evt) { 
	var evt = (evt) ? evt : ((event) ? event : null); 
	var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null); 
	if ((evt.keyCode == 13) && (node.type=="text"))  {return false;} 
} 

document.onkeypress = stopRKey;



function getTripById( trip_id ) {
	var trip = null;
	
	if( trip_id ) {
		trip_data.forEach(function(trip_data) {
			if( trip_id == trip_data.id ) {
				trip = trip_data;
			}
		});		
	}
	
	return trip;
	
}

/*
function getTripsUpdate() {
	fn_counter++;
	$.get('/dispatcher/dispatcher/providers',{
		 latitude:($("#s_latitude").val())?$("#s_latitude").val():current_lat,
		 longitude:($("#s_longitude").val())?$("#s_longitude").val():current_long,
		 service_type:$("#service_type").val()
	}, function(result) {
		if(googleMarkers.length) {
			for (var i = 0; i < googleMarkers.length; i++) {
				googleMarkers[i].setMap(null);
			}
			
			googleMarkers = [];		
		}
		
		ProviderShow(result.data);
		
	}.bind(this));
}



function ProviderShow(providers) {
    providers.forEach(function(provider) {
    	addMarkerToMap(provider);
    });
}



function addMarkerToMap(element) {
    var infowindow = new google.maps.InfoWindow();
	
	markercar = new google.maps.Marker({
		position: {
			lat : parseFloat( element.latitude ),
			lng : parseFloat( element.longitude )
		},
		id: element.id,
		map: map,
		title: element.service.service_number,
		icon: '/asset/img/marker-car.png'
	});
	
    var content = "<p>Name : "+element.first_name+" "+element.last_name+"</p>"+"<p>Rating : "+element.rating+"</p>";

	googleMarkers.push(markercar);
	
    google.maps.event.addListener(markercar, 'click', (function(markercar, content) {
		return function() {
		infowindow.setContent(content);
		infowindow.open(map, markercar);
		}
	})(markercar, content));
	
}


*/

function updateSource(location) {
	map.panTo(location);
	marker.setPosition(location);
	marker.setVisible(true);
	map.setZoom(15);
	updateSourceForm(location.lat(), location.lng());
	if(destination != undefined) {
		updateRoute();
	}
}

function updateDestination(location) {
	map.panTo(location);
	markerSecond.setPosition(location);
	markerSecond.setVisible(true);
	updateDestinationForm(location.lat(), location.lng());
	updateRoute();
}
	
 function updateRoute() {
    directionsDisplay.setMap(null);
	directionsDisplay.setMap(map);

	directionsService.route({
		origin: source,
		destination: destination,
		travelMode: google.maps.TravelMode.DRIVING,
		// unitSystem: google.maps.UnitSystem.IMPERIAL,
	}, function(result, status) {
		if (status == google.maps.DirectionsStatus.OK) {
			directionsDisplay.setDirections(result);

			marker.setPosition(result.routes[0].legs[0].start_location);
			markerSecond.setPosition(result.routes[0].legs[0].end_location);

			distance.value = result.routes[0].legs[0].distance.value / 1000;

			fare();
		}
	});
}


function updateSourceForm(lat, lng) {
	s_latitude.value = lat;
	s_longitude.value = lng;

	source = new google.maps.LatLng(lat, lng);
}

function updateDestinationForm(lat, lng) {
	d_latitude.value = lat;
	d_longitude.value = lng;
	destination = new google.maps.LatLng(lat, lng);
}

    
function updateMarker(event) {

    marker.setVisible(true);
	marker.setPosition(event.latLng);

	var geocoder = new google.maps.Geocoder();
	geocoder.geocode({'latLng': event.latLng}, function (results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			if (results[0]) {
				s_input.value = results[0].formatted_address;
				s_state.value = '';
				s_country.value = '';
				s_city.value = '';
				s_pin.value = '';
			} else {
				alert('No Address Found');
			}
		} else {
			alert('Geocoder failed due to: ' + status);
		}
	});

	updateSource(event.latLng);
}
	


function updateMarkerSecond(event) {

	markerSecond.setVisible(true);
	markerSecond.setPosition(event.latLng);

	var geocoder = new google.maps.Geocoder();
	geocoder.geocode({'latLng': event.latLng}, function (results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			if (results[0]) {
				d_input.value = results[0].formatted_address;
				d_state.value = '';
				d_country.value = '';
				d_city.value = '';
				d_pin.value = '';
			} else {
				alert('No Address Found');
			}
		} else {
			alert('Geocoder failed due to: ' + status);
		}
	});

	updateDestination(event.latLng);
}
	

/*
function assignProviderPopPicked(provider) {
   
    for (var i = mapMarkers.length - 1; i >= 0; i--) {
        if(mapMarkers[i].provider_id == provider.id) {
            index = i;
        }
        mapMarkers[i].infowindow.close();
         mapMarkers[i].infowindow.open(map, mapMarkers[i]);
    }
    console.log('index', provider);
     //mapMarkers[index].setPosition({lat: provider.latitude, lng: provider.longitude});
     //mapMarkers[index].infowindow.open(map, mapMarkers[index]);
}
*/

//when model popup try again button function
function dispatcherRequest(){
    $.ajax({
		url: bases_url+'/dispatcher/dispatcher',
		dataType: 'json',
		headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
		type: 'POST',
		data: $("#form-create-ride").serialize(),
		success: function(data) {
		   if(data.status=='SEARCHING'){
			   
			   $("#wait-modal").modal('show');
			   //countdown_init();
		   }
		}.bind(this)
	});
}


function manualRequest(){
	$("#wait-modal").modal('show');
	if( mySwitch.isChecked ) {
		$('.switchery').trigger('click');
	} 
}

function dispatchinglf(trip) {

    if (trip.status==='DROPPED') { 
        return false;
    }
   
    return dispatchinglf(trip);
}



/*******************************
Common Function end here
Function for new booking page
*******************************/
// All Event Listener Here 

$('.btn_corporate').click( function() {
	$('#corporate_list').hide();
	if( $(this).find('input').val() == '2' ) {
		$('#corporate_list').show();
	}
});

 $('#provider_auto_assign').change( function() {
  if(!this.checked){
		var html ='';
		updateProviders();
		$("#provider_list").show();
	
	} else {
		$("#provider_list").hide();
	}
  
});


function updateProviders() {
	$.ajax({
		url: bases_url+'/dispatcher/dispatcher/provider_list',
		dataType: 'json',
		headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
		type: 'POST',
		data: $("#form-create-ride").serialize(),
		success: function(data) {
			if( data ) {
				var html ='';
			   $.each(data, function(i, item) {
				   html+='<a class="dr_item" pr_id="'+item.id+'"> '+item.first_name+' '+item.last_name+' </a>';
			   });
			   
				$("#dr_list").html(html);
			}

		}.bind(this)
	});
}

$(window).load(function () {
		$("#provider_list").hide();
	page = location.pathname;
	
	if( page == '/dispatcher' ) {
		$('#provider_auto_assign').trigger('click');
	}
	
	if( page == '/dispatcher' || page == '/dispatcher/map' ) {	
		setInterval(zonesUpdate, 5000);
	}
	
	/* 
	if( page == '/dispatcher') {
		setInterval(getTripsUpdate, 3000);	
	} */
	
	
	if( page == '/dispatcher/recent-trips' ) {
		$('#dispatch-map').trigger('click');
		dispatcherAcive();
		setInterval(updateTripStatus, 3000);
	}
});


// Ride Form Reset
$('.form_create_ride_reset_btn').click(function() {
	ride_form[0].reset();
	$('#fareMap').fadeOut();
	ride_form.find('#s_latitude').val('');
	ride_form.find('#s_longitude').val('');
	ride_form.find('#d_latitude').val('');
	ride_form.find('#d_longitude').val('');
	ride_form.find('#distance').val('');
	ride_form.find('#request_id').val('');
	initMap();
	
});

//New Form Create function
ride_form.find('.btn-success').click(function(e) {
	e.preventDefault();

	var form	= 	$(this).closest('form');
	form.find('.has-error').removeClass('has-error');
	form.find('.help-block').remove();
	email = form.find('#email'); 
	
	var Input = { mobile: 'Mobile', email: 'Email', s_latitude: 'Source Latitude', s_longitude: 'Source Latitude', d_latitude: 'Source Latitude', d_longitude: 'Source Latitude' ,s_address: 'Pickup Address', d_address: 'Dropped Address', distance: 'Distance' };
	flag = true;
	var html = '';
	var i = 1;
	$.each(Input, function (ind, value) {
		if ( ! $.trim( $('#'+ind).val() ) ) {
			
			if( ind == 's_latitude'  || ind == 's_longitude' || ind == 'd_latitude'  || ind == 'd_longitude' ) {
				if(i == 1 ) {
					alert('Pickup/Dropped Location didn\'t fill correctly. Please fill again!');
				}
				
				i++;
			} else {
				$('#'+ind).after('<span class="help-block text-danger">Please fill the '+value+'</span>');
				$('#'+ind).closest('.form-group').addClass('has-error');				
			}
			
			flag = false; 
		}
	});
	
	var schedule_at = $('#schedule_time');
	
	if( schedule_at.val().length ) {
		var current_time = Math.round( new Date().getTime()/1000);
		req_start  =  current_time + schedule_req_time * 60;
		var schedule_time = toTimestamp($.trim(schedule_at.val()) );
		if( schedule_time < req_start ) {
			schedule_at.after('<span class="help-block text-danger">Please fill the Schedule Time as per admin guidelines!</span>');
			schedule_at.closest('.form-group').addClass('has-error');
			flag = false;
		}		
	}
	
	e = $.trim( email.val() );
	if( e ) {
		if( ! validateEmail( e ) ) {
			email.after('<span class="help-block text-danger">Email Id is not valid!</span>');
			email.closest('.form-group').addClass('has-error');
			flag = false;	
		}		
	}
	
	if( flag ) {
		createRide();
	}
});

$('#service_type').change(function() {
	fare();
	updateProviders();
	
});
 
function toTimestamp(strDate){
   var datum = Date.parse(strDate);
   return datum/1000;
}
 
//Custom function
function  fare() {
var HTML = '';
   $.ajax({
		url: bases_url+'/dispatcher/dispatcher/getFare',
		dataType: 'json',
		headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
		type: 'POST',
		data: $("#form-create-ride").serialize(),
		success: function(data) {
				HTML  +='<div class="row no-margin"><div class="col-md-12"><ul class="list-group">';
				HTML  +='<li class="list-group-item d-flex justify-content-between align-items-center">Type: <span class="badge badge-primary badge-pill">'+data.service.name+'</span></li><li class="list-group-item d-flex justify-content-between align-items-center">Total Distance: <span class="badge badge-primary badge-pill">'+data.distance+'  Kilometer</span></li>';
				HTML  +='<li class="list-group-item d-flex justify-content-between align-items-center">ETA: <span class="badge badge-primary badge-pill">'+data.time+'</span></li> <li class="list-group-item d-flex justify-content-between align-items-center"> Estimated Fare:  <span class="badge badge-primary badge-pill">$'+data.estimated_fare+'</span></li></ul></div></div>';
				  $('#fareMap').html(HTML);
				  $('#fareMap').fadeIn();
				  $('#distance').val(data.distance);
				  $('#estimated_price').val(data.estimated_fare);
		},
		
		error: function(data) {
			errors = data.responseJSON;
			if(errors.error) {
				alert(errors.error);
			}
		}
	});	
}

function createRide() {
	$('.preloader').fadeIn('fast');
	$.ajax({
		url: bases_url+'/dispatcher/dispatcher',
		dataType: 'json',
		headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
		type: 'POST',
		data: $("#form-create-ride").serialize(),
		error: function(data) {
			errors = data.responseJSON;
			console.log(errors);
		},
		
		success: function(json) {
			if(json) {
				$('.preloader').fadeOut('fast');
				if(json.flash_error) {
					alert( json.flash_error );
				} else {
					window.location.replace(bases_url+"/dispatcher/recent-trips");
				}
				
				console.log(json);
				
			} 
		}
		
	});
}


function checkRequestStatus(id) {
	
	setInterval(function() {
		
		if( time > 50000 ) {
			window.location.replace(bases_url+"/dispatcher/recent-trips");
		}
		
		$.get(bases_url+'/dispatcher/trip_data',{
			trip_id: id
		}, function( result ) {
			if( result ) {
				if(result.status != 'SEARCHING') {
					if( result.status == 'ACCEPTED' || result.status == 'STARTED' ) {
						$('#wait-modal .modal-title').html('Driver Accepted The Request');
					}
					
					setTimeout ( window.location.replace(bases_url+"/dispatcher/recent-trips") ,2000 );
				}			
			}
		});

		time+=5000;
		
	
	}, 3000 );
	
}



/***************************
New booking page end here
Function for recent-trip page
***************************/
//Event Listener

$(document).on('click', '#submit_assign_btn', function() {
	$.ajax({
		url: bases_url+'/dispatcher/assign/company',
		dataType: 'json',
		data: $('#assign_copatner_form').serialize(),
		headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
		type: 'POST',
		success: function(json) {
			if(json.error) {
				alert(json.error);
			}
			
			if( json.trip.status == 'COMPLETED' ) {
				window.location.replace(bases_url+"/dispatcher/recent-trips");
			}
		},
		error: function(xhr) {
			errors = $.parseJSON(xhr.responseText);
			window.location.replace(bases_url+"/dispatcher/recent-trips");
		}
	});
});


$(document).on('click', '#submit_reason_btn', function() {
	var form	= 	$(this).closest('form');
	var status	= 	form.find('input[name=cancel_status]').val();
	var reason	=	form.find('textarea').val();
	
	if(! $.trim(reason).length ) {
		alert('Please enter a reason must!');
		return false;
	}
	
	if( status == "cancel" ) {
		$status = 'Are you sure want to cancel the trip!';
	}
	
	if( status == "dead" ) {
		$status	= 'Are you sure want to dead  the trip!';
	}
	
	if( status == 'reassign' ) {
		$status	= 'Are you sure want to re-assign the trip!';
	}
	
	if( status == 'assign' ) {
		$status	= 'Are you sure want to assign the trip!';
	}
	
	if( confirm( $status) ) {
		
		rideUpadate(form.serialize());
	}
	
});


function validateEmail(email) {
    var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    if (filter.test( email ) ) {
        return true;
    } else {
		return false;
    }
}



$(document).on('click', '#booking_btn', function() {
    
	var form	= 	$(this).closest('form');
	form.find('.has-error').removeClass('has-error');
	form.find('.help-block').remove();
	var email 		= form.find('#email');
	var special_note = $.trim( form.find('#special_note').val() );
	
	flag = true;
	/*$('#booking_form input, #booking_form textarea').each(function (ind, value) {
		if ( ! $.trim( $(this).val() ) ) {
			$(this).after('<span class="help-block">Please fill the '+ $(this).attr('id') +'</span>');
			$(this).closest('.form-group').addClass('has-error');
			flag = false;
		}
	});
	
	if( ! validateEmail( $.trim( email.val() ) ) ) {
		email.after('<span class="help-block">Email Id is not valid!</span>');
		$(this).closest('.form-group').addClass('has-error');
		flag = false;	
	} 
    alert(flag);*/
	if( flag ) {
		
		$.ajax({
			url: bases_url+'/dispatcher/request/update',
			dataType: 'json',
			data: form.serialize(),
			headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
			type: 'POST',
			success: function( json ) {
			    //alert( json );
			    //window.location.replace(bases_url+"/dispatcher/recent-trips");
				if( json.error ) {
					alert( json.error );
				} else {
					window.location.replace(bases_url+"/dispatcher/recent-trips");
				}
			},
			error: function(xhr) {
				errors = $.parseJSON(xhr.responseText);
				
				console.log( errors );
				//console.log( errors );
				//window.location.replace("/dispatcher/recent-trips");		
			}
		});		
	}
	
});


//Custom Function


function showUserDetail( trip ) {
	
	if( trip != null ) {
	
		var service_name = null;
		var fare = null;
		var estimated_price =  $('#assign_manual-modal .user_et_form_gp');
		var user_service_type = $('#assign_manual-modal .user_sr_type_form_gp');
		
		if(services.length) {
			services.forEach(function(item, ind) {
				if( item.id == trip.service_type_id ) {
					service_name = item.name;
				}
			});
		}
		
		$('#assign_manual-modal .special_note').val('');
		$('#assign_manual-modal #request_id').val(trip.id);
		$('#assign_manual-modal .user_booking_id').text(trip.booking_id);
		$('#assign_manual-modal .user_name').text(trip.user.first_name +' '+trip.user.last_name);
		$('#assign_manual-modal .user_email').text( trip.user.email );
		$('#assign_manual-modal .user_mobile').text(trip.user.mobile);
		$('#assign_manual-modal .user_from').text(trip.s_address);
		$('#assign_manual-modal .user_to').text(trip.d_address);
		$('#assign_manual-modal .user_distance').text(trip.distance +' KM');
		
		if( service_name ) {
			user_service_type.find('.user_service_type').text(service_name);
			user_service_type.show();
		} else {
			user_service_type.hide();
		}
		
		if( fare ) {
			estimated_price.find('.user_estimated_price').text(fare);
			estimated_price.show();
		} else {
			estimated_price.hide();
		}	
	}
	
}


function rideUpadate(data) {
	$.ajax({
		url: bases_url+'/dispatcher/discancel/ride',
		dataType: 'json',
		data: data,
		headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
		type: 'POST',
		success: function( data ) {
			// console.log(data);
			//  alert(data);
			window.location.replace(bases_url+"/dispatcher/recent-trips");
		}.bind(this),
		error: function(xhr) {
			errors = $.parseJSON(xhr.responseText);
			//console.log(errors);
			alert(errors.error);
			window.location.replace(bases_url+"/dispatcher/recent-trips");		
		}.bind(this)
	});
}


function getUpdateFilterData(props) {
	$('.preloader').fadeIn('fast');
	$('.dispatcher-nav').find('.active').removeClass('active');
	$(this).addClass('active');
	
	initMap();
	$.get(bases_url+'/dispatcher/dispatcher/trips/', {
		 filter: props
	}, function(result) { 

		if( result ) {
			console.log(result);
			
			if( click_counter > 0 ) {
				$('.preloader').fadeOut('fast');
			}
			makeListHtml(props, result);
			trip_data = result;
			list_status = props;
				
		}   else {
			initMap();
		}
	});
	
	click_counter++;
}


function getLogsData() {
	$.get(bases_url+'/dispatcher/dispatcher/getlogs', function(result) {
		var html = '';
		if( result.length ) {
			html = '<div id="logs"><ul class="log_ul ul">';
			result.forEach(function( $line ) {
				html+= '<li>'+$line+'</li>';
			});
			html+= '</ul></div>'
		}
		
		$('#create-ride  .items-list').html(html);
		
	});
}

function makeListHtml(props, result ) {
	var html = '';
	var data = result;
	
	if(props=='')
	{
	    $('#dispatch-map').addClass('active');
	}
	if(props=='dispatch-new')
	{
	    $('#dispatch-new').addClass('active');
	}
	if(props=='dispatch-dispatching')
	{
	    $('#dispatch-dispatching').addClass('active');
	}
	if(props=='dispatch-completed')
	{
	    $('#dispatch-completed').addClass('active');
	}
	if(props=='dispatch-cancelled')
	{
	    $('#dispatch-cancelled').addClass('active');
	}
	if(props=='dispatch-scheduled')
	{
	    $('#dispatch-scheduled').addClass('active');
	}
	if(props=='dispatch-dead')
	{
	    $('#dispatch-dead').addClass('active');
	}
	if(props=='dispatch-log')
	{
	    $('#dispatch-log').addClass('active');
	}

	
	if( data.length > 0 ) {
		console.log(data);
	    //alert(data.length);
		for(var i=0; i < data.length; i++) {
		//alert(data[i].status);	
			if( data[i].status ==  'COMPLETED' ) {
				$tag = 'tag-success';
			} else if (  data[i].status ==  'CANCELLED' || data[i].status ==  'DEAD'  )  {
				$tag = 'tag-danger';
			} else if (  data[i].status ==  'SEARCHING' )  {
				$tag = 'tag-warning';
			} else if (  data[i].status ==  'SCHEDULED' )  {
				$tag = 'tag-primary';
			} else {
				$tag = 'tag-info';
			}
			var assign = ( data[i].current_provider_id == 0 ) ? 'Manual Assignment' :  'Auto Search';
		    
		    if( data[i].status == 'STARTED') {
				html += 	'<div style="height: auto;" class="il-item '+data[i].status+'" id="trip_item_'+data[i].id+'">';
			}   else if( data[i].status == 'CANCELLED') {
				html += 	'<div style="height: auto;" class="il-item '+data[i].status+'" id="trip_item_'+data[i].id+'">';
			}   else{
			    html += 	'<div style="height: auto;" class="il-item '+data[i].status+'" id="trip_item_'+data[i].id+'">';
			}
			
			html += 		'<a class="text-black" data-id="'+ data[i].id +'" href="#">';
			html += 			'<div class="media">';
			html += 				'<div class="media-body" onClick="ongoingInitialize('+data[i].id+')">';
			
			html +=					'<p class="tag_trip">'+ data[i].user.first_name +'<span style="font-size: 12px;padding: 7px;" class="mod_tag_'+data[i].id+' tag pull-right '+$tag+'">'+data[i].status+'</span></p>';
			html +=					'<h6 class="media-heading">Booking Id: '+ data[i].booking_id +'</h6>';
			html +=					'<h6 class="media-heading">From: '+ data[i].s_address +'</h6>';
			html +=					'<h6 class="media-heading">To: '+ data[i].d_address+'</h6>';
			html +=					'<h6 class="media-heading">Payment: '+ data[i].status +'</h6>';
			
			if( data[i].status == 'SEARCHING') {
				html +=					'<progress class="progress progress-success progress-sm" max="100"></progress>';
			}
			
			html += 				'<h6 class="media-heading">Created At: ' + data[i].created_at +'</h6>';
			
			if( !( data[i].status == 'SEARCHING' || !data[i].status == 'PENDING' ||  data[i].status == 'SCHEDULED' ) ) {
				html += 				'<h6 class="media-heading">Assigned At: ' + data[i].assigned_at +'</h6>';
			}
			
			if( data[i].status == 'SCHEDULED' ) {
				html += 				'<h6 class="media-heading">Schedule Time: ' + data[i].schedule_at +'</h6>';
			}
			
			if( data[i].status == 'COMPLETED' || data[i].status == 'ACCEPTED' || data[i].status == 'STARTED' || data[i].status == 'ARRIVED' || data[i].status == 'PICKEDUP' || data[i].status == 'SCHEDULED' || data[i].status == 'PENDING' ) {
    			html += 				'<h6 class="media-heading">Driver Name: ' + data[i].provider.first_name +'</h6>';
    			
    			if( data[i].payment !=null ){
    			    html += 				'<h6 class="media-heading">Total Fare: $'+data[i].payment.total+'</h6>';
    			}
			}
			
			
			html +=				'</div><div class="clearfix"></div>';

			
			if( data[i].status == 'SEARCHING' || data[i].status == 'ACCEPTED' || data[i].status == 'STARTED' || data[i].status == 'ARRIVED' || data[i].status == 'PICKEDUP' || data[i].status == 'SCHEDULED' || data[i].status == 'PENDING' ) {
			
				html +=				'<div class="create_ride_li_btn" id="req_btn'+data[i].id+'">';
				html += 				'<button type="button" class="btn btn-danger btn_action" request_id="'+data[i].id+'"  data-toggle="modal" data-target="#cancel_ride_form-modal"  cancel_status="cancel"  user_id="'+data[i].user_id+'">CANCEL</button>';
				html += 				' | <button type="button" class="btn btn-danger btn_action"  request_id="'+data[i].id+'" data-toggle="modal"  cancel_status="dead" data-target="#cancel_ride_form-modal"  user_id="'+data[i].user_id+'">DEAD</button> ';	
				html += 				' | <button type="button" class="btn btn-success btn_action"  request_id="'+data[i].id+'" data-toggle="modal" cancel_status="edit" data-target="#booking-modal">EDIT</button> ';
				
				if( data[i].status == 'PENDING' ||  data[i].status == 'SCHEDULED' ) {
					
					html += 				'| <button type="button" class="btn btn-info btn_action" data-toggle="modal" data-target="#assign_manual-modal"  cancel_status="cancel_assign" user_id="'+data[i].user_id+'" request_id="'+data[i].id+'">ASSIGN CO-PARTNER</button>';	
					html +=   				'| <button type="button" class="btn btn-info" onClick="DispatcherAssignList('+data[i].id+','+1+')">ASSIGN</button>';
					
				}
				
				if( data[i].status == 'ACCEPTED' || data[i].status == 'STARTED' || data[i].status == 'ARRIVED' || data[i].status == 'PICKEDUP' ) {	
					html +=   				'| <button type="button" class="btn btn-info" onClick="DispatcherAssignList('+data[i].id+','+2+')">RE-ASSIGN</button>';
				}
			
				
				html += 			'</div>';
			
			}
			
			html +=			'</div>';
			html +=		'</a>';
			html +=	'</div>';
		
		}
			
	}   else {
		
		html += '<div class="no_data">No Request found!</div>';
		
	}
//alert(html);
	return $('#create-ride  .items-list').empty().html(html);
	
}


 
function dispatcherAcive(){
	$(".dispatcher-tab").click(function() {
		id = $(this).attr('id');
		$('#log_wrapper').hide();
		
		$('.dispatcher-tab').removeClass('active');
		$(this).addClass('active');
		
		if( id == 'dispatcher-log' ) {
			$('#log_wrapper').slideDown();
		}
	});
}


function DispatcherAssignList(trip_id, type ) {
	var activeProviders = [];
	var trip = null;
	trip_data.forEach(function(trip_data) {
		if( trip_id == trip_data.id ) {
			trip = trip_data;
		}
	});

	if( trip != null ) {
		
		$.get(bases_url+'/dispatcher/dispatcher/providers', {
				latitude: trip.s_latitude,
				longitude: trip.s_longitude,
				service_type_id: trip.service_type_id
		}, function(result) {
			if( result.length ) {
				provider_list = result;
				var i = 0;
				provider_list.forEach(function(provider) {
					if(provider.id != trip.current_provider_id) {
						activeProviders[i] = provider;
						i++;
					}
				});
			}
			
			AvailableProviderList(activeProviders, trip, type);
			
		});
	}
}


function AvailableProviderList( providers, trip, type ) {
	var html = '';
	
	if( providers.length ) {
		providers.forEach(function(provider, ind) {
				
				action = ( type == 1 ) ?  'assign' : 'reassign';
			
				html += 	'<div class="il-item">';
				html += 		'<a class="text-black">';
				html += 			'<div class="media">';
				html += 				'<div class="media-body">';
				html +=					'<h6 class="media-heading">'+provider.first_name+' '+provider.last_name+'</h6>';
				html +=					'<h6 class="media-heading">Rating: '+ provider.rating+'</h6>';
				html +=					'<h6 class="media-heading">Phone: '+ provider.mobile +'</h6>';
				html += 				'<button type="button" class="btn btn-danger btn_action" provider_id="'+provider.id+'" request_id="'+trip.id+'" data-toggle="modal"  cancel_status="'+action+'" data-target="#cancel_ride_form-modal"  user_id="'+trip.user_id+'">Assign this Provider</button> ';						
				//html += 				'<span className="text-muted"><a href="/dispatcher/dispatcher/trips/'+trip.id+'/'+provider.id+'/'+type+'" class="btn btn-success">Assign this Provider</a></span>';
				html +=				'</div>';
				html +=			'</div>';
				html +=		'</a>';
				html +=	'</div>';
			
		});			
		
	} else {
		
		html += '<div class="no_data">No Acitve Provider found regarding choosen service type!</div>';
		
	}
	
	return $('#create-ride .items-list').html(html);
	
	
}

function assignProviderShow(providers, trip) {
	
	var infowindow = new google.maps.InfoWindow();
	
    providers.forEach(function(provider) {
		if( provider.service.service_type_id == trip.service_type_id ) {
			var markercar = new google.maps.Marker({
				position: {lat: parseInt(provider.latitude), lng: parseInt(provider.longitude)},
				map: map,
				provider_id: provider.id,
				title: provider.first_name + " " + provider.last_name,
				icon: '/asset/img/marker-car.png'
			});

			var content = "<p>Name : "+provider.first_name+" "+provider.last_name+"</p>"+
					"<p>Rating : "+provider.rating+"</p>"+
					"<p>Service Type : "+provider.service.service_type.name+"</p>"+
					"<a href='/dispatcher/dispatcher/trips/"+trip.id+'/'+provider.id+"' class='btn btn-success'>Assign this Provider</a>";
			googleMarkers.push(markercar);
			google.maps.event.addListener(markercar, 'click', (function(markercar, content) {
				return function() {
				infowindow.setContent(content);
				infowindow.open(map, markercar);
				}
			})(markercar, content));
		}
		
	});
}

//Click on Media Body function on 
function ongoingInitialize(trip_id) {
	
	var trip = null;
	trip_data.forEach(function(trip_data) {
		if( trip_id == trip_data.id ) {
			trip = trip_data;
		}
	});

	
	if( trip ) {
		map = new google.maps.Map(document.getElementById('map'), {
			center: {lat: 0, lng: 0},
			zoom: 4,
		});

		var bounds = new google.maps.LatLngBounds();

		var marker = new google.maps.Marker({
			map: map,
			anchorPoint: new google.maps.Point(0, -29),
			icon: bases_url+'/asset/img/c.png'
		});

		var markerSecond = new google.maps.Marker({
			map: map,
			anchorPoint: new google.maps.Point(0, -29),
			icon: bases_url+'/asset/img/d.png'
		});

		source = new google.maps.LatLng(trip.s_latitude, trip.s_longitude);
		destination = new google.maps.LatLng(trip.d_latitude, trip.d_longitude);

		marker.setPosition(source);
		markerSecond.setPosition(destination);

		var directionsService = new google.maps.DirectionsService;
		var directionsDisplay = new google.maps.DirectionsRenderer({suppressMarkers: true, preserveViewport: true});
		directionsDisplay.setMap(map);

		directionsService.route({
			origin: source,
			destination: destination,
			travelMode: google.maps.TravelMode.DRIVING
		}, function(result, status) {
			if (status == google.maps.DirectionsStatus.OK) {
				directionsDisplay.setDirections(result);

				marker.setPosition(result.routes[0].legs[0].start_location);
				markerSecond.setPosition(result.routes[0].legs[0].end_location);
			}
		});

		/*
		if(trip.provider) {
			var markerProvider = new google.maps.Marker({
				map: map,
				icon: "/asset/img/marker-car.png",
				anchorPoint: new google.maps.Point(0, -29)
			});

			provider = new google.maps.LatLng(trip.provider.latitude, trip.provider.longitude);
			markerProvider.setVisible(true);
			markerProvider.setPosition(provider);
			console.log('Provider Bounds', markerProvider.getPosition());
			bounds.extend(markerProvider.getPosition());
		}
		
		*/
		
		bounds.extend(marker.getPosition());
		bounds.extend(markerSecond.getPosition());
		map.fitBounds(bounds);
		
	}
}

function updateTripStatus() {

	if(list_status == '' || list_status == 'dispatch-dispatching' || list_status == 'dispatch-new' ) {
		$.get(bases_url+'dispatcher/dispatcher/trips/', {
		 filter: list_status
			}, function(result) { 
				if( result ) {
					for( var i in trip_data ) {
						if( result.length == trip_data.length && result[i].status != trip_data[i].status ) {
							$('.mod_tag_'+trip_data[i].id).text(result[i].status);
							if( result[i].status != 'SEARCHING' ) {
								$('#trip_item_'+result[i].id+' .progress').hide();
								if( result[i].status == 'COMPLETED' || result[i].status == 'CANCELLED' ) {
									$('#req_btn'+result[i].id).hide();
								}
							} else {
								$('#trip_item_'+result[i].id+' .progress').show();
							}
							
						}
					}
				}
			});
	}
}

/***********************
recent-page end here
Live Tracking page start here
************************/
//Events


$(document).on('click', '.btn_action' ,function(e) {
	
	var request_id		=	$(this).attr('request_id');
	
	if( ! request_id ) {
		alert('Something went wrong. please try again!');
	}
	
	trip = getTripById( request_id );
	var cancel_status	=	$(this).attr('cancel_status');
	
	if( trip ) {
		
		getUserData( trip );
	
		
		if( cancel_status == 'edit' ) {
			
			if( user_data == null ) {
				alert('User Not Found!');
				$('#booking-modal').modal('hide');
				return false;
				
			}
			
			if( user_data.id != trip.user_id ) {
				alert('Something went wrong, please try again');
				$('#booking-modal').modal('hide');
				return false;
			}
			
			ShowEditBookingForm(trip, user_data);
			
		} else {
		
			var form			=	$('#cancel_ride_form-modal');	
			var user_id			=	$(this).attr('user_id');
			var provider_id		=	$(this).attr('provider_id');
			form.find('input[name=cancel_status]').val(cancel_status );
			form.find('input[name=request_id]').val(request_id);
			form.find('input[name=user_id]').val(user_id);
			form.find('input[name=provider_id]').val(provider_id);
			
			if(	 cancel_status == 'cancel_assign' ) {
				showUserDetail( trip );
			}
			
		}
	}	
});


function getUserData( trip ) {
	
	$.ajax({
		url: bases_url+'/dispatcher/user',
		dataType: 'json',
		async: false,
		data: { id : trip.user_id },
		headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
		type: 'GET',
		success: function( json ) {
			user_data = json;
		}
		
		
	});
}


function ShowEditBookingForm(trip, user) {
	var form =	$('#booking_form');
	form[0].reset();
	form.find('#request_id').val( trip.id );
	form.find('#distance').val( trip.distance );
	form.find('#s_latitude').val( trip.s_latitude );
	form.find('#s_longitude').val( trip.s_longitude );
	form.find('#d_latitude').val( trip.d_latitude );
	form.find('#d_longitude').val( trip.d_longitude );
	form.find('#s_address').val( trip.s_address );
	form.find('#d_address').val( trip.d_address );
	form.find('#special_note').val( trip.special_note );
	form.find('#service_type').val( trip.service_type_id );

	//user info
	form.find('#first_name').val( user.first_name );
	form.find('#last_name').val( user.last_name );
	form.find('#email').val( user.email );
	form.find('#mobile').val( user.mobile );
	
}


function LiveTrackingInitMap() {
		
        map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: Number(current_lat), lng: Number(current_long)},
            zoom: 8,
            minZoom: 1
			
        }); 
        
		setInterval(ajaxMapData, 3000);
        var legend = document.getElementById('legend');
		
        var div = document.createElement('div');
        div.innerHTML = '<img src="' + mapIcons['active'] + '"> ' + 'Available Driver';
        legend.appendChild(div);
        
        var div = document.createElement('div');
        div.innerHTML = '<img src="' + mapIcons['riding'] + '"> ' + 'On Trip';
        legend.appendChild(div);

		
		map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(legend);
		
    }
	


function ajaxMapData() {
	clearOverlays();
	var ongoing = document.getElementById('ongoing').checked;
	var driver = document.getElementById('driver').checked;
	$.ajax({
		url: bases_url+'/dispatcher/map/ajax',
		dataType: "JSON",
		headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken,'ongoing':ongoing,'driver':driver },
		type: "GET",
		success: function(data) {
			ajaxMarkers = data;
		}
	});


	if( ajaxMarkers.length ) {
		ajaxMarkers.forEach(liveTrakingaddMarkerToMap);
	}
	
}

function clearOverlays() {
	
	for (var i = 0; i < googleMarkers.length; i++ ) {
		googleMarkers[i].setMap(null);
	}
	
	googleMarkers.length = 0;
	googleMarkers = [];
}


function zonesUpdate() {
	
	$.get(bases_url+'/dispatcher/provider_zones',{}, function( result ) {
		if( result.length ) {
			var html = '';
			var main_inner =  $('.main_inner .ul');
			result.forEach(function( zone, index ) {
				
				html+= '<li  class="zone">';
				html+=		'<div class="header">'+zone['name']+'</div>';
				html+=		'<div class="driver_list zone_'+zone['id']+'" data-zone_id="'+zone['id']+'"  id="zone_'+zone['id']+'">';	
							if( zone['drivers'].length ) {
								zone['drivers'].forEach(function( driver, ind ) {
									var bg =  ( driver.provider_status == 'riding' ) ? 'bg-danger' : '';
									
				html+=					'<div class="driver  driver_'+driver.id+'  '+bg+'" id="position_'+driver.driver_position+'">';
				html+=						'<div class="vehicle_no">'+driver.service_number+'</div>';
				html+=						'<div class="timeOut">In Time:'+driver.enter_time+'</div>';
				html+=					'</div>';
								});
								
							}
							
				html+=		'</div>';											
				html+=	'</li>';
				
			});	
			
			if( main_inner.length ) {
				main_inner.html(html);
			}
		}
	});
	
}
	
function rideOn(){
	ajaxMapData();
}
function liveTrakingaddMarkerToMap(element, index) {
	
	var infowindow = new google.maps.InfoWindow();
	
	if( element.latitude  && element.longitude ) {
		marker = new google.maps.Marker({
			position: {
				lat : parseFloat( element.latitude ),
				lng : parseFloat( element.longitude )
			},
			id: element.id,
			map: map,
			title: element.service.service_number,
			icon : mapIcons[element.service ? element.service.status : element.status],
		});

		googleMarkers.push(marker);
		
		main_content = "<p>Name : "+element.first_name+" "+element.last_name+"</p>"+"<p>Rating : "+element.rating+"</p><p>Phone No. : "+element.mobile+"</p>";
		
		google.maps.event.addListener(marker, 'click', (function(marker, main_content) {
			return function() {
			infowindow.setContent(main_content);
			infowindow.open(map, marker);
			}
		})(marker, main_content));
		
	}
	
}



/***********************
Live Tracking page start here
************************/



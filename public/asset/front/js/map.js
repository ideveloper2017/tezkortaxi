function initMap() {
    var marker;
    var map = new google.maps.Map(document.getElementById('map'), {
        mapTypeControl: false,
        zoomControl: true,
        center: {lat: current_latitude, lng: current_longitude},
        zoom: 12,
        styles : [{"elementType":"geometry","stylers":[{"color":"#f5f5f5"}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#f5f5f5"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#bdbdbd"}]},{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"color":"#e4e8e9"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#7de843"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#ffffff"}]},{"featureType":"road.arterial","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#dadada"}]},{"featureType":"road.highway","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},{"featureType":"transit.station","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#c9c9c9"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#9bd0e8"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]}]
    });

   	var oLatitudev = document.getElementById('origin_latitude').value;
    var oLongitudev = document.getElementById('origin_longitude').value;
    var dLatitudev = document.getElementById('destination_latitude').value;
    var dLongitudev = document.getElementById('destination_longitude').value;
   
    new AutocompleteDirectionsHandler(map,oLatitudev,oLongitudev,dLatitudev,dLongitudev);
	
	if( oLatitudev.length && oLongitudev.length &&  dLatitudev.length && dLongitudev.length ) {
		var polylineOptionsActual = new google.maps.Polyline({
            strokeColor: '#111',
            strokeOpacity: 0.8,
            strokeWeight: 4
		});
	
       directionsService = new google.maps.DirectionsService;
		// Instantiate a directions service.
	    // var base_url =  window.location.origin;
	   
     //    var image = base_url+'/ilyft/asset/front/img/location_new.png';

     //     marker = new google.maps.Marker({
     //        position: new google.maps.LatLng(oLatitudev, oLongitudev),
     //        map: map,
     //        icon: image
     //      });
     //     marker = new google.maps.Marker({
     //        position: new google.maps.LatLng(dLatitudev, dLongitudev),
     //        map: map,
     //        icon: image
     //      });
        
		// directionsDisplay = new google.maps.DirectionsRenderer({map: map, suppressMarkers: true, polylineOptions: polylineOptionsActual });
			
			
			
		directionsService.route({
			origin: new google.maps.LatLng(Number(oLatitudev), Number(oLongitudev)),
			destination: new google.maps.LatLng(Number(dLatitudev), Number(dLongitudev)),
			avoidTolls: true,
			avoidHighways: false,
			travelMode: google.maps.TravelMode.DRIVING
		}, function (response, status) {
			if (status == google.maps.DirectionsStatus.OK) {
				directionsDisplay.setDirections(response);
			} else {
				window.alert('Directions request failed due to ' + status);
			}

		});
		
	}

}

/**
 * @constructor
 */

function AutocompleteDirectionsHandler(map,slat1,slong1,dlat1,dlong1) {
	let slat=0;
	let slong=0;
	let dlat = 0;
	let dlong = 0;
	var marker;
    this.map = map;
    this.originPlaceId = null;
    this.destinationPlaceId = null;
    this.travelMode = 'DRIVING';
    var originInput = document.getElementById('origin-input');
    var destinationInput = document.getElementById('destination-input');
    var modeSelector = document.getElementById('mode-selector');
    var originLatitude = document.getElementById('origin_latitude');
    var originLongitude = document.getElementById('origin_longitude');
    var destinationLatitude = document.getElementById('destination_latitude');
    var destinationLongitude = document.getElementById('destination_longitude');

    var base_url =  window.location.origin;	
    var simage = base_url+'/asset/front/img/source.png';
    var dimage = base_url+'/asset/front/img/destination.png';
    var polylineOptionsActual = new google.maps.Polyline({
            strokeColor: '#111',
            strokeOpacity: 0.8,
            strokeWeight: 4
    });
     
    if( slat1.length !='' && slong1.length !='' &&  dlat1.length !='' && dlong1.length !='' ) {
       marker = new google.maps.Marker({
            position: new google.maps.LatLng(slat1, slong1),
            map: map,
            icon: simage
          });
       marker = new google.maps.Marker({
            position: new google.maps.LatLng(dlat1, dlong1),
            map: map,
            icon: dimage
          });
       directionsDisplay = new google.maps.DirectionsRenderer({map: map, suppressMarkers: true, polylineOptions: polylineOptionsActual });

   }
    this.directionsService = new google.maps.DirectionsService;


    var originAutocomplete = new google.maps.places.Autocomplete(
            originInput);
    var destinationAutocomplete = new google.maps.places.Autocomplete(
            destinationInput);
  
    originAutocomplete.addListener('place_changed', function(event) {
        var place = originAutocomplete.getPlace();
        
        if (place.hasOwnProperty('place_id')) {
            if (!place.geometry) {
                    // window.alert("Autocomplete's returned place contains no geometry");
                    return;
            }
            originLatitude.value = place.geometry.location.lat();
            originLongitude.value = place.geometry.location.lng();
            slat = place.geometry.location.lat();
            slong = place.geometry.location.lng();
           
        } else {
            service.textSearch({
                    query: place.name
            }, function(results, status) {
            	
                if (status == google.maps.places.PlacesServiceStatus.OK) {
                    originLatitude.value = results[0].geometry.location.lat();
                    originLongitude.value = results[0].geometry.location.lng();
                    slat = results[0].geometry.location.lat();
                    slong = results[0].geometry.location.lng();
                }
            });
        }
       /// alert(marker);
        if (marker && marker.setMap) {
           marker.setMap(null);
           // alert("h1");
           }
           marker = new google.maps.Marker({
            position: new google.maps.LatLng(slat, slong),
            map: map,
            icon: simage
          });
    });


    destinationAutocomplete.addListener('place_changed', function(event) {
    	var marker;
   // alert(slat1);
   if( slat1.length !='' && slong1.length !='' &&  dlat1.length !='' && dlong1.length !='' ) {
       marker = new google.maps.Marker({
            position: new google.maps.LatLng(slat1, slong1),
            map: map,
            icon: simage
          });
       marker = new google.maps.Marker({
            position: new google.maps.LatLng(dlat1, dlong1),
            map: map,
            icon: dimage
          });
       directionsDisplay = new google.maps.DirectionsRenderer({map: map, suppressMarkers: true, polylineOptions: polylineOptionsActual });

   }
        var place = destinationAutocomplete.getPlace();
       
        if (place.hasOwnProperty('place_id')) {
            if (!place.geometry) {
                // window.alert("Autocomplete's returned place contains no geometry");
                return;
            }
            destinationLatitude.value = place.geometry.location.lat();
            destinationLongitude.value = place.geometry.location.lng();
            dlat = place.geometry.location.lat();
            dlong = place.geometry.location.lng();
        } else {
            service.textSearch({
                query: place.name
            }, function(results, status) {

                if (status == google.maps.places.PlacesServiceStatus.OK) {
                    destinationLatitude.value = results[0].geometry.location.lat();
                    destinationLongitude.value = results[0].geometry.location.lng();
                    dlat = results[0].geometry.location.lat();
                    dlong = results[0].geometry.location.lng();
                }
            });
        }
        if (marker && marker.setMap) {
           marker.setMap(null);
           // alert("h2");
           }
         // markers[0].setMap(null);
         // alert(marker);
         var marker = new google.maps.Marker({
            position: new google.maps.LatLng(dlat, dlong),
            map: map,
            icon: dimage
          });

    });
    
    
    this.directionsDisplay = new google.maps.DirectionsRenderer({suppressMarkers: true, polylineOptions: polylineOptionsActual});
    this.directionsDisplay.setMap(map);
    this.setupPlaceChangedListener(originAutocomplete, 'ORIG');
    this.setupPlaceChangedListener(destinationAutocomplete, 'DEST');

}

// Sets a listener on a radio button to change the filter type on Places
// Autocomplete.

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

    this.directionsService.route({
        origin: {'placeId': this.originPlaceId},
        destination: {'placeId': this.destinationPlaceId},
        travelMode: this.travelMode
    }, function(response, status) {
        if (status === 'OK') {
            me.directionsDisplay.setDirections(response);
        } else {
            // window.alert('Directions request failed due to ' + status);
        }
    });
};
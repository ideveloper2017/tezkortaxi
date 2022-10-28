@extends('admin.layout.base')
@section('title', 'Add Zone ')  
<link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500">
</head>
<style>
    html,
    body {
      padding: 0;
      margin: 0;
      width: 100%;
      height: 100%;
    }
    #map {
      padding: 0;
      margin: 0;
      width: 100%;
      height: 80%;
    }
    #submit_zone_btn {
        background-color: #b01d23;
        color: #fff !important;
        font-weight: bold;      
    }
    
    .intr {
        color: red;
        font-style: italic;
    }
    #panel {
      width: 200px;
      font-family: Arial, sans-serif;
      font-size: 13px;
      float: right;
      margin: 10px;
    }

    #color-palette {
      clear: both;
      display: none;
    }

    .color-button {
      width: 14px;
      height: 14px;
      font-size: 0;
      margin: 2px;
      float: left;
      cursor: pointer;
    }

    #delete-button {
      margin-top: 5px;
      display: none;
    }
.gmnoprint > div:nth-child(4),.gmnoprint > div:nth-child(5){
    display:none!important;
}

</style>
<body>
    
    <div class="content-area py-1" style="">
    <div class="container-fluid">
@section('content')

<div class='box' style="background: #fff;">
<h5 style='padding: 10px;margin-bottom: -15px;'><span class="s-icon"><i class="ti-zoom-in"></i></span>&nbsp; Add Location</h5><hr>
<input id="pac-input" class="form-control" type="text" placeholder="Enter Location" style="top:5px!important;width:50%;">

<div id="panel">
  <div id="color-palette"></div>
  <div>
    <button id="delete-button">Delete Selected Shape</button>
  </div>
</div>
    <div id="map"></div>
    
   </div>   </div> 
</div>
@include('admin.layout.partials.zone_form')

@endsection

@section('scripts')
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&libraries=geometry,places,drawing&callback=initialize&ext=.js"></script>
<script type="text/javascript">

    var zoneModel = $('#zoneModel');
    var zoneForm =  $('#zoneForm');
    var zoneClose = zoneForm.find('#zone_close');
    var zoneSubmitBtn = zoneForm.find('#submit_zone_btn');

    zoneModel.modal({backdrop: 'static', keyboard: false}); 
    zoneModel.modal('hide');
    
    var map;
    var geocoder;
    var drawingManager;
    var selectedShape;
    var colors = ['#1E90FF', '#FF1493', '#32CD32', '#FF8C00', '#4B0082'];
    var selectedColor;
    var colorButtons = {};
    var polygons = [];
    var polygonArray = [];
    var googleMarkers = [];
    var all_zones = []; 
    var edit_polygon;  

    var polygonOptions = { fillColor: '#BCDCF9', fillOpacity: 0.0, strokeWeight: 2, strokeColor: '#57ACF9', zIndex: 1 };
    var markerOptions  = { icon: 'images/car-icon.png' };
    var drawingControl =  true;

    <?php if( isset( $zone ) ) { ?>
        edit_zone =  {!! json_encode ( $zone ) !!};
        if( edit_zone ) {
            
            edit_polygon = edit_zone.coordinate;
        }
    <?php  }  ?>
    
    <?php if( count($all_zones) ) { ?>
        var all_zones = <?php  echo json_encode( $all_zones ); ?>;
    <?php } ?>
        
        
        function initMap() {
            infoWindow = new google.maps.InfoWindow;
            
            map = new google.maps.Map( document.getElementById("map"), {
                center: new google.maps.LatLng(30.033333,31.233334),
                zoom: 8,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });
            
            initdrawingManager();
            
            google.maps.event.addListener(drawingManager, 'polygoncomplete', function (polygon) {
                for (var i = 0; i < polygon.getPath().getLength(); i++) {
                    var path =  polygon.getPath().getAt(i).lat()+','+polygon.getPath().getAt(i).lng();
                    polygonArray.push( path );  
                    
                }
                
                $('#zoneModel').modal('show');
                
                console.log( polygonArray );
            });
            

            
            
            google.maps.Map.prototype.clearOverlays = function() {
                for (var i = 0; i < googleMarkers.length; i++ ) {
                    googleMarkers[i].setMap(null);
                }
                googleMarkers.length = 0;
            }
            
            
            if( edit_polygon ) {
                
                drawingManager.setOptions({drawingMode:null,drawingControl:false});
                
                var edit_poly_options = { fillColor: '#BCDCF9', fillOpacity: 0.0, strokeWeight: 2, strokeColor: '#57ACF9', zIndex: 1 };
                edit_poly_options.clickable = true;
                edit_poly_options.editable = true;
                edit_poly_options.paths = makeZoneArray( edit_polygon );
                var shape = new google.maps.Polygon( edit_poly_options ); 
                shape.setMap(map);
                
                var place_polygon_path = shape.getPath(); 
                google.maps.event.addListener(place_polygon_path, 'set_at', function() {
                    polygonArray = [];
                    for (var i = 0; i < shape.getPath().getLength(); i++) {
                        var path =  shape.getPath().getAt(i).lat()+','+shape.getPath().getAt(i).lng();
                        alert(path);
                        polygonArray.push( path );                  
                    }
                });
            
                google.maps.event.addListener(shape, 'click', function() {  
                    $('#zoneModel').modal('show');
                }); 
            }
            
            showAllPolygons();
        }

        function initdrawingManager() {
            drawingManager = new google.maps.drawing.DrawingManager({
                drawingMode: google.maps.drawing.OverlayType.POLYGON,
                drawingControlOptions: {
                    position: google.maps.ControlPosition.TOP_CENTER,
                    drawingModes: [ google.maps.drawing.OverlayType.POLYGON ]
                },
                
                drawingControl: drawingControl,
                polygonOptions: polygonOptions
            });         
            drawingManager.setMap(map);         
        }   

        function showAllPolygons() {
            var options =  polygonOptions;
            options.paths = null;
            if( all_zones.length ) {
                all_zones.forEach(function(zone, ind) {
                    options.paths = makeZoneArray(zone.latlng); 
                    if( edit_zone ) {
                        if( edit_zone.id != zone.id ) {
                            var shape = new google.maps.Polygon(options);
                            shape.setMap(map);
                        }
                    } else {
                        var shape = new google.maps.Polygon(options);
                        shape.setMap(map);
                    }
                });             
            }   
        }

        function clearSelection() {
            if (selectedShape) {
                if (selectedShape.type !== 'marker') {
                    selectedShape.setEditable(false);
                }   

                selectedShape = null;
            }
        }

        function setSelection(shape) {
          if (shape.type !== 'marker') {
            clearSelection();
            shape.setEditable(true);
            selectColor(shape.get('fillColor') || shape.get('strokeColor'));
          }

          selectedShape = shape;
        }

        function deleteSelectedShape() {
          if (selectedShape) {
            selectedShape.setMap(null);
          }
        }

    function selectColor(color) {
      selectedColor = color;
      for (var i = 0; i < colors.length; ++i) {
        var currColor = colors[i];
        colorButtons[currColor].style.border = currColor == color ? '2px solid #789' : '2px solid #fff';
      }

        // Retrieves the current options from the drawing manager and replaces the
        // stroke or fill color as appropriate.
        var polylineOptions = drawingManager.get('polylineOptions');
        polylineOptions.strokeColor = color;
        drawingManager.set('polylineOptions', polylineOptions);


        var polygonOptions = drawingManager.get('polygonOptions');
        polygonOptions.fillColor = color;
        drawingManager.set('polygonOptions', polygonOptions);
}

function setSelectedShapeColor(color) {
  if (selectedShape) {
    if (selectedShape.type == google.maps.drawing.OverlayType.POLYLINE) {
      selectedShape.set('strokeColor', color);
    } else {
      selectedShape.set('fillColor', color);
    }
  }
}

function makeColorButton(color) {
  var button = document.createElement('span');
  button.className = 'color-button';
  button.style.backgroundColor = color;
  google.maps.event.addDomListener(button, 'click', function() {
    selectColor(color);
    setSelectedShapeColor(color);
  });

  return button;
}

function buildColorPalette() {
  var colorPalette = document.getElementById('color-palette');
  for (var i = 0; i < colors.length; ++i) {
    var currColor = colors[i];
    var colorButton = makeColorButton(currColor);
    colorPalette.appendChild(colorButton);
    colorButtons[currColor] = colorButton;
  }
  selectColor(colors[0]);
}

function initialize() {
  var map = new google.maps.Map(document.getElementById('map'), {
    zoom: 10,
    center: new google.maps.LatLng(30.033333,31.233334),
      mapTypeId: google.maps.MapTypeId.ROADMAP,
    disableDefaultUI: true,
    zoomControl: true
  });

  var polyOptions = {
    strokeWeight: 0,
    fillOpacity: 0.45,
    editable: true,
    draggable: true
  };
  // Creates a drawing manager attached to the map that allows the user to draw
  // markers, lines, and shapes.
  drawingManager = new google.maps.drawing.DrawingManager({
    drawingMode: google.maps.drawing.OverlayType.POLYGON,
    markerOptions: {
      draggable: true
    },
    polylineOptions: {
      editable: true,
      draggable: true
    },
    rectangleOptions: polyOptions,
    circleOptions: polyOptions,
    polygonOptions: polyOptions,
    map: map
  });
   google.maps.Map.prototype.clearOverlays = function() {
                for (var i = 0; i < googleMarkers.length; i++ ) {
                    googleMarkers[i].setMap(null);
                }
                googleMarkers.length = 0;
            }

  google.maps.event.addListener(drawingManager, 'overlaycomplete', function(e) {
    var newShape = e.overlay;

    newShape.type = e.type;

    if (e.type !== google.maps.drawing.OverlayType.MARKER) {
      // Switch back to non-drawing mode after drawing a shape.
      drawingManager.setDrawingMode(null);
      
       console.log('polygon path array', e.overlay.getPath().getArray());
       $.each(e.overlay.getPath().getArray(), function(key, latlng){
        var lat = latlng.lat();
        var lon = latlng.lng();
        console.log(lat, lon); 
        var path =  lat+','+lon;
        polygonArray.push( path ); 
        //str_input += lat +' '+ lon +',';
      });


        $('#zoneModel').modal('show');

      // Add an event listener that selects the newly-drawn shape when the user
      // mouses down on it.
      google.maps.event.addListener(newShape, 'click', function(e) {
        $('#zoneModel').modal('show');
        if (e.vertex !== undefined) {
          if (newShape.type === google.maps.drawing.OverlayType.POLYGON) {
            var path = newShape.getPaths().getAt(e.path);
           
            path.removeAt(e.vertex);
            if (path.length < 3) {
              newShape.setMap(null);
            }
          }
          if (newShape.type === google.maps.drawing.OverlayType.POLYLINE) {
            var path = newShape.getPath();
           
            path.removeAt(e.vertex);
            if (path.length < 2) {
              newShape.setMap(null);
            }
          }
        }
        setSelection(newShape);
      });
      setSelection(newShape);
    } else {
      google.maps.event.addListener(newShape, 'click', function(e) {
        setSelection(newShape);
      });
      setSelection(newShape);
    }
  });

    google.maps.Map.prototype.clearOverlays = function() {
        for (var i = 0; i < googleMarkers.length; i++ ) {
            googleMarkers[i].setMap(null);
        }
        googleMarkers.length = 0;
    }

  // Clear the current selection when the drawing mode is changed, or when the
  // map is clicked.
  google.maps.event.addListener(drawingManager, 'drawingmode_changed', clearSelection);
  google.maps.event.addListener(map, 'click', clearSelection);
  google.maps.event.addDomListener(document.getElementById('delete-button'), 'click', deleteSelectedShape);

  buildColorPalette();

  // SearchBox code
  // Create the search box and link it to the UI element.
  var input = document.getElementById('pac-input');
  var searchBox = new google.maps.places.SearchBox(input);
  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

  // Bias the SearchBox results towards current map's viewport.
  map.addListener('bounds_changed', function() {
    searchBox.setBounds(map.getBounds());
  });

  var markers = [];
  // Listen for the event fired when the user selects a prediction and retrieve
  // more details for that place.
  searchBox.addListener('places_changed', function() {
    var places = searchBox.getPlaces();

    if (places.length == 0) {
      return;
    }

    // Clear out the old markers.
    markers.forEach(function(marker) {
      marker.setMap(null);
    });
    markers = [];

    // For each place, get the icon, name and location.
    var bounds = new google.maps.LatLngBounds();
    places.forEach(function(place) {
      if (!place.geometry) {
        console.log("Returned place contains no geometry");
        return;
      }
      var icon = {
        url: place.icon,
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25)
      };

      // Create a marker for each place.
      markers.push(new google.maps.Marker({
        map: map,
        icon: icon,
        title: place.name,
        position: place.geometry.location
      }));

      if (place.geometry.viewport) {
        // Only geocodes have viewport.
        bounds.union(place.geometry.viewport);
      } else {
        bounds.extend(place.geometry.location);
      }
    });
    map.fitBounds(bounds);
  });

            
            if( edit_polygon ) {
                
                drawingManager.setOptions({drawingMode:null,drawingControl:false});
                
                var edit_poly_options = { fillColor: '#BCDCF9', fillOpacity: 0.0, strokeWeight: 2, strokeColor: '#57ACF9', zIndex: 1 };
                edit_poly_options.clickable = true;
                edit_poly_options.editable = true;
                edit_poly_options.paths = makeZoneArray( edit_polygon );
                var shape = new google.maps.Polygon( edit_poly_options ); 
                shape.setMap(map);
                
                var place_polygon_path = shape.getPath(); 
                google.maps.event.addListener(place_polygon_path, 'set_at', function() {
                    polygonArray = [];
                    for (var i = 0; i < shape.getPath().getLength(); i++) {
                        var path =  shape.getPath().getAt(i).lat()+','+shape.getPath().getAt(i).lng();
                        polygonArray.push( path );                  
                    }
                });
            
                google.maps.event.addListener(shape, 'click', function() {  
                    $('#zoneModel').modal('show');
                }); 
            }
            
            showAllPolygons();

}
    
    $("body").on("click","#submit_zone_btn",function(){
      checkArgForAddZone(polygonArray);
    })

    function initdrawingManager() {
            drawingManager = new google.maps.drawing.DrawingManager({
                drawingMode: google.maps.drawing.OverlayType.POLYGON,
                drawingControlOptions: {
                    position: google.maps.ControlPosition.TOP_CENTER,
                    drawingModes: [ google.maps.drawing.OverlayType.POLYGON ]
                },
                
                drawingControl: drawingControl,
                polygonOptions: polygonOptions
            });         
            drawingManager.setMap(map);         
        }   
        
        function showAllPolygons() {
            var options =  polygonOptions;
            options.paths = null;
            if( all_zones.length ) {
                all_zones.forEach(function(zone, ind) {
                    options.paths = makeZoneArray(zone.latlng); 
                    if( edit_zone ) {
                        if( edit_zone.id != zone.id ) {
                            var shape = new google.maps.Polygon(options);
                            shape.setMap(map);
                        }
                    } else {
                        var shape = new google.maps.Polygon(options);
                        shape.setMap(map);
                    }
                });             
            }   
        }


    function checkArgForAddZone(path_coordinate ) {
        
        var name    =   $('#zoneForm').find('input[name=zone_name]').val();
        var country_name    =   $('#zoneForm').find('#country_name').val();
        var state_name    =   $('#zoneForm').find('#state_name').val();
        var city_name    =   $('#zoneForm').find('#city_name').val();
        var currency_name    =   $('#zoneForm').find('input[name=currency_name]').val();
        var status_name    =  $("input[name='status_name']:checked").val();
        var zone_id =   $('#zoneForm').find('#zone_id').val();
        
        zone_id = ( zone_id.length ) ? zone_id : 0;

        if(!name.length) {
            alert('Please enter a zone name!');
            $('#zoneModel').modal('show');
            return false;
        }
        
        if( ! path_coordinate.length ) {
            alert('Please draw a zone!');
            return false;
        }
      
        addZone(zone_id, name,country_name,state_name,city_name,status_name,currency_name, path_coordinate);
        
    }
        
    function addZone(zone_id, zone_name,country_name,state_name,city_name,status_name,currency_name, path_coordinate) {
        
        $.ajax({
            headers     :   {'X-CSRF-TOKEN': window.Laravel.csrfToken },
            url         :   '{{url("/admin/zone")}}',
            dataType    :   'JSON',
            type        :   'POST',
            data        :   { id: zone_id, name:zone_name, country:country_name, state:state_name, city:city_name,status:status_name,currency:currency_name, coordinate: path_coordinate},
            success     :   function(json) {
                if( json.status ) {
                    window.location.replace("{{route('admin.zone.index')}}"); 
                }
            }
        });
    } 
   
    function makeZoneArray(polygonArg) {
        var zoneArray = [];
        if( polygonArg ) {
            polygonArg.forEach(function(item, ind ) {       
                zoneArray[ind] = { lat:parseFloat(item['lat']), lng: parseFloat(item['lng']) };
            });
        }
        return zoneArray; 
    } 

</script>
@endsection
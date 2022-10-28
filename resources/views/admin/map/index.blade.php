@extends('admin.layout.base')
@section('title', 'Live Tracking ')
@section('content')
<div class="content-area py-1">
<div class="container-fluid">
   <div class="box box-block bg-white">
      <h5 class="mb-1"><i class="ti-map-alt"></i>&nbsp;Live Tracking</h5>
      <hr>
      <div class="row">
         <div class="col-md-12">
         <div class="col-md-2">
               <label class="custom-control custom-checkbox">
               <input type="radio" value="all" checked="true" class="custom-control-input"  name="type">
               <span class="custom-control-indicator"></span>
               <span class="custom-control-description">All</span>
               </label>
            </div>
            <div class="col-md-2">
               <label class="custom-control custom-checkbox">
               <input type="radio" value="user" class="custom-control-input" name="type">
               <span class="custom-control-indicator"></span>
               <span class="custom-control-description">User</span>
               </label>
            </div>
            <div class="col-md-2">
               <label class="custom-control custom-checkbox">
               <input type="radio" value="driver" class="custom-control-input" name="type">
               <span class="custom-control-indicator"></span>
               <span class="custom-control-description">Driver</span>
               </label>
            </div>
            <div class="col-md-2">
               <label class="custom-control custom-checkbox">
               <input type="radio" value="ongoing" class="custom-control-input" name="type">
               <span class="custom-control-indicator"></span>
               <span class="custom-control-description">Ongoing Trip</span>
               </label>
            </div>
            <div class="col-md-2">
               <label class="custom-control custom-checkbox">
               <input type="radio" value="complete" class="custom-control-input" name="type">
               <span class="custom-control-indicator"></span>
               <span class="custom-control-description">Completed Trip</span>
               </label>
            </div>
       
         </div>
         <!---Check Box--->
         <div class="row">
            <div class="col-xs-12">
               <br/>
               <div id="map"></div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
@section('styles')
<style type="text/css">
   #map {
   height: 100%;
   min-height: 500px;
   }
   #legend {
   font-family: Arial, sans-serif;
   background: rgba(255,255,255,0.8);
   padding: 10px;
   margin: 10px;
   border: 2px solid #f3f3f3;
   }
   #legend h3 {
   margin-top: 0;
   font-size: 16px;
   font-weight: bold;
   text-align: center;
   }
   #legend img {
   vertical-align: middle;
   margin-bottom: 5px;
   }
</style>
@endsection
@section('scripts')
@if (Setting::get('map_type', 1) == 2)
        <script src="https://api-maps.yandex.ru/2.1/?apikey={{ env("YANDEX_MAP_KEY") }}&lang=en_US" type="text/javascript"></script>
    <script type="text/javascript">
        var map;
        var users;
        var providers;
        var ajaxMarkers = [];
        var Markers = [];
        var mapIcons = {
            user: '{{ asset("asset/img/marker-user.png") }}',
            complete: '{{ asset("asset/img/marker-home.png") }}',
            riding: '{{ asset("asset/img/car-active.png") }}',
            active: '{{ asset("asset/img/marker-car.png") }}',
        }
        // The ymaps.ready() function will be called when
        // all the API components are loaded and the DOM tree is generated.
        ymaps.ready(init);
        function init(){
            // Creating the map.
            var map = new ymaps.Map("map", {
                zoom: 12,
                minZoom: 1,
                center: [ 18.4562501, -77.9766789]
            });
            $(":radio").click(()=>{
                getData(map)
            })
            getData(map)
            setInterval(() => {
                getData(map);
            }, 15000);
        }
        function getData(map){
            var type = $(":checked").val();
            $.get('{{url("admin/get-locations")}}/'+type).then(res=>{
                setMarkers(map, res)
            })
        }

        function setMarkers(map, beaches) {

            var shape = {
                coords: [1, 1, 1, 20, 18, 20, 18, 1],
                type: 'poly'
            };
            for (var i = 0; i < beaches.length; i++) {
                var beach = beaches[i];
                MyIconContentLayout = ymaps.templateLayoutFactory.createClass(
                    '<div style="color: #FFFFFF; font-weight: bold;">$[properties.iconContent]</div>'
                );
                var   marker = new ymaps.Placemark([beach.latitude, beach.longitude], {
                    hintContent: '',
                    balloonContent: '',
                    iconContent: ''
                }, {
                    iconLayout: 'default#imageWithContent',
                    iconImageHref: mapIcons[beach.icon],
                    iconImageSize: [23, 30],
                    iconImageOffset: [-24, -24],
                    iconContentOffset: [15, 15],
                    iconContentLayout: MyIconContentLayout
                });
                Markers.push(marker);
                map.geoObjects.add(marker);
            }
        }
    </script>
@else
    <script>
        var map;
        var users;
        var providers;
        var ajaxMarkers = [];
        var Markers = [];
        var mapIcons = {
            user: '{{ asset("asset/img/marker-user.png") }}',
            complete: '{{ asset("asset/img/marker-home.png") }}',
            riding: '{{ asset("asset/img/car-active.png") }}',
            active: '{{ asset("asset/img/marker-car.png") }}',
            //unactivated: '{{ asset("asset/img/marker-plus.png") }}'
        }

        function initMap() {
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                minZoom: 1,
                center: {lat: 18.4562501, lng: -77.9766789}
                //center: {lat: 28.57427, lng: 77.3558}
            });
            $(":radio").click(()=>{
                getData(map)
            })
            getData(map)
            setInterval(() => {
                getData(map);
            }, 15000);
        }

 

        function getData(map){
            var type = $(":checked").val();
            $.get('{{url("admin/get-locations")}}/'+type).then(res=>{
                clearMarkers();
                setMarkers(map, res)
            })
        }

        function setMarkers(map, beaches) {


            var shape = {
                coords: [1, 1, 1, 20, 18, 20, 18, 1],
                type: 'poly'
            };
            var infowindow = new google.maps.InfoWindow({
                content: 'contentString'
            });
            for (var i = 0; i < beaches.length; i++) {
                var beach = beaches[i];
                var marker = new google.maps.Marker({
                    position: {lat: beach.latitude, lng: beach.longitude},
                    map: map,
                    icon: {url: mapIcons[beach.icon]},
                    shape: shape,
                    // id:'id'+i,
                    // title: beach.icon,
                    zIndex: 5
                });

                Markers.push(marker);
                var content = 'Content not found.';

                var infowindow = new google.maps.InfoWindow()

                google.maps.event.addListener(marker,'click', (function(marker,content,infowindow,beach){
                    return function() {
                        var url1 = '{{url("admin/get-details")}}/'+beach.icon+"/"+beach.id;
                        $.get(url1).then(res=>{
                            if(res) content = res
                            infowindow.setContent(content);
                            infowindow.open(map,marker);
                        })

                    };
                })(marker,content,infowindow,beach));
            }
        }
        function clearMarkers() {
            for (var i = 0; i < Markers.length; i++) {
                Markers[i].setMap(null);
            }
            Markers = [];
        }
    </script>
    <script src="//maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&libraries=places&callback=initMap" async defer></script>
@endif
@endsection
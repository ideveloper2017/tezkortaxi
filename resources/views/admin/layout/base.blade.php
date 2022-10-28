<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Title -->
    <title>{{ Setting::get('site_title') }}</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset(Setting::get('site_icon')) }}">

    <!-- Vendor CSS -->
    <script type="text/javascript" src="{{asset('asset/admin/vendor/jquery/jquery-1.12.3.min.js')}}"></script>
    <link rel="stylesheet" href="{{asset('asset/admin/vendor/bootstrap4/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('asset/admin/vendor/themify-icons/themify-icons.css')}}">
    <link rel="stylesheet" href="{{asset('asset/admin/vendor/font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('asset/admin/vendor/animate.css/animate.min.css')}}">
    <link rel="stylesheet" href="{{asset('asset/admin/vendor/jscrollpane/jquery.jscrollpane.css')}}">
    <link rel="stylesheet" href="{{asset('asset/admin/vendor/waves/waves.min.css')}}">
    <link rel="stylesheet" href="{{asset('asset/admin/vendor/switchery/dist/switchery.min.css')}}">
    <link rel="stylesheet" href="{{asset('asset/admin/vendor/DataTables/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('asset/admin/vendor/DataTables/Responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('asset/admin/vendor/DataTables/Buttons/css/buttons.dataTables.min.css')}}">
    <link rel="stylesheet" href="{{asset('asset/admin/vendor/DataTables/Buttons/css/buttons.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css">
    <link rel="stylesheet" href="{{ asset('asset/admin/vendor/dropify/dist/css/dropify.min.css') }}">
    <link rel="stylesheet" href="{{asset('asset/front/css/jasny-bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset('asset/admin/assets/css/core.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.10/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.0/axios.js" integrity="sha256-XmdRbTre/3RulhYk/cOBUMpYlaAp2Rpo/s556u0OIKk=" crossorigin="anonymous"></script>
    <link href="{{ asset('asset/front_dashboard/css/hamburgers.css') }}" rel="stylesheet">



    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
        
        
    </script>
    <style type="text/css">
        .rating-outer span,
        .rating-symbol-background {
            color: #ffe000!important;
        }
        .rating-outer span,
        .rating-symbol-foreground {
            color: #ffe000!important;
        }
    </style>
    @yield('styles')
</head>
<body class="fixed-sidebar fixed-header content-appear skin-1">

    <div class="wrapper">
        <div class="preloader"></div>
        <div class="site-overlay"></div>

        @include('admin.layout/partials.nav')

        @include('admin.layout/partials.header')

        <div class="site-content no-margin">

            @include('common.notify')

            @yield('content')

            @include('admin.layout/partials.footer')

        </div>
    </div>

    <!-- Vendor JS -->
    <script type="text/javascript" src="{{asset('asset/admin/vendor/jquery/jquery-1.12.3.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/tether/js/tether.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/bootstrap4/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/detectmobilebrowser/detectmobilebrowser.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/jscrollpane/jquery.mousewheel.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/jscrollpane/mwheelIntent.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/jscrollpane/jquery.jscrollpane.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/jquery-fullscreen-plugin/jquery.fullscreen')}}-min.js"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/waves/waves.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/DataTables/js/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/DataTables/js/dataTables.bootstrap4.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/DataTables/Responsive/js/dataTables.responsi')}}ve.min.js"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/DataTables/Responsive/js/responsive.bootstra')}}p4.min.js"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/DataTables/Buttons/js/dataTables.buttons')}}.min.js"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/DataTables/Buttons/js/buttons.bootstrap4')}}.min.js"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/DataTables/JSZip/jszip.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/DataTables/pdfmake/build/pdfmake.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/DataTables/pdfmake/build/vfs_fonts.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/DataTables/Buttons/js/buttons.html5.min.js')}}"></script>

    <script type="text/javascript" src="{{asset('asset/admin/vendor/DataTables/Buttons/js/buttons.print.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/DataTables/Buttons/js/buttons.colVis.min.js')}}"></script>

    <script type="text/javascript" src="{{asset('asset/admin/vendor/switchery/dist/switchery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/dropify/dist/js/dropify.min.js')}}"></script>

    <!-- pintaxi JS -->
    <script type="text/javascript" src="{{asset('asset/admin/assets/js/app.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/assets/js/demo.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/assets/js/tables-datatable.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/assets/js/forms-upload.js')}}"></script>


    @yield('scripts')

    <script type="text/javascript" src="{{asset('asset/front/js/rating.js')}}"></script>    
    <script type="text/javascript">
        $('.rating').rating();
        if(jQuery.browser.mobile == false) {
        function initScroll(){
            $('.custom-scroll').jScrollPane({
                autoReinitialise: true,
                autoReinitialiseDelay: 100
            });
        }

        initScroll();

        $(window).resize(function() {
            initScroll();
        });
    }
      $(".hamburger").click(function(){   
        $(".site-sidebar").toggleClass("open"); 
        $('.hamburger').toggleClass("is-active");
        $(".site-content").toggleClass("no-margin");
      }); 

    /* Scroll - if mobile */
    if(jQuery.browser.mobile == true) {
        $('.custom-scroll').css('overflow-y','scroll');
    }
	
    $(document).ready(function(){
      var select = $('.validation');
      $("#peak_hour").change(function(){
         if($(this).val() === 'YES'){
         select.attr("required","required");
        }else{
          select.removeAttr('required');
        }
      });
    });
    $(document).ready(function(){
      var select = $('.validation1');
      $("#night_hour").change(function(){
         if($(this).val() === 'YES'){
         select.attr("required","required");
        }else{
          select.removeAttr('required');
        }
      });
    });
	
    $(document).ready(function() {
        $("body").removeClass("compact-sidebar");
          $("body").on("change","#country_name",function(){
            if($(this).val()>0){
                $(this).prop('name','country_name');
                $("#country_name_input").prop('required',false).removeProp('name');
            }else{
                $(this).removeProp('name');
                $("#country_name_input").prop({'required':true,'name':'country_name'}); 
            }
        });

        $("body").on("change","#state_name",function(){
            if($(this).val()>0){
                $(this).prop('name','state_name');
                $("#state_name_input").prop('required',false).removeProp('name');
            }else{
                $(this).removeProp('name');
                $("#state_name_input").prop({'required':true,'name':'state_name'}); 
            }
            
        });
        
    });
     $("#peak_data").click(function(){  
            var i = $('.peak_day').length;
            
            var day_p = $("#peak_day option:selected" ).text();
            var start_time_p = $("#peak_start_time option:selected" ).text();
            var end_time_p = $("#peak_end_time option:selected" ).text();
            var peak_fare = $("#peak_fare").val();
           
            
          // alert(day_p+' '+start_time_p+' '+end_time_p+' '+peak_fare); 
        //   console.log($("#reg-form").serialize()); 
      var data = {'_token': "{{ csrf_token() }}",'day':day_p,'start_time':start_time_p,'end_time':end_time_p,'fare_in_percentage':peak_fare,'peak_night_type':'PEAK'};
      $.post("{{route('admin.peakNight')}}", data, function(res) {
        // console.log(res);
        
      // var resdata = $.parseJSON(res);
      // console.log(res);
      // console.log(res.data);
      if(res.status == 1){
          alert('You cant create duplicate day');
      }
      // $('#peakAdded').empty();
                // alert(i);
         if(i >= 6){ 
        $("#peakAdded").append('<div class="form-group row"><p style="color:red;text-align: center">You can add maximum 7 days</p></div>');
          }else{
      // $("#peakAdded").append('<div class="form-group row"><label for="store_link_ios" class="col-xs-2 col-form-label"></label><div class="col-xs-2"><select  class="form-control peak_day" name="peak_day[]" id="peak_day"><option>Day</option>@foreach(explode(',', env('DAY')) as $d=>$dv)<option value="{{$dv}}">{{$dv}} </option>@endforeach</select></div><div class="col-xs-2"><select  class="form-control" name="peak_start_time[]" id="peak_start_time"><option>Start Time</option>@foreach(explode(',', env('HOUR')) as $d=>$dv)<option value="{{$dv}}">{{$dv}} </option>                        @endforeach</select></div><div class="col-xs-2"><select  class="form-control" name="peak_end_time[]" id="peak_end_time"><option>End Time</option>@foreach(explode(',', env('HOUR')) as $d=>$dv)<option value="{{$dv}}">{{$dv}} </option>@endforeach</select></div><div class="col-xs-2"><input class="form-control" type="text" value="" name="peak_fare[]" id="peak_fare" placeholder="Peak Fare(%)"></div><div class="col-xs-2"><button type="button" class="btn btn-primary" id="peak_data" onclick="removeRow(this)">-</button></div></div>');
      $("#peakAdded").append('<div class="form-group row"><label for="store_link_ios" class="col-xs-2 col-form-label"></label><div class="col-xs-2"><select  class="form-control peak_day" name="peak_day[]" id="peak_day"><option>Day</option>@foreach(explode(',', env('DAY')) as $d=>$dv)<option value="{{$dv}}">{{$dv}} </option>@endforeach</select></div><div class="col-xs-2"><input type="time" class="form-control validation" name="peak_start_time[]" id="peak_start_time"></div><div class="col-xs-2"><input type="time" class="form-control validation" name="peak_end_time[]" id="peak_end_time"></div><div class="col-xs-2"><input class="form-control validation" type="text" value="" name="peak_fare[]" id="peak_fare" placeholder="Peak Fare(%)"></div><div class="col-xs-2"><button type="button" class="btn btn-primary" id="peak_data" onclick="removeRow(this)">-</button></div></div>');
        }
     
          });
        });
     function removeRow (input) {
      input.parentNode.parentNode.remove()
      }

        $("#night_data").click(function(){  
            var start_time_n = $("#night_start_time option:selected" ).text();
            var end_time_n = $("#night_end_time option:selected" ).text();
            var night_fare = $("#night_fare").val();
           
        // alert(day_p+' '+start_time_p+' '+end_time_p+' '+peak_fare); 
        //   console.log($("#reg-form").serialize()); 
     var data = {'_token': "{{ csrf_token() }}",'start_time':start_time_n,'end_time':end_time_n,'fare_in_percentage':night_fare,'peak_night_type':'NIGHT'};
    $.post("addpeakAnight", data, function(res) {
        // console.log(res);
        
      // var resdata = $.parseJSON(res);
// console.log(res.data);

$('#nightAdded').empty();

    $.each(res.data,function(key,val)
  {
      $("#nightAdded").append(" <div class='form-group row'><label  class='col-xs-2 col-form-label'></label><div class='col-xs-2'><input class='form-control' type='text' value="+val.start_time+"></div><div class='col-xs-2'><input class='form-control' type='text' value="+val.end_time+"></div><div class='col-xs-2'><input class='form-control' type='text' value="+val.fare_in_percentage+"></div></div>" );
      
          // console.log(key + " : " + val.day);     
  
  
});
    });
});
   
  $("select#peak_hour").change(function(){

            var selectedd = $(this).children("option:selected").val();
            
            if(selectedd === 'NO'){
                
                $(".peakHide").hide();
            }
            
            if(selectedd === 'YES'){
                
                $(".peakHide").show();
            }

        });
        
        // alert($( "#peak_hour option:selected" ).text());
        
        $("select#night_hour").change(function(){

            var selectedd = $(this).children("option:selected").val();
          
            if(selectedd === 'NO'){
               
                $(".nightHide").hide();
            }
            
            if(selectedd === 'YES'){
             
                $(".nightHide").show();
            }
            
           

        });
 
    var wId = null;

       $('#accountApproved').click(function(){
        
        var id = $(this).attr('data');


        $.ajax({

            url: '{{url("admin/approved_account?id=")}}'+id,
            beforeSend: function(xhr){xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');},
             success: function(result){

            
            location.reload();
            $("#msg").html('you have successfully approved');
            console.log(result);
            

        }});

    });
    
     var app = new Vue({

  el: '#zoneModel',

  data: {

    country: 0,

    countries: '',

    state: 0,

    states: '',

    city: 0,

    cities: ''

  },

  methods: {

    getCountries: function(){

      axios.get('{{route("admin.getcountry")}}', {

        params: {

          request: 'country'

        }

      })

      .then(function (response) {
        
         app.countries = response.data;

         app.states = '';

         app.cities = '';

         app.state = 0;

         app.city = 0;

      });

 

    },

    getStates: function(){

      axios.get('{{route("admin.getstate")}}', {

         params: {

           request: 'state',

           country_id: this.country

         }

      })

      .then(function (response) {

         app.states = response.data;
         app.state = 0;
         app.cities = '';
         app.city = 0;

      }); 

    }, 

    getCities: function(){
      axios.get('{{route("admin.getcity")}}', { 

        params: {

          request: 'city',
          state_id: this.state

        }

      }) 

      .then(function (response) {

        app.cities = response.data;

        app.city = 0;

      }); 

    }

  },

  created: function(){

    this.getCountries();

  }

});

var promocode = new Vue({
    el:'#promocode',
    data:{
       codes:'',
       code:0,
       codesuser:'',
    },
    methods:{
        getPromocodes:function(){
           axios.get('{{route("admin.promocodes")}}',{
               params:{
                  promocode_id : this.promocode,
               }
           }).then(function(response){
               promocode.codes = response.data;
               console.log(response.data);
           }) ;
        },
        getPromocodesUser:function(){
           axios.get('{{route("admin.promocodeusage")}}',{
               params:{
                  promocode_id : this.code,
               }
           }).then(function(response){
               promocode.codesuser = response.data;
               console.log(response.data);
           }) ;
        },
        formattedDate:function(d){
    let arr = d.split(/[- :]/);
    let date = new Date(Date.UTC(arr[0], arr[1]-1, arr[2], arr[3], arr[4], arr[5]));
    return date.getDate() + " - " + (date.getMonth() + 1) + " - " + date.getFullYear() + " " + date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds()
}
    },
    created: function(){
        this.getPromocodes();
    }
});
    </script>
</body>
</html>
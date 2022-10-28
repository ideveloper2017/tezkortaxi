<header>
    <?php
    // echo "<pre>";
    // print_r($_SERVER['REQUEST_URI']);
    // echo $fully . "Palak";
    // echo "</pre>";

    $fully_sum = 0; ?>
      @foreach($fully as $each)
        
        @if($each->payment != "")
            <?php $each_sum = 0;
            $each_sum = $each->payment->tax + $each->payment->fixed + $each->payment->distance + $each->payment->commision;
            $fully_sum += $each_sum;
            ?>
        @endif
              
      @endforeach
 <?php //echo currency($fully_sum) . "Palak2"; ?>
<div class="site-header">
    <nav class="navbar navbar-fixed-top">
      <div class="">
        <div class="col-md-12">
            <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>             
              
              <a class="" href="{{url('/provider/login')}}"><img src="/img/instacab_logo.png" style="height:50px; "></a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right" style="display: inline-grid;">              
                  <li class="menu-drop">
                        
                      <div class="dropdown">
                         <form id="form_online" method="POST" action="{{url('/provider/profile/available')}}">
                             <label class="btn-primary" style="background: transparent;color: black;"> Total Revenue</label>
                             <label class="btn-primary" style="background: transparent;color: black;" id="set_fully_sum"> 00.00</label>

                              <input type="text" value="active" name="service_status" id="active_offline_hdn" readonly />
                             <input checked  name="CARD" id="stripe_check" type="checkbox" class="js-switch" data-color="#43b968">
                         </form>
                        
                    
                         
                       
                        </div>
                  </li>
                </ul>
            </div>
        </div>
      </div>
    </nav>
  </div>  
</header>

@section('scripts')
<script type="text/javascript">
  //alert("hello");
  document.getElementById('set_fully_sum').textContent = "{{currency($fully_sum)}}";
</script>
@endsection

@extends('website.app')

@section('content')
	<?php
$locale = session()->get('locale');
   if($locale){

      $locale;

   }else{

      $locale = 'en';

   }
   $question = $locale.'_question';
   $des = $locale.'_description';
 ?>
	<div class="container">

		<div class="row">

			<div class="col-md-12">


<style>
.accordion {
    background-color: #eee;
    color: #444;
    cursor: pointer;
    padding: 18px;
    width: 100%;
    border: none;
    text-align: left;
    outline: none;
    font-size: 15px;
    transition: 0.4s;
}

.active, .accordion:hover {
    background-color: #ccc;
}

.panel {
    padding: 0 18px;
    background-color: white;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.2s ease-out;
}
</style>

        <h2>{{ trans('general.faq.head') }}</h2>
        <p>{{ trans('general.faq.para') }}</p>
                
         @foreach($data as $key => $val)
            <button class="accordion">{{ucwords($val->$question)}}</button>
            <div class="panel">
               <p>{{strip_tags($val->$des)}}</p>
            </div>
         @endforeach
<script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight){
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    } 
  });
}
</script>



			</div>

		</div>

	</div>
@endsection
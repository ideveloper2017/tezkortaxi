
$('#btn-contact').on('click' , function(e) {
	$('#contact_form').trigger('submit');
});
$('#btn-complaint').on('click' , function(e) {
	$('#complaint').trigger('submit');
});
$('#btn-lost-item').on('click' , function(e) {
	$('#lost_item').trigger('submit');
});
$('#contact_form').submit(function(e) {
    // var i = 0;
	e.preventDefault();
	var btn = $('#btn-contact');	
	var form_data = new FormData( $(this)[0] );
	var route = $('#contact_form').attr('action');
	
	$.ajax({
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
		type		: 'POST',
		dataType 	: 'json',
		url			: route,
		data		: form_data,
		cache       : true,
		contentType : false,
		processData : false,
		async       : false,	
		beforeSend: function () {
			btn.button('loading');
		},		
		success: function(json) {                                                                                                                                                                                                                    
			$('.alert, .help-block, .alert-success, .alert-danger').remove();
			$('.form-group').removeClass('has-error');
			var html = '';
			if(json.success) {
				html  =  '<div class="alert alert-success"><i class="fa fa-check-circle"></i> Message Send Successfully !<button type="button" class="close" data-dismiss="alert">&times;</button></div>';
			} else {
				html = '<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> Something went wrong, please try again !<button type="button" class="close" data-dismiss="alert">&times;</button></div>';
			} 
			$('#contact-form .panel-body').prepend( html );
			
		},

		complete: function () {						
			btn.button('reset');
		},
		error: function(xhr) {
			if( xhr.status === 422 ) {
				var data =  xhr.responseJSON;                                        
				$('.alert, .help-block, .alert-success, .alert-danger').remove();
				$('.form-group').removeClass('has-error');
				
			  
				if( data ) {                        
					$.each(data, function(ind, value ) {
						$('#input-'+ind).after('<div class="help-block">' + value[0] + '</div>');                            					
						$('.help-block').parent().addClass('has-error');
					});                          
				}

			}              
		}

	}); 
	
});

$('#complaint').submit(function(e) {
	e.preventDefault();
	var btn = $('#btn-complaint');	
	var form_data = new FormData( $(this)[0] );
		
	$.ajax({
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
		type		: 'POST',
		dataType 	: 'json',
		url			: route,
		data		: form_data,
		cache       : false,
		contentType : false,
		processData : false,	
		beforeSend: function () {
			btn.button('loading');
		},
								
		success: function(json) { 
		// alert(json);                                                                                                                                                                                                                                                
			$('.alert, .help-block, .alert-success, .alert-danger').remove();
			$('.form-group').removeClass('has-error');
			var html = '';
			if(json.success) {
				html  =  '<div class="alert alert-success"><i class="fa fa-check-circle"></i> Message Send Successfully !<button type="button" class="close" data-dismiss="alert">&times;</button></div>';
			} else {
				html = '<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> Something went wrong, please try again !<button type="button" class="close" data-dismiss="alert">&times;</button></div>';
			} 
			$('#contact-form1 .panel-body').prepend( html );
		},

		complete: function () {
			
			btn.button('reset');
			
		},

		error: function(xhr) {
			if( xhr.status === 422 ) {
				var data =  xhr.responseJSON;                                        
				$('.alert, .help-block, .alert-success, .alert-danger').remove();
				$('.form-group').removeClass('has-error');
				
			  
				if( data ) {                        
					$.each(data, function(ind, value ) {
						$('#input-'+ind).after('<div class="help-block">' + value[0] + '</div>');                            					
						$('.help-block').parent().addClass('has-error');
					});                          
				}

			}              
		}
	}); 
	
});

$('#lost_item').submit(function(e) {
	e.preventDefault();
	var btn = $('#btn-lost-item');	
	var form_data = new FormData( $(this)[0] );
		
	$.ajax({
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
		type		: 'POST',
		dataType 	: 'json',
		url			: route,
		data		: form_data,
		cache       : false,
		contentType : false,
		processData : false,	
		beforeSend: function () {
			btn.button('loading');
		},
								
		success: function(json) { 
		// alert(json);                                                                                                                                                                                                                                                
			$('.alert, .help-block, .alert-success, .alert-danger').remove();
			$('.form-group').removeClass('has-error');
			var html = '';
			if(json.success) {
				html  =  '<div class="alert alert-success"><i class="fa fa-check-circle"></i> Message Send Successfully !<button type="button" class="close" data-dismiss="alert">&times;</button></div>';
			} else {
				html = '<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> Something went wrong, please try again !<button type="button" class="close" data-dismiss="alert">&times;</button></div>';
			} 
			$('#contact-form1 .panel-body').prepend( html );
		},

		complete: function () {
			
			btn.button('reset');
			
		},

		error: function(xhr) {
			if( xhr.status === 422 ) {
				var data =  xhr.responseJSON;                                        
				$('.alert, .help-block, .alert-success, .alert-danger').remove();
				$('.form-group').removeClass('has-error');
				
			  
				if( data ) {                        
					$.each(data, function(ind, value ) {
						$('#input-'+ind).after('<div class="help-block">' + value[0] + '</div>');                            					
						$('.help-block').parent().addClass('has-error');
					});                          
				}

			}              
		}
	}); 
});

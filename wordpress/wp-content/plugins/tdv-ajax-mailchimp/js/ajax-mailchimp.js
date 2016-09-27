jQuery(function() {

	/*-----------------------------------/
	/* AJAX CALL FOR NEWSLETTER FUNCTION
	/*----------------------------------*/

	var newsletterForm = jQuery('.newsletter-form');
	
	jQuery('#btn-subscribe-newsletter').on('click', function() {
		var nonce = jQuery(this).attr("data-nonce");

		doSubscribe(newsletterForm, nonce);
	});

	function doSubscribe(newsletterForm, nonce) {
		var email = newsletterForm.find('input[name="email"]').val();
		var btn = newsletterForm.find('.btn');

		jQuery.ajax({

			url: myAjax.ajaxurl,
			type: 'POST',
			dataType: 'json',
			cache: false,
			data: {action: 'rpt_subscribe_newsletter', nonce: nonce, email: email },
			beforeSend: function(){
				btn.addClass('loading');
				btn.attr('disabled', 'disabled');
			},
			success: function( data, textStatus, XMLHttpRequest ){
				var className = '';

				if( data.result == true ){
					className = 'alert-success';
				}else {
					className = 'alert-danger';
				}

				newsletterForm.find('.alert').html( data.message )
				.removeClass( 'alert-danger alert-success' )
				.addClass( 'alert active ' + className )
				.slideDown(300);

				btn.removeClass('loading');
				btn.removeAttr('disabled');
			},
			error: function( XMLHttpRequest, textStatus, errorThrown ){
				console.log("AJAX ERROR: \n" + XMLHttpRequest.responseText + "\n" + textStatus);
			}
			
		});
	}

});


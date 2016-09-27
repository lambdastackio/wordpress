jQuery(document).ready( function() {
	// top searchbox show/hide
	jQuery('.top-searchbox-toggle').on( 'click', function(e) {
		e.preventDefault();
		var textInput = jQuery('.top-searchbox').find('input[type="text"]');

		// toggle search input disable
		if( textInput.prop('disabled') ) {
			textInput.prop('disabled', false);
		} else {
			textInput.prop('disabled', true);
		}

		jQuery('.top-searchbox').toggleClass('active');
		jQuery(this).find('.fa-search').toggleClass('fa-close');
		textInput.focus();
	});

	// toggle disabled class from top nav menu item
	jQuery(window).on('resize', function() {
		adaptDropdownToggle();
	});

	adaptDropdownToggle();

	function adaptDropdownToggle() {
		if( jQuery(window).width() <= 992 ) {
			jQuery('ul.navbar-nav li .dropdown-toggle').removeClass('disabled');
		} else {
			jQuery('ul.navbar-nav li .dropdown-toggle').addClass('disabled');
		}
	}

	// back to top scroll
	jQuery(window).scroll( function() {
		if(	jQuery(this).scrollTop() > 300 ) {
			jQuery('.back-to-top').fadeIn();
		} else {
			jQuery('.back-to-top').fadeOut();
		}
	});

	jQuery('.back-to-top').on( 'click', function(e) {
		e.preventDefault();

		jQuery('body, html').animate({
			scrollTop: 0
		}, 800, 'easeInOutExpo');
	});
});
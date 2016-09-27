jQuery(function() {
	
	/*----------------------------/
	/* PORTFOLIO ISOTOPE INIT
	/*---------------------------*/

	if(jQuery('.portfolio-isotope').length > 0) {
		jQuery(window).load(function() {
			jQuerycontainer = jQuery('.portfolio-isotope');

			var jQueryisoObj = jQuerycontainer.isotope({
				itemSelector: '.portfolio-item',
				layoutMode: 'fitRows'
			});

			jQuerycontainer.parent().height(jQuerycontainer.height());

			jQuery('.portfolio-item-filters a').click( function(e) {
				e.preventDefault();

				var selector = jQuery(this).attr('data-filter');
				jQuerycontainer.isotope({
					filter: selector
				});

				jQuerycontainer.parent().height(jQuerycontainer.height());

				jQuery('.portfolio-item-filters a').removeClass('active');
				jQuery(this).addClass('active');
			});
		});
	}

	// fix portfolio item overlay trigger
	jQuery('.portfolio-item').on('click', function() {
		// do nothing, this triggering portfolio overlay on mobile Safari
	});

});
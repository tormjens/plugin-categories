/**
 * Plugin Categories
 * http://tormorten.no
 *
 * Copyright (c) 2015 Tor Morten Jensen
 * Licensed under the GPLv2+ license.
 */
 
jQuery(document).ready(function($) {
	
	$(document).on( 'change', '#bulk-action-selector-top', function() {

		if( $(this).val() === 'categorize' ) {

			$('#bulk-action-selector-category').insertAfter($(this)).show();

		}

	});

});
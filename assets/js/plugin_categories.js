/*! Plugin Categories - v0.1.0
 * http://tormorten.no
 * Copyright (c) 2015; * Licensed GPLv2+ */
jQuery(document).ready(function($) {
	
	$(document).on( 'change', '#bulk-action-selector-top', function() {

		if( $(this).val() === 'categorize' ) {

			$('#bulk-action-selector-category').insertAfter($(this)).show();

		}

	});

});
jQuery(document).ready(function($) {

	$( '.repeater' ).repeater({

		defaultValues: {
			'item-code': '',
			'item-destination': 'header',
			'item-load': 'all',
			'item-type': 'css'
		},
		show: function() {
			var item_ID = $(this).find( '.item_ID' );

			$.ajax({
				url: ajax_cm_handfsl_vars.ajaxurl,
				data: {
					nonce: ajax_cm_handfsl_vars.get_unique_id_nonce,
					action: ajax_cm_handfsl_vars.get_unique_id_action
				},
				method: "POST"
			}).done( function( response ) {
				var unique_ID = parseInt( response );
				var items_IDS = [];

				$( 'input[class=item_ID]' ).each( function() {
					items_IDS.push( parseInt( $(this).val() ));
				});

				if( jQuery.inArray( unique_ID, items_IDS ) != -1 ){
					while( jQuery.inArray( unique_ID, items_IDS ) != -1 ){
						unique_ID++;
					}
				}

				item_ID.val( unique_ID );
			});

			$(this).slideDown();
		},
		hide: function( deleteElement ) {
			if( confirm('Are you sure you want to delete this element?') )
			{
				$(this).slideUp( deleteElement );
			}
		},
		// isFirstItemUndeletable: true

	});

	$(document).on( 'change', '.item-load', function() {

		if( $(this).val() === 'custom' )
		{
			$(this).parents( '.repeater-item' ).find( '.cpt' ).removeClass( 'hidden' );
		}
		else
		{
			$(this).parents( '.repeater-item' ).find( '.cpt' ).addClass( 'hidden' );
		}

	});
});
(function( $ ) {
	'use strict';

	$(function() {

		// Hides and resets fields unless an element selector is passed.
		function hideFields( ignoredElement = '' ) {

			if ( ignoredElement !== '.meta-section' ) {
				$( '.meta-section' ).hide().find( 'option:first' ).attr( 'selected', 'selected' );			
			}

			if ( ignoredElement !== '.meta-target-type' ) {
				$( '.meta-target-type' ).hide().find( 'option:first' ).attr( 'selected', 'selected' );
			}

		}

		// Shows or hides fields based on selected Scope.
		function setFieldsVisibility(scope) {
			
			switch(scope) {
				case 'global':
					hideFields();
				break;
				case 'section':
					hideFields( '.meta-section' );
					$( '.meta-section' ).show();
				break;
				case 'entity':
					hideFields( '.meta-target-type' );
					$( '.meta-target-type' ).show();
				break;
				default:
					// code block
			}
			
		}

		// Sets fields visibility on Scope change.
		$( 'input[type=radio][name=scope]' ).on('change', function() {
			setFieldsVisibility( this.value );
		});

		// Sets Section field visibility on page ready.
		setFieldsVisibility( $( 'input[type=radio][name=scope]:checked' ).val() );
	});

})( jQuery );

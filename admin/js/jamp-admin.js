(function( $ ) {
	'use strict';

	$(function() {

		// Shows or hides Section Field based on selected Scope.
		function setSectionVisibility(scope) {
			if (scope === 'section') {
				$( '.meta-section' ).show();
			} else {
				$( '.meta-section' ).hide().find( 'option:first' ).attr( 'selected', 'selected' );
			}
		}

		// Sets Section field visibility on Scope change.
		$( 'input[type=radio][name=scope]' ).on('change', function() {
			setSectionVisibility( this.value );
		});

		// Sets Section field visibility on page ready.
		setSectionVisibility( $( 'input[type=radio][name=scope]:checked' ).val() );
	});

})( jQuery );

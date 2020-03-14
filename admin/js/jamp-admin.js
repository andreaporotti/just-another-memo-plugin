(function( $ ) {
	'use strict';

	$(function() {
		// ...
		function getEntitiesList(postType) {
			
			//console.log(postType);
			//console.log(jamp_ajax);
			
			if ( postType !== '' ) {
				
				console.log('getting entities list...');
				
				$.post( jamp_ajax.ajax_url, {
					_ajax_nonce: jamp_ajax.nonce,
					action: 'build_entities_list',
					post_type: postType
				}, function( response ) {
					
					//console.log('response data:');
					//console.log(response);
					
					if ( response.success ) {

						var options = ``;
						$.each(response.data, function(key, entity) {
							options += `<option class="target-entity" value="${entity.id}">${entity.title}</option>`;
						});
						
						$('#target option.target-entity').remove();
						$('#target').append(options);

					} else {
						
						console.log('Error getting entities!');
						
					}
					
				} );
				
			} else {
				
				console.log('do nothing');
				
			}
			
		}

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
		
		// Fills the entities list on target type change.
		$( '#target-type' ).on('change', function() {
			getEntitiesList( this.value );
		});

		// Sets Section field visibility on page ready.
		setFieldsVisibility( $( 'input[type=radio][name=scope]:checked' ).val() );

	});

})( jQuery );

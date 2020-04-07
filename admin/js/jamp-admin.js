(function( $ ) {
	'use strict';

	$(function() {

		// Gets entities of the specified post type and insert them in the select.
		function getEntitiesList( postType ) {

			if ( postType !== '' ) {

				$.post( jamp_ajax.ajax_url, {
					_ajax_nonce: jamp_ajax.nonce,
					action: 'build_targets_list',
					post_type: postType
				}, function( response ) {

					if ( response.success ) {

						var options = ``;
						$.each(response.data, function( key, entity ) {
							options += `<option class="target-entity" value="${entity.id}">${entity.title} (${entity.status})</option>`;
						});
						
						$( '#target option.target-entity' ).remove();
						$( '#target' ).append(options);
						
						// Selects the option having the value from the database if it's present in the select.
						var savedTarget = $( '#saved-target' ).val();

						if ( $( `#target option[value="${savedTarget}"]` ).length > 0 ) {
							$( '#target' ).val(savedTarget);
						} else {
							$( '#target' ).val('');
						}

					} else {
						
						console.log('Error getting entities!');
						
					}
					
				} );
				
			} else {
				
				$( '#target option.target-entity' ).remove();
				
			}
			
		}

		// Hides and resets fields skipping ignored elements
		function hideFields( ignoredElements = [] ) {

			//if ( ignoredElement !== '.meta-section' ) {
			if ( $.inArray('.meta-section', ignoredElements) < 0 ) {
				$( '.meta-section' ).hide().find( 'option:first' ).attr( 'selected', 'selected' );			
			}

			//if ( ignoredElements !== '.meta-target-type' ) {
			if ( $.inArray('.meta-target-type', ignoredElements) < 0 ) {
				$( '.meta-target-type' ).hide().find( 'option:first' ).attr( 'selected', 'selected' );
			}
			
			if ( $.inArray('.meta-target', ignoredElements) < 0 ) {
				$( '.meta-target' ).hide().find( 'option:first' ).attr( 'selected', 'selected' );
			}

		}

		// Shows or hides fields based on selected Scope.
		function setFieldsVisibility( scope ) {
			
			switch(scope) {
				case 'global':
					hideFields();
				break;
				case 'section':
					hideFields( ['.meta-section'] );
					$( '.meta-section' ).show();
				break;
				case 'entity':
					hideFields( ['.meta-target-type', '.meta-target'] );
					$( '.meta-target-type' ).show();
					$( '#target-type' ).trigger('change');
					$( '.meta-target' ).show();
				break;
				default:
				break;
			}
			
		}
		
		// Moves a note to trash.
		function moveToTrash( note, location ) {
			
			if ( note !== '' & note !== null ) {
				
				$.post( jamp_ajax.ajax_url, {
					_ajax_nonce: jamp_ajax.nonce,
					action: 'move_to_trash',
					note: note
				}, function( response ) {
					
					if ( response.success ) {
						
						if ( location === 'column' ) {
							
							removeTrashedNoteFromColumn( note );
							
						} else if ( location === 'adminbar' ) {
							
							removeTrashedNoteFromAdminBar( note );
							
						}

					} else {

						console.log( 'Error moving note to trash!' );

					}
					
				} );
				
			}
			
		}
		
		// Removes trashed note from a custom column.
		function removeTrashedNoteFromColumn( note ) {
			
			// Gets number of notes in the current table cell.
			var cell = $( '.jamp-column-note[data-note=' + note + ']' ).parent( 'td' );
			var existingNotes = cell.find( '.jamp-column-note' ).length;

			// Hides current note.
			$( '.jamp-column-note[data-note=' + note + ']' ).addClass( 'jamp-column-note--red-background' ).fadeOut( 600, function() {

				// Removes hidden element.
				$(this).remove();

				// Shows the placeholder if no more notes are present in the row.
				if ( ( existingNotes - 1 ) === 0 ) {

					cell.find( '.jamp-column-note__no-notes-notice' ).removeClass( 'jamp-column-note__no-notes-notice--hidden' );

				}

			} );
			
		}
		
		// Removes trashed note from admin bar.
		function removeTrashedNoteFromAdminBar( note ) {
			
			// Gets number of notes in the current admin bar section.
			var section = $( '.jamp-admin-bar-note[data-note=' + note + ']' ).parent( '.jamp-admin-bar-section' );
			var existingNotes = section.find( '.jamp-admin-bar-note' ).length;
			
			// Hides current note.
			$( '.jamp-admin-bar-note[data-note=' + note + ']' ).addClass( 'jamp-admin-bar-note--red-background' ).fadeOut( 600, function() {
				
				// Removes hidden element.
				$(this).remove();
				
				// Shows the placeholder if no more notes are present in the section.
				if ( ( existingNotes - 1 ) === 0 ) {

					section.find( '.jamp-admin-bar-note__no-notes-notice' ).removeClass( 'jamp-admin-bar-note__no-notes-notice--hidden' );

				}
				
			} );
			
		}

		// Fills the entities list on target type change.
		$( '#target-type' ).on('change', function() {
			getEntitiesList( this.value );
		});

		// Sets fields visibility on Scope change.
		$( 'input[type=radio][name=scope]' ).on('change', function() {
			setFieldsVisibility( this.value );
		});

		// Sets fields visibility on page ready.
		setFieldsVisibility( $( 'input[type=radio][name=scope]:checked' ).val() );
		
		// Trash link on custom column.
		$( '.jamp-column-note__note-trash-action' ).on( 'click', function( e ) {
			e.preventDefault();
			
			var trashLink = $( this );
			
			$( '.jamp-trash-dialog' ).dialog( {
				modal: true,
				buttons: {
					'OK': function() {
						$( this ).dialog( "close" );
						moveToTrash( trashLink.data('note'), 'column' );
					}
				}
			} );
		} );
		
		// Trash link on admin bar.
		$( '.jamp-admin-bar-action--trash' ).on( 'click', function( e ) {
			e.preventDefault();
			
			var trashLink = $( this );
			
			$( '.jamp-trash-dialog' ).dialog( {
				modal: true,
				buttons: {
					'OK': function() {
						$( this ).dialog( "close" );
						moveToTrash( trashLink.data('note'), 'adminbar' );
					}
				}
			} );
		} );
		
	});

})( jQuery );

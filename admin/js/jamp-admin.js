(function( $ ) {
	'use strict';

	$(function() {

		// Gets entities of the specified post type and insert them in the meta box items select.
		function getEntitiesList( postType ) {
			if ( postType !== '' && postType !== null ) {
				$.post( jamp_ajax.ajax_url, {
					_ajax_nonce: jamp_ajax.nonce,
					action: 'build_targets_list',
					post_type: postType
				}, function( response ) {
					if ( response.success ) {
						let options = '';

						$.each( response.data, function( key, entity ) {
							options += `<option class="target-entity" value="${entity.id}">${entity.title} (${entity.status})</option>`;
						} );

						$( '#jamp_meta_box #target option.target-entity' ).remove();
						$( '#jamp_meta_box #target' ).append( options );

						// Selects the option having the value from the database if it's present in the select.
						let savedTarget = $( '#jamp_meta_box #saved-target' ).val();

						if ( $( `#jamp_meta_box #target option[value="${savedTarget}"]` ).length > 0 ) {
							$( '#jamp_meta_box #target' ).val( savedTarget );
						} else {
							$( '#jamp_meta_box #target' ).val( '' );
						}
					} else {
						alert( jamp_strings.get_entities_list_error );
					}
				} );
			} else {
				$( '#jamp_meta_box #target option.target-entity' ).remove();
			}
		}

		// Hides and resets meta box fields skipping ignored elements.
		function hideFields( ignoredElements ) {
			if ( $.inArray('.meta-section', ignoredElements) < 0 ) {
				$( '#jamp_meta_box .meta-section' ).hide().find( 'option:first' ).attr( 'selected', 'selected' );			
			}

			if ( $.inArray('.meta-target-type', ignoredElements) < 0 ) {
				$( '#jamp_meta_box .meta-target-type' ).hide().find( 'option:first' ).attr( 'selected', 'selected' );
			}

			if ( $.inArray('.meta-target', ignoredElements) < 0 ) {
				$( '#jamp_meta_box .meta-target' ).hide().find( 'option:first' ).attr( 'selected', 'selected' );
			}
		}

		// Shows or hides meta box fields based on selected Scope.
		function setFieldsVisibility( scope ) {
			switch ( scope ) {
				case 'global':
					hideFields();
					break;
				case 'section':
					hideFields( [ '.meta-section' ] );
					$( '#jamp_meta_box .meta-section' ).show();
					break;
				case 'entity':
					hideFields( [ '.meta-target-type', '.meta-target' ] );
					$( '#jamp_meta_box .meta-target-type' ).show();
					$( '#jamp_meta_box #target-type' ).trigger( 'change' );
					$( '#jamp_meta_box .meta-target' ).show();
					break;
				default:
			}
		}
		
		// Moves a note to trash.
		function moveToTrash( note, location ) {
			if ( note !== '' && note !== null ) {
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
						alert( jamp_strings.move_to_trash_error );
					}
				} );
			}
		}
		
		// Removes trashed note from a custom column.
		function removeTrashedNoteFromColumn( note ) {
			let selectedNote = $( '.jamp-column-note[data-note=' + note + ']' );

			// Gets number of notes in the current table cell.
			let cell = selectedNote.parent( 'td' );
			let existingNotes = cell.find( '.jamp-column-note' ).length;

			// Hides current note.
			selectedNote.addClass( 'jamp-column-note--red-background' ).fadeOut( 600, function() {
				// Removes hidden note.
				$(this).remove();

				// Shows the placeholder if the row contains no more notes.
				if ( ( existingNotes - 1 ) === 0 ) {
					cell.find( '.jamp-column-note__no-notes-notice' ).removeClass( 'jamp-column-note__no-notes-notice--hidden' );
				}
			} );
		}
		
		// Removes trashed note from admin bar.
		function removeTrashedNoteFromAdminBar( note ) {
			let selectedNote = $( '.jamp-admin-bar-note[data-note=' + note + ']' );

			// Gets number of notes in the current admin bar section.
			let section = selectedNote.parent( '.jamp-admin-bar-section' );
			let existingNotes = section.find( '.jamp-admin-bar-note' ).length;

			// Hides current note.
			selectedNote.addClass( 'jamp-admin-bar-note--red-background' ).fadeOut( 600, function() {
				// Removes hidden note.
				$(this).remove();

				// Shows the placeholder if the section contains no more notes.
				if ( ( existingNotes - 1 ) === 0 ) {
					section.find( '.jamp-admin-bar-note__no-notes-notice' ).removeClass( 'jamp-admin-bar-note__no-notes-notice--hidden' );
				}
			} );
		}

		// Fills meta box entities list on target type change.
		$( '#jamp_meta_box #target-type' ).on('change', function() {
			getEntitiesList( this.value );
		});

		// Sets meta box fields visibility on Scope change.
		$( '#jamp_meta_box input[type=radio][name=scope]' ).on('change', function() {
			setFieldsVisibility( this.value );
		});

		// Sets meta box fields visibility on page ready.
		setFieldsVisibility( $( '#jamp_meta_box input[type=radio][name=scope]:checked' ).val() );

		// Trash links on custom column.
		$( '.jamp-column-note__note-trash-action' ).on( 'click', function( e ) {
			e.preventDefault();

			let trashLink = $( this );

			$( '.jamp-trash-dialog' ).dialog( {
				modal: true,
				buttons: {
					'OK': function() {
						$( this ).dialog( 'close' );
						moveToTrash( trashLink.data('note'), 'column' );
					}
				}
			} );
		} );

		// Trash links on admin bar.
		$( '.jamp-admin-bar-action--trash' ).on( 'click', function( e ) {	
			e.preventDefault();

			let trashLink = $( this );

			$( '.jamp-trash-dialog' ).dialog( {
				modal: true,
				buttons: {
					'OK': function() {
						$( this ).dialog( 'close' );
						moveToTrash( trashLink.data('note'), 'adminbar' );
					}
				}	
			} );	
		} );

	});

})( jQuery );

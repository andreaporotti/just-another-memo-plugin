<?php
/**
 * Provides HTML code for the admin bar menu item.
 *
 * @link       https://www.andreaporotti.it
 * @since      1.0.0
 *
 * @package    Jamp
 * @subpackage Jamp/admin/partials
 */
?>

<?php
	$html = '';
	
	// Global notes.
	$create_url = add_query_arg( array(
		'post_type' => 'jamp_note',
		'jamp_scope' => 'global',
	), admin_url( 'post-new.php' ) );
	
	$html .= '<span class="jamp-admin-bar-section-title">' . __('Note globali') . '</span> (<a class="jamp-admin-bar-action jamp-admin-bar-action--create" href="' . $create_url . '">' . __('aggiungi') . '</a>)';
	
	$global_notes_args = array(
		'post_type' => 'jamp_note',
		'posts_per_page' => -1,
		'meta_key' => 'jamp_scope',
		'meta_compare' => '=',
		'meta_value' => 'global',
	);
	
	$global_notes = get_posts($global_notes_args);
	
	if ( ! empty( $global_notes ) ) {
		
		foreach ($global_notes as $note) {

			$html .= '<div class="jamp-admin-bar-note">'
					. '<span class="jamp-admin-bar-note__title">' . $note->post_title . '</span>'
					. '<span class="jamp-admin-bar-note__actions">'
					. '<a class="jamp-admin-bar-action jamp-admin-bar-action--edit" href="' . get_edit_post_link($note->ID) . '" title="' . __('Modifica') . '"></a>'
					. '<a class="jamp-admin-bar-action jamp-admin-bar-action--trash" href="' . get_delete_post_link($note->ID) . '" title="' . __('Sposta nel cestino') . '"></a>'
					. '</span>'
					. '<p class="jamp-admin-bar-note__content">' . $note->post_content . '</p>'
					. '</div>';
			
		}
		
	} else {
		
		$html .= '<span class="jamp-admin-bar-note__no-notes-notice">' . __('Non sono presenti note globali.') . '</span>';
		
	}

	// Section notes.
	if ( $this->is_section_supported() ) {

		$create_url = add_query_arg( array(
			'post_type' => 'jamp_note',
			'jamp_scope' => 'section',
			'jamp_target' => $this->get_current_page_url(),
		), admin_url( 'post-new.php' ) );
		
		$html .= '<span class="jamp-admin-bar-section-title">' . __('Note in questa sezione') . '</span> (<a class="jamp-admin-bar-action jamp-admin-bar-action--create" href="' . $create_url . '">' . __('aggiungi') . '</a>)';

		$section_notes_args = array(
			'post_type' => 'jamp_note',
			'posts_per_page' => -1,
			'meta_key' => 'jamp_target',
			'meta_compare' => '=',
			'meta_value' => $this->get_current_page_url(),
		);

		$section_notes = get_posts($section_notes_args);

		if ( ! empty( $section_notes ) ) {

			foreach ($section_notes as $note) {

				$html .= '<div class="jamp-admin-bar-note">'
						. '<span class="jamp-admin-bar-note__title">' . $note->post_title . '</span>'
						. '<span class="jamp-admin-bar-note__actions">'
						. '<a class="jamp-admin-bar-action jamp-admin-bar-action--edit" href="' . get_edit_post_link($note->ID) . '" title="' . __('Modifica') . '"></a>'
						. '<a class="jamp-admin-bar-action jamp-admin-bar-action--trash" href="' . get_delete_post_link($note->ID) . '" title="' . __('Sposta nel cestino') . '"></a>'
						. '</span>'
						. '<p class="jamp-admin-bar-note__content">' . $note->post_content . '</p>'
						. '</div>';

			}

		} else {

			$html .= '<span class="jamp-admin-bar-note__no-notes-notice">' . __('Non sono presenti note in questa sezione.') . '</span>';

		}

	}
	
	// Trash dialog.
	$html .= '<div class="jamp-trash-dialog jamp-trash-dialog--hidden" title="' . __('Sposta nel cestino?') . '">'
			. '<p>' . __('Vuoi spostare questa nota nel cestino?') . '</p>'
			. '</div>';

	return $html;
?>
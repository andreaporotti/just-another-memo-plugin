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
		'scope' => 'global',
	), admin_url( 'post-new.php' ) );
	
	$html .= '<span class="area-title">' . __('Note globali') . '</span> <span class="create-link">(<a href="' . $create_url . '">' . __('aggiungi') . '</a>)</span>';
	
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
			
			$html .= '<div class="note">'
					. '<span class="note-title">' . $note->post_title . '</span> '
					. '<span class="edit-link">(<a href="' . get_edit_post_link($note->ID) . '">' . __('modifica') . '</a></span> | '
					. '<span class="trash-link"><a href="' . get_delete_post_link($note->ID) . '">' . __('cestina') . '</a>)</span>'
					. '<p class="note-content">' . $note->post_content . '</p>'
					. '</div>';
			
		}
		
	} else {
		
		$html .= '<span class="notes-not-found">' . __('Non sono presenti note globali.') . '</span>';
		
	}

	// Section notes.
	if ( $this->is_section_supported() ) {

		$create_url = add_query_arg( array(
			'post_type' => 'jamp_note',
			'scope' => 'section',
			'section' => $this->get_current_page_url(),
		), admin_url( 'post-new.php' ) );
		
		$html .= '<span class="area-title">' . __('Note in questa sezione') . '</span> <span class="create-link">(<a href="' . $create_url . '">' . __('aggiungi') . '</a>)</span>';

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

				$html .= '<div class="note">'
						. '<span class="note-title">' . $note->post_title . '</span> '
						. '<span class="edit-link">(<a href="' . get_edit_post_link($note->ID) . '">' . __('modifica') . '</a></span> | '
						. '<span class="trash-link"><a href="' . get_delete_post_link($note->ID) . '">' . __('cestina') . '</a>)</span>'
						. '<p class="note-content">' . $note->post_content . '</p>'
						. '</div>';

			}

		} else {

			$html .= '<span class="notes-not-found">' . __('Non sono presenti note in questa sezione.') . '</span>';

		}

	}

	return $html;
?>
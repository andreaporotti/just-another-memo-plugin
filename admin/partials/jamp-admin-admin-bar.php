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
	$html .= '<span class="area-title">' . __('Note globali') . '</span><span class="create-link"><a href="#">aggiungi</a></span>';
	
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
					. '<span class="note-title">' . $note->post_title . '</span>'
					. '<p class="note-content">' . $note->post_content . '</p>'
					. '</div>';
			
		}
		
	} else {
		
		$html .= '<span class="notes-not-found">' . __('Non sono presenti note globali.') . '</span>';
		
	}
	
	wp_reset_postdata();
	
	// Section notes.
	$html .= '<span class="area-title">' . __('Note in questa sezione') . '</span><span class="create-link"><a href="#">aggiungi</a></span>';
	
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
					. '<span class="note-title">' . $note->post_title . '</span>'
					. '<p class="note-content">' . $note->post_content . '</p>'
					. '</div>';
			
		}
		
	} else {
		
		$html .= '<span class="notes-not-found">' . __('Non sono presenti note per questa sezione.') . '</span>';
		
	}
	
	wp_reset_postdata();

	return $html;
?>
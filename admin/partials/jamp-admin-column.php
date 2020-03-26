<?php
/**
 * Provides HTML code for the column inside elements lists.
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
	
	// Get notes.
	$notes_args = array(
		'post_type' => 'jamp_note',
		'posts_per_page' => -1,
		'meta_key' => 'jamp_target',
		'meta_compare' => '=',
		'meta_value' => $post_id,
	);
	
	$notes = get_posts($notes_args);
	
	if ( ! empty( $notes ) ) {

		foreach ($notes as $note) {
			
			$html .= '<div class="jamp-column-note">'
					. '<span class="jamp-column-note__tile">' . $note->post_title . '</span>'
					. '<div class="jamp-column-note__container">'
					. '<p class="jamp-column-note__content">' . $note->post_content . '</p>'
					. '<div class="jamp-column-note__note-actions">'
					. '<a href="' . get_edit_post_link($note->ID) . '">' . __('Modifica') . '</a> | '
					. '<a href="' . get_delete_post_link($note->ID) . '" class="jamp-column-note__note-trash-action">' . __('Cestino') . '</a>'
					. '</div>'
					. '</div>'
					. '</div>';
		
		}
		
	} else {
		$html .= '<span class="jamp-column-note__no-notes-notice">—</span>';
	}
	
	// Create link.
	$screen = get_current_screen();

	$create_url = add_query_arg( array(
		'post_type' => 'jamp_note',
		'jamp_scope' => 'entity',
		'jamp_target_type' => $screen->post_type,
		'jamp_target' => $post_id,
	), admin_url( 'post-new.php' ) );

	$html .= '<div class="jamp-column-note__generic-actions">'
			. '<a href="' . $create_url . '">Aggiungi nota</a>'
			. '</div>';

	// Show content.
	echo $html;
?>
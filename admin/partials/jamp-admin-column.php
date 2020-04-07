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
	if ( $column_name === 'jamp_note' ) {

		$html = '';

		// Get notes.
		$notes_args = array(
			'post_type'      => 'jamp_note',
			'posts_per_page' => -1,
			'meta_key'       => 'jamp_target',
			'meta_compare'   => '=',
			'meta_value'     => $post_id,
		);

		$notes = get_posts($notes_args);

		if ( ! empty( $notes ) ) {

			foreach ($notes as $note) {

				$html .= '<div class="jamp-column-note" data-note="' . $note->ID . '">'
						. '<span class="jamp-column-note__tile">' . $note->post_title . '</span>'
						. '<div class="jamp-column-note__container">'
						. '<p class="jamp-column-note__content">' . $note->post_content . '</p>'
						. '<div class="jamp-column-note__note-actions">'
						. '<a href="' . get_edit_post_link($note->ID) . '">' . __('Modifica') . '</a> | '
						. '<a href="#" class="jamp-column-note__note-trash-action" data-note="' . $note->ID . '">' . __('Cestino') . '</a>'
						. '</div>'
						. '</div>'
						. '</div>';

			}

		}

		// Adds placeholder, hidden if there are notes.
		$css_class = ( !empty( $notes ) ) ? 'jamp-column-note__no-notes-notice--hidden' : '';
		$html .= '<span class="jamp-column-note__no-notes-notice ' . $css_class . '">—</span>';

		// Create link.
		$screen = get_current_screen();

		$create_url = add_query_arg( array(
			'post_type'        => 'jamp_note',
			'jamp_scope'       => 'entity',
			'jamp_target_type' => $screen->post_type,
			'jamp_target'      => $post_id,
		), admin_url( 'post-new.php' ) );

		$html .= '<div class="jamp-column-note__generic-actions">'
				. '<a href="' . $create_url . '">Aggiungi nota</a>'
				. '</div>';

		// Show content.
		echo $html;
	
	}
	
	if ( $column_name === 'jamp_location' ) {
		
		$jamp_meta = get_post_meta( $post_id );
		
		switch ( $jamp_meta['jamp_scope'][0] ) {
			
			case 'global':
				
				echo __( 'Globale', 'jamp' );
				break;
			
			case 'section':
				
				// Look for the section url inside the sections list and print the corresponding name.
				foreach ( $this->sections_list as $section ) {

					if ( $section['url'] === $jamp_meta['jamp_target'][0] && $section['is_submenu'] ) {

						echo __( 'Sezione', 'jamp' ) . ': ' . $section['parent_name'] . ' ' .  $section['name'];

					}

				}
				
				break;
			
			case 'entity':
				
				// Look for the target type name inside the target types list and print the corresponding label.
				foreach ( $this->target_types_list as $target_type ) {

					if ( $target_type['name'] === $jamp_meta['jamp_target_type'][0] ) {

						echo $target_type['singular_name'] . ': ';

					}

				}
				
				$post = get_post( $jamp_meta['jamp_target'][0] );
				echo $post->post_title;
				
				break;
			
			default:
				
				break;
			
		}
		
	}

?>
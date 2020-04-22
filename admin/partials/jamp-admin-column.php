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
if ( 'jamp_note' === $column_name ) {

	// Get notes.
	$notes_args = array(
		'post_type'      => 'jamp_note',
		'posts_per_page' => -1,
		'meta_key'       => 'jamp_target',
		'meta_compare'   => '=',
		'meta_value'     => $post_id,
	);

	$notes = get_posts( $notes_args );

	if ( ! empty( $notes ) ) {

		foreach ( $notes as $note ) {
			
			$note_author = get_userdata( $note->post_author );
			$note_date   = wp_date( get_option( 'links_updated_date_format' ), strtotime( $note->post_modified_gmt ) );

			?>
			<div class="jamp-column-note" data-note="<?php echo esc_attr( $note->ID ); ?>">
				<span class="jamp-column-note__title"><?php echo esc_html( $note->post_title ); ?></span>
				<div class="jamp-column-note__container">
					<div class="jamp-column-note__content"><?php echo wp_kses_post( $note->post_content ); ?></div>
					<div class="jamp-column-note__note-actions">
						<a href="#" class="jamp-note-info-tooltip"><?php echo esc_html__( 'Info', 'jamp' ); ?>
						<span class="jamp-note-info-tooltip__content jamp-note-info-tooltip__content--top">
							<span class="jamp-note-info-tooltip__label"><?php echo esc_html__( 'Autore', 'jamp' ); ?></span>
							<span class="jamp-note-info-tooltip__field"><?php echo esc_html( $note_author->display_name ); ?></span>
							<span class="jamp-note-info-tooltip__label"><?php echo esc_html__( 'Ultima modifica', 'jamp' ); ?></span>
							<span class="jamp-note-info-tooltip__field"><?php echo esc_html( $note_date ); ?></span>
						</span>
						</a> | 
						<a href="<?php echo esc_url( get_edit_post_link( $note->ID ) ); ?>"><?php echo esc_html__( 'Modifica', 'jamp' ); ?></a> | 
						<a href="#" class="jamp-column-note__note-trash-action" data-note="<?php echo esc_attr( $note->ID ); ?>"><?php echo esc_html__( 'Cestino', 'jamp' ); ?></a>
					</div>
				</div>
			</div>
			<?php

		}
	}

	// Adds placeholder, hidden if there are notes.
	$css_class = ( ! empty( $notes ) ) ? 'jamp-column-note__no-notes-notice--hidden' : '';

	?>
	<span class="jamp-column-note__no-notes-notice <?php echo esc_attr( $css_class ); ?>">—</span>
	<?php

	// Create link.
	$screen = get_current_screen();

	$create_url = add_query_arg(
		array(
			'post_type'        => 'jamp_note',
			'jamp_scope'       => 'entity',
			'jamp_target_type' => $screen->post_type,
			'jamp_target'      => $post_id,
		),
		admin_url( 'post-new.php' )
	);

	?>
	<div class="jamp-column-note__generic-actions">
		<a href="<?php echo esc_url( $create_url ); ?>">Aggiungi nota</a>
	</div>
	<?php

}

if ( 'jamp_location' === $column_name ) {

	$jamp_meta = get_post_meta( $post_id );

	switch ( $jamp_meta['jamp_scope'][0] ) {

		case 'global':
			echo esc_html__( 'Globale', 'jamp' );
			break;

		case 'section':
			// Look for the section url inside the sections list and print the corresponding name.
			foreach ( $this->sections_list as $section ) {

				if ( $section['url'] === $jamp_meta['jamp_target'][0] && $section['is_submenu'] ) {

					echo esc_html__( 'Sezione', 'jamp' ) . ': ' . esc_html( $section['parent_name'] ) . ' ' . esc_html( $section['name'] );

				}
			}

			break;

		case 'entity':
			// Look for the target type name inside the target types list and print the corresponding label.
			foreach ( $this->target_types_list as $target_type ) {

				if ( $target_type['name'] === $jamp_meta['jamp_target_type'][0] ) {

					echo esc_html( $target_type['singular_name'] ) . ': ';

				}
			}

			$current_post = get_post( $jamp_meta['jamp_target'][0] );
			echo esc_html( $current_post->post_title );

			break;

		default:
			break;

	}
}

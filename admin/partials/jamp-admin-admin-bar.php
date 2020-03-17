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

	// Gets Notes with the current page url.
	$args = array(
		'post_type' => 'jamp_note',
		'posts_per_page' => -1,
		'meta_key' => 'jamp_target',
		'meta_compare' => '=',
		'meta_value' => $current_section_url,
	);
	
	$notes = new WP_Query($args);
	
	if ( $notes->have_posts() ) {
		while ( $notes->have_posts() ) {
			$notes->the_post();
			
			$html .= '<span class="jamp-admin-bar-note-title">' . get_the_title() . '</span><br>' . get_the_content();
		}
	} else {
		$html .= '<strong>Nessuna nota in questa sezione.</strong>';
	}

	wp_reset_postdata();
	
	return $html;
?>
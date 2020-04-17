<?php
/**
 * Custom Post Types registration.
 *
 * @link       https://www.andreaporotti.it
 * @since      1.0.0
 *
 * @package    Jamp
 * @subpackage Jamp/includes
 */

/**
 * Custom Post Types registration.
 *
 * This class defines all code necessary to register Custom Post Types.
 *
 * @since      1.0.0
 * @package    Jamp
 * @subpackage Jamp/includes
 * @author     Andrea Porotti
 */
class Jamp_CPT {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function register() {
		$labels = array(
			'name'                  => esc_html_x( 'Note', 'Post Type General Name', 'jamp' ),
			'singular_name'         => esc_html_x( 'Nota', 'Post Type Singular Name', 'jamp' ),
			'menu_name'             => esc_html__( 'Note', 'jamp' ),
			'name_admin_bar'        => esc_html__( 'Nota', 'jamp' ),
			'archives'              => esc_html__( 'Archivi Note', 'jamp' ),
			'attributes'            => esc_html__( 'Attributi Nota', 'jamp' ),
			'parent_item_colon'     => esc_html__( 'Nota genitore:', 'jamp' ),
			'all_items'             => esc_html__( 'Tutte le Note', 'jamp' ),
			'add_new_item'          => esc_html__( 'Aggiungi Nuova Nota', 'jamp' ),
			'add_new'               => esc_html__( 'Aggiungi Nuova', 'jamp' ),
			'new_item'              => esc_html__( 'Nuova Nota', 'jamp' ),
			'edit_item'             => esc_html__( 'Modifica Nota', 'jamp' ),
			'update_item'           => esc_html__( 'Aggiorna Nota', 'jamp' ),
			'view_item'             => esc_html__( 'Visualizza Nota', 'jamp' ),
			'view_items'            => esc_html__( 'Visualizza Note', 'jamp' ),
			'search_items'          => esc_html__( 'Cerca Nota', 'jamp' ),
			'not_found'             => esc_html__( 'Nessuna Nota trovata', 'jamp' ),
			'not_found_in_trash'    => esc_html__( 'Nessuna Nota trovata nel Cestino', 'jamp' ),
			'featured_image'        => esc_html__( 'Immagine in evidenza', 'jamp' ),
			'set_featured_image'    => esc_html__( 'Imposta immagine in evidenza', 'jamp' ),
			'remove_featured_image' => esc_html__( 'Rimuovi immagine in evidenza', 'jamp' ),
			'use_featured_image'    => esc_html__( 'Usa come immagine in evidenza', 'jamp' ),
			'insert_into_item'      => esc_html__( 'Inserisci nella Nota', 'jamp' ),
			'uploaded_to_this_item' => esc_html__( 'Caricato su questa Nota', 'jamp' ),
			'items_list'            => esc_html__( 'Elenco Note', 'jamp' ),
			'items_list_navigation' => esc_html__( 'Navigazione elenco Note', 'jamp' ),
			'filter_items_list'     => esc_html__( 'Filtra elenco Note', 'jamp' ),
		);

		$args = array(
			'label'               => esc_html__( 'Note', 'jamp' ),
			'description'         => esc_html__( 'Una nota che può essere aggiunta alla dashboard.', 'jamp' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor' ),
			'taxonomies'          => array(),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-welcome-write-blog',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'page',
			'show_in_rest'        => false,
		);

		register_post_type( 'jamp_note', $args );
	}

}

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
			'name'                  => _x( 'Note', 'Post Type General Name', 'jamp' ),
			'singular_name'         => _x( 'Nota', 'Post Type Singular Name', 'jamp' ),
			'menu_name'             => __( 'Note', 'jamp' ),
			'name_admin_bar'        => __( 'Nota', 'jamp' ),
			'archives'              => __( 'Archivi Note', 'jamp' ),
			'attributes'            => __( 'Attributi Nota', 'jamp' ),
			'parent_item_colon'     => __( 'Nota genitore:', 'jamp' ),
			'all_items'             => __( 'Tutte le Note', 'jamp' ),
			'add_new_item'          => __( 'Aggiungi Nuova Nota', 'jamp' ),
			'add_new'               => __( 'Aggiungi Nuova', 'jamp' ),
			'new_item'              => __( 'Nuova Nota', 'jamp' ),
			'edit_item'             => __( 'Modifica Nota', 'jamp' ),
			'update_item'           => __( 'Aggiorna Nota', 'jamp' ),
			'view_item'             => __( 'Visualizza Nota', 'jamp' ),
			'view_items'            => __( 'Visualizza Note', 'jamp' ),
			'search_items'          => __( 'Cerca Nota', 'jamp' ),
			'not_found'             => __( 'Nessuna Nota trovata', 'jamp' ),
			'not_found_in_trash'    => __( 'Nessuna Nota trovata nel Cestino', 'jamp' ),
			'featured_image'        => __( 'Immagine in evidenza', 'jamp' ),
			'set_featured_image'    => __( 'Imposta immagine in evidenza', 'jamp' ),
			'remove_featured_image' => __( 'Rimuovi immagine in evidenza', 'jamp' ),
			'use_featured_image'    => __( 'Usa come immagine in evidenza', 'jamp' ),
			'insert_into_item'      => __( 'Inserisci nella Nota', 'jamp' ),
			'uploaded_to_this_item' => __( 'Caricato su questa Nota', 'jamp' ),
			'items_list'            => __( 'Elenco Note', 'jamp' ),
			'items_list_navigation' => __( 'Navigazione elenco Note', 'jamp' ),
			'filter_items_list'     => __( 'Filtra elenco Note', 'jamp' ),
		);
		
		$args = array(
			'label'                 => __( 'Note', 'jamp' ),
			'description'           => __( 'Una nota che può essere aggiunta alla dashboard.', 'jamp' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'revisions' ),
			'taxonomies'            => array(),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-welcome-write-blog',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => false,
			'exclude_from_search'   => true,
			'publicly_queryable'    => false,
			'capability_type'       => 'page',
			'show_in_rest'          => false,
		);
		
		register_post_type( 'note', $args );
	}

}

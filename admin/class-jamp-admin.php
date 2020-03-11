<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.andreaporotti.it
 * @since      1.0.0
 *
 * @package    Jamp
 * @subpackage Jamp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Jamp
 * @subpackage Jamp/admin
 * @author     Andrea Porotti
 */
class Jamp_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The list of all dashboard side menu items.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array     $version    The list of all dashboard side menu items.
	 */
	private $sections_list = array();

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string    $plugin_name    The name of this plugin.
	 * @param    string    $version        The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Jamp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Jamp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/jamp-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Jamp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Jamp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/jamp-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Creates a list of all dashboard side menu items.
	 *
	 * @since    1.0.0
	 */
	public function build_sections_list() {
		global $menu, $submenu;

		// The sections placed on the first level menu.
		$first_level_sections = array();

		// Get sections placed on the first level menu. Some names are ignored.
		foreach ( $menu as $menu_item ) {
			if ( ! in_array( $menu_item[0], array( '', 'Link' ), true ) ) {
				$first_level_sections[] = array(
					'name'       => strstr( $menu_item[0], ' <', true ) ?: $menu_item[0], // The section name ignoring any HTML content.
					'file'       => remove_query_arg( 'return', wp_kses_decode_entities( $menu_item[2] ) ), // The section file name without the "return" parameter.
					'is_submenu' => false,
				);
			}
		}

		// Build complete sections list.
		foreach ( $first_level_sections as $section ) {
			// Add current first level section to the list.
			$this->sections_list[] = $section;

			// Get the sub sections of current first level section from the sub menu.
			foreach ( $submenu[ $section['file'] ] as $submenu_item ) {
				$this->sections_list[] = array(
					'name'       => '-- ' . ( strstr( $submenu_item[0], ' <', true ) ?: $submenu_item[0] ), // The section name ignoring any HTML content.
					'file'       => remove_query_arg( 'return', wp_kses_decode_entities( $submenu_item[2] ) ), // The section file name without the "return" parameter.
					'is_submenu' => true,
				);
			}
		}
	}

	/**
	 * Adds the meta box.
	 *
	 * @since    1.0.0
	 */
	public function add_meta_box() {

		$screens = array( 'jamp_note' );
		foreach ( $screens as $screen ) {
			add_meta_box(
				'jamp_meta_box',
				__( 'Impostazioni Nota' ),
				array( $this, 'meta_box_html_cb' ),
				$screen,
				'side',
				'default',
				$this->sections_list
			);
		}

	}

	/**
	 * Outputs the meta box HTML.
	 *
	 * @since    1.0.0
	 */
	public static function meta_box_html_cb( $post, $args ) {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/jamp-admin-meta-box.php';

	}

	/**
	 * Saves meta data
	 *
	 * @since    1.0.0
	 */
	public function save_meta_data( $post_id ) {

		// Checks save status.
		$is_autosave    = wp_is_post_autosave( $post_id );
		$is_revision    = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $_POST['jamp_meta_box_nonce'] ) && wp_verify_nonce( $_POST['jamp_meta_box_nonce'], 'jamp_meta_box_nonce' ) ) ? 'true' : 'false';

		// Exits script depending on save status.
		if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
			return;
		}

		// Checks for field values and saves if needed.
		if ( isset( $_POST['scope'] ) ) {
			update_post_meta( $post_id, 'jamp_scope', $_POST['scope'] );

			if ( $_POST['scope'] === 'global' ) {
				update_post_meta( $post_id, 'jamp_target_type', 'global' );
				update_post_meta( $post_id, 'jamp_target', 'global' );
			}

			if ( $_POST['scope'] === 'section' ) {
				if ( isset( $_POST['section'] ) ) {
					update_post_meta( $post_id, 'jamp_target_type', 'section' );
					update_post_meta( $post_id, 'jamp_target', $_POST['section'] );
				}
			}

			if ( $_POST['scope'] === 'entity' ) {
				update_post_meta( $post_id, 'jamp_target_type', 'entity' );
				update_post_meta( $post_id, 'jamp_target', 'entity' );
			}
		}

	}
	
	/**
	 * Adds a column to a management page.
	 *
	 * @since    1.0.0
	 */
	function add_columns_head( $defaults ) {
		
		$post_type = get_post_type();
		
		// Adds the column skipping out post type.
		if ( $post_type !== 'jamp_note' ) {
			$defaults['jamp_note'] = __( 'Note' );
		}
		
		return $defaults;
		
	}

	/**
	 * Shows the column content.
	 *
	 * @since    1.0.0
	 */
	function show_columns_content( $column_name, $post_id ) {
		
		if ( $column_name == 'jamp_note' ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/jamp-admin-column.php';
		}
		
	}

}

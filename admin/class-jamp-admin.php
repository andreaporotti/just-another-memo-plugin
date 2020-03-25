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
	 * The list of all supported target types.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array     $version    The list of all supported target types.
	 */
	private $target_types_list = array();

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
		 * 
		 * 
		 * 
		 * function my_enqueue( $hook ) {
				if( 'myplugin_settings.php' != $hook ) return;
				wp_enqueue_script( 'ajax-script',
					plugins_url( '/js/myjquery.js', __FILE__ ),
					array( 'jquery' )
				);
			}
		 * 
		 * 
		 * 
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/jamp-admin.js', array( 'jquery' ), $this->version, false );

		wp_localize_script( $this->plugin_name, 'jamp_ajax', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( $this->plugin_name ),
		) );
		
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

		// Gets sections placed on the first level menu. Some items are ignored.
		foreach ( $menu as $menu_item ) {
			if ( ! in_array( $menu_item[0], array( '', 'Link' ), true ) && ! strpos($menu_item[2], 'jamp_note') ) {
				// Gets section file without the "return" parameter.
				$file = remove_query_arg( 'return', wp_kses_decode_entities( $menu_item[2] ) );
				
				// Generates section absolute url.
				$url = $file;
				if ( ! strpos($url, '.php')) {
					$url = '/admin.php?page=' . $url;
				}
				$url = admin_url($url);
				
				$first_level_sections[] = array(
					'name'       => strstr( $menu_item[0], ' <', true ) ?: $menu_item[0], // The section name ignoring any HTML content.
					'file'       => $file,
					'url'        => $url,
					'is_submenu' => false,
				);
			}
		}

		// Build complete sections list.
		foreach ( $first_level_sections as $section ) {
			// Add current first level section to the list.
			$this->sections_list[] = $section;

			// Gets the sub sections of current first level section from the sub menu.
			foreach ( $submenu[ $section['file'] ] as $submenu_item ) {
				// Gets section file without the "return" parameter.
				$file = remove_query_arg( 'return', wp_kses_decode_entities( $submenu_item[2] ) );
				
				// Generates section absolute url.
				$url = $file;
				if ( ! strpos($url, '.php') ) {
					$url = '/admin.php?page=' . $url;
				}
				$url = admin_url($url);
				
				$this->sections_list[] = array(
					'name'       => '-- ' . ( strstr( $submenu_item[0], ' <', true ) ?: $submenu_item[0] ), // The section name ignoring any HTML content.
					'file'       => $file,
					'url'        => $url,
					'is_submenu' => true,
				);
			}
		}
	}

	/**
	 * Creates a list of all supported target types.
	 *
	 * @since    1.0.0
	 */
	public function build_target_types_list() {

		$post_types = get_post_types(array(
			'public' => true
		), 'objects');

		foreach ($post_types as $post_type) {
			if( ! in_array( $post_type->name, array( 'attachment', 'jamp_note' ), true ) ) {
				$this->target_types_list[] = array(
					'name' => $post_type->name,
					'label' => $post_type->label,
				);
			}
		}

	}
	
	/**
	 * Creates a list of all entities of the passed post type.
	 *
	 * @since    1.0.0
	 */
	public function build_targets_list() {

		// Checks the nonce is valid.
		check_ajax_referer( $this->plugin_name );

		$post_type = $_POST['post_type'];
		
		if ( ! empty( $post_type ) ) {

			// Gets entities as objects
			$entities_objects = get_posts( array(
				'post_type' => $post_type,
				'posts_per_page' => -1,
			) );

			// Builds the actual list.
			$entities = array();
			foreach ( $entities_objects as $entity ) {
				$entities[] = array(
					'id' => $entity->ID,
					'title' => $entity->post_title,
				);
			}
			
			wp_send_json_success($entities);

		} else {
			
			wp_send_json_error('');
			
		}

	}

	/**
	 * Adds the meta box.
	 *
	 * @since    1.0.0
	 */
	public function add_meta_box() {
		
		$this->build_target_types_list();

		$screens = array( 'jamp_note' );
		foreach ( $screens as $screen ) {
			add_meta_box(
				'jamp_meta_box',
				__( 'Impostazioni Nota' ),
				array( $this, 'meta_box_html_cb' ),
				$screen,
				'side',
				'default',
				array(
					'sections'     => $this->sections_list,
					'target_types' => $this->target_types_list
				)
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

		error_log('-- save_meta_data');
		error_log('$_SESSION:');
		error_log(print_r($_SESSION, true));
		
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
				update_post_meta( $post_id, 'jamp_target_type', $_POST['target-type'] );
				update_post_meta( $post_id, 'jamp_target', $_POST['target'] );
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
		
		// Adds the column skipping the plugin custom post type.
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
	
	/**
	 * Generates current page url.
	 *
	 * @since    1.0.0
	 */
	private static function get_current_page_url() {

		return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

	}
	
	/**
	 * Checks if current section is supported by the plugin.
	 *
	 * @since    1.0.0
	 */
	private function is_section_supported() {
		
		$current_section_url = $this->get_current_page_url();
		
		foreach ( $this->sections_list as $section ) {

			if ( $section['url'] === $current_section_url ) {

				return true;

			}

		}

		return false;

	}
	
	/**
	 * Adds an item to the admin bar.
	 *
	 * @since    1.0.0
	 */
	public function add_admin_bar_menu_item() {

		global $wp_admin_bar;		

		// Main node.
		$wp_admin_bar->add_node( array(
			'id'    => 'jamp',
			'title' => '<span class="ab-icon"></span>' . __( 'Note', 'jamp' ),
			'href'  => '#',
		));

		// Content node.
		$wp_admin_bar->add_node( array(
			'id'     => 'jamp-content',
			'parent' => 'jamp',
			'title'  => require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/jamp-admin-admin-bar.php',
		));

	}
	
	/**
	 * Saves referer as return url when loading the new or edit post pages.
	 *
	 * @since    1.0.0
	 */
	public function note_form_page( $post ) {
		
		error_log('-- note_form_page');
		
		// Extract parameters from querystring.
		$current_url = $this->get_current_page_url();
		$querystring = parse_url($current_url, PHP_URL_QUERY);
		parse_str($querystring, $params);
		
		// Get post type.
		$post_type = '';
		$current_screen = get_current_screen();
		
		if ( $current_screen->action === 'add' ) { // Creating a new note.
			
			error_log('---- new note');
			
			if ( isset( $params['post_type'] ) ) {
				
				$post_type = $params['post_type'];
				
			} 

		} else { // Editing a note.
			
			error_log('---- edit note');
			
			if ( isset ( $params['post'] ) ) {
				
				$post = get_post( $params['post'] );
				$post_type = $post->post_type;
				
			}
			
		}
		
		error_log('---- post type: ' . $post_type);
		
		// Save referer in session if current post is a note.
		if ( $post_type === 'jamp_note' ) {

			$referer = wp_get_referer();
			$_SESSION['jamp_return_url'] = $referer;

			error_log('---- saved referer: ' . $referer);

		}

	}
	
	public function redirect_after_save( $location ) {
		
		error_log('-- redirect_after_save');
		
		global $post;
		
		// Perform redirection only for notes.
		if ( $post->post_type === 'jamp_note' ) {

			if ( isset( $_SESSION['jamp_return_url'] ) && !empty( $_SESSION['jamp_return_url'] ) ) {
				
				// Extract the "message" parameter value from querystring.
				$querystring = parse_url($location, PHP_URL_QUERY);
				parse_str($querystring, $params);
				$message = $params['message'];

				// Save message value in session.
				$_SESSION['jamp_message'] = $message;
				
				// Get return url.
				$return_url = $_SESSION['jamp_return_url'];
				
				// Destroy session variabile.
				unset($_SESSION['jamp_return_url']);
				
				return $return_url;
				
			} else {
				
				// Fallback to default location.
				return $location;
				
			}

		}

	}
	
	public function set_admin_notices() {
		
		error_log('-- set_admin_notices');
		error_log(print_r($_SESSION, true));
		
		if ( isset( $_SESSION['jamp_message'] ) && $_SESSION['jamp_message'] >= 0 ) {

			// Define feedback messages.
			$messages['jamp_note'] = array(
				0  => '',
				1  => __( 'Nota aggiornata.' ),
				2  => __( 'Campo personalizzato aggiornato.' ),
				3  => __( 'Campo personalizzato eliminato.' ),
				4  => __( 'Nota aggiornata.' ),
				5  => isset( $_GET['revision'] ) ? sprintf( __( 'Nota ripristinata a revisione da %s.' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
				6  => __( 'Nota pubblicata.' ),
				7  => __( 'Nota salvata.' ),
				8  => __( 'Nota inviata.' ),
				9  => __( 'Nota pianificata.' ),
				10 => __( 'Bozza della nota aggiornata.' ),
			);
			
			?>
				<div class="notice notice-success is-dismissible">
					<p><strong><?php echo $messages['jamp_note'][$_SESSION['jamp_message']]; ?></strong></p>
				</div>
			<?php
			
			// Destroy session variabile.
			unset($_SESSION['jamp_message']);
		
		}
		
	}
	
	public function session_start() {
		if ( session_status() == PHP_SESSION_NONE ) {
			session_start();
		}
	}
	
	public function session_destroy() {
		session_destroy();
	}

}

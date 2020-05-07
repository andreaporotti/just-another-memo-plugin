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
	 * Custom feedback messages for actions on notes.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array     $version    Custom feedback messages for actions on notes.
	 */
	private $feedback_messages = array();

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string $plugin_name    The name of this plugin.
	 * @param    string $version        The version of this plugin.
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

		if ( current_user_can( 'publish_jamp_notes' ) ) {
			
			wp_enqueue_style( 'jamp-admin-style', plugin_dir_url( __FILE__ ) . 'css/jamp-admin.css', array( 'wp-jquery-ui-dialog' ), $this->version, 'all' );
			
		}

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

		if ( current_user_can( 'publish_jamp_notes' ) ) {

			wp_enqueue_script( 'jamp-admin-script', plugin_dir_url( __FILE__ ) . 'js/jamp-admin.js', array( 'jquery', 'jquery-ui-dialog' ), $this->version, false );

			wp_localize_script(
				'jamp-admin-script',
				'jamp_ajax',
				array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'nonce'    => wp_create_nonce( $this->plugin_name ),
				)
			);

			wp_localize_script(
				'jamp-admin-script',
				'jamp_strings',
				array(
					'get_entities_list_error' => esc_html__( 'An error occurred while loading the items list.', 'jamp' ),
					'move_to_trash_error'     => esc_html__( 'An error occurred while moving the note to the trash.', 'jamp' ),
				)
			);

		}

	}

	/**
	 * Creates a list of all dashboard side menu items.
	 *
	 * @since    1.0.0
	 */
	public function build_sections_list() {

		if ( current_user_can( 'publish_jamp_notes' ) ) {

			global $menu, $submenu;

			// The sections placed on the first level menu.
			$first_level_sections = array();

			// Menu items to not insert in the sections list.
			$menu_items_to_skip = array(
				'wp-menu-separator',
				'menu-top menu-icon-links',
				'menu-top menu-icon-jamp_note',
			);

			// Gets sections placed on the first level menu.
			foreach ( $menu as $menu_item ) {
				if ( ! in_array( $menu_item[4], $menu_items_to_skip, true ) ) {
					// Gets section name removing unwanted HTML content and HTML code surrounding the section name.
					$name = sanitize_text_field( ( strpos( $menu_item[0], ' <' ) > 0 ) ? strstr( $menu_item[0], ' <', true ) : $menu_item[0] );

					// Gets section file without the "return" parameter.
					$file = remove_query_arg( 'return', wp_kses_decode_entities( $menu_item[2] ) );

					// Generates section absolute url.
					$url = $file;
					if ( ! strpos( $url, '.php' ) ) {
						$url = '/admin.php?page=' . $url;
					}
					$url = admin_url( $url );

					$first_level_sections[] = array(
						'name'       => $name,
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

				// Check if there are sub sections of current first level section.
				if ( isset( $submenu[ $section['file'] ] ) ) {

					// Gets the sub sections of current first level section from the sub menu.
					foreach ( $submenu[ $section['file'] ] as $submenu_item ) {
						// Gets section name removing unwanted HTML content and HTML code surrounding the section name.
						$name = '-- ' . sanitize_text_field( ( strpos( $submenu_item[0], ' <' ) > 0 ) ? strstr( $submenu_item[0], ' <', true ) : $submenu_item[0] );

						// Gets section file without the "return" parameter.
						$file = remove_query_arg( 'return', wp_kses_decode_entities( $submenu_item[2] ) );

						// Generates section absolute url.
						$url = $file;
						if ( ! strpos( $url, '.php' ) ) {
							$url = '/admin.php?page=' . $url;
						}
						$url = admin_url( $url );

						$this->sections_list[] = array(
							'name'        => $name,
							'file'        => $file,
							'url'         => $url,
							'is_submenu'  => true,
							'parent_url'  => $section['url'],
							'parent_name' => $section['name'],
						);
					}

				}
			}

		}

	}

	/**
	 * Creates a list of all supported target types.
	 *
	 * @since    1.0.0
	 */
	public function build_target_types_list() {

		$post_types = get_post_types(
			array(
				'public' => true,
			),
			'objects'
		);

		foreach ( $post_types as $post_type ) {
			if ( ! in_array( $post_type->name, array( 'attachment', 'jamp_note' ), true ) ) {

				$this->target_types_list[] = array(
					'name'          => $post_type->name,
					'label'         => $post_type->label,
					'singular_name' => $post_type->labels->singular_name,
				);
			}
		}

	}

	/**
	 * Creates a list of all entities of the passed post type. (ajax function)
	 *
	 * @since    1.0.0
	 */
	public function build_targets_list() {

		if ( current_user_can( 'publish_jamp_notes' ) ) {

			// Checks the nonce is valid.
			check_ajax_referer( $this->plugin_name );

			$post_type = ( isset( $_POST['post_type'] ) ) ? sanitize_text_field( wp_unslash( $_POST['post_type'] ) ) : '';

			if ( ! empty( $post_type ) ) {

				// Gets entities as objects.
				$entities_objects = get_posts(
					array(
						'post_type'      => $post_type,
						'posts_per_page' => -1,
						'post_status'    => array( 'publish', 'future', 'draft', 'pending', 'private', 'trash' ),
					)
				);

				// Builds the actual list.
				$entities = array();
				foreach ( $entities_objects as $entity ) {

					$post_status_obj = get_post_status_object( $entity->post_status );

					$entities[] = array(
						'id'     => $entity->ID,
						'title'  => $entity->post_title,
						'status' => $post_status_obj->label,
					);
				}

				wp_send_json_success( $entities );

			} else {

				wp_send_json_error( '' );

			}

		} else {

			wp_send_json_error( '' );

		}

	}

	/**
	 * Adds the meta box.
	 *
	 * @since    1.0.0
	 */
	public function add_meta_box() {

		if ( current_user_can( 'publish_jamp_notes' ) ) {

			$this->build_target_types_list();

			$screens = array( 'jamp_note' );
			foreach ( $screens as $screen ) {
				add_meta_box(
					'jamp_meta_box',
					esc_html__( 'Note Settings', 'jamp' ),
					array( $this, 'meta_box_html_cb' ),
					$screen,
					'side',
					'default',
					array(
						'sections'     => $this->sections_list,
						'target_types' => $this->target_types_list,
					)
				);
			}

		}

	}

	/**
	 * Outputs the meta box HTML.
	 *
	 * @since    1.0.0
	 * @param    object $post Current post.
	 * @param    array  $args Variables to be available inside the callback.
	 */
	public static function meta_box_html_cb( $post, $args ) {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/jamp-admin-meta-box.php';

	}

	/**
	 * Saves meta data
	 *
	 * @since    1.0.0
	 * @param    int $post_id Current post ID.
	 */
	public function save_meta_data( $post_id ) {

		if ( current_user_can( 'publish_jamp_notes' ) ) {

			// Checks save status and nonce.
			$is_autosave    = wp_is_post_autosave( $post_id );
			$is_revision    = wp_is_post_revision( $post_id );
			$is_nonce_valid = ( isset( $_POST['jamp-meta-box-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['jamp-meta-box-nonce'] ) ), 'jamp_meta_box_nonce_secret_action' ) ) ? true : false;

			// Exits script depending on save status and nonce.
			if ( $is_autosave || $is_revision || ! $is_nonce_valid ) {
				return;
			}

			// Checks for field values and saves if needed.
			if ( isset( $_POST['scope'] ) ) {
				update_post_meta( $post_id, 'jamp_scope', sanitize_text_field( wp_unslash( $_POST['scope'] ) ) );

				if ( 'global' === $_POST['scope'] ) {
					update_post_meta( $post_id, 'jamp_target_type', 'global' );
					update_post_meta( $post_id, 'jamp_target', 'global' );
				}

				if ( 'section' === $_POST['scope'] ) {
					if ( isset( $_POST['section'] ) ) {
						update_post_meta( $post_id, 'jamp_target_type', 'section' );
						update_post_meta( $post_id, 'jamp_target', sanitize_text_field( wp_unslash( $_POST['section'] ) ) );
					}
				}

				if ( 'entity' === $_POST['scope'] ) {
					if ( isset( $_POST['target-type'] ) ) {
						update_post_meta( $post_id, 'jamp_target_type', sanitize_text_field( wp_unslash( $_POST['target-type'] ) ) );
					}

					if ( isset( $_POST['target'] ) ) {
						update_post_meta( $post_id, 'jamp_target', sanitize_text_field( wp_unslash( $_POST['target'] ) ) );
					}
				}
			}

		}

	}

	/**
	 * Adds a column to a management page.
	 *
	 * @since    1.0.0
	 * @param    array $columns Table columns.
	 */
	public function manage_columns_headers( $columns ) {
	
		if ( current_user_can( 'publish_jamp_notes' ) ) {

			$this->build_target_types_list();

			$post_type = get_post_type();

			if ( 'jamp_note' == $post_type ) {

				// Get Date column label and remove the column.
				$date_column_label = $columns['date'];
				unset( $columns['date'] );

				// Adds custom columns for notes page.
				$columns['jamp_author']   = esc_html__( 'Author', 'jamp' );
				$columns['jamp_location'] = esc_html__( 'Scope', 'jamp' );

				// Re-add Date column at the end.
				$columns['date'] = $date_column_label;

			} else {

				// Adds custom columns for other post types, pages and target types.
				$columns['jamp_note'] = esc_html__( 'Notes', 'jamp' );

			}
		
		}

		return $columns;

	}

	/**
	 * Shows the column content.
	 *
	 * @since    1.0.0
	 * @param    string $column_name Current table column name.
	 * @param    int    $post_id     Current post ID.
	 */
	public function manage_columns_content( $column_name, $post_id ) {

		if ( current_user_can( 'publish_jamp_notes' ) ) {

			if ( strpos( $column_name, 'jamp' ) !== false ) {

				require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/jamp-admin-column.php';

			}

		}

	}

	/**
	 * Generates current page url.
	 *
	 * @since    1.0.0
	 */
	private static function get_current_page_url() {

		$url = '';

		$request_scheme = ( isset( $_SERVER['REQUEST_SCHEME'] ) ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_SCHEME'] ) ) : '';
		$http_host      = ( isset( $_SERVER['HTTP_HOST'] ) ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '';
		$request_uri    = ( isset( $_SERVER['REQUEST_URI'] ) ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';

		if ( ! empty( $request_scheme ) && ! empty( $http_host ) && ! empty( $request_uri ) ) {
			$url = $request_scheme . '://' . $http_host . $request_uri;
		}

		// If current url is the admin url, let's add "index.php" to it so it's equal to the "Home" link in the sidebar menu.
		if ( $url === admin_url() ) {
			$url .= 'index.php';
		}

		return $url;

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

		if ( current_user_can( 'publish_jamp_notes' ) ) {

			global $wp_admin_bar;

			// Main node.
			$wp_admin_bar->add_node(
				array(
					'id'    => 'jamp',
					'title' => '<span class="ab-icon"></span>' . esc_html__( 'Notes', 'jamp' ),
					'href'  => '#',
				)
			);

			// Content node.
			$wp_admin_bar->add_node(
				array(
					'id'     => 'jamp-content',
					'parent' => 'jamp',
					'title'  => require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/jamp-admin-admin-bar.php',
				)
			);

		}

	}

	/**
	 * Saves referer as return url when loading the new or edit post pages.
	 *
	 * @since    1.0.0
	 */
	public function note_form_page() {

		if ( current_user_can( 'publish_jamp_notes' ) ) {

			// Extract parameters from querystring.
			$current_url = $this->get_current_page_url();
			$querystring = wp_parse_url( $current_url, PHP_URL_QUERY );
			parse_str( $querystring, $params );

			// Get post type.
			$post_type      = '';
			$current_screen = get_current_screen();

			if ( 'add' === $current_screen->action ) { // Creating a new note.

				if ( isset( $params['post_type'] ) ) {

					$post_type = $params['post_type'];

				}
			} else { // Editing a note.

				if ( isset( $params['post'] ) ) {

					$post      = get_post( $params['post'] );
					$post_type = $post->post_type;

				}
			}

			// Save referer in session if current post is a note.
			if ( 'jamp_note' === $post_type ) {

				$referer                     = wp_get_referer();
				$_SESSION['jamp_return_url'] = $referer;

			}

		}

	}

	/**
	 * Returns to the previous page after note create or edit.
	 *
	 * @since    1.0.0
	 * @param    string $location Destination url.
	 */
	public function redirect_after_save( $location ) {

		if ( current_user_can( 'publish_jamp_notes' ) ) {

			global $post;

			// Perform redirection only for notes.
			if ( 'jamp_note' === $post->post_type ) {

				if ( isset( $_SESSION['jamp_return_url'] ) && ! empty( $_SESSION['jamp_return_url'] ) ) {

					// Extract the "message" parameter value from querystring.
					$querystring = wp_parse_url( $location, PHP_URL_QUERY );
					parse_str( $querystring, $params );
					$message = $params['message'];

					// Save message id in session.
					$_SESSION['jamp_message'] = $message;

					// Get return url.
					$return_url = $_SESSION['jamp_return_url'];

					// Destroy session variabile.
					unset( $_SESSION['jamp_return_url'] );

					return $return_url;

				} else {

					// Fallback to default location.
					return $location;

				}
			}

		} else {

			// Fallback to default location.
			return $location;

		}

	}

	/**
	 * Shows custom notices in admin pages.
	 *
	 * @since    1.0.0
	 */
	public function show_admin_notices() {

		if ( current_user_can( 'publish_jamp_notes' ) ) {

			if ( isset( $_SESSION['jamp_message'] ) && $_SESSION['jamp_message'] >= 0 ) {

				// Set custom feedback messages.
				$messages['jamp_note'] = array(
					0  => '', // Unused. Messages start at index 1.
					1  => esc_html__( 'Note updated.', 'jamp' ),
					2  => esc_html__( 'Custom field updated.', 'jamp' ),
					3  => esc_html__( 'Custom field deleted.', 'jamp' ),
					4  => esc_html__( 'Note updated.', 'jamp' ),
					5  => '', // Unused. Revisions are disabled.
					6  => esc_html__( 'Note published.', 'jamp' ),
					7  => esc_html__( 'Note saved.', 'jamp' ),
					8  => esc_html__( 'Note submitted.', 'jamp' ),
					9  => esc_html__( 'Note scheduled.', 'jamp' ),
					10 => esc_html__( 'Note draft updated.', 'jamp' ),
				);

				?>
					<div class="notice notice-success is-dismissible">
						<p><?php echo esc_html( $messages['jamp_note'][ $_SESSION['jamp_message'] ] ); ?></p>
					</div>
				<?php

				// Destroy session variabile.
				unset( $_SESSION['jamp_message'] );

			}

		}

	}

	/**
	 * Adds bulk actions custom notices.
	 *
	 * @since    1.0.0
	 * @param    array $bulk_messages Array of messages displayed in the notices.
	 * @param    array $bulk_counts   Array of item counts for each message.
	 */
	public function manage_default_bulk_notices( $bulk_messages, $bulk_counts ) {

		if ( current_user_can( 'publish_jamp_notes' ) ) {

			$bulk_messages['jamp_note'] = array(
				// translators: %s is the number of updated notes.
				'updated'   => esc_html( _n( '%s note updated.', '%s notes updated.', $bulk_counts['updated'], 'jamp' ) ),
				// translators: %s is the number of locked notes.
				'locked'    => esc_html( _n( '%s note not updated, somebody is editing it.', '%s notes not updated, somebody is editing them.', $bulk_counts['locked'], 'jamp' ) ),
				// translators: %s is the number of deleted notes.
				'deleted'   => esc_html( _n( '%s note permanently deleted.', '%s notes permanently deleted.', $bulk_counts['deleted'], 'jamp' ) ),
				// translators: %s is the number of trashed notes.
				'trashed'   => esc_html( _n( '%s note moved to the Trash.', '%s notes moved to the Trash.', $bulk_counts['trashed'], 'jamp' ) ),
				// translators: %s is the number of untrashed notes.
				'untrashed' => esc_html( _n( '%s note restored from the Trash.', '%s notes restored from the Trash.', $bulk_counts['untrashed'], 'jamp' ) ),
			);

		}

		return $bulk_messages;

	}

	/**
	 * Moves a note to trash. (ajax function)
	 *
	 * @since    1.0.0
	 */
	public function move_to_trash() {

		if ( current_user_can( 'publish_jamp_notes' ) ) {

			// Checks the nonce is valid.
			check_ajax_referer( $this->plugin_name );

			$note_id = ( isset( $_POST['note'] ) ) ? intval( wp_unslash( $_POST['note'] ) ) : 0;

			if ( ! empty( $note_id ) && current_user_can( 'delete_post', $note_id ) ) {

				$note = wp_trash_post( $note_id );

				if ( ! empty( $note ) ) {

					wp_send_json_success();

				} else {

					wp_send_json_error();

				}
			}

		} else {

			wp_send_json_error();

		}

	}

	/**
	 * Creates a TinyMCE custom configuration when editing notes.
	 *
	 * @since    1.0.0
	 * @param    array $mceInit An array with TinyMCE config.
	 */
	public function tiny_mce_before_init( $mceInit ) {

		if ( current_user_can( 'publish_jamp_notes' ) ) {

			unset( $mceInit['toolbar1'] );
			unset( $mceInit['toolbar2'] );
			unset( $mceInit['toolbar3'] );
			unset( $mceInit['toolbar4'] );

			$mceInit['wpautop']  = false;
			$mceInit['toolbar1'] = 'bold,italic,alignleft,aligncenter,alignright,link,strikethrough,hr,forecolor,pastetext,removeformat,charmap,undo,redo,wp_help';

		}

		return $mceInit;

	}

	/**
	 * Adds notes to the post types to be deleted when deleting a user.
	 *
	 * @since    1.0.0
	 * @param    array $post_types_to_delete Array of post types to delete.
	 * @param    int   $id                   User ID.
	 */
	public function post_types_to_delete_with_user( $post_types_to_delete, $id ) {

		if ( current_user_can( 'publish_jamp_notes' ) ) {

			$post_types_to_delete[] = 'jamp_note';

		}

		return $post_types_to_delete;

	}

	/**
	 * Starts PHP session.
	 *
	 * @since    1.0.0
	 */
	public function session_start() {

		if ( session_status() === PHP_SESSION_NONE ) {

			session_start();

		}

	}

	/**
	 * Clears PHP session.
	 *
	 * @since    1.0.0
	 */
	public function session_destroy() {

		session_destroy();

	}

}

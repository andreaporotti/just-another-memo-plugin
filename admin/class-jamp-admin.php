<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 * @package    Jamp
 * @subpackage Jamp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Jamp
 * @subpackage Jamp/admin
 * @author     Andrea Porotti
 */
class Jamp_Admin {

	/**
	 * The name of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The name of this plugin.
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
	 * Initializes the class and sets its properties.
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
	 * Registers the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		if ( current_user_can( 'publish_jamp_notes' ) ) {

			wp_enqueue_style( 'jamp-admin-style', plugin_dir_url( __FILE__ ) . 'css/jamp-admin.css', array( 'wp-jquery-ui-dialog' ), $this->version, 'all' );

		}

	}

	/**
	 * Registers the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

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
					'get_entities_list_error' => esc_html__( 'An error occurred while loading the items list. Please reload the page and try again.', 'jamp' ),
					'move_to_trash_error'     => esc_html__( 'An error occurred while moving the note to the trash. Please reload the page and try again.', 'jamp' ),
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
					$name = trim( sanitize_text_field( ( strpos( $menu_item[0], ' <' ) > 0 ) ? strstr( $menu_item[0], ' <', true ) : $menu_item[0] ) );

					if ( ! empty( $name ) ) {
						// Gets section file without the "return" parameter.
						$file = remove_query_arg( 'return', wp_kses_decode_entities( $menu_item[2] ) );

						// Generate section url.
						$url = wp_specialchars_decode( menu_page_url( $menu_item[2], false ) );
						if ( empty( $url ) ) {
							$url = wp_specialchars_decode( admin_url( $menu_item[2] ) );
						}

						$first_level_sections[] = array(
							'name'       => $name,
							'file'       => $file,
							'url'        => $url,
							'is_submenu' => false,
							'is_enabled' => false, // Assuming it contains sub-items, a first level item is disabled by default.
						);
					}
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
						$name = trim( sanitize_text_field( ( strpos( $submenu_item[0], ' <' ) > 0 ) ? strstr( $submenu_item[0], ' <', true ) : $submenu_item[0] ) );

						if ( ! empty( $name ) ) {
							// Gets section file without the "return" parameter.
							$file = remove_query_arg( 'return', wp_kses_decode_entities( $submenu_item[2] ) );

							// Generate section url.
							$url = wp_specialchars_decode( menu_page_url( $submenu_item[2], false ) );
							if ( empty( $url ) ) {
								$url = wp_specialchars_decode( admin_url( $submenu_item[2] ) );
							}

							$this->sections_list[] = array(
								'name'        => '-- ' . $name,
								'file'        => $file,
								'url'         => $url,
								'is_submenu'  => true,
								'is_enabled'  => true, // A sub item is enabled by default.
								'parent_url'  => $section['url'],
								'parent_name' => $section['name'],
							);
						}
					}
				} else {

					// Enable last inserted first level section because it must be selectable.
					end( $this->sections_list );
					$this->sections_list[ key( $this->sections_list ) ]['is_enabled'] = true;

				}
			}
		}

	}

	/**
	 * Creates a list of all supported target types.
	 *
	 * @since    1.0.0
	 * @param    boolean $filtered If true returns only the enabled target types.
	 * @param    boolean $return   If true returns the target types array.
	 */
	public function build_target_types_list( $filtered = true, $return = false ) {

		// Get enabled target types.
		$enabled_target_types = get_option( 'jamp_enabled_target_types', array() );

		// Get post types.
		$post_types = get_post_types(
			array(
				'public' => true,
			),
			'objects'
		);

		// Set post types to be ignored.
		$post_types_to_skip = array(
			'attachment',
			'jamp_note',
		);

		foreach ( $post_types as $post_type ) {

			if ( ! in_array( $post_type->name, $post_types_to_skip, true ) ) {

				// If we need just the enabled target types.
				if ( $filtered && ! in_array( $post_type->name, $enabled_target_types, true ) ) {
					continue;
				}

				$this->target_types_list[] = array(
					'name'          => $post_type->name,
					'label'         => $post_type->label,
					'singular_name' => $post_type->labels->singular_name,
				);

			}
		}

		if ( $return ) {
			return $this->target_types_list;
		}

	}

	/**
	 * Creates a list of all entities of a post type.
	 * It's used by ajax calls.
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

			// Get admin menu items.
			do_action( 'adminmenu' );

			// Get enabled target types.
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
	 * @param    array  $args Variables coming from the registered meta box.
	 */
	public static function meta_box_html_cb( $post, $args ) {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/jamp-admin-meta-box.php';

	}

	/**
	 * Saves meta data.
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

			// Check for field values and save them.
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
	 * Adds a custom column to an admin page.
	 *
	 * @since    1.0.0
	 * @param    array $columns List of table columns.
	 */
	public function manage_columns_headers( $columns ) {

		if ( current_user_can( 'publish_jamp_notes' ) ) {

			$post_type = $this->get_current_post_type();

			if ( 'jamp_note' === $post_type ) { // Notes admin page.

				// Get all target types.
				$this->build_target_types_list( false );

				// Get Date column label and remove the column.
				$date_column_label = $columns['date'];
				unset( $columns['date'] );

				// Adds custom columns for notes page.
				$columns['jamp_author']   = esc_html__( 'Author', 'jamp' );
				$columns['jamp_location'] = esc_html__( 'Scope', 'jamp' );

				// Re-add Date column at the end.
				$columns['date'] = $date_column_label;

			} else { // Other admin pages.

				// Get enabled target types.
				$this->build_target_types_list();

				// Create a list of target types names.
				$enabled_target_types_names = array();
				foreach ( $this->target_types_list as $target_type ) {
					$enabled_target_types_names[] = $target_type['name'];
				}

				if ( in_array( $post_type, $enabled_target_types_names, true ) ) {

					// Adds custom columns for other post types, pages and target types.
					$columns['jamp_note'] = esc_html__( 'Notes', 'jamp' );

				}
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

			// Load the file if the column name contains the word 'jamp'.
			if ( strpos( $column_name, 'jamp' ) !== false ) {

				require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/jamp-admin-column.php';

			}
		}

	}

	/**
	 * Generates current page url.
	 *
	 * @since    1.0.0
	 * @param    boolean $encode Set to true to replace some characters in the url.
	 */
	private static function get_current_page_url( $encode = false ) {

		$url = '';

		$request_scheme = ( isset( $_SERVER['REQUEST_SCHEME'] ) ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_SCHEME'] ) ) : '';
		$http_host      = ( isset( $_SERVER['HTTP_HOST'] ) ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '';
		$script_name    = ( isset( $_SERVER['SCRIPT_NAME'] ) ) ? sanitize_text_field( wp_unslash( $_SERVER['SCRIPT_NAME'] ) ) : '';
		$query_string   = ( isset( $_SERVER['QUERY_STRING'] ) ) ? sanitize_text_field( wp_unslash( $_SERVER['QUERY_STRING'] ) ) : '';

		if ( ! empty( $request_scheme ) && ! empty( $http_host ) && ! empty( $script_name ) ) {
			$url .= $request_scheme . '://' . $http_host . $script_name;

			if ( ! empty( $query_string ) ) {
				$url .= '?' . $query_string;
			}
		}

		// If current url is the admin url, let's add "index.php" to it so it's equal to the "Home" link in the sidebar menu.
		if ( admin_url() === $url ) {
			$url .= 'index.php';
		}

		// Replace '&' with '|' to prevent parse errors on wp_parse_url.
		if ( $encode ) {
			$url = str_replace( '&', '|', $url );
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
	 * Returns to the previous page after note save.
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
	 * Adds custom notices for bulk actions.
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
	 * Moves a note to trash.
	 * It's used by ajax calls.
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
	 * @param    array $mce_init An array with TinyMCE config.
	 */
	public function tiny_mce_before_init( $mce_init ) {

		if ( current_user_can( 'publish_jamp_notes' ) ) {

			if ( 'jamp_note' === $this->get_current_post_type() ) {

				unset( $mce_init['toolbar1'] );
				unset( $mce_init['toolbar2'] );
				unset( $mce_init['toolbar3'] );
				unset( $mce_init['toolbar4'] );

				$mce_init['wpautop']  = false;
				$mce_init['toolbar1'] = 'bold,italic,alignleft,aligncenter,alignright,link,strikethrough,hr,forecolor,pastetext,removeformat,charmap,undo,redo,wp_help';

			}
		}

		return $mce_init;

	}

	/**
	 * Adds the notes to the post types to be deleted when deleting a user.
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
	 * Gets current post type.
	 *
	 * @since    1.0.0
	 */
	private static function get_current_post_type() {

		global $post, $typenow, $current_screen;

		if ( $post && $post->post_type ) {
			return $post->post_type;
		} elseif ( $typenow ) {
			return $typenow;
		} elseif ( $current_screen && $current_screen->post_type ) {
			return $current_screen->post_type;
		} elseif ( isset( $_REQUEST['post_type'] ) ) {
			return sanitize_key( $_REQUEST['post_type'] );
		}

		return null;

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

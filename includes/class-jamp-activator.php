<?php
/**
 * Fired during plugin activation
 *
 * @link       https://www.andreaporotti.it
 * @since      1.0.0
 *
 * @package    Jamp
 * @subpackage Jamp/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Jamp
 * @subpackage Jamp/includes
 * @author     Andrea Porotti
 */
class Jamp_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		// Initialize plugin options.
		if ( false === get_option( 'jamp_delete_data_on_uninstall' ) ) {
			add_option( 'jamp_delete_data_on_uninstall', 0, '', 'no' );
		}

		if ( false === get_option( 'jamp_permissions' ) ) {
			// Set default permissions.
			$permissions = array(
				array(
					'role_slug'         => 'administrator',
					'role_capabilities' => Jamp::$capabilities,
				),
			);

			add_option( 'jamp_permissions', $permissions, '', 'no' );

			// Add capabilities to default roles.
			foreach ( $permissions as $permission ) {
				$role = get_role( $permission['role_slug'] );

				foreach ( $permission['role_capabilities'] as $capability ) {
					$role->add_cap( $capability );
				}
			}
		}

		if ( false === get_option( 'jamp_enabled_target_types' ) ) {
			// Get the target types.
			$admin        = new Jamp_Admin( '', '' );
			$target_types = $admin->build_target_types_list( false, true );

			// Create a list of target types names.
			$target_types_names = array();
			foreach ( $target_types as $target_type ) {
				$target_types_names[] = $target_type['name'];
			}

			add_option( 'jamp_enabled_target_types', $target_types_names, '', 'no' );
		}

	}

}

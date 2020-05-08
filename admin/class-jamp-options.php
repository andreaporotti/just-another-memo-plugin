<?php
/**
 * The options-specific functionality of the plugin.
 *
 * @link       https://www.andreaporotti.it
 * @since      1.0.0
 *
 * @package    Jamp
 * @subpackage Jamp/admin
 */

/**
 * The options-specific functionality of the plugin.
 *
 * Configures the options page and registers the settings.
 *
 * @package    Jamp
 * @subpackage Jamp/admin
 * @author     Andrea Porotti
 */
class Jamp_Options {

	/**
	 * Adds the plugin options menu item under the Settings item.
	 *
	 * @since    1.0.0
	 */
	public function options_menu() {

		add_options_page(
			esc_html__( 'JAMP Settings', 'jamp' ),
			'JAMP',
			'manage_options',
			'jamp_options',
			array(
				$this,
				'options_page',
			)
		);

	}

	/**
	 * Adds the plugin options page.
	 *
	 * @since    1.0.0
	 */
	public function options_page() {

		// Check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {

			return;

		}

		// Load page code.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/jamp-admin-options-page.php';

	}

	/**
	 * Adds the plugin options to the options page.
	 *
	 * @since    1.0.0
	 */
	public function options_init() {

		// Add a new section.
		add_settings_section(
			'jamp_options_section_target_types',
			esc_html__( 'Item types', 'jamp' ),
			array(
				$this,
				'options_section_target_types',
			),
			'jamp_options'
		);

		// Register a setting.
		register_setting(
			'jamp_options',
			'jamp_enabled_target_types',
			array(
				'type'              => 'array',
				'show_in_rest'      => false,
				'default'           => array(),
				'sanitize_callback' => array(
					$this,
					'option_enabled_target_types_sanitize',
				),
			)
		);

		// Add setting field to the section.
		add_settings_field(
			'jamp_enabled_target_types',
			esc_html__( 'Enable notes for these item types', 'jamp' ),
			array(
				$this,
				'option_enabled_target_types',
			),
			'jamp_options',
			'jamp_options_section_target_types',
			array(
				'name' => 'jamp_enabled_target_types',
			)
		);

		/* ====================================================== */

		// Add a new section.
		add_settings_section(
			'jamp_options_section_uninstall',
			esc_html__( 'Uninstall', 'jamp' ),
			array(
				$this,
				'options_section_uninstall',
			),
			'jamp_options'
		);

		// Register a setting.
		register_setting(
			'jamp_options',
			'jamp_delete_data_on_uninstall',
			array(
				'type'              => 'boolean',
				'show_in_rest'      => false,
				'default'           => 0,
				'sanitize_callback' => array(
					$this,
					'option_delete_data_on_uninstall_sanitize',
				),
			)
		);

		// Add setting field to the section.
		add_settings_field(
			'jamp_delete_data_on_uninstall',
			esc_html__( 'Remove all data on plugin uninstall', 'jamp' ),
			array(
				$this,
				'option_delete_data_on_uninstall',
			),
			'jamp_options',
			'jamp_options_section_uninstall',
			array(
				'label_for' => 'jamp_delete_data_on_uninstall',
			)
		);

	}

	/**
	 * Callback for the target types options section output.
	 *
	 * @since    1.0.0
	 * @param    array $args Array of section attributes.
	 */
	public function options_section_target_types( $args ) {

		?>
		<p id="<?php echo esc_attr( $args['id'] ); ?>">
			<?php echo esc_html__( 'Settings about the item types discovered by the plugin.', 'jamp' ); ?>
		</p>
		<?php

	}

	/**
	 * Callback for the enabled_target_types option field output.
	 *
	 * @since    1.0.0
	 * @param    array $args Array of field attributes.
	 */
	public function option_enabled_target_types( $args ) {

		// Get the option value.
		$option_enabled_target_types = get_option( $args['name'], array() );

		// Get the target types.
		$admin        = new Jamp_Admin( '', '' );
		$target_types = $admin->build_target_types_list( false, true );

		?>
		<?php foreach ( $target_types as $target_type ) : ?>
			<?php $id_attr = $args['name'] . '_' . $target_type['name']; ?>
			<?php $checked_attr = ( in_array( $target_type['name'], $option_enabled_target_types, true ) ) ? 'checked' : ''; ?>
			<input type="checkbox" id="<?php echo esc_attr( $id_attr ); ?>" name="<?php echo esc_attr( $args['name'] ); ?>[]" value="<?php echo esc_attr( $target_type['name'] ); ?>" <?php echo esc_attr( $checked_attr ); ?>>
			<label for="<?php echo esc_attr( $id_attr ); ?>"><?php echo esc_html( $target_type['label'] ); ?></label>
			<br>
		<?php endforeach; ?>

		<p class="description">
			<?php echo esc_html__( 'Choose which items you want to add notes to.', 'jamp' ); ?>
		</p>
		<?php
	}

	/**
	 * Callback for the enabled_target_types option value sanitization.
	 *
	 * @since    1.0.0
	 * @param    array $value Option value.
	 */
	public function option_enabled_target_types_sanitize( $value ) {

		if ( empty( $value ) ) {
			$value = array();
		}

		return $value;

	}

	/**
	 * Callback for the uninstall options section output.
	 *
	 * @since    1.0.0
	 * @param    array $args Array of section attributes.
	 */
	public function options_section_uninstall( $args ) {

		?>
		<p id="<?php echo esc_attr( $args['id'] ); ?>">
			<?php echo esc_html__( 'These settings are applied when you uninstall the plugin.', 'jamp' ); ?>
		</p>
		<?php

	}

	/**
	 * Callback for the delete_data_on_uninstall option field output.
	 *
	 * @since    1.0.0
	 * @param    array $args Array of field attributes.
	 */
	public function option_delete_data_on_uninstall( $args ) {

		// Get the option value.
		$option_delete_data_on_uninstall = get_option( $args['label_for'], 0 );

		?>
		<input type="checkbox" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="<?php echo esc_attr( $args['label_for'] ); ?>" value="1" <?php checked( $option_delete_data_on_uninstall, 1 ); ?>>
		<p class="description">
			<?php echo esc_html__( 'Enabling this option all notes and settings will be PERMANENTLY DELETED when you uninstall the plugin.', 'jamp' ); ?>
		</p>
		<?php

	}

	/**
	 * Callback for the delete_data_on_uninstall option value sanitization.
	 *
	 * @since    1.0.0
	 * @param    string $value Option value.
	 */
	public function option_delete_data_on_uninstall_sanitize( $value ) {

		if ( '1' !== $value ) {
			return 0;
		}

		return $value;

	}

}

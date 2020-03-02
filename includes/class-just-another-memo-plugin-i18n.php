<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.andreaporotti.it
 * @since      1.0.0
 *
 * @package    Just_Another_Memo_Plugin
 * @subpackage Just_Another_Memo_Plugin/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Just_Another_Memo_Plugin
 * @subpackage Just_Another_Memo_Plugin/includes
 * @author     Andrea Porotti <info@andreaporotti.it>
 */
class Just_Another_Memo_Plugin_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'just-another-memo-plugin',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}

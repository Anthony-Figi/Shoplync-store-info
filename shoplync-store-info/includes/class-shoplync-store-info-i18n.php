<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.anthonygfigueroa.ca
 * @since      1.0.0
 *
 * @package    Shoplync_Store_Info
 * @subpackage Shoplync_Store_Info/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Shoplync_Store_Info
 * @subpackage Shoplync_Store_Info/includes
 * @author     Anthony Figueroa <mail@anthonygfigueroa.ca>
 */
class Shoplync_Store_Info_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'shoplync-store-info',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}

<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.anthonygfigueroa.ca
 * @since             1.0.0
 * @package           Shoplync_Store_Info
 *
 * @wordpress-plugin
 * Plugin Name:       Shoplync Store Information Plugin
 * Plugin URI:        https://www.anthonygfigueroa.ca
 * Description:       Used by the custom Shoplync theme to enable various features sitewide.
 * Version:           1.6.8
 * Author:            Anthony Figueroa
 * Author URI:        https://www.anthonygfigueroa.ca
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       shoplync-store-info
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'SHOPLYNC_PATH', plugin_dir_path( __FILE__ ) );
/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SHOPLYNC_STORE_INFO_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-shoplync-store-info-activator.php
 */
function activate_shoplync_store_info() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-shoplync-store-info-activator.php';
	Shoplync_Store_Info_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-shoplync-store-info-deactivator.php
 */
function deactivate_shoplync_store_info() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-shoplync-store-info-deactivator.php';
	Shoplync_Store_Info_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_shoplync_store_info' );
register_deactivation_hook( __FILE__, 'deactivate_shoplync_store_info' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-shoplync-store-info.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_shoplync_store_info() {

	$plugin = new Shoplync_Store_Info();
	$plugin->run();

}
run_shoplync_store_info();

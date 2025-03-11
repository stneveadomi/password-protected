<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://neveadomi.com
 * @since             1.0.0
 * @package           PPPTNSE
 *
 * @wordpress-plugin
 * Plugin Name:       Password Protected Plugin Thats Not Stupidly Expensive
 * Plugin URI:        http://neveadomi.com/password-protected-plugin-thats-not-stupidly-expensive
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Steven Neveadomi
 * Author URI:        http://neveadomi.com/
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PASSWORD_PROTECTED_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-password-protected-activator.php
 */
function activate_password_protected() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-password-protected-activator.php';
	PPPTNSE_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-password-protected-deactivator.php
 */
function deactivate_password_protected() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-password-protected-deactivator.php';
	PPPTNSE_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_password_protected' );
register_deactivation_hook( __FILE__, 'deactivate_password_protected' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-password-protected.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_password_protected() {

	$plugin = new PPPTNSE();
	$plugin->run();

}
run_password_protected();


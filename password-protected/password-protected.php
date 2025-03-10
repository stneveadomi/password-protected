<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Plugin_Name
 *
 * @wordpress-plugin
 * Plugin Name:       WordPress Plugin Boilerplate
 * Plugin URI:        http://example.com/plugin-name-uri/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Your Name or Your Company
 * Author URI:        http://example.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       plugin-name
 * Domain Path:       /languages
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
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin-name-activator.php';
	Plugin_Name_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin-name-deactivator.php';
	Plugin_Name_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_plugin_name' );
register_deactivation_hook( __FILE__, 'deactivate_plugin_name' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-plugin-name.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_plugin_name() {

	$plugin = new Plugin_Name();
	$plugin->run();

}
run_plugin_name();

function set_password_protected_cookie($days, $cookie_value) {
    $cookie_name = 'password_protected';
    $expiration = time() + (86400 * $days); // 86400 seconds in a day
    setcookie($cookie_name, $cookie_value, $expiration, "/");
}

function is_password_protected_cookie_valid($password) {
    global $wpdb;
    $cookie_name = 'password_protected';

    if (isset($_COOKIE[$cookie_name])) {
        $cookie_value = $_COOKIE[$cookie_name];

        // Query the database for the list of valid passwords
        $table_name = $wpdb->prefix . 'password_protected';
        $query = $wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE password = %s", $cookie_value);
        $count = $wpdb->get_var($query);

        // Check if the cookie value matches any password in the database
        if ($count > 0 && $cookie_value === $password) {
            return true;
        }
    }

    return false;
}

function add_password_to_table($password) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'password_protected';

    // Insert the new password into the database
    $wpdb->insert(
        $table_name,
        array(
            'password' => $password
        ),
        array(
            '%s'
        )
    );
}


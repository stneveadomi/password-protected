<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    PPPTNSE
 * @subpackage PPPTNSE/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    PPPTNSE
 * @subpackage PPPTNSE/admin
 * @author     Your Name <email@example.com>
 */
class PPPTNSE_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $password_protected    The ID of this plugin.
	 */
	private $password_protected;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $password_protected       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $password_protected, $version ) {

		$this->password_protected = $password_protected;
		$this->version = $version;

	}

	public function ppptnse_add_admin_menu() {
		add_submenu_page(
			'tools.php', // Parent slug
			'Password Protected', // Page title
			'Password Protected', // Menu title
			'manage_options', // Capability
			'password-protected', // Menu slug
			array($this, 'ppptnse_display_admin_page') // Function to display the page
		);
	}
	
	public function ppptnse_display_admin_page() {
		include plugin_dir_path(__FILE__) . 'partials/password-protected-admin-display.php';
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
		 * defined in PPPTNSE_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The PPPTNSE_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->password_protected, plugin_dir_url( __FILE__ ) . 'css/password-protected-admin.css', array(), $this->version, 'all' );
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
		 * defined in PPPTNSE_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The PPPTNSE_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->password_protected, plugin_dir_url( __FILE__ ) . 'js/password-protected-admin.js', array( 'jquery' ), $this->version, false );

	}

}

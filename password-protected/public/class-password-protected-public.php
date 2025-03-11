<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    PPPTNSE
 * @subpackage PPPTNSE/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    PPPTNSE
 * @subpackage PPPTNSE/public
 * @author     Your Name <email@example.com>
 */
class PPPTNSE_Public {

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
	 * @param      string    $password_protected       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $password_protected, $version ) {

		$this->password_protected = $password_protected;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->password_protected, plugin_dir_url( __FILE__ ) . 'css/password-protected-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->password_protected, plugin_dir_url( __FILE__ ) . 'js/password-protected-public.js', array( 'jquery' ), $this->version, false );

	}

	public function is_password_check_needed() {
		return !is_user_logged_in() && !is_page('login');
	}

	private function get_passwords_by_page_id($page_id) {
		global $wpdb;
		$query = $wpdb->prepare("SELECT password FROM {$wpdb->prefix}password_protected WHERE page_id = %d", $page_id);
		return $wpdb->get_col($query);
	}

	public function check_if_password_needed($template) {
		if ($this->is_password_check_needed()) {
			$page_id = get_the_ID();
			$passwords = $this->get_passwords_by_page_id($page_id);

			if (!empty($passwords)) {
				if (isset($_COOKIE['password_protected_password'])) {
					$cookie_password = $_COOKIE['password_protected_password'];
					if (!in_array($cookie_password, $passwords)) {
						$template = plugin_dir_path(dirname(__FILE__)) . 'public/partials/no-content-template.php';
					}
				}
			}
		}
		return $template;
	}

	public function filter_pages($args = array()) {
		$pages = get_pages($args);
		foreach ($pages as $key => $page) {
			if ($this->is_password_check_needed()) {
				$passwords = $this->get_passwords_by_page_id($page->ID);

				if (!empty($passwords)) {
					if (isset($_COOKIE['password_protected_password'])) {
						$cookie_password = $_COOKIE['password_protected_password'];
						if (!in_array($cookie_password, $passwords)) {
							unset($pages[$key]);
						}
					} else {
						unset($pages[$key]);
					}
				}
			}
		}
		return $pages;
	}

}

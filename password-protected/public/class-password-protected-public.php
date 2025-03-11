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
		add_shortcode('pp_submit_password', array($this, 'submit_password_shortcode'));
		
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

	public function submit_password_shortcode() {
		ob_start();
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['post_password'])) {
			$post_password = sanitize_text_field($_POST['post_password']);

			setcookie('password_protected_password', $post_password, time() + 864000, '/');
			exit;
		}
	
		
		?>
		<?php if (isset($_COOKIE['password_protected_password'])): ?>
			<p>Password: <?php echo htmlspecialchars($_COOKIE['password_protected_password']); ?></p>
			<p>Expires: <?php echo date('Y-m-d H:i:s', $_COOKIE['password_protected_password'] + 864000); ?></p>
		<?php endif; ?>
		<form method="post" action="">
			<label for="post_password">Enter Password:</label>
			<input type="password" name="post_password" id="post_password" required>
			<input type="submit" value="Submit">
		</form>
		<?php
		return ob_get_clean();
	}

	public function is_password_check_needed() {
		return !is_user_logged_in() && !is_page('login');
	}

	private function get_passwords_by_page_id($page_id) {
		global $wpdb;
		$query = $wpdb->prepare("SELECT password FROM {$wpdb->prefix}password_protected WHERE post_id = %d", $page_id);
		return $wpdb->get_col($query);
	}

	public function filter_post($post) {
		if ($this->is_password_check_needed()) {
			$passwords = $this->get_passwords_by_page_id($post->ID);

			if (!empty($passwords)) {
				if (isset($_COOKIE['password_protected_password'])) {
					$cookie_password = $_COOKIE['password_protected_password'];
					if (!in_array($cookie_password, $passwords)) {
						wp_redirect(site_url('login'));
						exit;
					}
				} else {
					wp_redirect(site_url('login'));
					exit;
				}
			}
		}
		return $post;
	}

	public function filter_pages($pages) {
		
		for($i = count($pages) - 1; $i >= 0; $i--) {
			$page = $pages[$i];
			if ($this->is_password_check_needed()) {
				$passwords = $this->get_passwords_by_page_id($page->ID);

				if (!empty($passwords)) {
					if (isset($_COOKIE['password_protected_password'])) {
						$cookie_password = $_COOKIE['password_protected_password'];
						if (!in_array($cookie_password, $passwords)) {
							unset($pages[$i]);
						}
					} else {
						unset($pages[$i]);
					}
				}
			}
		}
		return array_values($pages);
	}

	public function filter_content($content) {
		if (in_the_loop() && is_main_query() ) {
			if ($this->is_password_check_needed()) {
				global $post;
				$passwords = $this->get_passwords_by_page_id($post->ID);

				if (!empty($passwords)) {
					if (isset($_COOKIE['password_protected_password'])) {
						$cookie_password = $_COOKIE['password_protected_password'];
						if (!in_array($cookie_password, $passwords)) {
							return __('This content is password protected. Please enter the password to view it.', 'password-protected');
						}
					} else {
						return __('This content is password protected. Please enter the password to view it.', 'password-protected');
					}
				}
			}
		}
		return $content;
	}

	public function filter_posts($posts, $queries) {
		return $this->filter_pages($posts);
	}

}

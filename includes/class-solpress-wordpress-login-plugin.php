<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://profiles.wordpress.org/solpressplugins/
 * @since      1.0.0
 *
 * @package    Solpress_Wordpress_Login_Plugin
 * @subpackage Solpress_Wordpress_Login_Plugin/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Solpress_Wordpress_Login_Plugin
 * @subpackage Solpress_Wordpress_Login_Plugin/includes
 * @author     Solpress <https://profiles.wordpress.org/solpressplugins/>
 */
class Solpress_Wordpress_Login_Plugin {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Solpress_Wordpress_Login_Plugin_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'SOLPRESS_WORDPRESS_LOGIN_PLUGIN_VERSION' ) ) {
			$this->version = SOLPRESS_WORDPRESS_LOGIN_PLUGIN_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'solpress-wordpress-login';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_shortcode_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Solpress_Wordpress_Login_Plugin_Loader. Orchestrates the hooks of the plugin.
	 * - Solpress_Wordpress_Login_Plugin_i18n. Defines internationalization functionality.
	 * - Solpress_Wordpress_Login_Plugin_Admin. Defines all hooks for the admin area.
	 * - Solpress_Wordpress_Login_Plugin_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-solpress-wordpress-login-plugin-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-solpress-wordpress-login-plugin-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-solpress-wordpress-login-plugin-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-solpress-wordpress-login-plugin-public.php';

		$this->loader = new Solpress_Wordpress_Login_Plugin_Loader();

		/**
		 * This class is responsible for shortcodes
		 */
		if ( is_readable( SOLPRESS_WORDPRESS_LOGIN_PLUGIN_PATH . 'includes/class-solpress-wordpress-login-plugin-shortcodes.php' ) ) {
			require_once SOLPRESS_WORDPRESS_LOGIN_PLUGIN_PATH . 'includes/class-solpress-wordpress-login-plugin-shortcodes.php';
		}

		/**
		 * This class is responsible for login/register of crypto wallet users
		 */
		if ( is_readable( SOLPRESS_WORDPRESS_LOGIN_PLUGIN_PATH . 'includes/class-solpress-wordpress-login-plugin-crypto-wallet-user.php' ) ) {
			require_once SOLPRESS_WORDPRESS_LOGIN_PLUGIN_PATH . 'includes/class-solpress-wordpress-login-plugin-crypto-wallet-user.php';
		}

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Solpress_Wordpress_Login_Plugin_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Solpress_Wordpress_Login_Plugin_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Solpress_Wordpress_Login_Plugin_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'login_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'login_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'solpress_login_menu' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'admin_init' );
		$this->loader->add_action( 'plugin_action_links_' . plugin_basename( SOLPRESS_WORDPRESS_LOGIN_PLUGIN_BASE_FILE ), $plugin_admin, 'add_plugin_action_links');

		//adding user meta field
		$this->loader->add_action( 'show_user_profile', $plugin_admin, 'solpress_login_add_user_meta' );
		$this->loader->add_action( 'edit_user_profile', $plugin_admin, 'solpress_login_add_user_meta' );
        //adding user meta field
		$this->loader->add_action( 'personal_options_update', $plugin_admin, 'solpress_login_save_user_meta' );
		$this->loader->add_action( 'edit_user_profile_update', $plugin_admin, 'solpress_login_save_user_meta' );

		global $pagenow;
		global $user_id;
        $current_user_id = get_current_user_id();
		
		//why the === not working although they have the same type -> int
		if( intval( $user_id ) === intval( $current_user_id ) && 'profile.php' === $pagenow ) {

			$this->loader->add_action( 'show_user_profile', $plugin_admin, 'solpress_login_button_add_user_meta' );
			$this->loader->add_action( 'edit_user_profile', $plugin_admin, 'solpress_login_button_add_user_meta' );
		}

		//include the file in the backend also
		//for the button in the user profile
		if ( is_readable( plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-solpress-wordpress-login-plugin-crypto-wallet-user.php' ) ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-solpress-wordpress-login-plugin-crypto-wallet-user.php';

			if ( class_exists( 'Solpress_Wordpress_Login_Plugin_Crypto_Wallet_User' ) ) {
				$this->user = new Solpress_Wordpress_Login_Plugin_Crypto_Wallet_User( $this->get_plugin_name(), $this->get_version() );

				$this->loader->add_action( 'wp_ajax_nopriv_public_key', $this->user, 'get_public_key' );
				$this->loader->add_action( 'wp_ajax_public_key', $this->user, 'get_public_key' );
				
				/**
				 * Check if WooCommerce is active
				 * 
				 **/
					$this->loader->add_action( 'init', $this->user, 'get_links' ); 

			}
		}


	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Solpress_Wordpress_Login_Plugin_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		
		if ( is_readable( plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-solpress-wordpress-login-plugin-crypto-wallet-user.php' ) ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-solpress-wordpress-login-plugin-crypto-wallet-user.php';

			if ( class_exists( 'Solpress_Wordpress_Login_Plugin_Crypto_Wallet_User' ) ) {
				$this->user = new Solpress_Wordpress_Login_Plugin_Crypto_Wallet_User( $this->get_plugin_name(), $this->get_version() );

				$this->loader->add_action( 'wp_ajax_nopriv_public_key', $this->user, 'get_public_key' );
				$this->loader->add_action( 'wp_ajax_public_key', $this->user, 'get_public_key' );
				
				/**
				 * Check if WooCommerce is active
				 * 
				 **/
					$this->loader->add_action( 'init', $this->user, 'get_links' ); 
				
			}
		}

		$this->loader->add_action( 'login_form', $plugin_public,'sign_in_with_crypto_wallet');
		$this->loader->add_action( 'register_form', $plugin_public,'register_in_with_crypto_wallet' );
		
		/**
         * Check if WooCommerce is active
         * 
         **/
        if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			
			$this->loader->add_action( 'woocommerce_login_form', $plugin_public,'action_woocommerce_login_form'); 
		    $this->loader->add_action( 'woocommerce_register_form', $plugin_public,'action_woocommerce_register_form'); 
			$this->loader->add_action( 'woocommerce_edit_account_form', $plugin_public,'action_woocommerce_edit_account'); 

		}
		

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Solpress_Wordpress_Login_Plugin_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}


	
	/**
	 * Defining all shortcodes for the plugin
	 * @see add_shortcode( string $tag, callable $callback )
	 */
	public function define_shortcode_hooks() {

		$plugin_shortcodes = new Solpress_Wordpress_Login_Plugin_Shortcodes(
			$this->plugin_name,
			$this->version
		);

		add_shortcode( 'solpress_login_button', array( $plugin_shortcodes, 'solpress_wordpress_login_plugin_login_button' ) );


	}

}

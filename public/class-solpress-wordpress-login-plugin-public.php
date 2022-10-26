<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://profiles.wordpress.org/solpressplugins/
 * @since      1.0.0
 *
 * @package    Solpress_Wordpress_Login_Plugin
 * @subpackage Solpress_Wordpress_Login_Plugin/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Solpress_Wordpress_Login_Plugin
 * @subpackage Solpress_Wordpress_Login_Plugin/public
 * @author     Solpress <https://profiles.wordpress.org/solpressplugins/>
 */
class Solpress_Wordpress_Login_Plugin_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
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
		 * defined in Solpress_Wordpress_Login_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Solpress_Wordpress_Login_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, SOLPRESS_WORDPRESS_LOGIN_PLUGIN_URL . 'public/css/solpress-wordpress-login-plugin-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'solpress_login_main.min.css', SOLPRESS_WORDPRESS_LOGIN_PLUGIN_URL . 'public/front/build/static/css/main.min.css', array(), $this->version, 'all' );

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
		 * defined in Solpress_Wordpress_Login_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Solpress_Wordpress_Login_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, SOLPRESS_WORDPRESS_LOGIN_PLUGIN_URL . 'public/js/solpress-wordpress-login-plugin-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'solpress_login_main.min.js', SOLPRESS_WORDPRESS_LOGIN_PLUGIN_URL . 'public/front/build/static/js/main.min.js', array( 'jquery' ), $this->version, true );


		//test path: public/js/solpress-wordpress-login-plugin-public.js
		//actual path: 
		wp_localize_script(
			'solpress_login_main.min.js',
			'solpress_wordpress_vars',
			array(
				'ajax_url'           => admin_url( 'admin-ajax.php' ),
				'action_login_button'             => 'public_key',
				'security'         => wp_create_nonce( 'solpress_wordpress_login_plugin_public_key_nonce' ),
				
				'general_error'      => __( 'Something went wrong!','solpress-wordpress-login' ),
			)
		);

	}

	/**
	* Generic function to display the button for the shortcode.
	*
	* @param string $label text to be displayed on the button.
	* @param string $default_value default text to be displayed on the button, if options is empty.
	* @param string $page_source the page that the request is coming from.
	* @param string $id a unique identifier for the section that the button is displayed in.
	*
	* @author  WEBW
	* @since   1.0.0
	* @return  void
	*/

	public function display_short_code($label, $default_value, $page_source, $id ) {

		$label = esc_attr(preg_replace("/\\\+/", "", $label));
		$label = (!empty($label)) ? sanitize_text_field( $label ) : __( $default_value, 'solpress-wordpress-login');
		$button_text = $label;
		
		$shortcode = sprintf(
			'[solpress_login_button label="%s" page="%s" id="%s"]',
			$button_text,
			$page_source,
			$id

		);
		echo do_shortcode( $shortcode );
    }


	
	/**
	* Display the button for the shortcode in login page.
	*
	* @author  WEBW
	* @since   1.0.0
	* @return  void
	*/
	public function sign_in_with_crypto_wallet() {

		$label = get_option('swl-login-button-text');
		$default_value = 'Login with your Crypto Wallet';
		$page_source = 'login';
		$id = 'solana-login';

		$this->display_short_code($label, $default_value, $page_source, $id );
    }

	/**
	* Display the button for the shortcode in registration page.
	*
	* @author  WEBW
	* @since   1.0.0
	* @return  void
	*/

	public function register_in_with_crypto_wallet() {

		$label = $label = get_option('swl-register-button-text');
		$default_value = 'Register with your Crypto Wallet';
		$page_source = 'register';
		$id = 'solana-register';

		$this->display_short_code($label, $default_value, $page_source, $id );
    }

	/**
	* Display the button for the shortcode in woocommerce login page.
	*
	* @author  WEBW
	* @since   1.0.0
	* @return  void
	*/
	public function action_woocommerce_login_form() {

		$label = get_option('swl-login-button-text');
		$default_value = 'Login with your Crypto Wallet';
		$page_source = 'wc-login';
		$id = 'wc-solana-login';

		$this->display_short_code($label, $default_value, $page_source, $id );
    }

	/**
	* Display the button for the shortcode in woocommerce register page.
	*
	* @author  WEBW
	* @since   1.0.0
	* @return  void
	*/

	public function action_woocommerce_register_form() {

		$label = get_option('swl-register-button-text');
		$default_value = 'Register with your Crypto Wallet';
		$page_source = 'wc-register';
		$id = 'wc-solana-register';

		$this->display_short_code($label, $default_value, $page_source, $id );
    }




	/**
	* Display the button for the shortcode in woocommerce account page.
	*
	* @author  WEBW
	* @since   1.0.0
	* @return  void
	*/

	public function action_woocommerce_edit_account() {
		?>
		<h3><?php _e('Crypto Wallet', 'solpress-wordpress-login'); ?></h3>
		
		<span class="description"><?php _e('After linking a crypto wallet to your account, you can use it to log in.', 'solpress-wordpress-login'); ?></span>
	    <br/>
			<?php 

	            $label = get_option('swl-user-profile-button-text');
				$default_value = 'Link your Crypto Wallet';
                $page_source = 'wc-profile';
                $id = 'solana-wc-profile';
		
				$this->display_short_code($label, $default_value, $page_source, $id );
			
			?>
	<?php }
}

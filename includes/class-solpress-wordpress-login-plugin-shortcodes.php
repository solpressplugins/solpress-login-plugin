<?php

/**
 * The file that defines the shortcodes
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
 * The shortcode class
 *
 *
 * @since      1.0.0
 * @package    Solpress_Wordpress_Login_Plugin
 * @subpackage Solpress_Wordpress_Login_Plugin/includes
 * @author     Solpress <https://profiles.wordpress.org/solpressplugins/>
 */
class Solpress_Wordpress_Login_Plugin_Shortcodes {

        /**
		 * The ID of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string $plugin_name The ID of this plugin.
		 */
		private $plugin_name;

		/**
		 * The version of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string $version The current version of this plugin.
		 */
		private $version;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 *
		 * @param      string $plugin_name The name of the plugin.
		 * @param      string $version The version of this plugin.
		 */
		public function __construct( $plugin_name, $version ) {

			$this->plugin_name = $plugin_name;
			$this->version     = $version;

		}
		/**
		* Get sign in message.
		*
		* @author  WEBW
	    * @since   1.0.0
		* @return string $signature_message
		*/
		public function get_sign_in_message() {
			$message = sprintf(
				__(
					'Log in to %1$s',
					'solpress-wordpress-login-plugin'
				),
				get_bloginfo( 'name', 'display' )
			) . "\n\n" . get_home_url();
		
			$signature_message = get_option('swl-sign-in-message');
			$signature_message = (!empty($signature_message)) ? sanitize_text_field( $signature_message ) : $message;

			return $signature_message;
		}

		/**
		* Shortcode for login.
		*
		* @param  array $attributes  an associative array of attributes, or an empty string if no attributes are given.
		*
		* @author  WEBW
	    * @since   1.0.0
	    * @return  $template contents of the output buffer or false, if output buffering isn't active.
		*/
		public function solpress_wordpress_login_plugin_login_button( $attributes ) {
			$label = get_option('swl-short-code-label-button-text');
            $label = (!empty($label)) ? sanitize_text_field( $label ) : __('Link your Crypto Wallet', 'solpress-wordpress-login-plugin');
			$label = esc_attr(preg_replace("/\\\+/", "", $label));
			
			$attributes = shortcode_atts( array(
				'label' => $label,
				'page' => 'shortcode',
				'id' => 'solana-shortcode',
			), $attributes );

			$button_text_from_shortcode = $attributes['label'];
			$page_source_from_shortcode = $attributes['page'];
			$id_from_shortcode = $attributes['id'];
			$sign_message = $this->get_sign_in_message();

			if ( is_readable( SOLPRESS_WORDPRESS_LOGIN_PLUGIN_PATH . 'public/partials/solpress-wordpress-login-plugin-public-login-button.php' ) ) {
				ob_start();
				include SOLPRESS_WORDPRESS_LOGIN_PLUGIN_PATH . 'public/partials/solpress-wordpress-login-plugin-public-login-button.php';
				$template = ob_get_contents();
				ob_end_clean();
				return $template;
			}

		}
		

}

<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://profiles.wordpress.org/solpressplugins/
 * @since      1.0.0
 *
 * @package    Solpress_Wordpress_Login_Plugin
 * @subpackage Solpress_Wordpress_Login_Plugin/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Solpress_Wordpress_Login_Plugin
 * @subpackage Solpress_Wordpress_Login_Plugin/includes
 * @author     Solpress <https://profiles.wordpress.org/solpressplugins/>
 */
class Solpress_Wordpress_Login_Plugin_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'solpress-wordpress-login-plugin',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}

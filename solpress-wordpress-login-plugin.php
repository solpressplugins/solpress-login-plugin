<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://profiles.wordpress.org/solpressplugins/
 * @since             1.0.0
 * @package           Solpress_Wordpress_Login
 *
 * @wordpress-plugin
 * Plugin Name:       SolPress Login
 * Plugin URI:        https://solpress.dev/
 * Description:       A completely free, open source, permissionless, censorship-resistant, decentralized WordPress user registration and login plugin using Solana blockchain wallets. Say goodbye to emails and hello to web3 logins.
 * Version:           1.0
 * Author:            Solpress
 * Author URI:        https://profiles.wordpress.org/solpressplugins/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       solpress-wordpress-login
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
define( 'SOLPRESS_WORDPRESS_LOGIN_PLUGIN_VERSION', '1.0' );
define( 'SOLPRESS_WORDPRESS_LOGIN_PLUGIN_PATH', plugin_dir_path(__FILE__));
define( 'SOLPRESS_WORDPRESS_LOGIN_PLUGIN_URL', plugin_dir_url(__FILE__));
define( 'SOLPRESS_WORDPRESS_LOGIN_PLUGIN_BASE_FILE', __FILE__ );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-solpress-wordpress-login-plugin-activator.php
 */
function activate_solpress_wordpress_login_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-solpress-wordpress-login-plugin-activator.php';
	Solpress_Wordpress_Login_Plugin_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-solpress-wordpress-login-plugin-deactivator.php
 */
function deactivate_solpress_wordpress_login_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-solpress-wordpress-login-plugin-deactivator.php';
	Solpress_Wordpress_Login_Plugin_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_solpress_wordpress_login_plugin' );
register_deactivation_hook( __FILE__, 'deactivate_solpress_wordpress_login_plugin' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-solpress-wordpress-login-plugin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_solpress_wordpress_login_plugin() {

	$plugin = new Solpress_Wordpress_Login_Plugin();
	$plugin->run();

}
run_solpress_wordpress_login_plugin();

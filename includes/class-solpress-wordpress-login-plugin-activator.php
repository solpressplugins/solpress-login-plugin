<?php

/**
 * Fired during plugin activation
 *
 * @link       https://profiles.wordpress.org/solpressplugins/
 * @since      1.0.0
 *
 * @package    Solpress_Wordpress_Login_Plugin
 * @subpackage Solpress_Wordpress_Login_Plugin/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Solpress_Wordpress_Login_Plugin
 * @subpackage Solpress_Wordpress_Login_Plugin/includes
 * @author     Solpress <https://profiles.wordpress.org/solpressplugins/>
 */
class Solpress_Wordpress_Login_Plugin_Activator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate()
    {

        function showActivationError()
        {
            ?>
					<div class="updated">
							<p><?php _e('SolPress Login Plugin is not correctly activated. Try re-activating or reinstalling SolPress Login plugin again.!', 'solpress-wordpress-login-plugin');?></p>
					</div>
					<?php
}

        $api_url = esc_url('https://solpressloginapp.herokuapp.com/accounts');
        // get user id
        $data = array("userId" => get_current_user_id());
        // make post to remote for auth key
        $args = [
            'body' => json_encode($data),
            'method' => 'POST',
            'headers' => [
                'Content-type' => 'application/json',
            ],
        ];
        $response = wp_remote_post($api_url, $args);

        if (!is_wp_error($response)) {

            if (isset($response['response'])) {
                if (isset($response['response']['code'])) {
                    $response_code = $response['response']['code'];
                }
            }

            if (isset($response['body']) && 200 === $response_code) {

                $body = wp_remote_retrieve_body($response);

                $code = json_decode($body);
                if (strlen($code) > 0) {
                    add_option('swl-auth-key', $code);
                    update_option('swl-auth-key', $code);
                } else {
                    // echo 'Invalid data';
                    add_action('admin_notices', 'showActivationError');
                    return;
                }
            }
        }
    }
}
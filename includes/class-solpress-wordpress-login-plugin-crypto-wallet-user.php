<?php

/**
 * The file that contains logic for login/register with crypto wallet
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
 * The class that contains logic for login/register with crypto wallet
 *
 *
 * @since      1.0.0
 * @package    Solpress_Wordpress_Login_Plugin
 * @subpackage Solpress_Wordpress_Login_Plugin/includes
 * @author     Solpress <https://profiles.wordpress.org/solpressplugins/>
 */
class Solpress_Wordpress_Login_Plugin_Crypto_Wallet_User
{

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
     * Redirection Links
     *
     * @since    1.0.0
     * @access   private
     * @var      array $links array containing links for redirection.
     */
    public $links;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     *
     * @param      string $plugin_name The name of the plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Getting links to be used for the redirection url.
     *
     * @author  WEBW
     * @since   1.0.0
     * @return  void
     */

    public function get_links()
    {

        $this->links['wc_profile_url'] = '';
        $this->links['wc_account'] = '';

        $this->links['profile_url'] = esc_url(get_edit_profile_url());
        if (function_exists('wc_get_account_endpoint_url')) {
            $this->links['wc_profile_url'] = esc_url(wc_get_account_endpoint_url('edit-account'));
        }
        if (function_exists('wc_get_page_permalink')) {
            $this->links['wc_account'] = esc_url(wc_get_page_permalink('myaccount'));
        }
        $this->links['redirect_settings_url'] = esc_url(get_option('swl-redirect-url-settings'));
        $this->links['home_url'] = esc_url(home_url());
    }

    /**
     * Get User Public Key, Source Page, Sign in message and Signature from the ajax request.
     *
     * @author  WEBW
     * @since   1.0.0
     * @return  success message and redirection url on success and error message on failure.
     */

    public function get_public_key()
    {

        if (!check_ajax_referer('solpress_wordpress_login_plugin_public_key_nonce', 'security', false)) {
            wp_send_json_error(array(
                'errorMessage' => __('Invalid security token sent.', 'solpress-wordpress-login-plugin'),
            ), 401);
            wp_die();

        } else {
            $public_key_user = isset($_REQUEST['publicKey']) ? $_REQUEST['publicKey'] : "";
            $public_key_user = sanitize_text_field($public_key_user);

            $source_page = isset($_REQUEST['sourcePage']) ? $_REQUEST['sourcePage'] : "";
            $source_page = sanitize_text_field($source_page);

            $redirect_url = isset($_REQUEST['redirectionURL']) ? $_REQUEST['redirectionURL'] : "";
            $redirect_url = esc_url($redirect_url);
            if (class_exists('Solpress_Wordpress_Login_Plugin_Shortcodes')) {
                $signin_message = Solpress_Wordpress_Login_Plugin_Shortcodes::get_sign_in_message();
            }

            $signature = isset($_REQUEST['signature']) ? $_REQUEST['signature'] : "";
            $signature = sanitize_text_field($signature);

            $verified = $this->verify_user_using_api($public_key_user, $signin_message, $signature);

            if ($verified) {
                // echo "verified";
                $user = $this->login_user($public_key_user, $source_page, $redirect_url);

                if ($user) {
                    return wp_send_json_success(array(
                        'statusCode' => 200,
                        'redirectUrl' => $this->get_redirection_url($redirect_url, $source_page),
                        'successMessage' => __('You are now logged in', 'solpress-wordpress-login-plugin'),
                    ));
                    wp_die();
                } else {
                    // echo "not-verified";
                    wp_send_json_error(array(
                        'errorMessage' => __('An error occurred', 'solpress-wordpress-login-plugin'),
                    ), 400);
                    wp_die();
                }

            } else {
                wp_send_json_error(array(
                    'errorMessage' => __('An error occurred', 'solpress-wordpress-login-plugin'),
                ), 400);
                wp_die();
            }
        }

    }

    /**
     * Verify user before login/register.
     *
     * @param  string $public_key_user user public key.
     * @param  string $signin_message user sign in message.
     * @param  string $signature user signature message.
     *
     * @author  WEBW
     * @since   1.0.0
     * @return  bool true if the user is verified false if not.
     */

    public function verify_user_using_api($public_key_user, $signin_message, $signature)
    {

        $api_url = esc_url('https://solpressloginapp.herokuapp.com/users/');

        $auth_key = get_option('swl-auth-key');
        $auth_key = (!empty($auth_key)) ? sanitize_text_field($auth_key) : '';

        if (!empty($public_key_user)) {
            $public_key = $public_key_user;
        }

        if (!empty($signin_message)) {
            $message = $signin_message;
        }

        if (!empty($signature)) {
            $signature = $signature;
        }

        if (!empty($auth_key)) {
            $key = $auth_key;
        }

        $data = [
            'publicKey' => $public_key,
            'message' => $message,
            'signature' => $signature,
            'key' => $key,
        ];

        $args = [
            'body' => json_encode($data),
            'method' => 'POST',
            'headers' => [
                'Content-type' => 'application/json',
            	'Authorization' => 'Bearer ' . get_option('swl-auth-key'),
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

                $body = json_decode($body);

                if (count((array) $body) > 0 && isset($body->verified) && true === $body->verified) {
                    return true;
                } else {
                    // echo 'Invalid data';
                    return false;
                }

            } else {
                return false;
            }

        } else {
            // echo 'Response is a wp_error';
            return false;
        }

    }

    /**
     * Get redirection url from options.
     *
     * @param  string $redirect_url the url that the user will be redirected to.
     * @param  string $source_page the page that the request is coming from.
     *
     * @author  WEBW
     * @since   1.0.0
     * @return  url redirection_url
     */

    public function get_redirection_url($redirect_url, $source_page)
    {

        if (!empty($redirect_url)) {
            $redirection_url = esc_url($redirect_url);
        } elseif ('profile' === $source_page) {
            $redirection_url = $this->links['profile_url'];
        } elseif ('wc-profile' === $source_page) {
            $redirection_url = $this->links['wc_profile_url'];
        } elseif ('wc-login' === $source_page || 'wc-register' === $source_page) {
            $redirection_url = $this->links['wc_account'];
        } elseif (!empty(get_option('swl-redirect-url-settings'))) {
            $redirection_url = $this->links['redirect_settings_url'];
        } else {
            $redirection_url = $this->links['home_url'];
        }

        return $redirection_url;
    }

    /**
     * Gets user from usermeta table using the public key.
     *
     * @param string $public_key user public key.
     *
     * @author  WEBW
     * @since   1.0.0
     * @return  int $user_id
     */

    public function find_user_by_public_key($public_key)
    {
        global $wpdb;
        $public_key_user = $public_key;
        $table_name = $wpdb->prefix . 'usermeta';
        $public_key_db = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT  user_id FROM `$table_name` WHERE meta_key = 'publickey' AND meta_value = %s ", $public_key_user
            ));

        if (!is_null($public_key_db)) {
            if (property_exists($public_key_db, 'user_id')) {
                $user_id = $public_key_db->user_id;
            }

        }
        return $user_id;
    }

    /**
     * logs in the user with the specified id.
     *
     * @param int $user_id user's id.
     *
     * @author  WEBW
     * @since   1.0.0
     * @return  bool true if the user is logged in or error if not.
     */

    public function log_in($user_id)
    {
        if (!$user_id) {

            wp_send_json_error(array(
                'errorMessage' => __('User not found. Try creating a new account.', 'solpress-wordpress-login-plugin'),
            ), 401);
        }

        clean_user_cache($user_id);
        wp_clear_auth_cookie();

        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id);
        //should take user obj as an input not user id
        update_user_caches($user_id);

        if (is_user_logged_in()) {
            return true;
        } else {
            wp_send_json_error(array(
                'errorMessage' => __('Could not log in user, try again!', 'solpress-wordpress-login-plugin'),
            ), 400);
        }

    }

    /**
     * Get user Id from current user.
     *
     * @param object $current_user.
     *
     * @author  WEBW
     * @since   1.0.0
     * @return  int user_id
     */
    public function get_user_id($current_user)
    {
        if (is_object($current_user) && property_exists($current_user, 'data')) {
            $user_data = $current_user->data;
            if (property_exists($user_data, 'ID')) {
                $user_id = $user_data->ID;
                return $user_id;
            }
        }
    }

    /**
     * Updates user meta with public key.
     *
     * @param int user_id user's id.
     * @param string $public_key user's public key.
     *
     * @author  WEBW
     * @since   1.0.0
     * @return  (int|bool) Meta ID if the key didn't exist, true on successful update, false on failure or if the value passed to the function is the same as the one that is already in the database.
     */
    public function update_user_meta($user_id, $public_key)
    {
        if ($user_id) {
            $update_meta_key = update_user_meta($user_id, 'publickey', $public_key);
        }

        /**
         * false means it failed or the
         * meta key already existed with the same value
         */
        if (false !== $update_meta_key) {
            return $update_meta_key;
        } else {
            wp_send_json_error(array(
                'errorMessage' => __('Could not update profile', 'solpress-wordpress-login-plugin'),
            ), 400);
        }

    }

    /**
     * Link user account with crypto wallet
     *
     * @param  string $public_key_user user public key.
     * @param  string $source_page the page that the request is coming from.
     * @param  string $redirect_url the url that the user will be redirected to.
     *
     * @author  WEBW
     * @since   1.0.0
     * @return  success if the user is linked to account and error if not.
     */

    public function link_user($public_key, $source_page, $redirect_url)
    {

        if ($public_key) {

            $current_user = wp_get_current_user();
            $existing_user = $this->find_user_by_public_key($public_key);

            if (!empty($existing_user)) {
                //there is a user with this public key
                wp_send_json_error(array(
                    'errorMessage' => __('Address is already linked to an account.', 'solpress-wordpress-login-plugin'),
                ), 400);
            } else {
                // No matching address, so we can create this link.
                $user_id = $this->get_user_id($current_user);
                $success = $this->update_user_meta($user_id, $public_key);

                //should we send succes from here or from the function above
                if ($success) {
                    return wp_send_json_success(array(
                        'statusCode' => 200,
                        'redirectUrl' => $this->get_redirection_url($redirect_url, $source_page),
                        'successMessage' => __('Success! This address is now linked to your account.', 'solpress-wordpress-login-plugin'),

                    ));

                } else {
                    wp_send_json_error(array(
                        'errorMessage' => __('This address is already linked to an account.', 'solpress-wordpress-login-plugin'),
                    ), 400);
                }
            }
        } else {
            wp_send_json_error(array(
                'errorMessage' => __('Invalid Request.', 'solpress-wordpress-login-plugin'),
            ), 400);
        }
    }

    /**
     * Registers user.
     *
     * @param  string $public_key_user user public key.
     * @see wp_create_user Creates a new user with just the username, password, and email. Email is optional.
     *
     * @author  WEBW
     * @since   1.0.0
     * @return (WP_User|false) WP_User on success, false on failure.
     */
    public function register($public_key)
    {
        $userlogin_max_length = 60;

        $user_login = substr($public_key, 0, $userlogin_max_length);
        $user_login = trim($user_login);

        if (empty($user_login)) {
            wp_send_json_error(array(
                'errorMessage' => __('Empty Username.', 'solpress-wordpress-login-plugin'),
            ), 400);
        }

        $existing_user = $this->find_user_by_public_key($public_key);

        // var_dump($existing_user);

        if (!is_null($existing_user)) {
            wp_send_json_error(array(
                'errorMessage' => __('User already exists, Please Login', 'solpress-wordpress-login-plugin'),
            ), 400);
        }

        /**
         *  wp_create_user returns newly created user's id
         */
        $user_id = wp_create_user($user_login, wp_generate_password());

        /**
         * if is_wp_error is true it will return user_id
         * and exists the function
         */
        if (is_wp_error($user_id)) {
            return $user_id;
        }

        // $user = get_user_by( 'ID', $user_id );

        $this->update_user_meta($user_id, $public_key);

        return $user_id;
    }

    /**
     * Registers, if address doesn't exist, and logs in.
     *
     * @param  string $public_key_user user public key.
     *
     * @author  WEBW
     * @since   1.0.0
     * @return (WP_User|WP_Error) WP_User on success, WP_Error on error.
     */
    public function register_and_log_in($public_key)
    {
        if ($public_key) {
            $user_id = $this->register($public_key);
        }

        if ($user_id) {
            $user = $this->log_in($user_id);
        }

        /**
         * if the user is logged in, $user will be true
         */
        if ($user) {
            return $user;
        }
    }

    /**
     * Login user
     *
     * @param  string $public_key_user user public key.
     * @param  string $source_page the page that the request is coming from.
     * @param  string $redirect_url the url that the user will be redirected to.
     *
     * @author  WEBW
     * @since   1.0.0
     * @return  bool true if the user is logged in or registered and error if not.
     */

    public function login_user($public_key, $source_page, $redirect_url)
    {

        if (is_user_logged_in()) {
            if ('profile' === $source_page || 'wc-profile' === $source_page) {
                //returns wp_send_json_success
                $this->link_user($public_key, $source_page, $redirect_url);
            } else {
                wp_send_json_error(array(
                    'errorMessage' => __('You are already logged in', 'solpress-wordpress-login-plugin'),
                ), 401);
            }

        } else {
            if ('register' === $source_page || 'wc-register' === $source_page) {
                // var_dump("user is registering");
                $registered_user = $this->register_and_log_in($public_key);
                if ($registered_user) {
                    return true;
                }
            } else {
                // var_dump("user is loggingin");
                // case for login, wc-login and shortcode
                $user_id = $this->find_user_by_public_key($public_key);
                $logged_user = $this->log_in($user_id);
                if ($logged_user) {
                    return true;
                }
            }

        }

    }

}
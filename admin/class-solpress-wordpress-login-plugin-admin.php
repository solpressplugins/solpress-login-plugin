<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://profiles.wordpress.org/solpressplugins/
 * @since      1.0.0
 *
 * @package    Solpress_Wordpress_Login_Plugin
 * @subpackage Solpress_Wordpress_Login_Plugin/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Solpress_Wordpress_Login_Plugin
 * @subpackage Solpress_Wordpress_Login_Plugin/admin
 * @author     Solpress <https://profiles.wordpress.org/solpressplugins/>
 */
class Solpress_Wordpress_Login_Plugin_Admin
{

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
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

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

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/solpress-wordpress-login-plugin-admin.css', array(), $this->version, 'all');

        global $pagenow;
        $valid_pages = array('profile.php', 'wp-login.php');

        if (in_array($pagenow, $valid_pages, true)) {

            wp_enqueue_style($this->plugin_name . 'public-css', SOLPRESS_WORDPRESS_LOGIN_PLUGIN_URL . 'public/css/solpress-wordpress-login-plugin-public.css', array(), $this->version, 'all');
            wp_enqueue_style('main.min.css', SOLPRESS_WORDPRESS_LOGIN_PLUGIN_URL . 'public/front/build/static/css/main.min.css', array(), $this->version, 'all');

        }

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

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

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/solpress-wordpress-login-plugin-admin.js', array('jquery'), $this->version, false);

        wp_enqueue_script($this->plugin_name . 'public-js', SOLPRESS_WORDPRESS_LOGIN_PLUGIN_URL . 'public/js/solpress-wordpress-login-plugin-public.js', array('jquery'), $this->version, false);
        wp_enqueue_script('solpress_login_admin_main.min.js', SOLPRESS_WORDPRESS_LOGIN_PLUGIN_URL . 'public/front/build/static/js/main.min.js', array('jquery'), $this->version, true);

        wp_localize_script(
            'solpress_login_admin_main.min.js',
            'solpress_wordpress_vars',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'action_login_button' => 'public_key',
                'security' => wp_create_nonce('solpress_wordpress_login_plugin_public_key_nonce'),

                'general_error' => __('Something went wrong!', 'solpress-wordpress-login'),
            )
        );

    }

    /**
     * Create admin menu and sub menus.
     *
     * @see add_menu_page( string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '', string $icon_url = '', int $position = null ).
     *
     * @author  WEBW
     * @since   1.0.0
     * @return  void
     */

    public function solpress_login_menu()
    {
        add_menu_page(
            esc_html__('SolPress Login Plugin Settings', 'solpress-wordpress-login'),
            esc_html__('SolPress Login Plugin Settings', 'solpress-wordpress-login'),
            'manage_options',
            'solpress-wordpress-login',
            array($this, 'solpress_wordpress_login_plugin'),
            'dashicons-admin-generic
		',
            22
        );
    }

    /**
     * Include partial for admin settings page.
     *
     * @author  WEBW
     * @since   1.0.0
     * @return  void
     */

    public function solpress_wordpress_login_plugin()
    {

        $page_template = SOLPRESS_WORDPRESS_LOGIN_PLUGIN_PATH . 'admin/partials/solpress-wordpress-login-plugin-admin-display.php';
        if (is_file($page_template) && is_readable($page_template)) {
            include_once( plugin_dir_path( __FILE__ ) . 'partials/solpress-wordpress-login-plugin-admin-display.php' );
        }

    }

    /**
     * Calls functions responsible for adding settings to plugin with is sections and fields.
     *
     * @author  WEBW
     * @since   1.0.0
     * @return  void
     */

    public function admin_init()
    {

        $this->add_settings_section();

        $this->add_settings_fields();

        $this->save_fields();

    }

    /**
     * Add settings section for plugin options.
     *
     * @see add_settings_section( string $id, string $title, callable $callback, string $page )
     *
     * @author  WEBW
     * @since   1.0.0
     * @return  void
     */
    public function add_settings_section()
    {

        add_settings_section('swl-login-button-settings', esc_html__('Login Button Text', 'solpress-wordpress-login'), function () {
            '<p>' . esc_html_e('This is where you place the text that will appear on the button', 'solpress-wordpress-login') . '</p>';
        }, 'solpress-wordpress-login');

        add_settings_section('swl-redirect-url-settings', esc_html__('Redirection After Login/Registration', 'solpress-wordpress-login'), function () {
            '<p>' . esc_html_e('This is where you place the url the user will redirected to after successfull login or register', 'solpress-wordpress-login') . '</p>';
        }, 'solpress-wordpress-login');

        add_settings_section('swl-sign-in-message-settings', esc_html__('Sign In Message', 'solpress-wordpress-login'), function () {
            '<p>' . esc_html_e('This is where you place the sign in the message that will appear to the user in the Crypto Wallet', 'solpress-wordpress-login') . '</p>';
        }, 'solpress-wordpress-login');

        add_settings_section('swl-auth-key-settings', esc_html__('User Verification', 'solpress-wordpress-login'), function () {
            '<p>' . esc_html_e('This is where you place the authentication key for user verification', 'solpress-wordpress-login') . '</p>';
        }, 'solpress-wordpress-login');

    }

    /**
     * Add settings fields.
     *
     * @see add_settings_field( string $id, string $title, callable $callback, string $page, string $section = 'default', array $args = array() ).
     *
     * @author  WEBW
     * @since   1.0.0
     * @return  void
     */

    public function add_settings_fields()
    {

        add_settings_field(
            'swl-login-button-text',
            esc_html__('Login Button Text', 'solpress-wordpress-login'),
            array($this, 'markup_text_fields_cb'),
            'solpress-wordpress-login',
            'swl-login-button-settings',
            array(
                'name' => 'swl-login-button-text',
                'value' => get_option('swl-login-button-text'),
            )
        );

        add_settings_field(
            'swl-register-button-text',
            esc_html__('Registration Button Text', 'solpress-wordpress-login'),
            array($this, 'markup_text_fields_cb'),
            'solpress-wordpress-login',
            'swl-login-button-settings',
            array(
                'name' => 'swl-register-button-text',
                'value' => get_option('swl-register-button-text'),
            )
        );

        add_settings_field(
            'swl-user-profile-button-text',
            esc_html__('User Profile Button Text', 'solpress-wordpress-login'),
            array($this, 'markup_text_fields_cb'),
            'solpress-wordpress-login',
            'swl-login-button-settings',
            array(
                'name' => 'swl-user-profile-button-text',
                'value' => get_option('swl-user-profile-button-text'),
            )
        );

        add_settings_field(
            'swl-short-code-label-button-text',
            esc_html__('Short Code Label Button Text', 'solpress-wordpress-login'),
            array($this, 'markup_text_fields_cb'),
            'solpress-wordpress-login',
            'swl-login-button-settings',
            array(
                'name' => 'swl-short-code-label-button-text',
                'value' => get_option('swl-short-code-label-button-text'),
            )
        );

        add_settings_field(
            'swl-redirect-url-settings',
            esc_html__('Redirection Url', 'solpress-wordpress-login'),
            array($this, 'markup_text_fields_cb'),
            'solpress-wordpress-login',
            'swl-redirect-url-settings',
            array(
                'name' => 'swl-redirect-url-settings',
                'value' => get_option('swl-redirect-url-settings'),
            )
        );

        add_settings_field(
            'swl-sign-in-message',
            esc_html__('Sign in Message', 'solpress-wordpress-login'),
            array($this, 'markup_text_fields_cb'),
            'solpress-wordpress-login',
            'swl-sign-in-message-settings',
            array(
                'name' => 'swl-sign-in-message',
                'value' => get_option('swl-sign-in-message'),
            )
        );

        add_settings_field(
            'swl-auth-key',
            esc_html__('Authentication Key', 'solpress-wordpress-login'),
            array($this, 'markup_text_fields_cb'),
            'solpress-wordpress-login',
            'swl-auth-key-settings',
            array(
                'name' => 'swl-auth-key',
                'value' => get_option('swl-auth-key'),
            	'readonly' => 'true'
            )
        );

    }

    /**
     * Save settings fields.
     *
     * @see register_setting( string $option_group, string $option_name, array $args = array() ).
     *
     * @author  WEBW
     * @since   1.0.0
     * @return  void
     */
    public function save_fields()
    {

        register_setting(
            'swl-settings-page-options-group',
            'swl-login-button-text',
            array(
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        register_setting(
            'swl-settings-page-options-group',
            'swl-register-button-text',
            array(
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        register_setting(
            'swl-settings-page-options-group',
            'swl-user-profile-button-text',
            array(
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        register_setting(
            'swl-settings-page-options-group',
            'swl-short-code-label-button-text',
            array(
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        register_setting(
            'swl-settings-page-options-group',
            'swl-redirect-url-settings',
            array(
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        register_setting(
            'swl-settings-page-options-group',
            'swl-sign-in-message',
            array(
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        register_setting(
            'swl-settings-page-options-group',
            'swl-auth-key',
            array(
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

    }

    /**
     * Markup for text fields.
     *
     * @param  array $args array containing the data to be used in textfields.
     *
     * @author  WEBW
     * @since   1.0.0
     * @return  array $args array containing the data to be used in textfields  after modifications.
     */
    public function markup_text_fields_cb($args)
    {
        if (!is_array($args)) {
            return null;
        }

        $name = (isset($args['name']) ? esc_html($args['name']) : '');
        $value = (isset($args['value']) ? esc_html($args['value']) : '');

        ?>
		<input
		type="text"
		name="<?php echo esc_attr($name); ?>"
		value="<?php echo esc_attr($value); ?>"
		class="field-<?php echo esc_attr($name); ?>"
		size="50"
		/>

    <?php	}

    /**
     * Add Plugin Action Links.
     *
     * @param  array $links
     *
     * @author  WEBW
     * @since   1.0.0
     * @return  array $links after adding settings link.
     */
    public function add_plugin_action_links($links)
    {

        $links[] = '<a href="' . esc_url(get_admin_url(null, 'admin.php?page=solpress-wordpress-login')) . '">' . esc_html__('Settings', 'solpress-wordpress-login') . '</a>';

        return $links;
    }

    /**
     * Add public key as a user meta field.
     *
     * @param  object $user
     *
     * @author WEBW
     * @since  1.0.0
     * @return void
     */
    public function solpress_login_add_user_meta($user)
    {

        if (is_readable(SOLPRESS_WORDPRESS_LOGIN_PLUGIN_PATH . 'admin/partials/solpress-wordpress-login-plugin-admin-display-user-meta-fields.php')) {
            include SOLPRESS_WORDPRESS_LOGIN_PLUGIN_PATH . 'admin/partials/solpress-wordpress-login-plugin-admin-display-user-meta-fields.php';
        }

    }

    /**
     * update or create (if it doesn't exist) public key as a user meta field.
     *
     * @param  int $user_id
     *
     * @author WEBW
     * @since  1.0.0
     * @return bool false if user cannot edit or updates user meta.
     */
    public function solpress_login_save_user_meta($user_id)
    {

        if (!current_user_can('edit_user', $user_id)) {
            return false;
        }
        //should it be sanitised or it is auto sanitised????
        if (isset($_POST['publickey']) && isset($_POST['security']) ) {
            if (wp_verify_nonce( sanitize_text_field($_POST['security']), 'solpress_wordpress_login_plugin_public_key_nonce' )) {
                $sanitised_pk = sanitize_text_field($_POST['publickey']);
                update_user_meta($user_id, 'publickey', $sanitised_pk);
            } else {
                throw new Error('nonce verification failed');
            }
        }

    }

    /**
     * Add "Login with crypto wallet" button.
     *
     * @author WEBW
     * @since  1.0.0
     * @return void
     */
    public function solpress_login_button_add_user_meta()
    {
        ?>
		<h3><?php esc_html_e('Crypto Wallet', 'solpress-wordpress-login');?></h3>

		<span class="description"><?php esc_html_e('After linking a crypto wallet to your account, you can use it to log in.', 'solpress-wordpress-login');?></span>
	    <br/>
			<?php

        $label = get_option('swl-user-profile-button-text');
        $label = (!empty($label)) ? sanitize_text_field($label) : __('Link your Crypto Wallet', 'solpress-wordpress-login');
        $label = esc_attr(preg_replace("/\\\+/", "", $label));
        $button_text = $label;
        $page_source = 'profile';
        $id = 'solana-profile';

        $shortcode = sprintf(
            '[solpress_login_button label="%s" page="%s" id="%s"]',
            $button_text,
            $page_source,
            $id

        );
        echo do_shortcode($shortcode);

        ?>


	<?php }

}
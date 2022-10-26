<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://profiles.wordpress.org/solpressplugins/
 * @since      1.0.0
 *
 * @package    Solpress_Wordpress_Login_Plugin
 * @subpackage Solpress_Wordpress_Login_Plugin/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <?php settings_errors(); ?>
    <form method="post" action="options.php">
    <?php 
        settings_fields('swl-settings-page-options-group');

        do_settings_sections('solpress-wordpress-login');   
    ?>
		<?php submit_button(); ?>
    </form>
</div>
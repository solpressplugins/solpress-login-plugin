
<section class="solpress-login" id="<?php echo esc_html($id_from_shortcode); ?>"
data-button-text="<?php
    echo esc_html($button_text_from_shortcode);
    ?>" 
data-source-page="<?php 
    echo esc_html($page_source_from_shortcode);
    ?>"
data-message="<?php
    echo esc_html($sign_message);
    ?>"     
>
<?php
		$text     = esc_html__( 'Enable JavaScript to log in with a crypto wallet.','solpress-wordpress-login' );
		$noscript = '<noscript>' . $text . '</noscript>';

		echo esc_html('<h4 style="color:#2271B1;">' . $noscript . '</h4>');
?>
</section>

<?php 

//NOTE: if the user inserted the source page and id
//the if conditions won't work if he is not using 
//the keywords that we are using
//it will land in the other or default case

// 1st option from shortcode att
// $button_text = $button_text_from_shortcode;
// $page_source = $page_source_from_shortcode;
// $id = $id_from_shortcode;
// $signature_message = $sign_message;

// global $pagenow;

// if( empty($button_text) && empty($page_source) && empty($id) ) {
//     //2nd option from source page
//     switch ( $pagenow ) {
//         case 'wp-login.php':
//             if ( $_REQUEST['action'] === 'register' ) {
//                 $label = get_option('swl-register-button-text');
//                 $label = (!empty($label)) ? $label : 'Register with your Crypto Wallet';
//                 $button_text = $label;
//                 $page_source = 'register';
//                 $id = 'solana-register';
//             } else {
//                 $label = get_option('swl-login-button-text');
//                 $label = (!empty($label)) ? $label : 'Login with your Crypto Wallet';
//                 $button_text = $label;
//                 $page_source = 'login';
//                 $id = 'solana-login';
//             }
//             break;
//         case 'profile.php':
//             $label = get_option('swl-user-profile-button-text');
//             $label = (!empty($label)) ? $label : 'Link your Crypto Wallet';
//             $button_text = $label;
//             $page_source = 'profile';
//             $id = 'solana-profile';
//             break;
//         default:
//             if ( is_wc_endpoint_url( 'edit-account' ) ) {
//                 $label = get_option('swl-user-profile-button-text');
//                 $label = (!empty($label)) ? $label : 'Link your Crypto Wallet';
//                 $button_text = $label;
//                 $page_source = 'wc-profile';
//                 $id = 'solana-wc-profile';
//             } else {
//                 //default incase no att in shortcode and no matching page
//                 $label = get_option('swl-short-code-label-button-text');
//                 $label = (!empty($label)) ? $label : 'Link your Crypto Wallet';
//                 $button_text = $label;
//                 $page_source = 'shortcode';
//                 $id = 'solana-shortcode';
//             }    
//     }

// }
    
    // $message = sprintf(
    //     __(
    //         'Log in to %1$s',
    //         'solpress-wordpress-login'
    //     ),
    //     get_bloginfo( 'name', 'display' )
    // ) . "\n\n" . get_home_url();

    // $signature_message = get_option('swl-sign-in-message');
    // $signature_message = (!empty($signature_message)) ? $signature_message : $message;

?>


<section class="solpress-login" 
id="<?php echo $id_from_shortcode; ?>"
data-button-text="<?php
    echo $button_text_from_shortcode;
    ?>" 
data-source-page="<?php 
    echo $page_source_from_shortcode;
    ?>"
data-message="<?php
    echo $sign_message;
    ?>"     
>
<?php
		$text     = esc_html__( 'Enable JavaScript to log in with a crypto wallet.','solpress-wordpress-login' );
		$noscript = '<noscript>' . $text . '</noscript>';

		echo '<h4 style="color:#2271B1;">' . $noscript . '</h4>';
?>
</section>

<h3><?php _e('User Wallet Public Key', 'solpress-wordpress-login-plugin'); ?></h3>
			
<table class="form-table">
  <tr>
    <th>
        <label for="publickey"><?php _e('Public Key', 'solpress-wordpress-login-plugin'); ?>
        </label>
    </th>
    <td>
        <input type="text" name="publickey" id="publickey" value="<?php echo esc_attr( get_the_author_meta( 'publickey', $user->ID ) ); ?>" class="regular-text" /><br />
            <span class="description"><?php _e('Please enter your public key.', 'solpress-wordpress-login-plugin'); ?></span>
    </td>
  </tr>
</table>
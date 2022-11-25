<h3><?php esc_html_e('User Wallet Public Key', 'solpress-wordpress-login'); ?></h3>
<table class="form-table">
  <tr>
    <th>
        <label for="publickey"><?php esc_html_e('Public Key', 'solpress-wordpress-login'); ?>
        </label>
    </th>
    <td>
        <input type="text" name="publickey" id="publickey" value="<?php echo esc_attr( get_the_author_meta( 'publickey', $user->ID ) ); ?>" class="regular-text" /><br />
            <span class="description"><?php esc_html_e('Please enter your public key.', 'solpress-wordpress-login'); ?></span>
    </td>
  </tr>
</table>
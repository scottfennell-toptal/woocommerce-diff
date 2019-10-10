<?php
/**
 * Lost password form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-lost-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.0
 */
if (!defined('ABSPATH')) {
    exit;
}

wc_print_notices();
?>

<form method="post" class="woocommerce-ResetPassword lost_reset_password mt-5">

    <div class="form-group mb-4">
        <label for="user_login"><?php _e('Email', 'woocommerce'); ?></label>
        <input class="form-control woocommerce-Input woocommerce-Input--text input-text" type="text" name="user_login" id="user_login" />
    </div>

    <div class="clear"></div>

    <?php do_action('woocommerce_lostpassword_form'); ?>

    <p class="woocommerce-form-row form-row">
        <input type="hidden" name="wc_reset_password" value="true" />
        <button class="btn btn-brand btn-block mb-4" type="submit">Send password reset</button>
    </p>

    <?php wp_nonce_field('lost_password'); ?>
    <p class="small text-center text-gray-soft">Remember your password ? <a href="<?php echo get_bloginfo("url"); ?>/signin">Sign in</a></p>

</form>

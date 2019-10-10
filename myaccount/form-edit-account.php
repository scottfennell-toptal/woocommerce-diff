<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
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
 * @version 2.6.0
 */
if (!defined('ABSPATH')) {
    exit;
}

do_action('woocommerce_before_edit_account_form');
?>
<div class="container container--xs">
    <h1 class="mb-1 text-center">Your Account</h1>
    <p class="fs-14 text-gray text-center mb-5">Change your name, email, or password.</p>
    <form class="woocommerce-EditAccountForm edit-account" action="" method="post">
        <div class="form-group row">
            <?php do_action('woocommerce_edit_account_form_start'); ?>

            <div class="clear"></div>

            <div class="col-sm-6 mb-4 mb-sm-0">
                <label for="account_first_name"><?php _e('First name', 'woocommerce'); ?> <span class="required">*</span></label>
                <input type="text" class="form-control woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" value="<?php echo esc_attr($user->first_name); ?>" />

            </div>
            <div class="col-sm-6">
                <label for="account_last_name"><?php _e('Last name', 'woocommerce'); ?> <span class="required">*</span></label>
                <input type="text" class="form-control woocommerce-Input woocommerce-Input--text input-text" name="account_last_name" id="account_last_name" value="<?php echo esc_attr($user->last_name); ?>" />

            </div>
        </div>
        <div class="form-group">

            <label for="account_email"><?php _e('Email', 'woocommerce'); ?> <span class="required">*</span></label>
            <input type="email" class="form-control woocommerce-Input woocommerce-Input--email input-text" name="account_email" id="account_email" value="<?php echo esc_attr($user->user_email); ?>" />


        </div>
        <input type="submit" class="btn btn-outline-brand btn-block mb-4" name="save_account_details" value="<?php esc_attr_e('Update info', 'woocommerce'); ?>" />
        <hr class="my-5">
        <div class="form-group">
            <label for="password_current"><?php _e('Current password ', 'woocommerce'); ?></label>
            <input type="password" class="form-control woocommerce-Input woocommerce-Input--password input-text" name="password_current" id="password_current" />

        </div>
        <div class="form-group">
            <label for="password_1"><?php _e('New password ', 'woocommerce'); ?></label>
            <input type="password" class="form-control woocommerce-Input woocommerce-Input--password input-text" name="password_1" id="password_1" />

        </div>
        <div class="form-group">
            <label for="password_2"><?php _e('Confirm new password', 'woocommerce'); ?></label>
            <input type="password" class="form-control woocommerce-Input woocommerce-Input--password input-text" name="password_2" id="password_2" />

        </div>
        <?php wp_nonce_field('save_account_details'); ?>
        <input type="submit" class="btn btn-outline-brand btn-block mb-4" name="save_account_details" value="<?php esc_attr_e('Update password', 'woocommerce'); ?>" />
        <input type="hidden" name="action" value="save_account_details" />
    </form>
</div>

<?php do_action('woocommerce_after_edit_account_form'); ?>

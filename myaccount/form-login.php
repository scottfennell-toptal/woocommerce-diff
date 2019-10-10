<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
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
    exit; // Exit if accessed directly
}
?>




<div id="signup_div_wrapper">
    <img class="img-fluid mx-auto d-block mb-3" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/elements/bootstrap-logo.svg" alt="">
    <h1 class="mb-1 text-center">Sign up</h1>
    <p class="fs-14 text-gray text-center mb-5">Redownload themes, get support, and review themes.</p>

    <?php wc_print_notices(); ?>

    <form method="post" class="register">

        <?php do_action('woocommerce_register_form_start'); ?>

        <?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="reg_username"><?php _e('Username', 'woocommerce'); ?> <span class="required">*</span></label>
                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" value="<?php echo (!empty($_POST['username']) ) ? esc_attr($_POST['username']) : ''; ?>" />
            </p>

        <?php endif; ?>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="reg_email"><?php _e('Email address', 'woocommerce'); ?> <span class="required">*</span></label>
            <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" value="<?php echo (!empty($_POST['email']) ) ? esc_attr($_POST['email']) : ''; ?>" />
        </p>

        <?php if ('no' === get_option('woocommerce_registration_generate_password')) : ?>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="reg_password"><?php _e('Password', 'woocommerce'); ?> <span class="required">*</span></label>
                <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" />
            </p>

        <?php endif; ?>

        <!-- Spam Trap -->
        <div style="<?php echo ( ( is_rtl() ) ? 'right' : 'left' ); ?>: -999em; position: absolute;"><label for="trap"><?php _e('Anti-spam', 'woocommerce'); ?></label><input type="text" name="email_2" id="trap" tabindex="-1" autocomplete="off" /></div>

        <?php do_action('woocommerce_register_form'); ?>

        <p class="woocomerce-FormRow form-row">
            <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
            <input type="submit" class="btn btn-brand btn-block btn-lg mb-4 mt-3" style="margin:0;" name="register" value="<?php esc_attr_e('Sign Up', 'woocommerce'); ?>" />
        </p>

        <?php do_action('woocommerce_register_form_end'); ?>

    </form>

    <p class="text-gray-soft text-center small mb-2">By clicking "Sign up" you agree to our <a href="<?php echo get_bloginfo("url"); ?>/terms">Terms of Service</a>.</p>
    <p class="text-gray-soft text-center small mb-2">Already have an account? <a href="<?php echo get_bloginfo("url"); ?>/signin/">Sign in</a></p>
    <p class="text-gray-soft text-center small">Trying to sign up to sell themes? <a href="<?php echo get_bloginfo("url"); ?>/sell/">Apply to be a seller.</a></p>

</div>
<?php do_action('woocommerce_after_customer_login_form'); ?>

<?php
/**
 * Customer new account email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-new-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     1.6.4
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<?php do_action('woocommerce_email_header', $email_heading, $email); ?>

<p><?php printf(__('We appreciate you leaving an honest review of %1$s', 'woocommerce'), '<strong>' . ($product_name) . '</strong>'); ?></p>


<p><?php printf(__('Use this coupon code at checkout for 15&#37; off any order: %s.', 'woocommerce'), '<strong>' . $coupon . '</strong>'); ?></p>

<?php
do_action('woocommerce_email_footer', $email);

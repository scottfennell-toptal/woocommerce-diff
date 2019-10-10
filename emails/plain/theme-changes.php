<?php

/**
 * Customer new account email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/plain/customer-new-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails/Plain
 * @version     2.0.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


echo "= " . str_replace("[{product_name}]", $product_name, $email_heading) . " =\n\n";

echo sprintf(__('%1$s has been updated!', 'woocommerce'), ($product_name));

if ($msg) {
    echo sprintf(__('A message from %1$s about the update: ', 'woocommerce'), ($seller_name));
    echo $msg;
}

echo '<a href="' . $download_url . '">Download the update</a>';
if ($seller_support) {
    echo '<a href="' . $seller_support . '" target="_blank">Request Support</a>';
}
echo sprintf(__('Thanks so much for purchasing a Bootstrap Theme!\n\nCheers,\nTeam Bootstrap'));

echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

echo apply_filters('woocommerce_email_footer_text', get_option('woocommerce_email_footer_text'));

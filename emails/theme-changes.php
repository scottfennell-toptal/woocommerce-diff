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

<?php do_action('woocommerce_email_header', str_replace("[{product_name}]", $product_name, $email_heading), $email); ?>

<p><?php printf(__('%1$s has been updated!', 'woocommerce'), '<strong>' . ($product_name) . '</strong>'); ?></p>
<?php
if ($msg) {
    echo '<p>' . sprintf(__('A message from %1$s about the update: ', 'woocommerce'), ($seller_name));
    echo '<br>' . $msg . '</p>';
}
?>
<ul>
    <li><?php printf(__(' %s.', 'woocommerce'), '<a href="' . $download_url . '">Download the update</a>'); ?></li>
    <?php if ($seller_support) { ?>
        <li><?php printf(__(' %s.', 'woocommerce'), '<a href="' . $seller_support . '" target="_blank">Request Support</a>'); ?></li>
    <?php } ?>
</ul>
<p><?php printf(__('Thanks so much for purchasing a Bootstrap Theme!')); ?></p>
<p><?php
    printf(__('Cheers,'));
    echo "<br />";
    printf(__('Team Bootstrap'));
    ?></p>

<?php
do_action('woocommerce_email_footer', $email);

<?php
/**
 * Order Item Details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details-item.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.0
 */
if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;
$author_id = $wpdb->get_var("SELECT `post_author` FROM `" . $wpdb->prefix . "posts` WHERE `ID`='" . $product->get_ID() . "'");
$dokan_profile_settings = get_user_meta($author_id, "dokan_profile_settings", true);

if (!apply_filters('woocommerce_order_item_visible', true, $item)) {
    return;
}
?>
<div class="card--summary__item">
    <div class="card--summary__theme-thumb">
        <img class="img-fluid" src="<?php echo get_the_post_thumbnail_url($product->get_ID(), 'smaller_crop'); ?>" alt="">
    </div>
    <div class="card--summary__theme-description">
        <div>
          <h5 class="card--summary__theme-title"><?php echo get_the_title($product->get_ID()); ?></h5>
          <?php
          do_action('woocommerce_order_item_meta_start', $item_id, $item, $order);
          wc_display_item_meta($item);
          do_action('woocommerce_order_item_meta_end', $item_id, $item, $order);
          ?>
        </div>
        <div class="card--summary__theme-action">
            <p class="card--summary__theme-price"><?php echo "$" . number_format($item->get_subtotal(), 2); ?></p>
            <?php
            $downloads = wc_display_item_downloads($item, array("echo" => false));
            $stpos = 0;
            $downloads = str_replace('<strong class="wc-item-download-label">Download:</strong>', '', $downloads);
            while ($stpos = strpos($downloads, 'target="_blank">', $stpos + 1)) {
                $nd_pos = strpos($downloads, '</a>', $stpos);
                $downloads = substr($downloads, 0, $stpos + 16) . "Download" . substr($downloads, $nd_pos);
            }
            echo $downloads;
            if ($dokan_profile_settings["support_link"]) {
                ?>
                <a href="<?php echo $dokan_profile_settings["support_link"]; ?>">Get Support</a>
            <?php } ?>
        </div>
    </div>
</div>

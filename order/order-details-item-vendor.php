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
            if ($order->data["parent_id"]) {
                $subtotal = wc_get_order_item_meta($item_id, "_line_subtotal", true);
                $parent_order_items = $wpdb->get_results("SELECT `order_item_id` FROM `" . $wpdb->prefix . "woocommerce_order_items` WHERE `order_id`='" . $order->data["parent_id"] . "' AND `order_item_name`='" . $item->get_name() . "'");
                foreach ($parent_order_items as $parent_order_item) {
                    if ($subtotal == wc_get_order_item_meta($parent_order_item->order_item_id, "_line_subtotal", true)) {
                        $parent_order_item_id = $parent_order_item->order_item_id;
                    }
                }
                $type = $wpdb->get_var("SELECT `meta_value` FROM `" . $wpdb->prefix . "woocommerce_order_itemmeta` WHERE `meta_key`='License Type' AND `order_item_id`='" . $parent_order_item_id . "'");
            } else {
                $type = $wpdb->get_var("SELECT `meta_value` FROM `" . $wpdb->prefix . "woocommerce_order_itemmeta` WHERE `meta_key`='License Type' AND `order_item_id`='" . $item_id . "'");
            }
            $total_pr += $pr;
            echo '<div class="order_item_prod">' . ucfirst($type) . '</div>';

//        do_action('woocommerce_order_item_meta_start', $item_id, $item, $order);
//        wc_display_item_meta($item);
//        do_action('woocommerce_order_item_meta_end', $item_id, $item, $order);
            ?>
        </div>
        <div class="card--summary__theme-action">
            <p class="card--summary__theme-price"><?php echo "$" . number_format($item->get_subtotal(), 2); ?></p>
        </div>
    </div>
</div>
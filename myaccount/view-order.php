<?php
/**
 * View Order
 *
 * Shows the details of a particular order on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/view-order.php.
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
?>
<style>
    .section{
        padding-top: 0;
    }
</style>
<ol class="breadcrumb mb-1 fs-13 mt-5">
    <li class="breadcrumb-item"><a href="<?php echo get_bloginfo("url"); ?>/my-account/downloads/"><?php _e("Purchases"); ?></a></li>
    <li class="breadcrumb-item active"><?php _e("Order"); ?></li>
</ol>
<h2>Order #<?php echo $order->get_order_number(); ?></h2>
<div class="row justify-content-between">
    <div class="col-xl-4 col-lg-5 mb-4 mb-lg-0 order-lg-2">
        <div class="card card--summary">
            <div class="card-body">
                <?php
                foreach ($order->get_items() as $item_id => $item) {
                    $product = apply_filters('woocommerce_order_item_product', $item->get_product(), $item);

                    wc_get_template('order/order-details-item.php', array(
                        'order' => $order,
                        'item_id' => $item_id,
                        'item' => $item,
                        'show_purchase_note' => $show_purchase_note,
                        'purchase_note' => $product ? $product->get_purchase_note() : '',
                        'product' => $product,
                    ));
                }

                $args = array(
                    'post_parent' => $order->get_id(),
                    'post_type' => 'shop_order',
                    'numberposts' => -1,
                    'post_status' => 'any'
                );

                $sub_orders = get_children($args);
                $sub_refunded = 0;
                $refunded = 0;

                if ($order->get_total_discount()) {
                    ?>
                    <div class="card--summary__footer">
                        <p class="mb-0"><?php _e('Discount', 'dokan'); ?></p>
                        <p>-<?php echo wc_price($order->get_total_discount()); ?></p>
                    </div>
                    <?php
                }

                foreach ($sub_orders as $sub_order) {
                    $the_sub_order = new WC_Order($sub_order->ID);

                    if ($the_sub_order->get_total_refunded()) {
                        $sub_refunded += $the_sub_order->get_total_refunded();
                        ?>
                        <div class="card--summary__footer">
                            <p class="mb-0"><?php _e('Refunded', 'dokan'); ?></p>
                            <p>-<?php echo wc_price($the_sub_order->get_total_refunded()); ?></p>
                        </div>
                        <?php
                    }
                }
                if ($order->get_total_refunded()) {
                    $refunded += $order->get_total_refunded()
                    ?>
                    <div class="card--summary__footer">
                        <p class="mb-0"><?php _e('Refund', 'dokan'); ?></p>
                        <p>-<?php echo wc_price($order->get_total_refunded()); ?></p>
                    </div>
                <?php } ?>

                <div class="card--summary__footer">
                    <p class="mb-0">Total</p>
                    <p><?php echo wc_price($order->get_total() - $sub_refunded - $refunded); ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-xl-7">
        <div class="theme-description__list mb-4">
            <div class="theme-description__list__item"><span class="theme-description__item__title">Order date</span><span><?php echo wc_format_datetime($order->get_date_created()); ?></span></div>
            <div class="theme-description__list__item"><span class="theme-description__item__title">Payment type</span><span><?php echo wp_kses_post($order->get_payment_method_title()); ?></span></div>

            <?php if ($order->get_formatted_billing_full_name()) : ?>
                <div class="theme-description__list__item"><span class="theme-description__item__title">Customer name</span><span><?php echo esc_html($order->get_formatted_billing_full_name()); ?></span></div>
            <?php endif; ?>
            <?php if ($order->get_billing_email()) : ?>
                <div class="theme-description__list__item"><span class="theme-description__item__title">Customer email</span><span><?php echo esc_html($order->get_billing_email()); ?></span></div>
            <?php endif; ?>

            <?php if ($order->get_billing_phone()) : ?>
                <div class="theme-description__list__item"><span class="theme-description__item__title">Customer phone</span><span><?php echo esc_html($order->get_billing_phone()); ?></span></div>

            <?php endif; ?>

            <?php if ($order->get_customer_note()) : ?>
                <div class="theme-description__list__item"><span class="theme-description__item__title">Customer notes</span><span><?php echo esc_html($order->get_customer_note()); ?></span></div>

            <?php endif; ?>

            <div class="theme-description__list__item"><span class="theme-description__item__title">Need an invoice?</span><span><a href="<?php echo get_bloginfo("url");?>/invoice/?invoice_id=<?php echo $order->get_order_number(); ?>&email=<?php echo urlencode($order->get_billing_email()); ?>" target="_blank">View Invoice</a></span></div>

            <!--
                          <div class="theme-description__list__item"><span class="theme-description__item__title">Customer name</span><span>Dave Gamache</span></div>
                          <div class="theme-description__list__item"><span class="theme-description__item__title">Theme seller</span><span><a href="/profile">Tres Amibros</a> (<a href="#">Request support</a>)</span></div>
            -->
        </div>
    </div>
</div>

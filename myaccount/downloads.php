<?php
/**
 * Downloads
 *
 * Shows downloads on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/downloads.php.
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

$downloads = WC()->customer->get_downloadable_products();
$has_downloads = (bool) $downloads;

do_action('woocommerce_before_account_downloads', $has_downloads);
?>

<?php if ($has_downloads) : ?>

    <?php do_action('woocommerce_before_available_downloads'); ?>
    <?php
    $columns = wc_get_account_downloads_columns();
    $new_columns = array("order" => "Order");
    foreach ($columns as $column_id => $column_name) {
        if ($column_name == "Product") {
            $column_name = __("Theme");
            $new_columns[$column_id] = $column_name;
        } elseif ($column_name == "File") {
            $column_name = __("Re-download");
            $new_columns[$column_id] = $column_name;
        } elseif ($column_name == "Expires") {
            $column_name = __("Date");
            $new_columns["date"] = $column_name;
        }
    }
    ?>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <?php
                    foreach ($new_columns as $column_id => $column_name) :
                        ?>
                        <th class="<?php
                        if ($column_id == "download-file") {
                            echo "text-right";
                        }
                        ?>"><span class="nobr"><?php echo esc_html($column_name); ?></span></th>
                        <?php endforeach; ?>
                </tr>
            </thead>
            <?php
            foreach ($downloads as $download) {
                $ord_parent = $wpdb->get_var("SELECT `post_parent` FROM `wp_posts` WHERE `ID`='" . $download["order_id"] . "'");
                if ($ord_parent == 0) {
                    ?>
                    <tr>
                        <?php foreach ($new_columns as $column_id => $column_name) : ?>
                            <td class="<?php
                            if ($column_id == "download-file") {
                                echo "text-right";
                            }
                            ?>" data-title="<?php echo esc_attr($column_name); ?>">
                                    <?php
                                    if (has_action('woocommerce_account_downloads_column_' . $column_id)) {
                                        do_action('woocommerce_account_downloads_column_' . $column_id, $download);
                                    } else {
                                        switch ($column_id) {
                                            case 'order' :
                                                ?>
                                            <a href="<?php echo esc_url(get_bloginfo("url") . "/my-account/view-order/" . $download['order_id']); ?>" class="text-brand">
                                                <?php echo "#" . esc_html($download['order_id']); ?>
                                            </a>
                                            <?php
                                            break;
                                        case 'date' :
                                            ?>
                                            <?php echo get_the_date("m/d/Y", $download['order_id']); ?>
                                            <?php
                                            break;
                                        case 'download-product' :
                                            ?>
                                            <a href="<?php echo esc_url(get_permalink($download['product_id'])); ?>" class="text-brand">
                                                <?php echo esc_html($download['product_name']); ?>
                                            </a>
                                            <?php
                                            break;
                                        case 'download-file' :
                                            ?>
                                            <a href="<?php echo esc_url($download['download_url']); ?>" class="text-brand woocommerce-MyAccount-downloads-file">
                                                <?php _e("Download"); ?>
                                            </a>
                                            <?php
                                            break;
                                        case 'download-remaining' :
                                            echo is_numeric($download['downloads_remaining']) ? esc_html($download['downloads_remaining']) : __('&infin;', 'woocommerce');
                                            break;
                                        case 'download-expires' :
                                            ?>
                                            <?php if (!empty($download['access_expires'])) : ?>
                                                <time datetime="<?php echo date('Y-m-d', strtotime($download['access_expires'])); ?>" title="<?php echo esc_attr(strtotime($download['access_expires'])); ?>"><?php echo date_i18n(get_option('date_format'), strtotime($download['access_expires'])); ?></time>
                                            <?php else : ?>
                                                <?php _e('Never', 'woocommerce'); ?>
                                            <?php endif; ?>
                                            <?php
                                            break;
                                        case 'download-actions' :
                                            ?>
                                            <?php
                                            $actions = array(
                                                'download' => array(
                                                    'url' => $download['download_url'],
                                                    'name' => __('Download', 'woocommerce'),
                                                ),
                                            );
                                            if ($actions = apply_filters('woocommerce_account_download_actions', $actions, $download)) {
                                                foreach ($actions as $key => $action) {
                                                    echo '<a href="' . esc_url($action['url']) . '" class="button woocommerce-Button ' . sanitize_html_class($key) . '">' . __("Download") . '</a>';
                                                }
                                            }
                                            ?>
                                            <?php
                                            break;
                                    }
                                }
                                ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <?php
                }
            }
            ?>
        </table>
    </div>
    <?php do_action('woocommerce_after_available_downloads'); ?>
<?php else : ?>
    <div style="padding-top: 65px;">
        <h1 class="mb-1 text-center">No Downloads</h1>
        <div class="fs-14 text-gray text-center mb-5">
            <p>You haven't purchased anything yet!</p>
            <a class="woocommerce-Button button" href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>">
                <?php esc_html_e('Explore Themes', 'woocommerce') ?>
            </a>
        </div>
    </div>
<?php endif; ?>

<?php do_action('woocommerce_after_account_downloads', $has_downloads); ?>

<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */
//if (!defined('ABSPATH')) {
//    exit; // Exit if accessed directly
//}

global $post;
global $product;
global $wpdb;
global $is_review;
global $edit_prod_id;

/**
 * woocommerce_before_single_product hook.
 *
 * @hooked wc_print_notices - 10
 */
do_action('woocommerce_before_single_product');

if (post_password_required()) {
    echo get_the_password_form();
    return;
}

$current_user = wp_get_current_user();
$prod_id = get_the_ID();
$author_id = $wpdb->get_var("SELECT `post_author` FROM `" . $wpdb->prefix . "posts` WHERE `ID`='" . $prod_id . "'");
//print_r($product);

$dokan_settings = get_user_meta($author_id, "dokan_profile_settings", true);

$theme_store_name = $dokan_settings["store_name"];
$public_support_link = $dokan_settings["public_support_link"];
$support_link = $dokan_settings["support_link"];

$theme_store_img = wp_get_attachment_image_src($dokan_settings["gravatar"], "square_crop");
$theme_store_link = get_bloginfo("url") . "/store/" . get_the_author_meta('user_nicename', $author_id);

$show_new = $_GET["show_new"];
if (isset($_GET["prod_id"])) {
    $show_new = 1;
}

if ($is_review) {

    $the_preview_url = $_REQUEST["preview_url"];
    $the_title = stripslashes(stripslashes($_REQUEST["post_title"]));
    $product_cat = get_term_by("id", $_REQUEST["product_cat"], "product_cat");
    $the_content = stripslashes(stripslashes($_REQUEST["post_content"]));
    $ext_price = $_REQUEST["_regular_price_extended"];
    $reg_price = $_REQUEST["_regular_price"];
    $price_html = wc_price($_REQUEST["_regular_price"]);
    $bootstrap_ver = $_REQUEST["bootstrap_ver"];
    $updated_time = time();
    $changelog = $_REQUEST["changelog"];
    $current_ver = $_REQUEST["theme_version"];
    $preview_url = $_REQUEST["preview_url"];
    $img_content = stripslashes($_REQUEST["thumb_img_body"]);
    if (substr($img_content, 0, 5) == 'url("') {
        $img_content = substr($img_content, 5, strlen($img_content) - 7);
    }
    if (substr($img_content, 0, 4) == 'url(') {
        $img_content = substr($img_content, 4, strlen($img_content) - 5);
    }

    $upload_dir = wp_upload_dir();
    if ($img_content) {
        $imageData = $img_content;
        list($type, $imageData) = explode(';', $imageData);
        list(, $extension) = explode('/', $type);
        list(, $imageData) = explode(',', $imageData);
        $fileName = uniqid() . '.' . $extension;

        $imageData = base64_decode($imageData);
        $img_path = "/tmpimg_" . rand(1000, 9999) . time() . "." . $extension;
        if ($imageData && $extension) {
            $image_upload = file_put_contents($upload_dir["path"] . $img_path, $imageData);

            //HANDLE UPLOADED FILE
            if (!function_exists('wp_handle_sideload')) {

                require_once( ABSPATH . 'wp-admin/includes/file.php' );
            }

            // Without that I'm getting a debug error!?
            if (!function_exists('wp_get_current_user')) {

                require_once( ABSPATH . 'wp-includes/pluggable.php' );
            }

            // @new
            $file = array();
            $file['error'] = '';
            $file['tmp_name'] = $upload_dir["path"] . $img_path;
            $file['name'] = $img_path;
            $file['type'] = 'image/' . $extension;
            $file['size'] = filesize($upload_dir["path"] . $img_path);

            // upload file to server
            // @new use $file instead of $image_upload
            $file_return = wp_handle_sideload($file, array('test_form' => false));

            $filename = $file_return['file'];
            $attachment = array(
                'post_mime_type' => $file_return['type'],
                'post_title' => preg_replace('/\.[^.]+$/', '', basename($img_path)),
                'post_content' => '',
                'post_status' => 'inherit',
                'guid' => $wp_upload_dir['url'] . '/' . basename($img_path)
            );
            $tmp_attach_id = wp_insert_attachment($attachment, $filename, 289);
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attach_data = wp_generate_attachment_metadata($tmp_attach_id, $filename);
            wp_update_attachment_metadata($tmp_attach_id, $attach_data);
            update_post_meta($tmp_attach_id, "_is_tmp", 1);

            $post_thumbnail_id = $tmp_attach_id;
        }
    }

    $author_id = get_current_user_id();
//print_r($product);

    $dokan_settings = get_user_meta($author_id, "dokan_profile_settings", true);

    $theme_store_name = $dokan_settings["store_name"];
    $public_support_link = $dokan_settings["public_support_link"];


    $theme_store_img = wp_get_attachment_image_src($dokan_settings["gravatar"], "square_crop");
    $theme_store_link = get_bloginfo("url") . "/store/" . get_the_author_meta('user_nicename', $author_id);


    $cat_term_link = get_term_link($product_cat);
    $cat_term_name = $product_cat->name;
    $init_time = time();

    if ($edit_prod_id) {
        $_pf = new WC_Product_Factory();
        $product = $_pf->get_product($edit_prod_id);
        $post = get_post($edit_prod_id);
        $average = ($product->get_average_rating());
        $rating_count = $product->get_rating_count();
        if (!$tmp_attach_id) {
            $post_thumbnail_id = get_post_thumbnail_id($post->ID);
        }
    } else {
        $average = 0;
        $rating_count = 0;
    }
} else {
    $average = ($product->get_average_rating());
    $rating_count = $product->get_rating_count();
    $init_time = get_the_time("U", $prod_id);

    if ((get_post_meta($prod_id, "new_info", true) || get_post_meta($prod_id, "new_info_save", true)) && $show_new == 1 && ($author_id == get_current_user_id() || current_user_can('edit_others_pages'))) {
        $the_title = get_post_meta($prod_id, "new_post_title", true);
        $product_cats = array(get_post_meta($prod_id, "new_product_cat", true));
        $product_cat = get_term_by("id", $product_cats[0], "product_cat");
        $the_content = get_post_meta($prod_id, "new_post_content", true);
        $ext_price = get_post_meta(get_the_ID(), "new_regular_price_extended", true);
        $reg_price = get_post_meta(get_the_ID(), "new_regular_price", true);
        $price_html = "$" . number_format($reg_price, 2);
        $bootstrap_ver = get_post_meta($prod_id, "new_bootstrap_ver", true);
        $updated_time = get_post_meta($prod_id, "new_updated_time", true);
        $changelog = get_post_meta($prod_id, "new_changelog", true);
        $current_ver = get_post_meta($prod_id, "new_theme_version", true);
        $preview_url = get_post_meta($prod_id, "new_preview_url", true);

        $post_thumbnail_id = get_post_meta($prod_id, "new_thumbnail_id", true);
        if (!$post_thumbnail_id && (get_post_meta($prod_id, "new_info", true) || get_post_meta($prod_id, "new_info_save", true))) {
            $post_thumbnail_id = get_post_thumbnail_id($post->ID);
        }

        $cat_term_link = get_term_link($product_cat);
        $cat_term_name = $product_cat->name;
    } else {
        $the_title = get_the_title();
        $product_cats = wp_get_post_terms(get_the_ID(), 'product_cat');
        $product_cat = $product_cats[0];
        $the_content = get_post($prod_id)->post_content;
        $ext_price = get_post_meta(get_the_ID(), "_regular_price_extended", true);
        $reg_price = $product->get_price();
        $price_html = $product->get_price_html();
        $bootstrap_ver = get_post_meta($prod_id, "bootstrap_ver", true);
        $updated_time = get_post_meta($prod_id, "updated_time", true);
        $changelog = get_post_meta($prod_id, "changelog", true);
        $current_ver = get_post_meta($prod_id, "theme_version", true);
        $preview_url = get_post_meta($prod_id, "preview_url", true);

        $post_thumbnail_id = get_post_thumbnail_id($post->ID);

        $cat_term_link = get_term_link($product_cat);
        $cat_term_name = $product_cat->name;
    }

    $the_preview_url = get_bloginfo("url") . "/preview/?theme_id=" . get_the_ID() . "&show_new=" . $show_new;
}

$theme_store_img = $theme_store_img[0];

if (isset($_GET["saved"]) || (get_post_meta($prod_id, "new_info", true) && ($author_id == get_current_user_id() || current_user_can('edit_others_pages')))) {
    ?>
    <div style="margin-top: 10px; margin-bottom: 30px;">
        <?php
        if (isset($_GET["saved"])) {
            ?>
            <div class="alert alert-success">Congrats! Your theme has been updated!</div>
            <?php
        } elseif ($show_new && !isset($_GET["prod_id"])) {
            ?>
            <div class="alert alert-warning">You're viewing a newer version of the theme that's not yet been approved. Please <a href="<?php the_permalink(); ?>">click here</a> to see the live version.</div>
            <?php
        } elseif (!isset($_GET["prod_id"])) {
            ?>
            <div class="alert alert-warning">There is a newer version of this theme that has not yet been approved. Please <a href="<?php
                the_permalink();
                echo "?show_new=1"
                ?>">click here</a> to see it</div>
                <?php
            }
            ?>
    </div>
    <?php
}
?>
<h1 class="d-none d-lg-block mb-3"><?php echo $the_title; ?></h1>
<div class="row">
    <div class="col-lg-8 mb-md-0 mb-3">
        <div id="product-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="feature-screenshot">
                <?php
                global $post, $product;
                $columns = apply_filters('woocommerce_product_thumbnails_columns', 4);
                $thumbnail_size = "large_crop";

//apply_filters('woocommerce_product_thumbnails_large_size', 'full');

                $full_size_image = wp_get_attachment_image_src($post_thumbnail_id, $thumbnail_size);

                $placeholder = has_post_thumbnail() ? 'with-images' : 'without-images';
                $wrapper_classes = apply_filters('woocommerce_single_product_image_gallery_classes', array(
                    'woocommerce-product-gallery',
                    'woocommerce-product-gallery--' . $placeholder,
                    'woocommerce-product-gallery--columns-' . absint($columns),
                    'images',
                ));
                ?>
                <div class="" data-columns="<?php echo esc_attr($columns); ?>" style="opacity: 1; transition: opacity .25s ease-in-out;">
                    <?php
                    $attributes = array(
                        'title' => get_post_field('post_title', $post_thumbnail_id),
                        'data-caption' => get_post_field('post_excerpt', $post_thumbnail_id),
                        'data-src' => $full_size_image[0],
                        'data-large_image' => $full_size_image[0],
                        'data-large_image_width' => $full_size_image[1],
                        'data-large_image_height' => $full_size_image[2],
                    );

                    if ($full_size_image[0]) {
                        $html = '<div data-thumb="' . $full_size_image[0] . '" class="woocommerce-product-gallery__image"><a href="' . esc_url($full_size_image[0]) . '">';
                        $img_obj = wp_get_attachment_image($post_thumbnail_id, 'large_crop', false, $attributes);
                        $html .= $img_obj;
                        $html .= '</a></div>';
                    } else {
                        $html = '<div class="woocommerce-product-gallery__image--placeholder">';
                        $html .= sprintf('<img src="%s" alt="%s" class="wp-post-image" />', esc_url(wc_placeholder_img_src()), esc_html__('Awaiting product image', 'woocommerce'));
                        $html .= '</div>';
                    }

                    echo $html;

                    //echo apply_filters('woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id);
                    //do_action('woocommerce_product_thumbnails');
                    ?>
                </div>
                <a class="feature-screenshot__overlay" target="_blank" href="<?php echo $the_preview_url; ?>">
                    <button class="btn btn-inverted">Launch live preview</button>
                </a>
            </div>

            <!-- Responsive sidebar put below the theme -->
            <div class="d-lg-none">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="mt-3 mb-1"><?php echo $the_title; ?></h4>
                        <div class="dropdown">
                            <a class="dropdown-toggle link--dark" js-price-dropdown="true" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Standard License</a>
                            <div class="dropdown-menu dropdown-menu--xl dropdown-menu--centered dropdown-menu--has-triangle">
                                <button class="dropdown-block-item switch_price_prod" data-type="Standard License" data-price="<?php echo $reg_price; ?>" data-price_label="<?php echo "$" . number_format($reg_price, 2); ?>" data-label="Standard License">
                                    <div class="d-flex justify-content-between align-items-center mb-2"><span>Standard License</span><span class="d-flex align-items-center"><?php echo $price_html; ?></span></div>
                                    <ul class="fs-13 text-gray-soft mb-2">
                                        <li>Use for a single product</li>
                                        <li>Non-paying users only</li>
                                    </ul>
                                    <p class="fs-11 text-gray-soft">Read the full <a href="<?php echo get_bloginfo("url"); ?>/licenses#fullStandardLicense">Standard License</a></p>
                                </button>
                                <?php if ($ext_price) { ?>
                                    <div class="dropdown-divider"></div>
                                    <button class="dropdown-block-item switch_price_prod" data-type="Extended License" data-price="<?php echo $ext_price; ?>" data-price_label="<?php echo "$" . number_format($ext_price, 2); ?>" data-label="Extended License">
                                        <div class="d-flex justify-content-between align-items-center mb-2"><span>Extended License</span><span class="d-flex align-items-center">$<?php echo number_format($ext_price, 2); ?></span></div>
                                        <ul class="fs-13 text-gray-soft mb-2">
                                            <li>Use for a single product</li>
                                            <li>Paying users allowed</li>
                                        </ul>
                                        <p class="fs-11 text-gray-soft">Read the full <a href="<?php echo get_bloginfo("url"); ?>/licenses#fullExtendedLicense">Extended License</a></p>
                                    </button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <h3 class="d-flex align-items-center" js-price-value="main_price_div"><?php echo $price_html; ?></h3>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a class="btn btn-brand btn-block" target="_blank" href="<?php echo $the_preview_url; ?>">Live preview</a>
                    <form action="<?php echo get_bloginfo("url") . "/cart/"; ?>" method="POST" class="d-block w-100">
                        <input type="hidden" js-license-type="license_type" name="license_type" value="Standard License" />
                        <input type="hidden" name="add-to-cart" value="<?php the_ID(); ?>" />
                        <button type="submit" class="btn btn-brand btn-block btn-checkout mt-0 ml-1"> <span class="btn-text">Add to cart</span></a>
                    </form>
                </div>
                <div class="theme-purchases">
                    <div class="theme-purchases__item">
                        <a class="theme-purchases__item__inner text-center" data-toggle="tab" href="#reviews-tab" role="tab" js-handle="review-toggler">
                            <ul class="rating justify-content-center">
                                <?php
                                for ($i = 0; $i < 5; $i++) {
                                    if ($i < $average) {
                                        $cl = 'rating__item--active';
                                    } else {
                                        $cl = "";
                                    }
                                    echo '<li class="rating__item ' . $cl . '"></li>';
                                }
                                ?>

                            </ul>

                            <p><?php
                                if ($average) {
                                    echo number_format($average, 2) . "/5 ";
                                }
                                ?>(<?php echo $rating_count; ?> reviews)</p>
                        </a>
                        <div class="theme-purchases__item__inner text-center">
                            <h5 class="mb-0"><i class="bootstrap-themes-icon-cart"></i><?php
                                $count = get_post_meta($post->ID, 'completed_total_sales', true);
                                if (!$count) {
                                    echo 0;
                                } else {
                                    echo $count;
                                }
                                ?></h5>
                            <p>Purchases</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center has-border">
                <ul class="nav sub-nav sub-nav--has-border" role="tablist">
                    <li class="nav-item"><a class="nav-link sub-nav-link active" data-toggle="tab" href="#description-tab" role="tab">Description</a></li>
                    <li class="nav-item"><a class="nav-link sub-nav-link" data-toggle="tab" href="#reviews-tab" role="tab">Reviews</a></li>
                    <?php if (strlen(trim(strip_tags($changelog)))) { ?>
                        <li class="nav-item"><a class="nav-link sub-nav-link" data-toggle="tab" href="#changelog-tab" role="tab">Changelog</a></li>
                    <?php } ?>
                </ul>
                <ul class="d-none list-social justify-content-end">
                    <li class="list-social__item">Share:</li>
                    <li class="list-social__item"><a class="bootstrap-themes-icon-facebook-squared list-social__link" target="_blank" href="http://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_the_permalink()); ?>&title=<?php echo urlencode(get_the_title()); ?>"></a></li>
                    <li class="list-social__item"><a class="bootstrap-themes-icon-pinterest-squared list-social__link" target="_blank" href="http://pinterest.com/pin/create/bookmarklet/?media=<?php echo urlencode(get_the_post_thumbnail_url()); ?>&url=<?php echo urlencode(get_the_permalink()); ?>&is_video=false&description=<?php echo urlencode(get_the_title()); ?>"></a></li>
                    <li class="list-social__item"><a class="bootstrap-themes-icon-twitter list-social__link" target="_blank" href="http://twitter.com/intent/tweet?status=<?php echo urlencode(get_the_title()); ?>+<?php echo urlencode(get_the_permalink()); ?>"></a></li>
                </ul>

                <?php if ($author_id == get_current_user_id()) { ?>
                    <a class="btn btn-xs btn-outline-brand justify-content-end theme-preview--hidden" href="<?php echo get_bloginfo("url") . "/my-account/vendor/products/?action=edit&product_id=" . ($post->ID); ?>">Edit <span class="d-none d-md-inline">theme</span></a>
                <?php } ?>

                <!-- Commented out as this feature will be disabled for now -->

            </div>
            <div class="tab-content">
                <div class="tab-pane fade show mt-2 mt-lg-5 active" id="description-tab" role="tabpanel">
                    <div class="theme-description__list d-lg-none mb-4">
                        <?php if ($bootstrap_ver) { ?>
                            <div class="theme-description__list__item"><span class="theme-description__item__title">Bootstrap</span><span><?php echo $bootstrap_ver; ?></span></div>
                        <?php } ?>
                        <div class="theme-description__list__item"><span class="theme-description__item__title">Released</span><span><?php echo human_time_diff($init_time, current_time('timestamp')) . ' ago'; ?></span></div>
                        <?php if ($updated_time) { ?>
                            <div class="theme-description__list__item"><span class="theme-description__item__title">Updated</span><span><?php echo human_time_diff($updated_time, current_time('timestamp')) . ' ago'; ?></span></div>
                        <?php } ?>
                        <?php if ($current_ver) { ?>
                            <div class="theme-description__list__item"><span class="theme-description__item__title">Version</span><span><?php echo $current_ver; ?></span></div>
                        <?php } ?>
                        <div class="theme-description__list__item"><span class="theme-description__item__title">Category</span>
                            <?php
                            if ($cat_term_name) {
                                echo '<a href="' . $cat_term_link . '">' . $cat_term_name . '</a>';
                            }
                            ?>
                        </div>
                    <?php if (wc_customer_bought_product($current_user->user_email, $current_user->ID, $post->ID)) { ?>
                        <div class="theme-description__list__item align-items-center"><span class="theme-description__item__title">Questions?</span><a class="btn btn-xs btn-outline-brand" href="<?php echo $support_link; ?>" target="_blank">Contact for support</a></div>
                    <?php }elseif ($public_support_link) { ?>
                        <div class="theme-description__list__item align-items-center"><span class="theme-description__item__title">Questions?</span><a class="btn btn-xs btn-outline-brand" href="<?php echo $public_support_link; ?>" target="_blank">Contact Seller</a></div>
                    <?php } ?>
                    </div>
                    <div class="theme-description">
                        <?php echo apply_filters('the_content', $the_content); ?>
                    </div>
                </div>
                <div class="tab-pane fade" id="reviews-tab" role="tabpanel">
                    <?php
                    $comments_query = new WP_Comment_Query;
                    $comments = $comments_query->query(array('post_id' => $post->ID, "parent" => 0));
                    ?>
                    <?php
                    if (wc_customer_bought_product($current_user->user_email, $current_user->ID, $post->ID)) {
                        foreach ($comments as $comment) {
                            if ($comment->user_id == get_current_user_id()) {
                                $my_review = $comment->comment_ID;
                                //print_r($comment);
                                if (!$comment->comment_approved) {
                                    $see_review = "hidden";
                                    $no_action = "border-0 p-0";
                                }
                            }
                        }
                        ?>

                        <div class="<?php echo $no_action; ?> theme-review__submission-prompt d-flex justify-content-between align-items-center">
                            <?php
                            if ($my_review) {
                                $start_review = "hidden";
                            } else {
                                $see_review = "hidden";
                            }
                            ?>
                            <div id="see_review_msg" class="<?php echo $start_review; ?>">
                                <h6 class="mb-0">Share a review. Get a discount.</h6>
                                <p class="text-gray fs-14 mr-3">Leave an honest review to recieve 15% off your next purchase</p>
                            </div>
                            <div id="start_review_msg" class="<?php echo $see_review; ?>">
                                <h6 class="mb-0">Thanks for leaving a review!</h6>
                                <p class="text-gray fs-14">We appreciate your feedback.</p>
                            </div>

                            <a class="btn btn-brand btn-sm <?php echo $see_review; ?>" id="see_review_but" href="#comment_<?php echo $my_review; ?>">See your review</a>
                            <a class="btn btn-brand btn-sm <?php echo $start_review; ?>" id="start_review_but" href="#">Start a review</a>
                        </div>
                        <?php
                    } elseif (get_current_user_id()) {
                        if ($author_id != get_current_user_id()) {
                            ?>
                            <div class="theme-review__submission-prompt d-flex justify-content-between align-items-center">

                                <div id="start_review_msg" class="<?php echo $see_review; ?>">
                                    <h6 class="mb-0">You must purchase this theme to leave a review.</h6>
                                    <p class="text-gray fs-14">Once purchased, you will have the option to leave a review.</p>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        ?>
                        <div class="theme-review__submission-prompt d-flex justify-content-between align-items-center">

                            <div id="start_review_msg" class="<?php echo $see_review; ?>">
                                <h6 class="mb-0">You must purchase this theme to leave a review.</h6>
                                <p class="text-gray fs-14">If you have already purchased it, <a href="<?php echo get_bloginfo("url"); ?>/signin/">login</a> to leave a review.</p>
                            </div>
                        </div>
                        <?php
                    }
                    ?>

                    <div id="review_form_response1" style="display:none;" class="alert"></div>
                    <div class="theme-review">
                        <form action="" id="review_submit_form">
                            <input type="hidden" name="action" value="review_submit" />
                            <input type="hidden" name="post_id" value="<?php echo $post->ID; ?>" />
                            <div id="review_submit_form_overlay" >
                                <i class="fa fa-spinner fa-spin"></i>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-8 mb-3">
                                    <label class="col-form-label" for="reviewTitle">Review title</label>
                                    <input class="form-control required" type="text" name="reviewTitle" id="reviewTitle">
                                    <div class="invalid-feedback"><?php _e("Please provide a title"); ?></div>
                                </div>
                                <div class="form-group col-md-4 mb-3">
                                    <label class="col-form-label" for="reviewScore">Review</label>

                                    <select class="form-control required" name="reviewScore" id="reviewScore">
                                        <option value="5">&#9733;&#9733;&#9733;&#9733;&#9733; (5/5)</option>
                                        <option value="4">&#9733;&#9733;&#9733;&#9733;&#9734; (4/5)</option>
                                        <option value="3">&#9733;&#9733;&#9733;&#9734;&#9734; (3/5)</option>
                                        <option value="2">&#9733;&#9733;&#9734;&#9734;&#9734; (2/5)</option>
                                        <option value="1">&#9733;&#9734;&#9734;&#9734;&#9734; (1/5)</option>
                                    </select>
                                    <div class="invalid-feedback"><?php _e("Please select a score"); ?></div>
                                </div>
                                <div class="form-group col-12">
                                    <label class="col-form-label" for="reviewBody">Review</label>
                                    <span class="form-sublink" id="reviewBody_count">0/500</span>
                                    <textarea class="form-control required" name="reviewBody" id="reviewBody"></textarea>
                                    <div class="invalid-feedback"><?php _e("Please enter a review"); ?></div>
                                </div>
                                <div class="form-group col-12">
                                    <button class="btn btn-brand btn-block" id="post_review" type="button">Post review</button>
                                </div>
                                <script>
                                    jQuery(document).ready(function () {
                                        jQuery("#reviewBody").on("keyup change", function () {
                                            var tex = jQuery(this).val();
                                            if (tex.length > 500) {
                                                tex = tex.substring(0, 500);
                                                jQuery(this).val(tex);
                                            }
                                            jQuery("#reviewBody_count").html(tex.length + "/500");
                                        });

                                        jQuery("#reviewBody").change();
                                    });

                                </script>
                            </div>
                        </form>
                    </div>
                    <div id="review_list" class="mt-4">
                        <?php echo get_prod_reviews($post->ID); ?>
                    </div>

                </div>
                <?php if (strlen(trim(strip_tags($changelog)))) { ?>
                    <div class="tab-pane fade mt-4 mt-lg-5" id="changelog-tab" role="tabpanel">
                        <div class="theme-description">
                            <?php echo apply_filters('the_content', $changelog); ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <?php //echo do_action("woocommerce_tabs_bootstrap");                    ?>
            <div class="summary entry-summary">

                <?php
                /**
                 * woocommerce_single_product_summary hook.
                 *
                 * @hooked woocommerce_template_single_title - 5
                 * @hooked woocommerce_template_single_rating - 10
                 * @hooked woocommerce_template_single_price - 10
                 * @hooked woocommerce_template_single_excerpt - 20
                 * @hooked woocommerce_template_single_add_to_cart - 30
                 * @hooked woocommerce_template_single_meta - 40
                 * @hooked woocommerce_template_single_sharing - 50
                 * @hooked WC_Structured_Data::generate_product_data() - 60
                 */
//do_action('woocommerce_single_product_summary');
//echo do_action("woocommerce_images_test");
                ?>

            </div><!-- .summary -->
        </div>
    </div>
    <div class="col-lg-4 d-none d-lg-block pl-xs-0 pl-lg-5">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="dropdown">
                <a class="dropdown-toggle link--dark" js-price-dropdown="true" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Standard License</a>
                <div class="dropdown-menu dropdown-menu--xl dropdown-menu--centered dropdown-menu--has-triangle">
                    <button class="dropdown-block-item switch_price_prod" data-type="Standard License" data-price="<?php echo $reg_price; ?>" data-price_label="<?php echo "$" . number_format($reg_price, 2); ?>" data-label="Standard License">
                        <div class="d-flex justify-content-between align-items-center mb-2"><span>Standard License</span><span class="d-flex align-items-center"><?php echo $price_html; ?></span></div>
                        <ul class="fs-13 text-gray-soft mb-2">
                            <li>Use for a single product</li>
                            <li>Non-paying users only</li>
                        </ul>
                        <p class="fs-11 text-gray-soft">Read the full <a href="<?php echo get_bloginfo("url"); ?>/licenses#fullStandardLicense">Standard License</a></p>
                    </button>
                    <?php if ($ext_price) { ?>
                        <div class="dropdown-divider"></div>
                        <button class="dropdown-block-item switch_price_prod" data-type="Extended License" data-price="<?php echo $ext_price; ?>" data-price_label="<?php echo "$" . number_format($ext_price, 2); ?>" data-label="Extended License">
                            <div class="d-flex justify-content-between align-items-center mb-2"><span>Extended License</span><span class="d-flex align-items-center">$<?php echo number_format($ext_price, 2); ?></span></div>
                            <ul class="fs-13 text-gray-soft mb-2">
                                <li>Use for a single product</li>
                                <li>Paying users allowed</li>
                            </ul>
                            <p class="fs-11 text-gray-soft">Read the full <a href="<?php echo get_bloginfo("url"); ?>/licenses#fullExtendedLicense">Extended License</a></p>
                        </button>
                    <?php } ?>
                </div>
            </div>
            <h2 class="d-flex align-items-center" js-price-value="main_price_div"><?php echo $price_html; ?></h2>

        </div>
        <form action="<?php echo get_bloginfo("url") . "/cart/"; ?>" method="POST" class=" btn-block">
            <input type="hidden" js-license-type="license_type" name="license_type" value="Standard License" />
            <input type="hidden" name="add-to-cart" value="<?php the_ID(); ?>" />
            <button type="submit" class="btn btn-brand btn-block btn-checkout"> <span class="btn-text">Add to cart</span></button>
        </form>
        <a class="btn btn-outline-brand btn-block mb-4 ml-0" target="_blank" href="<?php echo $the_preview_url; ?>">Live preview</a>
        <div class="theme-purchases">
            <div class="theme-purchases__item">
                <a class="theme-purchases__item__inner text-center" data-toggle="tab" href="#reviews-tab" role="tab" js-handle="review-toggler">
                    <ul class="rating justify-content-center">
                        <?php
                        for ($i = 0; $i < 5; $i++) {
                            if ($i < $average) {
                                $cl = 'rating__item--active';
                            } else {
                                $cl = "";
                            }
                            echo '<li class="rating__item ' . $cl . '"></li>';
                        }
                        ?>

                    </ul>

                    <p><?php
                        if ($average) {
                            echo number_format($average, 2) . "/5 ";
                        }
                        ?>(<?php echo $rating_count; ?> reviews)</p>
                </a>
                <div class="theme-purchases__item__inner text-center">
                    <h5 class="mb-0"><i class="bootstrap-themes-icon-cart"></i><?php
                        $count = get_post_meta($post->ID, 'completed_total_sales', true);
                        if (!$count) {
                            echo 0;
                        } else {
                            echo $count;
                        }
                        ?></h5>
                    <p>Purchases</p>
                </div>
            </div>
            <div class="theme-purchases__item">
                <div class="theme-purchases__item__inner px-0">
                    <ul class="guarantees">
                        <li> <i class="bootstrap-themes-icon-check-circle"></i>Reviewed by the Bootstrap team</li>
                        <li><i class="bootstrap-themes-icon-check-circle"></i><a href="https://themes.zendesk.com/hc/en-us/articles/360000006291-How-do-I-get-help-with-the-theme-I-purchased-">6 months technical support</a></li>
                        <li><i class="bootstrap-themes-icon-check-circle"></i>100% money back guarantee</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="theme-description__list">
            <?php if ($bootstrap_ver) { ?>
                <div class="theme-description__list__item"><span class="theme-description__item__title">Bootstrap</span><span><?php echo $bootstrap_ver; ?></span></div>
            <?php } ?>
            <div class="theme-description__list__item"><span class="theme-description__item__title">Released</span><span><?php echo human_time_diff($init_time, current_time('timestamp')) . ' ago'; ?></span></div>

            <?php if ($updated_time) { ?>
                <div class="theme-description__list__item"><span class="theme-description__item__title">Updated</span><span><?php echo human_time_diff($updated_time, current_time('timestamp')) . ' ago'; ?></span></div>
            <?php } ?>
            <?php if ($current_ver) { ?>
                <div class="theme-description__list__item"><span class="theme-description__item__title">Version</span><span><?php echo $current_ver; ?></span></div>
            <?php } ?>
            <div class="theme-description__list__item"><span class="theme-description__item__title">Category</span>
                <?php
                if ($cat_term_name) {
                    echo '<a href="' . $cat_term_link . '">' . $cat_term_name . '</a>';
                }
                ?>
            </div>
            <?php if (wc_customer_bought_product($current_user->user_email, $current_user->ID, $post->ID)) { ?>
                <div class="theme-description__list__item align-items-center"><span class="theme-description__item__title">Questions?</span><a class="btn btn-xs btn-outline-brand" href="<?php echo $support_link; ?>" target="_blank">Contact for support</a></div>
            <?php }elseif ($public_support_link) { ?>
                <div class="theme-description__list__item align-items-center"><span class="theme-description__item__title">Questions?</span><a class="btn btn-xs btn-outline-brand" href="<?php echo $public_support_link; ?>" target="_blank">Contact Seller</a></div>
            <?php } ?>
            <div class="theme-description__list__item">
                <a class="profile-author" href="<?php echo $theme_store_link; ?>">
                    <div class="profile-author__logo">
                        <img class="profile-author__img" src="<?php echo $theme_store_img; ?>" alt="">
                    </div>
                    <div class="profile-author__author__description">
                        <p>Created by</p>
                        <h6 class="profile-logo__author__title"><?php echo $theme_store_name; ?></h6>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Related Products -->
<!-- Note: It just didn't work at all. It doesn't show products in the same category when they exist, so I moved it all to "notes.txt" for reference so I can ship, but it needs to be fixed.  -->

<?php
    /**
     * woocommerce_after_single_product_summary hook.
     *
     * @hooked woocommerce_output_product_data_tabs - 10
     * @hooked woocommerce_upsell_display - 15
     * @hooked woocommerce_output_related_products - 20
     */
//do_action('woocommerce_after_single_product_summary');
    $product_cats = wp_get_post_terms(get_the_ID(), 'product_cat');
    if (count($product_cats)) {
        $prod_cat = $product_cats[0];

        $related = new WP_Query(array(
            "post_type" => "product",
            "posts_per_page" => 3,
            "post__not_in" => array(get_the_ID()),
            "orderby" => "meta_value_num",
            'meta_key'  => 'week_sales',
            "tax_query" => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'term_id',
                    'terms' => $prod_cat->term_id,
                ),
            ),
        ));
        
        if($related->found_posts>0){
        ?>
          <div class="theme-cards-holder mt-5 pt-5" style="border-top: 1px solid #D5DCE5; border-bottom-width: 0; margin-bottom: -60px;">
            <div class="theme-cards__heading">
                <div>
                  <h5 class="theme-cards__title"><?php echo $prod_cat->name; ?> Themes</h5>
                  <p class="text-gray-soft"><?php _e("Related themes in the same category."); ?></p>
                </div>
                <?php if ($related->found_posts > 3) { ?>
                  <a class="theme-cards__heading__button btn btn-outline-brand btn-sm" href="<?php echo get_term_link($prod_cat); ?>">View All</a>
                <?php } ?>
            </div>
            <ul class="row">
                <?php
                while ($related->have_posts()) : $related->the_post();
                    ?>

                    <?php
                    /**
                     * woocommerce_shop_loop hook.
                     *
                     * @hooked WC_Structured_Data::generate_product_data() - 10
                     */
                    do_action('woocommerce_shop_loop');
                    ?>

                    <?php wc_get_template_part('content', 'product'); ?>

                    <?php
                endwhile;

                wp_reset_query();
                ?>
            </ul>
          </div>
    <?php }
    }
    ?>

<!-- #product-<?php the_ID(); ?> -->
<script>
    jQuery(document).ready(function () {
        jQuery("#review_submit_form").submit(function () {
            return false;
        })

        jQuery("#start_review_but").click(function () {
            jQuery("#review_submit_form").slideDown();
            return false;
        });

        console.log(jQuery("#review_submit_form").serialize());

        jQuery("#post_review").click(function () {
            console.log(jQuery("#review_submit_form").serialize());
            if (!validate_form(jQuery("#review_submit_form"))) {
                jQuery("#review_submit_form_overlay").fadeIn();
                jQuery.ajax({
                    url: "<?php echo get_bloginfo("url"); ?>/wp-admin/admin-ajax.php",
                    method: "POST",
                    data: jQuery("#review_submit_form").serialize(),
                    context: document.body
                }).done(function (data) {
                    var obj = JSON.parse(data);

                    if (obj.success) {
                        jQuery("#review_submit_form").slideUp();
                        jQuery("#review_submit_form_overlay").fadeOut();
                        jQuery("#reviewTitle").val("");
                        jQuery("#reviewBody").val("");
                        jQuery("#reviewScore").val(5);
                        jQuery("#review_form_response").removeClass("alert-danger").addClass("alert-success").html(obj.msg).slideDown();
                        var the_data = obj.html;
                        jQuery("#review_list").fadeOut(200, function () {
                            jQuery(this).html(the_data).fadeIn(200);
                        });
                        jQuery("#start_review_but").addClass("hidden");
                        jQuery("#see_review_but").removeClass("hidden").attr("href", "#comment_" + obj.rid);

                        jQuery("#start_review_msg").addClass("hidden");
                        jQuery("#see_review_msg").removeClass("hidden");

                    } else {
                        jQuery("#review_submit_form_overlay").fadeOut();
                        jQuery("#review_form_response").removeClass("alert-success").addClass("alert-danger").html(obj.msg).slideDown();
                    }
                });
            }
        });
    });
</script>
<?php do_action('woocommerce_after_single_product'); ?>

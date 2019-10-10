<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
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
    exit; // Exit if accessed directly
}

global $product;

// Ensure visibility
if (empty($product) || !$product->is_visible()) {
    return;
}
?>
<li class="col-md-4 col-6">
    <div class="theme-card">
        <div class="theme-card__body">
            <a class="d-block" href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail("smaller_crop", array('class' => 'theme-card__img')); ?>
            </a>

            <a class="theme-card__body__overlay btn btn-brand btn-sm" target="_blank" href="<?php echo get_bloginfo("url"); ?>/preview/?theme_id=<?php the_ID(); ?>">Live preview</a>
        </div>
        <div class="theme-card__footer">
            <div class="theme-card__footer__item"><a class="theme-card__title mr-1" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                <p class="theme-card__info">
                    <?php
                    $product_cats = wp_get_post_terms(get_the_ID(), 'product_cat');
                    if (count($product_cats)) {
                        echo '<ul class="prod_cats_list">';
                        $prod_cat = $product_cats[0];
                        echo '<li><a href="' . get_term_link($prod_cat) . '">' . $prod_cat->name . '</a></li>';
                        echo '</ul>';
                    }
                    ?>
                </p>
            </div>
            <div class="theme-card__footer__item">
                <p class="theme-card__price"><?php echo $product->get_price_html(); ?></p>

                <ul class="rating">
                    <?php
                    $average = round($product->get_average_rating());
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
            </div>
        </div>
    </div>
</li>


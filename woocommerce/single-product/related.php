<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     10.3.0
 */
if (!defined('ABSPATH')) {
    exit;
}

if ($related_products):
    if (function_exists('wp_increase_content_media_count')) {
        $content_media_count = wp_increase_content_media_count(0);
        if ($content_media_count < wp_omit_loading_attr_threshold()) {
            wp_increase_content_media_count(wp_omit_loading_attr_threshold() - $content_media_count);
        }
    }
    ?>

    <section class="related section--pt_S section--pb_M o-hid">
        <div class="container related__container">
            <h2 class="section-title ff--title related__title mb--20 l-mb--24">Схожі товари</h2>

            <!-- Embla Slider for Related Products -->
            <div class="embla related-slider">
                <div class="embla__viewport">
                    <div class="embla__container flex-row catalogue__track">
                        <?php
                        // Filter to inject Embla class to product cards
                        $add_embla_class = function ($classes) {
                            $classes[] = 'embla__slide catalogue__slide';
                            return $classes;
                        };
                        add_filter('woocommerce_post_class', $add_embla_class);

                        foreach ($related_products as $related_product):
                            $post_object = get_post($related_product->get_id());
                            setup_postdata($GLOBALS['post'] = $post_object);
                            wc_get_template_part('content', 'product');
                        endforeach;

                        remove_filter('woocommerce_post_class', $add_embla_class);
                        wp_reset_postdata();
                        ?>
                    </div>
                </div>
                <!-- Pagination Dots -->
                <div class="embla__dots flex-row flex-center gap--8 mt--24 slider-pagination"></div>
            </div>
        </div>
    </section>
    <?php
endif;

wp_reset_postdata();
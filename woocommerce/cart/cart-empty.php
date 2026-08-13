<?php
/**
 * Empty cart page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-empty.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined('ABSPATH') || exit;

// /*
//  * @hooked wc_empty_cart_message - 10
//  */
// do_action( 'woocommerce_cart_is_empty' );

if (wc_get_page_id('shop') > 0): ?>
    <div class=" mx mt--32 text-center">
        <h1 class="ff--title text--h4 t--center">Ваш кошик порожній</h1>
        <div class="cart-items__empty mx">
            <picture>
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/pics/cart-empty-drawing.png'); ?>"
                     alt="<?php esc_attr_e('Кошик порожній', 'woocommerce'); ?>"
                     loading="lazy" width="200" height="200" class="contain-image mx">
            </picture>
        </div>


        <a class="button d-block mt--24 btn btn--full_accent cart-footer__btn text--btn-m t-w--500 rel o-hid mx wc-backward<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>"
           href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>">
            <?php
            /**
             * Filter "Return To Shop" text.
             *
             * @since 4.6.0
             * @param string $default_text Default text.
             */
            echo esc_html(apply_filters('woocommerce_return_to_shop_text', __('Return to shop', 'woocommerce')));
            ?>
        </a>

    </div>
<?php endif; ?>
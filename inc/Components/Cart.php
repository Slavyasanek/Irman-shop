<?php

namespace CleanTheme\Components;

use CleanTheme\Helpers;

class Cart {

    public function __construct() {
        // Register AJAX actions for logged-in and guest users
        add_action( 'wp_ajax_clean_update_cart_quantity', [ __CLASS__, 'ajax_update_quantity' ] );
        add_action( 'wp_ajax_nopriv_clean_update_cart_quantity', [ __CLASS__, 'ajax_update_quantity' ] );

        add_action( 'wp_ajax_clean_get_cart_drawer', [ __CLASS__, 'ajax_get_cart_drawer' ] );
        add_action( 'wp_ajax_nopriv_clean_get_cart_drawer', [ __CLASS__, 'ajax_get_cart_drawer' ] );

        // Hook into WooCommerce standard fragment refresh
        add_filter( 'woocommerce_add_to_cart_fragments', [ __CLASS__, 'cart_fragments' ] );
    }

    /**
     * Provide HTML fragments to WooCommerce or AJAX calls.
     */
    public static function cart_fragments( $fragments = [] ) {
        ob_start();
        self::render_inner();
        $fragments['#cart-drawer .backdrop__inner'] = ob_get_clean();

        return $fragments;
    }

    /**
     * AJAX endpoint: Update Item Quantity
     */
    public static function ajax_update_quantity() {
        check_ajax_referer( 'clean-cart-nonce', 'nonce' );

        $cart_key = isset( $_POST['cart_key'] ) ? sanitize_text_field( $_POST['cart_key'] ) : '';
        $quantity = isset( $_POST['quantity'] ) ? max( 0, (int) $_POST['quantity'] ) : 0;

        if ( $cart_key && WC()->cart ) {
            if ( $quantity === 0 ) {
                WC()->cart->remove_cart_item( $cart_key );
            } else {
                WC()->cart->set_quantity( $cart_key, $quantity );
            }

            WC()->cart->calculate_totals();
        }

        wp_send_json_success( [
            'fragments' => self::cart_fragments(),
            'count'     => WC()->cart->get_cart_contents_count(),
        ] );
    }

    /**
     * AJAX endpoint: Get full cart drawer HTML markup
     */
    public static function ajax_get_cart_drawer() {
        wp_send_json_success( [
            'fragments' => self::cart_fragments(),
            'count'     => WC()->cart->get_cart_contents_count(),
        ] );
    }

    public static function empty_cart() {
        $is_cart_empty = WC()->cart && WC()->cart->is_empty();
        ?>
        <div class="cart-items__empty mx <?= $is_cart_empty ? '' : 'd-none'; ?>">
            <picture>
                <img src="<?= esc_url( get_template_directory_uri() . '/assets/pics/cart-empty-drawing.png' ); ?>" 
                     alt="Кошик порожній"
                     loading="lazy" width="200" height="200" class="contain-image">
            </picture>
        </div>
        <?php
    }

    public static function cart_item( $cart_item_key, $cart_item ) {
        /** @var \WC_Product $_product */
        $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
        
        if ( ! $_product || ! $_product->exists() || $cart_item['quantity'] < 1 || ! apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
            return;
        }

        $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
        $product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
        $product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
        $thumbnail_id      = $_product->get_image_id();
        $thumbnail_url     = $thumbnail_id ? wp_get_attachment_image_url( $thumbnail_id, 'mobile' ) : wc_placeholder_img_src( 'thumbnail' );

        // Check if item allows changing quantity (has more than 1 allowed or isn't sold individually)
        $max_qty           = $_product->get_max_purchase_quantity();
        $show_quantity     = ! $_product->is_sold_individually() && ( $max_qty === -1 || $max_qty > 1 );
        ?>
        <div class="cart-item cart-items__item d-flex" data-key="<?= esc_attr( $cart_item_key ); ?>">
            <a class="cart-item__img shrink0 rel o-hid" href="<?= esc_url( $product_permalink ); ?>" aria-label="<?= esc_attr( sprintf( 'Переглянути %s', $product_name ) ); ?>">
                <picture>
                    <img src="<?= esc_url( $thumbnail_url ); ?>" alt="<?= esc_attr( $product_name ); ?>" loading="lazy" width="80" height="80" class="cover-image">
                </picture>
            </a>
            <div class="cart-item__content flex-col j-between grow1">
                <div class="cart-item__row flex-col a-center">
                    <p class="cart-item__title m-0 text--reg mb--8 t-w--500">
                        <?php if ( $product_permalink ) : ?>
                            <a href="<?= esc_url( $product_permalink ); ?>" class="c--black"><?= esc_html( $product_name ); ?></a>
                        <?php else : ?>
                            <?= esc_html( $product_name ); ?>
                        <?php endif; ?>
                    </p>
                    <p class="cart-item__price m-0 text--h4 t-w--500 mb--24"><?= $product_price; ?></p>

                </div>

                <?php if ( $show_quantity ) : 
                    $current_qty  = (int) $cart_item['quantity'];
                    $disable_minus = $current_qty <= 1;
                    $disable_plus  = ( $max_qty > 0 ) && ( $current_qty >= $max_qty );
                ?>
                    <div class="quant-block cart-item__quant-block flex-row" 
                         data-max-quantity="<?= esc_attr( $max_qty ); ?>">
                        <button class="btn quant-block__btn quant-block__btn--minus sq--32 flex-center c--grey text--reg" 
                                data-update="minus" 
                                type="button" 
                                aria-label="Зменшити кількість"
                                <?php disabled( $disable_minus, true ); ?>>-</button>
                        
                        <div class="quant-block__value sq--32 flex-center c--grey text--reg" 
                             data-value="<?= esc_attr( $current_qty ); ?>" 
                             aria-live="assertive">
                            <?= esc_html( $current_qty ); ?>
                        </div>
                        
                        <button class="btn quant-block__btn quant-block__btn--plus sq--32 flex-center c--grey text--reg" 
                                data-update="plus" 
                                type="button" 
                                aria-label="Збільшити кількість"
                                <?php disabled( $disable_plus, true ); ?>>+</button>
                    </div>
                <?php endif; ?>
                
                <?php
                echo apply_filters(
                    'woocommerce_cart_item_remove_link',
                    sprintf(
                        '<button class="btn btn-def-link btn-def-link__black cart-item__delete-btn t-w--50 rel mt--32" type="button" data-cart-remove="%s" aria-label="%s">Видалити</button>',
                        esc_attr( $cart_item_key ),
                        esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), $product_name ) )
                    ),
                    $cart_item_key
                );
                ?>
            </div>
        </div>
        <?php
    }

    /**
     * Renders inner container content (target for outerHTML/innerHTML replacement)
     */
    public static function render_inner() {
        if ( null === WC()->cart ) {
            return;
        }

        $checkout_url  = function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : '#';
        $cart_is_empty = WC()->cart->is_empty();
        $total_price   = WC()->cart->get_cart_subtotal();
        ?>
        <div class="backdrop__inner cart anim abs inset bg--white flex-col">
            <div class="cart-heading cart__heading rel">
                <button class="btn btn--icon sq--32 abs cart-heading__close-btn close-btn" type="button" aria-label="Закрити кошик" data-modal-close>
                    <?= Helpers::get_svg_icon( 'close', 'wh-full' ) ?>
                </button>

                <h5 class="section-title cart-heading__title ff--title">
                    <?php if ( ! $cart_is_empty ) : ?>
                        Кошик(<?= WC()->cart->get_cart_contents_count() ?>)
                    <?php else:?>
                        Ваш кошик пустий
                    <?php endif;?>
                </h5>
            </div>
            
            <div class="cart-items cart__items rel" aria-live="polite">
                <div class="cart-items__list">
                    <?php if ( ! $cart_is_empty ) : ?>
                        <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) : ?>
                            <?php self::cart_item( $cart_item_key, $cart_item ); ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <?php self::empty_cart(); ?>
                
                <div class="cart-items__loader preloader abs wh-full inset anim d-none" aria-hidden="true">
                    <div class="preloader__inner abs o-hid"></div>
                </div>
            </div>

            <div class="cart__footer cart-footer bg--white flex-col sticky w-full">
                <div class="cart-footer__total-price flex-row j-between t-w--500">
                    <div class="cart-footer__title text--btn-m">Загальна сума</div>
                    <div class="cart-footer__val text--h4" aria-atomic="true"><?= $total_price; ?></div>
                </div>

                <div class="cart-footer__btns cart-footer__btns--dir_col flex-col">
                    <a class="btn btn--full_accent cart-footer__btn w-full text--btn-m t-w--500 rel o-hid <?php if ( $cart_is_empty):?>disabled<?php endif;?>" href="<?= esc_url( $checkout_url ) ?>" >
                        Оформити замовлення
                    </a>
                    <button class="btn btn--border_accent cart-footer__btn w-full text--btn-m rel o-hid" type="button" data-modal-close>
                        <span class="rel z1">Продовжити покупки</span>
                    </button>
                </div>
            </div>
        </div>
        <?php
    }

    public static function render() {
        if ( null === WC()->cart ) {
            return;
        }
        ?>
        <div class="backdrop backdrop--cart fixed wh-full inset anim" 
             id="cart-drawer" 
             role="dialog" 
             aria-modal="true" 
             aria-label="Кошик покупок"
             data-modal="cart">
            <?php self::render_inner(); ?>
        </div>
        <?php
    }
}


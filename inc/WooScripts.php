<?php

namespace CleanTheme;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WooScripts {
    public function __construct() {
        add_filter( 'body_class', [$this, 'add_custom_class_to_cart_body'] );
    }

    public function add_custom_class_to_cart_body( $classes ) {
        if ( function_exists( 'is_cart' ) && is_cart() ) {
            $classes[] = 'cart-page';
        }

        if ( is_checkout() && ! is_wc_endpoint_url() ) {
            $classes[] = 'checkout-page';
        }
        return $classes;
    }
}
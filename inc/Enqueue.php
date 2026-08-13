<?php

namespace CleanTheme;

class Enqueue {
    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'dequeque_styles'], 99);

        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts'], 5);
        add_action('after_setup_theme', [$this, 'theme_support']);
        add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
        add_filter('script_loader_tag', [$this, 'add_module_type_attribute'], 10, 3);

        // Dequeue WooCommerce styles that are incorrectly injected into the block editor iframe.
        add_action('enqueue_block_editor_assets', [$this, 'dequeue_editor_styles'], 99);
    }

    public function dequeque_styles() {
        wp_dequeue_style( 'woocommerce-layout' );
        wp_dequeue_style( 'woocommerce-smallscreen' );
        wp_dequeue_style( 'woocommerce-general' );
        wp_dequeue_style('photoswipe');
        wp_dequeue_style('photoswipe-default-skin');
        wp_dequeue_script('wc-add-to-cart');
        wp_dequeue_script('wc-zoom');
        wp_dequeue_script('wc-photoswipe-ui-default');
        wp_dequeue_script('wc-photoswipe');
        wp_dequeue_script('wc-flexslider');
        wp_dequeue_script('wc-single-product');
        wp_dequeue_script( 'wc-cart-fragments' );
        wp_dequeue_script( 'jquery-blockui' );
        wp_dequeue_script( 'woocommerce' );
    }

    public function dequeue_editor_styles() {
        wp_dequeue_style( 'woocommerce-classictheme-editor-fonts-css' );
    }

    public function theme_support() {
        add_theme_support( 'woocommerce' );
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('align-wide');
        add_theme_support('editor-styles');
        remove_action('wp_footer', 'wp_enqueue_global_styles', 1);

        add_editor_style('build/css/style.css');
        add_editor_style('build/css/editor-tweaks.css');
    }

    public function enqueue_scripts() {
        
        // select2 ??

        
        $theme_version = wp_get_theme()->get('Version');
        // Main Stylesheet
        wp_enqueue_style(
            'clean-theme-style',
            get_template_directory_uri() . '/build/css/style.css',
            [],
            $theme_version
        );
        // Main Script (In footer, no dependencies)
        wp_register_script( 'clean-theme-data', false );
        wp_enqueue_script( 'clean-theme-data' );

        $data = array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'clean-cart-nonce' ),
        );

        wp_add_inline_script( 
            'clean-theme-data', 
            'window.cleanThemeData = ' . wp_json_encode( $data ) . ';', 
            'before' 
        );

        wp_enqueue_script(
            'clean-theme-js',
            get_template_directory_uri() . '/build/js/main.js',
            [],
            $theme_version,
            array(
                'in_footer' => true
            )
        );

        if (function_exists('is_product')) {
            if (is_product()) {
                wp_enqueue_style(
                    'product-theme-style',
                    get_template_directory_uri() . '/build/css/product-page.css',
                    [],
                    $theme_version
                );

                wp_enqueue_script(
                    'product-page-js',
                    get_template_directory_uri() . '/build/js/product-page-js.js',
                    [],
                    $theme_version,
                    array(
                        'in_footer' => true
                    )
                );
            }
        }

        
        if (is_shop() || is_product_category()) {
            wp_enqueue_style(
                'shop-theme-style',
                get_template_directory_uri() . '/build/css/shop.css',
                [],
                $theme_version
            );
        }

        if (is_404()) {
            wp_enqueue_style(
                '404-style',
                get_template_directory_uri() . '/build/css/not-found.css',
                [],
                $theme_version
            );
        }

        if (is_checkout()) {
            wp_enqueue_style(
                'checkout-style',
                get_template_directory_uri() . '/build/css/checkout.css',
                [],
                $theme_version
            );
        }

        if ( is_order_received_page() ) {
            wp_enqueue_style(
                'thankyou-style',
                get_template_directory_uri() . '/build/css/thankyou.css',
                [],
                $theme_version
            );
        }
    }

    public function add_module_type_attribute($tag, $handle, $src) {
        if (in_array($handle, ['clean-theme-js', 'product-page-js', 'shop-js', 'acf-products-section-script'], true) || str_starts_with($handle, 'block-')) {
            return '<script type="module" src="' . esc_url($src) . '" id="' . $handle . '"></script>';
        }
        return $tag;
    }
}
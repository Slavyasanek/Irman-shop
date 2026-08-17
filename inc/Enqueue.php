<?php

namespace CleanTheme;

class Enqueue {
    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'dequeque_styles'], 99);

        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts'], 5);
        add_action('after_setup_theme', [$this, 'theme_support']);
        add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
        add_filter('script_loader_tag', [$this, 'add_module_type_attribute'], 10, 3);
        add_action('wp_head', [$this, 'images_preload']);
        add_action('wp_head', [$this, 'critical_css'], 1);

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
        wp_deregister_style('wc-blocks-style');
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

        
        // $theme_version = wp_get_theme()->get('Version');
        // Main Stylesheet
        wp_enqueue_style(
            'clean-theme-style',
            get_template_directory_uri() . '/build/css/style.css',
            [],
            filemtime(get_template_directory_uri() . '/build/css/style.css')
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
            filemtime(get_template_directory_uri() . '/build/js/main.js'),
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
                    filemtime(get_template_directory_uri() . '/build/css/product-page.css')
                );

                wp_enqueue_script(
                    'product-page-js',
                    get_template_directory_uri() . '/build/js/product-page-js.js',
                    [],
                    filemtime(get_template_directory_uri() . '/build/js/product-page-js.js'),
                    array(
                        'in_footer' => true
                    )
                );
            }
        }

        if (function_exists('is_shop') && function_exists('is_product_category')) {
            if (is_shop() || is_product_category()) {
                wp_enqueue_style(
                    'shop-theme-style',
                    get_template_directory_uri() . '/build/css/shop.css',
                    [],
                    filemtime(get_template_directory_uri() . '/build/css/shop.css')
                );
            }
        }        
        
        if (function_exists('is_404')) {
            if (is_404()) {
                wp_enqueue_style(
                    '404-style',
                    get_template_directory_uri() . '/build/css/not-found.css',
                    [],
                    filemtime(get_template_directory_uri() . '/build/css/not-found.css')
                );
            }
        }


        if (function_exists('is_checkout')) {
            if (is_checkout()) {
                wp_enqueue_style(
                    'checkout-style',
                    get_template_directory_uri() . '/build/css/checkout.css',
                    [],
                    filemtime(get_template_directory_uri() . '/build/css/checkout.css')
                );
            }
        }


        if (function_exists('is_order_received_page')) {
            if ( is_order_received_page() ) {
                wp_enqueue_style(
                    'thankyou-style',
                    get_template_directory_uri() . '/build/css/thankyou.css',
                    [],
                    filemtime(get_template_directory_uri() . '/build/css/thankyou.css')
                );
            }
        }

    }

    public function critical_css() {
        $critical_css_path = get_template_directory() . '/build/css/critical.css'; 
        if (file_exists($critical_css_path)) {
            $critical_css = file_get_contents($critical_css_path);
            if (!empty($critical_css)) {
                echo '<style id="critical-css">' . $critical_css . '</style>' . "\n";
            }
        }
    }

    public function add_module_type_attribute($tag, $handle, $src) {
        if (in_array($handle, ['clean-theme-js', 'product-page-js', 'shop-js', 'acf-products-section-view-script'], true) || str_starts_with($handle, 'block-')) {
            return '<script type="module" src="' . esc_url($src) . '" id="' . $handle . '"></script>';
        }
        return $tag;
    }

    public function images_preload() {
        if (!is_singular() || !has_blocks()) {
            return;
        }

        $post = get_post();
        $blocks = parse_blocks($post->post_content);

        foreach ($blocks as $block) {

            if ($block['blockName'] === 'acf/video-hero-section') {
               
                $data = !empty($block['attrs']['data']) ? $block['attrs']['data'] : [];
                $bg_type = !empty($data['type_fonu']) ? $data['type_fonu'] : 'video';
                $get_url = function($field_key) use ($data) {
                    if (empty($data[$field_key])) {
                        return '';
                    }
                    $val = $data[$field_key];
                    

                    if (is_array($val) && !empty($val['url'])) {
                        return $val['url'];
                    }

                    if (is_numeric($val)) {
                        return wp_get_attachment_image_url($val, 'full');
                    }

                    if (is_string($val) && filter_var($val, FILTER_VALIDATE_URL)) {
                        return $val;
                    }
                    return '';
                };

                if ($bg_type === 'video') {
                    $mobile_poster_url  = $get_url('mobile_poster');
                    $desktop_poster_url = $get_url('desktop_poster');

                    if ($mobile_poster_url) {
                        echo '<link rel="preload" as="image" href="' . esc_url($mobile_poster_url) . '" media="(max-width: 959px)" fetchpriority="high" />' . "\n";
                    }
                    if ($desktop_poster_url) {
                        echo '<link rel="preload" as="image" href="' . esc_url($desktop_poster_url) . '" media="(min-width: 960px)" fetchpriority="high" />' . "\n";
                    }
                } else {
                    $mobile_img_url  = $get_url('mobile_image');
                    $desktop_img_url = $get_url('desktop_image');

                    if ($mobile_img_url) {
                        echo '<link rel="preload" as="image" href="' . esc_url($mobile_img_url) . '" media="(max-width: 959px)" fetchpriority="high" />' . "\n";
                    }
                    if ($desktop_img_url) {
                        echo '<link rel="preload" as="image" href="' . esc_url($desktop_img_url) . '" media="(min-width: 960px)" fetchpriority="high" />' . "\n";
                    }
                }

                // Stop after the first hero block is found
                break;
            }
        }
    }
}
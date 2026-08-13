<?php

namespace CleanTheme;

use WP_Query;

class Shop {

    public function __construct() {

        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

        add_action( 'wp_ajax_wc_load_more', [ $this, 'load_more_products' ] );
        add_action( 'wp_ajax_nopriv_wc_load_more', [ $this, 'load_more_products' ] );

        remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
        add_action( 'woocommerce_after_shop_loop', [ $this, 'add_load_more_button' ], 10 );
    }

    /**
     * Enqueue JS and pass variables (query data, nonce, URL)
     */
    public function enqueue_scripts() {
        $theme_version = wp_get_theme()->get('Version');

        if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
            return;
        }

        global $wp_query;

        wp_enqueue_script(
            'shop-js',
            get_template_directory_uri() . '/build/js/shop-js.js',
            ['clean-theme-js'],
            $theme_version,
            true 
        );

        $base_url = get_pagenum_link( 1 );
        $base_url = trailingslashit( preg_replace( '/page\/[0-9]+\/?/', '', $base_url ) );

        wp_localize_script( 'shop-js', 'wc_load_more_params', [
            'ajax_url'     => admin_url( 'admin-ajax.php' ),
            'nonce'        => wp_create_nonce( 'wc_load_more_nonce' ),
            'query_vars'   => wp_json_encode( $wp_query->query_vars ),
            'current_page' => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
            'max_page'     => $wp_query->max_num_pages,
            'base_url'     => $base_url
        ] );
    }

    /**
     * Render the Load More button at the bottom of the product loop
     */
    public function add_load_more_button() {
        global $wp_query;

        // Only show button if there is more than 1 page
        if ( $wp_query->max_num_pages > 1 ) {
            echo '<button class="btn btn-def-link btn-def-link__black catalogue__load-more mx text--reg rel d-block mt--48">
                Показати більше
            </button>';
        }
    }

    /**
     * Process the AJAX request and return the HTML of the products
     */
    public function load_more_products() {
        check_ajax_referer( 'wc_load_more_nonce', 'nonce' );

        $query_vars = json_decode( stripslashes( $_POST['query_vars'] ), true );
        
        $query_vars['paged'] = intval( $_POST['page'] );
        $query_vars['post_status'] = 'publish';

        $products = new WP_Query( $query_vars );

        if ( $products->have_posts() ) {
            ob_start();
            
            while ( $products->have_posts() ) {
                $products->the_post();

                wc_get_template_part( 'content', 'product' );
            }
            
            wp_reset_postdata();
            
            $html = ob_get_clean();
            wp_send_json_success( $html );
        } else {
            wp_send_json_error( 'No more products' );
        }

        wp_die();
    }
}

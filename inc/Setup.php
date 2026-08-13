<?php
namespace CleanTheme;

class Setup {
    public function __construct() {
        add_filter( 'block_categories_all', [ $this, 'register_block_categories' ], 10, 2 );
        // Remove unnecessary blocks.
        add_filter( 'allowed_block_types_all', [ $this, 'neutech_allowed_block_types' ], 10, 2 );

        // Register theme settings
        add_action( 'acf/init', [ $this, 'register_theme_settings' ] ); 
        add_action('template_redirect', [$this, 'redirect_rules']);
       
    }

    public function register_theme_settings() {
        if ( function_exists( 'acf_add_options_page' ) ) {
            acf_add_options_page([
                'page_title'    => 'Опції сайту',
                'menu_title'    => 'Опції сайту',
                'menu_slug'     => 'site_option',
                'capability'    => 'edit_posts',
                'redirect'      => false,
                'icon_url'      => 'dashicons-admin-generic',
                'position'      => 2,
            ]);
        }
    }

    public function register_block_categories( $categories, $block_editor_context ) {
        return array_merge(
            [
                [
                    'slug'  => 'irman',
                    'title' => 'Irman Blocks',
                    'icon'  => 'star-filled',
                ],
            ],
            $categories
        );
    }

    public function neutech_allowed_block_types( $allowed_blocks, $editor_context ) {
        if ( ! empty( $editor_context->post ) ) {
            
            return [
                // 1. Custom blocks.
                'acf/hero-section',
                'acf/products-section',
                'acf/two-col-section',
                'acf/three-col-section',
                'acf/faq-section',
                'acf/text-content-section',
                'acf/video-hero-section',
                "acf/image-content-section",
                "acf/video-content-section",
                
                // 2. Critically necessary standard blocks.
                'core/paragraph',
                'core/heading',
                'core/list',
                'core/image',
                'core/columns',
                'core/shortcode'
            ];
        }
        
        return $allowed_blocks;
    }

    public function redirect_rules() {
        if ( is_singular('post') || is_author() || is_date() || is_attachment() ) {
            wp_safe_redirect( home_url(), 301 );
            exit;
        }

        // // Optional: Redirect Category and Tag archives if you aren't using them
        // if ( is_category() || is_tag() ) {
        //     wp_safe_redirect( home_url(), 301 );
        //     exit;
        // }
    }
}
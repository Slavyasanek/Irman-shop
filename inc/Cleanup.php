<?php
namespace CleanTheme;

class Cleanup {
    public function __construct() {
        add_action('init', [$this, 'cleanup_head']);
        add_action('wp_enqueue_scripts', [$this, 'remove_jquery']);
        add_filter('emoji_svg_url', '__return_false');
        add_action('admin_init', [$this, 'clean_disable_comments_post_types_support']);
        add_filter('comments_open', [$this, 'clean_disable_comments_status'], 20, 2);
        add_filter('pings_open', [$this, 'clean_disable_comments_status'], 20, 2);
        add_action('wp_head', [$this, 'hide_frontend_comments']);
    }

    /**
     * Remove jQuery from frontend
     */
    public function remove_jquery() {
        if ( ! is_admin() && !is_checkout() ) {
            wp_deregister_script('jquery');
            wp_register_script('jquery', false);
        }
    }

    /**
     * Clean up wp_head() output
     */
    public function cleanup_head() {
        // Remove Emoji support
        
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');

        // Remove RSS feed links
        remove_action('wp_head', 'feed_links_extra', 3);
        remove_action('wp_head', 'feed_links', 2);

        // Remove WP version
        remove_action('wp_head', 'wp_generator');

        // Remove wlwmanifest (Windows Live Writer)
        remove_action('wp_head', 'wlwmanifest_link');

        // Remove RSD (Really Simple Discovery)
        remove_action('wp_head', 'rsd_link');

        // Remove Shortlink
        remove_action('wp_head', 'wp_shortlink_wp_head');
    }

    public function clean_disable_comments_post_types_support() {
          $post_types = get_post_types();
           foreach ($post_types as $post_type) {
               if(post_type_supports($post_type, 'comments')) {
                   remove_post_type_support($post_type, 'comments');
                   remove_post_type_support($post_type, 'trackbacks');
               }
           }
    }

    public function clean_disable_comments_status() {
       return false;
    }

    public function hide_frontend_comments() {
        if (is_singular()) {
           echo '<style> #comments, #respond { display:none !important; } </style>';
        }
    }
}
<?php

namespace CleanTheme;
class ImageSizes {

    
    private $max_desktop_size = 1990;
    private $mobile_size_width = 420;
    private $mobile_size_height = 500;

    private $mobile_size_name = 'mobile';

    public function __construct() {
        
        add_filter( 'wp_handle_upload', [ $this, 'resize_original_image_on_upload' ] );
        add_filter( 'big_image_size_threshold', '__return_false' );
        add_action( 'init', [ $this, 'register_mobile_size' ] );
        add_filter( 'intermediate_image_sizes_advanced', [ $this, 'keep_only_mobile_size' ], 99 );
        add_filter( 'image_size_names_choose', [ $this, 'filter_media_library_dropdown' ], 99 );
    }

    // original limit
    public function resize_original_image_on_upload( $image_data ) {
        if ( empty( $image_data['type'] ) || strpos( $image_data['type'], 'image/' ) !== 0 ) {
            return $image_data;
        }

        if ( $image_data['type'] === 'image/svg+xml' ) {
            return $image_data;
        }

        $file_path = $image_data['file'];
        $editor = wp_get_image_editor( $file_path );

        if ( is_wp_error( $editor ) ) {
            return $image_data; 
        }

        $size = $editor->get_size();
        
        if ( $size['width'] > $this->max_desktop_size || $size['height'] > $this->max_desktop_size ) {
            $editor->resize( $this->max_desktop_size, $this->max_desktop_size, false );
            $editor->save( $file_path );
        }

        return $image_data;
    }

    // add mobile size
    public function register_mobile_size() {
        add_image_size( $this->mobile_size_name, $this->mobile_size_width, $this->mobile_size_height, ['center', 'center'] );
    }

    // delete old sizes
    public function keep_only_mobile_size( $sizes ) {
        foreach ( $sizes as $size_name => $size_args ) {
            if ( $this->mobile_size_name !== $size_name ) {
                unset( $sizes[ $size_name ] );
            }
        }
        return $sizes;
    }

    // refresh sizes list
    public function filter_media_library_dropdown( $sizes ) {
        return [
            'full'                 => 'Desktop',
            $this->mobile_size_name => 'Mobile',
        ];
    }
}
<?php
namespace CleanTheme;

class Helpers {

    public static function showPreview($path) {
        if (!$path) return;

        $block_dir = basename($path);
        $preview_image_url = sprintf('%s/blocks/%s/screenshot.jpg', get_template_directory_uri(), $block_dir);
        echo '<img src="' . esc_url( $preview_image_url ) . '" style="width:100%; height:auto; display:block;">';
    }
    public static function get_vimeo_embed_iframe($url, $className) {

        if (!is_string($url) || empty($url)) {
            return '';
        }

        $path = parse_url($url, PHP_URL_PATH);

        if ($path) {
            $video_id = basename($path);

            if (is_numeric($video_id)) {
                $embed_url = 'https://player.vimeo.com/video/' . htmlspecialchars($video_id, ENT_QUOTES, 'UTF-8') . '?background=1';

                $iframe = '<iframe src="' . $embed_url . '" title="Vimeo video player" frameborder="0" class="' . $className . '" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';

                return $iframe;
            }
        }
        return '';
    }

    public static function get_svg_icon($icon_name, $class_name = '') {
        if (empty($icon_name)) {
            return '';
        }

        // Define the path to the sprite file inside your theme
        $sprite_url = get_template_directory_uri() . '/assets/icons/sprite.svg';

        // Prepare the class attribute if one was passed
        $class_attr = !empty($class_name) ? ' class="' . esc_attr($class_name) . '"' : '';

        // Build the SVG markup safely
        $html = sprintf(
            '<svg%s role="presentation"><use href="%s#%s"></use></svg>',
            $class_attr,
            esc_url($sprite_url),
            esc_attr($icon_name)
        );

        return $html;
    }
}
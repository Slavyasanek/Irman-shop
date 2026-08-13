<?php
namespace CleanTheme;

use CleanTheme\Fields\ImageContentFields;
use CleanTheme\Fields\ProductPostFields;
use CleanTheme\Fields\ProductsSectionFields;
use CleanTheme\Fields\ThemeOptionsFields;
use CleanTheme\Fields\HeroFields;
use CleanTheme\Fields\ThreeColFields;
use CleanTheme\Fields\TwoColFields;
use CleanTheme\Fields\FaqFields;
use CleanTheme\Fields\TextContentFields;
use CleanTheme\Fields\VideoContentFields;
use CleanTheme\Fields\VideoHeroFields;

class Acf {

    private const BLOCKS = [
        'hero-section' => HeroFields::class,
        'products-section' => ProductsSectionFields::class,
        'two-col-section' => TwoColFields::class,
        'three-col-section' => ThreeColFields::class,
        'faq-section' => FaqFields::class,
        'text-content-section' => TextContentFields::class,
        'video-hero-section' => VideoHeroFields::class,
        'image-content-section' => ImageContentFields::class,
        'video-content-section' => VideoContentFields::class
    ];

    public function __construct() {
        // Register blocks (block.json)
        add_action('init', [$this, 'register_blocks'], 5);

        // Fields regsiter (ACF Builder)
        add_action('acf/init', [$this, 'register_fields']);
    }

    public static function get_blocks_fields() {
        return array_values(array_filter(array_values(self::BLOCKS)));
    }

    public static function get_blocks_names() {
        return array_values(array_filter(array_keys(self::BLOCKS)));
    }

    public function register_blocks() {
        foreach(self::get_blocks_names() as $name) {
            register_block_type( get_template_directory() . '/blocks/'.$name );
        }
    }

    public function register_fields() {
        if ( ! function_exists('acf_add_local_field_group') ) {
            return;
        }

        $product_fields = new ProductPostFields();
        acf_add_local_field_group($product_fields->get_fields());

        $theme_options = new ThemeOptionsFields();
        acf_add_local_field_group( $theme_options->get_fields() );

        foreach(self::get_blocks_fields() as $class) {
            acf_add_local_field_group( (new $class)->get_fields() );
        }
    }
}
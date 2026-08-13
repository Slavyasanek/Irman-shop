<?php

namespace CleanTheme\Fields;

use StoutLogic\AcfBuilder\FieldsBuilder;

class ProductsSectionFields {
    public static function get_fields() {
        $products = new FieldsBuilder('products_section');

        $products->setLocation('block', '==', 'acf/products-section'); 

        $products
            ->addText('zagolovok', [
                'label'   => 'Заголовок',
                'wrapper' => ['width' => '50%'],
            ])
            ->addLink('posylannya', [
                'label'        => 'Посилання (Кнопка)',
                'return_format' => 'array',
                'instructions' => 'Якщо не додати, посилання не буде відображатися',
                'wrapper'      => ['width' => '50%'],
            ])
            ->addRelationship('tovary', [
                'label'         => 'Товари',
                'required'      => 1,
                'post_type'     => ['product'],
                'filters'       => ['search', 'taxonomy'],
                'return_format' => 'id',
                'elements'      => ['featured_image'],
            ]);

        return $products->build();
    }
}
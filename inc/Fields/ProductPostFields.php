<?php

namespace CleanTheme\Fields;

use StoutLogic\AcfBuilder\FieldsBuilder;

class ProductPostFields {
    public static function get_fields() {
        $product_options = new FieldsBuilder('product_options', [
            'title' => 'Опції товару',
        ]);

        $product_options
            ->setLocation('post_type', '==', 'product');

        $product_options
            ->addRepeater('harakterystyky', [
                'label' => 'Характеристики',
                'instructions' => 'Наприклад, "Розмір" - "23 см"',
                'button_label' => 'Додати характеристику',
                'layout' => 'block',
            ])
                ->addText('nazva', [
                    'label' => 'Назва',
                    'wrapper' => [
                        'width' => '50%',
                    ],
                ])
                ->addText('znachennya', [
                    'label' => 'Значення',
                    'wrapper' => [
                        'width' => '50%',
                    ],
                ])
            ->endRepeater()

            ->addTextarea('perevagy', [
                'label' => 'Переваги',
                'rows' => 6,
                'new_lines' => 'wpautop',
                'wrapper' => [
                    'width' => '50%',
                ],
            ])
            
            ->addTextarea('doglyad', [
                'label' => 'Догляд',
                'rows' => 6,
                'new_lines' => 'wpautop',
                'wrapper' => [
                    'width' => '50%',
                ],
            ]);

        return $product_options->build();
    }
}
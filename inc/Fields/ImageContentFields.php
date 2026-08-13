<?php

namespace CleanTheme\Fields;

use StoutLogic\AcfBuilder\FieldsBuilder;

class ImageContentFields {
    public static function get_fields() {
        $imageContent = new FieldsBuilder('image_content_block');

        $imageContent->setLocation('block', '==', 'acf/image-content-section');

        $imageContent
            // --- General Settings ---
            ->addRadio('image_position', [
                'label'         => 'Розташування зображення',
                'choices'       => [
                    'left'  => 'Зліва',
                    'right' => 'Зправа',
                ],
                'default_value' => 'right',
                'layout'        => 'horizontal',
                'return_format' => 'value',
                'wrapper'       => ['width' => '50%'],
            ])

            // --- Media Field ---
            ->addImage('image', [
                'label'         => 'Зображення',
                'return_format' => 'array',
                'required'      => 1,
                'wrapper'       => ['width' => '100%'],
            ])

            // --- Content Fields ---
            ->addText('title', [
                'label'   => 'Заголовок (H2)',
                'wrapper' => ['width' => '100%'],
            ])
            ->addTextarea('text', [
                'label'        => 'Основний текст',
                'wrapper'      => ['width' => '100%'],
                'rows' => 6,
                "new_lines" => 'wpautop'
            ])

            // --- Optional Button Settings ---
            ->addTrueFalse('show_button', [
                'label'         => 'Показувати кнопку',
                'default_value' => 0,
                'ui'            => 1,
                'wrapper'       => ['width' => '30%'],
            ])
            ->addLink('button_link', [
                'label'         => 'Посилання та текст кнопки',
                'return_format' => 'array',
                'wrapper'       => ['width' => '70%'],
            ])
                ->conditional('show_button', '==', 1);

        return $imageContent->build();
    }
}
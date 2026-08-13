<?php

namespace CleanTheme\Fields;

use StoutLogic\AcfBuilder\FieldsBuilder;

class VideoContentFields {
    public static function get_fields() {
        $videoContent = new FieldsBuilder('video_content_block');

        $videoContent->setLocation('block', '==', 'acf/video-content-section');

        $videoContent
            // --- Layout Settings ---
            ->addRadio('video_position', [
                'label'         => 'Розташування відео',
                'choices'       => [
                    'left'  => 'Зліва',
                    'right' => 'Зправа',
                ],
                'default_value' => 'left',
                'layout'        => 'horizontal',
                'return_format' => 'value',
            ])



            // --- Video Fields ---
            ->addFile('video_file', [
                'label'         => 'Файл відео (MP4)',
                'instructions'  => 'Завантажте відео у форматі MP4',
                'return_format' => 'array',
                'mime_types'    => 'mp4',
                'required'      => 1,
            ])

            // --- Content Fields ---
            ->addText('title', [
                'label'   => 'Заголовок (H2)',
                'wrapper' => ['width' => '100%'],
            ])
            ->addTextarea('text', [
                'label'        => 'Основний текст',
                'new_lines' => 'wpautop',
                'rows' => 5,
                'wrapper'      => ['width' => '100%'],
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

        return $videoContent->build();
    }
}
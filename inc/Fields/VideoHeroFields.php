<?php

namespace CleanTheme\Fields;

use StoutLogic\AcfBuilder\FieldsBuilder;

class VideoHeroFields {
    public static function get_fields() {
        $videoHero = new FieldsBuilder('video_hero_block');

        $videoHero->setLocation('block', '==', 'acf/video-hero-section'); 

        $videoHero
            ->addRadio('height', [
                'label'         => 'Висота секції',
                'choices'       => [
                    '100' => '100',
                    '70' => '70',
                    '50' => '50'
                ],
                'default_value' => '100',
                'layout'        => 'horizontal',
                'return_format' => 'value',
            ])
            // --- Typography ---
            ->addTextarea('zagolovok', [
                'label'        => 'Головний заголовок (H1)',
                'delay'        => 0,
                'required'     => 1,
                'wrapper'      => ['width' => '50%'],
                'rows' => 1,
                'new_lines' => 'br'
            ])
            ->addText('pidzagolovok', [
                'label'   => 'Підзаголовок (H2)',
                'wrapper' => ['width' => '50%'],
            ])

            // --- Background Type Selector ---
            ->addRadio('type_fonu', [
                'label'         => 'Тип фону',
                'choices'       => [
                    'image' => 'Зображення',
                    'video' => 'Відео',
                ],
                'default_value' => 'video',
                'layout'        => 'horizontal',
                'return_format' => 'value',
            ])

            // --- IMAGE FIELDS (Show if type_fonu == image) ---
            ->addImage('desktop_image', [
                'label'         => 'Фонове зображення (Desktop)',
                'return_format' => 'array',
                'required'      => 1,
                'wrapper'       => ['width' => '50%'],
            ])
                ->conditional('type_fonu', '==', 'image')

            ->addImage('mobile_image', [
                'label'         => 'Фонове зображення (Mobile)',
                'return_format' => 'array',
                'required'      => 1,
                'wrapper'       => ['width' => '50%'],
            ])
                ->conditional('type_fonu', '==', 'image')

            // --- VIDEO FIELDS (Show if type_fonu == video) ---
            ->addFile('desktop_video', [
                'label'         => 'Відео для Desktop (MP4)',
                'instructions'  => 'Завантажте відео у форматі .mp4',
                'return_format' => 'array',
                'mime_types'    => 'mp4',
                'required'      => 1,
                'wrapper'       => ['width' => '50%'],
            ])
                ->conditional('type_fonu', '==', 'video')

            ->addFile('mobile_video', [
                'label'         => 'Відео для Mobile (MP4)',
                'instructions'  => 'Завантажте вертикальне/квадратне відео у форматі .mp4',
                'return_format' => 'array',
                'mime_types'    => 'mp4',
                'required'      => 1,
                'wrapper'       => ['width' => '50%'],
            ])
                ->conditional('type_fonu', '==', 'video')

            // --- BUTTONS SETTINGS ---
            ->addTrueFalse('show_first_btn', [
                'label'         => 'Показувати першу кнопку (Посилання)',
                'default_value' => 1,
                'ui'            => 1,
                'wrapper'       => ['width' => '50%'],
            ])
            ->addLink('first_btn_link', [
                'label'         => 'Посилання та текст першої кнопки',
                'instructions'  => 'Якщо залишити порожнім — за замовчуванням виведеться "Каталог" з посиланням на ID 27',
                'return_format' => 'array',
                'wrapper'       => ['width' => '50%'],
            ])
                ->conditional('show_first_btn', '==', 1)

            ->addTrueFalse('show_second_btn', [
                'label'         => 'Показувати другу кнопку (Модалка)',
                'default_value' => 1,
                'ui'            => 1,
                'wrapper'       => ['width' => '50%'],
            ])
            ->addText('second_btn_text', [
                'label'         => 'Текст другої кнопки',
                'default_value' => 'Індивідуальне замовлення',
                'wrapper'       => ['width' => '50%'],
            ])
                ->conditional('show_second_btn', '==', 1);

        return $videoHero->build();
    }
}
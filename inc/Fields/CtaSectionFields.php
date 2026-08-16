<?php

namespace CleanTheme\Fields;

use StoutLogic\AcfBuilder\FieldsBuilder;

class CtaSectionFields {
    public static function get_fields() {
        $ctaSection = new FieldsBuilder('cta_block');

        $ctaSection->setLocation('block', '==', 'acf/cta-section');

        $ctaSection
            // --- Content Fields ---
            ->addText('title', [
                'label'   => 'Заголовок (H2)',
                'wrapper' => ['width' => '100%'],
            ])
            ->addTextarea('subtitle', [
                'label'    => 'Підзаголовок',
                'rows'     => 3,
                'wrapper'  => ['width' => '100%'],
            ])

            // --- Primary Link / Button ---
            ->addTrueFalse('show_primary_button', [
                'label'         => 'Показувати основну кнопку',
                'default_value' => 1,
                'ui'            => 1,
                'wrapper'       => ['width' => '30%'],
            ])
            ->addLink('primary_button_link', [
                'label'         => 'Основне посилання та текст кнопки',
                'return_format' => 'array',
                'wrapper'       => ['width' => '70%'],
            ])
                ->conditional('show_primary_button', '==', 1)

            // --- Social Toggles (URLs come from options page) ---
            ->addTrueFalse('show_instagram', [
                'label'         => 'Показувати кнопку Instagram',
                'instructions'  => 'Посилання береться з налаштувань сайту',
                'default_value' => 0,
                'ui'            => 1,
                'wrapper'       => ['width' => '50%'],
            ])
            ->addTrueFalse('show_telegram', [
                'label'         => 'Показувати кнопку Telegram',
                'instructions'  => 'Посилання береться з налаштувань сайту',
                'default_value' => 0,
                'ui'            => 1,
                'wrapper'       => ['width' => '50%'],
            ]);

        return $ctaSection->build();
    }
}
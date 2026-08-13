<?php
namespace CleanTheme\Fields;

use StoutLogic\AcfBuilder\FieldsBuilder;

class ThemeOptionsFields
{
    public function get_fields()
    {
        $siteOptions = new FieldsBuilder('site_options', [
            'title' => 'Опції сайту',
            'style' => 'default',
            'label_placement' => 'top',
        ]);

        $siteOptions
            // --- Tab: Header & Footer ---
            ->addTab('Хедер та футер', [
                'placement' => 'left',
            ])
            ->addImage('logotyp', [
                'label' => 'Логотип',
                'preview_size' => 'medium',
                'return_format' => 'array'
            ])
            ->setWidth('50')
            ->addTrueFalse('pokazuvaty_knopku_indyvidualnogo_zamovlennya', [
                'label' => 'Показувати кнопку індивідуального замовлення?',
                'ui' => 0,
            ])
            ->setWidth('50')

            ->addTab('Модальне вікно замовлення', [
                'placement' => 'left',
            ])
            ->addGroup('modal_indyvidualne_zamovlennya', [
                'label' => 'Індивідуальне замовлення (Модальне вікно)',
                'layout' => 'block',
            ])
                ->addText('title', [
                    'label' => 'Заголовок',
                    'default_value' => 'Індивідуальне замовлення',
                ])
                ->setWidth('50')
                ->addText('subtitle', [
                    'label' => 'Підзаголовок',
                    'default_value' => 'Ми створюємо унікальні вироби на замовлення, враховуючи Ваші побажання',
                ])
                ->setWidth('50')
                ->addTextarea('text', [
                    'label' => 'Опис',
                    'rows' => 3,
                    'default_value' => 'Зв’яжіться з нами для обговорення деталей через месенджер або інстаграм',
                ])
                ->addImage('image', [
                    'label' => 'Зображення',
                    'preview_size' => 'medium',
                    'return_format' => 'array',
                ])
            ->endGroup()

            // --- Tab: Social Networks ---
            ->addTab('Соціальні мережі', [
                'placement' => 'left',
            ])
            ->addText('posylannya_na_instagram', [
                'label' => 'Посилання на інстаграм',
            ])
            ->setWidth('50')
            ->addText('nazva_instagramu', [
                'label' => 'Назва інстаграму',
            ])
            ->setWidth('50')
            ->addText('posylannya_na_telegram', [
                'label' => 'Посилання на телеграм',
            ])
            ->setWidth('50')
            ->addText('kontaktnyj_nomer_telefonu', [
                'label' => 'Контактний номер телефону',
            ])
            ->setWidth('50')
            ->addText('chasy_roboty', [
                'label' => 'Часи роботи',
            ])
            ->setWidth('50')

            // --- Tab: Telegram ---
            ->addTab('Telegram Tab', [ // Label changed slightly to avoid slug conflict with the group
                'label' => 'Telegram',
                'placement' => 'left',
            ])
            ->addGroup('telegram', [
                'label' => 'Telegram',
                'layout' => 'block',
            ])
            ->addText('api_token', [
                'label' => 'API Token',
            ])
            ->setWidth('50')
            ->addText('chat_id', [
                'label' => 'Chat ID',
            ])
            ->setWidth('50')
            ->endGroup()

            // Location Settings
            ->setLocation('options_page', '==', 'site_option');

        return $siteOptions->build();
    }
}
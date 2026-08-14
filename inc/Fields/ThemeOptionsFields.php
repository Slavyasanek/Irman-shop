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

            // --- Tab: Modal ---
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
            ->addTab('Telegram Tab', [
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

            // --- Tab: Scripts & Analytics ---
            ->addTab('Скрипти та аналітика', [
                'label' => 'Скрипти / Аналітика',
                'placement' => 'left',
            ])
            ->addRepeater('kastomni_skrypty', [
                'label' => 'Кастомні скрипти',
                'button_label' => 'Додати скрипт',
                'layout' => 'block',
            ])
                ->addText('title', [
                    'label' => 'Назва скрипту',
                    'placeholder' => 'напр. Google Analytics 4',
                ])
                ->setWidth('40')
                ->addSelect('location', [
                    'label' => 'Місце виводу',
                    'choices' => [
                        'header' => 'Header (<head>)',
                        'footer' => 'Footer (перед </body>)',
                    ],
                    'default_value' => 'header',
                ])
                ->setWidth('30')
                ->addSelect('load_strategy', [
                    'label' => 'Стратегія завантаження',
                    'choices' => [
                        'immediate' => 'Прямий вивід (одразу)',
                        'delayed'   => 'Відкладено (взаємодія / таймер 3.5с)',
                    ],
                    'default_value' => 'delayed',
                ])
                ->setWidth('30')
                ->addTextarea('code', [
                    'label' => 'Код скрипту (HTML / <script>)',
                    'rows' => 5,
                ])
            ->endRepeater()

            // Location Settings
            ->setLocation('options_page', '==', 'site_option');

        return $siteOptions->build();
    }
}
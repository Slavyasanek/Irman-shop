<?php

namespace CleanTheme\Fields;

use StoutLogic\AcfBuilder\FieldsBuilder;

class FaqFields {
    public static function get_fields() {
        $faq = new FieldsBuilder('faq_section', [
            'title' => 'Часті питання',
        ]);

        $faq->setLocation('block', '==', 'acf/faq-section');

        $faq
            ->addRepeater('pytannya-vidpovid', [
                'label' => 'Часті питання (Питання та Відповідь)',
                'layout' => 'block',
                'button_label' => 'Додати питання',
            ])
                ->addText('pytannya', [
                    'label' => 'Питання',
                    'required' => 1,
                ])
                ->addWysiwyg('vidpovid', [
                    'label' => 'Відповідь',
                    'toolbar' => 'basic',
                    'media_upload' => 0,
                    'required' => 1,
                ])
            ->endRepeater();

        return $faq->build();
    }
}

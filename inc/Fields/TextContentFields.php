<?php

namespace CleanTheme\Fields;

use StoutLogic\AcfBuilder\FieldsBuilder;

class TextContentFields {
    public static function get_fields() {
        $text_content = new FieldsBuilder('text_content_section', [
            'title' => 'Текстовий контент',
        ]);

        $text_content->setLocation('block', '==', 'acf/text-content-section');

        $text_content
            ->addWysiwyg('tekst_kontent', [
                'label' => 'Текст',
                'toolbar' => 'full',
                'media_upload' => 1,
                'required' => 1,
            ]);

        return $text_content->build();
    }
}

<?php

namespace CleanTheme\Fields;

use StoutLogic\AcfBuilder\FieldsBuilder;

class TwoColFields {
    public static function get_fields() {
        $two_col_fields = new FieldsBuilder('two_col_fields', [
            'title' => 'Текст між 2 зображеннями',
        ]);

        $two_col_fields->setLocation('block', '==', 'acf/two-col-section'); 

        $two_col_fields
            ->addTextarea('zagolovok', [
                'label' => 'Заголовок',
                'rows' => 1,
            ])
            ->addWysiwyg('tekst', [
                'label' => 'Текст',
                'media_upload' => 0, // Disable media upload inside the text editor if not needed
                'toolbar' => 'basic', // Or 'full' depending on your needs
                'required' => 1,
            ])
            ->addImage('live_zobrazhennya', [
                'label' => 'Ліве зображення',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'required' => 1,
                'wrapper' => array(
                    'width' => '50%'
                ),
            ])
            ->addImage('prave_zobrazhennya', [
                'label' => 'Праве зображення',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'required' => 1,
                'wrapper' => array(
                    'width' => '50%'
                ),
            ]);

        return $two_col_fields->build();
    }
}
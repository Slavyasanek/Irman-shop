<?php

namespace CleanTheme\Fields;

use StoutLogic\AcfBuilder\FieldsBuilder;

class ThreeColFields {
    public static function get_fields() {
        $about = new FieldsBuilder('about', [
            'title' => 'Про нас',
        ]);

        $about->setLocation('block', '==', 'acf/three-col-section'); 


        $about
            ->addTextarea('zagolovok', [
                'label' => 'Заголовок',
                'media_upload' => 0,
                'rows' => 1
            ])
            
            // First Block (Left)
            ->addGroup('livyj_blok', [
                'label' => 'Лівий блок',
            ])
                ->addTextarea('tekst', [
                    'label' => 'Текст',
                    'wrapper' => array(
                        'width' => '50%'
                    ),
                    'rows' => 3,
                    'new_lines' => 'wpautop'
                ])
                ->addImage('zoobrazhennya', [ // Note: exact match to your original key
                    'label' => 'Зображення',
                    'return_format' => 'array',
                    'wrapper' => array(
                        'width' => '50%'
                    ),
                    'required' => 1,
                ])
            ->endGroup()

            // Second Block (Center)
            ->addGroup('czentralnyj_blok', [
                'label' => 'Центральний блок',
            ])
                ->addImage('zobrazhennya', [
                    'label' => 'Зображення',
                    'return_format' => 'array',
                    'required' => 1,
                    'wrapper' => array(
                        'width' => '50%'
                    ),
                ])
                ->addTextarea('tekst', [
                    'label' => 'Текст',
                    'wrapper' => array(
                        'width' => '50%'
                    ),
                    'rows' => 3,
                    'new_lines' => 'wpautop'
                ])
            ->endGroup()

            // Third Block (Right)
            ->addGroup('pravyj_blok', [
                'label' => 'Правий блок',
            ])
                ->addImage('zobrazhennya', [
                    'label' => 'Зображення',
                    'return_format' => 'array',
                    'wrapper' => array(
                        'width' => '50%'
                    ),
                ])
                ->addTextarea('tekst', [
                    'label' => 'Текст',
                    'wrapper' => array(
                        'width' => '50%'
                    ),
                    'new_lines' => 'wpautop',
                    'rows' => 3,
                ])
            ->endGroup();

        return $about->build();
    }
}
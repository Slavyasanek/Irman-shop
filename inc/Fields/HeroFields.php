<?php

namespace CleanTheme\Fields;

use StoutLogic\AcfBuilder\FieldsBuilder;

class HeroFields {
    public static function get_fields() {
        $hero = new FieldsBuilder('hero_block');

        $hero->setLocation('block', '==', 'acf/hero-section'); 

        $hero
            ->addTextarea('zagolovok', [
                'label' => 'Головний заголовок (H1)',
                'delay' => 0,
                'required' => 1,
                'rows' => 1,
                'wrapper' => array(
                    'width' => '50%'
                ) 
            ])
            ->addText('pidzagolovok', [
                'label' => 'Підзаголовок (H2)',
                'wrapper' => array(
                    'width' => '50%'
                ) 
            ])
            ->addGallery('kolekcziya_zobrazhen', [
                'label' => 'Колекція зображень (Grid)',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'max' => 7,
                'required' => 1,
                'wrapper' => array(
                    'width' => '50%'
                ) 
            ])
            ->addImage('fonove_zobrazhennya_dlya_telefonu', [
                'label' => 'Фонове зображення (Mobile)',
                'return_format' => 'array',
                'required' => 1,
                'wrapper' => array(
                    'width' => '50%'
                ) 
            ]);
            

        return $hero->build();
    
    }
}
<?php 

use CleanTheme\Helpers;

if ( ! empty( $block['data']['_is_inserter_preview'] ) ) {
    Helpers::showPreview($block['path']);
    return;
}


$title = get_field('zagolovok');
$first_block = get_field('livyj_blok') ?: [];
$second_block = get_field('czentralnyj_blok') ?: [];
$third_block = get_field('pravyj_blok') ?: [];

// Create a unique ID for the section heading to assist screen readers
$section_id = $title ? 'three-data-col-title-' . sanitize_title($title) . '-' . wp_generate_uuid4() : '';
?>

<section class="three-data-col section--pt_S section--pb_XS text--reg" <?= $title ? 'aria-labelledby="' . esc_attr($section_id) . '"' : '' ?>> 
    <div class="container three-data-col__container flex-col"> 
        
        <div class="three-data-col__main-block   flex-col">
            <?php if ($title): ?>
                <h2 id="<?= esc_attr($section_id) ?>" class="section-title three-data-col__title ff--title">
                    <?= $title ?>
                </h2>
            <?php endif; ?>
            
            <?php if (!empty($first_block['tekst'])): ?>
                <div class="three-data-col__descr"> 
                    <?= $first_block['tekst'] ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($first_block['zoobrazhennya'])): ?>
                <div class="three-data-col__img w-full o-hid three-data-col__img--main">
                    <picture> 
                        <img src="<?= esc_url($first_block['zoobrazhennya']['url']) ?>" 
                             width="328" 
                             height="441" 
                             alt="<?= esc_attr($first_block['zoobrazhennya']['alt'] ?: '') ?>" 
                             loading="lazy"
                             class="cover-image">
                    </picture>
                </div>
            <?php endif; ?>
        </div>

        <div class="three-data-col__text-img  flex-col"> 
            <?php if (!empty($second_block['zobrazhennya'])): ?>
                <div class="three-data-col__img w-full o-hid"> 
                    <picture> 
                        <img src="<?= esc_url($second_block['zobrazhennya']['url']) ?>" 
                             width="328" 
                             height="441" 
                             alt="<?= esc_attr($second_block['zobrazhennya']['alt'] ?: '') ?>" 
                             loading="lazy"
                             class="cover-image">
                    </picture>
                </div>
            <?php endif; ?>

            <?php if (!empty($second_block['tekst'])): ?>
                <div class="three-data-col__txt"><?= $second_block['tekst'] ?></div>
            <?php endif; ?>
        </div>

        <div class="three-data-col__small-img-txt flex-col"> 
            <?php if (!empty($third_block['zobrazhennya'])): ?>
                <div class="three-data-col__img three-data-col__img--small  w-full o-hid"> 
                    <picture> 
                        <img src="<?= esc_url($third_block['zobrazhennya']['url']) ?>" 
                             width="328" 
                             height="328" 
                             alt="<?= esc_attr($third_block['zobrazhennya']['alt'] ?: '') ?>" 
                             loading="lazy"
                             class="cover-image">
                    </picture>
                </div>
            <?php endif; ?>

            <?php if (!empty($third_block['tekst'])): ?>
                <div class="three-data-col__txt"><?= $third_block['tekst'] ?></div>
            <?php endif; ?>
        </div>

    </div>
</section> 
<?php 

use CleanTheme\Helpers;

if ( ! empty( $block['data']['_is_inserter_preview'] ) ) {
    Helpers::showPreview($block['path']);
    return;
}



$left_img = get_field('live_zobrazhennya');
$title = get_field('zagolovok');
$text = get_field('tekst');
$right_img = get_field('prave_zobrazhennya');

// Create a unique ID for the section heading to link it for screen readers
$section_id = $title ? 'section-title-' . sanitize_title($title) . '-' . wp_generate_uuid4() : '';
?>

<section class="three-col section--pt_XS section--pb_S text--reg" <?= $title ? 'aria-labelledby="' . esc_attr($section_id) . '"' : '' ?>>
    <div class="container three-col__container  flex-col">
        
        <?php if (!empty($left_img)): ?>
            <div class="three-col__left-img three-col__img w-full o-hid rel">
                <picture> 
                    <img src="<?= esc_url($left_img['url']) ?>" 
                         width="328" 
                         height="413" 
                         alt="<?= esc_attr($left_img['alt'] ?: '') ?>" 
                         loading="lazy"
                         class="cover-image">
                </picture>
            </div>
        <?php endif; ?>

        <div class="three-col__text-block"> 
            <?php if ($title): ?>
                <h2 id="<?= esc_attr($section_id) ?>" class="section-title three-col__title ff--title">
                    <?= $title?>
                </h2>
            <?php endif; ?>

            <?php if ($text): ?>
                <div class="three-col__txt">
                    <?= wp_kses_post($text) ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($right_img)): ?>
            <div class="three-col__img three-col__right-img w-full o-hid d-none rel">
                <picture> 
                    <img src="<?= esc_url($right_img['url']) ?>" 
                         width="328" 
                         height="413" 
                         alt="<?= esc_attr($right_img['alt'] ?: '') ?>" 
                         loading="lazy"
                         class="cover-image">
                </picture>
            </div>
        <?php endif; ?>

    </div>
</section>
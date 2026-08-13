<?php

use CleanTheme\Helpers;

if ( ! empty( $block['data']['_is_inserter_preview'] ) ) {
    Helpers::showPreview($block['path']);
    return;
}
/**
 * Hero Block Template.
 */

$title      = get_field('zagolovok');
$subtitle   = get_field('pidzagolovok');
$gallery    = get_field('kolekcziya_zobrazhen');
$mobile_bg  = get_field('fonove_zobrazhennya_dlya_telefonu');

// Accessibility: catalog link
$catalog_link = get_page_link(27); 
?>

<section class="hero rel" <?php echo get_block_wrapper_attributes(); ?>> 
    <div class="container hero__container flex-row h-full"> 
        <div class="hero__content-block rel z1 c--white"> 
            <?php if ($title): ?>
                <h1 class="hero__title ff--title"><?= $title ?></h1>
            <?php endif; ?>

            <?php if ($subtitle): ?>
                <h2 class="hero__subtitle fs--italic text--subtitle mt--24 l-mt--32">
                    <p><?= $subtitle ?></p>
                </h2>
            <?php endif; ?>

            <div class="hero__actions">
                <a class="btn btn--full_accent hero__btn text--btn-m w-full d-block rel o-hid mt--32 l-mt--48" href="<?= esc_url($catalog_link) ?>">Каталог</a>

                <button class="btn btn--border_accent hero__btn text--btn-m rel o-hid w-full d-block hide-desktop c--white mt--24" type="button" data-modal-target="order-modal" aria-label="Відкрите модальне вікно індивідуального замовлення"> 
                    <span class=" rel z1">Індивідуальне замовлення</span>
                </button>
            </div>
        </div>

        <div class="hero__grid h-full d-none grow1"> 
            <?php if (is_array($gallery)): ?>
                <div></div>
                <?php foreach ($gallery as $index => $img): 
                    $attr = [
                        'class'         => 'cover-image',
                        'loading'       => 'lazy', 
                        'width' => '208',
                        'height' => '250',
                    ];
                ?>
                    <div class="hero__img rel o-hid">
                        <picture class="abs inset wh-full"> 
                            <?= wp_get_attachment_image($img['id'], 'mobile', false, $attr); ?>
                        </picture>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="hero__bg abs inset wh-full o-hid hide-desktop" aria-hidden="true">
            <div class="hero__bg-img abs inset wh-full">
                <?php if ($mobile_bg): ?>
                    <picture> 
                         <?= wp_get_attachment_image($mobile_bg['id'], 'mobile', false, array(
                            'fetchpriority' => 'high',
                            'width' => '600',
                            'height' => '600',
                            'class' => 'cover-image'
                         )); ?>
                    </picture>
                <?php endif; ?>
            </div>
            <div class="hero__overlay abs inset wh-full"></div>
        </div>
    </div>
</section>
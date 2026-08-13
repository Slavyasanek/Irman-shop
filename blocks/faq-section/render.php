<?php

use CleanTheme\Helpers;

if ( ! empty( $block['data']['_is_inserter_preview'] ) ) {
    Helpers::showPreview($block['path']);
    return;
}

$faq_list = get_field('pytannya-vidpovid') ?: [];
?>

<?php if (!empty($faq_list)): ?>
<section class="faq section--pb_M"> 
    <div class="container faq__container"> 
        <div class="faq__list"> 
            <?php foreach($faq_list as $faq): 
                if (empty($faq['pytannya'])) continue;
            ?>
            <div class="faq-item faq__item">
                <div class="faq-item__heading text text--size_16">
                    <div class="faq-item__quest"><?= esc_html($faq['pytannya']) ?></div>
                    <button class="btn btn--icon faq-item__btn" aria-label="Toggle answer">
                        <?= Helpers::get_svg_icon('arrow-down') ?>
                    </button>
                </div>
                <div class="faq-item__content text text--size_14">
                    <div class="faq-item__answer"> 
                        <?= wp_kses_post($faq['vidpovid']) ?>
                    </div>
                </div>
            </div>
            <?php endforeach;?>
        </div>
    </div>
</section>
<?php endif; ?>

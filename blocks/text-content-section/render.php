<?php

use CleanTheme\Helpers;

if ( ! empty( $block['data']['_is_inserter_preview'] ) ) {
    Helpers::showPreview($block['path']);
    return;
}

$text_content = get_field('tekst_kontent');
?>

<?php if (!empty($text_content)): ?>
<section class="text-content section--pt_M section--pb_M text text--reg"> 
    <div class="container text-content__container">
        <div class="text-content__text"> 
            <?= wp_kses_post($text_content) ?>
        </div>
    </div>
</section>
<?php endif; ?>

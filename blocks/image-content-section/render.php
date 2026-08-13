<?php

use CleanTheme\Helpers;

if (!empty($block['data']['_is_inserter_preview'])) {
    Helpers::showPreview($block['path']);
    return;
}

$anchor = '';
if ( ! empty( $block['anchor'] ) ) {
    $anchor = 'id="' . esc_attr( $block['anchor'] ) . '" ';
}

$class_name = 'section--pt_S section--pb_S image-content text--reg';
if ( ! empty( $block['className'] ) ) {
    $class_name .= ' ' . $block['className'];
}

// ACF Fields
$image_position     = get_field( 'image_position' ) ?: 'left';

$image              = get_field( 'image' );
$title              = get_field( 'title' );
$text               = get_field( 'text' );
$show_button        = get_field( 'show_button' );
$button_link        = get_field( 'button_link' );

?>

<section <?php echo $anchor; ?> class="<?php echo esc_attr( $class_name ); ?>">
    <div class="container image-content__container flex-col image-content__container--dir_<?= $image_position ?>">


        <div class="image-content__content">
            <?php if ( ! empty( $title ) ) : ?>
                <h2 class="image-content-title section-title ff--title mb--24"><?= $title  ?></h2>
            <?php endif; ?>

            <?php if ( ! empty( $text ) ) : ?>
                <div class="image-content__text">
                    <?php echo wp_kses_post( $text ); ?>
                </div>
            <?php endif; ?>

            <?php if ( $show_button && ! empty( $button_link ) ) : 
                $btn_url    = $button_link['url'];
                $btn_title  = $button_link['title'];
                $btn_target = ! empty( $button_link['target'] ) ? $button_link['target'] : '_self';
                ?>

                <a href="<?php echo esc_url( $btn_url ); ?>" 
                   class="btn btn--full_accent o-hid rel text--btn-sm d-block mt--32 o-hid image-content__btn" 
                   target="<?php echo esc_attr( $btn_target ); ?>"
                   <?php echo $btn_target === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>>
                    <?php echo esc_html( $btn_title ); ?>
                </a>

            <?php endif; ?>
        </div>

        <?php if ( ! empty( $image ) ) : ?>
            <div class="image-content__img o-hid shrink0 w-full ">
                <img src="<?php echo esc_url( $image['url'] ); ?>" 
                     alt="<?php echo esc_attr( $image['alt'] ?: $title ); ?>" 
                     loading="lazy" 
                     width="500" 
                     height="700"
                     class="contain-image">
            </div>
        <?php endif; ?>

    </div>
</section>
<?php

use CleanTheme\Helpers;

/**
 * CTA Section Block Template.
 *
 * @param array      $block      The block settings and attributes.
 * @param string     $content    The block inner HTML (if any).
 * @param bool       $is_preview True during backend preview render.
 * @param int|string $post_id    The post ID the block is rendering content against.
 * @param array      $context    The context provided to the block by the parent block.
 */

// Anchor setup
$anchor = '';
if ( ! empty( $block['anchor'] ) ) {
    $anchor = 'id="' . esc_attr( $block['anchor'] ) . '" ';
}

// Class names setup
$class_name = 'cta-section text--reg section--pt_XS section--pb_XS';
if ( ! empty( $block['className'] ) ) {
    $class_name .= ' ' . $block['className'];
}

// Local ACF Fields
$title               = get_field( 'title' );
$subtitle            = get_field( 'subtitle' );
$show_primary_button = get_field( 'show_primary_button' );
$primary_button_link = get_field( 'primary_button_link' );
$show_instagram      = get_field( 'show_instagram' );
$show_telegram       = get_field( 'show_telegram' );

// Option Fields (Global Site Settings)
$instagram_url = get_field( 'instagram_url', 'option' );
$telegram_url  = get_field( 'telegram_url', 'option' );

?>

<section <?php echo $anchor; ?> class="<?php echo esc_attr( $class_name ); ?>">
    <div class="container cta-section__container flex-col rel">

        <div class="flower abs c--accent cta-section__flower cta-section__flower--bottom sq--64">
            <?= Helpers::get_svg_icon('flower', 'wh-full') ?>
        </div>

        <div class="cta-section__content w-full">
            <?php if ( ! empty( $title ) ) : ?>
                <h2 class="cta-section__title mb--16 ff--title section-title"><?php echo esc_html( $title ); ?></h2>
            <?php endif; ?>

            <?php if ( ! empty( $subtitle ) ) : ?>
                <div class="cta-section__subtitle text--sub">
                    <?php echo wp_kses_post( wpautop( $subtitle ) ); ?>
                </div>
            <?php endif; ?>

            <div class="cta-section__actions mt--32 flex flex-wrap gap--16">
                
                <?php /* Основна кнопка-посилання */ ?>
                <?php if ( $show_primary_button && ! empty( $primary_button_link ) ) : 
                    $btn_url    = $primary_button_link['url'];
                    $btn_title  = $primary_button_link['title'];
                    $btn_target = ! empty( $primary_button_link['target'] ) ? $primary_button_link['target'] : '_self';
                    ?>
                    <a href="<?php echo esc_url( $btn_url ); ?>" 
                       class="btn btn--full_accent text--btn-sm o-hid rel cta-section__btn" 
                       target="<?php echo esc_attr( $btn_target ); ?>"
                       <?php echo $btn_target === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>>
                        <?php echo esc_html( $btn_title ); ?>
                    </a>
                <?php endif; ?>

                <?php /* Кнопка Instagram */ ?>
                <?php if ( $show_instagram && ! empty( $instagram_url ) ) : ?>
                    <a href="<?php echo esc_url( $instagram_url ); ?>" 
                       class="btn btn--social btn--instagram text--btn-sm rel cta-section__btn-social" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       aria-label="Instagram">
                        <?= Helpers::get_svg_icon('instagram', 'sq--20') ?>
                        <span>Instagram</span>
                    </a>
                <?php endif; ?>

                <?php /* Кнопка Telegram */ ?>
                <?php if ( $show_telegram && ! empty( $telegram_url ) ) : ?>
                    <a href="<?php echo esc_url( $telegram_url ); ?>" 
                       class="btn btn--social btn--telegram text--btn-sm rel cta-section__btn-social" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       aria-label="Telegram">
                        <?= Helpers::get_svg_icon('telegram', 'sq--20') ?>
                        <span>Telegram</span>
                    </a>
                <?php endif; ?>

            </div>
        </div>

    </div>
</section>
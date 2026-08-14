<?php

use CleanTheme\Helpers;
/**
 * Video & Content Block Template.
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
$class_name = 'video-content text--reg section--pt_XS section--pb_XS';
if ( ! empty( $block['className'] ) ) {
    $class_name .= ' ' . $block['className'];
}

// ACF Fields
$video_position = get_field( 'video_position' ) ?: 'left';
$video_file     = get_field( 'video_file' );
$video_poster   = get_field( 'video_poster' );
$title          = get_field( 'title' );
$text           = get_field( 'text' );
$show_button    = get_field( 'show_button' );
$button_link    = get_field( 'button_link' );

?>

<section <?php echo $anchor; ?> class="<?php echo esc_attr( $class_name ); ?>">
    <div class="container video-content__container flex-col rel video-content__container--pos_<?= $video_position ?>">

        <div class="flower abs c--accent video-content__flower video-content__flower--bottom sq--64">
            <?= Helpers::get_svg_icon('flower', 'wh-full') ?>
        </div>
        
        <div class="video-content__content w-full o-hid">
            <?php if ( ! empty( $title ) ) : ?>
                <h2 class="video-content__title mb--24 ff--title section-title"><?= $title ?></h2>
            <?php endif; ?>

            <?php if ( ! empty( $text ) ) : ?>
                <div class="video-content__text">
                    <?php echo wp_kses_post( wpautop( $text ) ); ?>
                </div>
            <?php endif; ?>

            <?php if ( $show_button && ! empty( $button_link ) ) : 
                $btn_url    = $button_link['url'];
                $btn_title  = $button_link['title'];
                $btn_target = ! empty( $button_link['target'] ) ? $button_link['target'] : '_self';
                ?>

                    <a href="<?php echo esc_url( $btn_url ); ?>" 
                       class="btn btn--full_accent text--btn-sm o-hid rel video-content__btn mt--32 d-block" 
                       target="<?php echo esc_attr( $btn_target ); ?>"
                       <?php echo $btn_target === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>>
                        <?php echo esc_html( $btn_title ); ?>
                    </a>

            <?php endif; ?>
        </div>

        <?php if ( ! empty( $video_file['url'] ) ) : ?>
            <div class="video-content__video o-hid shrink0">
                <video 
                    muted 
                    loop 
                    playsinline 
                    preload="none"
                    data-lazy-video
                    <?php if (!empty($video_poster['url'])) echo 'poster="' . esc_url($video_poster['url']) . '"'; ?>
                    class="contain-image">
                    <source src="<?php echo esc_url( $video_file['url'] ); ?>" type="video/mp4">
                    Ваш браузер не підтримує відео.
                </video>
            </div>
        <?php endif; ?>

    </div>
</section>
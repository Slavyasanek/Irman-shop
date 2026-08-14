<?php

use CleanTheme\Helpers;

if (!empty($block['data']['_is_inserter_preview'])) {
    Helpers::showPreview($block['path']);
    return;
}

/**
 * Video Hero Block Template.
 */
$title = get_field('zagolovok');
$subtitle = get_field('pidzagolovok');
$bg_type = get_field('type_fonu') ?: 'video';

$section_height = get_field('height') ?: '100';

// Image Fields
$desktop_img = get_field('desktop_image');
$mobile_img = get_field('mobile_image');

// Video Fields
$desktop_vid = get_field('desktop_video');
$desktop_poster = get_field('desktop_poster');
$mobile_vid = get_field('mobile_video');
$mobile_poster = get_field('mobile_poster');

// Button 1 logic
$show_first_btn  = get_field('show_first_btn');
$first_btn_link  = get_field('first_btn_link');

$first_btn_url   = !empty($first_btn_link['url']) ? $first_btn_link['url'] : get_page_link(27);
$first_btn_title = !empty($first_btn_link['title']) ? $first_btn_link['title'] : 'Каталог';
$first_btn_target= !empty($first_btn_link['target']) ? $first_btn_link['target'] : '_self';

// Button 2 logic
$show_second_btn  = get_field('show_second_btn');
$second_btn_text  = get_field('second_btn_text') ?: 'Індивідуальне замовлення';
?>

<section class="hero-video rel o-hid c--white d-flex hero-video--h_<?= $section_height ?>" <?php echo get_block_wrapper_attributes(); ?>>

    <!-- Background Wrapper -->
    <div class="hero-video__bg abs inset wh-full z0" aria-hidden="true">

        <?php if ($bg_type === 'video'): ?>
            <?php if (!empty($mobile_vid['url'])): ?>
                <?= var_dump($mobile_vid) ?>
                <div class="hero-video__mobile hero-video__video abs inset wh-full hide-desktop">
                    <video class="abs inset cover-image" 
                           autoplay loop muted playsinline preload="auto" 
                           poster="<?= !empty($mobile_poster['url']) ? esc_url($mobile_poster['url']) : ''; ?>"
                           height="<?= $mobile_vid['height'] ?>"
                           width="360"
                           >
                            Sorry, your browser doesn't support embedded videos
                        <source src="<?= esc_url($mobile_vid['url']); ?>" type="video/mp4" >
                    </video>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($desktop_vid['url'])): ?>

                <div class="hero-video__desktop hero-video__video abs inset wh-full hide-mobile">
                    <video class="abs inset cover-image" 
                           autoplay loop muted playsinline preload="auto" 
                           poster="<?= !empty($desktop_poster['url']) ? esc_url($desktop_poster['url']) : ''; ?>"
                           height="720"
                           width="1920"
                           >
                            Sorry, your browser doesn't support embedded videos
                        <source src="<?= esc_url($desktop_vid['url']); ?>" type="video/mp4">
                    </video>
                </div>
            <?php endif; ?>

        <?php else: ?>


            <?php if (!empty($mobile_img) || !empty($desktop_img)): ?>
                <div class="hero-video__img abs inset wh-full">
                    <picture class="wh-full d-block">
                        <?php if (!empty($desktop_img)): ?>
                            <source media="(min-width: 960px)" srcset="<?= esc_url($desktop_img['url']); ?>">
                        <?php endif; ?>

                        <?php 

                        $fallback_img = !empty($mobile_img) ? $mobile_img : $desktop_img; 
                        if ($fallback_img): 
                        ?>

                            <img src="<?= esc_url($fallback_img['url']); ?>" 
                                 alt="<?= esc_attr($title); ?>" 
                                 class="cover-image wh-full" 
                                 fetchpriority="high" 
                                 decoding="sync"
                                 width="360"
                                 height="330"
                                 >
                        <?php endif; ?>
                    </picture>
                </div>
            <?php endif; ?>

        <?php endif; ?>

        <!-- Dark Overlay for Readability -->
        <div class="hero-video__overlay abs inset wh-full"></div>
    </div>

    <!-- Content Layer -->
    <div class="container hero-video__container flex-center h-full rel z1 my">
        <div class="hero-video__content-block rel">
            <?php if ($title): ?>
                <h1 class="hero-video__title ff--title"><?= $title ?></h1>
            <?php endif; ?>

            <?php if ($subtitle): ?>
                <div class="hero-video__subtitle fs--italic text--subtitle mt--24 l-mt--32">
                    <p><?= esc_html($subtitle) ?></p>
                </div>
            <?php endif; ?>

            <?php if ($show_first_btn || $show_second_btn): ?>
                <div class="hero-video__actions mt--32 l-mt--48 flex-col gap--24">
                    
                    <?php if ($show_first_btn): ?>
                        <a class="btn btn--full_accent hero-video__btn text--btn-m w-full d-block rel o-hid"
                           href="<?= esc_url($first_btn_url) ?>"
                           target="<?= esc_attr($first_btn_target) ?>">
                            <?= esc_html($first_btn_title) ?>
                        </a>
                    <?php endif; ?>

                    <?php if ($show_second_btn): ?>
                        <button class="btn btn--border_accent hero-video__btn text--btn-m rel o-hid w-full d-block c--white"
                                type="button" 
                                data-modal-target="order-modal"
                                aria-label="<?= esc_attr($second_btn_text) ?>">
                            <span class="rel z1"><?= esc_html($second_btn_text) ?></span>
                        </button>
                    <?php endif; ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
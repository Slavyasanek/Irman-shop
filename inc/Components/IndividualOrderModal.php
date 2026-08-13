<?php

namespace CleanTheme\Components;

use CleanTheme\Helpers;

class IndividualOrderModal {
    public static function render() {
        $data = get_field('modal_indyvidualne_zamovlennya', 'option');

        $title    = !empty($data['title']) ? $data['title'] : 'Індивідуальне замовлення';
        $subtitle = !empty($data['subtitle']) ? $data['subtitle'] : '';
        $text     = !empty($data['text']) ? $data['text'] : '';
        $image    = !empty($data['image']) ? $data['image'] : null;
        ?>
        <div class="backdrop backdrop--modal fixed wh-full inset anim" 
            id="order-modal" 
            role="dialog" 
            aria-modal="true" 
            aria-labelledby="modal-title" 
            data-modal="order-modal">
            <div class="modal backdrop__inner xy-center bg--accent-200 w-full abs">
                <button class="btn flex-center close-btn sq--32 abs modal__close-btn" type="button" aria-label="Закрити модальне вікно" data-modal-close>
                    <?= Helpers::get_svg_icon('close', 'wh-full') ?>
                </button>
                
                <?php if ($title): ?>
                    <h2 class="modal__title c--black ff--title text--h4 m-0" id="modal-title"><?= $title ?></h2>
                <?php endif; ?>

                <div class="modal__content d-grid  mt--12 l-mt--24">
                    <div class="modal__txt">
                        <?php if ($subtitle): ?>
                            <h3 class="modal__subtitle t-w--500 c--black text--subtitle fs--italic"><?= $subtitle ?></h3>
                        <?php endif; ?>

                        <?php if ($text): ?>
                            <p class="text--reg modal__txt mt--16 l-mt--24"><?= $text ?></p>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($image['url'])): ?>
                        <div class="modal__img o-hid">
                            <picture>
                                <img src="<?= esc_url($image['url']) ?>" 
                                     alt="<?= esc_attr($image['alt'] ?: $title) ?>" 
                                     loading="lazy" 
                                     width="200" 
                                     height="200" 
                                     class="contain-image">
                            </picture>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="modal__btns flex-col sticky w-full bg--accent-200">
                    <?= SocialButtons::render('modal__backorder-btn') ?>
                </div>
            </div>
        </div>
        <?php
    }
}
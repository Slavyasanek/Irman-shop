<?php

namespace CleanTheme\Components;

use CleanTheme\Helpers;


class SocialButtons {
    public static function render(string $className = '') {
        $insta_url  = get_field('posylannya_na_instagram', 'option');
        $tele_url   = get_field('posylannya_na_telegram', 'option');
        ?>
            <?php if($tele_url): ?>
                <a class="btn btn--icon btn--border_accent <?= $className ?> rel text--btn-m o-hid flex-center backorder-btn"
                   href="<?= esc_url($tele_url) ?>" target="_blank" rel="noopener noreferrer"
                   aria-label="Написати нам у Telegram">
                    <span class="rel z1">Написати</span>
                    <?= Helpers::get_svg_icon('telegram', 'sq--24 rel z1') ?>
                </a>
            <?php endif; ?>

            <?php if($insta_url): ?>
                <a class="btn btn--icon btn--border_accent <?= $className ?> rel text--btn-m o-hid flex-center backorder-btn"
                    href="<?= esc_url($insta_url) ?>" target="_blank"    
                    rel="noopener noreferrer"
                    aria-label="Написати нам у Instagram">
                    <span class="rel z1">Написати</span>
                    <?= Helpers::get_svg_icon('instagram', 'rel z1 sq--24') ?>
                </a>
            <?php endif; ?>
        <?php
    }
}
<?php

namespace CleanTheme\Components;


use CleanTheme\Helpers;

class MobileMenu {
    public static function render() {
        $insta_url  = get_field('posylannya_na_instagram', 'option');
        $tele_url   = get_field('posylannya_na_telegram', 'option');
        ?>
        <div class="mob-menu header__mob-menu fixed w-full anim bg--accent-200 c--black hide-desktop z2" id="mobile-menu" role="dialog" aria-modal="true" aria-label="Мобільне меню" data-mobmenu>
            <button class="btn sq--32 flex-center close-btn mob-menu__close-btn abs" type="button" aria-label="Закрити меню" data-mobmenu-close>
                <?= Helpers::get_svg_icon('close', 'wh-full') ?>
            </button>

            <div class="mob-menu__menu text--menu-items">
                <?php wp_nav_menu(['menu' => 'Header menu', 'container' => false, 'fallback_cb' => false,
                'menu_class' => 'flex-center flex-col']); ?>
            </div>

            <?php if ($insta_url || $tele_url):  ?>
                <div class="mob-menu__icons flex-center mt--24">
                    <?php if ($insta_url): ?>
                        <a class="btn flex-center footer__social sq--32 social-icon social-icon--fill"
                           href="<?= esc_url($insta_url) ?>" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           aria-label="Ми в Instagram">
                            <?= Helpers::get_svg_icon('instagram', 'fill--black wh-full') ?>
                        </a>
                    <?php endif; ?>

                    <?php if ($tele_url): ?>
                        <a class="btn flex-center footer__social sq--32 social-icon social-icon--stroke"
                           href="<?= esc_url($tele_url) ?>" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           aria-label="Ми в Telegram">
                            <?= Helpers::get_svg_icon('telegram', 'stroke--black wh-full') ?>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}
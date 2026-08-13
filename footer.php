<?php

use CleanTheme\Components\Cart;
use CleanTheme\Components\IndividualOrderModal;
use CleanTheme\Helpers;
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package irman
 */

?>
  <?php 
    // Fetch values once for efficiency
    $work_hours = get_field('chasy_roboty', 'option');
    $phone      = get_field('kontaktnyj_nomer_telefonu', 'option');
    $insta_url  = get_field('posylannya_na_instagram', 'option');
    $tele_url   = get_field('posylannya_na_telegram', 'option');
?>

<footer class="footer text text--reg bg--white-200 c--black" role="contentinfo">
    <div class="container footer__container flex-col">

        <div class="footer__block mx">
            <p class="footer__subtitle fs--italic t-w--500 m-0 mb--10">Допомога покупцеві</p>
            <nav class="footer__menu" aria-label="Меню футера">
                <?php wp_nav_menu([
                    'menu' => 'Footer menu',
                    'container' => false,
                    'fallback_cb' => false,
                    'menu_class' => 'flex-col'
                ]); ?>
            </nav>
        </div>

        <div class="footer__block mx">
            <p class="footer__subtitle fs--italic t-w--500 m-0 mb--10">Контакти</p>
            <?php if($work_hours): ?>
                <p class="footer__time m-0"><?= esc_html($work_hours) ?></p>
            <?php endif; ?>
            
            <?php if($phone): ?>
                <a class="footer__tel d-block c--black mt--8" href="tel:<?= preg_replace('/[^0-9+]/', '', $phone) ?>">
                    <?= esc_html($phone) ?>
                </a>
            <?php endif; ?>

            <?php if($insta_url || $tele_url): ?>
                <div class="footer__socials flex-row mt--16">
                    <?php if($insta_url): ?>
                        <a class="btn btn--icon footer__social sq--32"
                           href="<?= esc_url($insta_url) ?>" 
                           target="_blank" rel="noopener noreferrer" 
                           aria-label="Ми в Instagram">
                            <?= Helpers::get_svg_icon('instagram', 'wh-full fill--black') ?>
                        </a>
                    <?php endif; ?>
                    
                    <?php if($tele_url): ?>
                        <a class="btn btn--icon footer__social sq--32"
                           href="<?= esc_url($tele_url) ?>" 
                           target="_blank" rel="noopener noreferrer" 
                           aria-label="Ми в Telegram">
                            <?= Helpers::get_svg_icon('telegram', 'wh-full stroke--black') ?>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="container t--center">
        <p class="footer__copyright m-0 mt--24">&#169; <?= date("Y"); ?> Irman. Всі права захищені.</p>
    </div>
</footer>

<div class="backdrop preloader fixed wh-full inset anim" aria-hidden="true">
    <div class="backdrop__inner preloader__inner abs o-hid"></div>
</div>

<?= IndividualOrderModal::render(); ?>
<?= Cart::render(); ?>

<?php /* --- JS Templates --- */ ?>
<template id="cartItemTemplate">
    <div class="cart-item cart-items__item" data-key="">
        <a class="cart-item__img" href="" aria-label="Переглянути товар">
            <picture>
                <img src="" alt="" loading="lazy" width="80" height="80">
            </picture>
        </a>
        <div class="cart-item__content">
            <div class="cart-item__row cart-item__row--dir_column">
                <p class="cart-item__title"></p>
                <p class="cart-item__price"></p>

                <div class="quant-block cart-item__quant-block">
                    <button class="btn quant-block__btn quant-block__btn--minus" 
                            data-update="minus" type="button" aria-label="Зменшити кількість">-</button>
                    <div class="quant-block__value" data-value="1" aria-live="assertive">1</div>
                    <button class="btn quant-block__btn quant-block__btn--plus" 
                            data-update="plus" type="button" aria-label="Збільшити кількість">+</button>
                </div>
            </div>
            <button class="btn btn-def-link btn-def-link__black cart-item__delete-btn" type="button">
                Видалити
            </button>
        </div>
    </div>
</template>


<?php wp_footer(); ?>

</body>

</html>
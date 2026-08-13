<?php

use CleanTheme\Components\MobileMenu;
use CleanTheme\Helpers;
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package irman
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <?php 

    $logo           = get_field('logotyp', 'option');
    $show_order_btn = get_field('pokazuvaty_knopku_indyvidualnogo_zamovlennya', 'option');
    $insta_url      = get_field('posylannya_na_instagram', 'option');
    $tele_url       = get_field('posylannya_na_telegram', 'option');
    
    // SEO: Fallback for logo alt
    $logo_alt = ($logo && !empty($logo['alt'])) ? $logo['alt'] : get_bloginfo('name');
?>

<div class="header-group">
    <header class="header bg--white-200 c--black fixed z2 w-full">
        <div class="container header__container flex-row j-between">
            <div class="header__left-side">
                
                <button class="btn hamburger sq--32 header__hamburger rel z1 anim hide-desktop" 
                        type="button" 
                        data-mobmenu-open
                        aria-label="Відкрити меню" 
                        aria-expanded="false" 
                        aria-controls="mobile-menu">
                    <div class="hamburger__wrapper wh-full rel">
                        <span class="d-block hamburger__line anim abs bg--black w-full"> </span>
                        <span class="d-block hamburger__line anim abs bg--black w-full"> </span>
                        <span class="d-block hamburger__line anim abs bg--black w-full"> </span>
                    </div>
                </button>

                <nav class="header__menu d-none text--menu-items" aria-label="Головне меню">
                    <?php 
                    // Check if the menu exists or has items before rendering
                    wp_nav_menu([
                        'menu'           => 'Header menu',
                        'container'      => false,
                        'fallback_cb'    => false,
                        'depth'          => 2,
                        'menu_class' => 'flex-row'
                    ]); 
                    ?>
                </nav>
            </div>

            <a class="header__logo rel shrink0" href="<?= esc_url(home_url('/')) ?>" aria-label="На головну">
                <?php if (is_array($logo) && !empty($logo['url'])): ?>
                    <picture>
                        <img src="<?= esc_url($logo['url']) ?>" 
                             alt="<?= esc_attr($logo_alt) ?>" 
                             width="<?= esc_attr($logo['width'] ?? '') ?>" 
                             height="<?= esc_attr($logo['height'] ?? '') ?>"
                             fetchpriority="high"
                             class="contain-image">
                    </picture>
                <?php else: ?>
                    <span class="logo-text"><?php bloginfo('name'); ?></span>
                <?php endif; ?>
            </a>

            <div class="header__right-side flex-row j-end">
                <?php if ($show_order_btn): ?>
                    <button class="btn btn--border_accent text--btn-m header__order-btn header__order-btn--desk rel o-hid c--black flex-center d-none" type="button" data-modal-target="order-modal" aria-label="Відкрите модальне вікно індивідуального замовлення">
                        <span class="rel z1">Індивідуальне замовлення</span>
                        <?= Helpers::get_svg_icon('thread', 'sq--32 rel z1') ?>
                    </button>

                    <button class="btn header__order-btn header__order-btn--mob rel o-hid c--black flex-center" type="button" data-modal-target="order-modal" aria-label="Відкрите модальне вікно індивідуального замовлення">
                        <?= Helpers::get_svg_icon('thread', 'sq--32') ?>
                    </button>
                <?php endif; ?>

                <button class="btn flex-center header__cart-btn cart-btn rel sq--32" 
                        data-cart="<?= WC()->cart->get_cart_contents_count() ?>" 
                        type="button" 
                        aria-label="Кошик"
                        data-modal-target="cart">
                    <?= Helpers::get_svg_icon('basket', 'cart-btn__icon wh-full') ?>
                </button>
            </div>
        </div>
    </header>

    <?= MobileMenu::render() ?>
</div>

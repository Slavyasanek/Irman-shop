<?php

use CleanTheme\Components\SocialButtons;
use CleanTheme\Helpers;

defined('ABSPATH') || exit;

global $product;
$product_id = $product->get_id();
$product_title = get_the_title();
$gallery_images_ids = $product->get_gallery_image_ids();
$stock_status = $product->get_stock_status();

// Optimize DB queries by fetching ACF fields once at the top
$features = get_field('harakterystyky', $product_id);
$perevagy = get_field('perevagy', $product_id);
$doglyad = get_field('doglyad', $product_id);
?>

<main>
    
    <article class="product-page section--pb_S text--reg o-hid rel" id="product-<?php the_ID(); ?>" itemscope itemtype="https://schema.org/Product">
        <div class="flower abs c--accent product-page__flower product-page__flower--top sq--64">
            <?= Helpers::get_svg_icon('flower', 'wh-full') ?>
        </div>
        <div class="flower abs c--accent product-page__flower product-page__flower--bottom sq--64">
            <?= Helpers::get_svg_icon('flower', 'wh-full') ?>
        </div>
        <div class="container product-page__container grid">
            
            <h1 class="product-page__title t-w--500 text--h4 hide-desktop" itemprop="name"><?= esc_html($product_title) ?></h1>

            <div class="product-slider product-page__slider">
                <!-- Embla Main Slider -->
                <section id="productMainSlider" class="embla product-slider__main" aria-label="Галерея товару">
                    <div class="embla__viewport ">
                        <div class="embla__container flex-row product-slider__main-track" id="lightgallery">
                            <?php foreach ($gallery_images_ids as $index => $image_id): 
                                $main_img_data = wp_get_attachment_image_src($image_id, 'full');
                                if (!$main_img_data) continue;
                                
                                $media_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                                $alt_text = $media_alt ? $media_alt : sprintf('%s - зображення %d', $product_title, $index + 1);
                                $loading = ($index === 0) ? 'eager' : 'lazy';
                            ?>
                                <div class="embla__slide product-slider__main-slide">
                                    <a href="<?= esc_url($main_img_data[0]) ?>" 
                                       data-pswp-width="<?= esc_attr($main_img_data[1]) ?>" 
                                       data-pswp-height="<?= esc_attr($main_img_data[2]) ?>"
                                       class="product-slider__img-link d-block o-hid rel" 
                                       aria-label="Збільшити зображення <?= ($index + 1) ?>">
                                        <picture>
                                            <?= wp_get_attachment_image($image_id, 'full', false, array(
                                                'loading'  => $loading,
                                                'class'    => 'cover-image',
                                                'width'    => '232',
                                                'height'   => '320',
                                                'alt'      => $alt_text,
                                                'itemprop' => ($index === 0) ? 'image' : ''
                                            )) ?>
                                        </picture>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Navigation Arrows (Desktop Only) -->
                    <button class="embla__prev product-slider__arrow product-slider__arrow--prev d-none sq--64 btn abs" type="button" aria-label="Попередній слайд">
                        <?= Helpers::get_svg_icon('slider-arrow', 'wh-full') ?>
                    </button>

                    <button class="embla__next product-slider__arrow product-slider__arrow--next d-none sq--64 btn abs" type="button" aria-label="Наступний слайд">
                        <?= Helpers::get_svg_icon('slider-arrow', 'wh-full') ?>
                    </button>
                    <!-- Dots for Mobile -->
                    <div class="embla__dots flex-row flex-center gap--8 mt--16 slider-pagination"></div>
                </section>

                <!-- Embla Thumbnails Slider -->
                <section id="productThumbnails" class="embla product-page__slider product-slider__thumbnails d-none l-mt--24" aria-label="Мініатюри товару">
                    <div class="embla__viewport o-hid">
                        <div class="embla__container flex-row product-slider__thumb-track">
                            <?php foreach ($gallery_images_ids as $index => $image_id): 
                                $thumb_img_data = wp_get_attachment_image_src($image_id, 'medium');
                                if (!$thumb_img_data) continue;
                                
                                $media_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                                $alt_text = $media_alt ? $media_alt : sprintf('Мініатюра %d для %s', $index + 1, $product_title);
                            ?>
                                <div class="embla__slide product-slider__thumbnail o-hid anim">
                                    <picture>
                                        <?= wp_get_attachment_image($image_id, 'mobile', false, array(
                                            'loading' => 'lazy',
                                            'class'   => 'cover-image',
                                            'width'   => '85',
                                            'height'  => '140',
                                            'alt'     => $alt_text
                                        )) ?>
                                    </picture>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>
            </div>

            <div class="product-page__profile product-profile">
                <h1 class="product-profile__title t-w--500 text--h4 d-none" itemprop="name"><?= esc_html($product_title) ?></h1>
                <div itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                    <meta itemprop="priceCurrency" content="<?= esc_attr(get_woocommerce_currency()) ?>" />
                    <meta itemprop="price" content="<?= esc_attr($product->get_price()) ?>" />
                    <meta itemprop="availability" content="<?= $stock_status === 'instock' ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' ?>" />
                    
                    <?php if ($product->is_on_backorder()): ?>
                        <p class="product-profile__status c--grey m-0 text--menu-items l-mt--32">Під замовлення</p>
                    <?php else: ?>
                        <?php 
                            $stock_qty = $product->managing_stock() ? (int) $product->get_stock_quantity() : 0;
                            
                            if ($stock_status === 'instock') {
                                if ($stock_qty > 1) {
                                    $status_text = sprintf('В наявності %d шт.', $stock_qty);
                                } else {
                                    $status_text = 'В наявності';
                                }
                            } else {
                                $status_text = 'Немає в наявності';
                            }
                        ?>
                        <p class="m-0 product-profile__status text--menu-items l-mt--32 <?= $stock_status === 'instock' ? 'c--green' : 'c--grey' ?>">
                            <?= esc_html($status_text) ?>
                        </p>
                    <?php endif; ?>
                    
                    <p class="product-profile__price t-w--500 mt--16 mb--24 l-mt--40" aria-label="Ціна: <?= esc_attr($product->get_price()) ?> гривень">
                        <?= number_format($product->get_price(), 0, '', ' ') ?>&#8372;
                    </p>
                </div>

                <?php if (!$product->is_on_backorder()): ?>
                    <?php
                        $max_qty   = $product->get_max_purchase_quantity();
                        $in_cart_qty = 0;

                        // Check if cart exists and locate product quantity in cart
                        if ( WC()->cart ) {
                            foreach ( WC()->cart->get_cart() as $cart_item ) {
                                if ( $cart_item['product_id'] == $product_id || $cart_item['variation_id'] == $product_id ) {
                                    $in_cart_qty += $cart_item['quantity'];
                                }
                            }
                        }

                        // Determine if the button should be disabled from start
                        $is_in_cart     = $in_cart_qty > 0;
                        $reached_limit  = ( $max_qty > 0 && $in_cart_qty >= $max_qty );
                        $is_disabled    = ($stock_status !== 'instock') || $reached_limit;

                        // Set dynamic label based on cart status
                        $button_text = 'Купити';
                        if ( $is_in_cart ) {
                            $button_text = 'Вже у кошику';
                        }
                        ?>

                    <button class="btn btn--full_accent product-profile__cart-btn text--btn-m w-full d-block rel o-hid add_to_cart_button ajax_add_to_cart <?= $is_in_cart ? 'in-cart' : '' ?>" 
                            data-product-id="<?= esc_attr($product_id) ?>" 
                            data-quantity="1"
                            data-max-quantity="<?= esc_attr($max_qty) ?>"
                            data-cart-quantity="<?= esc_attr($in_cart_qty) ?>"
                            aria-label="Додати <?= esc_attr($product_title) ?> в кошик"
                            <?php disabled($is_disabled, true); ?>>
                        <span class="btn-text"><?= esc_html($button_text) ?></span>
                    </button>
                <?php else: ?>
                    <div class="product-profile__backorder flex-col">
                        <p class="product-profile__backorder-txt">
                            <span>*</span>Товари під замовлення виготовляються виключно за передоплатою. Будь ласка, зв’яжіться з нами для оформлення замовлення.
                        </p>

                        <?= SocialButtons::render('product-profile__backorder-btn') ?>

                    </div>
                <?php endif; ?>

                <div class="product-profile__content flex-col mt--32">
                    <div class="product-profile__block">
                        <?php if ($product->get_description()): ?>
                            <h3 class="product-profile__subtitle mb--12 fs--italic text--subtitle t-w--500">Опис товару</h3>
                            <div class="product-profile__descr" itemprop="description">
                                <?= wp_kses_post($product->get_description()) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($features) && is_array($features)): ?>
                        <div class="product-profile__block">
                            <h3 class="product-profile__subtitle mb--12 fs--italic text--subtitle t-w--500">Характеристики</h3>
                            <ul class="product-profile__char-list">
                                <?php foreach ($features as $feature): ?>
                                    <li class="product-profile__char d-grid a-center">
                                        <div class="product-profile__char-name t-w--500"><?= esc_html($feature['nazva']) ?></div>
                                        <div class="product-profile__char-val"><?= esc_html($feature['znachennya']) ?></div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if ($perevagy): ?>
                        <div class="product-profile__block">
                            <h3 class="product-profile__subtitle mb--12 fs--italic text--subtitle t-w--500">Переваги</h3>
                            <div class="product-profile__descr">
                                <?= wp_kses_post($perevagy) ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($doglyad): ?>
                        <div class="product-profile__block">
                            <h3 class="product-profile__subtitle mb--12 fs--italic text--subtitle t-w--500">Догляд</h3>
                            <div class="product-profile__descr">
                                <?= wp_kses_post($doglyad) ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="product-profile__block">
                        <h3 class="product-profile__subtitle mb--12 fs--italic text--subtitle t-w--500">Оплата, доставка та повернення</h3>
                        <div class="product-profile__descr">
                            <p>Оплата готівкою, карткою Visa / Mastercard, безготівковий розрахунок</p>
                            <p>Способи доставки:</p>
                            <ul>
                                <li>у відділення Нової Пошти</li>
                                <li>кур'єром Нової Пошти</li>
                                <li>самовивіз за домовленістю</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </article>

    <?= woocommerce_output_related_products() ?>
</main>
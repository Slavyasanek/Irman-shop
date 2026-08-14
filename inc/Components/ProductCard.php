<?php

namespace CleanTheme\Components;

use CleanTheme\Helpers;

class ProductCard {
    public static function render($product_id, $className = '') {
        $product_obj = wc_get_product($product_id);
        if (!$product_obj) {
            return '';
        }

        $gallery_images_ids = $product_obj->get_gallery_image_ids();
        $main_image_id      = $product_obj->get_image_id();
        $stock_status       = $product_obj->get_stock_status();
        
        $product_terms = get_the_terms($product_id, 'product_cat');
        if (!empty($product_terms) && !is_wp_error($product_terms)) {
            $term_slugs      = wp_list_pluck($product_terms, 'slug');
            $cat_data_string = implode(',', $term_slugs);
        } else {
            $cat_data_string = 'uncategorized';
        }

        $main_img_data = wp_get_attachment_image_src($main_image_id, 'mobile');
        $product_name  = $product_obj->get_name();
        $permalink     = $product_obj->get_permalink();
        $price         = $product_obj->get_price();
        
        ?>

        <article class="product-card flex-col wh-full <?= esc_attr($className) ?>" data-cat="<?= esc_attr($cat_data_string) ?>">
            
            <div class="product-card__img-wr rel o-hid"> 

                <picture> 
                    <?php if ($main_img_data): ?>
                        <?= wp_get_attachment_image($main_image_id, 'mobile', false, array(
                            'loading' => 'lazy',
                            'class'   => 'cover-image product-card__main-img abs inset wh-full anim',
                            'width'   => '156',
                            'height'  => '214',
                            'alt'     => esc_attr($product_name),
                        )) ?>
                    <?php endif; ?>

                    <?php 
                    if (!empty($gallery_images_ids) && isset($gallery_images_ids[1])): 
                        $hover_img_data = wp_get_attachment_image_src($gallery_images_ids[1], 'full');
                        if ($hover_img_data):
                    ?>
                        <?= wp_get_attachment_image($gallery_images_ids[1], 'mobile', false, array(
                            'loading' => 'lazy',
                            'class'   => 'cover-image product-card__odd-img op-0 abs inset wh-full anim',
                            'width'   => '156',
                            'height'  => '214',
                            'alt'     => esc_attr($product_name . ' - фото 2'),
                        )) ?>
                    <?php 
                        endif;
                    endif; 
                    ?>
                </picture>

                <a href="<?= esc_url($permalink) ?>" tabindex="-1" aria-hidden="true" class="abs wh-full inset op-0 z1">
                    <?= esc_html($product_name) ?>
                </a>
            </div>
            
            <div class="product-card__info flex-col grow1">

                <header class="product-card__heading flex-row a-start j-between"> 
                    <div class="product-card__title-wrap">
                        <a class="product-card__title c--black" href="<?= esc_url($permalink) ?>">
                            <?= esc_html($product_name) ?>
                        </a>
                    </div>
                    
                    <div class="product-card__price-wrap">
                        <p class="product-card__price m-0 t-w--500" aria-label="Ціна: <?= esc_attr(number_format($price, 0, '', '')) ?> гривень">
                            <?= number_format($price, 0, '', '') ?>&#8372;
                        </p>
                    </div>
                </header>
                
                <?php if ($product_obj->is_on_backorder()): ?>
                    <div class="product-card__status c--grey-200">Під замовлення</div>
                <?php else: ?>
                    <div class="product-card__status c--green <?= $stock_status === 'instock' ? 'product-card__status--pos' : '' ?>">
                        <?= $stock_status === 'instock' ? 'В наявності' : 'Немає в наявності' ?>
                    </div>
                <?php endif; ?>                        
                
                <a class="btn flex-center btn--border_accent text--btn-sm rel product-card__btn w-full o-hid mt--12" 
                   href="<?= esc_url($permalink) ?>"
                   aria-label="В кошик: <?= esc_attr($product_name) ?>">
                    <span class="rel z1">В кошик</span>
                    <?= Helpers::get_svg_icon('basket', 'rel z1 sq--12') ?>
                </a>
            </div>
        </article>
        <?php
    }
}
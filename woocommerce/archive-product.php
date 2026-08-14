<?php

use CleanTheme\Helpers;
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );
$paged = get_query_var('paged');

// Outputs opening divs and breadcrumbs
do_action( 'woocommerce_before_main_content' );
?>

<section class="catalogue catalogue--shop section--pt_S section--pb_S">
    <div class="container rel"> 

        <div class="tabs o-hid catalogue__tabs tabs--shop anim sticky bg--white z2">

            <div class="tabs__inner flex-row rel gap--20">
                <?php 
                $is_shop = is_shop() || is_post_type_archive('product');
                $is_tag = is_product_tag(); // Check if we are on a product tag page
                $current_obj = get_queried_object();
                $shop_page_url = get_permalink( wc_get_page_id( 'shop' ) );

                // Switch taxonomy based on the current page type
                $taxonomy = $is_tag ? 'product_tag' : 'product_cat';
                $terms = get_terms(array('taxonomy' => $taxonomy, 'hide_empty' => true));
                ?>

                <a href="<?= esc_url($shop_page_url) ?>" class="btn tabs__item <?= $is_shop ? 'active' : '' ?> <?php if ($is_tag):?> flex-row gap--8 mb--16 <?php endif;?>">
                    <?= Helpers::get_svg_icon('arrow-back', 'sq--16') ?>
                    Всі товари
                </a>
                

                <?php if (!$is_tag):?>
                    <?php foreach ($terms as $term):
                        // The active check works for both categories and tags since it compares term_id
                        $is_active = (!$is_shop && isset($current_obj->term_id) && $current_obj->term_id === $term->term_id) ? 'active' : '';
                    ?>
                        <a href="<?= esc_url(get_term_link($term)) ?>" class="btn tabs__item <?= $is_active ?>">
                            <?= esc_html($term->name) ?>
                        </a>
                    <?php endforeach; ?>

                    <div class="tabs__toggler bg--black anim abs"></div>
                <?php endif;?>
            </div>
        </div>

        <ul class="grid grid--col_2-4 grid--gap_16-24 catalogue__products text--reg"> 
            <?php 
            if ( woocommerce_product_loop() ) :

                while ( have_posts() ) : the_post();
                    wc_get_template_part('content', 'product');
            ?>
            <?php 
                endwhile;
            else:

                do_action( 'woocommerce_no_products_found' );
            endif; 
            ?>
        </ul>

        <?php 
            do_action( 'woocommerce_after_shop_loop' ); 
        ?>

    </div>
</section>

<?php
do_action( 'woocommerce_after_main_content' );
get_footer( 'shop' );
?>
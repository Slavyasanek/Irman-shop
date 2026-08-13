<?php 

use CleanTheme\Components\ProductCard;
use CleanTheme\Helpers;

if ( ! empty( $block['data']['_is_inserter_preview'] ) ) {
    Helpers::showPreview($block['path']);
    return;
}

$products  = get_field('tovary') ?: [];
$zagolovok = get_field('zagolovok');
$link      = get_field('posylannya');
?>

<?php if ( is_array($products) && ! empty($products) ) : ?>
<section class="catalogue section--pt_S section--pb_S products o-hid" <?php echo get_block_wrapper_attributes(); ?>>
    <div class="container">
        
        <?php if ( ! empty($zagolovok) ) : ?>
            <h2 class="section-title catalogue__title ff--title t-w--400 mb--24"><?= esc_html($zagolovok) ?></h2>
        <?php endif; ?>

        <!-- Embla Carousel Root Wrapper -->
        <div class="embla products__slider catalogue__products">
            <div class="embla__viewport w-full">
                <div class="embla__container d-flex catalogue__track">
                    <?php foreach ($products as $product_id) : ?>
                        <div class="embla__slide catalogue__slide">
                            <?= ProductCard::render($product_id, 'catalogue__product'); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Dots Container -->
            <div class="embla__dots flex-center flex-wrap mt--24 slider-pagination"></div>
        </div>
        
        <?php if ( $link ) : ?>
            <a class="btn btn-def-link btn-def-link__black catalogue__load-more text--reg rel d-block mt--32 mx" 
               href="<?= esc_url($link['url']) ?>" 
               target="<?= esc_attr($link['target'] ?: '_self') ?>">
                <?= esc_html($link['title']) ?>
            </a>
        <?php endif; ?>

    </div>
</section>
<?php endif; ?>
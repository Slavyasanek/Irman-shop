<?php

use CleanTheme\Helpers;
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.1.0
 *
 * @var WC_Order $order
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="woocommerce-order thankyou section--pb_S text--reg">
    <div class="container thankyou__container">
        
	<?php
	if ( $order ) :

		do_action( 'woocommerce_before_thankyou', $order->get_id() );
		?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
				<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php esc_html_e( 'Pay', 'woocommerce' ); ?></a>
				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php esc_html_e( 'My account', 'woocommerce' ); ?></a>
				<?php endif; ?>
			</p>

		<?php else : ?>



            <?php
            
              function render_thankyou_row( string $label, string $value, ?string $icon_slug = null, string $extra_classes = '' ): void {
                $wrapper_class = trim( 'thankyou__row d-flex j-between ' . $extra_classes );
                ?>
                <div class="<?= esc_attr( $wrapper_class ) ?>">
                    <div class="flex-col">
                        <p class="thankyou__key t-w--500"><?= esc_html( $label ) ?></p>
                        <p class="thankyou__value"><?= esc_html( $value ) ?></p>
                    </div>
                    
                    <?php if ( $icon_slug ) : ?>
                        <div class="sq--32 thankyou__icon c--accent-400 shrink0">
                            <?= Helpers::get_svg_icon( $icon_slug, 'wh-full' ) ?>
                        </div>
                    <?php endif; ?>
                </div>
                <?php
            }
            ?>
            
    		<h2 class="section-title thankyou__title ff--title mb--32">Дякуємо за замовлення!</h2>

            <div class="thankyou__order text--size_16 flex-col mx">

                <!-- 1. Order Info Block -->
                <div class="thankyou__block bg--accent-200">
                    <div class="thankyou__block-title fs--italic text--subtitle mb--32">Інформація про замовлення</div>

                    <?php
                    render_thankyou_row(
                        'Номер замовлення',
                        '№' . $order->get_order_number(),
                        'order'
                    );

                    render_thankyou_row(
                        'Дата',
                        wc_format_datetime( $order->get_date_created() ),
                        'date',
                        'mt--16'
                    );
                    ?>
                </div>

                <!-- 2. Customer Info Block -->
                <div class="thankyou__block bg--accent-200">
                    <div class="thankyou__block-title fs--italic text--subtitle mb--32">Ваші дані</div>

                    <?php
                    render_thankyou_row(
                        "Ім'я",
                        $order->get_billing_first_name() . " " . $order->get_billing_last_name(),
                        'customer'
                    );

                    render_thankyou_row(
                        'E-mail',
                        $order->get_billing_email(),
                        'email',
                        'mt--16'
                    );

                    render_thankyou_row(
                        'Номер телефону',
                        $order->get_billing_phone(),
                        'phone',
                        'mt--16'
                    );
                    ?>
                </div>

                <!-- 3. Order Items Block -->
                <div class="thankyou__block bg--accent-200 thankyou__block--items">
                    <div class="thankyou__block-title fs--italic text--subtitle mb--32">Товари</div>

                    <div class="thankyou__items-list flex-col gap--16">
                        <?php 
                        foreach ( $order->get_items() as $item_id => $checkout_item ) : 
                            $product_obj = $checkout_item->get_product();
                            if ( ! $product_obj ) continue;

                            $image_id  = $product_obj->get_image_id();
                            $image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'thumbnail' ) : wc_placeholder_img_src();
                        ?>
                            <div class="thankyou__item flex-row j-between">

                                <a class="thankyou__item-img o-hid" href="<?= esc_url( $product_obj->get_permalink() ) ?>">
                                    <img src="<?= esc_url( $image_url ) ?>" alt="<?= esc_attr( $checkout_item->get_name() ) ?>" loading="lazy" class="cover-image" width="80" height="80">
                                </a>
                                <div class="thankyou__item-info">
                                    <a class="thankyou__item-title t-w--500" href="<?= esc_url( $product_obj->get_permalink() ) ?>">
                                        <?= esc_html( $checkout_item->get_name() ) ?>
                                    </a>
                                    <p class="thankyou__item-count">Кількість: <?= esc_html( $checkout_item->get_quantity() ) ?></p>
                                </div>

                                
                                <div class="thankyou__item-price t-w--500">
                                    <?= wc_price( $checkout_item->get_total() ) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="thankyou__divider mt--24 mb--24"></div>

                    <!-- Total Row inside the block -->
                    <div class="thankyou__row d-flex j-between align-center">
                        <p class="thankyou__key t-w--600">Загальна сума</p>
                        <p class="thankyou__value t-w--600"><?= $order->get_formatted_order_total() ?></p>
                    </div>
                </div>

                <!-- 4. Payment Method Block -->
                <div class="thankyou__block bg--accent-200">
                    <div class="thankyou__block-title fs--italic text--subtitle mb--32">Спосіб оплати</div>

                    <?php
                    render_thankyou_row(
                        "",
                        $order->get_payment_method_title(),
                        'payment'
                    );
                    ?>
                </div>

                <!-- 5. Shipping Block -->
                <div class="thankyou__block bg--accent-200">
                    <div class="thankyou__block-title fs--italic text--subtitle mb--32">Доставка</div>

                    <?php
                    render_thankyou_row(
                        "Спосіб доставки",
                        $order->get_shipping_method(),
                        'delivery-type'
                    );

                    $address = trim( $order->get_billing_address_1() . " " . $order->get_billing_city() );
                    render_thankyou_row(
                        "Адреса",
                        ! empty( $address ) ? $address . ", Україна" : "—",
                        '',
                        'mt--16'
                    );
                    ?>
                </div>

            </div>

		<?php endif; ?>




	<?php else : ?>

		<?php wc_get_template( 'checkout/order-received.php', array( 'order' => false ) ); ?>

	<?php endif; ?>

    </div>
</div>

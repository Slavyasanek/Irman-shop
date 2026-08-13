<?php

namespace CleanTheme;

use WC_Order;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Telegram_Order_Notifier {

    public function __construct() {
        add_action( 'woocommerce_order_status_processing', [ $this, 'send_processing_notification' ] );
    }

    /**
     * Get ACF Telegram API Credentials safely when needed.
     */
    private function get_credentials(): ?array {
        if ( ! function_exists( 'get_field' ) ) {
            error_log( '[Telegram Notifier] ACF plugin is not active.' );
            return null;
        }

        $options = get_field( 'telegram', 'option' );

        if ( empty( $options['api_token'] ) || empty( $options['chat_id'] ) ) {
            error_log( '[Telegram Notifier] API Token or Chat ID is missing in ACF options.' );
            return null;
        }

        return [
            'api_token' => $options['api_token'],
            'chat_id'   => $options['chat_id'],
        ];
    }

    /**
     * WooCommerce hook callback on order status processing.
     *
     * @param int $order_id
     */
    public function send_processing_notification( int $order_id ): void {
        $credentials = $this->get_credentials();
        if ( ! $credentials ) {
            return;
        }

        $order = wc_get_order( $order_id );
        if ( ! $order ) {
            error_log( "[Telegram Notifier] Order #{$order_id} not found." );
            return;
        }

        [ $message, $media ] = $this->build_notification_data( $order );
        $this->send_to_telegram( $credentials, $message, $media );
    }

    /**
     * Builds the message body and collects attached media.
     *
     * @param WC_Order $order
     * @return array{0: string, 1: array}
     */
    private function build_notification_data( WC_Order $order ): array {
        $order_number = $order->get_order_number();
        $date         = $order->get_date_created() ? $order->get_date_created()->format( 'd.m.Y H:i' ) : '';
        $order_url    = $order->get_edit_order_url();
        $customer     = $order->get_formatted_billing_full_name();
        $phone        = $order->get_billing_phone();
        $email        = $order->get_billing_email();

        // 1. Header & Order Info
        $message  = "🛍 <b>НОВЕ ЗАМОВЛЕННЯ №<a href='" . esc_url( $order_url ) . "'>" . $order_number . "</a></b>\n";
        $message .= "📅 <i>" . $date . "</i>\n";
        $message .= "───────────────\n\n";

        // 2. Purchased Items List & Product Media
        $products = $order->get_items();
        $media    = [];

        $message .= "📦 <b>Склад замовлення:</b>\n";
        foreach ( $products as $item ) {
            $product  = $item->get_product();
            $name     = $item->get_name();
            $quantity = $item->get_quantity();
            $total    = wc_price( $item->get_total(), [ 'currency' => $order->get_currency() ] );

            $total_formatted = wp_strip_all_tags( $total );

            $message .= "• <b>" . esc_html( $name ) . "</b>\n";
            $message .= "  └ " . $quantity . " шт. × " . $total_formatted . "\n";

            // Collect product images (up to 10)
            if ( $product && $product->get_image_id() ) {
                $image_url = wp_get_attachment_url( $product->get_image_id() );
                if ( $image_url && count( $media ) < 10 ) {
                    $media[] = [
                        'type'  => 'photo',
                        'media' => $image_url,
                    ];
                }
            }
        }
        $message .= "\n";

        // 3. Customer Information
        $message .= "👤 <b>Покупець:</b>\n";
        $message .= "• " . esc_html( $customer ) . "\n";
        if ( $phone ) {
            $message .= "• 📞 <code>" . esc_html( $phone ) . "</code>\n";
        }
        if ( $email ) {
            $message .= "• ✉️ <code>" . esc_html( $email ) . "</code>\n";
        }
        $message .= "\n";

        // 4. Delivery Information
        $shipping_address = $order->get_shipping_address_1();
        $shipping_city    = $order->get_shipping_city();
        $shipping_method  = $order->get_shipping_method();

        if ( $shipping_method || $shipping_address ) {
            $message .= "🚚 <b>Доставка:</b>\n";
            if ( $shipping_method ) {
                $message .= "• Спосіб: " . esc_html( $shipping_method ) . "\n";
            }
            if ( $shipping_address || $shipping_city ) {
                $full_address = implode( ', ', array_filter( [ $shipping_city, $shipping_address ] ) );
                $message .= "• Адреса: " . esc_html( $full_address ) . "\n";
            }
            $message .= "\n";
        }

        // 5. Customer Note
        $customer_note = $order->get_customer_note();
        if ( $customer_note ) {
            $message .= "💬 <b>Коментар до замовлення:</b>\n";
            $message .= "<blockquote>" . esc_html( $customer_note ) . "</blockquote>\n\n";
        }

        // 6. Payment & Totals Summary
        $message .= "💳 <b>Оплата та сума:</b>\n";
        $message .= "• Спосіб оплати: " . esc_html( $order->get_payment_method_title() ) . "\n";
        $message .= "• Всього до сплати: <b>" . wp_strip_all_tags( wc_price( $order->get_total() ) ) . "</b>\n\n";

        $message .= "───────────────\n";
        $message .= "⚙️ <i>Irman " . date( "Y" ) . "</i>";

        return [ $message, $media ];
    }

    /**
     * Dispatch HTTP POST payload to Telegram API.
     *
     * @param array  $credentials
     * @param string $message
     * @param array  $media
     */
    private function send_to_telegram( array $credentials, string $message, array $media ): void {
        $api_token = $credentials['api_token'];
        $chat_id   = $credentials['chat_id'];

        if ( ! empty( $media ) ) {
            $media[0]['caption']    = $message;
            $media[0]['parse_mode'] = 'HTML';

            $endpoint = "https://api.telegram.org/bot{$api_token}/sendMediaGroup";
            $payload  = [
                'chat_id' => $chat_id,
                'media'   => wp_json_encode( $media ),
            ];
        } else {
            $endpoint = "https://api.telegram.org/bot{$api_token}/sendMessage";
            $payload  = [
                'chat_id'                  => $chat_id,
                'text'                     => $message,
                'parse_mode'               => 'HTML',
                'disable_web_page_preview' => true,
            ];
        }

        $response = wp_remote_post( $endpoint, [
            'body'      => $payload,
            'timeout'   => 15,
            'sslverify' => true,
        ] );

        // Debugging & Logging
        if ( is_wp_error( $response ) ) {
            error_log( '[Telegram Notifier Error]: ' . $response->get_error_message() );
        } else {
            $response_code = wp_remote_retrieve_response_code( $response );
            $response_body = wp_remote_retrieve_body( $response );

            if ( $response_code !== 200 ) {
                error_log( "[Telegram Notifier API Error {$response_code}]: " . $response_body );
            }
        }
    }
}
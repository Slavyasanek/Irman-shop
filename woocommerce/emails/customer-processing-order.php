<?php
/**
 * Customer processing order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-processing-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>" />
		<meta content="width=device-width, initial-scale=1.0" name="viewport">
		<title><?php echo get_bloginfo( 'name', 'display' ); ?></title>
		<link href="https://fonts.cdnfonts.com/css/segoe-script" rel="stylesheet">     
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap">
	</head>
	<body <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="font-family:'Montserrat', sans-serif;">
		<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
			<tbody>
				<tr>
					<td align="center" valign="top">
						<table border="0" cellpadding="0" cellspacing="0" style="background-color: #F6F6F6; color: #1B1919; width:100%; max-width: 600px;border: none;border-spacing: 0;">
							<tbody>
								<tr>
									<!-- START HEADER -->
									<td style="text-align: center; padding: 20px;background-color: #F2F2F2;">
										<a href="<?= home_url() ?>" style="text-decoration: none;">
											<img src="<?= get_field('logotyp', 'option')['url'] ?>" alt="<?= get_field('logotyp', 'option')['url'] ?>" width="118" height="54">
										</a>
									</td>
									<!-- END HEADER -->
								</tr>

								<tr>
									<td style="text-align: center; font-family:'Segoe Script', sans-serif; font-size: 24px;padding: 20px;">
										Ваше замовлення передано в обробку!
									</td>
								</tr>

								<tr>
									<td style="padding: 10px 20px 50px">
										<table style="border-top-left-radius: 8px; border-top-right-radius: 8px; border: 1px solid #E4C9AC;width: 100%;"  cellpadding="0" cellspacing="0">
											<tbody>
												<tr>
													<td style="border-bottom: 1px solid #E4C9AC;color: #1B1919;font-family: 'Montserrat', sans-serif; font-size: 16px; line-height: 120%; font-style: italic;width: 50%; text-align: left;font-weight: 700;padding: 12px 10px;">
														Номер замовлення
													</td>
													<td  style="border-bottom: 1px solid #E4C9AC;font-family: 'Montserrat', sans-serif; font-size: 16px; line-height: 120%; font-style: italic;width: 50%; text-align: right;font-weight: 500;padding: 12px 10px;">
														№<?= $order->get_order_number() ?>
													</td>
												</tr>
												<tr>
													<td style="border-bottom: 1px solid #E4C9AC;color: #1B1919;font-family: 'Montserrat', sans-serif; font-size: 16px; line-height: 120%; font-style: italic;width: 50%; text-align: left;font-weight: 700;padding: 12px 10px;">
														Дата
													</td>
													<td  style="border-bottom: 1px solid #E4C9AC;font-family: 'Montserrat', sans-serif; font-size: 16px; line-height: 120%; font-style: italic;width: 50%; text-align: right;font-weight: 500;padding: 12px 10px;">
														<?= wc_format_datetime( $order->get_date_created() ) ?>
													</td>
												</tr>
												<tr>
													<td style="border-bottom: 1px solid #E4C9AC;color: #1B1919;font-family: 'Montserrat', sans-serif; font-size: 16px; line-height: 120%; font-style: italic;width: 50%; text-align: left;font-weight: 700;padding: 12px 10px;">
														Дані покупця
													</td>
													<td  style="border-bottom: 1px solid #E4C9AC;font-family: 'Montserrat', sans-serif; font-size: 16px; line-height: 120%; font-style: italic;width: 50%; text-align: right;font-weight: 500;padding: 12px 10px;">
													<?= $order->get_billing_first_name()?> <?= $order->get_billing_last_name()?>
													</td>
												</tr>
												<tr>
													<td style="border-bottom: 1px solid #E4C9AC;color: #1B1919;font-family: 'Montserrat', sans-serif; font-size: 16px; line-height: 120%; font-style: italic;width: 50%; text-align: left;font-weight: 700;padding: 12px 10px;">
														E-mail
													</td>
													<td  style="border-bottom: 1px solid #E4C9AC;font-family: 'Montserrat', sans-serif; font-size: 16px; line-height: 120%; font-style: italic;width: 50%; text-align: right;font-weight: 500;padding: 12px 10px;">
														<?= $order->get_billing_email()?>
													</td>
												</tr>
												<tr>
													<td style="border-bottom: 1px solid #E4C9AC;color: #1B1919;font-family: 'Montserrat', sans-serif; font-size: 16px; line-height: 120%; font-style: italic;width: 50%; text-align: left;font-weight: 700;padding: 12px 10px;">
														Номер телефону
													</td>
													<td  style="border-bottom: 1px solid #E4C9AC;font-family: 'Montserrat', sans-serif; font-size: 16px; line-height: 120%; font-style: italic;width: 50%; text-align: right;font-weight: 500;padding: 12px 10px;">
														<?= $order->get_billing_phone() ?>
													</td>
												</tr>
												<tr>
													<td style="color: #1B1919;font-family: 'Montserrat', sans-serif; font-size: 16px; line-height: 120%; font-style: italic;width: 50%; text-align: left;font-weight: 700;padding: 12px 10px;">
														Назва товару
													</td>
													<td style="color: #1B1919;font-family: 'Montserrat', sans-serif; font-size: 16px; line-height: 120%; font-style: italic;width: 50%; text-align: right;font-weight: 700;padding: 12px 10px;">
														Сума
													</td>
												</tr>
											</tbody>
										</table>
										<table style="width: 100%;border-left: 1px solid #E4C9AC;border-right: 1px solid #E4C9AC;"cellpadding="0" cellspacing="0" width="100%">
											<tbody>
											<?php foreach($order->get_items() as $checkout_item): 
													$product_obj = $checkout_item->get_product();?>
													<tr>
														<td style="border-bottom: 1px solid #E4C9AC;color: #1B1919;padding: 10px">
															<table cellpadding="0" cellspacing="0" width="100%">
																<tbody>
																	<tr>
																		<td style="width: 74px;">
																			<a href="<?= $product_obj->get_permalink() ?>" style="display: block;">
																				<img src="<?= wp_get_attachment_image_url($product_obj->get_image_id(), 'full') ?>" alt="<?= $product_obj->get_name() ?>" width="74" height="102" style="margin: 0;border-radius: 12px;height: 102px;object-fit: cover; margin-right:10px;">
																			</a>
																		</td>
																		<td valign="top">
																			<a href="<?= $product_obj->get_permalink() ?>" style="color: #1B1919;font-family: 'Montserrat', sans-serif; font-size: 16px; line-height: 120%; font-style: italic;font-weight: 400;margin: 0 0 10px 10px;display: block;color: #1B1919; text-decoration: none;"><?= $product_obj->get_name() ?></a>
																			<p style="font-family: 'Montserrat', sans-serif; font-size: 16px; line-height: 120%; font-style: italic;font-weight: 400;margin: 0 0 0 10px;">Кількість: <?= $checkout_item->get_quantity() ?></p>
																		</td>
																		<td valign="bottom" style="width: 80px;">
																			<p style="color: #1B1919;font-family: 'Montserrat', sans-serif; font-size: 16px; line-height: 120%; font-style: italic;font-weight: 700;margin: auto 0 0;text-align:right; margin-left: 10px;"><?= number_format( $product_obj->get_price(), 0, '', '' ) ?>&#8372;</p>
																		</td>
																	</tr>
																</tbody>
															</table>
														</td>
													</tr>
												<?php endforeach;?>
											</tbody>
										</table>
										<table style="border-bottom-left-radius: 8px; border-bottom-right-radius: 8px; border: 1px solid #E4C9AC;width: 100%;border-top: none;"  cellpadding="0" cellspacing="0">
											<tbody>
												<tr>
													<td style="border-bottom: 1px solid #E4C9AC;color: #1B1919;font-family: 'Montserrat', sans-serif; font-size: 16px; line-height: 120%; font-style: italic;width: 50%; text-align: left;font-weight: 700;padding: 12px 10px;">
														Загальна сума
													</td>
													<td style="border-bottom: 1px solid #E4C9AC;color: #1B1919;font-family: 'Montserrat', sans-serif; font-size: 16px; line-height: 120%; font-style: italic;width: 50%; text-align: right;font-weight: 500;padding: 12px 10px;">
														<?= $order->get_formatted_order_total() ?>
													</td>
												</tr>
												<tr>
													<td style="border-bottom: 1px solid #E4C9AC;color: #1B1919;font-family: 'Montserrat', sans-serif; font-size: 16px; line-height: 120%; font-style: italic;width: 50%; text-align: left;font-weight: 700;padding: 12px 10px;">
														Спосіб оплати
													</td>
													<td style="border-bottom: 1px solid #E4C9AC;color: #1B1919;font-family: 'Montserrat', sans-serif; font-size: 16px; line-height: 120%; font-style: italic;width: 50%; text-align: right;font-weight: 500;padding: 12px 10px;">
													<?php if ($order->get_customer_note()):?>	
														<?= $order->get_customer_note() ?>
													<?php else:?>
														<?= $order->get_payment_method_title() ?>
													<?php endif;?>
													</td>
												</tr>
												<tr>
													<td style="border-bottom: 1px solid #E4C9AC;color: #1B1919;font-family: 'Montserrat', sans-serif; font-size: 16px; line-height: 120%; font-style: italic;width: 50%; text-align: left;font-weight: 700;padding: 12px 10px;">
														Доставка
													</td>
													<td style="border-bottom: 1px solid #E4C9AC;color: #1B1919;font-family: 'Montserrat', sans-serif; font-size: 16px; line-height: 120%; font-style: italic;width: 50%; text-align: right;font-weight: 500;padding: 12px 10px;">
													<?= $order->get_shipping_method() ?>
													</td>
												</tr>
												<tr>
													<td style="font-family: 'Montserrat', sans-serif; font-size: 16px; line-height: 120%; font-style: italic;width: 50%; text-align: left;font-weight: 700;padding: 12px 10px;">
														Адреса
													</td>
													<td style="font-family: 'Montserrat', sans-serif; font-size: 16px; line-height: 120%; font-style: italic;width: 50%; text-align: right;font-weight: 500;padding: 12px 10px;">
														<?= $order->get_billing_address_1() ?>, <?= $order->get_billing_city() ?>, Україна
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
							<!-- START FOOTER -->
							<tfoot>
								<td>
									<table cellpadding="0" cellspacing="0" valign="top" style="width: 100%;">
										<tbody>
											<tr>
												<td style="padding: 20px 0; background-color: #F2F2F2;">
													<table cellpadding="0" cellspacing="0" valign="top" style="width: 100%;">
														<tbody>
															<tr>
																<td>
																	<a href="<?= get_field('posylannya_na_instagram', 'option') ?>" target="_blank" rel="noopener noreferrer">
																		<img src="<?= get_template_directory_uri() ?>/assets/images/instagram.png" alt="Telegram Link" height="24" width="24" style="display: block;margin: 0 0 0 auto;">
																	</a>
																</td>
																<td style="width: 48px;">
																	<a href="<?= get_field('posylannya_na_telegram', 'option') ?>" target="_blank" rel="noopener noreferrer" style="margin: 0 0 0 16px;display: block; color: #1B1919; text-decoration: 'none;">
																		<img src="<?= get_template_directory_uri() ?>/assets/images/telegram.png" alt="Telegram Link" height="24" width="24" style="display: block;">
																	</a>
																</td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
											<tr>
												<td style="padding: 0 0 20px;background-color: #F2F2F2;">
													<p style="font-family: 'Montserrat', sans-serif;color: #1B1919;font-size: 16px;font-weight: 400;margin: 0;text-align: center;">© Irman Shop, <?= date('Y') ?></p>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tfoot>
							<!-- END FOOTER -->
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	</body>
</html>
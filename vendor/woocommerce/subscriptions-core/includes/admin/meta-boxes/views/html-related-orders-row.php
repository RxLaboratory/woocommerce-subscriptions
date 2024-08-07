<?php
/**
 * Display a row in the related orders table for a subscription or order
 *
 * @var WC_Order|WC_Subscription $order A WC_Order or WC_Subscription order object to display.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<tr>
	<td>
		<?php // translators: placeholder is an order number. ?>
		<a href="<?php echo esc_url( $order->get_edit_order_url() ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Edit order number %s', 'woocommerce-subscriptions' ), $order->get_order_number() ) ); ?>">
			<?php
			// translators: placeholder is an order number.
			echo sprintf( esc_html_x( '#%s', 'hash before order number', 'woocommerce-subscriptions' ), esc_html( $order->get_order_number() ) );
			?>
		</a>
	</td>
	<td>
		<?php echo esc_html( $order->get_meta( '_relationship' ) ); ?>
	</td>
	<td>
		<?php
		$date_created = $order->get_date_created();

		if ( $date_created ) {
			$t_time          = $order->get_date_created()->date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) );
			$date_to_display = ucfirst( wcs_get_human_time_diff( $date_created->getTimestamp() ) );
		} else {
			$t_time          = __( 'Unpublished', 'woocommerce-subscriptions' );
			$date_to_display = $t_time;
		}

		if ( ! wcs_is_custom_order_tables_usage_enabled() ) {
			// Backwards compatibility for third-parties using the generic WP post time filter.
			// Only apply this filter if HPOS is not enabled, as the filter is not compatible with HPOS.
			$date_to_display = apply_filters( 'post_date_column_time', $date_to_display, get_post( $order->get_id() ) );
		}

		?>
		<abbr title="<?php echo esc_attr( $t_time ); ?>">
			<?php echo esc_html( apply_filters( 'wc_subscriptions_related_order_date_column', $date_to_display, $order ) ); ?>
		</abbr>
	</td>
	<td>
		<?php
		$classes = array(
			'order-status',
			sanitize_html_class( 'status-' . $order->get_status() ),
		);

		if ( wcs_is_subscription( $order ) ) {
			$status_name = wcs_get_subscription_status_name( $order->get_status() );
			$classes[]   = 'subscription-status';
		} else {
			$status_name = wc_get_order_status_name( $order->get_status() );
		}

		printf( '<mark class="%s"><span>%s</span></mark>', esc_attr( implode( ' ', $classes ) ), esc_html( $status_name ) );
		?>
	</td>
	<td>
		<span class="amount"><?php echo wp_kses( $order->get_formatted_order_total(), array( 'small' => array(), 'span' => array( 'class' => array() ), 'del' => array(), 'ins' => array() ) ); // phpcs:ignore WordPress.Arrays.ArrayDeclarationSpacing.AssociativeArrayFound ?></span>
	</td>
</tr>

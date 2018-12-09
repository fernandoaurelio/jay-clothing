<?php 
function wc_get_account_menu_items() {
  $endpoints = array(
    'orders'          => get_option( 'woocommerce_myaccount_orders_endpoint', 'orders' ),
    'downloads'       => get_option( 'woocommerce_myaccount_downloads_endpoint', 'downloads' ),
    'edit-address'    => get_option( 'woocommerce_myaccount_edit_address_endpoint', 'edit-address' ),
    'payment-methods' => get_option( 'woocommerce_myaccount_payment_methods_endpoint', 'payment-methods' ),
    'edit-account'    => get_option( 'woocommerce_myaccount_edit_account_endpoint', 'edit-account' ),
    'customer-logout' => get_option( 'woocommerce_logout_endpoint', 'customer-logout' ),
  );

  $items = array(
    'dashboard'       => __( 'Dashboard', 'woocommerce' ),
    'orders'          => __( 'Orders', 'woocommerce' ),
    'downloads'       => __( 'Downloads', 'woocommerce' ),
    'edit-address'    => __( 'Addresses', 'woocommerce' ),
    'payment-methods' => __( 'Payment methods', 'woocommerce' ),
    'edit-account'    => __( 'Account details', 'woocommerce' ),
    'customer-logout' => __( 'Logout', 'woocommerce' ),
  );

  // Remove missing endpoints.
  foreach ( $endpoints as $endpoint_id => $endpoint ) {
    if ( empty( $endpoint ) ) {
      unset( $items[ $endpoint_id ] );
    }
  }

  // Check if payment gateways support add new payment methods.
  if ( isset( $items['payment-methods'] ) ) {
    $support_payment_methods = false;
    foreach ( WC()->payment_gateways->get_available_payment_gateways() as $gateway ) {
      if ( $gateway->supports( 'add_payment_method' ) || $gateway->supports( 'tokenization' ) ) {
        $support_payment_methods = true;
        break;
      }
    }

    if ( ! $support_payment_methods ) {
      unset( $items['payment-methods'] );
    }
  }

  return apply_filters( 'woocommerce_account_menu_items', $items, $endpoints );
}

?>
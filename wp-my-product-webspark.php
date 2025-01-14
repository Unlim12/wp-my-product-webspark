<?php
/**
 * Plugin Name: WP My Product Webspark
 * Description: Custom WooCommerce functionality for managing products from "My Account".
 * Version: 0.8
 * Author: Unlim12
 * Text Domain: wp-my-product-webspark
 */

if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    return;
}

define( 'WP_MY_PRODUCT_WEBS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'WP_MY_PRODUCT_WEBS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once WP_MY_PRODUCT_WEBS_PLUGIN_PATH . 'includes/class-my-account-products.php';
require_once WP_MY_PRODUCT_WEBS_PLUGIN_PATH . 'includes/class-email-notifications.php';

add_action( 'plugins_loaded', function() {
    new My_Account_Products();
    new Email_Notifications();

    add_filter( 'woocommerce_email_settings', 'webspark_add_email_notification_setting' );
});

function webspark_add_email_notification_setting( $settings ) {
    $new_setting = [
        'title'    => __( 'Product Notification Emails', 'wp-my-product-webspark' ),
        'desc'     => __( 'Enable or disable notifications for new products.', 'wp-my-product-webspark' ),
        'id'       => 'webspark_notifications_enabled',
        'type'     => 'checkbox',
        'default'  => 'yes',
        'desc_tip' => false,
    ];

    foreach ( $settings as $index => $setting ) {
        if ( isset( $setting['id'] ) && $setting['id'] === 'email_sender_options' ) {
            array_splice( $settings, $index, 0, [ $new_setting ] );
            return $settings; 
        }
    }
    $settings[] = $new_setting;
    return $settings;
}

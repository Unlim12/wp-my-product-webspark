<?php
class Email_Notifications {

    public function __construct() {
        add_action( 'save_post_product', [ $this, 'send_admin_notification' ], 10, 3 );
    }

    public function send_admin_notification( $product_id, $author_id ) {
    $notifications_enabled = get_option( 'webspark_notifications_enabled', 'yes' );
    if ( $notifications_enabled !== 'yes' ) {
        return;
    }

    $product_title = get_the_title( $product_id );
    $author_link = admin_url( "user-edit.php?user_id=" . intval( $author_id ) );
    $product_edit_link = admin_url( "post.php?post=" . intval( $product_id ) . "&action=edit" );

    ob_start();
    include WP_MY_PRODUCT_WEBS_PLUGIN_PATH . 'includes/templates/email-template.php';
    $message = ob_get_clean();

    $subject = __( 'New Product Submitted for Review', 'wp-my-product-webspark' );
    $admin_email = get_option( 'admin_email' );
    $headers = [ 'Content-Type: text/html; charset=UTF-8' ];

    wp_mail( $admin_email, $subject, $message, $headers );
}
}

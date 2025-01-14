<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php esc_html_e( 'New Product Notification', 'wp-my-product-webspark' ); ?></title>
</head>
<body>
    <h1><?php esc_html_e( 'New Product Added', 'wp-my-product-webspark' ); ?></h1>
    <p><?php esc_html_e( 'A new product has been submitted for review:', 'wp-my-product-webspark' ); ?></p>
    <ul>
        <li><strong><?php esc_html_e( 'Product Title:', 'wp-my-product-webspark' ); ?></strong> <?php echo esc_html( $product_title ); ?></li>
        <li><strong><?php esc_html_e( 'Author Profile:', 'wp-my-product-webspark' ); ?></strong> <a href="<?php echo esc_url( $author_link ); ?>"><?php esc_html_e( 'View Author', 'wp-my-product-webspark' ); ?></a></li>
        <li><strong><?php esc_html_e( 'Edit Product:', 'wp-my-product-webspark' ); ?></strong> <a href="<?php echo esc_url( $product_edit_link ); ?>"><?php esc_html_e( 'Edit Product', 'wp-my-product-webspark' ); ?></a></li>
    </ul>
</body>
</html>
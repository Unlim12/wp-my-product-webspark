<?php
if ( isset( $product ) && is_a( $product, 'WP_Post' ) ) :
    $product_id = $product->ID;
    $product_title = esc_attr( $product->post_title );
    $product_price = get_post_meta( $product_id, '_price', true );
    $product_quantity = get_post_meta( $product_id, '_stock', true );
    $product_description = esc_textarea( $product->post_content );
    $product_image_id = get_post_thumbnail_id( $product_id );
    $product_image_url = wp_get_attachment_url( $product_image_id );
?>

    <form method="POST" action="">
        <?php wp_nonce_field( 'edit_product_action', 'edit_product_nonce' ); ?>

        <label for="product_title"><?php _e( 'Product Title', 'wp-my-product-webspark' ); ?></label>
        <input type="text" id="product_title" name="product_title" value="<?php echo $product_title; ?>" required />

        <label for="product_price"><?php _e( 'Price', 'wp-my-product-webspark' ); ?></label>
        <input type="number" id="product_price" name="product_price" value="<?php echo $product_price; ?>" required />

        <label for="product_quantity"><?php _e( 'Quantity', 'wp-my-product-webspark' ); ?></label>
        <input type="number" id="product_quantity" name="product_quantity" value="<?php echo $product_quantity; ?>" required />

        <label for="product_description"><?php _e( 'Description', 'wp-my-product-webspark' ); ?></label>
        <textarea id="product_description" name="product_description" required><?php echo $product_description; ?></textarea>

        <label for="product_image"><?php _e( 'Product Image', 'wp-my-product-webspark' ); ?></label>
        <input type="number" id="product_image" name="product_image" value="<?php echo $product_image_id; ?>" />

        <input type="submit" name="edit_product" value="<?php _e( 'Update Product', 'wp-my-product-webspark' ); ?>" />
    </form>

<?php
else :
    echo __( 'Product not found or you do not have permission to edit this product.', 'wp-my-product-webspark' );
endif;
?>

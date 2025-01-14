<?php
$product = isset( $product ) ? $product : null;
$title = $product ? $product->post_title : '';
$price = $product ? get_post_meta( $product->ID, '_price', true ) : '';
$quantity = $product ? get_post_meta( $product->ID, '_stock', true ) : '';
$description = $product ? $product->post_content : '';
$image_id = $product ? get_post_thumbnail_id( $product->ID ) : '';
$image_url = $image_id ? wp_get_attachment_url( $image_id ) : '';
?>

<form method="post">
    <?php wp_nonce_field( 'add_product_action', 'add_product_nonce' ); ?>
    <input type="hidden" name="product_id" value="<?php echo esc_attr( $product ? $product->ID : 0 ); ?>">
    
    <p>
        <label for="product_title"><?php _e( 'Product Title', 'wp-my-product-webspark' ); ?></label>
        <input type="text" id="product_title" name="product_title" value="<?php echo esc_attr( $title ); ?>" required>
    </p>
    
    <p>
        <label for="product_price"><?php _e( 'Price', 'wp-my-product-webspark' ); ?></label>
        <input type="number" id="product_price" name="product_price" value="<?php echo esc_attr( $price ); ?>" required>
    </p>
    
    <p>
        <label for="product_quantity"><?php _e( 'Quantity', 'wp-my-product-webspark' ); ?></label>
        <input type="number" id="product_quantity" name="product_quantity" value="<?php echo esc_attr( $quantity ); ?>" required>
    </p>
    
    <p>
        <label for="product_description"><?php _e( 'Description', 'wp-my-product-webspark' ); ?></label>
        <?php wp_editor( $description, 'product_description', [ 'textarea_name' => 'product_description' ] ); ?>
    </p>
    
    <p>
        <label for="product_image"><?php _e( 'Product Image', 'wp-my-product-webspark' ); ?></label>
        <input type="hidden" id="product_image" name="product_image" value="<?php echo esc_attr( $image_id ); ?>" />
        <button type="button" id="upload_image_button"><?php _e( 'Select Image', 'wp-my-product-webspark' ); ?></button>
        <img id="product_image_preview" src="<?php echo esc_url( $image_url ); ?>" style="max-width: 100px; max-height: 100px;" />
    </p>

    <p>
        <button type="submit"><?php _e( 'Save Product', 'wp-my-product-webspark' ); ?></button>
    </p>
</form>

<script type="text/javascript">
    jQuery(document).ready(function($){
        let mediaUploader;
        
        $('#upload_image_button').click(function(e) {
            e.preventDefault();
            
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            
            mediaUploader = wp.media.frames.file_frame = wp.media({
                title: '<?php _e( "Select Image", "wp-my-product-webspark" ); ?>',
                button: {
                    text: '<?php _e( "Use this image", "wp-my-product-webspark" ); ?>'
                },
                multiple: false  
            });
            
            mediaUploader.on('select', function() {
                let attachment = mediaUploader.state().get('selection').first().toJSON();
                $('#product_image').val(attachment.id);
                $('#product_image_preview').attr('src', attachment.url);
            });
            
            mediaUploader.open();
        });
    });
</script>

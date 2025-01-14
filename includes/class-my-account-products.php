<?php
class My_Account_Products {

    public function __construct() {
        add_action( 'woocommerce_account_menu_items', [ $this, 'add_menu_items' ] );
        add_action( 'init', [ $this, 'add_endpoints' ] );
        add_action( 'woocommerce_account_add-product_endpoint', [ $this, 'add_product_page' ] );
        add_action( 'woocommerce_account_my-products_endpoint', [ $this, 'my_products_page' ] );
        add_action( 'template_redirect', [ $this, 'handle_form_submission' ] );
        add_action( 'template_redirect', [ $this, 'handle_product_actions' ] );
    }

    public function add_menu_items( $items ) {
        $items['add-product'] = __( 'Add Product', 'wp-my-product-webspark' );
        $items['my-products'] = __( 'My Products', 'wp-my-product-webspark' );
        return $items;
    }

    public function add_endpoints() {
        add_rewrite_endpoint( 'add-product', EP_ROOT | EP_PAGES );
        add_rewrite_endpoint( 'edit-product', EP_ROOT | EP_PAGES );
        add_rewrite_endpoint( 'my-products', EP_ROOT | EP_PAGES );
    }
    
   public function add_product_page() {
    $product_id = isset( $_GET['product_id'] ) ? intval( $_GET['product_id'] ) : 0;
    $product = $product_id ? get_post( $product_id ) : null;

    if ( $product && $product->post_author !== get_current_user_id() ) {
        wc_add_notice( __( 'You do not have permission to edit this product.', 'wp-my-product-webspark' ), 'error' );
        wp_redirect( esc_url( wc_get_endpoint_url( 'my-products' ) ) );
        exit;
    }

    if ( $product ) {
        wp_redirect( admin_url( 'post.php?post=' . $product_id . '&action=edit' ) );
        exit;
    } else {
        wc_get_template(
            'add-product-form.php', 
            [], 
            '', 
            WP_MY_PRODUCT_WEBS_PLUGIN_PATH . 'includes/templates/'
        );
        }   
    }

    public function my_products_page() {
        wc_get_template( 'my-products-list.php', [], '', WP_MY_PRODUCT_WEBS_PLUGIN_PATH . 'includes/templates/' );
    }

    public function handle_form_submission() {
        if ( isset( $_POST['add_product_nonce'] ) && wp_verify_nonce( $_POST['add_product_nonce'], 'add_product_action' ) ) {
            $title = sanitize_text_field( $_POST['product_title'] );
            $price = floatval( $_POST['product_price'] );
            $quantity = intval( $_POST['product_quantity'] );
            $description = wp_kses_post( $_POST['product_description'] );
            $image_id = intval( $_POST['product_image'] );

            $product_id = wp_insert_post( [
                'post_title'   => $title,
                'post_content' => $description,
                'post_type'    => 'product',
                'post_status'  => 'pending',
                'meta_input'   => [
                    '_price'    => $price,
                    '_stock'    => $quantity,
                    '_stock_status' => 'instock',
                ],
            ] );

            if ( $product_id ) {
                set_post_thumbnail( $product_id, $image_id );
                wc_add_notice( __( 'Product added successfully.', 'wp-my-product-webspark' ), 'success' );
            } else {
                wc_add_notice( __( 'Failed to add product.', 'wp-my-product-webspark' ), 'error' );
            }

           wp_redirect( esc_url( wc_get_endpoint_url( 'my-products', '', wc_get_page_permalink( 'myaccount' ) ) ) );
           exit;
        }
    }

    public function handle_product_actions() {
        if ( isset( $_GET['action'], $_GET['product_id'] ) && $_GET['action'] === 'delete' ) {
            $product_id = intval( $_GET['product_id'] );

            if ( current_user_can( 'administrator' ) || ( (int) get_post_field( 'post_author', $product_id ) === get_current_user_id() ) ) {
                wp_trash_post( $product_id );
                wc_add_notice( __( 'Product deleted successfully.', 'wp-my-product-webspark' ), 'success' );
            } else {
                wc_add_notice( __( 'You do not have permission to delete this product.', 'wp-my-product-webspark' ), 'error' );
            }

            wp_redirect( esc_url( wc_get_endpoint_url( 'my-products', '', wc_get_page_permalink( 'myaccount' ) ) ) );
            exit;
        }
    }
}


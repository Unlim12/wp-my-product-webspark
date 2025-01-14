<?php
$current_user = wp_get_current_user();

if ( ! is_user_logged_in() ) {
    echo '<p>' . __( 'You must be logged in to view your products.', 'wp-my-product-webspark' ) . '</p>';
    return;
}

$args = [
    'post_type'      => 'product',
    'post_status'    => [ 'publish', 'pending' ], 
    'author'         => $current_user->ID,
    'posts_per_page' => 10,
    'paged'          => get_query_var( 'paged', 1 ),
];
$query = new WP_Query( $args );

if ( $query->have_posts() ) : ?>

    <table>
        <thead>
            <tr>
                <th><?php _e( 'Product Name', 'wp-my-product-webspark' ); ?></th>
                <th><?php _e( 'Quantity', 'wp-my-product-webspark' ); ?></th>
                <th><?php _e( 'Price', 'wp-my-product-webspark' ); ?></th>
                <th><?php _e( 'Status', 'wp-my-product-webspark' ); ?></th>
                <th><?php _e( 'Actions', 'wp-my-product-webspark' ); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                <tr>
                    <td><?php the_title(); ?></td>
                    <td>
                        <?php
                        $stock_quantity = get_post_meta( get_the_ID(), '_stock', true );
                        echo $stock_quantity ? esc_html( $stock_quantity ) : __( 'N/A', 'wp-my-product-webspark' );
                        ?>
                    </td>
                    <td>
                        <?php
                        $price = get_post_meta( get_the_ID(), '_price', true );
                        echo $price ? wc_price( $price ) : __( 'Free', 'wp-my-product-webspark' );
                        ?>
                    </td>
                    <td><?php echo esc_html( get_post_status() ); ?></td>
                    <td>
                       <a href="<?php echo esc_url( admin_url( 'post.php?post=' . get_the_ID() . '&action=edit' ) ); ?>">
                            <?php _e( 'Edit', 'wp-my-product-webspark' ); ?>
                        </a>


                                |
                        <a href="<?php echo esc_url( add_query_arg( [ 'action' => 'delete', 'product_id' => get_the_ID() ], wc_get_endpoint_url( 'my-products' ) ) ); ?>" 
                           onclick="return confirm('<?php _e( 'Are you sure you want to delete this product?', 'wp-my-product-webspark' ); ?>');">
                            <?php _e( 'Delete', 'wp-my-product-webspark' ); ?>
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Пагинация -->
    <div class="pagination">
        <?php
        echo paginate_links( [
            'total'   => $query->max_num_pages,
            'current' => max( 1, get_query_var( 'paged', 1 ) ),
        ] );
        ?>
    </div>

<?php else : ?>

    <p><?php _e( 'No products found.', 'wp-my-product-webspark' ); ?></p>

<?php endif; ?>

<?php wp_reset_postdata(); ?>

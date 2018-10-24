<?php



add_filter( 'woocommerce_register_post_type_shop_order', function( $vars ) {
        $args = array(
            'public'      => true,
            'has_archive' => true,
            'publicly_queryable'  => true,
            'query_var' => true,
            //'capability_type'  => 'post',
            //'map_meta_cap' => false,
            'rewrite'     => array( 'slug' => 'orders' ),
        );
        
        $args = wp_parse_args( $args, $vars );
        
        //error_log( print_r( $args, 1 ) );
        
        return $args;
} );



function show_orders_link() {
 
    $out = '';
 
    $args = array(
        'numberposts' => 1,
        'meta_key'    => '_customer_user',
        'meta_value'  => get_current_user_id(),
        'post_type'   => wc_get_order_types(),
        'post_status' => array_keys( wc_get_order_statuses() ),
        'facetwp' => true, // we added this
    );
    
    // Use $loop, a custom variable we made up, so it doesn't overwrite anything
    $loop = new WP_Query( $args );

    // have_posts() is a wrapper function for $wp_query->have_posts(). Since we
    // don't want to use $wp_query, use our custom variable instead.
    if ( $loop->have_posts() ) {
        
        $out = sprintf( ' | <a href="%s">My Orders</a>', get_post_type_archive_link( 'shop_order' ) );   
    }
    
    wp_reset_postdata();
    
    return $out;
    
}



function _s_filter_orders_by_customer( $query ) {
    
    if ( is_admin() || ! $query->is_main_query() )
        return;

 
    if ( is_post_type_archive( 'shop_order' ) ) {
        // Display 50 posts for a custom post type called 'movie'
        $query->set( 'post_status', 'wc-completed' );
        $query->set( 'meta_key', '_customer_user' );
        $query->set( 'meta_value', get_current_user_id() );
        return;
    }
        
    /// if ( 'shop_order' == $query->query_vars['post_type']  ) {
    if ( 'shop_order' == $query->get('post_type')  ) {
        // Display 50 posts for a custom post type called 'movie'
        $query->set( 'post_status', 'wc-completed' );
        //$query->set( 'meta_key', '_customer_user' );
        //$query->set( 'meta_value', get_current_user_id() ); // get_current_user_id()
        return;
    }
}
add_action( 'pre_get_posts', '_s_filter_orders_by_customer', 1 );
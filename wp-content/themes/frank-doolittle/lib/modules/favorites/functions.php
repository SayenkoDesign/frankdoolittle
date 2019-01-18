<?php

global $doolittle_favorite;
$doolittle_favorite = new Doolittle_Favorites;


function favorites_add( $post_ids ) {
    global $doolittle_favorite;
    return $doolittle_favorite->add( $post_ids );
}

function favorites_delete( $post_ids ) {
    global $doolittle_favorite;
    return $doolittle_favorite->delete( $post_ids );
}

function favorites_count() {
    // global $doolittle_favorite;
    $favorite = new Doolittle_Favorites; // needs to be initialized because its a rest api call
    return $favorite->get_count();
}

function favorites_get_response( $args ) {
    global $doolittle_favorite;
    return $doolittle_favorite->response( $args );
}

function get_favorite_class() {
    global $doolittle_favorite;
    return $doolittle_favorite->get_item( get_the_ID() ) ? 'disabled' : '';
}

function _s_get_favorites_count() {
    global $doolittle_favorite;
    return $doolittle_favorite->get_data( 'count' );
}

function _s_get_design_favorites() {

    global $doolittle_favorite;
    $post_ids = $doolittle_favorite->get_count_by_type( 'favorite', 'design' );

    if( empty( $post_ids ) ) {
        return false;
    }

    // arguments, adjust as needed
    $args = array(
        'post_type'      => 'doolittle_design',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby' => 'post__in',
        'post__in' => $post_ids

     );

    $loop = new WP_Query( $args );
    
    $out = '';

    // have_posts() is a wrapper function for $wp_query->have_posts(). Since we
    // don't want to use $wp_query, use our custom variable instead.
    if ( $loop->have_posts() ) :
         while ( $loop->have_posts() ) : $loop->the_post();

            $data = array(
                'post_id'       => get_the_ID(),
                'image'         => get_the_post_thumbnail( get_the_ID(), 'item-thumbnail' ),
                'title'         => get_the_title(),
                'description'   => get_field( 'part_number' ),
                
            );
            
            $out .= _s_get_item( $data, true );
  
        endwhile;
     endif;
     wp_reset_postdata();
     
     return $out;
}


function _s_get_product_favorites() {

    global $doolittle_favorite;
    $post_ids = $doolittle_favorite->get_count_by_type( 'favorite', 'product' );

    if( empty( $post_ids ) ) {
        return false;
    }

    // arguments, adjust as needed
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 200,
        'post_status'    => 'publish',
        'orderby' => 'post__in',
        'no_found_rows' => true,
        'post__in' => $post_ids

     );

    $loop = new WP_Query( $args );
    
    $out = '';

    // have_posts() is a wrapper function for $wp_query->have_posts(). Since we
    // don't want to use $wp_query, use our custom variable instead.
    if ( $loop->have_posts() ) :
         while ( $loop->have_posts() ) : $loop->the_post();

            $data = array(
                'post_id'       => get_the_ID(),
                'image'         => get_the_post_thumbnail( get_the_ID(), 'item-thumbnail' ),
                'title'         => get_the_title(),
                'description'   => get_field( 'part_number' ),
                
            );
            
            $out .= _s_get_item( $data, true );

        endwhile;
     endif;
     wp_reset_postdata();
     
     return $out;
}

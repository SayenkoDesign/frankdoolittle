<?php
global $doolittle_quote;
$doolittle_quote = new Doolittle_Quotes;

function quotes_add( $post_ids ) {
    global $doolittle_quote;
    return $doolittle_quote->add( $post_ids );
}

function quotes_delete( $post_ids ) {
    global $doolittle_quote;
    return $doolittle_quote->delete( $post_ids );
}

function quotes_count() {
    $quote = new Doolittle_Quotes; // needs to be initialized because its a rest api call
    return $quote->get_count();
}

function quotes_get_response( $args ) {
    global $doolittle_quote;
    return $doolittle_quote->response( $args );
}

function get_quote_class() {
    global $doolittle_quote;
    return $doolittle_quote->get_item( get_the_ID() ) ? 'disabled' : '';
}

function get_quote_item( $item_id ) {
    global $doolittle_quote;
    return $quotes->get_item( $item_id ); 
}

function _s_get_quotes_count() {
    global $doolittle_quote;
    return $doolittle_quote->get_data( 'count' );
}


function _s_get_quotes( $type = 'design' ) {
                    
    global $doolittle_quote;

    $post_ids = $doolittle_quote->get_count_by_type( 'quote', $type );
    
    if( empty( $post_ids ) ) {
        return false;   
    }
    
    $prefix = '';
    
    if( 'design' == $type ) {
        $prefix = 'doolittle_';
    }
    
    $post_type = sprintf( '%s%s', $prefix, $type );
                        
    // arguments, adjust as needed
    $args = array(
        'post_type'      => $post_type,
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
            
            $out .= _s_get_item( $data );
             
        endwhile;
     endif;
     wp_reset_postdata();  
     
     return $out;
}

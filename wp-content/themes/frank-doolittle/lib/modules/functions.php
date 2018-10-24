<?php

function _s_is_product_or_design( $post_id ) {
    
    $post_type = get_post_type( $post_id );
    // Check that post ID is actually a product or design
    if( ! $post_type || ! in_array( $post_type, array( 'product', 'doolittle_design' ) ) ) {
        return false;
    }
    
    return str_replace( 'doolittle_', '', $post_type );
}


function get_single_product_or_design( $post_id ) {
    
    if( false == absint( $post_id ) ) {
        return false;
    }
    
    $type = _s_is_product_or_design( $post_id );
    
    if( ! $type ) {
        return false;
    }
    
    $post_type = get_post_type( $post_id );
    
    $args = array(
		'post_type'      => $post_type,
        'p'              => $post_id,
		'posts_per_page' => 1,
		'post_status'    => 'publish',
	);

 
    $out = false;

    
	// Use $loop, a custom variable we made up, so it doesn't overwrite anything
	$loop = new WP_Query( $args );
       
    if ( $loop->have_posts() ) : 
 		while ( $loop->have_posts() ) : $loop->the_post(); 
                
            /*if( 'product' == get_post_type() ) {
                
                $product = new WC_Product( get_the_ID() );
                
                // Ensure product visibility
                if ( empty( $product ) || ! $product->is_visible() ) {
                    return;
                }
            }*/
            
            $out = array(
                'post_id' => get_the_ID(),
                'image' => get_the_post_thumbnail( get_the_ID(), 'item-thumbnail' ),
                'title' => get_the_title(),
                'description' => get_post_meta( get_the_ID(), sprintf( '%s_description', $type ), true ),
                'type' => $type
             );
            
		endwhile;
  	endif;
 
	wp_reset_postdata();
    
    return $out;
    
}



function _s_get_item( $data = array(), $favorite = false, $hide_controls = false, $hide_stock = true ) {
    
    if( empty( $data ) ) {
        return false;
    }
    
    $post_id = $data['post_id'];
    
    if( 'product' == get_post_type( $post_id ) ) {
        
        $product = new WC_Product( $post_id );
        
        // Ensure product visibility
        if ( $hide_stock && ( empty( $product ) || ! $product->is_visible() ) ) {
            return;
        }
    }
    
    $out = sprintf( '<div class="column" data-post-id="%s">', $data['post_id'] );
    $out .= '<div class="item">';
    $out .= sprintf( '<div class="image">%s</div>',  $data['image'] ); 
    $out .= sprintf( '<h4>%s</h4>', $data['title'] );  
    $out .= sprintf( '<p>%s</p>', $data['description'] );  
    
    if( ! $hide_controls ) {
        $out .= sprintf( '<input type="hidden" name="post_ids[]" value="%s">', $data['post_id'] );  
        $out .= '<div class="close-btn"><span><b>&times;</b></span></div>';
     }
     
     if( ! $hide_controls && true == $favorite ) {
        $out .= sprintf('<label class="select-btn"><input type="checkbox" class="hide" name="post_ids[]" value="%s"><span>
                   <i class="fa fa-check" aria-hidden="true"></i></span></label>', $data['post_id'] );
     }
    
    $out .= '</div>';
    $out .= '</div>'; 
    
    return $out;  
 }
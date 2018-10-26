<?php

featured_designs();
function featured_designs() {
        
    $term_id = get_field( 'featured_designs' );
    
    if( empty( $term_id ) ) {
        return false;
    }
    
    // Use this to generate the featured design slide
    $product = new Product_Attributes();
    
    add_filter( 'the_posts', function( $posts, \WP_Query $query )
    {
        if( $pick = $query->get( '_shuffle_and_pick' ) )
        {
            shuffle( $posts );
            $posts = array_slice( $posts, 0, (int) $pick );
        }
        return $posts;
    }, 10, 2 );
    
    $out = '';
    
    $args = array(
        'post_type'         => 'doolittle_design',
        'orderby'           => 'RAND',
        'post_status'       => 'publish',
        'posts_per_page'    => 100,
        '_shuffle_and_pick' => 20
    );
    
    $tax_query[] = array(
        'taxonomy'         => 'doolittle_design_cat',
        'terms'            => [ $term_id ],
        'field'            => 'term_taxonomy_id', 
        'operator'         => 'IN',
        'include_children' => false,
    );
			

	$args['tax_query'] = $tax_query;
    
    // Use $loop, a custom variable we made up, so it doesn't overwrite anything
    $loop = new WP_Query( $args );
    
    $slider_class = ' slider';
                  
    if ( $loop->have_posts() ) : 
    
     
        while ( $loop->have_posts() ) : $loop->the_post(); 
            
            $out .= sprintf( '%s', $product->related_design() );
   
        endwhile;
           
    endif;

    wp_reset_postdata();   
    
    if( empty( $out ) ) {
        return false;
    }
    
     $attr = array( 'id' => 'services', 'class' => 'section featured-designs text-center' );        
          
    _s_section_open( $attr );		
    
    printf( '<div class="column row"><h2 class="text-center">Featured Designs</h2>
            <a href="%s" class="see-more">See More <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a></div>', 
            get_term_link( $term_id ) );
        
    
    printf( '<div class="featured-designs-slider%s">%s</div>', $slider_class, $out );
    _s_section_close();	
}
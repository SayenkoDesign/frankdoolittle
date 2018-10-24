<?php

function get_product_categories() {
 
    $args = array( 
                'taxonomy'      => 'product_cat',
                'hierarchical'  => false,
                'hide_empty'    => false,
                'parent'        => 0
             );
    
    if( is_tax() ) {
        
        $obj = get_queried_object();
                            
        if( $obj->parent ) {
             $args['parent'] = $obj->parent;
        }
        else {
            $args['parent'] = $obj->term_id;
        }
    } 
    
    $terms = get_terms( $args );
    
    $out = '';
            
    if ( $terms && ! is_wp_error( $terms ) ) {
        
        foreach( $terms as $term ) {
            
            $out .= sprintf( '<li><a href="%s" title="%s">%s</a> (%s)</li>', 
                    get_term_link( $term ), 
                    esc_attr( __('Link to ') . $term->name ), 
                    $term->name, 
                    $term->count );
        }
        
        $out = sprintf( '<ul class="term-list">%s</ul>', $out );
    } 
    
    return $out;  
}


function hex_color( $hex ) {
    $hex = str_replace( '#', '', $hex );
    return sprintf( '#%s', $hex );
}
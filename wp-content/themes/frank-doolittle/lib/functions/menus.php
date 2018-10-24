<?php


function _s_primary_menu_menu_atts( $atts, $item, $args ) {
      
    //error_log( print_r( $args, 1 ) );
    
    if( 'primary' != $args->theme_location ) {
        return $items;
    }
          
    $classes = $item->classes;
    
    foreach( $classes as $class ) {
        
        if(  ( strpos( $class, 'product_cat' ) !== false ) || ( strpos( $class, 'design_cat' ) !== false ) ) {
            $term_id = $item->object_id;
            $taxonomy = str_replace( 'menu-item-object-', '', $class );
            $term_object = get_term_by( 'id', $term_id, $taxonomy );
            
            if ( $term_object instanceof WP_Term ) {
                
                 $attachment_id = get_woocommerce_term_meta( $term_id, 'thumbnail_id', true );
            
                 if( $photo = wp_get_attachment_image( $attachment_id, 'medium' ) ) {
                     $atts['data-photo'] = esc_html( $photo );
                 }
                
            }
        }
            
    }
      
    return $atts;
}

add_filter( 'nav_menu_link_attributes', '_s_primary_menu_menu_atts', 10, 3 );



// Add data attribute to menu item
function _s_contact_menu_atts( $atts, $item, $args ) {
      $classes = $item->classes;
      
 	  if ( in_array( 'get-started', $classes ) ) {
		$atts['data-open'] = 'contact';
	  }
	  return $atts;
}

add_filter( 'nav_menu_link_attributes', '_s_contact_menu_atts', 10, 3 );



// Filter menu items as needed and set a custom class etc....
function set_current_menu_class($classes) {
	global $post;
	
	/*
	if( _s_is_page_template_name( 'find-an-agent' ) || is_post_type_archive( 'agent' ) || is_singular( 'agent' ) ) {
		
		$classes = array_filter($classes, "remove_parent_classes");
		
		if ( in_array('menu-item-206', $classes ) )
			$classes[] = 'current-menu-item';
	}
	*/
			
	return $classes;
}

//add_filter('nav_menu_css_class', 'set_current_menu_class',1,2); 


// check for current page classes, return false if they exist.
function remove_parent_classes($class){
  return in_array( $class, array( 'current_page_item', 'current_page_parent', 'current_page_ancestor', 'current-menu-item' ) )  ? FALSE : TRUE;
}
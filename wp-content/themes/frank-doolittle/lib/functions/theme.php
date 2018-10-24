<?php

// Show degub info
if( function_exists( '_s_display_debug_data' ) ) {
   // add_action( 'wp_footer', '_s_display_debug_data' );
}

/**
 * Custom Body Class
 *
 * Add additional body classes to pages for targeting.
 *
 * @param array $classes
 * @return array
 */
function _s_add_custom_body_class( $classes ) {
	
	$body_class = '';
	
 	if( wp_is_mobile() ) {
		$body_class = 'mobile';
	}
	
	
	
	// If exists add body class
	if( !empty( $body_class ) ) {
		$classes[] = $body_class;
	}
	
	return $classes;
}
add_filter( 'body_class', '_s_add_custom_body_class' );



function _s_add_design_archive_class( $classes ) {
  
  if ( is_post_type_archive( 'doolittle_design' ) || is_tax( 'doolittle_design_cat' ) ) {
      $classes[] = 'design-archive';
  }
   return $classes;
}
add_filter( 'body_class', '_s_add_design_archive_class' );



function be_template_redirect( $template ) {
	if ( is_tax( 'doolittle_design_cat' ) ) 
		$template = locate_template( 'archive-doolittle_design.php' );	
	return $template;
}
add_filter( 'template_include', 'be_template_redirect' );	



function _s_add_product_archive_class( $classes ) {
  
  if ( is_post_type_archive( 'product' ) || is_tax( 'product_cat' ) ) {
      $classes[] = 'product-archive';
  }
   return $classes;
}
add_filter( 'body_class', '_s_add_product_archive_class' );



function hap_hide_the_archive_title( $title ) {

	// Skip if the site isn't LTR, this is visual, not functional.
	// Should try to work out an elegant solution that works for both directions.
	if ( is_rtl() ) {
		return $title;
	}

	// Split the title into parts so we can wrap them with spans.
	$title_parts = explode( ': ', $title, 2 );

	// Glue it back together again.
	if ( ! empty( $title_parts[1] ) ) {
		$title = wp_kses(
			$title_parts[1],
			array(
				'span' => array(
					'class' => array(),
				),
			)
		);
		$title = '<span class="screen-reader-text">' . esc_html( $title_parts[0] ) . ': </span>' . $title;
	}

	return $title;

}

add_filter( 'get_the_archive_title', 'hap_hide_the_archive_title' );



function search_filter($query) {
  if ( !is_admin() && $query->is_main_query() ) {
    if ($query->is_search) {
      $query->set('posts_per_page', '-1' );
    }
  }
}

add_action('pre_get_posts','search_filter');


// Helper function to find a meta value by post ID
// You can search the post_meta table by post ID to see whats been added.
function get_meta_value_by_post_id( $post_id = false, $meta_key = '' ) {
    
    if( ! absint( $post_id ) || empty( $meta_key ) ) {
        return false;
    }
    
    $user_id = _s_get_session_user_id();
    
    $args = array(
          'p'         => $post_id, // ID of a page, post, or custom type
          'post_type' => 'any',
          'meta_query' => array(
            array(
                'key'     => $meta_key,
                'value'   => $user_id,
             ),
        ),
    );
    
    $query = new WP_Query( $args );
    
    return $query->found_posts;
}
<?php

/**
*  Creates ACF Options Page(s)
*/


if( function_exists('acf_add_options_sub_page') ) {

	acf_add_options_page(array(
		'page_title' 	=> 'Theme Settings',
		'menu_title' 	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-settings',
		'capability' 	=> 'edit_posts',
 		'redirect' 	=> true
	));
    
    acf_add_options_page(array(
		'page_title' 	=> 'Company Details',
		'menu_title' 	=> 'Company Details',
        'menu_slug' 	=> 'theme-settings-company-details',
        'parent' 		=> 'theme-settings',
		'capability' => 'edit_posts',
 		'redirect' 	=> false
	));
    
    acf_add_options_page(array(
		'page_title' 	=> 'Messages',
		'menu_title' 	=> 'Messages',
        'menu_slug' 	=> 'theme-settings-messages',
        'parent' 		=> 'theme-settings',
		'capability' => 'edit_posts',
 		'redirect' 	=> false
	));
    
    /*
 	 acf_add_options_page(array(
		'page_title' 	=> 'Error 404 Page Settings',
		'menu_title' 	=> 'Error 404 Page Settings',
        'menu_slug' 	=> 'theme-settings-404',
        'parent' 		=> 'theme-settings',
		'capability' => 'edit_posts',
 		'redirect' 	=> false
	));
    */

}


// filter for a specific field based on it's name
add_filter('acf/fields/relationship/query/name=related_products', 'my_relationship_query', 10, 3);
function my_relationship_query( $args, $field, $post_id ) {
	
    // exclude current post from being selected
    $args['exclude'] = $post_id;
	
	
	// return
    return $args;
    
}


function _s_get_acf_image( $attachment_id, $size = 'large', $background = FALSE ) {

	if( ! absint( $attachment_id ) )
		return FALSE;

	if( wp_is_mobile() ) {
 		$size = 'large';
	}

	if( $background ) {
		$background = wp_get_attachment_image_src( $attachment_id, $size );
		return $background[0];
	}

	return wp_get_attachment_image( $attachment_id, $size );

}


function _s_get_acf_oembed( $iframe ) {


	// use preg_match to find iframe src
	preg_match('/src="(.+?)"/', $iframe, $matches);
	$src = $matches[1];


	// add extra params to iframe src
	$params = array(
		'controls'    => 1,
		'hd'        => 1,
		'autohide'    => 1,
		'rel' => 0
	);

	$new_src = add_query_arg($params, $src);

	$iframe = str_replace($src, $new_src, $iframe);


	// add extra attributes to iframe html
	$attributes = 'frameborder="0"';

	$iframe = str_replace('></iframe>', ' ' . $attributes . '></iframe>', $iframe);

	$iframe = sprintf( '<div class="embed-container">%s</div>', $iframe );


	// echo $iframe
	return $iframe;
}


/// Add Woocommerce product attribute fields dynamically to all terms except size
function my_modify_field_group_function($group) {
    

	if ( $group['key'] != 'group_5a2996d2a5ab2' ) {   // note: I replaced $field with $group
        // not our field group
        return $group;
	
	} else {
        
        if ( ! class_exists( 'WooCommerce' ) ) 
            return;
        
        // Gather the global attribute types
        $attribute_terms = wc_get_attribute_taxonomy_names();
            
        // Initialize the array for holding the location rules
        $group_filter = array();
    
        // Loop through the attribute types and build our field group location rules: IF this OR this OR this
        foreach( $attribute_terms as $attribute_term ) {
            
            if ( strpos( $attribute_term, 'size' ) !== false ) {
                continue;
            }
                        
            $group_filter[] = array( array(
                'param'    => 'taxonomy',
                'operator' => '==',
                'value'    => $attribute_term,
                'order_no' => 0,
                'group_no' => 0,
            ) );
        }

		// add an OR rule to existing location rules for a specific field group
		$group['location'] = $group_filter;
		return $group;		
	}
	
}
add_filter('acf/get_field_group', 'my_modify_field_group_function');

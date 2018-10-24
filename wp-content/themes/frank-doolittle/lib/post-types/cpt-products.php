<?php

/**
 * Overwrite product_tag taxonomy properties to effectively hide it from WP admin ..
 */
add_action('init', function() {
    register_taxonomy('product_tag', 'product', [
        'public'            => false,
        'show_ui'           => false,
        'show_admin_column' => false,
        'show_in_nav_menus' => false,
        'show_tagcloud'     => false,
    ]);
}, 100);

/**
 * .. and also remove the column from Products table - it's also hardcoded there.
 */
add_action( 'admin_init' , function() {
    add_filter('manage_edit-product_columns', function($columns) {
        unset( $columns['product_tag'] );
        unset( $columns['taxonomy-product_related'] );
        unset( $columns['taxonomy-product_size'] );
        unset( $columns['taxonomy-product_size'] );
        unset( $columns['taxonomy-product_complexity'] );
        unset( $columns['taxonomy-product_application'] );
        unset( $columns['taxonomy-product_event'] );
        unset( $columns['taxonomy-product_composition'] );
        
        $filters = array( 'price' => 'Price', 
            'view_by' => 'View By', 
            'fit' => 'Fit', 
            'style' => 'Style', 
            'material' => 'Material', 
            'weight' => 'Weight', 
            'properties' => 'Properties', 
            'origin' => 'Origin' );
            
        foreach( $filters as $filter => $filter_name ) {
            unset( $columns['taxonomy-product_filter_' . $filter ] );
        }
        
        
        $date = $columns['date'];
        unset( $columns['date'] );
        $columns = array_insert_after( $columns, 3, array( 'part_number' => 'Product Number' ) );    
        $columns = array_insert_after( $columns, '', array( 'date' => 'Date' ) );    
        return $columns;
    }, 100);
});


function custom_product_column( $column, $post_id ) {
    switch ( $column ) {

       case 'part_number':
            echo get_field( 'part_number', $post_id );
            break;
    }
}

add_action( 'manage_product_posts_custom_column' , 'custom_product_column', 10, 2 );


$vendor = array(
    __( 'Vendor', '_s' ), // Singular
    __( 'Vendors', '_s' ), // Plural
    'product_vendor' // Registered name
);

register_via_taxonomy_core( $vendor, 
	array(
		'rewrite' => false,
        'hierarchical' => true,
        'show_in_nav_menus'   => true,
		'exclude_from_search' => true,
	), 
	array(  'product' ) 
);


function filter_products_by_taxonomy( $post_type, $which ) {

	// Apply this only on a specific post type
	if ( 'product' !== $post_type )
		return;

	// A list of taxonomy slugs to filter by
	$taxonomies = array( 'product_vendor' );

	foreach ( $taxonomies as $taxonomy_slug ) {

		// Retrieve taxonomy data
		$taxonomy_obj = get_taxonomy( $taxonomy_slug );
		$taxonomy_name = $taxonomy_obj->labels->name;

		// Retrieve taxonomy terms
		$terms = get_terms( $taxonomy_slug );

		// Display filter HTML
		echo "<select name='{$taxonomy_slug}' id='{$taxonomy_slug}' class='postform'>";
		echo '<option value="">' . sprintf( esc_html__( 'Show All %s', '_s' ), $taxonomy_name ) . '</option>';
		foreach ( $terms as $term ) {
			printf(
				'<option value="%1$s" %2$s>%3$s (%4$s)</option>',
				$term->slug,
				( ( isset( $_GET[$taxonomy_slug] ) && ( $_GET[$taxonomy_slug] == $term->slug ) ) ? ' selected="selected"' : '' ),
				$term->name,
				$term->count
			);
		}
		echo '</select>';
	}

}
add_action( 'restrict_manage_posts', 'filter_products_by_taxonomy' , 99, 2);



$product_composition_icon = array(
    __( 'Product Composition', '_s' ), // Singular
    __( 'Product Composition', '_s' ), // Plural
    'product_composition' // Registered name
);

register_via_taxonomy_core( $product_composition_icon, 
	array(
		'rewrite' => false,
        'hierarchical' => true,
        'show_in_nav_menus'   => false,
		'exclude_from_search' => false,
	), 
	array(  'product' ) 
);


// Filters

$product_filters = array( 
    'color' => 'Color', 
    'price' => 'Price', 
    'view_by' => 'View By', 
    'fit' => 'Fit', 
    'style' => 'Style', 
    'material' => 'Material', 
    'weight' => 'Weight', 
    'properties' => 'Properties', 
    'origin' => 'Origin' );

foreach( $product_filters as $slug => $name ) {
    
    $args = array(
        __( sprintf( 'Filter - %s', $name ), '_s' ), // Singular
        __( sprintf( 'Filter - %s', $name ), '_s' ), // Plural
        sprintf( 'product_filter_%s', $slug ) // Registered name
    );
    
    register_via_taxonomy_core( $args, 
        array(
            'rewrite' => false,
            'hierarchical' => true,
            'show_in_nav_menus'   => false,
            'exclude_from_search' => false,
        ), 
        array(  'product' ) 
    );

}


// Tags

$product_tags = array( 
    // 'related_tags' => 'Related Product Tags',
    'related' => 'Related Products',
    'size' => 'Size', 
    'complexity' => 'Complexity', 
    'application' => 'Application', 
    'event' => 'Event', 
    );

foreach( $product_tags as $slug => $name ) {
    
    $args = array(
        __( sprintf( 'Tag - %s', $name ), '_s' ), // Singular
        __( sprintf( 'Tags - %s', $name ), '_s' ), // Plural
        sprintf( 'product_%s', $slug ) // Registered name
    );
    
    register_via_taxonomy_core( $args, 
        array(
            'rewrite' => false,
            'hierarchical' => true,
            'show_in_nav_menus'   => false,
            'exclude_from_search' => false,
        ), 
        array(  'product' ) 
    );

}





// Hide meta boxes, we'll use ACF to show them instead

/**
 * Removes the category, author, post excerpt, and slug meta boxes.
 *
 * @since    1.0.0
 *
 * @param    array    $hidden    The array of meta boxes that should be hidden for Acme Post Types
 * @param    object   $screen    The current screen object that's being displayed on the screen
 * @return   array    $hidden    The updated array that removes other meta boxes
 */
function hide_product_meta_boxes( $hidden, $screen ) {
    
    //error_log( print_r( $screen->id, 1 ));

	if ( 'doolittle_design' == $screen->id ) {

		$hidden = array(
			'tagsdiv-product_price_filter',
			'tagsdiv-product_size_tag',
            'tagsdiv-product_complexity_tag',
            'tagsdiv-product_application_tag',
            'tagsdiv-product_event_tag',
    		);
		
	}

	return $hidden;
	
}


//add_action( 'default_hidden_meta_boxes', 'hide_product_meta_boxes', 99, 2 );


function remove_product_metaboxes() {
    //remove_meta_box( 'tagsdiv-product_price_filter','product','side' );  
    remove_meta_box( 'tagsdiv-product_size_tag','product','side' ); 
    remove_meta_box( 'tagsdiv-product_complexity_tag','product','side' ); 
    remove_meta_box( 'tagsdiv-product_application_tag','product','side' ); 
    remove_meta_box( 'tagsdiv-product_event_tag','product','side' ); 
}
//add_action('admin_menu','remove_product_metaboxes');
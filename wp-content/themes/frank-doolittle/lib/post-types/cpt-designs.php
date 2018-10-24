<?php

/**
 * Create new CPT - Designs
 */

class CPT_Designs extends CPT_Core {

    const POST_TYPE = 'doolittle_design';
    const TEXTDOMAIN = '_s';
    
    /**
     * Register Custom Post Types. See documentation in CPT_Core, and in wp-includes/post.php
     */
    public function __construct() {
    
    
        // Register this cpt
        // First parameter should be an array with Singular, Plural, and Registered name
        parent::__construct(
        
            array(
                __( 'Design', self::TEXTDOMAIN ), // Singular
                __( 'Designs', self::TEXTDOMAIN ), // Plural
                self::POST_TYPE // Registered name/slug
            ),
            array(
                'public'              => true,
                'publicly_queryable'  => true,
                'show_ui'             => true,
                'query_var'           => true,
                'capability_type'     => 'post',
                'has_archive'         => true,
                'hierarchical'        => false,
                'show_ui'             => true,
                'show_in_menu'        => true,
                'show_in_nav_menus'   => true,
                'exclude_from_search' => false,
                'rewrite'             => array( 'slug' => 'designs' ),
                'supports' => array( 'title', 'editor', 'thumbnail', 'revisions' ),
            )
        
        );
        
        add_action( 'pre_get_posts', array( $this,'pre_get_posts' ) );
        
     }
     
     
     function pre_get_posts($query) {
		
        global $wpdb;				
		
        
        if ( $query->is_main_query() && is_post_type_archive( self::POST_TYPE ) && !is_admin() ) {
			
            $my_query = $wpdb->prepare('SELECT %1$s.ID FROM %1$s WHERE %1$s.post_type = "doolittle_design" AND %1$s.post_status = "publish" GROUP BY %1$s.post_title', $wpdb->posts, $wpdb->posts, $wpdb->posts, $wpdb->posts, $wpdb->posts );
            $found = $wpdb->get_col($my_query);
            
            $query->set('post__in', $found );
		
		}
			
		return $query;
	}   
     
     

     
    public function columns( $columns ) {
		
        unset( $columns['taxonomy-doolittle_design_size_tag'] );
        unset( $columns['taxonomy-doolittle_design_complexity_tag'] );
        unset( $columns['taxonomy-doolittle_design_application_tag'] );
        unset( $columns['taxonomy-doolittle_design_event_tag'] );
        
        $columns = array_insert_after( $columns, 1, array( 'thumbnail' => 'Featured Image' ) );
        
        $columns = array_insert_after( $columns, 'title', array( 'part_number' => 'Design Number' ) );     
                
        return $columns;
        
    }


    public function columns_display( $column, $post_id ) {     
        
        if( 'doolittle_design' != get_post_type( $post_id ) ) {
            return $column;
        }
        
        
        switch ( $column ) {
            
            case 'thumbnail':
            echo get_the_post_thumbnail( $post_id, array( 100, 100, true ) );
            break;
            case 'part_number':
            echo get_post_meta( $post_id, 'part_number', true );
            break;
        }
           
	}


}

new CPT_Designs();



$design_categories = array(
    __( 'Design Category', '_s' ), // Singular
    __( 'Design Categories', '_s' ), // Plural
    'doolittle_design_cat' // Registered name
);

register_via_taxonomy_core( $design_categories, 
	array(
		'rewrite' => array( 'hierarchical' => true, 'slug'=> 'design-category' )
	), 
	array(  'doolittle_design' ) 
);


// Filters - These are used on Archive page (Organization, Occasion, Decoration)

// Filters

$design_filters = array( 
    'theme' => 'Theme',
    'organization' => 'Organization', 
    'occasion' => 'Occasion', 
    'decoration' => 'Decoration', 
    );

foreach( $design_filters as $slug => $name ) {
    
    $args = array(
        __( sprintf( 'Filter - %s', $name ), '_s' ), // Singular
        __( sprintf( 'Filter - %s', $name ), '_s' ), // Plural
        sprintf( 'design_filter_%s', $slug ) // Registered name
    );
    
    register_via_taxonomy_core( $args, 
        array(
            'rewrite' => false,
            'hierarchical' => true,
            'show_in_nav_menus'   => false,
            'exclude_from_search' => false,
        ), 
        array(  'doolittle_design' ) 
    );

}



// Tags

$design_tags = array( 
    'size' => 'Size', 
    'complexity' => 'Complexity', 
    'application' => 'Application', 
    'event' => 'Events', 
    );

foreach( $design_tags as $slug => $name ) {
    
    $args = array(
        __( sprintf( 'Tag - %s', $name ), '_s' ), // Singular
        __( sprintf( 'Tags - %s', $name ), '_s' ), // Plural
        sprintf( 'doolittle_design_%s', $slug ) // Registered name
    );
    
    register_via_taxonomy_core( $args, 
        array(
            'rewrite' => false,
            'hierarchical' => true,
            'show_in_nav_menus'   => false,
            'exclude_from_search' => false,
        ), 
        array(  'doolittle_design' ) 
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
function acme_remove_meta_boxes( $hidden, $screen ) {
    
    //error_log( print_r( $screen->id, 1 ));

	if ( 'doolittle_design' == $screen->id ) {

		$hidden = array(
			'tagsdiv-organization_filter',
			'tagsdiv-occasion_filter',
			'tagsdiv-decoration_filter',
			'tagsdiv-doolittle_design_size_tag',
            'tagsdiv-doolittle_design_complexity_tag',
            'tagsdiv-doolittle_design_application_tag',
            'tagsdiv-doolittle_design_event_tag',
    		);
		
	}

	return $hidden;
	
}

//add_action( 'default_hidden_meta_boxes', 'acme_remove_meta_boxes', 99, 2 );


function remove_my_post_metaboxes() {
    remove_meta_box( 'tagsdiv-organization_filter','doolittle_design','side' ); 
    remove_meta_box( 'tagsdiv-occasion_filter','doolittle_design','side' ); 
    remove_meta_box( 'tagsdiv-decoration_filter','doolittle_design','side' ); 
    remove_meta_box( 'tagsdiv-doolittle_design_size_tag','doolittle_design','side' ); 
    remove_meta_box( 'tagsdiv-doolittle_design_complexity_tag','doolittle_design','side' ); 
    remove_meta_box( 'tagsdiv-doolittle_design_application_tag','doolittle_design','side' ); 
    remove_meta_box( 'tagsdiv-doolittle_design_event_tag','doolittle_design','side' ); 
}
//add_action('admin_menu','remove_my_post_metaboxes');




<?php

/**
 * Create new CPT - Quotes
 */

class CPT_Quotes extends CPT_Core {

    const POST_TYPE = 'doolittle_quote';
	const TEXTDOMAIN = '_s';

	/**
     * Register Custom Post Types. See documentation in CPT_Core, and in wp-includes/post.php
     */
    public function __construct() {


		// Register this cpt
        // First parameter should be an array with Singular, Plural, and Registered name
        parent::__construct(

        	array(
				__( 'Quote', self::TEXTDOMAIN ), // Singular
				__( 'Quotes', self::TEXTDOMAIN ), // Plural
				self::POST_TYPE // Registered name/slug
			),
			array(
				'public'              => false,
				'publicly_queryable'  => false,
				'show_ui'             => true,
				'query_var'           => true,
				'capability_type'     => 'post',
				'has_archive'         => false,
				'hierarchical'        => false,
				'show_ui'             => true,
				'show_in_menu'        => WP_DEBUG,
				'show_in_nav_menus'   => false,
				'exclude_from_search' => true,
				'rewrite'             => false,
                //'show_in_rest'        => true,
                //'rest_base'          => 'doolittle_package',
                //'rest_controller_class' => 'WP_REST_Posts_Controller',
				'supports' => array( 'title', 'editor', 'custom-fields' ),
			)

        );

        if( WP_DEBUG ) {
            add_action( 'add_meta_boxes', array( $this, 'add_custom_meta_box' ) );  
        }
     }   
     
     
    public function add_custom_meta_box() {
        add_meta_box('debug-data', 'Debug Data', array( $this, 'debug_data' ), self::POST_TYPE, "normal", "low", null);
     }

     public function debug_data( $object ) {
        
        echo '<pre>';
        var_dump( get_post_custom( $object->ID ) );
        echo '</pre>';
    }
    

}

new CPT_Quotes();

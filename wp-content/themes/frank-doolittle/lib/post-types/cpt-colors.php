<?php

/**
 * Create new CPT - Colors
 */

class CPT_Colors extends CPT_Core {

    const POST_TYPE = 'doolittle_colors';
	const TEXTDOMAIN = '_s';

	/**
     * Register Custom Post Types. See documentation in CPT_Core, and in wp-includes/post.php
     */
    public function __construct() {


		// Register this cpt
        // First parameter should be an array with Singular, Plural, and Registered name
        parent::__construct(

        	array(
				__( 'Color', self::TEXTDOMAIN ), // Singular
				__( 'Colors', self::TEXTDOMAIN ), // Plural
				self::POST_TYPE // Registered name/slug
			),
			array(
				'public'              => false,
				'publicly_queryable'  => true,
				'show_ui'             => true,
				'query_var'           => true,
				'capability_type'     => 'post',
				'has_archive'         => false,
				'hierarchical'        => false,
 				'show_in_menu'        => 'edit.php?post_type=doolittle_product',
				'show_in_nav_menus'   => false,
				'exclude_from_search' => true,
				'rewrite'             => false,
				'supports' => array( 'title', 'editor', 'thumbnail' ),
			)

        );


     }



}

new CPT_Colors();
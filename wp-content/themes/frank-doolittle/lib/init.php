<?php

/****************************************
	WordPress Cleanup functions - work in progress
*****************************************/
	include_once( 'wp-cleanup.php' );


/****************************************
	Theme Settings - load main stylesheet, add body classes
*****************************************/
	include_once( 'theme-settings.php' );



/****************************************
	include_onces (libraries, Classes etc)
*****************************************/
	include_once( 'includes/cpt-core/CPT_Core.php' );

	include_once( 'includes/taxonomy-core/Taxonomy_Core.php' );

    include_once( 'includes/theme-functions/array.php' );
    
    include_once( 'includes/theme-functions/string.php' );

    include_once( 'includes/theme-functions/form.php' );
    
    include_once( 'includes/theme-functions/shortcodes.php' );
    
    include_once( 'includes/table-class.php' );

/****************************************
	Post Types
*****************************************/

	 include_once( 'post-types/cpt-products.php' );
     include_once( 'post-types/cpt-designs.php' );
     include_once( 'post-types/cpt-favorites.php' );
     include_once( 'post-types/cpt-packages.php' );
     include_once( 'post-types/cpt-quotes.php' );
     include_once( 'post-types/cpt-orders.php' );
     
/****************************************
	Functions
*****************************************/

    include_once( 'functions/svg.php' );

	include_once( 'functions/theme.php' );

	include_once( 'functions/template-tags.php' );

	include_once( 'functions/acf.php' );

	include_once( 'functions/fonts.php' );

	include_once( 'functions/scripts.php' );

	include_once( 'functions/addtoany.php' );

	include_once( 'functions/menus.php' );
    
    include_once( 'functions/breadcrumbs.php' );

	include_once( 'functions/gravity-forms.php' );

	//include_once( 'functions/widgets.php' );
    
    include_once( 'functions/mega-menu.php' );
    
    include_once( 'functions/members.php' );
    
    include_once( 'functions/facetwp.php' );
    
    /// include_once( 'functions/relevanssi.php' );
    
    include_once( 'functions/social.php' );
    
    include_once( 'functions/wp-all-import.php' );
      
    include_once( 'functions/woocommerce.php' );  
    include_once( 'functions/woocommerce-emails.php' );  
    
    include_once( 'functions/design-import.php' );
    
    // Debug data
    include_once( 'functions/debug.php' );  

/****************************************
	Page Builder
*****************************************/


 	include_once( 'page-builder/functions.php' );

	include_once( 'page-builder/markup.php' );

	include_once( 'page-builder/layout.php' );

	include_once( 'page-builder/filters.php' );

	// Load modules
    include_once( 'page-builder/modules/cta.php' );
	include_once( 'page-builder/modules/content-block.php' );
    include_once( 'page-builder/modules/list.php' );
	include_once( 'page-builder/modules/grid.php' );



/****************************************
	Modules
*****************************************/

include_once( 'modules/init.php' );
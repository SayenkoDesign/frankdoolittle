<?php

add_filter( 'nav_menu_link_attributes', 'filter_menu_link_attributes', 10, 3 );

function filter_menu_link_attributes( $atts, $item, $args ) {
    
    //var_dump( $atts );
    //var_dump( $item );
    
    if( 'primary' != $args->theme_location ) {
        return $atts;
    }
    
    return $atts;
}


/**
 * Register Mega Menu post type
 *
 */
function be_mega_menu_cpt() {

	$labels = array(
		'name'               => 'Mega Menus',
		'singular_name'      => 'Mega Menu',
		'add_new'            => 'Add New',
		'add_new_item'       => 'Add New Mega Menu',
		'edit_item'          => 'Edit Mega Menu',
		'new_item'           => 'New Mega Menu',
		'view_item'          => 'View Mega Menu',
		'search_items'       => 'Search Mega Menus',
		'not_found'          => 'No Mega Menus found',
		'not_found_in_trash' => 'No Mega Menus found in Trash',
		'parent_item_colon'  => 'Parent Mega Menu:',
		'menu_name'          => 'Mega Menus',
	);

	$args = array(
		'labels'              => $labels,
		'hierarchical'        => false,
		'supports'            => array( 'title', 'thumbnail', 'editor', 'revisions' ),
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => 'themes.php',
		'show_in_nav_menus'   => false,
		'publicly_queryable'  => true,
		'exclude_from_search' => true,
		'has_archive'         => false,
		'query_var'           => true,
		'can_export'          => true,
		'rewrite'             => array( 'slug' => 'megamenu', 'with_front' => false ),
		'menu_icon'           => 'dashicons-editor-table', // https://developer.wordpress.org/resource/dashicons/
	);

	register_post_type( 'megamenu', apply_filters( 'be_mega_menu_post_type_args', $args ) );

}
add_action( 'init', 'be_mega_menu_cpt' );

 
/*
Usage:

$args = array(
	'menu_class' => 'menu mega-menu',
	'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
	'depth' => 0,
	'walker' => new Mega_Menu_Walker()
);
wp_nav_menu($args);


// Additional 
- add class "mega-menu-item" to top level menu
- add class="mega-menu-column" to start a new column
*/
class Mega_Menu_Walker extends Walker_Nav_Menu
{
    var $current_classes;
	
	public function start_lvl( &$output, $depth = 0, $args = array() )
	{
         static $mega_menu;
		 
		 $indent = str_repeat("\t", $depth);
		 		 
		 if( 0 === $depth ) {
			 $mega_menu = false;
		 }
		
         
		 if ( in_array("menu-item-has-children", $this->current_classes ) && in_array("mega-menu-item", $this->current_classes ) ) {
            
            $output .= sprintf( "\n%s<ul class=\"mega-sub-menu\">\n", $indent );  
			
			$mega_menu = true;
			
         }
		 else {
			
			if( $mega_menu ) {
				$output .= "\n$indent<ul>\n";  
			}
			else {
				$output .= "\n$indent<ul class=\"sub-menu\">\n";  
			}
				
		}
	}
	
	
	public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        $indent  = ($depth) ? str_repeat("\t", $depth) : '';
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        if (in_array("mega-menu-column", $classes)) {
            
            if( in_array("no-border", $classes ) ) {
                $additional_classes  = 'no-border';
            }
            else {
                $additional_classes  = '';   
            }
            
            
            $output .= sprintf( '</ul><ul class="mega-sub-menu %s">', $additional_classes );
        }
        
        
        unset( $classes['menu-item'] );
        $classes[]   = 'menu-item-' . $item->ID;
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        $id          = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth);
        $id          = $id ? ' id="' . esc_attr($id) . '"' : '';
        $output .= $indent . '<li' . $id . $class_names . '>';
        $atts           = array();
        $atts['title']  = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        $atts['rel']    = !empty($item->xfn) ? $item->xfn : '';
        $atts['href']   = !empty($item->url) ? $item->url : '';
        $atts           = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);
        $attributes     = '';
        
		foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
                
            }
            
            
        }
		
		// Change output tag for mega menu post
		$item_output_tag = 'a';
 		if( strpos( $class_names, 'mega-menu-post-' ) !== false ) {
			$attributes = 'class="hide"';
			$item_output_tag = 'div';
		}
        
        $item_output = $args->before;
        $item_output .= sprintf('<%s %s>', $item_output_tag, $attributes );
        $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID);
        $item_output .= $args->link_after;
        $item_output .= sprintf('</%s>', $item_output_tag );
        $item_output .= $args->after;
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
        
        
        // Check to see if this is a parent list item, if it is open the mega-menu div
        // **** also check for mega menu class
        if (in_array("menu-item-has-children", $classes) && in_array("mega-menu-item", $classes) ) {
            $output .= '<div class="sub-menu">';
            
        }
		
		$this->current_classes = $classes;
    }
    
    
    
    public function end_el(&$output, $item, $depth = 0, $args = array())
    {
        // Check to see if this is a parent list item, if it is close the mega-menu div
        
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        if (in_array("menu-item-has-children", $classes ) && in_array("mega-menu", $classes ) ) {
            $output .= '</div><!-- END mega-menu -->';
        }
        
        //$output .= "</li>\n";
        
    }
     
}




add_filter('wp_nav_menu_objects', 'my_wp_nav_menu_objects', 10, 2);

function my_wp_nav_menu_objects( $items, $args ) {
	
	// loop
	foreach( $items as &$item ) {
        
        $mega_menu = get_field( 'mega_menu', $item );
        
        $mega_menu_type = get_field( 'mega_menu_type', $item );
        
        //error_log( 'mega menu type: ' . $mega_menu_type );
        
        if( empty( $mega_menu ) ) {
            continue;
        }
        
		$menu_item_content = get_field( 'menu_item_content', $item );
	
		if( !empty( $menu_item_content ) && ! is_wp_error( $menu_item_content ) ) {
            
            $opening_markup = apply_filters( 'be_mega_menu_opening_markup', '<div class="mega-menu-post-content">' );
			$closing_markup = apply_filters( 'be_mega_menu_closing_markup', '</div>' );
            
            $content = '';
						
			// Featured Image?
			if( has_post_thumbnail( $menu_item_content ) ) {
				$thumbnail = get_the_post_thumbnail( $menu_item_content, 'medium' );
				$content .= sprintf( '<div class="mega-menu-thumbnail" data-photo="%s">%s</div>', esc_html( $thumbnail ), $thumbnail );
			}
			
			$content .= apply_filters( 'the_content', $menu_item_content->post_content );
			$content = $opening_markup . $content . $closing_markup;
            
            $item->description = $content;
        }
  		
	}
	
	
	// return
	return $items;
	
}


function be_header_menu_desc( $item_output, $item, $depth, $args ) {
	
	if( 'primary' == $args->theme_location && $item->description )
		$item_output = $item->description;
		
	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'be_header_menu_desc', 10, 4 );
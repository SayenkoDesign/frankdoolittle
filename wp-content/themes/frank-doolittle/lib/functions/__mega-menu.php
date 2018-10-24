<?php

/**
 * Mega Menu
 * Version: 1.0
 * Requires ACF fields
 */
 

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;



class Mega_Menu_Walker extends Walker_Nav_Menu
{
    
    var $columns;
    
    var $mega_menu_item = false;
    
    var $menu_item;
    
    var $current_classes;
    
    
    
    public function start_lvl( &$output, $depth = 0, $args = array() )
	{
        $indent = str_repeat("\t", $depth);
             
        $item = $this->menu_item;
        
        $classes = $item->classes;
        

        $tag = ( in_array("menu-item-has-children", $classes ) && in_array("mega-menu-item", $classes ) ) ? 'div' : 'ul';
        
        $output .= sprintf( "\n%s<%s class=\"sub-menu\">\n", $indent, $tag ); 
	}
    
	
 	
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 )
    {
        $indent  = ($depth) ? str_repeat("\t", $depth) : '';
                
        // Custom filter, we need to add mega menu classes earlier than nav_menu_css_class
        $item = apply_filters( 'be_mega_menu_item', $item, $args );
        
        // Grab the menu item so we can use it in start_lvl()
        $this->menu_item = $item; 
        
         
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[]      = 'menu-item-' . $item->ID;
        
        $class_names    = join(' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
        $class_names    = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
       
        $id             = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth);
        $id             = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        
        // Set mega menu flag
        if( in_array("mega-menu-item", $classes ) ) {
             $this->mega_menu_item = true;
        }
        
        
        // Wrap columns
        if( in_array("mega-menu-column", $classes ) ) {
            
            // Close the open column
            if( $this->columns ) {
                $output .= "</ul>";
            }
            
            $output .= '<ul class="mega-sub-menu">';
            
            // Increment the Column count
            $this->columns++;
        }
        
        
        $output        .= $indent . '<li' . $id . $class_names . '>';
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
 		
    }

    
    public function end_lvl( &$output, $depth = 0, $args = array() ) 
    {
        $indent = str_repeat( "\t", $depth );
        
        $item = $this->menu_item;
                
        if( true == $this->mega_menu_item ) {
            $output .= "$indent</ul><!-- close mega-sub-menu-->\n";
        }
		
        $tag =  true == $this->mega_menu_item ? 'div' : 'ul';
         
        $output .= "$indent</$tag><!-- close end_lvl -->\n";
        
        // Reset column count
        $this->columns = 0;
        $this->mega_menu_item = false;
    }
     
}



final class Mega_Menu_Walker_Edits {  

    
	/**
	 * Menu Location
	 *
	 * @since 1.0
	 */
	public $menu_location = 'primary';
    
    
    
    /**
	 * Plugin Constructor.
	 *
	 * @since 1.0
	 * @return BE_Mega_Menu
	 */
	function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}
	
    
    /**
	 * Initialize
	 *
	 * @since 1.0
	 */
	function init() {
		// Set new location
		$this->menu_location = apply_filters( 'be_mega_menu_location', $this->menu_location );
		add_action( 'init', array( $this, 'register_cpt' ), 20 );
		add_filter( 'wp_nav_menu_args', array( $this, 'mega_menu_custom_args' ) );
        add_filter( 'be_mega_menu_item', array( $this, 'menu_item' ), 10, 2 );
		add_filter( 'walker_nav_menu_start_el', array( $this, 'display_mega_menu_post' ), 10, 4 );
        add_filter('wp_nav_menu_objects', array( $this, 'add_mega_menu_post' ), 10, 2);
	}
    
    
	/**
	 * Register Mega Menu post type
	 *
	 * @since 1.0.0
	 */
	function register_cpt() {
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
    
    
	/**
	 * Limit Menu Depth
	 *
	 * @since 1.0.0
	 * @param array $args
	 * @return array
	 */
	function mega_menu_custom_args( $args ) {
		if( $this->menu_location == $args['theme_location'] )
			$args['depth'] = 0;
            $args['menu_class'] .= ' mega-menu';
            $args['walker'] = new Mega_Menu_Walker();
		return $args;
	}
    
    
    
    /**
	 * Menu Item
	 *
	 * @since 1.0
	 * @param array $classes
	 * @param object $item
	 * @param object $args
	 * @param int $depth
	 * @return array
	 */
	function menu_item( $item, $args ) {
		if( $this->menu_location != $args->theme_location )
			return $item;
        
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
  		
        $menu_item = get_field( 'mega_menu', $item );
        
        if( empty( $menu_item ) ) {
            return $item;
        }
        
        // Add mega menu parent class
        if( 'Parent' == $menu_item['mega_menu_item'] ) {
             $classes[] = 'mega-menu-item';
        }
                
        // Wrap columns
        if( 'Column' == $menu_item['mega_menu_item'] ) {
            $classes[] = 'mega-menu-column';
        }
        
        $item->classes = $classes;
        
		return $item;
	}
    
    
    
    /**
	 * Add Mega Menu Post to mega menu item
	 *
	 * @since 1.0.0
	 * @param array $args
	 * @return array
	 */
    function add_mega_menu_post( $items, $args ) {
        
        // loop
        foreach( $items as &$item ) {
            
                    
            // Parent needs to be a mega menu parent
            if( ! $item->menu_item_parent ) {
                continue;
            }
            
            $menu_item = get_field( 'mega_menu', $item );
            
            if( empty( $menu_item ) ) {
                continue;
            }
                    
            $menu_item_content = $menu_item['menu_item_content'];
                                    
            if( is_wp_error( $menu_item_content ) || ! is_object( $menu_item_content ) ) {
                continue;
            }
                
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
        
        
        // return
        return $items;
        
    }
        
    
    // Output mega menu description to menu item
    function display_mega_menu_post( $item_output, $item, $depth, $args ) {
        
        if( $this->menu_location == $args->theme_location && $item->description )
            $item_output = $item->description;
            
        return $item_output;
    }
    
}

// new Mega_Menu_Walker_Edits();
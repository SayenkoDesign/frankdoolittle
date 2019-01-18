<?php

class Doolittle_Module_Core {
     
    protected $prefix = 'doolittle_';
    
    protected $ID = null;
    
    protected $_data = [];
    
    //private $quotes;
    
    //private $favorites;
    
    //private $quotes_count;
    
    //private $favorites_count;
    
    private $allowed_types = array( 'favorite', 'quote', 'package' );
    
    private $allowed_keys = array( 'product', 'design' );
    
    public $response = array();
    
    public $message = '';
    
    
    
    
    
    public function __construct() {
        
        $this->_init();
    }
    
    
    /**
	 * Initialize the class.
	 *
	 * Set the raw data, the ID and the parsed settings.
	 *
	 * @since 1.4.0
	 * @access protected
	 *
	 * @param array $data Initial data.
	 */
	protected function _init() {
        // $this->ID = $this->get_post( $this->type );
	}
    
    
    /**
	 * Get items.
	 *
	 * Utility method that receives an array with a needle and returns all the
	 * items that match the needle. If needle is not defined the entire haystack
	 * will be returned.
	 *
	 * @since 1.4.0
	 * @access private
	 * @static
	 *
	 * @param array  $haystack An array of items.
	 * @param string $needle   Optional. Needle. Default is null.
	 *
	 * @return mixed The whole haystack or the needle from the haystack when requested.
	 */
	protected static function _get_items( array $haystack, $needle = null ) {
		if ( $needle ) {
			return isset( $haystack[ $needle ] ) ? $haystack[ $needle ] : null;
		}

		return $haystack;
	}

	/**
	 * Get the raw data.
	 *
	 * Retrieve all the items or, when requested, a specific item.
 	 *
	 * @since 1.4.0
	 * @access public
	 *
	 * @param string $item Optional. The requested item. Default is null.
	 *
	 * @return mixed The raw data.
	 */
	public function get_data( $item = null ) {
		return self::_get_items( $this->_data, $item );
	}


	/**
	 * Set data.
	 *
	 * Change or add new settings to an existing control in the stack.
	 *
	 * @since 1.4.0
	 * @access public
	 *
	 * @param string|array $key   Setting name, or an array of key/value.
	 * @param string|null  $value Optional. Setting value. Optional field if
	 *                            `$key` is an array. Default is null.
	 */
	final public function set_data( $key, $value = null ) {
		// strict check if override all settings.
		if ( is_array( $key ) ) {
			$this->_data = $key;
		} else {
			$this->_data[ $key ] = $value;
		}
	}
        
    
    public function get_post_title_by_type( $type ) 
    {
        
        // Can only be a product or a design
        if( !in_array( $type, $this->allowed_types ) ) {
            return false;
        }
          
        $post_id = $this->ID;
        
        if( ! $post_id ) {
            return false;
        }
        
        return get_the_title( $post_id );
    }
    
    
    public function get_count_by_type( $type, $key ) 
    {
        
        // Can only be a product or a design
        if( !in_array( $type, $this->allowed_types ) ) {
            return false;
        }
          
        $post_id = $this->ID;
        
        if( ! $post_id ) {
            return false;
        }
        
        // convert to meta key format
        $key = sprintf( '_%s', $key );
            
        $post_meta = get_post_meta( $post_id, $key );
            
        if( empty( $post_meta ) ) {
            return false;
        }
        
        // Remove duplicates
        $post_meta = array_unique( $post_meta );
        
        return $post_meta;
    }
    
    
    /*
     * Get post by type
    */
    public function get_post( $type, $create = false ) 
    {
        
        if( empty( $type ) ) {
            return false;
        }
                
        $post_ids = array();
        
        
        $args = array(
            'post_type'      => sprintf( 'doolittle_%s', $type ),
            'posts_per_page' => 1,
            'post_status'    => 'publish',
            //'no_found_rows' => true,
            'meta_query' => array(
                array(
                    'key' => '_user_id',
                    'value' => DOOLITTLE_USER_ID
                )
            )
        );
        
        //error_log( print_r( $args, 1) );
    
        // Use $loop, a custom variable we made up, so it doesn't overwrite anything
        $loop = new WP_Query( $args );
        
        //$found_posts = $loop->found_posts;
        $post_ids = wp_list_pluck( $loop->posts, 'ID' );
        
        wp_reset_postdata();
        
        //error_log( sprintf('user id: %s', DOOLITTLE_USER_ID ) );
          
        if( ! empty( $post_ids ) ) {            
            return $post_ids[0];
        }
        
        if( true == $create ) {
            $args = array(
                'post_title'    => md5( uniqid( rand(), true ) ),
                'post_type'     => sprintf( 'doolittle_%s', $type ),
                'post_status'   => 'publish',
                'meta_input'    => array(
                        '_user_id' => DOOLITTLE_USER_ID
                )
            );
                    
            $post_id = wp_insert_post( $args, false ); 
            
            error_log( sprintf( 'New %s ID created: %s %s', sprintf( 'doolittle_%s', $type ), $post_id, print_r( $args, 1) ) );
            
            return $post_id;
        }
        
    }
    
    
    public function get_type( $post_id ) 
    {
    
        $post_type = get_post_type( $post_id );
        
        
        if( ! $post_type ) {
            return false;
        }
        
        $post_type = str_replace( 'doolittle_', '', $post_type );
        
        // Check that post ID is actually a product or design
        if( ! in_array( $post_type, $this->allowed_types ) ) {
            return false;
        }
        
        
        
        return $post_type;
    }
    
    
    public function get_key( $post_id ) 
    {
    
        $post_type = get_post_type( $post_id );
        
        if( ! $post_type ) {
            return false;
        }
        
        $post_type = str_replace( 'doolittle_', '', $post_type );
                
        // Check that post ID is actually a product or design
        if( ! in_array( $post_type, $this->allowed_keys ) ) {
            return false;
        }
                
        return $post_type;
    }
    
    
   public function get_item( $item_id = false )  {
     
         $item_id = absint( $item_id );
         
         if( ! $item_id ) {
             return false;
         }
         
         
         $key = $this->get_key( $item_id  );
         
         // Post we'll be adding item to
         $post_id = $this->ID;
          
         if( ! $post_id ) {
            return false;
         }
              
        
        // convert to meta key format
        $key = sprintf( '_%s', $key );
         
        // Get all items
        $items_found = get_post_meta( $post_id, $key );
        
        // do we really need to remove duplicates?
        $items_found = array_unique( $items_found );
   
        // Try to find the item
        if( !in_array( $item_id, $items_found ) ) {
            return false;
        }
         
        return true;
    }

    
    
    public function add_item( $item_id = false, $type = false, $key = false ) 
    {
        
        if( false == absint( $item_id ) ) {
            return false;
        }        
        
        // Post we'll be adding item to, create if needed
        $post_id = $this->get_post( $type, true );
                
        if( ! $post_id ) {
            return false;
        }
        
        // convert to meta key format
        $key = sprintf( '_%s', $key );
                
        // Get all items
        $items_found = get_post_meta( $post_id, $key );
                
        // do we really need to remove duplicates?
        $items_found = array_unique( $items_found );
        
        // Try to find the item
        if( is_array( $items_found ) && in_array( $item_id, $items_found ) ) {
            return false;
        }
        
 
        return add_post_meta( $post_id, $key, $item_id );
 
    }
    
    
    
    public function delete_item( $item_id = false, $type = false, $key = false ) 
    {
        
        if( false == absint( $item_id ) ) {
            return false;
        }
        
        // Post we'll be adding item to
        $post_id = $this->ID;
        
        if( ! $post_id ) {
            return false;
        }
        
        // convert to meta key format
        $key = sprintf( '_%s', $key );
             
        return delete_post_meta( $post_id, $key, $item_id );
    }
    
    
    
    public function response( $args = array() ) {
        
        if( !is_array($args ) || empty( $args ) )
            return false;
            
        $args = wp_parse_args( $args, $this->response );
        
        //error_log( print_r( $args, 1 ) );
        
        $response = new WP_REST_Response( $args );
        $response->header( 'Access-Control-Allow-Origin', apply_filters( 'doolittle_access_control_allow_origin', '*' ) );
    
        return $response;
        
    }
    
    
    public function __destruct()
    {
        
    }
    
}
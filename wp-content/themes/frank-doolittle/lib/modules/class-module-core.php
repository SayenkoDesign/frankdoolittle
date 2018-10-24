<?php

class Doolittle_Module_Core {
 
    var $prefix = 'doolittle_';
    
    var $quotes;
    
    var $favorites;
    
    var $quotes_count;
    
    var $favorites_count;
    
    var $allowed_types = array( 'favorite', 'quote', 'package' );
    
    var $allowed_keys = array( 'product', 'design' );
    
    var $response = array();
    
    var $message = '';
    
    var $ID = '';
    
    public function __construct() {
        
        
    }
        
    
    public function get_post_title_by_type( $type ) 
    {
        
        // Can only be a product or a design
        if( !in_array( $type, $this->allowed_types ) ) {
            return false;
        }
          
        $post_id = $this->get_post( $type );
        
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
          
        $post_id = $this->get_post( $type );
        
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
    public function get_post( $type ) 
    {
        
        if( empty( $type ) ) {
            return false;
        }
        
        $post_ids = array();
        
        
        $args = array(
            'post_type'      => sprintf( 'doolittle_%s', $type ),
            'posts_per_page' => 1,
            'post_status'    => 'publish',
            'meta_query' => array(
                array(
                    'key' => '_user_id',
                    'value' => DOOLITTLE_USER_ID
                )
            )
        );
    
        // Use $loop, a custom variable we made up, so it doesn't overwrite anything
        $loop = new WP_Query( $args );
        
        $found_posts = $loop->found_posts;
          
        if( $found_posts ) {
            $post_ids = wp_list_pluck( $loop->posts, 'ID' );
            wp_reset_postdata();
            return $post_ids[0];
        }
        
        wp_reset_postdata();
        
        $post_id = wp_insert_post( array(
            'post_title'    => md5( uniqid( rand(), true ) ),
            'post_type'     => sprintf( 'doolittle_%s', $type ),
            'post_status'   => 'publish',
            'meta_input'    => array(
                    '_user_id' => DOOLITTLE_USER_ID
            )
        ), false ); 
        
        error_log( sprintf( 'New ID created: %s', $post_id ) );
        
        return $post_id;
        
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
         $post_id = $this->get_post( $this->type );
          
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
        
        // Post we'll be adding item to
        $post_id = $this->get_post( $type );
                
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
        $post_id = $this->get_post( $type );
        
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
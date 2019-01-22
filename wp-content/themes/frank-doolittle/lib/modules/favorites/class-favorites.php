<?php

class Doolittle_Favorites extends Doolittle_Module_Core {
    
    private $type = 'favorite';
    
     
    public function __construct() {
        
        parent::__construct();
        
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
        
        $this->ID = $this->get_post( $this->type );
        
        $args = [
            'count' => $this->get_count(),
        ];
        
        $this->set_data( $args );
        
	}
    
    
    // Quote Count

    public function get_count() {
        $products = $this->get_count_by_type( $this->type, 'product' );
        $designs = $this->get_count_by_type( $this->type, 'design' );
        $total = 0;                
        
        if( is_array( $products ) && !empty( $products ) ) {
            
            // check that product exists                        
            foreach( $products as $key => $product ) {
                                
                // if( ! wc_get_product( $product ) ) {
                if( FALSE === get_post_status( $product ) ) {
                    unset( $products[$key] );
                }
            }
                        
            $total = $total + count( $products );
        }
            
        if( is_array( $designs ) && !empty( $designs ) ) {
            
            // check that design exists
            foreach( $designs as $key => $design ) {
                                
                if( false == is_string( get_post_status( $design ) ) ) {
                    unset( $designs[$key] );
                }
            }
            
            $total = $total + count( $designs );
        }
        
        return $total;
    }
    
    
     
    public function add( $post_ids = array() ) {
        
        if( !is_array( $post_ids ) || empty( $post_ids ) ) {
            return false;
        }
                
        $favorites = array();
        
        foreach( $post_ids as $post_id ) {
            
             // Get key
            $key = $this->get_key( $post_id );
            
            // if this is already a quote, don't add to favorites
            /*
            if( get_quote_item( $post_id ) ) {
                continue;
            }
            */
                                                 
            $add_item = $this->add_item( $post_id, $this->type, $key );
            
            // Add item 
             
            if( $add_item ) {
                 $favorites[$key] = get_single_product_or_design( $post_id );
            } 
        }
        
        return $favorites;
        
    }
    
    
    
    public function delete( $post_ids = array() ) {
        
        if( !is_array( $post_ids ) || empty( $post_ids ) ) {
            return false;
        }
        
        $removed = array();
        
        foreach( $post_ids as $post_id ) {
            
            // Get key
            $key = $this->get_key( $post_id );
             
            // Delete item
            $delete_item = $this->delete_item( $post_id, $this->type, $key );
                         
            if( $delete_item ) {                
                $removed[] = $post_id;
            } 
        }
        
        return $removed;
        
    }   
    
    private function get_post_favorites( $user_id = false ) 
    {
        $type = 'favorite';
        
        $post_ids = array();
        
        $_user_id = $user_id ? $user_id : DOOLITTLE_USER_ID;
        
        $args = array(
            'post_type'      => sprintf( 'doolittle_%s', $type ),
            'posts_per_page' => 1,
            'post_status'    => 'publish',
            'meta_query' => array(
                array(
                    'key' => '_user_id',
                    'value' => $_user_id
                )
            )
        );
    
        // Use $loop, a custom variable we made up, so it doesn't overwrite anything
        $loop = new WP_Query( $args );
        
        $found_posts = $loop->found_posts;
        
        $_posts = $loop->posts;
                  
        wp_reset_postdata();
        
        if( $found_posts ) {
            $post_ids = wp_list_pluck( $_posts, 'ID' );
            return $post_ids[0];
        }
        
        return false;
                
    }
    
    public function update_user_favorites( $user_id ) {
        
        $logged_out_post_id = $this->get_post_favorites();
        
        // Do logged in favorites exist?
        $user_favorites = $this->get_post_favorites( $user_id );
        
        if( $user_favorites ) {
            // update user favorites with logged out favorites
            
            $products = get_post_meta( $logged_out_post_id,  '_product' );
            
            if( !empty( $products ) ) {
                foreach( $products as $product ) {
                    add_post_meta( $user_favorites, '_product', $product );
                }
            }
            
            $designs = get_post_meta( $logged_out_post_id,  '_design' );
            
            if( !empty( $designs ) ) {
                foreach( $designs as $design ) {
                    add_post_meta( $user_favorites, '_design', $design );
                }
            }
            
            wp_delete_post( $logged_out_post_id);
            
        }
        else {
            update_post_meta( $logged_out_post_id, '_user_id', $user_id );
        }
                
        
 
    }
    
    
    public function remove_expired() 
    {
        //if ( false === ( $remove_expired = get_transient( sprintf('doolittle_remove_expired_%s', $this->type ) ) ) ) :
            
            //set_transient( 'doolittle_remove_expired_favorites', $remove_expired, 1 * MONTH_IN_SECONDS );
            
            // Get all quotes older than 30 days
            $args = array(
                'post_type' => sprintf( 'doolittle_%s', $this->type ),
                'date_query' => array(
                    array(
                        'column' => 'post_date_gmt',
                        'before' => '1 month ago',
                    ),
                    array(
                        'column' => 'post_modified_gmt',
                        'before' => '1 month ago',
                    ),
                ),
                'posts_per_page' => 5,
            );
            
            //error_log( print_r( $args, 1 ) );
            
            $loop = new WP_Query( $args );
            
            $post_ids = [];
            
            if ( $loop->have_posts() ) : 
                while ( $loop->have_posts() ) : $loop->the_post(); 
                    
                    $user_id = get_post_meta( get_the_ID(), '_user_id', true );
                    
                    if( ! is_numeric( $user_id ) ) {
                        $post_ids[] = get_the_ID();
                        wp_delete_post( get_the_ID() );
                    }
                    
                endwhile;
                
            endif;
            
            wp_reset_postdata();
        
       // endif;

     
        
                
    }
    
    
    public function __destruct()
    {
        //$this->remove_expired();
    }
    
}

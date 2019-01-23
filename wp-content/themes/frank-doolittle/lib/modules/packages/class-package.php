<?php

/*
[package-id] => 3
[design-id] => 63
[product-id] => 25
[total-quantity] => 10
[sizes] => yes
[attributes] => Array
    (
        [0] => Array
            (
                [color] => red
                [size] => small
                [quantity] => 5
            )

        [1] => Array
            (
                [color] => red
                [size] => small
                [quantity] => 5
            )

    )
*/

class Doolittle_Package extends Doolittle_Module_Core {
    
    var $type = 'package';
         
    public function __construct() {
        
        parent::__construct();
          
    }    
    
    
    function get( $post_id = false ) {
    
        if( ! $post_id ) {
           return false; 
        }
        
        // arguments, adjust as needed
        $args = array(
            'post_type'      => 'doolittle_package',
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
            
        if( !empty( $loop->posts ) ) {
            $post_ids =  wp_list_pluck( $loop->posts, 'ID' );
                    
            if( !empty( $post_ids ) ) {
                return $post_ids[0] == $post_id ? true : false;
            }
        }
        
        wp_reset_postdata();
        
        return false;
    }
    
    
    
    function get_the_data( $post_id ) {
        
        if( ! absint( $post_id ) ) {
            return false;
        }
        
        // Needs to be a package
        if( 'doolittle_package' != get_post_type( $post_id ) ) {
            return false;
        }
        
        // Needs to exist
        $data = get_post_meta( $post_id, '_package_data', true );
        
        if( empty( $data ) ) {
            return false;   
        }
        
        $data = maybe_unserialize( $data );
        
        if( !is_array( $data ) || empty( $data ) ) {
            return false;   
        } 
        
        return $this->get_attributes( $data );        
         
    }
    
    
    function get_attributes( $data ) {
        
        $defaults = array(
            'design' => false,
            'product' => false,
            'total-quantity' => false,
            'sizes' => 'no',
            'notes' => '',
            'post_ids' => array(),
            'attributes' => array() // color, size, quantity

        );
        
        $data = wp_parse_args( $data, $defaults );
        
        // Add product and design
        
        if( !empty( $data['post_ids'] ) ) {
            foreach( $data['post_ids'] as $post_id ) {
                $item = get_single_product_or_design( $post_id );
                if( !empty( $item ) ) {
                    $data[$item['type']] = $item;
                }
                
                
            }
        }
        
        return $data;
    }
        
    
    public function add() {
   
       $post_id = wp_insert_post( array(
            'post_title'    => md5( uniqid( rand(), true ) ),
            'post_type'     => 'doolittle_package',
            'post_status'   => 'publish',
            'meta_input'    => array(
                    '_user_id' => DOOLITTLE_USER_ID
            )
        ), false ); 
        
    
        return $post_id;
    }
    
    
    
    public function delete( $post_ids = array() ) {
        
        if( !is_array( $post_ids ) || empty( $post_ids ) ) {
            return false;
        }
        
        $removed = array();
        
        foreach( $post_ids as $post_id ) {
            
            $delete_item = $this->delete_item( $post_id );
                         
            if( $delete_item ) {                
                $removed[] = $delete_item;
            } 
        }
        
        
        
        return $removed;
        
    }
    
    
    public function delete_item( $post_id = false, $type = false, $key = false ) {
           
        if( false == absint( $post_id ) ) {
            return false;
        }
        
        $removed = wp_trash_post( $post_id );
        error_log( sprintf( 'Add to trash: [Post ID: %s] by Doolittle_Package::delete_item', $post_id ) );
        
        if( !is_wp_error( $removed ) ) {
            return $removed->ID;
        }
           
    }
    
        
    
    public function update( $data ) {
        
        if( !is_array( $data ) || empty( $data ) ) {
            return false;   
        }
        
        $post_id = $data['package-id'];
          
        if( ! absint( $post_id ) ) {
            return false;
        }
        
         
        if( 'doolittle_package' != get_post_type( $post_id ) ) {
            return false;
        }
        
        // reset sizes if there are no attributes
        if( empty( $data['attributes'] ) ) {
            $data['sizes'] = 'no';
        }
        
        $serialized = maybe_serialize( $data );        
      
        return update_post_meta( $post_id, '_package_data', $serialized );
        
    }
    
    
    private function get_post_packages( $user_id = false ) 
    {
        $type = 'package';
        
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
    
    public function update_user_packages( $user_id ) {
        
        $logged_out_post_id = $this->get_post_packages();
        
        // Do logged in packages exist?
        $user_packages = $this->get_post_packages( $user_id );
        
        if( $user_packages ) {
            // update user packages with logged out packages
            $packages = get_post_meta( $logged_out_post_id,  '_package_data' );
            
            if( !empty( $packages ) ) {
                foreach( $packages as $package ) {
                    add_post_meta( $user_packages, '_product', $package );
                }
            }
            
            
            wp_trash_post( $logged_out_post_id );
            error_log( sprintf( 'Add to trash: [Post ID: %s] by Doolittle_Package::update_user_packages', $logged_out_post_id ) );
            
        }
        else {
            update_post_meta( $logged_out_post_id, '_user_id', $user_id );
        }
                
        
 
    }
    
    
    public function remove_expired() 
    {
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
            'post_status' => 'publish'
        );
        
        //error_log( print_r( $args, 1 ) );
        
        $loop = new WP_Query( $args );
        
        $post_ids = [];
        
        if ( $loop->have_posts() ) : 
            while ( $loop->have_posts() ) : $loop->the_post(); 
                
                $user_id = get_post_meta( get_the_ID(), '_user_id', true );
                
                if( ! is_numeric( $user_id ) ) {
                    $post_ids[] = get_the_ID();
                    wp_trash_post( get_the_ID() );
                    error_log( sprintf( 'Add to trash: [Post ID: %s] by Doolittle_Package::remove_expired', get_the_ID() ) );
                }
                
            endwhile;
            
        endif;
        
        wp_reset_postdata();
                
    }
    
    
    public function __destruct()
    {
        
        //$this->remove_expired();
        
    }
  
}

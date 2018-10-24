<?php

// Register REST API endpoints
class Doolittle_Package_REST_API_Endpoints {

    /**
     * The namespace.
     *
     * @var string
     */
    protected $namespace;
    
    
    /**
	 * Register the routes for the objects of the controller.
	 */
	public static function register_endpoints() {
		
        $namespace = sprintf( 'wp/%s', API_VERSION );
        
        // Create a new blank package
        register_rest_route( $namespace, API_PREFIX . 'package/add', array(
            'methods'   => 'GET',
            'callback'  => array( 'Doolittle_Package_REST_API_Endpoints', 'add' ),
        ) );
        
        // Delete a package
        register_rest_route( $namespace, API_PREFIX . 'package/delete', array(
            'methods'   => 'POST',
            'callback'  => array( 'Doolittle_Package_REST_API_Endpoints', 'delete' ),
            'args' => array(
                'post_ids' => array(
                    'required' => true,
                    'type' => 'object',
                    'description' => 'Package post_ids',
                )            
            )
        ) );
        
        
        // Delete a package
        register_rest_route( $namespace, API_PREFIX . 'package/update', array(
            'methods'   => 'POST',
            'callback'  => array( 'Doolittle_Package_REST_API_Endpoints', 'update' ),
            'args' => array(
                'data' => array(
                    'required' => true,
                    'type' => 'object',
                    'description' => 'Package form',
                )            
            )
        ) );
	}
    
    
    public static function add( $request ) {
        
        // Stop direct linking.
        $nonce = $request->get_header('X-WP-Nonce');
        //error_log( print_r( wp_verify_nonce( $nonce, 'wp_rest' ), 1) );
        //error_log( wp_get_referer() );
        
        if( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
            //$error = array( 'Error' => 'Not valid' );
            //error_log( print_r( $error, 1 ) );
            return  packages_get_response( array( 'Error' => 'Not valid' ) );
        }
        
        $post_id = packages_add();        
        
        $retval = array( 
                    'package_id' => $post_id,
                    'favorites_count' => favorites_count(),  
                    'quotes_count' => quotes_count(), 
                    );
        
        return  packages_get_response( $retval );
    }
    
    
    
    // Delete package if it exists
    public static function delete( $request ) {
           
        $params = $request->get_params();
                    
        if( ! is_array( $params ) && !isset( $params['post_ids'] ) ) {
           return false;
        }
        
        $post_ids = $params['post_ids'];  
        
        $removed = packages_delete( $post_ids );
                  
        $retval = array( 
                    //'message' => $this->message, 
                    'packages' => $removed,
                    'favorites_count' => favorites_count(),  
                    'quotes_count' => quotes_count(), 
                    );
        
        return  packages_get_response( $retval );  
            
    }
    
    
    public static function update( $request ) {
           
        $params = $request->get_params();
                    
        if( ! is_array( $params ) && !isset( $params['data'] ) ) {
           return false;
        }
        
        $data = $params['data'];  
        
        $success = packages_update( $data );
                
        $retval = array( 
                    //'message' => $this->message, 
                    'package' => $success,
                    'favorites_count' => favorites_count(),  
                    'quotes_count' => quotes_count(), 
                    );
        
        return  packages_get_response( $retval );  
            
    }

	
}

add_action( 'rest_api_init', array( 'Doolittle_Package_REST_API_Endpoints', 'register_endpoints' ) );

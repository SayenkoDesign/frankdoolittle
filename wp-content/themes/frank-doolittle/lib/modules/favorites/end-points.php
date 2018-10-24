<?php

// Register REST API endpoints
class Doolittle_Favorite_REST_API_Endpoints {

    /**
     * The namespace.
     *
     * @var string
     */
    private $namespace;
    
    
    /**
	 * Register the routes for the objects of the controller.
	 */
	public static function register_endpoints() {
		
        $namespace = sprintf( 'wp/%s', API_VERSION );
        
        // endpoints will be registered here
		register_rest_route( $namespace, API_PREFIX . 'favorite/add', array(
            'methods'   => 'POST',
            'callback'  => array( 'Doolittle_Favorite_REST_API_Endpoints',  'rest_favorite_add' ),
            'args' => array(
                'post_ids' => array(
                    'required' => true,
                    'type' => 'object',
                    'description' => 'Product or design post_id',
                )            
            )
        ) );
        
        register_rest_route( $namespace, API_PREFIX . 'favorite/delete', array(
            'methods'   => 'POST',
            'callback'  => array( 'Doolittle_Favorite_REST_API_Endpoints', 'rest_favorite_delete' ),
            'args' => array(
                'post_ids' => array(
                    'required' => true,
                    'type' => 'object',
                    'description' => 'Product or design post_id',
                )            
            )
        ) );
   
	}
    
    
    // Add Product or Design to favorites. 
    public static function rest_favorite_add( $request ) {
        
        // Stop direct linking.  
        $nonce = $request->get_header('X-WP-Nonce');        
        if( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
            return  favorites_get_response( array( 'Error' => 'Not valid' ) );
        }
        
        $params = $request->get_params();
                    
        if( ! is_array( $params ) && !isset( $params['post_ids'] ) ) {
           return false;
        }
        
        // Get post ID and type (product|design)
        $post_ids = $params['post_ids'];        
         
        $post_ids = favorites_add( $post_ids );
                
        $retval = array( 
                    //'message' => $this->message, 
                    'post_ids' => $post_ids,
                    'favorites_count' => favorites_count(),  
                    'quotes_count' => quotes_count(), 
                    );
        
        return  favorites_get_response( $retval );
    }
    
    
    // Delete Product or Design to favorites. 
    public static function rest_favorite_delete( $request ) {
        
        global $favorites_class;
        
        $params = $request->get_params();
        
        //error_log( print_r( $params, 1 ) );
            
        if( ! is_array( $params ) && !isset( $params['post_ids'] ) ) {
           return false;
        }
        
        // Get post ID and type (product|design)
        $post_ids = $params['post_ids'];
          
        $post_ids = favorites_delete( $post_ids );
                
        $retval = array( 
                    //'message' => $this->message, 
                    'post_ids' => $post_ids,
                    'favorites_count' => favorites_count(),  
                    'quotes_count' => quotes_count(), 
                    );
        
        return  favorites_get_response( $retval );
    }


	
}
add_action( 'rest_api_init', array( 'Doolittle_Favorite_REST_API_Endpoints', 'register_endpoints' ) );
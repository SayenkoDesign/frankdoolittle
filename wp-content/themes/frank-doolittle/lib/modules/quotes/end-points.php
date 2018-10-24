<?php

// Register REST API endpoints
class Doolittle_Quote_REST_API_Endpoints {

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
     
        register_rest_route( $namespace, API_PREFIX . 'quote/add', array(
            'methods'   => 'POST',
            'callback'  => array( 'Doolittle_Quote_REST_API_Endpoints', 'rest_quote_add' ),
            'args' => array(
                'post_ids' => array(
                    'required' => true,
                    'type' => 'object',
                    'description' => 'Product or design post_id',
                )            
            )
        ) );
        
        register_rest_route( $namespace, API_PREFIX . 'quote/delete', array(
            'methods'   => 'POST',
            'callback'  => array( 'Doolittle_Quote_REST_API_Endpoints', 'rest_quote_delete' ),
            'args' => array(
                'post_ids' => array(
                    'required' => true,
                    'type' => 'object',
                    'description' => 'Product or design post_id',
                )            
            )
        ) );
        
	}
    
    
    // Add Product or Design to Quote. 
    public static function rest_quote_add( $request ) {
        
        // Stop direct linking.  
        $nonce = $request->get_header('X-WP-Nonce');        
        if( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
            return  rest_ensure_response( array( 'Error' => 'Not valid' ) );
        }
        
        $params = $request->get_params();
        
        //error_log( print_r( $params, 1 ) );
            
        if( ! is_array( $params ) && !isset( $params['post_ids'] ) ) {
           return false;
        }
        
        // Get post ID and type (product|design)
        $post_ids = $params['post_ids'];
        
        $quotes = quotes_add( $post_ids );
        
        // need to get the removed favorites
                
        $retval = array( 
                    //'message' => $this->message, 
                    'post_ids' => $quotes,
                    'favorites_count' => favorites_count(),  
                    'quotes_count' => quotes_count(), 
                    );
                    
        return rest_ensure_response( $retval );
        
    }
    
    
    // Delete Product or Design from Quote. 
    public static function rest_quote_delete( $request ) {
        
         $params = $request->get_params();
        
        //error_log( print_r( $params, 1 ) );
            
        if( ! is_array( $params ) && !isset( $params['post_ids'] ) ) {
           return false;
        }
        
        // Get post ID and type (product|design)
        $post_ids = $params['post_ids'];
        
        $removed = quotes_delete( $post_ids );
                
        $retval = array( 
                    //'message' => $this->message, 
                    'post_ids' => $removed,
                    'favorites_count' => favorites_count(),  
                    'quotes_count' => quotes_count(), 
                    );
                    
        
        return  quotes_get_response( $retval );
    }
	
}

add_action( 'rest_api_init', array( 'Doolittle_Quote_REST_API_Endpoints', 'register_endpoints' ) );
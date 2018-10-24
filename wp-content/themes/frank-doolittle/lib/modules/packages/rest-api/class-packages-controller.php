<?php

// Extend the `WP_REST_Posts_Controller` class
class Packages_Controller extends WP_REST_Posts_Controller
{

    // Override the register_routes() and add '/my_custom_route'
    public function register_routes()
    {
        $schema = $this->get_item_schema();
        $get_item_args = array(
                'context'  => $this->get_context_param( array( 'default' => 'view' ) ),
        );
        
        register_rest_route( $this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)', [
            [
                'methods' => WP_REST_Server::READABLE,
                'callback' => [$this, 'get_item'],
                'permission_callback' => [$this, 'get_items_permissions_check'],
                'args' => $get_item_args,
            ],
            'schema' => [$this, 'get_public_item_schema'],
        ]);
    }
    

    public function get_item( $request ) {
        $post = $this->get_post( $request['id'] );
        if ( is_wp_error( $post ) ) {
                return $post;
        }

        $data     = $this->prepare_item_for_response( $post, $request );
        $response = rest_ensure_response( $data );

        if ( is_post_type_viewable( get_post_type_object( $post->post_type ) ) ) {
                $response->link_header( 'alternate',  get_permalink( $post->ID ), array( 'type' => 'text/html' ) );
        }

        return $response;
    }
    
}

// Create an instance of `My_Custom_Controller` and call register_routes() methods
/*
add_action('rest_api_init', function () {
    $myProductController = new Doolittle_Product_Controller('my_post_type');
    $myProductController->register_routes();
});
*/
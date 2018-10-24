<?php

// Add any scripts needed for Rest API
add_action( 'wp_enqueue_scripts', 'rest_site_scripts', 999 );
function rest_site_scripts() {

    wp_enqueue_script( 'rest_appjs',
      trailingslashit( THEME_JS ). 'app.js',
      array( 'jquery', 'wp-api' ), '', true
    );
    
    // Provide a global object to our JS file contaning our REST API endpoint, and API nonce
    // Nonce must be 'wp_rest' !
    wp_localize_script( 'rest_appjs', 'doolittle_rest_object',
        array(
            'api_nonce'     => wp_create_nonce( 'wp_rest' ),
            'api_url'       => get_rest_url( '', sprintf( 'wp/%s/', API_VERSION ) ), //site_url( sprintf( '/wp-json/wp/%s/', API_VERSION ) ),
            'api_prefix'    => API_PREFIX,
            'messages'      => _module_messages()
        )
    );
}
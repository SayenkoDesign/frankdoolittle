<?php

// Constants
define( 'API_PREFIX', 'doolittle_' );
define( 'API_VERSION', 'v2' );

// We need to use WP_Session instead of native sessions, WP Engine does not support Sessions and Cookies while page caching is enabled

 
define( 'DOOLITTLE_USER_ID', _s_get_session_user_id() );


// Messages

function _module_messages() {
    $fields =  get_fields( 'option' );   
    
    $fields = htmlspecialchars( wp_json_encode( $fields ), ENT_QUOTES, 'UTF-8' );
    
    return $fields;
    
}
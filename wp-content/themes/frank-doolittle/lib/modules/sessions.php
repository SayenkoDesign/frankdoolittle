<?php
// ToDo: Kyle, When we log a user in, we need to update favorites, packages and orders? Or do we combine them
function _s_get_session_user_id() {
        
    $user_id = get_current_user_id() ? get_current_user_id() : md5( uniqid( rand(), true ) );
    
    if( class_exists( 'WP_Session' ) ) {
        
        $wp_session = WP_Session::get_instance();
                 
        if( !isset( $wp_session['user_id'] ) || empty( $wp_session['user_id'] ) ) {
            
            $wp_session['user_id'] = $user_id;
        }
        else {
             $user_id = $wp_session['user_id'];   
        }
        
    }
    
    return $user_id;
}

function _s_update_session_user_id( $user_id ) {
            
    if( class_exists( 'WP_Session' ) ) {
        
        $wp_session = WP_Session::get_instance();
        $wp_session['user_id'] = $user_id;
    }
}



// Merge any favorites, quotes and package session id's with logged in user id
// update WP_Session user_id
function _s_user_register( $user_id ) {
    
    // if DOOLITTLE_USER_ID != $user_id

}

//add_action( 'user_register', '_s_user_register', 10, 1 );


// Merge any favorites, quotes and package session id's with logged in user id
// update WP_Session user_id
function your_function( $user_login, $user ) {
    
    if ( DOOLITTLE_USER_ID != $user->ID ) {
        $favorites = new Doolittle_Favorites;
        $favorites->update_user_favorites( $user->ID );
        
        $quotes = new Doolittle_Quotes;
        $quotes->update_user_quotes( $user->ID );
        
        $packages = new Doolittle_Package;
        $packages->update_user_packages( $user->ID );
        
        _s_update_session_user_id( $user->ID );
    }

}

add_action('wp_login', 'your_function', 10, 2);

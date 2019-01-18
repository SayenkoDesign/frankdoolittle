<?php
/*
function _s_display_debug_data() {
    
    if( ! is_user_logged_in() ) {
        return false;
    }
    
    if( 2 != get_current_user_id() ) { // Kyle
        return false;
    }
    
    $output = '';
    
    $data = [];
    
    $data['user_id'] = DOOLITTLE_USER_ID;
    
    
    $core = new Doolittle_Module_Core;
    
    $data['quote_title'] = $core->get_post_title_by_type( 'quote' );
    
    $data['favorite_title'] = $core->get_post_title_by_type( 'favorite' );
        
    foreach( $data as $key => $value ) {
        $output .= sprintf( '<p><strong>%s:</strong> %s</p>', strtoupper( str_replace( '_', ' ', $key ) ), $value );
    }
    
    printf( '<div class="column row">%s</div>', $output );
}
*/
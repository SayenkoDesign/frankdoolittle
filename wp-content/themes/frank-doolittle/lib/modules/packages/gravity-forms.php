<?php

// Add unique quote_id to form
add_filter( 'gform_field_value_quote_id', 'customer_details_set_quote_id' );
function customer_details_set_quote_id( $value ) {
    return md5( uniqid( rand(), true ) );
}

// Get packages on confirmation page
function gform_get_doolittle_packages( $quote_id = '' ) {
    
    if( ! $quote_id ) {
        return false;
    }
    
    $form_id = 5;
    
    $entries = GFAPI::get_entries( $form_id, 
               array( 'field_filters' => array( array('key' => '15', 'value' => $quote_id ) ) ),
               '',
               array( 'offset' => 0, 'page_size' => 1 ) );
    
    if( !empty( $entries ) ) {
        
        $entry = $entries[0];
        $packages = $entry[14];
        if( !empty( $packages ) ) {
            return explode( ',', $packages );
        }
        
    }
    
    return false;
}


// Add packages to form

add_filter( 'gform_field_value_packages', 'customer_details_set_packages' );
function customer_details_set_packages( $value ) {
    // Get list fo packages
    $args = array(
        'post_type'      => 'doolittle_package',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'order'          => 'ASC',
        'orderby'        => 'ID',
        'meta_query' => array(
            array(
                'key' => '_user_id',
                'value' => DOOLITTLE_USER_ID
            )
        )
    );
    
    $loop = new WP_Query( $args );
    
    $post_ids = '';
    
    $found_posts = $loop->found_posts;
          
    if( $found_posts ) {
        $post_ids = wp_list_pluck( $loop->posts, 'ID' );
        
        if( !empty( $post_ids ) ) {
            $post_ids = implode( ',', $post_ids );
        }
        
    }
    
    wp_reset_postdata();
    
    return $post_ids;
}

// Add view order link
function replace_download_link( $text, $form, $entry, $url_encode, $esc_html, $nl2br, $format ) {
 
    $custom_merge_tag = '{view_quote_link}';
 
    if ( strpos( $text, $custom_merge_tag ) === false ) {
        return $text;
    }
 
    //$download_link = gform_get_meta( $entry['id'], 'gfmergedoc_download_link' );
    $quote_details_page = get_permalink( 40 );
    $quote_id = $entry[15];
    $view_quote_link = sprintf( '<a href="%s">View Quote details</a>', add_query_arg( 'quote_id', $quote_id, $quote_details_page ) );
    $text = str_replace( $custom_merge_tag, $view_quote_link, $text );
    return $text;
}


add_filter( 'gform_replace_merge_tags', 'replace_download_link', 10, 7 );


// Mark packages as private, so they can't be seen again.
function doolittle_mark_packages_private( $entry, $form ) {
 
    /*
    //getting post
    $post = get_post( $entry['post_id'] );
 
    //changing post content
    $post->post_status = 'private';
 
    //updating post
    wp_update_post( $post );
    */
     
    $quotes = new Doolittle_Quotes;
    
    $quote_id = $quotes->get_post( 'quote' );
      
    $packages = $entry[14];
    if( !empty( $packages ) ) {
        $packages = explode( ',', $packages );
        if( ! empty( $packages ) && is_array( $packages ) ) {
            
            foreach( $packages as $package_id ) {
                $my_post = array(
                      'ID'           => $package_id,
                      'post_status'   => 'private',
                  );
                
                // Update the post into the database
                  wp_update_post( $my_post );
            }
            
            wp_delete_post( $quote_id );
        }
    }
}

add_action( 'gform_after_submission', 'doolittle_mark_packages_private', 10, 2 );


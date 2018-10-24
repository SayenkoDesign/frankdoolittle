<?php

function doolittle_photos( $images ) {
    
    $array = explode( ';', $images );
    
    if( empty( $array ) ) {
        return;
    }
    
    $ret = [];
    
    foreach( $array as $image ) {
        $ret[] = sprintf( 'http://doolittle.wpengine.com/photos/%s', $image );
    }
    
    return implode( ';', $ret );
}

function set_default_price( $post_id ) {
    
    $rows = get_field('product_price', $post_id );
    
    if( empty( $rows ) ) {
        $unit_price = 0;
    } else {
        $last_row = end($rows);
        $unit_price = floatval( $last_row['unit_price'] );
    }
      
    update_post_meta($post_id, '_sort_price', $unit_price );
    
}

add_action('pmxi_saved_post', 'set_default_price', 99, 1);

/*
function set_default_price( $string ) {
	
    $array = explode( ';', $string );
	
	if( empty( $array ) ) {
		return '';
	}
    
    $last = end( $array );
    
    if( empty( $last ) ) {
        return '';
    }
    
    $values = explode( ':', $last );
    
    if( !empty( $values ) && count( $values ) > 1 ) {
        return $values[1];
    }
    
    return '';
}
*/


/*

*/
function parse_repeater_color( $options = '', $find = 'key' ) {
    
    $values = parse_repeater_fields( $options, 'key' );
    
    $colors = explode( ':', $values );	
    
    // Let's create the colors if they do not exist
    
    foreach( $colors as $color ) {
        
    }
    
}



// Grab list of repeats, use $find by (key, attr-1, attr-2, attr-n ....)
function parse_repeater_fields( $options = '', $find = 'key' ) {
	
    // Example: 'Heather Charcoal| S-3XL;Heather Navy| S-6XL ;Blue| S-M|Dark, Light; ';
    // Remove all whitespace from string   
    $options = trim( $options );
    
    // Split into an array
	$options = explode( ';', $options );	
          
    // Remove any empty values
    $options = array_filter( $options );
            
    if( !is_array( $options ) || empty( $options ) ) {
        return;
    }
      
    $attributes = array();
  	
	foreach( $options as $option ) {
                
        $attr = [];
 
        $attr[] = explode( '|', $option );
                
        if( empty( $attr ) ) {
            continue;
        }
                  
        foreach( $attr as $key => $array ) {
              
            if( !empty( $array ) ) {
                
                foreach( $array as $k => $v ) {
                   
                   if( $k ) {
                       $attr['attr-'.$k] = trim( $v ); 
                   }
                   else {
                       $attr['key'] = trim( $v ); 
                   }
                   
                }
                
                unset( $attr[$key] );
                
                $attributes[] = $attr;
            }
            
        }
 	}
    
    $values =  wp_list_pluck( $attributes, $find );
    
    if( !empty( $values ) ) {
        return implode( '|', $values );
    }
}



// Convert a field to a repeater
/*
function parse_repeater_fields( $string = '', $key = 0 ) {
	
    error_log( print_r( $string, 1 ) );
    
    // Example: 'Heather Charcoal| S-3XL;Heather Navy| S-6XL ;Blue| S-M|Dark, Light; ';
 
    $attr = explode( ';', $string );
            
    if( empty( $attr ) ) {
        return false;
    }
    
    
        
    if( isset( $attr[$key] ) ) {
        return $attr[$key];
    }
    
    return false;
}
*/



function _parse_string_to_values( $string, $match = array() ) {
		
  	$values = explode( '|', $string );
	
	if( empty( $values ) ) {
		return false;
	}
	
	// Are we matching values?
 	if( !empty( $match ) ) {
		
  		$temp = array();
 		foreach( $values as $value ) {
			if( array_key_exists( $value, $match ) ) {
				$temp[] = $match[$value];
			}
		}
		
		$values = $temp;
	
	}
 		
	return implode( ',', $values ); 
 }
 
 

function parse_data_attribute( $val ) {
	if( is_array( $val ) ) {
		$t = array();
		foreach( $val as $k => $v ) {
			if( !empty( $v ) ) {
				$t[] = sprintf('%s:%s', $k, $v);
			}
		}
		
		return implode( ',', $t );
	}
	else {
		return $val;	
	}
}


// Fix image image urls
function correct_image_url( $url ) {
	global $wpdb;
	
	$prefix = 'm_';
	$domain = site_url();	
	$images = explode(',', $url);
	
	if( !empty( $images ) ) {
		foreach( $images as $key => $value ) {
			
			//$value = str_replace('files/', 'files/m_', $value );
			//$images[$key] = addhttp( $domain ) . parse_url( addhttp( $value ), PHP_URL_PATH);
			$images[$key] = addhttp( $domain . $value . '.JPG' );
			
			/*
			$test = $images[$key];
			if( @get_headers($test)[0] == 'HTTP/1.1 404 Not Found' ) {
				$table = 'missing_photos';
				$data = array('IMAGE_URL' => $test, 'IMAGE_ID' => $value );
				$wpdb->insert( $table, $data );
			}
			*/
			
		}
		
		return implode(',', $images );
	}
	
	return '';
}
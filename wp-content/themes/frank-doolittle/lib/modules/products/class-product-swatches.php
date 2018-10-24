<?php
class Product_Swatches {
    
    var $product;
    var $color_attribute;
    var $size_attribute = 'pa_size';
    
    public function __construct( $product_id ) {
          
        $product = wc_get_product( $product_id );
        
        if( ! $product ) {
            return false;
        }
        
        if( ! $product->is_in_stock() ) {
            return false;
        }
                  
        $this->product = $product; 
        
         
        
        $this->set_color_attribute();      
 	}
    
    
    private function set_color_attribute() {
        
        $product_attributes = $this->product->get_attributes();
        
        if( empty( $product_attributes ) ) {
            return false;
        } 
                                  
        // Color attribute will be anything else that is not a size
        foreach( $product_attributes as $key => $product_attribute ) {
            if ( $key != $this->size_attribute ) {
                $this->color_attribute = $key;
            }
        }
    }
    
    
    public function get_color_sizes() {
                
        if( empty( $this->product->get_attributes() ) ) {
            return false;
        } 
                      
        if( $this->product->is_type( 'simple' ) ){
            return $this->get_color_sizes_simple();
        }
            
        else if( $this->product->is_type( 'variable' ) ){
           return $this->get_color_sizes_variable();
        }
        else {
            return false;   
        }
         
    }
    
    
    public function get_swatches() {
                        
		$swatches = $this->get_color_sizes();
                        
        if( empty( $swatches ) ) {
            return false;
        }
        
        $out = '';

		foreach( $swatches as $swatch ) {

			$out .= $this->get_swatch( $swatch );
			
		}

		return sprintf( '<div class="colors">%s</div>', $out );
        
    }
    
    
    public function get_custom_swatches( $swatches ) {
                                
        if( empty( $swatches ) ) {
            return false;
        }
        
        $out = '';

		foreach( $swatches as $swatch ) {

			$out .= $this->get_swatch( $swatch );
			
		}

		return sprintf( '<div class="colors">%s</div>', $out );
        
    }
    
   
    
    public function get_swatch( $swatch = array() ) {
        
        if(  empty( $swatch ) || !isset( $swatch['color'] ) ) {
            return false;
        }
        
        $color = $swatch['color'];
        
        if( ! $color instanceof WP_Term ) {
            return false;
        }
                
        $label = $color->name;
        
        $sizes = $swatch['sizes'];
                
        if( !empty( $sizes ) ) {
              
            if( count( $sizes ) > 1 ) {
                $first = array_shift( $sizes );
                $last  = array_pop( $sizes );
                $sizes = sprintf( ' | %s - %s', $first, $last ); 
            }
            else {
               $first = array_shift( $sizes );
               $sizes = sprintf( ' | %s', $first ); 
            }
        }
                 
        $hex = get_field('hex', $color );
        $photo = get_field('photo', $color );
                
        $type = !empty( $photo ) ? 'photo' : 'color';
        
        $style = '';
        
        if ( 'photo' === $type ) {
            $url = wp_get_attachment_image_src( $photo, 'swatch' );
            if( !empty( $url ) ) {
               $style = sprintf( 'style="background-image: url(%s);"', $url[0] ); 
            }
        }
        else {
            if( !empty( $hex ) ) {
                $hex = hex_color( $hex );
                $style = sprintf( 'style="background-color: %s;"', $hex );
            }
            
        }
        
        if( !empty( $style ) ) {
            
            $swatch_large = sprintf( '<div class="swatch-large" %s></div>', $style );
            
            $swatch_thumbnail = sprintf( '<div class="swatch-thumbnail"><div class="background" %s></div></div>', $style );
            
            $swatch = sprintf( '<div class="swatch">%s<p>%s%s</p></div>', $swatch_large, $label, $sizes );
            
            // double quotes to single quotes
            $swatch = dq_to_sq( $swatch );
 
			return sprintf( '<span data-tooltip aria-haspopup="true" data-allow-html="true" class="has-tip top" data-click-open="true" data-disable-hover="false" tabindex="2" title="%s" data-position="top" data-alignment="center">%s</span>', $swatch, $swatch_thumbnail );
        }
    }
        
    
    
    
    public function get_color_sizes_simple() {
        
        $colors = $this->get_product_attributes( $this->color_attribute );
                                
        if( empty( $colors ) ) {
            return false;
        }
        
        $sizes = $this->get_product_attributes( $this->size_attribute );    
        
        if( !empty( $sizes ) ) {
            $sizes = wp_list_pluck( $sizes, 'name' );
        }
        
        foreach( $colors as $slug => $term_object ) {
            $colors[$slug] = array( 'color' => $term_object, 'sizes' => $sizes ); 
        }
                    
        return $colors;

    }
    
    
    
    public function get_color_sizes_variable() {
                 
                         
        // Get available variations
        $variations = $this->product->get_available_variations();
                        
        // Filter variations
        $filtered_variations = [];
        
        foreach ( $variations as $variation ) {
            
            // If the variation is not in stock, skip this variation
            // Just incase we want to worry about stock at some point in the future
            if ( ! $variation['is_in_stock'] && ! $variation['backorders_allowed']  ) {
                continue;
            }
                        
            if( !is_array( $variation['attributes'] ) ) {
                return;
            }
            
            $filtered_variations[] = $variation;
            
        }
        
        $attributes = wp_list_pluck( $filtered_variations, 'attributes' );
                                       
        $options = array();
                
        $primary_key = sprintf( 'attribute_%s', $this->color_attribute );
                        
        $colors = wp_list_pluck( $attributes, $primary_key );
        $colors = array_unique( $colors );
        
        
        foreach( $colors as $color ) {
            $criteria = array( $primary_key => $color );
            $filtered = wp_list_filter( $attributes, $criteria );
            
            $sizes = wp_list_pluck( $filtered, 'attribute_pa_size' );
             
            $term = get_term_by( 'slug', $color, str_replace( 'attribute_', '', $primary_key ) );
            
            if ( !empty( $term ) && !is_wp_error( $term ) ) {
                
                if( !empty( $sizes ) )  {
                    $size_labels = [];
                    foreach( $sizes as $size ) {
                        $size_term = get_term_by( 'slug', $size, 'pa_size' );
                        if ( !empty( $size_term ) && !is_wp_error( $size_term ) ) {
                            $size_labels[$size_term->name] = $size_term->name; 
                        }
                    }
                    
                    $size_labels = array_unique( $size_labels );
                }
                
                $options[$term->slug] = array( 'color' => $term, 'sizes' => $size_labels );
            }
        }
                        
        return $options;  
    }
    
   
     
    private function get_size_attributes() {
        return $this->get_product_attributes( 'size' );
    }
    
    public function get_product_attributes( $key ) {
                
        $attributes = $this->product->get_attributes();

        foreach ( $attributes as $attribute ) {
                                     
            if ( $attribute->is_taxonomy() ) {
                $attribute_taxonomy = $attribute->get_taxonomy_object();
                $attribute_values = wc_get_product_terms( $this->product->get_id(), $attribute->get_name(), array( 'fields' => 'all' ) );
                  
                foreach ( $attribute_values as $attribute_value ) {
                        $value_name = esc_html( $attribute_value->name );
                        $values[$attribute->get_name()][$attribute_value->slug] = $attribute_value;
                }
            }
        }
        
        if( isset( $values[$key ] ) ) {
            return $values[$key ];
        }
    }
    
}
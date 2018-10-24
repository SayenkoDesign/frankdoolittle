<?php

class Package_attributes {
    
    var $package_id = false;
    var $package_data = array();
      
    public function __construct( $package_id = false ) {
         $this->package_id = $package_id;  
         
    }
    
    
    function get_attributes( $post_id ) {
        
        if( ! absint( $post_id ) ) {
            return $this->get_data();
        }
        
        // Needs to be a package
        if( 'doolittle_package' != get_post_type( $post_id ) ) {
            return $this->get_data();
        }
        
        // Needs to exist
        $data = get_post_meta( $post_id, '_package_data', true );
        
        if( empty( $data ) ) {
            return $this->get_data();
        }
        
        $data = maybe_unserialize( $data );
        
        if( !is_array( $data ) || empty( $data ) ) {
            return $this->get_data();
        } 
        
        return $this->get_data( $data );        
         
    }
    
    
    public function get_data( $data = array() ) {
        
        $defaults = array(
            'design' => false,
            'product' => false,
            'total-quantity' => false,
            'sizes' => 'no',
            'post_ids' => array(),
            'attributes' => array() // color, size, qualtity

        );
        
        if( empty( $data ) ) {
            return $defaults;
        }
        
        $data = wp_parse_args( $data, $defaults );
        
        // Add product and design
        
        if( !empty( $data['post_ids'] ) ) {
            foreach( $data['post_ids'] as $post_id ) {
                $item = get_single_product_or_design( $post_id );
                if( !empty( $item ) ) {
                    $data[$item['type']] = $item;
                }
                
                
            }
        }
        
        return $data;
    }
    
 
    
    public function get_confirm_sizes( $checked = 'no' ) {        
                
        $out = sprintf( '<label for="sizes">%s</label>', 'Have you confirmed your sizes?' );
        
        $out .= sprintf( '<label class="inline"><input type="radio" name="sizes" value="yes" %s> <span>yes</span></label>',
               checked( 'yes', $checked, false ) );
               
        $out .= sprintf( '<label class="inline"><input type="radio" name="sizes" value="no" %s> <span>no</span></label>',
               checked( 'no', $checked, false ) );
               
        return   sprintf( '<div class="group confirm-sizes">%s</div>', $out );
    }
    
    
    public function get_product_sizes( $product = array(), $attributes = array(), $show = false ) {
        
        $product_id = isset( $product['post_id'] ) ? $product['post_id'] : false;
                  
        $product_attributes = new Product_Attributes;
        
        // We need an empty row if there is nothing set
        if( empty( $attributes ) ) {
            $attributes = array('');
        }
        
        $rows = '';
        
        foreach( $attributes as $key => $attr ) {
            
            $color_select = $product_attributes->get_color_select( $product_id, $attr );
            $size_select = $product_attributes->get_size_select( $product_id, $attr );
                        
            $selected_quantity = '';
            
            if( isset( $attr['quantity'] ) ) {
                $selected_quantity = $attr['quantity'];
            }
            
            $out  = sprintf( '<li><label>Color</label>%s</li>', $color_select );
            $out .= sprintf( '<li><label>Size</label>%s</li>', $size_select  );
            $out .= sprintf( '<li><label>QTY</label><input type="text" name="attributes[][quantity]" placeholder="" maxlength="4" 
                                    class="quantity" value="%s"></li>', $selected_quantity );
            
            $_hide = ! $key ? ' style="display: none;"' : '';         
            $rows .= sprintf( '<ul>%s<li><span class="remove-attr"%s></span></li></ul>', $out, $_hide );  
        }
        
        $hide = false == $show  ? ' hide' : '';
        return sprintf( '<div class="group product-attributes%s"><div class="attributes">%s</div>
                         <div class="actions"><span class="add-attr"></span></div></div>', $hide, $rows );
        
    }
    
    
    private function confirm_sizes_checked() {
        
        
    }
    
}

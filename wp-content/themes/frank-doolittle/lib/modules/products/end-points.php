<?php

// Add product attributes to product
function doolittle_rest_prepare_product( $data, $post, $request ) {

    $post_id = $post->ID;
	$product = array( 'post_id' => $post_id );
    
    $_data = $data->data;
 	
	$package_attributes = new Package_attributes();
    
    $out = '';
    $out .= $package_attributes->get_confirm_sizes();
    $out .= $package_attributes->get_product_sizes( $product );

    $_data['package_attributes'] = sprintf( '<div class="package-attributes">%s</div>', $out );
	$data->data = $_data;

	return $data;
}
add_filter( 'rest_prepare_product', 'doolittle_rest_prepare_product', 10, 3 );
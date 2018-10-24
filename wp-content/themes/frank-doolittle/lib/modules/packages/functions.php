<?php
function _s_get_pakcgaes_table( $packages ) {
    
    if( empty( $packages ) ) {
        return false;
    }
    
    $post_ids = explode( ',', $packages );
    
    if( !is_array( $post_ids ) ) {
        return false;
    }
    
    $args = array(
        'post_type'         => 'doolittle_package',
        'posts_per_page'    => -1,
        'post_status'       => 'publish',
        'post__in'          => $post_ids,
        'orderby'           => 'post__in',
    );
    
    $out = '';
        
    // Use $loop, a custom variable we made up, so it doesn't overwrite anything
    $loop = new WP_Query( $args );
    
    if ( $loop->have_posts() ) : 
       
           
        while ( $loop->have_posts() ) : $loop->the_post(); 
        
            
                                
        endwhile;
   							
         
    endif;

    wp_reset_postdata();
    
    
}


/*
function _s_get_pakcgaes_table( $packages ) {
    
    if( empty( $packages ) ) {
        return false;
    }
    
    $post_ids = explode( ',', $packages );
    
    if( !is_array( $post_ids ) ) {
        return false;
    }
    
    $args = array(
        'post_type'         => 'doolittle_package',
        'posts_per_page'    => -1,
        'post_status'       => 'publish',
        'post__in'          => $post_ids,
        'orderby'           => 'post__in',
    );

    $table = new CI_Table();
      
    // Use $loop, a custom variable we made up, so it doesn't overwrite anything
    $loop = new WP_Query( $args );
    
    if ( $loop->have_posts() ) : 
       
           
        while ( $loop->have_posts() ) : $loop->the_post(); 
        
            $cell = array();
            $cell[] = array( 'data' => get_the_title() );
            $cell[] = array( 'data' => get_field( 'date' ) );
            
            $url = get_field( 'pdf' );
            if( !empty( $url ) ) {
                $url = sprintf( '<a href="%s">Download</a>', $url );
            }
            $cell[] = array( 'data' => $url );
            
            $table->add_row( $cell );
                                
        endwhile;
        
        
        $template = array(
                    'table_open' => '<table class="footable">'
            );
							
		$table->set_template($template);
        
        $content = $table->generate();
							
		printf( '<div id="footable-wrapper" class="footable-wrapper">%s</div>', $content );
							
         
    endif;

    wp_reset_postdata();
    
    
}
*/


function _s_get_package_data( $post_id ) {
    $package = new Doolittle_Package;
    return $package->get_data( $post_id );
}
 

// Class Helpers
function packages_get_response( $args ) {
    $package = new Doolittle_Package;
    return $package->response( $args );
}

function packages_add() {
    $package = new Doolittle_Package;
    return $package->add();
}

function packages_update( $data = array() ) {
    $package = new Doolittle_Package;
    return $package->update( $data );
}


function packages_delete( $post_ids = array() ) {
    $package = new Doolittle_Package;
    return $package->delete( $post_ids );
}



function modal_item_tmpl() {
    
    echo '<script type="text/template" id="tmpl-modal-item">';

    $data = array(
        'post_id'       => '{{ data.post_id }}',
        'image'         => '{{{ data.image }}}',
        'title'         => '{{{ data.title }}}',
        'description'   => '{{{ data.description }}}',
        'attributes'    => '{{ data.attributes }}',
    );
    
    echo _s_get_item( $data );
    
    echo '</script>';
    
}

add_action('wp_footer', 'modal_item_tmpl' );


function modal_item_favorite_tmpl() {
    
    echo '<script type="text/template" id="tmpl-modal-item-favorite">';

    $data = array(
        'post_id'       => '{{ data.post_id }}',
        'image'         => '{{{ data.image }}}',
        'title'         => '{{{ data.title }}}',
        'description'   => '{{{ data.description }}}',
        
    );
    
    echo _s_get_item( $data, true );
    
    echo '</script>';
    
}

add_action('wp_footer', 'modal_item_favorite_tmpl' );



function single_package_tmpl() {
    ?>
    <script type="text/template" id="tmpl-single-package">
     
    <div class="package" data-package-id="{{ data.package_id }}">
        
        <h3>Package <span class="package-number">{{{ data.package_number }}}</span></h3>
        <form>
        <input type="hidden" name="package-id" value="{{ data.package_id }}">
        <div class="remove-package">Remove<span><b>&times;</b></span></div>
        <div class="row">
        
            <div class="small-12 medium-7 large-7 columns">
        
         
                      <div class="columns medium-6 large-6">
                        <div class="select-design">
                            
                           <div class="place-holder">
                           <h4>Add a Design</h4>
                            <div class="image"><img src="<?php echo trailingslashit(THEME_IMG);?>place-holder.png" />
                            <div class="button add-to-package" data-open="modal-design"><span><?php echo get_svg('plus');?></span><span class="screen-reader-text">Click Here</span></div></div>
                            <p>Okay to leave blank</p>
                           </div>
                           <div class="selected-design hide">
                               <h4>Selected Design</h4>
                           </div>
                        </div>
                     </div>
                    
                     
                     <div class="columns medium-6 large-6">
                
                        <div class="select-product">
                           <div class="place-holder">
                            <h4>Add a product</h4>
                            <div class="image"><img src="<?php echo trailingslashit(THEME_IMG);?>place-holder.png" />
                            <div class="button add-to-package" data-open="modal-product"><span><?php echo get_svg('plus');?></span><span class="screen-reader-text">Click Here</span></div></div>
                            <p>Okay to leave blank</p>
                           </div>
                           <div class="selected-product hide">
                               <h4>Selected product</h4>
                              
                           </div>
                        </div>
                
                     </div>
             
            </div> 
             
             <div class="small-12 medium-5 large-5 columns">
        
                <div class="package-details">
           
                   <div class="group total-quantity">
                   <h4>Total Quantity</h4>
                   <input name="total-quantity" type="text" maxlength="4" class="quantity" value="">
                   </div>
                   
                </div><!-- package details -->
        
             </div>
          </div><!-- row -->
                    
      </form>
      
    </div>
      
    </script>
<?php
    
}

add_action('wp_footer', 'single_package_tmpl' );




function package_product_attributes_tmpl() {
    
    echo '<script type="text/template" id="tmpl-product-attributes">';
      
    $package_attributes = new Package_attributes();
                                      
    echo $package_attributes->get();
    
    echo '</script>';
    
}

//add_action('wp_footer', 'package_product_attributes_tmpl' );
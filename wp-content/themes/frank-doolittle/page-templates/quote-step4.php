<?php
/*
Template Name: Quote Step 4
*/

get_header(); ?>
<div id="primary" class="content-area">

	<main id="main" class="site-main" role="main">

      <section class="section-packages">
         <div class="wrap">
            <div class="row">
            
                <?php
                //$quote_id = '0f201cc558dc74bc39f065e9f33e53e2';
        
                $quote_id = false;
                
                if( isset( $_GET['quote_id'] ) && !empty( $_GET['quote_id'] )  ) {
                    $quote_id = sanitize_text_field( $_GET['quote_id'] );
                }        
                
                $packages = gform_get_doolittle_packages( $quote_id );
                
                
                ?>

            	<div class="columns small-centered large-8">
                  
                      <div class="entry-content">
                          <h1 class="text-center">Thanks for submitting a quote request</h1>
                          <p>You can view the items you requested quotes on below. One of our account specialist will contact you within one business day.</p>
                      </div>
                   
                  <?php
                  if( !empty( $packages ) ) {
                      
                      // arguments, adjust as needed
                        $args = array(
                            'post_type'      => 'doolittle_package',
                            'posts_per_page' => -1,
                            'post_status'    => 'private',
                            'orderby' => 'post__in', 
                            'post__in' => $packages
                            
                         );
                      
                        $loop = new WP_Query( $args );
                        
                        if ( $loop->have_posts() ) : 
                        
                            $package_number = 0;
                        
                            echo '<div class="packages">';
                        
                            while ( $loop->have_posts() ) : $loop->the_post(); 
                                
                                $package_number++;
                             
                                $data = array(
                                    'post_id'       => get_the_ID(),
                                    'image'         => get_the_post_thumbnail( get_the_ID(), 'item-thumbnail' ),
                                    'title'         => get_the_title(),
                                    'description'   => get_field( 'part_number' ),
                                    
                                );
                                
                                $details = _s_get_package_data( get_the_ID() );
                                
                                $design = $details['design'];
                                $product = $details['product'];
                                $attributes = $details['attributes'];
                                ?>
                                <h3>Package <span class="package-number"><?php echo $package_number;?></span></h3>
                                <div class="row package">
    
                                <div class="small-12 medium-7 large-7 columns">
                                     <div class="row">
                                      <div class="columns column-block medium-6 large-6">
                                        <div class="item">
                                           <h4>Design</h4>
                                           <?php
                                           if( empty( $design ) ) {
                                               printf( '<div class="place-holder"><div class="image">
                                               <img src="%splace-holder.png" /></div></div>', trailingslashit(THEME_IMG) );
                                           }
                                           else {
                                               echo _s_get_item( $design, false, true, false );
                                           }
                                           ?>
                                        </div>
                                     </div>
                                         
                                     <div class="columns column-block medium-6  large-6">
                                         <div class="item">
                                           <h4>Product</h4>
                                           <?php
                                           if( empty( $product ) ) {
                                               printf( '<div class="place-holder"><div class="image">
                                               <img src="%splace-holder.png" /></div></div>', trailingslashit(THEME_IMG) );
                                           }
                                           else {
                                               echo _s_get_item( $product, false, true, false );
                                           }
                                           ?>
                                        </div>
                                
                                     </div>
                                     
                                     </div>
                                 
                                </div> 
                                 
                                 <div class="small-12 medium-5 large-5 columns">
                            
                                    <div class="quote-details">
                               
                                       <div class="group total-quantity">
                                       <h4>Total Quantity</h4>
                                       <p><?php echo $details['total-quantity'] ? $details['total-quantity'] : 'NA' ;?></p>
                                       </div>
                                                      
                                       <?php
                                       if( !empty( $attributes ) ) {
                                          
                                          echo '<div class="quote-attributes">';
                                          
                                          foreach( $attributes as $row ) {
                                              $color = sprintf( '<div class="cell"><span>Color</span>%s</div>', $row['color'] );
                                              $size = sprintf( '<div class="cell"><span>Size</span>%s</div>', $row['size'] );
                                              $qty = sprintf( '<div class="cell"><span>QTY</span>%s</div>', $row['quantity'] );
                                              printf( '<div class="attribute">%s%s%s</div>', $color, $size, $qty );
                                          }
                                          
                                          echo '</div>';
                                       }
                                       ?>  
                                           
                                    </div><!-- quote details -->
                                    
                                    <?php
                                    $notes = $details['notes'];
                                   
                                    if( !empty( $notes ) ) {
                                       printf( '<div class="notes">%s</div>', wpautop( $notes ) );
                                    }
                                   ?>      
                            
                                 </div>
                              </div><!-- row -->
                                
                                
                                <?php
                                 
                            endwhile;
                            
                            echo '</div>';
                            
                         endif;
                         wp_reset_postdata();  
                      
                  }
                  ?>
 
			    </div><!-- quote area large 8 -->
 
            </div>
         </div>
      </section><!-- packages -->

   </main>
</div>
<?php
get_footer();

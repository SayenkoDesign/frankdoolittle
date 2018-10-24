<?php
/*
Template Name: Quote Step 1
*/

get_header(); ?>
<div id="primary" class="content-area">

	<main id="main" class="site-main" role="main">
        <div class="column row text-center">
            <h1 class="step">Step 1</h1> 
            <h2>My Quote</h2>
        </div>

      <?php
      get_template_part( 'template-parts/quote', 'how-it-works' );
      ?>

      <section class="section-packages">

         <div class="wrap">
            <div class="row">
               <div class="columns small-centered large-8">
                   
                     <div id="packages" class="packages">
                    
                      <!-- start package -->
    
                      <?php
                        
                      
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
                        
                        $count = 1;

                        if ( $loop->have_posts() ) : 
                            while ( $loop->have_posts() ) : $loop->the_post(); 
                                _s_get_template_part( 'template-parts/quote', 'package', array( 'package' => $loop->post, 'package_number' => $count ) );
                                
                                $count++;
                        
                            endwhile;
                        
                        endif;
                        
                        wp_reset_postdata();
                        
                      ?>
    
                      <!-- end package-->
                        
                    </div>
                    
                    
                    <p></p><a class="add-package">+ add package</a></p>
                    
                    
               </div>
            </div>


            <div class="row columns quote-buttons">
             <a data-open="modal-quote" class="btn-primary btn-inverted view-items">View Items</a>
             <a class="btn-secondary remove-packages">Clear All</a>
             <a href="<?php echo get_permalink( 37 );?>" class="btn-primary next-step">Next Step</a>
            </div>


         </div>
      </section><!-- packages -->

      <?php
      get_template_part( 'template-parts/modal', 'design' );
      get_template_part( 'template-parts/modal', 'product' );
      get_template_part( 'template-parts/modal', 'quote' );
      ?>

   </main>
</div>
<?php
get_footer();

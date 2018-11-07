<?php
/**
 * Archive - Doolittle Product
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package _s
 */

get_header(); ?>

<?php
//get_template_part( 'template-parts/section', 'breadcrumbs' );
?>

<div class="row">

    <div class="medium-4 columns">
        
        <div id="secondary" class="widget-area" role="complementary">
            <?php
                //get_search_form();
                if ( is_active_sidebar( 'product-archive' ) ) :
		            dynamic_sidebar( 'product-archive' ); 
                endif;
            ?>

            <div class="filters product-filters">
                                
            <?php 
                $product = new Product_attributes;
                
                $filters = array( 
                                  // 'category' => 'Products', 
                                  'color' => 'Color',
                                  'view_by' => 'View By', 
                                  'fit' => 'Fit', 
                                  'style' => 'Style', 
                                  'material' => 'Material', 
                                  'weight' => 'Weight', 
                                  'properties' => 'Properties', 
                                  'price' => 'Price', 
                                  'origin' => 'Origin',
                                  'event' => 'Event'
                                   );
                
                // Keep first 4 filters open by default 
                $filter_count = 0;
                foreach( $filters as $filter => $title ) {
                    $facet = facetwp_display( 'facet', sprintf( 'product_filter_%s', $filter ) );
                                                                
                    $filter_count++;
                    
                    $open = $filter_count < 5 ? true : false;
                    
                    $product->add_accordion_item( $title, $facet, $open );
                }
                                                
                echo $product->get_accordion();
                
                $attribute_terms = wc_get_attribute_taxonomy_names();
                
                //var_dump( $attribute_terms );
                
                /*
                $queried_object = get_queried_object();
                
                if( is_product_category() ) {
                    var_dump($queried_object );
                }
                */
            ?>
            
            </div>
        </div>

	</div>

	<div class="medium-8 columns">

		<div id="primary" class="content-area">

			<main id="main" class="site-main" role="main">
				
                
                
                <section class="products">
                <div class="wrap">
                
                    <div class="column row">
                     
                        <header class="page-header">
                            <?php
                            the_archive_title( '<h1 class="archive-title">', '</h1>' );
                            // the_archive_description( '<div class="taxonomy-description">', '</div>' ); ?>
                            
                            <!--<div class="filters grid-filters"><?php //echo facetwp_display( 'sort' ); ?></div>-->
                        </header>
                          
                    </div>
                    
                    
					<?php
					if ( have_posts() ) : ?>
						<div class="row small-up-1 medium-up-2 large-up-3 grid facetwp-template">
                        <?php
						while ( have_posts() ) :

							the_post();
                            
                            get_template_part( 'template-parts/product', 'column' );


						endwhile;
                        ?>
                        </div>         
                        <?php

						//the_posts_navigation();

					else :

						printf( '<div class="column row">%s</div>', 'Nothing found.' );

					endif; ?>   
                    
                              
                    
                </div>
            </section>

			</main>

		</div>

	</div>
 
</div>

<?php
get_footer();

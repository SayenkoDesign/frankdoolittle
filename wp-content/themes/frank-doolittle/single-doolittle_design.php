<?php
get_header(); 
?>

<div id="primary" class="content-area">

	<main id="main" class="site-main" role="main">
        
		<?php
	    get_template_part( 'template-parts/section', 'breadcrumbs' );

        while ( have_posts() ) :

            the_post();
            
            $design = new Design_attributes;
            
            $design_title = $design->get_title();
                        
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            
                <div class="entry-content">            
                
                    <div class="row">
                    
                        <div class="large-7 large-push-5 large-padding-left columns">
                        
                            <?php
                            printf( '<div class="hide-for-large">%s</div>', $design_title );
                            ?>
                        
                            <div class="photos clearfix">
                            
                            <?php
                            // Out put the slider or a thumbnail
                            $photos = $design->get_photos();
            
                            if( !empty( $photos ) ) {
                                printf( '<div class="enlarge"><a data-open="modal-slideshow">%s</a></div>', get_svg( 'search' ) );
                                echo $photos;
                            }
                            
                            // Related Products
                            echo $design->get_related_designs();
                            
                            ?>
                            </div>
                        </div>
                        
                        
                        <div class="large-5 large-pull-7 large-padding-right columns">
                            <?php
                            printf( '<div class="show-for-large">%s</div>', $design_title );
                            
                            echo '<div class="description">';
                            the_content();
                            echo '</div>';
                            
                            // Social share
                            print( '<h4>Share This:</h4>' );
                            echo _s_get_addtoany_share_icons();
                            
                
                            get_template_part( 'template-parts/product', 'buttons' );
                            
                            // Suggested Products
                            echo $design->get_suggested_products();
                            
                            ?>
                        </div>
                    
                    </div>
                
                </div>
                
            </article>    
            <?php
         endwhile; ?>
        

   </main>

</div>

<?php

get_template_part( 'template-parts/modal', 'design-slideshow' );


get_footer();

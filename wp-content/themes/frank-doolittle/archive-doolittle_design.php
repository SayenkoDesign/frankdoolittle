<?php
get_header(); ?>
    <div id="primary" class="content-area">

        <main id="main" class="site-main" role="main">

			<?php
			get_template_part( 'template-parts/design', 'how-it-works' );
			?>
  
            <section class="designs">
                <div class="wrap">
                
                    <div class="row expanded">
                        <div class="small-12 columns">
                    
                            <header class="page-header">
                                <?php
                                the_archive_title( '<h1 class="archive-title">', '</h1>' );
                                the_archive_description( '<div class="taxonomy-description">', '</div>' ); ?>
                            </header>
                
                            <div class="filters design-filters">
                            
                            <?php 
                                echo facetwp_display( 'facet', 'design_filter_organization' );
                                echo facetwp_display( 'facet', 'design_filter_occasion' );
                                echo facetwp_display( 'facet', 'design_filter_decoration' );
                                echo facetwp_display( 'facet', 'design_filter_theme' );
                            ?>
                            
                            </div>
                        
                        </div>
                        
                    </div>
                    
                    <div class="row expanded small-up-1 medium-up-2 large-up-3 grid facetwp-template">
					<?php
                    
                    
					if ( have_posts() ) : ?>
						
                        <?php
						while ( have_posts() ) :

							the_post();
                            
                            get_template_part( 'template-parts/design', 'column' );


						endwhile;

						//the_posts_navigation();

					else :

						get_template_part( 'template-parts/content', 'none' );

					endif; ?>   
                    
                    </div>                   
                    
                </div>
            </section>
        </main>
    </div>
<?php
get_footer();

<?php
/**
 * The template for displaying search results pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package _s
 */

get_header(); ?>

<div class="row column">

	<div id="primary" class="content-area">

		<main id="main" class="site-main" role="main">

		<?php
        $types = array();                 
        if( have_posts() ) {
            while (have_posts()) {
                the_post();
                if ( empty( $types[$post->post_type] ) ) {
                    $counts[$post->post_type] = 0;
                }
                $counts[$post->post_type]++;
            }
            wp_reset_postdata();
            rewind_posts();
        }
                
		if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title"><?php printf( esc_html__( 'Search Results for: %s', '_s' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
			</header>
            
            <?php
            $types = array('product', 'doolittle_design' );
            
            foreach( $types as $type ) {
                                
                if( 'product' == $type ) {
                    
                    if( isset( $counts[$type] ) && $counts[$type] > 0  ) {
                        printf( '<h2>%s</h2>', 'Products' );
                    }
                    echo '<div class="product-search-results"><section class="products"><div class="row small-up-1 medium-up-2 large-up-3 xlarge-up-4 grid">';
                }
                else {
                    
                    if( $counts[$type] > 0  ) {
                        printf( '<h2>%s</h2>', 'Designs' );
                    }
                    
                    echo '<div class="design-search-results"><section class="designs"><div class="row small-up-1 medium-up-2 large-up-3 grid">';
                }
         
                while( have_posts() ){
                    the_post();
                    
                    if( $type == get_post_type() ) {
                        if( 'product' == get_post_type() ) {
                             get_template_part( 'template-parts/product', 'column' );
                        }
                        else if( 'doolittle_design' == get_post_type() ) {
                            get_template_part( 'template-parts/design', 'column' );  
                        }
                    }
                }
                
                echo '</div></div></div>';
                
                rewind_posts();
            }
            
			//the_posts_navigation();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>

		</main>

	</div>

</div>

<?php
get_footer();

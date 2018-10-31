<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package _s
 */

get_header(); ?>

<div id="primary" class="content-area">

	<main id="main" class="site-main" role="main">
	
	<section class="three-boxes">
		<div class="wrap">
			<div class="row">
				<div class="columns large-7">
					
                        
                         <?php
                         $promotions = get_field( 'promotions' );
                         
                         $slides = $promotions['slides'];
                         $ads    = $promotions['ads'];
                         
                         if( !empty( $slides ) ) {
                             
                             $out = '';
                             
                             foreach( $slides as $slide ) {
                                 
                                 $background = _s_get_acf_image( $slide['photo'], 'large', true );
                                 
                                 $background = sprintf( ' style="background-image: url(%s);"', $background );
                                                                  
                                 $button = $slide['button'];
                                                                  
                                 $white_button = $slide['white_button'];
                                 if( !empty( $white_button ) ) {
                                     $white_button = ' btn-white';
                                 }
                                 
                                 $classes = sprintf( 'btn-primary btn-inverted%s', $white_button );
                                                 
                                 $btn = pb_get_cta_button( $button, array( 'class' => $classes ) );
                                                                  
                                 $url = pb_get_cta_url( $button );
                                                                  
                                 if( empty( $button['text'] ) && !empty( $url ) ) {
                                     $btn = sprintf( '<a href="%s" class="cover"></a>', $url );
                                 }
                                 
                                 $out .= sprintf( '<div class="promotion"%s>%s</div>', $background, $btn );
                                 
                             }
                              
                             printf( '<div class="promotion-slider">%s</div>', $out );
                         }
                         ?>
				</div>
				<div class="columns large-5">
                
                    <?php
                    if( !empty( $ads ) ) {
                             
                             $out = '';
                             
                             foreach( $ads as $ad ) {
                                 
                                 $background = _s_get_acf_image( $ad['photo'], 'large', true );
                                 
                                 $background = sprintf( ' style="background-image: url(%s);"', $background );
                                                                  
                                 $button = $ad['button'];
                                                                  
                                 $white_button = $ad['white_button'];
                                 if( !empty( $white_button ) ) {
                                     $white_button = ' btn-white';
                                 }
                                 
                                 $classes = sprintf( 'btn-primary btn-inverted%s', $white_button );
                
                                 $btn = pb_get_cta_button( $button, array( 'class' => $classes ) );
                                 
                                 $url = pb_get_cta_url( $button );
                                 
                                 
                                 if( empty( $button['text'] ) && !empty( $url ) ) {
                                     $btn = sprintf( '<a href="%s" class="cover"></a>', $url );
                                 }
                                 
                                 $out .= sprintf( '<div class="columns column-block small-12 medium-6 large-12">
                                                  <div class="promotion-box"%s>%s</div></div>', 
                                                  $background, $btn );
                             }
                              
                             printf( '<div class="row">%s</div>', $out );
                         }
                    ?>

				</div>
			</div>
		</div><!-- wrap -->
	</section>
    
    <?php
    // top Selling Products
    top_selling_products();
    function top_selling_products() {
        global $post;
        
        $rows    = get_field('top_selling_products' );
        $per_row = get_field('top_selling_products_per_row' );
        $per_row = $per_row ? $per_row : 7;
        
                
        if( empty( $rows ) ) {
            return;
        }
        
        $out = '';
                  
        foreach( $rows as $row ) {
            
            $icon = $row['image'];
            if( empty( $icon ) ) {
                continue;
            }
                        
            $icon = _s_get_acf_image( $icon, 'thumbnail' );
            
            $title = _s_get_heading( $row['title'], 'h3' );
            
            $column = $icon . $title;
             
            if( !empty( $row['category'] ) ) {
                $url = get_term_link( $row['category'] );
                if( ! is_wp_error( $url ) ) {
                    $column = sprintf( '<a href="%s">%s</a>', $url, $column );
                }
                
            }
            
            $out .= sprintf( '<div class="columns column-block">%s</div>', $column );
        }
        
        if( empty( $out ) ) {
            return false;
        }
        
        $attr = array( 'id' => 'top-selling-products', 'class' => 'section top-selling-products text-center' );        
          
        _s_section_open( $attr );		
        
        echo '<div class="column row"><h2 class="text-center">Top Selling Products</h2>
                </div>';
        
        printf( '<div class="row small-up-2 medium-up-3 large-up-%s">%s</div>', $per_row, $out );
        
        _s_section_close();	
    }
    
    get_template_part( 'template-parts/home', 'featured-designs' );
    ?>
    
	</main>

</div>
<?php
get_footer();

<?php
get_header(); 
?>

<div id="primary" class="content-area">

	<main id="main" class="site-main" role="main">
        
		<?php
			get_template_part( 'template-parts/section', 'breadcrumbs' );
		?>
        
        <?php
        while ( have_posts() ) :

            the_post();
                         
            $product_attributes = new Product_attributes;
            
            $product_title = $product_attributes->get_title();
                                                
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            
                <div class="entry-content">            
                
                    <div class="row">
                        
                        <div class="large-7 large-push-5 large-padding-left columns">
                                    
                                        <?php
                                        printf( '<div class="hide-for-large">%s</div>', $product_title );
                                        ?>
                                    
                                        <div class="photos clearfix">
                                        
                                        <?php
                                        // Out put the slider or a thumbnail
                                        $photos = $product_attributes->get_photos();
                        
                                        if( !empty( $photos ) ) {
                                            printf( '<div class="enlarge"><a data-open="modal-slideshow" data-current-slide="0">%s</a></div>', get_svg( 'search' ) );
                                            echo $photos;
                                        }
                                        
                                        // Related Products
                                        echo $product_attributes->get_related_products();
                                        
                                        ?>
                                        </div>
                                    </div>
                        
                        
                        <div class="large-5 large-pull-7 large-padding-right columns">
                            <?php
                            printf( '<div class="show-for-large">%s</div>', $product_title );
                            
                            $product_attributes->add_accordion_item( 'Product Details', apply_filters( 'the_content', get_the_content() ), true );
                            
                            $size = get_field( 'size' );
                            if( !empty( $size ) ) {
                                $product_attributes->add_accordion_item( 'Size', $size, false );
                            }
                            
                            $product_attributes->add_accordion_item( 'Pricing', $product_attributes->get_pricing(), true );
                            
                            // Imprint/Imprint Details
                            $product_attributes->add_accordion_item( 'Imprint', $product_attributes->get_imprint() );
                            
                            // Imprint/Imprint Details
                            $product_attributes->add_accordion_item( 'Setup', $product_attributes->get_setup() );
                            
                            // Any additional accordions?
                            $product_attributes->add_additional_accordion_items();
                            
                            // Output all accordions
                            echo $product_attributes->get_accordion();
                                        
                            $available_colors = $product_attributes->get_color_swatches();
                            
                            if( !empty( $available_colors ) ) {
                                $available_colors_label = get_field( 'available_colors_label' );
                                $available_colors_label = !empty( $available_colors_label ) ? $available_colors_label : 'Available Colors';
                                echo $product_attributes->get_attribute( $available_colors_label, $available_colors );
                            }
                            
                            // Output additional colors as needed?
                            
                            $second_color_heading = get_field( 'second_color_heading' );
                            $second_colors = get_field( 'second_colors' );
                            
                            if( !empty( $second_color_heading ) && !empty( $second_colors ) ) {
                                
                                $second_colors = $product_attributes->get_additional_colors( $second_colors );
                                
                                echo $product_attributes->get_attribute( $second_color_heading, $second_colors );
                                
                            }
                            
                            
                            $third_color_heading = get_field( 'third_color_heading' );
                            $third_colors = get_field( 'third_colors' );
                            
                            if( !empty( $third_color_heading ) && !empty( $third_colors ) ) {
                                
                                $third_colors = $product_attributes->get_additional_colors( $third_colors );
                                
                                echo $product_attributes->get_attribute( $third_color_heading, $third_colors );
                                
                            }
                                                        
                
                            get_template_part( 'template-parts/product', 'buttons' );
                        
                            ?>
                        </div>
                    </div>
                    
                </div>
                
            </article>
                
            <?php
         endwhile; ?>
        
         
        <div class="column row">
        <?php
            // Related Designs
            echo $product_attributes->get_related_designs();
            
            // Get all the tags
            
            
        ?>
        </div>

   </main>

</div>

<?php

get_template_part( 'template-parts/modal', 'product-slideshow' );


get_footer();

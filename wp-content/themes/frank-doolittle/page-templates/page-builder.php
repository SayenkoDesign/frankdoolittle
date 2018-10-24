<?php
/*
Template Name: Page Builder
*/


/**
 * Custom Body Class
 *
 * @param array $classes
 * @return array
 */
function kr_body_class( $classes ) {
  $classes[] = 'page-builder';
  return $classes;
}
add_filter( 'body_class', 'kr_body_class' );

get_header(); ?>

<?php
// Hero
get_template_part( 'template-parts/section', 'hero' );
?>

<div id="primary" class="content-area">

    <?php
    $page_title = get_field( 'page_title' );
    
    if( !empty( $page_title ) ) {
        $page_title = _s_get_heading( $page_title, 'h1' );
        printf( '<div class="column row">%s</div>', $page_title );
    }
		
    ?>

	<main id="main" class="site-main" role="main">
     
	<?php
 	page_builder();
	function page_builder() {
	
		global $post;
  		
		if ( have_rows('sections') ) {
		
			while ( have_rows('sections') ) { 
			
				the_row();
			
				$row_layout = get_row_layout();
				
 				if( function_exists( "section_{$row_layout}" ) ) {
					call_user_func( "section_{$row_layout}" );
 				}
  					
			} // endwhile have_rows('sections')
			
 		
		} // endif have_rows('sections')
	
	
	}
		
		
	
	
	// Default
	section_content_block();
	function section_content_block() {
				
		static $_section_counter;
		$_section_counter++;
        
        global $post;
		
		// fields
		$editor =  get_sub_field('editor');
		
		if( empty( $editor ) ) {
			return false;
		}
		
         
        $sidebar = '';
		
		// is there a photo
		$photo =  get_sub_field('photo');
         
        $size = 'medium';
		
		if( !empty( $photo ) ) {
			$attachment_id = $photo['ID'];
			$photo = wp_get_attachment_image($photo, $size, '', array( 'class' => 'wp-post-image' ) );
            $sidebar = $photo;
		}
  		
 		$attr = array( 'class' => sprintf( 'section content-block content-block-%s', $_section_counter ) );
		_s_section_open( $attr );		
			print( '<div class="column row">' );
		
				if( !empty( $sidebar ) ) {
					echo $sidebar;
				}
                
                $button = get_sub_field( 'button' );
              
                if( !empty( $button ) ) {
                    $button = sprintf( '<p>%s</p>', pb_get_cta_button( $button, array( 'class' => 'btn-primary' ) ) );
                }
			
                printf( '<div class="entry-content">%s%s</div>', $editor, $button );	
		
			echo '</div>';
		_s_section_close();	
	}
	?>
	</main>


</div>

<?php
get_footer();

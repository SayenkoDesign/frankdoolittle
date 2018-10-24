<?php
/*
Template Name: Contact
*/

get_header(); ?>

<?php
// Hero
get_template_part( 'template-parts/section', 'hero' );
?>

<div id="primary" class="content-area">

	<main id="main" class="site-main" role="main">
	<?php
 	// Default
	section_default();
	function section_default() {
				
		global $post;
		
		$attr = array( 'class' => 'section default' );
		
		_s_section_open( $attr );		
		
		print( '<div class="row">' );
		
			echo '<div class="small-12 large-4 columns">';
			// lib/functions/theme.php
			$footer 	= get_field( 'footer', 'option' );
			printf( '<h3>%s</h3>%s', __( 'Veritas Legal Services', '_s' ), $footer );
			$phone_number = get_field( 'phone_number', 'option' );
            if( !empty( $phone_number ) ) {
                printf( '<p><a href="tel:%1$s" class="phone-number">%1$s</a></p>', $phone_number );
            }
			echo '</div>';
			
			echo '<div class="small-12 large-7 columns">';
			while ( have_posts() ) :
	
				the_post();
                
                echo '<div class="entry-content">';
				
				the_content();
                
                echo '</div>';
					
			endwhile;
			echo '</div>';
		
		print( '</div>' );
		_s_section_close();	
	}
	?>
	</main>


</div>

<?php
get_footer();

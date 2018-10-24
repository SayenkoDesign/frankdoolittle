<?php
/*
Template Name: Blank
*/

get_header(); ?>

<div id="primary" class="content-area">

	<main id="main" class="site-main" role="main">
	<?php
 	// Default
	section_default();
	function section_default() {
				
		global $post;
		
		$attr = array( 'class' => 'section default' );
		
		_s_section_open( $attr );		
		
		print( '<div class="column row">' );
			
			while ( have_posts() ) :
	
				the_post();
                
                the_field( 'rich_editor' );
					
			endwhile;
		
		print( '</div>' );
		_s_section_close();	
	}
	?>
	</main>


</div>

<?php
get_footer();

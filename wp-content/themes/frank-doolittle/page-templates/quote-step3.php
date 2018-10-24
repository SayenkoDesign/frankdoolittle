<?php
/*
Template Name: Quote Step 3
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
		
		print( '<div class="row"><div class="large-8 large-centered columns">' );
        
        print( '<div class="entry-content text-center"><h1 class="step">Step 3</h1> 
                  <h2>Customer Details</h2></div>' );
          
		
		while ( have_posts() ) :

			the_post();
			
			the_content();
				
		endwhile;
		
		print( '</div></div>' );
		_s_section_close();	
	}
    
	?>

	</main>


</div>

<?php
get_footer();

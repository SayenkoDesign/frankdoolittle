<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package _s
 */

get_header(); ?>

<?php
// Hero


    
    
section_hero();
function section_hero() {
    global $post;
    
    $prefix = 'hero_404';
    $prefix = set_field_prefix( $prefix );

    $heading = get_field( sprintf( '%stitle', $prefix ), 'option' );
    $description	= get_field( sprintf( '%sdescription', $prefix ), 'option' );
    
    $background_image = get_field( sprintf( '%sbackground_image', $prefix ), 'option' );

    $style = '';
    $content = '';
    $photo_source = '';
    
    if( !empty( $background_image ) ) {
        $attachment_id = $background_image;
        $size = 'hero';
        $background = wp_get_attachment_image_src( $attachment_id, $size );
        $style = sprintf( 'background-image: url(%s);', $background[0] );
        
        $photo_source = get_post_field( 'post_content', $attachment_id );
          
        if( !empty( $photo_source ) ) {
            $photo_source = sprintf( '<div class="photo-source">%s</div>', $photo_source );
        }
    }
    
            
    if( !empty( $heading ) ) {
        $content .= sprintf( '<h1>%s</h1>', $heading );
    }
    
    if( !empty( $description ) ) {
        $description = _s_wrap_text( $description, "\n" );
        $content .= sprintf( '<h3>%s</h3>', $description );
    }


    $args = array(
        'html5'   => '<section %s>',
        'context' => 'section',
        'attr' => array( 'id' => 'hero', 'class' => 'section hero', 'style' => $style ),
        'echo' => false
    );
    
    $out = _s_markup( $args );
    $out .= '<div class="flex">';
    $out .= _s_structural_wrap( 'open', false );
    
    
    $out .= sprintf( '<div class="row"><div class="small-12 columns">%s%s</div></div>', $content, $photo_source );
    
    $out .= _s_structural_wrap( 'close', false );
    $out .= '</div>';
    $out .= '</section>';
    
    echo $out;
        
}

?>

<div id="primary" class="content-area">

	<main id="main" class="site-main" role="main">
	
		<section class="section default">
			<div class="column row">
	
				<div class="entry-content">
					<p><?php echo get_field( 'content_404', 'option' ); ?></p>
				</div><!-- .page-content -->
				
				</div>
		</section>

	</main><!-- #main -->

</div><!-- #primary -->

	

<?php
get_footer();

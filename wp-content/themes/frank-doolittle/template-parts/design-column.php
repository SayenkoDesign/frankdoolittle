<?php
/**
 * Template part for displaying archive design column.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package _s
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'column column-block' ); ?>>

	<?php
    $url = get_the_post_thumbnail_url( get_the_ID(), 'large' );
    $style = sprintf( 'style="background-image: url(%s);"', $url );
    printf( '<a href="%s" class="image" %s></a>', get_permalink(), $style );
    ?>
    
	<a href="<?php echo get_permalink();?>" class="entry-header">
		<?php the_title( '<h4 class="entry-title">', '</h4>' ); ?>

		<div class="entry-meta">
			<?php echo get_field( 'part_number' ); ?>
		</div><!-- .entry-meta -->
	</a><!-- .entry-header -->
</article><!-- #post-## -->

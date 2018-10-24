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
    $image = get_the_post_thumbnail( get_the_ID(), 'large' );
    printf( '<a href="%s" class="image"><div>%s</div></a>', get_permalink(), $image );
    ?>
    
	<a href="<?php echo get_permalink();?>" class="entry-header">
		<?php the_title( '<h4 class="entry-title">', '</h4>' ); ?>

		<div class="entry-meta">
			<?php 
            $part_number = get_field( 'part_number' ); 
            //echo _s_get_textarea( $part_number, array( 'class' => 'part-number' ) );
            ?>
            <?php
            // Show last price, which is the amount for the largest quantity order
            $prices = get_field( 'product_price' );

            $unit_price = '';
    
            if ( ! empty( $prices ) ) {
                $last = array_pop( $prices );
    
                if ( ! empty( $last['unit_price'] ) ) {
                    echo _s_get_textarea( format_money( $last['unit_price'] ), array( 'class' => 'price' ) );
                }
            }
            ?>
		</div><!-- .entry-meta -->
	</a><!-- .entry-header -->
</article><!-- #post-## -->

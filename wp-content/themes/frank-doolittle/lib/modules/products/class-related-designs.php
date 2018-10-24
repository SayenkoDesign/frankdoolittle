<?php

class Related_Designs {


	public function __construct() {
        
	}


	public function _get_related_design_ids() {

		$post_ids = array();

		$default_args = array(
			'post_type'      => 'doolittle_design',
			'posts_per_page' => 10,
			'orderby'        => 'RAND',
			'post_status'    => 'publish',
			'no_found_rows'  => true,
			'fields'         => 'ids'
		);

		$tags = get_field( 'design_tags' );

		if ( empty( $tags ) ) {
			return false;
		}

		foreach ( $tags as $tag ) {

			if ( empty( $tag ) ) {
				continue;
			}

			$args = $default_args;

			$tax_query = array( 'relation' => 'AND' );

			foreach ( $tag as $key => $term ) {

				if ( empty( $term ) ) {
					continue;
				}

				$tax         = sprintf( 'doolittle_%s_tag', $key );
				$tax_query[] = array(
					'taxonomy'         => $tax,
					'terms'            => [ $term ],
					'field'            => 'term_taxonomy_id',
					'operator'         => 'IN',
					'include_children' => false,
				);

			}
            
            
            // Are we on a specific category
            
            $tax_query[] = array(
					'taxonomy'         => $tax,
					'terms'            => [ $term ],
					'field'            => 'term_taxonomy_id',
					'operator'         => 'IN',
					'include_children' => false,
				);
            

			$args['tax_query'] = $tax_query;

			$loop = new WP_Query( $args );

			$post_ids[] = $loop->posts;

			wp_reset_postdata();

		}

		$post_ids = array_reduce( $post_ids, 'array_merge', array() );

		$post_ids = array_unique( $post_ids );

		return $post_ids;

	}


	public function get_related_designs() {

		$post_ids = $this->_get_related_design_ids();

		if ( empty( $post_ids ) ) {
			return false;
		}

		$out = '';

		$args = array(
			'post_type'      => 'doolittle_design',
			'post__in'       => $post_ids,
			'orderby'        => 'RAND',
			'post_status'    => 'publish',
			'posts_per_page' => 20
		);

		// Use $loop, a custom variable we made up, so it doesn't overwrite anything
		$loop = new WP_Query( $args );

		$column_class = ' class="slide"';
		$slider_class = ' slider';

		if ( $loop->have_posts() ) :


			while ( $loop->have_posts() ) : $loop->the_post();

				$out .= sprintf( '%s', $this->related_design() );

			endwhile;

		endif;

		wp_reset_postdata();

		if ( empty( $out ) ) {
			return false;
		}

		return sprintf( '<div class="related-designs">
                       <h2>Related Designs</h2>
                       <div class="related-designs-slider%s">%s</div></div>', $slider_class, $out );
	}


	public function related_design() {

		global $post;

		$part_number = get_field( 'part_number' );

		if ( ! empty( $part_number ) ) {
			$part_number = sprintf( '<p class="part-number">%s</p>', $part_number );
		}

		$title = sprintf( '<h3><a href="%s">%s</a></h3>', get_permalink(), get_the_title() );

		$background = get_the_post_thumbnail_url( $post, 'large' );

		if ( ! empty( $background ) ) {
			$background = sprintf( ' style="background-image: url(%s);"', $background );
		}

		$cat      = '';
		$taxonomy = 'doolittle_design_cat';
		$terms    = wp_get_post_terms( get_the_ID(), $taxonomy );

		if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
			$term = array_pop( $terms );
			$cat  = sprintf( '<p class="post-term"><a href="%s" class="term-link">%s</a></p>', get_term_link( $term->slug, $taxonomy ), $term->name );
		}

		$details = sprintf( '<div class="hover">%s%s%s</div>', $part_number, $title, $cat );

		return sprintf( '<div class="slide"><div class="background" %s>%s</div></div>', $background, $details );
	}

}
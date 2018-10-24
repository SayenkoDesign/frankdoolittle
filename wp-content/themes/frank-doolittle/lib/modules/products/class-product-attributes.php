<?php

class Product_Attributes {

	var $product_id;
    var $accordion = array();

	public function __construct( $product_id = null ) {
        $this->product_id = $product_id;
	}


	public function get_title() {

		$title = the_title( '<h2>', '</h2>', false );

		$part_number = $this->get_part_number();

		$icon = $this->get_product_composition_icon();

		return sprintf( '<header class="entry-title">%s%s%s</header>', $title, $part_number, $icon );

	}


	public function get_part_number() {

		$part_number = get_field( 'part_number' );

		if ( ! empty( $part_number ) ) {
			return sprintf( '<p class="part-number">%s<p>', $part_number );
		}
	}


	private function get_product_composition_icon() {
        
        global $post;
        
		$terms = wp_get_post_terms( $post->ID, 'product_composition' );
        
        if( empty( $terms ) ) {
            return false;
        }
        
        $term = $terms[0];
        
        $field = get_field( 'logo', $term );
        
        if( ! empty( $field ) ) {
            return wp_get_attachment_image( $field, 'thumbnail', false, array( 'class' => 'brand-logo' ) );
        }
		
	}


	public function get_pricing() {

		$rows = get_field( 'product_price' );

		if ( empty( $rows ) ) {
			return false;
		}

		$slides = '';

		foreach ( $rows as $row ) {

			$qty   = $row['quantity'];
			$price = format_money( $row['unit_price'] );

			$slides .= sprintf( '<div><p class="qty">%s</p><p class="price">%s</p></div>', $qty, $price );

		}

		$price_notes = get_field( 'price_notes' );
		if ( ! empty( $price_notes ) ) {
			$price_notes = sprintf( '<div class="price-notes">%s</div>', _s_get_textarea( $price_notes ) );
		}


		return sprintf( '<div class="product-pricing clearfix"><div class="legend">
											 <p class="qty">quantity:</p>
											 <p class="price">unit price:</p>
										 </div><div class="price-slider slider">%s</div></div>%s', $slides, $price_notes );

	}


	public function get_imprint() {

		$imprint = get_field( 'imprint' );
		$imprint = _s_get_textarea( $imprint );

		$rows = get_field( 'imprint_details' );

		$out = '';

		if ( ! empty( $rows ) ) {

			foreach ( $rows as $row ) {
				$out .= sprintf( '<li><strong>%s:</strong> %s</li>', strtoupper( $row['feature'] ), $row['description'] );
			}

			$out = sprintf( '<ul class="features">%s</ul>', $out );
		}


		return $imprint . $out;
	}
    
    
    public function get_setup() {
        
        $setup_fee = get_field( 'setup_fee' );
        if( !empty( $setup_fee ) ) {
            $setup_fee = sprintf( '<p><strong>%s</strong></p>', $setup_fee );
        }
                
		$setup_notes = get_field( 'setup_notes' );
		$setup_notes = _s_get_textarea( $setup_notes );
        
        if( empty( $setup_fee ) && empty( $setup_notes ) ) {
            return;
        }


		return $setup_fee . $setup_notes;
	}
    

	public function get_color_select( $post_id = false, $attr = array() ) {

		if ( ! absint( $post_id ) ) {
			return false;
		}

		$options = $this->get_color_select_options( $post_id );

		if ( empty( $options ) ) {
			$options = array( '' => 'Color' );
		}

		$selected_color = '';

		if ( isset( $attr['color'] ) ) {
			$selected_color = $attr['color'];
		}

		return form_color_dropdown( 'attributes[][color]', $options, $selected_color, array( 'class' => 'select-color' ) );
	}


	public function get_color_select_options( $post_id ) {

		$options = array( '' => 'Color' );
        
        $swatches = new Product_Swatches( $post_id );
        
        $attributes = $swatches->get_color_sizes();
               
        if( empty( $attributes ) ) {
            $attributes = array();
        }
                
        foreach( $attributes as $attribute ) {
            
            $color = $attribute['color'];
            $label      = $color->name;
            
            $sizes      = $attribute['sizes'];
            if( empty( $sizes ) ) {
                $sizes = array();
            }
            $sizes      = array_combine( $sizes, $sizes );
            $sizes      = array( '' => 'Size' ) + $sizes;
            
            $data_sizes = htmlspecialchars( json_encode( $sizes ), ENT_QUOTES, 'UTF-8' );

			$options[ $label ] = array( 'option' => $label, 'sizes' => $sizes, 'data-sizes' => $data_sizes );
		}

		return $options;

	}


	public function get_size_select( $post_id, $attr = array() ) {

		if ( ! absint( $post_id ) ) {
			return false;
		}

		$size_options = array( '' => 'Size' );

		$selected_color = $selected_size = '';

		if ( isset( $attr['color'] ) ) {
			$selected_color = $attr['color'];
		}

		$product_attributes = new Product_Attributes;
		$color_options      = $product_attributes->get_color_select_options( $post_id );

		// if there is a size, make sure there is a color
		if ( ! empty( $selected_color ) && isset( $attr['size'] ) ) {
			$selected_size = $attr['size'];
			if ( isset( $color_options[ $selected_color ]['sizes'] ) ) {
				$size_options = $color_options[ $selected_color ]['sizes'];
			}
		}

		if ( empty( $size_options ) ) {
			$size_options = array( '' => 'Size' );
		}

		return form_color_dropdown( 'attributes[][size]', $size_options, $selected_size, array( 'class' => 'select-size' ) );
	}


	public function get_color_swatches() {
        
        global $post;
        
		$swatches = new Product_Swatches( $post->ID );
                
        return $swatches->get_swatches();
 
	}
    
    
    public function get_additional_colors( $field ) {
                 
        global $product;
        
        $post_id = $product->get_id();
        
        if( empty( $field ) ) {
            return false;
        }
        
        $rows = explode( '|', $field );
        
        if( empty( $rows ) ) {
            return false;
        }
        
        $swatches = new Product_Swatches( $post_id );
        
        $taxonomy =  $swatches->color_attribute;
        
        $out = '';
        
        foreach( $rows as $row ) {
            
            $term = get_term_by( 'name', $row, $taxonomy );
            
            if( false === $term || is_wp_error($term ) ) {
                continue;
            }
            
            
            
            $color   = $term->name; // $row['color'];
            $hex     = get_field( 'hex', $taxonomy . '_' . $term->term_id ); //$row['hex'];
            $photo   = get_field( 'photo', $taxonomy . '_' . $term->term_id ); //$row['photo'];
                        
            $type = !empty( $photo ) ? 'photo' : 'color';
        
            $style = '';
            
            if ( 'photo' === $type ) {
                $url = wp_get_attachment_image_src( $photo, 'swatch' );
                if( !empty( $url ) ) {
                   $style = sprintf( 'style="background-image: url(%s);"', $url[0] ); 
                }
            }
            else {
                if( !empty( $hex ) ) {
                    $hex = hex_color( $hex );
                    $style = sprintf( 'style="background-color: %s;"', $hex );
                }
                
            }
            
            if( !empty( $style ) ) {
                
                $swatch_large = sprintf( '<div class="swatch-large" %s></div>', $style );
                
                $swatch_thumbnail = sprintf( '<div class="swatch-thumbnail"><div class="background" %s></div></div>', $style );
                
                $swatch = sprintf( '<div class="swatch">%s<p>%s</p></div>', $swatch_large, $color );
                
                // double quotes to single quotes
                $swatch = dq_to_sq( $swatch );
     
                $out .= sprintf( '<span data-tooltip aria-haspopup="true" data-allow-html="true" class="has-tip top" data-click-open="true" data-disable-hover="false" tabindex="2" title="%s" data-position="top" data-alignment="center">%s</span>', $swatch, $swatch_thumbnail );
            }
                             
        }
        
        return sprintf( '<div class="colors">%s</div>', $out );
                                 
    }
    
    
    public function add_additional_accordion_items() {
        
        
        $rows = get_field( 'additional_attributes' );
        
        if( empty( $rows ) ) {
            return false;
        }
        
        $out = '';
          
        foreach( $rows as $row ) {
            
            $heading    = $row['heading'];
            $content    = $row['content'];
              
            if( empty( $heading ) || empty( $content ) ) {
                continue;
            }
             
            $this->add_accordion_item( $heading, $content );
             
        }
                                 
    }
  

	public function get_attribute( $title = '', $content = '' ) {

		if ( empty( $title ) && empty( $content ) ) {
			return;
		}

		return sprintf( '<div class="attributes-item"><div class="attributes-title"><h4>%s</h4></div>
                        <div class="attributes-content">%s</div></div>', $title, $content );
	}


	public function add_accordion_item( $title = '', $content = '', $active = false ) {
		$this->accordion[] = array( 'title' => $title, 'content' => $content, 'active' => $active );
	}


	public function get_accordion() {

		$accordion_content = '';

		$rows = $this->accordion;

		if ( empty( $rows ) ) {
			return false;
		}

		foreach ( $rows as $row ) {

			$title   = $row['title'];
			$content = $row['content'];
			$active  = $row['active'];

			if ( ! empty( $title ) && ! empty( $content ) ) {
				$accordion_title   = sprintf( '<a href="#" class="accordion-title"><h4>%s</h4></a>', $title );
				$is_active         = ( true == $active ) ? ' is-active' : '';
				$accordion_content .= sprintf( '<li class="accordion-item%s" data-accordion-item>%s
                <div class="accordion-content" data-tab-content>%s</div></li>', $is_active, $accordion_title, $content );
			}
		}

		return sprintf( '<ul class="accordion" data-accordion data-multi-expand="true" data-allow-all-closed="true">%s</ul>',
			$accordion_content );

	}

    /*
	public function get_related_products() {
		
        // Get related products
        
        // Query products where product tags == related products
        
        global $post;
        
        $search_terms = wp_get_post_terms( $post->ID, 'product_related' );
        $search_terms = wp_list_pluck( $search_terms, 'slug');        
        
        $args = array(
			'post_type'      => 'product',
			'posts_per_page' => 20,
            'posts_per_page' => 20,
            'orderby'        => 'rand',
			'post_status'    => 'publish',
            'post__not_in' => array( $post->ID )
		);
        
        if( !empty( $search_terms ) ) {
            $tax_query[] = array(
                'taxonomy'         => 'product_related_tags',
                'terms'            =>  $search_terms,
                'field'            => 'slug',   
                'operator'         => 'IN',
                'include_children' => false,
            );
            
            $args['tax_query'] = $tax_query;
        }
        
		$out = '';

		// Use $loop, a custom variable we made up, so it doesn't overwrite anything
		$loop = new WP_Query( $args );
        
		if ( $loop->have_posts() ) :

			while ( $loop->have_posts() ) : $loop->the_post();

				$out .= $this->related_product();

			endwhile;

		endif;

		wp_reset_postdata();
        
        // Individual related products?
        
        $related_products = get_field( 'related_products' );
        
		if ( ! empty( $related_products ) ) {
                        
            $args = array(
                'post_type'      => 'product',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                'post__in' => $related_products
            );
            
            // Use $loop, a custom variable we made up, so it doesn't overwrite anything
            $loop = new WP_Query( $args );
            
            if ( $loop->have_posts() ) :
    
                while ( $loop->have_posts() ) : $loop->the_post();
                    
                    $out .= $this->related_product();
                        
                endwhile;
    
            endif;
    
            wp_reset_postdata();
		
		}
                

		if ( empty( $out ) ) {
			return false;
		}

		return sprintf( '<div class="related-products">
                       <h4>Related Products</h4>
                       <div class="related-products-slider slider">%s</div></div>', $out );
	}
    */
    
    /*
    foreach of the related
    */
    
    public function get_related_products() {
		
        // Get related products
        
        // Query products where product tags == related products
        
        global $post;
        
        $search_terms = wp_get_post_terms( $post->ID, 'product_related' );
        $search_terms = wp_list_pluck( $search_terms, 'slug');        
        
        $args = array(
			'post_type'      => 'product',
            'posts_per_page' => 20,
            'orderby'        => 'rand',
			'post_status'    => 'publish',
            'post__not_in' => array( $post->ID )
		);
        
        if( !empty( $search_terms ) ) {
            $tax_query[] = array(
                'taxonomy'         => 'product_related',
                'terms'            =>  $search_terms,
                'field'            => 'slug',   
                'operator'         => 'IN',
                'include_children' => false,
            );
            
            $args['tax_query'] = $tax_query;
        }
        
		$out = '';

		// Use $loop, a custom variable we made up, so it doesn't overwrite anything
		$loop = new WP_Query( $args );
        
		if ( $loop->have_posts() ) :

			while ( $loop->have_posts() ) : $loop->the_post();

				$out .= $this->related_product();

			endwhile;

		endif;

		wp_reset_postdata();
        
        // Individual related products?
        
        $related_products = get_field( 'related_products' );
        
		if ( ! empty( $related_products ) ) {
                        
            $args = array(
                'post_type'      => 'product',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                'post__in' => $related_products
            );
            
            // Use $loop, a custom variable we made up, so it doesn't overwrite anything
            $loop = new WP_Query( $args );
            
            if ( $loop->have_posts() ) :
    
                while ( $loop->have_posts() ) : $loop->the_post();
                    
                    $out .= $this->related_product();
                        
                endwhile;
    
            endif;
    
            wp_reset_postdata();
		
		}
                

		if ( empty( $out ) ) {
			return false;
		}

		return sprintf( '<div class="related-products">
                       <h4>Related Products</h4>
                       <div class="related-products-slider slider">%s</div></div>', $out );
	}



	private function related_product() {

		global $post;

		$image = get_the_post_thumbnail( $post, 'thumbnail' );

		$prices = get_field( 'product_price' );

		$unit_price = '';

		if ( ! empty( $prices ) ) {
			$last = array_pop( $prices );

			if ( ! empty( $last['unit_price'] ) ) {
				$unit_price = sprintf( '<p class="price">%s</p>', format_money( $last['unit_price'] ) );
			}
		}

		return sprintf( '<div class="slide"><a href="%s">%s%s%s</a></div>', get_permalink(), $image, the_title( '<p class="title">', '</p>', false ), $unit_price );
	}


	/*
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

				$tax         = sprintf( 'doolittle_%s', $key );
				$tax_query[] = array(
					'taxonomy'         => $tax,
					'terms'            => [ $term ],
					'field'            => 'term_taxonomy_id',
					'operator'         => 'IN',
					'include_children' => false,
				);

			}

			$args['tax_query'] = $tax_query;

			$loop = new WP_Query( $args );

			$post_ids[] = $loop->posts;

			wp_reset_postdata();

		}

		$post_ids = array_reduce( $post_ids, 'array_merge', array() );

		$post_ids = array_unique( $post_ids );

		return $post_ids;

	}
    */
    
    /*
        Size, complexity, Application, Event
        
    */
    public function get_product_tag_slugs() {
         
         global $post;
         
         $tags = array( 'size', 'complexity', 'application', 'event' );
         
         $search_terms = [];
         
         foreach( $tags as $tag ) {
             $terms = wp_get_post_terms( $post->ID, sprintf( 'product_%s', $tag ) );
             $array = wp_list_pluck( $terms, 'slug');
             if( ! empty( $array ) ) {
                 array_walk( $array, 'prefix_array_value', $tag );
                 $search_terms[] = $array; 
             }
             
         }
         
         if( ! empty( $search_terms ) ) {
             return combinations( $search_terms );
             // return $search_terms;
         }
         
         return false;
         
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

		$tags = $this->get_product_tag_slugs();
                
		if ( empty( $tags ) ) {
			return false;
		}

		foreach ( $tags as $tag ) {

			if ( empty( $tag ) ) {
				continue;
			}

			$args = $default_args;

			$tax_query = array( 'relation' => 'AND' );

			foreach ( $tag as $term ) {

				if ( empty( $term ) ) {
					continue;
				}
                
                $parts = explode (':', $term ); 

				$tax         = sprintf( 'doolittle_design_%s', $parts[0] );
				$tax_query[] = array(
					'taxonomy'         => $tax,
					'terms'            => [ $parts[1] ],
					'field'            => 'slug', 
					'operator'         => 'IN',
					'include_children' => false,
				);

			}

			$args['tax_query'] = $tax_query;

			$loop = new WP_Query( $args );

			$post_ids[] = $loop->posts;

			wp_reset_postdata();

		}

		$post_ids = array_reduce( $post_ids, 'array_merge', array() );

		$post_ids = array_unique( $post_ids );

		return $post_ids;

	}
        
    // old function
	/*public function get_related_designs() {

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
                       <h2>Featured Designs</h2>
                       <div class="related-designs-slider%s">%s</div></div>', $slider_class, $out );
	}
    */

	
    public function get_related_designs() {
        
        $frontpage_id = get_option( 'page_on_front' );
        
		$term_id = get_field( 'featured_designs', $frontpage_id );
    
        if( empty( $term_id ) ) {
            return false;
        }
        
		$out = '';

		$args = array(
			'post_type'      => 'doolittle_design',
			'orderby'        => 'RAND',
			'post_status'    => 'publish',
			'posts_per_page' => 20
		);
        
        $tax_query[] = array(
            'taxonomy'         => 'doolittle_design_cat',
            'terms'            => [ $term_id ],
            'field'            => 'term_taxonomy_id', 
            'operator'         => 'IN',
            'include_children' => false,
        );
                
    
        $args['tax_query'] = $tax_query;

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
                       <h2>Featured Designs</h2>
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


	public function get_slideshow() {
        
        global $post;
        
		// get photos.
		$attachments = $this->get_attachment_ids();
        
		if ( empty( $attachments ) || $attachments == 1 ) {
			return false;
		}

		$slides     = '';
		$thumbnails = '';

		foreach ( $attachments as $attachment ) {
			$slide  = wp_get_attachment_image( $attachment, 'large' );
			$slides .= sprintf( '<div>%s</div>', $slide );
		}

		printf( '<div class="photos slider">%s</div>', $slides );

	}


	public function get_photos() {
        
        global $post;
        
        // get photos.
		$attachments = $this->get_attachment_ids();
        
		if ( empty( $attachments ) ) {
			return false;
		}

		$slides     = '';
		$thumbnails = '';
		$classes    = '';

		foreach ( $attachments as $attachment ) {

			$large       = wp_get_attachment_image( $attachment, 'large' );
			$thumbnail   = wp_get_attachment_image( $attachment, 'medium' );
			// $slides    .= sprintf( '<div data-thumbnail="%s">%s</div>', $thumbnail[0], $large );
            $slides     .= sprintf( '<div>%s</div>', $large );
            $thumbnails .= sprintf( '<div>%s</div>', $thumbnail );
		}
        
		if ( count( $attachments ) > 1 ) {
			$classes = ' slider';
            $thumbnails = sprintf( '<div class="thumbnails%s">%s</div>', $classes, $thumbnails );
		}
        else {
           $thumbnails = ''; 
        }

		return sprintf( '<div class="featured-image"><div class="images%s">%s</div>%s</div>', $classes, $slides, $thumbnails );

	}


	public function get_attachment_ids() {
        
        global $post, $product;
                  
        $attachment_ids = $product->get_gallery_image_ids();
        
        if ( !empty( $attachment_ids ) && has_post_thumbnail() ) {
		    $post_thumbnail = get_post_thumbnail_id( get_the_ID() );
			array_unshift( $attachment_ids, $post_thumbnail );
            return $attachment_ids;
		}
        else if( has_post_thumbnail() ) {
            $post_thumbnail = get_post_thumbnail_id( get_the_ID() );
            return (array) $post_thumbnail;
        }

		return false;

	}


    /**
     * Retrieves the taxonomy name associated on the specified $term_id. 
     *
     * @access private
     * @param  int    $term_id  The term ID from which to retrieve the taxonomy name.
     * @return string $taxonomy The name of the taxaonomy associated with the term ID.
     */
    private function get_taxonomy_by_term_id( $term_id ) {
        
        // We can't get a term if we don't have a term ID.
        if ( 0 === $term_id || null === $term_id ) {
            return;
        }
        
        // Grab the term using the ID then read the name from the associated taxonomy.
        $taxonomy = '';
        $term = get_term( $term_id );
        if ( false !== $term ) {
            $taxonomy = $term->taxonomy;
        }
    
        return trim( $taxonomy );
    }


	public function __destruct() {
		$this->accordion = array();
	}


}
<?php

class Design_Attributes {
 
    var $accordion = array();
      
    public function __construct() {
         
    }
    
    
    public function get_title() {
        
        $title = the_title( '<h1>', '</h1>', false );
        
        $part_number = $this->get_part_number();
                
        return sprintf( '<header class="entry-title">%s%s</header>', $title, $part_number );
    
    }
    
    
    public function get_part_number() {
        
        $part_number = get_field( 'part_number' );
        
        if( !empty( $part_number ) ) {
            return sprintf( '<p class="part-number">%s<p>', $part_number );
        }
    }
    
  
    
    public function get_related_designs() {
        
        global $post;
        
        $args = array(
            'post_type'           => 'doolittle_design',
            'posts_per_page'      => 20,
            'orderby'             => 'rand',
            'post_status'    => 'publish',
            'post__not_in' => array( $post->ID )
        );
        
        $search_terms = wp_get_post_terms( $post->ID, 'doolittle_design_cat', array( 'fields' => 'ids' ) );
        
        $parents = get_terms( array( 'taxonomy' => 'doolittle_design_cat', 'parent' => 0 ) );
        $parents = wp_list_pluck( $parents, 'term_id' );
        
        // Remove parent terms
        $filtered = array_diff( $search_terms, $parents );
                
        
        if( !empty( $search_terms ) ) {
            $tax_query[] = array(
                'taxonomy'         => 'doolittle_design_cat',
                'terms'            =>  $filtered,
                'field'            => 'term_taxonomy_id',
                'operator'         => 'IN',
                'include_children' => false,
            );
            
            $args['tax_query'] = $tax_query;
        }
        
        /*
        $related_posts = get_field('related_designs');
        
        if( !empty( $related_posts ) ) {
            $args['post__in'] = $related_posts;
            $args['orderby'] = 'post__in';
        }
        else {
            return;   
        }
        
        */
        
        $out = '';
          
        // Use $loop, a custom variable we made up, so it doesn't overwrite anything
        $loop = new WP_Query( $args );
                    
        if ( $loop->have_posts() ) : 
        
         
            while ( $loop->have_posts() ) : $loop->the_post(); 
                
                $out .= $this->related_design();
       
            endwhile;
               
        endif;
    
        wp_reset_postdata();   
        
        if( empty( $out ) ) {
            return false;
        }
        
        return sprintf( '<div class="related-designs">
                       <h4>Related Designs</h4>
                       <div class="related-designs-slider slider">%s</div></div>', $out );
    }
    
    
     private function related_design() {
        
        global $post;
        
        $image = get_the_post_thumbnail( $post, 'thumbnail' );
        
        if( empty( $image ) ) {
            return;
        }
          
        return sprintf( '<div><a href="%s">%s</a></div>', get_permalink(), $image );   
    }

    
    
    /*
        Size, complexity, Application, Event
        
    */
    public function get_design_tag_slugs() {
         
         global $post;
         
         $tags = array( 'size', 'complexity', 'application', 'event' );
         
         $search_terms = [];
         
         foreach( $tags as $tag ) {
             $terms = wp_get_post_terms( $post->ID, sprintf( 'doolittle_design_%s', $tag ) );
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
    
    
    public function _get_suggested_product_ids() {

		$post_ids = array();

		$default_args = array(
			'post_type'      => 'product',
			'posts_per_page' => 10,
			'orderby'        => 'RAND',
			'post_status'    => 'publish',
			'no_found_rows'  => true,
			'fields'         => 'ids'
		);

		$tags = $this->get_design_tag_slugs();
                        
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

				$tax         = sprintf( 'product_%s', $parts[0] );
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
    
    
    /*
    public function _get_suggested_product_ids() {
        
        $post_ids = array();
        
        $default_args = array(
            'post_type'           => 'product',
            'posts_per_page'      => 10,
            'orderby'             => 'RAND',
            'post_status'         => 'publish',
            'no_found_rows'       => true,
            'fields'              => 'ids'
        );
                
        $tags = get_field( 'product_tags' );
                
        if( empty( $tags ) ) {
            return false;   
        }
                
        foreach( $tags as $tag ) {
                        
            if( empty( $tag ) ) {
                continue;   
            }
             
            $args = $default_args;  
            
            $tax_query = array( 'relation' => 'AND' );
             
            foreach( $tag as $tax => $term ) {
                                
                if( empty( $term ) ) {
                    continue;
                }  
                
                $tax_query[] = array (
                    'taxonomy' => $tax,
                    'terms' => [$term],
                    'field' => 'term_taxonomy_id',
                    'operator' => 'IN',
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
 
    }*/

    
    
    public function get_suggested_products() {
        
        $post_ids = $this->_get_suggested_product_ids();
        
        
        if( empty( $post_ids ) ) {
            return false;
        }
        
        $out = '';
        
        $args = array(
            'post_type'         => 'product',
            'post__in'          => $post_ids,
            'orderby'           => 'RAND',
            'post_status'       => 'publish',
            'posts_per_page'    => 20
        );
        
        // Use $loop, a custom variable we made up, so it doesn't overwrite anything
        $loop = new WP_Query( $args );
        
        $column_class = ' class="slide"';
        $slider_class = ' slider';
                      
        if ( $loop->have_posts() ) : 
        
         
            while ( $loop->have_posts() ) : $loop->the_post(); 
                
                $out .= sprintf( '%s', $this->suggested_product() );
       
            endwhile;
               
        endif;
    
        wp_reset_postdata();   
        
        if( empty( $out ) ) {
            return false;
        }
        
        return sprintf( '<div class="suggested-products">
                       <h4>Suggested Products:</h4>
                       <div class="suggested-products-slider%s">%s</div></div>', $slider_class, $out );
    }
    
    
    
     private function suggested_product() {
        
        global $post;
        
        $image = get_the_post_thumbnail( $post, 'thumbnail' );
        
        $prices = get_field( 'product_price' );
        
        $unit_price = '';
        
        if( !empty( $prices ) ) {
            $last = array_pop( $prices );
            
            if( !empty( $last['unit_price'] ) ) {
                $unit_price = sprintf( '<p class="price">%s</p>', format_money( $last['unit_price'] ) );
            }
         }
            
        return sprintf( '<div class="slide"><a href="%s">%s%s%s</a></div>', get_permalink(), $image, the_title( '<p class="title">', '</p>', false ), $unit_price );   
    }
    
    
    
     
    
    public function get_slideshow() {
     
        // get photos. 
        $attachments = $this->get_attachment_ids();
        
        if( empty( $attachments ) || $attachments == 1 ) {
            return false;
        }
                
        $slides = '';
        $thumbnails = '';
        
        foreach( $attachments as $attachment ) {
            $slide = wp_get_attachment_image_src( $attachment, 'large' );
            $slides .= sprintf( '<div><span style="background-image: url(%s);"></span></div>', $slide[0] );
        }
        
        printf( '<div class="photos slider">%s</div>', $slides );
        
    }
    
    
    public function get_photos() {
     
        // get photos. 
        $attachments = $this->get_attachment_ids();
                
        if( empty( $attachments ) ) {
            return false;
        }
                
        $slides = '';
        $thumbnails = '';
        $classes = '';
        
        foreach( $attachments as $attachment ) {
            
            $slide = wp_get_attachment_image_src( $attachment, 'large' );
            $thumbnail = wp_get_attachment_image_src( $attachment, 'thumbnail' );
            $slides .= sprintf( '<div data-thumbnail="%s"><span style="background-image: url(%s);"></span></div>', $thumbnail[0], $slide[0] );
        }
        
        if( count( $attachments ) > 1 ) {
            $classes = ' slider';
        }
        
        return sprintf( '<div class="featured-image"><div class="images%s">%s</div></div>', $classes, $slides );
        
    }
        
    
    private function get_attachment_ids() {
        
        $attachments = get_field( 'photos' );
        
        if( empty( $attachments ) ) {
            $attachments = array();
        }
        
        $attachments = wp_list_pluck( $attachments, 'ID' );
        $post_thumbnail = get_post_thumbnail_id( get_the_ID() );
        if( !empty( $post_thumbnail ) ) {
            array_unshift( $attachments, $post_thumbnail );
        }
        
        return $attachments;
        
    }
    
    
    public function __destruct()
    {
        $this->accordion = array();
    }

    
}
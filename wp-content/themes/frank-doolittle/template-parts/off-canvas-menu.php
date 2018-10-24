<?php

/*
Modal - Menu
		
*/
?>
<div class="off-canvas position-left hide-for-large" id="offCanvas" data-transition="overlap" data-off-canvas>	
	<nav class="nav-secondary" role="navigation">
		<div class="wrap">
 			<!--<button class="close-button" aria-label="Close menu" type="button" data-close>
			  <span aria-hidden="true">&times;</span>
			</button>-->            
            <div class="favorites-and-quotes">
				
                <a data-open="modal-favorites" class="favorites">
                    <p>Favorites</p>
                    <div class="icon">
                        <?php
                        $favorite_count = _s_get_favorites_count();
                        // Set count to empty so we can hide with CSS
                        $favorite_count = $favorite_count ? $favorite_count : '';
                        printf( '<span class="number">%s</span>', $favorite_count );
                        ?>
                    </div>
                </a>
 				
                
                 <a href="<?php the_permalink( 33 );?>" class="quotes">
                    <?php
                    $quote_count = _s_get_quotes_count();
                    // Set quote count to empty so we can hide with CSS
                    $quote_count = $quote_count ? $quote_count : '';
                    $quote_text = $quote_count ? 'My Quote' : 'Build Quote';
                    printf( '<p>%s</p>', $quote_text );
                    ?>
					<div class="icon">
                        <?php
                        printf( '<span class="number">%s</span>', $quote_count );
                        ?>
					</div>
                 </a>
                 
			</div>
            
            
            
            <?php
            get_search_form();
            ?>
            
            
			<?php
				// Desktop Menu
				$args = array(
					'theme_location' => 'primary',
                    'menu' => 'Primary Menu',
                    'container' => 'false',
                    'container_class' => '',
                    'container_id' => '',
                    'menu_id'        => 'primary-menu',
					'menu_class'     => 'vertical menu',
					'before' => '',
					'after' => '',
					'link_before' => '',
					'link_after' => '',
					'items_wrap' => '<ul id="%1$s" class="%2$s" data-accordion-menu>%3$s</ul>',
					'depth' => 0
				);
				wp_nav_menu($args);						
				
			?>
			
		</div>
	</nav><!-- .nav-secondary -->			
	
</div>

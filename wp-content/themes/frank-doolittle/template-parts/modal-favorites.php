<?php

/*
Modal - Favorites

*/


$favorites_modal_not_logged_in      = get_field( 'favorites_modal_not_logged_in', 'option' );
$favorites_modal_default            = get_field( 'favorites_modal_default', 'option' );
$favorites_modal_designs_none       = get_field( 'favorites_modal_designs_none', 'option' );
$favorites_modal_products_none      = get_field( 'favorites_modal_products_none', 'option' );
?>
    
<div class="modal-favorites reveal" id="modal-favorites" data-reveal data-animation-in="hinge-in-from-middle-y fast" data-animation-out="hinge-out-from-middle-y fast">
    
    <div class="modal-header">
        <div class="column row">
            <h2>My Favorites</h2>
            <button class="close-button" data-close aria-label="Close modal" type="button"><span aria-hidden="true">&times;</span></button>
        </div>
    </div>
    
    <div class="modal-content">
        
        <div class="modal-description">
            <div class="column row">
            <?php
              
            if( !is_user_logged_in() &&  !empty( $favorites_modal_not_logged_in ) ) {
                printf( '<p class="black">%s</p>', $favorites_modal_not_logged_in );
            }
            
            if( !empty( $favorites_modal_default ) ) {
                echo $favorites_modal_default;
            }
            ?>
 
            </div>
        </div>

        <div class="grid-container design">
             
                <h3>Designs</h3>
                <?php
                printf( '<div class="row small-up-1 medium-up-3 large-up-4 grid">%s</div>', _s_get_design_favorites() );
                printf( '<div class="notice">%s</div>',  $favorites_modal_designs_none );
                ?>

               
        </div><!-- .designs -->

           
        <div class=" grid-container product">
              
                <h3>Products</h3>
                <?php
                printf( '<div class="row small-up-1 medium-up-3 large-up-4 grid">%s</div>', _s_get_product_favorites() );
                printf( '<div class="notice">%s</div>',  $favorites_modal_products_none );
                ?>
               
        </div>

        <div class="row columns text-center actions">
            <a class="btn-primary btn-inverted add-favorites-to-quote disabled">Add selected to quote</a>
        </div>

    </div>
 
 </div>
<?php

/*
Modal - Quotes

*/

?>
    
<div class="modal-quote reveal" id="modal-quote" data-reveal data-animation-in="hinge-in-from-middle-y fast" data-animation-out="hinge-out-from-middle-y fast">
    
    <div class="modal-header">
        <div class="column row">
            <h2>View Items Save In My Quote</h2>
            <button class="close-button" data-close aria-label="Close modal" type="button"><span aria-hidden="true">&times;</span></button>
        </div>
    </div>
    
    <div class="modal-content">
        
        <div class="modal-description">
            <div class="column row">
            <p>Below is a list of designs and products you selected to build a quote.</p>
            </div>
        </div>

        <div class="grid-container design">
             
                <h3>Designs</h3>
                <?php
                printf( '<div class="row small-up-1 medium-up-3 large-up-4 grid">%s</div>', _s_get_quotes() );
                printf( '<div class="notice">%s</div>',  'No designs have been added to your quote.' );
                ?>

               
        </div><!-- .designs -->

           
        <div class=" grid-container product">
              
                <h3>Products</h3>
                <?php
                printf( '<div class="row small-up-1 medium-up-3 large-up-4 grid">%s</div>', _s_get_quotes( 'product' ) );
                printf( '<div class="notice">%s</div>',  'No Products have been added to your quote.' );
                ?>
               
        </div>

        <div class="row columns text-center actions">
            <a class="btn-secondary clear-all">Remove All</a>
        </div>

    </div>
 
 </div>
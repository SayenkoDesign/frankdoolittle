<?php

/*
Modal - Designs

*/

$design_modal_default   = get_field( 'design_modal_default', 'option' );
$design_modal_none      = get_field( 'design_modal_none', 'option' );
?>
<div class="modal-design reveal" id="modal-design" data-reveal data-animation-in="hinge-in-from-middle-y fast" data-animation-out="hinge-out-from-middle-y fast">

    
    <div class="modal-header">
        <div class="column row">
            <h3>Step 2</h3>
            <h2>Add an Item</h2>
            <button class="close-button" data-close aria-label="Close modal" type="button"><span aria-hidden="true">&times;</span></button>
        </div>
    </div>
    
    <div class="modal-content">
        
        <div class="modal-description">
            <div class="column row">
            <div class="msg"></div>
            <?php
            echo $design_modal_default;
            ?>
            </div>
        </div>
        
        <div class="grid-container">
            <div class="column row">
                <h3>Designs</h3>
                <?php
                printf( '<div  class="row small-up-1 medium-up-3 large-up-4 grid">%s</div>', _s_get_quotes() ); 
                printf( '<div class="notice">%s</div>',  $design_modal_none );
                ?>
                <div class="text-center actions">
                    <a class="btn-secondary clear-all">Remove All</a>
                </div>
             </div>
        </div>

    </div>
 
 </div>
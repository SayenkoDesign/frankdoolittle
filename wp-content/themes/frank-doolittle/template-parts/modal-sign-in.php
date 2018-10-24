<?php

/*
Modal - Sign in

*/
?>

<div class="modal-sign-in reveal" id="modal-sign-in" data-reveal data-deep-link="true" data-animation-in="hinge-in-from-middle-y fast" data-animation-out="hinge-out-from-middle-y fast">
    <div class="modal-header">
        <div class="column row">
         <h2>Sign In</h2>
        <button class="close-button" data-close aria-label="Close modal" type="button"><span aria-hidden="true">&times;</span></button>
        </div>
    </div>
      
    <div class="modal-content">
        <div class="column row">
            <div class="modal-form">
                <?php
                global $wp;
                $current_url = home_url( add_query_arg( array(), $wp->request ) );
                
                add_filter( 'login_form_middle', function() {
                    return sprintf( '<div><a href="%s">Lost your Password?</a></div>', wp_lostpassword_url() );
                });
                
                doolittle_login_form( array( 'redirect' => $current_url ) );
                ?>
            </div>
         </div>
    </div>
 </div>

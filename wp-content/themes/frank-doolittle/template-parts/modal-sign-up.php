<?php

/*
Modal - Create Account

*/

modal_create_account();
function modal_create_account() {
    ?>
    <div class="reveal modal-create-account" id="modal-create-account" data-reveal data-deep-link="true" data-animation-in="hinge-in-from-middle-y fast" data-animation-out="hinge-out-from-middle-y fast">
 	<div class="modal-content">
      <div class="modal-header">
         <button class="close-button" data-close aria-label="Close modal" type="button">
            <span aria-hidden="true">&times;</span>
          </button>
          <h2>Create an Account</h2>
      </div>
      <div class="modal-form">
	  <?php
 	    echo do_shortcode( '[gravityform id="4" title="false" description="false" ajax="true" tabindex="99"]' );
	  ?>
      </div>
   </div>
</div>
<?php

}

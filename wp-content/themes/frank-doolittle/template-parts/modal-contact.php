<?php

/*
Modal - Contact

*/

modal_contact();
function modal_contact() {
    ?>
    <div class="modal-contact reveal" id="contact" data-reveal data-animation-in="hinge-in-from-middle-y fast" data-animation-out="hinge-out-from-middle-y fast">
      <div class="modal-header">
          <div class="column row">
            <h2><a href="tel:800-621-7633">Call (800) 621-7633</a> or Contact Us Below</h2>
            <button class="close-button" data-close aria-label="Close modal" type="button"><span aria-hidden="true">&times;</span></button>
          </div>
      </div>
      
	  <div class="modal-content">
          <div class="row column about">
             <p>
                Our company headquarters are located in the greater Seattle area, but our suppliers and decorators are located throughout all regions of the United States. We work with and ship to organizations nationwide.
             </p>
          </div>
          <div class="row contact-info">
             <div class="columns large-6">
               <p>
                  <strong>The FrankDoolittle Comany</strong><br />  11811 NE 1st St, Suite A-209<br />   Bellevue, WA 98005
               </p>
             </div>
             <div class="columns large-6">
               <p><strong>Toll Free:</strong> 800.621.7633<br />
                  <strong>Local:</strong> 425.274.7250 <br />
                  <strong>Fax:</strong> 425.274.7252  <br />
                  <strong>Email:</strong> <a href="mailto:info@frankdoolittle.com">info@frankdoolittle.com</a>
               </p>
             </div>
          </div>
          
          
          <div class="modal-form">
	  <?php
 	    echo do_shortcode( '[gravityform id="2" title="false" description="false" ajax="true" tabindex="99"]' );
	  ?>
      </div>
          
          
		  </div>

      
</div>
<?php

}

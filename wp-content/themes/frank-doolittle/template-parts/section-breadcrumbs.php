<?php
/*
section - breadcrumbs
*/
?>
<section class="breadcrumbs">
   <div class="wrap">
      <div class="row columns">
         <?php 
         if ( function_exists( 'bcn_display' ) ) {
               bcn_display(); 
         }
         ?>
      </div>
   </div>
</section>

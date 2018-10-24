<?php

/*
Modal - Product Slideshow

*/

modal_product_slideshow();
function modal_product_slideshow() {
    global $post;
    $design = new Design_attributes;
    $part_number = $design->get_part_number();
    ?>
    <div class="modal-slideshow full reveal" id="modal-slideshow" data-reveal>
      <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
      </button>
      <div class="modal-content column row">
          <div class="modal-title">
            <?php
            the_title( '<h2>', '</h2>' );
            echo $part_number;
            ?>
            <div class="buttons show-for-large">
                <a href="" class="add-to-favorites <?php echo get_favorite_class(); ?>" data-post-id="<?php echo get_the_ID(); ?>">
                + Add to Favorites</a>
                <a href="" class="add-to-quote <?php echo get_quote_class(); ?>" data-post-id="<?php echo get_the_ID(); ?>">
                  + Add to Quote</a>
            </div>
          </div>
          <div class="slideshow">
              <?php
              echo $design->get_slideshow();
              ?>
          </div>
          <?php
          echo _s_get_addtoany_share_icons();
          ?>
      </div>
</div>
<?php

}

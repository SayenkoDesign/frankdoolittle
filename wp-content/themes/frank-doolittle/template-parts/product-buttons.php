

<div class="buttons">
  <a href="" class="add-to-favorites <?php echo get_favorite_class(); ?>" data-post-id="<?php echo get_the_ID(); ?>">
    <?php echo get_svg('my-favorite');?>Add to Favorites</a>
  
  <a href="" class="add-to-quote <?php echo get_quote_class(); ?>" data-post-id="<?php echo get_the_ID(); ?>">
  <?php echo get_svg('my-quote-white');?>Add to Quote</a>

</div><!-- button holder -->

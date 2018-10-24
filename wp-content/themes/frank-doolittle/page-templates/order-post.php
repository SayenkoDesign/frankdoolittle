<?php
/*
Template Name: Order Details
*/

$order_id = false;

$order_archive = get_post_type_archive_link( 'shop_order' );

if( !isset( $_GET['order_id'] ) && empty( absint( $_GET['order_id'] ) ) ) {
    wp_redirect( $order_archive );
    exit;
}

$order_id = absint( $_GET['order_id'] );

$order = wc_get_order( $order_id );

if( ! $order ) {
    wp_redirect( $order_archive );
    exit;
}

get_header(); ?>
<div id="primary" class="content-area">

	<main id="main" class="site-main" role="main">
    
      <?php
      $order_date = $order->get_date_created()->date_i18n( 'm/j/Y' );
      $custom_order_id = get_field( 'order_id', $order_id );
      $product = get_field( 'product', $order_id );
      ?>
    
      <section class="order-post-title-row">
         <div class="wrap">
            <div class="row columns text-center">
                <?php
                printf( '<h1 class="order-post-title">Order #%s</h1>', $custom_order_id );
                printf( '<p class="orders-date">%s</p>', $order_date );
                printf( '<p class="order-for">For: %s</p>', $product );
                printf( '<a href="%s" class="btn-secondary"> < Back to Orders</a>', $order_archive );
                ?>
            </div>
         </div>
      </section>
      <?php
      /*
      invoice (url) use images/portal/invoice.png
      designs ( gallery)
      */
      
      $columns = $modals = '';
      
      $invoice = get_field( 'invoice', $order_id );
      
      
      
      if( !empty ( $invoice ) ) {
          $background = sprintf( 'background-image: url(%sportal/invoice.png);', trailingslashit( THEME_IMG ) );
          $invoice = sprintf( '<a href="%s" style="%s" target="_blank" class="image"><span>Download Invoice</span></a>', $invoice, $background );
          
          $columns .= sprintf( '<div class="column column-block">%s</div>', $invoice );
          
          $attr = array( 'id' => 'order-post-section', 'class' => 'section order-post-section' );        
              
          _s_section_open( $attr );	
          
          $designs = get_field( 'designs', $order_id );
          
          foreach( $designs as $key => $design ) {
              
              $id = $key + 1;
              $img = wp_get_attachment_image( $design['ID'], 'large' );
              $photo = wp_get_attachment_image_src( $design['ID'], 'large' );
              $background = sprintf( 'background-image: url(%s);', $photo[0] );
              $enlarge = sprintf( '<div class="enlarge"><a data-open="gallery-photo-%s">%s</a></div>', $id, get_svg( 'search' ) );
              $columns .= sprintf( '<div class="column column-block"><span style="%s" class="image">%s</span></div>', $background, $enlarge );
             
              $modals .=  _s_order_design_modal( $id, $img );
          }          
          
          printf( '<div class="row small-up-1 medium-up-2 large-up-3">%s</div>', $columns );
          
          echo $modals;
          
          _s_section_close();	
      }      
      
      ?>

   </main>

</div>

<?php
get_footer();


function _s_order_design_modal( $id, $img ) {
    return sprintf( '<div class="modal-slideshow full reveal" id="gallery-photo-%s" data-reveal>
      <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
      </button>
      <div class="modal-content column row">%s</div></div>', $id, $img );
}
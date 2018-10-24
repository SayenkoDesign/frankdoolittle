<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package _s
 */



get_header(); ?>

<div class="column row">

    <div id="primary" class="content-area">

        <main id="main" class="site-main" role="main">
        
            <header class="page-header text-center"><h1>My Orders</h1></header>
        
            <?php
            if ( have_posts() ) : ?>
            
                <div class="filters product-filters text-center">
                                
                <?php 
                    echo facetwp_display( 'facet', 'orders_filter_year' );
                ?>
                </div>

                

                <?php
                echo '<div class="column row facetwp-template">';
                
                $table = new CI_Table();
        
                $attr = array( 'id' => 'my-orders-table', 'class' => 'section orders-table' );        
              
                _s_section_open( $attr );	
                
                $th = array();
                $th[] = array( 'data' => '<span>Date</span>', 'data-sort' => 'int' );
                $th[] = array( 'data' => '<span>Product</span>', 'data-sort' => 'string' );
                $th[] = array( 'data' => '<span>Order</span>', 'data-sort' => 'int' );
                $table->set_heading( $th );
                
                while ( have_posts() ) :

                    the_post();

                    $order = new WC_Order( get_the_ID() );
        
                    $order_date = $order->get_date_created()->date_i18n( 'm/j/Y' );
                    $order_date_timestamp = strtotime( $order_date );
                    
                    //$order_completed_date = $order->get_date_completed()->date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) );
                    $order_status = $order->get_status(); // pending, completed
                    $order_status_name = wc_get_order_status_name( $order->get_status() );
                    
                    $cell = array();
                    $cell[] = array( 'data' => $order_date, 'data-sort-value' => $order_date_timestamp  );
                    $cell[] = array( 'data' => get_field( 'product' ) );
                    $cell[] = array( 'data' => sprintf( '<a href="%s?order_id=%s">%s</a>', get_permalink(1482), get_the_ID(), get_field( 'order_id' ) ) );
                    
                    $table->add_row( $cell );

                endwhile;
                
                $template = array(
                'tbody' => '<tbody class="facetwp-template">'
                );
                                
                $table->set_template($template);
            
                printf( '<div class="row"><div class="large-8 large-centered columns">%s</div></div>', $table->generate() );
               
                _s_section_close();	

                //the_posts_navigation();

            else :

                get_template_part( 'template-parts/content', 'none' );

            endif; ?>

        </main>

    </div>

</div>


<?php
get_footer();

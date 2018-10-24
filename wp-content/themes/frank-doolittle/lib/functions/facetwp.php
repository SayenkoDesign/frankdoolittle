<?php

// Save last price value as a seperate field for sorting
function my_acf_save_post( $post_id ) {
    
    $rows = get_field('product_price');
    
    if( empty( $rows ) ) {
        $unit_price = 0;
    } else {
        $last_row = end($rows);
        $unit_price = floatval( $last_row['unit_price'] );
    }
      
    update_post_meta($post_id, '_sort_price', $unit_price );
    
}

add_action('acf/save_post', 'my_acf_save_post', 20);


function my_facetwp_sort_options( $options, $params ) {
    
    $options['default']['label'] = __( 'Sort By', 'fwp' );
    
    $options['price_desc'] = array(
        'label' => 'Price (High-Low)',
        'query_args' => array(
            'orderby' => 'meta_value_num', // sort by numerical custom field
            'meta_key' => '_sort_price', // required when sorting by custom fields
            'order' => 'DESC', // descending order
        )
    );
    
    $options['price_asc'] = array(
        'label' => 'Price (Low-High)',
        'query_args' => array(
            'orderby' => 'meta_value_num', // sort by numerical custom field
            'meta_key' => '_sort_price', // required when sorting by custom fields
            'order' => 'ASC', // descending order
        )
    );
    
    unset( $options['date_desc'] );
    unset( $options['date_asc'] );
    
    return $options;
}

add_filter( 'facetwp_sort_options', 'my_facetwp_sort_options', 10, 2 );


function fwp_load_more_button() {
?>
<script>
(function($) {
    $(function() {
        if ('object' != typeof FWP) {
            return;
        }

        wp.hooks.addFilter('facetwp/template_html', function(resp, params) {
            if (FWP.is_load_more) {
                FWP.is_load_more = false;
                $('.facetwp-template').append(params.html);
                return true;
            }
            return resp;
        });
    });

    $(document).on('click', '.fwp-load-more', function() {
        $('.fwp-load-more').html('Loading...');
        FWP.is_load_more = true;
        FWP.paged = parseInt(FWP.settings.pager.page) + 1;
        FWP.soft_refresh = true;
        FWP.refresh();
    });

    $(document).on('facetwp-loaded', function() {
        if (FWP.settings.pager.page < FWP.settings.pager.total_pages) {
            if (! FWP.loaded && 1 > $('.fwp-load-more').length) {
                $('.facetwp-template').after('<button class="fwp-load-more btn-secondary load-more">Load more</button>');
            }
            else {
                $('.fwp-load-more').html('Load more').show();
            }
        }
        else {
            $('.fwp-load-more').hide();
        }
    });

    $(document).on('facetwp-refresh', function() {
        if (! FWP.loaded) {
            FWP.paged = 1;
        }
    });
})(jQuery);
</script>
<?php
}

function fwp_load_more_scroll() {
?>
<script>
(function($) {
    window.fwp_is_paging = false;

    $(document).on('facetwp-refresh', function() {
        if (! window.fwp_is_paging) {
            window.fwp_page = 1;
            FWP.extras.per_page = 'default';
        }

        window.fwp_is_paging = false;
    });

    $(document).on('facetwp-loaded', function() {
        window.fwp_total_rows = FWP.settings.pager.total_rows;

        if (! FWP.loaded) {
            window.fwp_default_per_page = FWP.settings.pager.per_page;
            
            $(window).scroll(function() {
                if ( 300 >= $(document).height() - $(window).height() - $(window).scrollTop() ) {
                    var rows_loaded = (window.fwp_page * window.fwp_default_per_page);
                    if (rows_loaded < window.fwp_total_rows) {
                        console.log(rows_loaded + ' of ' + window.fwp_total_rows + ' rows');
                        window.fwp_page++;
                        window.fwp_is_paging = true;
                        FWP.extras.per_page = (window.fwp_page * window.fwp_default_per_page);
                        FWP.soft_refresh = true;
                        FWP.refresh();
                    }
                }
            });
        }
    });
})(jQuery);
</script>
<?php
}
add_action( 'wp_head', 'fwp_load_more_button', 99 );
add_filter( 'facetwp_template_force_load', '__return_true' );


// Save last price value as a seperate field for sorting
function order_year_save_post( $post_id ) {
    
    if( 'shop_order' != get_post_type() ) {
        return;
    }
    
    $order = new WC_Order( $post_id );
            
    $order_year = $order->get_date_created()->date_i18n( 'Y' );
      
    update_post_meta($post_id, '_order_year', $order_year );
    
}

add_action('acf/save_post', 'order_year_save_post', 20);


add_filter( 'facetwp_is_main_query', function( $is_main_query, $query ) {
    if ( isset( $query->query_vars['facetwp'] ) ) {
        $is_main_query = (bool) $query->query_vars['facetwp'];
    }
    return $is_main_query;
}, 10, 2 );


add_filter( 'facetwp_indexer_query_args', function( $args ) {
    $args['post_type'] = array('any', 'shop_order');
    $args['post_status'] = array( 'publish', 'wc-completed' );
    return $args;
});


add_filter( 'facetwp_use_rest_api', '__return_false' );
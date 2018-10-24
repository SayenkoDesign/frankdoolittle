<?php

// Fix - Set woocommerce_hide_invisible_variations to true so disabled variation attributes are hidden on product pages. WooCommerce 3.3.2
add_filter( 'woocommerce_hide_invisible_variations', '__return_false', 10);

// Require user be logged in to view orders

function redirect_orders_no_login() {
    
    if ( is_user_logged_in() ) {
        return;
    }
    
    $order_id = isset( $_GET['order_id'] ) ? $_GET['order_id'] : false;
    
    if ( is_post_type_archive( 'shop_order' ) || ( true == $order_id )  ) {
    
        wp_redirect( sprintf( '%s#modal-sign-in', trailingslashit( site_url() ) ) ); 
        exit;
    }
}


add_action( 'get_header', 'redirect_orders_no_login' );



// change WooCommerce Order #

add_filter( 'woocommerce_order_number', function( $post_id ) {
    
    return get_post_meta( $post_id, 'order_id', true );
    
}, 1, 10);

// Peoducts Admin remove columns
function change_order_columns_filter( $columns ) {
    unset($columns['shipping_address']);
    unset($columns['customer_message']);
    unset($columns['order_total']);
    return $columns;
}
add_filter( 'manage_edit-shop_order_columns', 'change_order_columns_filter',10, 1 );


// toolbar-variations-defaults

// Remove WooCommerce Admin menu items


// Hide certain elements with styles
if (!function_exists('hide_setting_checkout_for_shop_manager')){
    function hide_setting_checkout_for_shop_manager() {
             echo '
                <style> 
                    #inventory_product_data > div.options_group ._sold_individually_field { display: none!important; } 
                    
                    #order_data h2 {
                        display: none!important;   
                    }
                    
                    #order_data .order_data_column:not(:first-child) {
                        display: none!important;   
                    }
                </style>';
    }
}
add_action('admin_head', 'hide_setting_checkout_for_shop_manager');


// This sits in my plugin .php file
function my_woocommerce_product_after_variable_attributes( $loop, $variation_data, $variation ) {
    ?>
    <script>
    setupVariation = function( variationId ) {
        $( 'input[name="variable_is_virtual[' + variationId + ']"]' ).attr( 'checked', 'checked' ).trigger( 'change' );
    }
    
    setupVariation($loop);
    </script>
    <?php
}

//add_action( 'woocommerce_product_after_variable_attributes', 'my_woocommerce_product_after_variable_attributes', 10, 3 );




function wooninja_remove_items() {
 $remove = array( 
    'wc-reports', 
    'wc-addons',
    //'wc-settings',
    //'wc-status',
    'edit.php?post_type=shop_coupon',
    //'edit.php?post_type=shop_order'
    );
  foreach ( $remove as $submenu_slug ) {
     remove_submenu_page( 'woocommerce', $submenu_slug );
   }
}

add_action( 'admin_menu', 'wooninja_remove_items', 99, 0 );



add_filter( 'woocommerce_settings_tabs_array', 'remove_woocommerce_setting_tabs', 200, 1 );
function remove_woocommerce_setting_tabs( $tabs ) {
    // Declare the tabs we want to hide
    $tabs_to_hide = array(
        'Tax',
        'Shipping',
        'Products',
        'Checkout',
        //'Emails',
        'API',
        'Accounts',
        );
 
    // Get the current user
    $user = wp_get_current_user();

    // Remove the tabs we want to hide
    $tabs = array_diff($tabs, $tabs_to_hide);

    return $tabs;
}


add_filter( 'woocommerce_general_settings', function( $fields ) {
    $fields[0]['desc'] = __( 'This is where your business is located.', 'woocommerce' );
    return $fields;
}, 10, 1 );



// Peoducts Admin remove columns
function change_columns_filter( $columns ) {
    unset($columns['product_tag']);
    unset($columns['sku']);
    unset($columns['featured']);
    unset($columns['product_type']);
    unset($columns['post_type']);
    unset($columns['price']);
    return $columns;
}
add_filter( 'manage_edit-product_columns', 'change_columns_filter',10, 1 );



// Remove Grouped and External Products
function remove_product_types( $types ){
    unset( $types['grouped'] );
    unset( $types['external'] );

    return $types;
}

add_filter( 'product_type_selector', 'remove_product_types' );

add_filter( 'product_type_options', function( $types ) {
    return array();
}, 10, 1);


// Remove Product Data Tabs in admin

function remove_tab($tabs){
    unset($tabs['general']);
    unset($tabs['shipping']);
    //unset($tabs['inventory']); // it is to remove inventory tab
    unset($tabs['advanced']); // it is to remove advanced tab
    unset($tabs['linked_product']); // it is to remove linked_product tab
    //unset($tabs['attribute']); // it is to remove attribute tab
    //unset($tabs['variations']); // it is to remove variations tab
    return($tabs);
}
add_filter('woocommerce_product_data_tabs', 'remove_tab', 10, 1);


// Remove Products Short Description

function remove_metaboxes() {
     //remove_meta_box( 'postcustom' , 'product' , 'normal' );
     remove_meta_box( 'postexcerpt' , 'product' , 'normal' );
     //remove_meta_box( 'commentsdiv' , 'product' , 'normal' );
     //remove_meta_box( 'tagsdiv-product_tag' , 'product' , 'normal' );
}
add_action( 'add_meta_boxes' , 'remove_metaboxes', 50 );



// Orders

function hide_wc_order_statuses( $order_statuses ) {

    // Hide core statuses
    unset( $order_statuses['wc-refunded'] );
    unset( $order_statuses['wc-failed'] );
    unset( $order_statuses['wc-on-hold'] );
    unset( $order_statuses['wc-cancelled'] );
    unset( $order_statuses['wc-pending'] );
    unset( $order_statuses['wc-processing'] );
    //unset( $order_statuses['wc-completed'] );

    return $order_statuses;
}
add_filter( 'wc_order_statuses', 'hide_wc_order_statuses' );

// Remove Order metaboxes
 
function remove_woocommerce_order_metaboxes() {
    remove_meta_box( 'woocommerce-order-items','shop_order', 'normal' );  
    remove_meta_box( 'woocommerce-order-downloads','shop_order', 'normal' );  
}
add_action('add_meta_boxes','remove_woocommerce_order_metaboxes', 50);


function hide_woocommerce_order_actions( $order_actions ) {

    // Hide core statuses
    unset( $order_actions['send_order_details_admin'] );
    unset( $order_actions['regenerate_download_permissions'] );

    return $order_actions;
}
add_filter( 'woocommerce_order_actions', 'hide_woocommerce_order_actions' );



add_filter( 'loop_shop_per_page', 'new_loop_shop_per_page', 20 );

function new_loop_shop_per_page( $cols ) {
  // $cols contains the current number of products per page based on the value stored on Options -> Reading
  // Return the number of products you wanna show per page.
  $cols = 12;
  return $cols;
}
<?php

// Remove all order details
function _s_woocommerce_remove_order_details( $order, $sent_to_admin, $plain_text, $email ){
    $mailer = WC()->mailer(); // get the instance of the WC_Emails class
    remove_all_actions( 'woocommerce_email_order_details', 10, 4 );
    // remove_action( 'woocommerce_email_order_details', array( $mailer, 'order_details' ), 10, 4 );
    add_action( 'woocommerce_email_order_details', '_s_woocommerce_email_order_details', 10, 4 );
}
add_action( 'woocommerce_email_order_details', '_s_woocommerce_remove_order_details', 5, 4 );


// Add custom order details
function _s_woocommerce_email_order_details( $order, $sent_to_admin, $plain_text, $email ) {
              
       $order_data = $order->get_data();
       $order_billing_first_name = $order_data['billing']['first_name'];
       
       $order_message = 'An order has been added to your portal';
       
       if ( $plain_text ) {
           printf( "Dear %s,\n\n", $order_billing_first_name );
           
           $order_message .= "\n\nPlease login to view your orders:";
           
           printf( '%s %s', $order_message, get_post_type_archive_link( 'shop_order' ) );
       }
       else {
           printf( '<p>Dear %s,</p>', $order_billing_first_name );
           
           printf( '<p>Please <a href="%s">click here to login</a> to view your orders.</p>', get_post_type_archive_link( 'shop_order' ) );
       }
       
       
       
       
}




// Remove all order meta
function _s_woocommerce_remove_email_order_meta( $order, $sent_to_admin, $plain_text, $email ){
    remove_all_actions( 'woocommerce_email_order_meta', 10, 4 );
}
add_action( 'woocommerce_email_order_meta', '_s_woocommerce_remove_email_order_meta', 5, 4 );


// Remove all customer details
function removing_customer_details_in_emails( $order, $sent_to_admin, $plain_text, $email ){
    $mailer = WC()->mailer();
    remove_all_actions( 'woocommerce_email_customer_details', 10, 4 );
    remove_action( 'woocommerce_email_customer_details', array( $mailer, 'customer_details' ), 10, 4 );
    remove_action( 'woocommerce_email_customer_details', array( $mailer, 'email_addresses' ), 20, 4 );
}
add_action( 'woocommerce_email_customer_details', 'removing_customer_details_in_emails', 5, 4 );
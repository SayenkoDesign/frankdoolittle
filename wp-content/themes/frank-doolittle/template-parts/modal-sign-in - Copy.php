<?php


?>

<?php
$animation_in = 'data-animation-in="hinge-in-from-middle-y fast"';
if ( isset( $_GET['login'] ) && $_GET['login'] == 'failed' ) {
    $animation_in = '';   
}
?>

<div class="sign-in reveal" id="sign-in" data-reveal data-deep-link="true" <?php echo $animation_in;?> data-animation-out="hinge-out-from-middle-y fast">
    <div class="modal-header">
        <div class="column row">
         <h2>Sign In</h2>
        <button class="close-button" data-close aria-label="Close modal" type="button"><span aria-hidden="true">&times;</span></button>
        </div>
    </div>
      
    <div class="modal-content">
        <div class="column row">
            <div class="modal-form">
                <?php
                $error = '';
                
                if ( isset( $_GET['login'] ) && $_GET['login'] == 'failed' ) {
                    
                    if( isset( $_GET['error_type'] ) ) {
                        
                        $error_type = $_GET['error_type'];
                        
                        switch( $error_type ) {
                            case 'invalid_username':
                            $error = '<strong>ERROR</strong>: Invalid username.';
                            break;
                            case 'empty_username':
                            $error = '<strong>ERROR</strong>: The username field is empty.';
                            break;
                            case 'empty_password':
                            $error = '<strong>ERROR</strong>: The password field is empty.';
                            break;
                            case 'incorrect_password':
                            $error = '<strong>ERROR</strong>: The password you entered is incorrect.';
                            break;
                            case 'both_empty':
                            $error = '<strong>ERROR</strong>: Username and password missing.';
                            break;
                            default:
                            $error = '<strong>ERROR</strong>: Login failed.';
                            break;
                        }
                         
                    }
                    
                }
                
                if( !empty( $error ) ) {
                    printf( '<div class="msg"><div class="error">%s</div></div>', $error );
                }
                
                global $wp;
                $current_url = home_url( add_query_arg( array(), $wp->request ) );
                wp_login_form( array( 'redirect' => $current_url ) );
                ?>
            </div>
         </div>
    </div>
 </div>

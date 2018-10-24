<?php


 /**
 * Disable admin bar on the frontend of your website
 * for subscribers.
 */
function _s_hide_admin_bar() {
  if (! current_user_can('edit_posts') ) {
        add_filter('show_admin_bar', '__return_false');
        remove_action('wp_head', '_admin_bar_bump_cb');
        add_action( 'wp_head', '_s_subscriber_remove_header_css' );
  }
  
}

add_action('init', '_s_hide_admin_bar');

function _s_subscriber_remove_header_css() {
?>
<style type="text/css" media="screen">
	.site-header {
        top: 0!important;   
    }
</style>
<?php
}


/**
 * Changes 'Username' to 'Email Address' on wp-admin login form
 * and the forgotten password form
 *
 * @return null
 */
function _s_login_head() {
    function _s_username_label( $translated_text, $text, $domain ) {
        if ( 'Username or Email Address' === $text || 'Username' === $text ) {
            $translated_text = __( 'Email Address' , '_s' );
        }
        return $translated_text;
    }
    add_filter( 'gettext', '_s_username_label', 20, 3 );
}
//add_action( 'login_head', '_s_login_head' );


// Custom login form
function doolittle_login_form( $args = array() ) {
	$defaults = array(
		'echo' => true,
		// Default 'redirect' value takes the user back to the request URI.
		'redirect' => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
		'form_id' => 'loginform',
		'label_username' => __( 'Username or Email Address' ),
		'label_password' => __( 'Password' ),
		'label_remember' => __( 'Remember Me' ),
		'label_log_in' => __( 'Log In' ),
		'id_username' => 'user_login',
		'id_password' => 'user_pass',
		'id_remember' => 'rememberme',
		'id_submit' => 'wp-submit',
		'remember' => true,
		'value_username' => '',
		// Set 'value_remember' to true to default the "Remember me" checkbox to checked.
		'value_remember' => false,
	);

	/**
	 * Filters the default login form output arguments.
	 *
	 * @since 3.0.0
	 *
	 * @see wp_login_form()
	 *
	 * @param array $defaults An array of default login form arguments.
	 */
	$args = wp_parse_args( $args, apply_filters( 'login_form_defaults', $defaults ) );

	/**
	 * Filters content to display at the top of the login form.
	 *
	 * The filter evaluates just following the opening form tag element.
	 *
	 * @since 3.0.0
	 *
	 * @param string $content Content to display. Default empty.
	 * @param array  $args    Array of login form arguments.
	 */
	$login_form_top = apply_filters( 'login_form_top', '', $args );

	/**
	 * Filters content to display in the middle of the login form.
	 *
	 * The filter evaluates just following the location where the 'login-password'
	 * field is displayed.
	 *
	 * @since 3.0.0
	 *
	 * @param string $content Content to display. Default empty.
	 * @param array  $args    Array of login form arguments.
	 */
	$login_form_middle = apply_filters( 'login_form_middle', '', $args );

	/**
	 * Filters content to display at the bottom of the login form.
	 *
	 * The filter evaluates just preceding the closing form tag element.
	 *
	 * @since 3.0.0
	 *
	 * @param string $content Content to display. Default empty.
	 * @param array  $args    Array of login form arguments.
	 */
	$login_form_bottom = apply_filters( 'login_form_bottom', '', $args );
    
    $form = sprintf( '<div class="gform_wrapper"><form name="%s" id="%s" action="%s" method="post">', 
                     $args['form_id'], $args['form_id'], esc_url( site_url( 'wp-login.php', 'login_post' ) ) );

	$form .= $login_form_top;
    
	$form .= '<ul class="gform_fields top_label form_sublabel_below description_below">';
    
    $form .= sprintf('<li id="field_1" class="gfield gfield_contains_required field_sublabel_below field_description_below hidden_label gfield_visibility_visible"><label class="gfield_label" for="%s">Username<span class="gfield_required">*</span></label><div class="ginput_container ginput_container_text"><input name="log" id="%s" type="text" value="%s" class="large" tabindex="1000" placeholder="%s" aria-required="true" aria-invalid="false"></div></li>',
                    esc_attr( $args['id_username'] ), 
                    esc_attr( $args['id_username'] ),
                    esc_html( $args['value_username'] ), 
                    esc_html( $args['label_username'] ) );
                    
	$form .= sprintf('<li id="field_1" class="gfield gfield_contains_required field_sublabel_below field_description_below hidden_label gfield_visibility_visible"><label class="gfield_label" for="%s">Password<span class="gfield_required">*</span></label><div class="ginput_container ginput_container_text"><input name="pwd" id="%s" type="password" value="" class="large" tabindex="1000" placeholder="%s" aria-required="true" aria-invalid="false"></div></li>',
                    esc_attr( $args['id_password'] ), 
                    esc_attr( $args['id_password'] ),
                    esc_html( $args['label_password'] ) );		
            
     
     
     $form .= '<li class="gfield login-remember">';    
     
     if( $args['remember'] ) {
         $form .= sprintf( '<div><label><input name="rememberme" type="checkbox" id="%s" value="forever" %s />%s</label></div>', 
         esc_attr( $args['id_remember'] ), 
         $args['value_remember'] ? ' checked="checked"' : '', 
         esc_html( $args['label_remember'] ) );
     }
     
     $form .= $login_form_middle; 
     
     $form .= '</li>';
     
     $form .= sprintf( '<li class="login-submit"><input type="submit" name="wp-submit" id="%s" class="button button-primary" value="%s" /><input type="hidden" name="redirect_to" value="%s" /></li>', 
                       esc_attr( $args['id_submit'] ), 
                       esc_attr( $args['label_log_in'] ),
                       esc_url( $args['redirect'] ) );
         
			
	$form .= $login_form_bottom;
	
    $form .= '</form></div>';

	if ( $args['echo'] )
		echo $form;
	else
		return $form;
}


/**
 * Redirect non-admins to the homepage after logging into the site.
 *
 * @since 	1.0
 */
function doolittle_login_redirect( $redirect_to, $request, $user  ) {
    
    if( is_wp_error($user) ) {
        return home_url();
    }
    
	return ( is_array( $user->roles ) && in_array( 'administrator', $user->roles ) ) ? admin_url() : home_url();
}
add_filter( 'login_redirect', 'doolittle_login_redirect', 10, 3 );


/**
 * Get current user role
 *
 * @since 	1.0
 */
function doolittle_get_user_role( $user = null ) {
	$user = $user ? new WP_User( $user ) : wp_get_current_user();
	return $user->roles ? array_values( $user->roles )[0] : false;
}

/**
 * Find out if they are a user. We do it this way so that anyone that can read can see content.
 *
 * @since 	1.0
 */
function doolittle_is_member() {
	return current_user_can( 'read' ) ? true : false;
}


function doolittle_reset_password_shortcode( $attr, $content = null ) {
    
    ob_start( );
    ?>
    <form method="post" action="<?php echo wp_lostpassword_url() ?>" class="wp-user-form">
        <div class="username">
            <label for="user_login" class="hide"><?php _e('Username or Email'); ?>: </label>
            <input type="text" name="user_login" value="" size="20" id="user_login" tabindex="1001" />
        </div>
        <div class="login_fields">
            <?php do_action('login_form', 'resetpass'); ?>
            <input type="submit" name="user-submit" value="<?php _e('Reset my password'); ?>" class="user-submit" tabindex="1002" />
         </div>
    </form>
    <?php
    $output = ob_get_clean( );
    
    return do_shortcode( $output );
     
}

add_shortcode( 'doolittle_lost_password_form', 'doolittle_reset_password_shortcode' );



/**
 * Display user info
 *
 * @since  0.1.0
 * @access public
 * @param  array   $attr
 * @return string
 */
function doolittle_user_info( $atts = '' ) {
	
	$a = shortcode_atts( array(
        'value' => 'user_firstname',
    ), $atts );
	
	if( is_user_logged_in() ) {
		// Hello [username]! My Account (link to profile) 
		$user = wp_get_current_user();
		$detail = $a['value'];
		return 	( isset( $user->{$detail} ) ) ? $user->{$detail} : 'User';
	}
	
	return 'User';	
}

add_shortcode( 'doolittle_user_info', 'doolittle_user_info' );



/**
 * Display logout link
 *
 * @since  0.1.0
 * @access public
 * @return string
 */
function doolittle_logout_url() {
	return sprintf('<a href="%s">Logout</a>', wp_logout_url( site_url() ) );
}

add_shortcode( 'doolittle-logout', 'doolittle_logout_url' );



function doolittle_show_members_only_message() {
	if( !is_user_logged_in() )
		return doolittle_members_only_message();	
}

add_shortcode( 'doolittle_show_members_only_message', 'doolittle_show_members_only_message' );
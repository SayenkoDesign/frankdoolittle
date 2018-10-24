<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package _s
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<link rel="dns-prefetch" href="//fonts.googleapis.com">

<link rel="apple-touch-icon" sizes="60x60" href="<?php echo THEME_FAVICONS;?>/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo THEME_FAVICONS;?>/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo THEME_FAVICONS;?>/favicon-16x16.png">
<link rel="manifest" href="<?php echo THEME_FAVICONS;?>/manifest.json">
<link rel="mask-icon" href="<?php echo THEME_FAVICONS;?>/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#ffffff">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

 
<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', '_s' ); ?></a>

<header id="masthead" class="site-header" role="banner">
    <div class="wrap">
    <div class="pre-header">
        <div class="row">
            <div class="columns large-6 sign-in-area">
                <?php
                // Does user have any orders?
                //$orders = _s_count_orders_by_user(); 
                
                if( is_user_logged_in() ) {
                    printf( '<span>%s %s!</span> | <a href="%s" class="profile-link">%s</a>%s | %s',
                            __( 'Hello' , '_s' ), 
                            doolittle_user_info( 'user_firstname' ), // use user_login or user_firstname
                            get_permalink( 43 ), // My Account
                            __( 'My Account' , '_s' ), 
                            show_orders_link(),
                            doolittle_logout_url()
                     );
                }
                else {
                    print( '<a data-open="modal-sign-in">Sign In</a> | <a data-open="modal-create-account">Create Account</a>' );
                }
                ?>
            </div>
            <div class="columns large-6 phone">
                <?php
                $phone      = get_field( 'phone', 'option' );
                     
                if( !empty( $phone ) ) {
                    $number = _s_convert_phone_to_tel( $phone );
                    printf( '<a href="%s">%s</a>', $number, $phone );
                }
                ?>
            </div>
        </div>
    </div>
    <div class="row mid-header">
        <div class="columns small-4 medium-4 large-4">
            <div class="members-logo">
                    <span>
                        <img src="<?php echo THEME_IMG;?>/header/header-left-logo.svg" />
                    </span>
                    <?php
                        printf( '<div class="show-for-large">%s</div>', get_search_form( false ) );
                     ?>
                     
            </div>
            <div class="mobile-phone">
            <?php
            $phone = get_field( 'phone', 'option' );
                     
            if( !empty( $phone ) ) {
                $number = _s_convert_phone_to_tel( $phone );
                printf( '<a href="%s">%s</a>', $number, get_svg( 'phone-mobile' ) );
            }
            ?>
            </div>
        </div>
        <div class="columns small-4 medium-4 large-4">
            <div class="site-branding">
                <div class="site-title">
                <?php
                $site_url = home_url();
                printf('<a href="%s" title="%s"><img src="%s" alt="%s"/></a>',
                        $site_url, get_bloginfo( 'name' ), THEME_IMG .'/logo.svg', get_bloginfo( 'name' ) );
                ?>
                </div>
            </div><!-- .site-branding -->
        </div>
        <div class="columns large-4 show-for-large favorites-and-quotes">
            
            <a data-open="modal-favorites" class="favorites">
                <p>Favorites</p>
                <div class="icon">
                    <?php
                    $favorite_count = _s_get_favorites_count();
                    // Set count to empty so we can hide with CSS
                    $favorite_count = $favorite_count ? $favorite_count : '';
                    printf( '<span class="number">%s</span>', $favorite_count );
                    ?>
                </div>
            </a>
            
            
             <a href="<?php the_permalink( 33 );?>" class="quotes">
                <?php
                $quote_count = _s_get_quotes_count();
                // Set quote count to empty so we can hide with CSS
                $quote_count = $quote_count ? $quote_count : '';
                $quote_text = $quote_count ? 'My Quote' : 'Build Quote';
                printf( '<p>%s</p>', $quote_text );
                ?>
                <div class="icon">
                    <?php
                    printf( '<span class="number">%s</span>', $quote_count );
                    ?>
                </div>
             </a>
             
        </div>
        
        <button type="button" class="menu-toggle hide-for-large" data-toggle="offCanvas"><?php echo get_svg('icon-menu');?></button>
    </div>
    <div class="row column nav-row">
        <nav id="site-navigation show-for-large" class="nav-primary" role="navigation">
            <?php
                
                $args = array(
                    'theme_location' => 'primary',
                    'menu' => 'Primary Menu',
                    'container' => 'false',
                    'container_class' => '',
                    'container_id' => '',
                    'menu_id'        => 'primary-menu',
                    'menu_class'     => 'dropdown menu mega-menu',
                    'before' => '',
                    'after' => '',
                    'link_before' => '',
                    'link_after' => '',
                    'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    'depth' => 0,
                    'walker' => new Mega_Menu_Walker()
                );
                wp_nav_menu($args);
            ?>
        </nav><!-- #site-navigation -->
    </div>
    </div><!-- wrap -->
</header><!-- #masthead -->
    
<div id="page" class="site-container">

	<div id="content" class="site-content">
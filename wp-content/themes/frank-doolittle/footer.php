<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package _s
 */
?>

</div><!-- #content -->


<?php
 // Footer functions located inside: theme.php
?>

<div class="footer-widgets">
    <div class="wrap">
        <div class="row">

            <div class="medium-12 large-6 columns contact-info">
               <div class="row">
                  <div class="small-12 columns">
                     <h3 class="account-specialist">Talk to an account specialist now!</h3>
                  </div>
                  <div class="small-12 medium-6 large-6 columns">
                    <?php
                    $hours      = get_field( 'hours', 'option' );
                    $address    = get_field( 'address', 'option' );
                     
                    echo _s_get_textarea( $hours, array( 'class' => 'company-hours' ) );
                    echo _s_get_textarea( $address, array( 'class' => 'company-location' ) );
                    ?>
                    
                  </div>
                  <div class="small-12 medium-6 large-6 columns">
                     <?php
                     $phone      = get_field( 'phone', 'option' );
                     $fax        = get_field( 'fax', 'option' );
                     $email      = get_field( 'email', 'option' );
                     
                     if( !empty( $phone ) ) {
                         $number = _s_convert_phone_to_tel( $phone );
                         $phone = sprintf( '%s <a href="%s">%s</a>', get_svg( 'phone' ), $number, $phone );
                         echo _s_get_textarea( $phone, array( 'class' => 'contact-icons' ) );
                     }
                     
                     if( !empty( $fax ) ) {
                         $fax = sprintf( '%s %s', get_svg( 'fax' ), $fax );
                         echo _s_get_textarea( $fax, array( 'class' => 'contact-icons' ) );
                     }
                     
                     if( !empty( $email ) ) {
                         $email_address = sprintf( 'mailto:%s', antispambot( $email ) );
                         $email = sprintf( '%s <a href="%s">Send an email</a>', get_svg( 'email' ), $email_address );
                         echo _s_get_textarea( $email, array( 'class' => 'contact-icons' ) );
                     }
                     ?>
                   </div>
               </div>
            </div>

            <div class="medium-12 large-6 columns stay-connected">

               <div class="social">
                  <h3>Stay Connected!</h3>
                  <?php
                  echo _s_get_social_icons();
                  ?>
               </div>
                <div class="widget">
                 <?php
                 if ( is_active_sidebar( 'footer' ) ) :

                     $attr = array( 'id' => 'footer-newsletter', 'class' => 'section footer-newsletter' );

                     _s_section_open( $attr );

                         print( '<div class="">' );

                         dynamic_sidebar( 'footer' );

                         echo '</div>';

                     _s_section_close();

                 endif;
                 ?>
                </div>
            </div>

        </div><!-- row  -->

	</div><!-- wrap -->
</div>

<div class="footer-logo">
  <div class="wrap">
    <div class="row columns small-12">
      <img class="center-block" src="<?php echo THEME_IMG;?>/footer-logo.svg" />
    </div>
  </div>
</div>

<footer id="colophon" class="site-footer" role="contentinfo">

    <div class="wrap">
      <div class="footer-main">

      </div>
      <div class="subfooter">
          <div class="column row">

              <?php
              footer_copyright();
              function footer_copyright() {
                  $menu = '';

                  if ( has_nav_menu( 'copyright' ) ) {
                      /* $args = array(
                          'theme_location' => 'copyright',
                          'container' => false,
                          'container_class' => '',
                          'container_id' => '',
                          'menu_id'        => 'copyright-menu',
                          'menu_class'     => 'menu',
                          'before' => '',
                          'after' => '',
                          'link_before' => '',
                          'link_after' => '',
                          'depth' => 0,
                          'echo' => false
                      ); */
                      //$menu = sprintf( '<span class="links">%s</span>', strip_tags( wp_nav_menu($args), '<a>' ) );
                  }

                  $credit = sprintf( '<span><a href="%1$s">Seattle Web Design</a> by <a href="%1$s">Sayenko Design.</a></span>', 'https://www.sayenkodesign.com/' );

                  printf( '<p>&copy; 2017 The Frank Doolittle Company. All rights reserved.  Cookie Policy  |  Privacy Policy %s %s</p>', date( 'Y' ), $credit, $menu );

              }
              ?>

             </div>
           </div>
    </div>

 </footer><!-- #colophon -->

<!-- </div> -->
<?php
// Load modals

get_template_part( 'template-parts/off-canvas', 'menu' );
  
get_template_part( 'template-parts/modal', 'contact' );

get_template_part( 'template-parts/modal', 'create-account' );

get_template_part( 'template-parts/modal', 'sign-up' );

get_template_part( 'template-parts/modal', 'sign-in' );

get_template_part( 'template-parts/modal', 'favorites' );

?>


<?php wp_footer(); ?>
 
</body>
</html>

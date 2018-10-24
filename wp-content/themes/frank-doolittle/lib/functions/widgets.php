<?php
// Example: change sidebar params

/* function _s_widget_display_callback($instance, $widget, $args) {

  if ( strpos( $args['id'], 'cs-' ) === FALSE ) {
	  return $instance;
  }

  $args['before_widget'] = sprintf( '<aside class="widget %s">', $widget->widget_options['classname'] );
  $args['after_widget'] = '</aside>';
  $args['before_title'] = '<h3 class="widget-title">';
  $args['after_title'] = '</h3>';

  $widget->widget($args, $instance);

  return false;
} */

//add_filter( 'widget_display_callback', '_s_widget_display_callback', 10, 3 );


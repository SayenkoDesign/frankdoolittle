<?php

add_action( 'admin_menu', 'design_cpt_menu' );
 
//Create an admin menu which will also create a blank admin page
function design_cpt_menu() {
	add_submenu_page( 'edit.php?post_type=doolittle_design', 'Custom Design Import', 'Custom Design Import', 'manage_options', 'custom-design-import', 'custom_design_import' );
}
 
//The main function which controls the output on the admin page
function custom_design_import() {
 
	gravity_form_enqueue_scripts( 1, true ); //Enqueue form scripts (only for form with ID of 1)
 
	gravity_form( 1, false, true, false, null, false ); //Output a form with an ID of 1
 
}
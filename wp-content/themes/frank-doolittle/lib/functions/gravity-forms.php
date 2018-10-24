<?php

// Turn on label visibility
add_filter( 'gform_enable_field_label_visibility_settings', '__return_true' );

// On submit scroll back down to form
add_filter( 'gform_confirmation_anchor', '__return_true' );
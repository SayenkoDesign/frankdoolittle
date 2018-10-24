<?php

//add_filter('relevanssi_hits_filter', 'separate_result_types');
/*
function separate_result_types($hits) {
    $types = array();
    $types['product'] = array();
    $types['doolittle_design'] = array();
 
    // Split the post types in array $types
    if (!empty($hits)) {
        foreach ($hits[0] as $hit) {
            if (!is_array($types[$hit->post_type])) $types[$hit->post_type] = array();                        
            array_push($types[$hit->post_type], $hit);
        }
    }
 
    // Merge back to $hits in the desired order
    $hits[0] = array_merge($types['product'], $types['doolittle_design']);
    return $hits;
}*/
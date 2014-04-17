<?php

/**
 * Metabox for Promo Module
 */
function tc_promo_module_metaboxes($meta_boxes) {
  $prefix = '_pm_'; // Prefix for all fields

  /* additional_attr metabox */
  $meta_boxes ['promo_module_metabox'] = array (
    'id' => 'promo_module_metaboxes',
    'title' => 'Image Banners',
    'pages' => array ('promo'), // promo module post type
    'context' => 'normal',
    'priority' => 'high',
    'show_names' => true, // Show field names on the left
    'fields' => array (
      array (
        'name' => 'Super Leaderboard Banner',
        'id' => $prefix . 'leaderboard',
        'type' => 'file'
      ),
      array (
        'name' => 'Medium Rectangle Banner',
        'id' => $prefix . 'rectangle',
        'type' => 'file'
      )
    )
  );

  return $meta_boxes;
}
add_filter ( 'cmb_meta_boxes', 'tc_promo_module_metaboxes' );


// Initialize the metabox class
add_action ( 'init', 'tc_initialize_cmb_meta_boxes', 9999 );
function tc_initialize_cmb_meta_boxes() {
  if (! class_exists ( 'cmb_Meta_Box' )) {
    require_once ('metabox/init.php');
  }
}
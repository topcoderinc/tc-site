<?php

/* Promo Module Post Type */
add_action('init', 'promo_register');
function promo_register() {
  $strPostName = 'Promo Module';

  $labels = array(
    'name' => _x($strPostName . 's', 'post type general name'),
    'singular_name' => _x($strPostName, 'post type singular name'),
    'add_new' => _x('Add New', $strPostName . ' Post'),
    'add_new_item' => __('Add New ' . $strPostName . ' Post'),
    'edit_item' => __('Edit ' . $strPostName . ' Post'),
    'new_item' => __('New ' . $strPostName . ' Post'),
    'view_item' => __('View ' . $strPostName . ' Post'),
    'search_items' => __('Search ' . $strPostName),
    'not_found' => __('Nothing found'),
    'not_found_in_trash' => __('Nothing found in Trash'),
    'parent_item_colon' => ''
  );

  $args = array(
    'labels' => $labels,
    'public' => TRUE,
    'publicly_queryable' => TRUE,
    'show_ui' => TRUE,
    'query_var' => TRUE,
    'rewrite' => TRUE,
    'capability_type' => 'post',
    'hierarchical' => FALSE,
    'menu_position' => 5,
    'exclude_from_search' => FALSE,
    'show_in_nav_menus' => TRUE,
    'taxonomies' => array(
      'category'
    ),
    'supports' => array(
      'title',
      'editor',
      'thumbnail',
      'page-attributes'
    )
  );

  register_post_type('promo', $args);
  $strPostName = 'Blog';

  $labels = array(
    'name' => _x($strPostName . 's', 'post type general name'),
    'singular_name' => _x($strPostName, 'post type singular name'),
    'add_new' => _x('Add New', $strPostName . ' Post'),
    'add_new_item' => __('Add New ' . $strPostName . ' Post'),
    'edit_item' => __('Edit ' . $strPostName . ' Post'),
    'new_item' => __('New ' . $strPostName . ' Post'),
    'view_item' => __('View ' . $strPostName . ' Post'),
    'search_items' => __('Search ' . $strPostName),
    'not_found' => __('Nothing found'),
    'not_found_in_trash' => __('Nothing found in Trash'),
    'parent_item_colon' => ''
  );

  $args = array(
    'labels' => $labels,
    'public' => TRUE,
    'publicly_queryable' => TRUE,
    'show_ui' => TRUE,
    'query_var' => TRUE,
    'rewrite' => TRUE,
    'capability_type' => 'post',
    'hierarchical' => FALSE,
    'menu_position' => 5,
    'exclude_from_search' => FALSE,
    'show_in_nav_menus' => TRUE,
    'taxonomies' => array('category', 'post_tag'),
    'supports' => array(
      'title',
      'editor',
      'thumbnail',
      'custom-fields',
      'tags',
      'comments'

    )
  );

  register_post_type(BLOG, $args);
  add_post_type_support(BLOG, 'author');
}

/**
 * Metabox for Promo Module 
 */
function promo_module_metaboxes($meta_boxes) {
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
						),
      array (
        'name' => 'URL Link',
        'id' => $prefix . 'link',
        'type' => 'text'
      )
				) 
		);
	
	return $meta_boxes;
}
add_filter ( 'cmb_meta_boxes', 'promo_module_metaboxes' );


/**
 * Metabox for Referral Page
 */
function tc_referral_metaboxes($meta_boxes) {
  $prefix = '_tc_'; // Prefix for all fields

  /* additional_attr metabox */
  $meta_boxes ['referral_metabox'] = array (
    'id' => 'promo_module_metaboxes',
    'title' => 'Image Banners',
    'pages' => array ('page'), 
				'show_on' => array( 'key' => 'page-template', 'value' => 'page-referral.php' ),
    'context' => 'normal',
    'priority' => 'high',
    'show_names' => true, // Show field names on the left
    'fields' => array (
      array (
        'name' => 'Referral Base URL',
        'id' => $prefix . 'base_url',
        'type' => 'text'
      ),
      array (
        'name' => 'Text Snippet',
        'id' => $prefix . 'text_snippet',
        'type' => 'textarea'
      )
    )
  );

  return $meta_boxes;
}
add_filter ( 'cmb_meta_boxes', 'tc_referral_metaboxes' );


// Initialize the metabox class
add_action ( 'init', 'be_initialize_cmb_meta_boxes', 9999 );
function be_initialize_cmb_meta_boxes() {
		if (! class_exists ( 'cmb_Meta_Box' )) {
				locate_template('metabox/init.php', true);
		}
}

/* Case studies Module Post Type */
add_action('init', 'case_studies_register');
function case_studies_register() {
  $strPostName = 'Case Studies';

  $labels = array(
    'name' => _x($strPostName . 's', 'post type general name'),
    'singular_name' => _x($strPostName, 'post type singular name'),
    'add_new' => _x('Add New', $strPostName . ' Post'),
    'add_new_item' => __('Add New ' . $strPostName . ' Post'),
    'edit_item' => __('Edit ' . $strPostName . ' Post'),
    'new_item' => __('New ' . $strPostName . ' Post'),
    'view_item' => __('View ' . $strPostName . ' Post'),
    'search_items' => __('Search ' . $strPostName),
    'not_found' => __('Nothing found'),
    'not_found_in_trash' => __('Nothing found in Trash'),
    'parent_item_colon' => ''
  );

  $args = array(
    'labels' => $labels,
    'public' => TRUE,
    'publicly_queryable' => TRUE,
    'show_ui' => TRUE,
    'query_var' => TRUE,
    'rewrite' => TRUE,
    'capability_type' => 'post',
    'hierarchical' => FALSE,
    'menu_position' => 5,
    'exclude_from_search' => FALSE,
    'show_in_nav_menus' => TRUE,
    'taxonomies' => array('category', 'post_tag'),
    'supports' => array(
      'title',
      'editor',
      'thumbnail',
      'custom-fields',
      'tags',
      'comments'

    )
  );

  register_post_type('case-studies', $args);
}
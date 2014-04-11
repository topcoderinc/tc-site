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
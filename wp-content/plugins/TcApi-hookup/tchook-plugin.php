<?php
/*
Plugin Name: TC API Hookup
Description:  Plugin(s) with classes for TC API hooking up
Version: 1.0
Author: hi4sandy,evilkyro1965
Author URI:  http://www.topcoder.com
*/

define( 'TCHOOK_PLUGIN_PATH', dirname( __FILE__ ) );
define( 'TCHOOK_PLUGIN_URL', plugins_url( '', __FILE__ ) );
define( 'TCHOOK_PLUGIN_FILE', plugin_basename( __FILE__ ) );
define( 'TCHOOK_PLUGIN_INC', TCHOOK_PLUGIN_PATH . '/includes' );

require_once( TCHOOK_PLUGIN_INC . '/plugin.php' );
require_once( TCHOOK_PLUGIN_INC . '/config.php' );
require_once( TCHOOK_PLUGIN_INC . '/ajax_func.php' );
require_once( TCHOOK_PLUGIN_INC . '/shortcode.php' );
require_once( TCHOOK_PLUGIN_INC . '/widget.php' );

/* includes */
require_once ( TCHOOK_PLUGIN_INC .'/shotcodes/tc_ratings_chart_ds.php');
require_once ( TCHOOK_PLUGIN_INC .'/shotcodes/tc_ratings_chart_dev.php');
require_once ( TCHOOK_PLUGIN_INC .'/shotcodes/tc_rating_color.php');


$TCHOOK_class = 'TCHOOK_';

if ( is_admin() ) {
    $TCHOOK_class .= 'Admin';
    require_once( TCHOOK_PLUGIN_INC . '/admin.php' );
} else {
    $TCHOOK_class .= 'Public';
    require_once( TCHOOK_PLUGIN_INC . '/theme-functions.php' );
    require_once( TCHOOK_PLUGIN_INC . '/public.php' );
}

$TCHOOK_config_data = array(
    'plugin_file' => TCHOOK_PLUGIN_FILE,
);

$TCHOOK_plugin = new $TCHOOK_class( new TCHOOK_Config( $TCHOOK_config_data ) );

unset( $TCHOOK_class, $TCHOOK_config_data );

add_action ( 'init', 'create_post_types_plugin' );

function create_post_types_plugin() {
	register_post_type ( 'stars-of-month', array (
		'labels' => array (
				'name' => __ ( 'stars-of-month' ),
				'singular_name' => __ ( 'stars-of-month' ),
				'add_new' => _x ( 'Add New', 'Add New' ),
				'add_new_item' => __ ( 'Add New Stars Of Month' ),
				'edit_item' => __ ( 'Edit  Stars Of Month' ),
				'new_item' => __ ( 'new  Stars Of Month' ),
				'view_item' => __ ( 'View  Stars Of Month' ),
				'search_item' => __ ( 'Search  Stars Of Months' ),
				'not_found' => __ ( 'No  Stars Of Month found' ),
				'menu_name' => __ ( 'Stars Of Month' ) 
		),
		'public' => true,
		'has_archive' => true,
		'taxonomies' => array (
				'post_tag',
				'category'  
		),
		'supports' => array (
				'title',
				'editor',
				'page-attributes',
				'author',
				'thumbnail',
				'excerpt',
				'comments',
				'custom-fields',
				'page-attributes' 	
		) 
	) );
}

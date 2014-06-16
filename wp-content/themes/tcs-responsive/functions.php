<?php

// Define once because it's faster
define('THEME_URL', get_template_directory_uri());
define('CURRENT_FULL_URL', add_query_arg($wp->query_string, '', home_url($wp->request)));

@ini_set('display_errors', 1);

define("BLOG", "blog");

locate_template('config/env.php', TRUE);

if (!defined('TC_API_URL')) {
  define('TC_API_URL', 'https://api.topcoder.com/v2');
}

locate_template('lib/scripts.php', TRUE);
locate_template('lib/widget.php', TRUE);
locate_template("config/rewrite-config.php", TRUE);
locate_template('lib/rewrite_rules.php', TRUE);
locate_template('lib/options.php', TRUE);
locate_template('lib/sidebars.php', TRUE);
locate_template('lib/menu.php', TRUE);
locate_template('lib/comments.php', TRUE);
locate_template('lib/meta.php', TRUE);
locate_template('lib/share.php', TRUE);
locate_template("lib/rss.php", TRUE);
locate_template("lib/post_types.php", TRUE);
locate_template("lib/ajax.php", TRUE);
locate_template("lib/images.php", TRUE);
locate_template("lib/member-profile/config.php", TRUE);

if (!empty($skipAdminSSL)) {
  force_ssl_admin(false);
}

// enables tags on pages
function tags_support_all() {
  register_taxonomy_for_object_type('post_tag', 'page');
}

add_action('init', 'tags_support_all');

date_default_timezone_set(get_option('timezone_string'));

function get_rel_url($url, $force = FALSE) {
  if (!strstr($url, $_SERVER['HTTP_HOST']) && !$force) {
    return $url;
  }
  $url_info = parse_url($url);
  $link = $url_info['path'];
  if (isset($url_info['query']) && strlen($url_info['query'])) {
    $link .= '?' . $url_info['query'];
  }
  return $link;
}

if( strpos(CURRENT_FULL_URL,ACTIVE_CONTESTS_PERMALINK) !== false ||
	strpos(CURRENT_FULL_URL,PAST_CONTESTS_PERMALINK) !== false ||
	strpos(CURRENT_FULL_URL,REVIEW_OPPORTUNITIES_PERMALINK) !== false )
{
	if( strpos(CURRENT_FULL_URL,"%20") !== false ) {
		$redirectUrl = str_replace("%20", "_", CURRENT_FULL_URL);
		$redirectString = "Location: $redirectUrl";
		print_r($redirectString);
		header($redirectString);
		exit;
	}
}

/**
 * Get the current full url
 *
 * @deprecated use CURRENT_FULL_URL
 *
 * @return string
 */
function curPageURL() {
  return CURRENT_FULL_URL;
}

function get_page_link_by_slug($page_slug) {
  $page = get_page_by_path($page_slug);
  if ($page) :
    return get_permalink($page->ID);
  else :
    return "#";
  endif;
}

function wpb_set_post_views($postID) {
  $count_key = 'wpb_post_views_count';
  $count = get_post_meta($postID, $count_key, TRUE);
  if ($count == '') {
    $count = 0;
    delete_post_meta($postID, $count_key);
    add_post_meta($postID, $count_key, '0');
  }
  else {
    $count++;
    update_post_meta($postID, $count_key, $count);
  }
}


function wpb_track_post_views($post_id) {
  if (!is_single()) {
    return;
  }
  if (empty ($post_id)) {
    global $post;
    $post_id = $post->ID;
  }
  wpb_set_post_views($post_id);
}

add_action('wp_head', 'wpb_track_post_views');
/**
 * add filter for query_vars
 */
function tcapi_query_vars($query_vars) {
  $query_vars [] = 'contest_type';
  $query_vars [] = 'contestID';
  $query_vars [] = 'page';
  $query_vars [] = 'pages';
  $query_vars [] = 'post_per_page';
  $query_vars [] = 'handle';
  $query_vars [] = 'slug';
  $query_vars [] = 'tab';
  $query_vars [] = 'ct';
  $query_vars [] = 'list';
  $query_vars [] = 'contestType';
  $query_vars [] = 'pageNumber';
  $query_vars [] = 'role';
  $query_vars [] = 'termsOfUseID';
  $query_vars [] = 'autoRegister';
  $query_vars [] = 'type';
  $query_vars [] = 'nocache';
  $query_vars [] = 'challenge-type';
  return $query_vars;
}

add_filter('query_vars', 'tcapi_query_vars');


/* commonly used functions
 -----------------------------------*/
/* excerpt */
function new_excerpt_more($more) {
  return '...<br/>' . '<a href="' . get_permalink(get_the_ID()) . '" class="more">Read More</a>';
}

add_filter('excerpt_more', 'new_excerpt_more');

function custom_excerpt_length($length) {
  return 27;
}

add_filter('excerpt_length', 'custom_excerpt_length', 999);

function custom_excerpt($new_length = 20, $new_more = '...') {
  add_filter(
    'excerpt_length',
    function () use ($new_length) {
      return $new_length;
    },
    999
  );
  add_filter(
    'excerpt_more',
    function () use ($new_more) {
      return $new_more;
    }
  );
  $output = get_the_excerpt();
  $output = apply_filters('wptexturize', $output);
  $output = apply_filters('convert_chars', $output);
  $output = $output;
  echo $output;
}

function custom_content($new_length = 55) {
  $output = get_the_content();
  $output = apply_filters('wptexturize', $output);
  $output = substr($output, 0, $new_length);
  return $output;
}

/* singnup function from given theme */
function get_cookie() {
  global $_COOKIE;
  // $_COOKIE['main_user_id_1'] = '22760600|2c3a1c1487520d9aaf15917189d5864';
  $hid = explode("|", $_COOKIE ['main_tcsso_1']);
  $handleName = $_COOKIE ['handleName'];
  // print_r($hid);
  $hname = explode("|", $_COOKIE ['direct_sso_user_id_1']);
  $meta = new stdclass ();
  $meta->handle_id = $hid [0];
  $meta->handle_name = $handleName;
  return $meta;
}

// add menu support
add_theme_support('menus');

remove_filter('the_content', 'wpautop');

function fixIERoundedCorder() {
  $pieHtcLocation = get_bloginfo('stylesheet_directory') . "/css/PIE.htc";
  ?>
  <style>
    .btn, a.btn, .blogCategoryMenu a, .searchBox input, .subscribeBox input {
      behavior: url("<?php echo $pieHtcLocation;?>");
    }
  </style>
<?php
}

function get_user_browser() {
  $u_agent = $_SERVER['HTTP_USER_AGENT'];
  $ub = '';
  if (preg_match('/MSIE/i', $u_agent)) {
    $ub = "ie";
  }
  elseif (preg_match('/Firefox/i', $u_agent)) {
    $ub = "firefox";
  }
  elseif (preg_match('/Safari/i', $u_agent)) {
    $ub = "safari";
  }
  elseif (preg_match('/Chrome/i', $u_agent)) {
    $ub = "chrome";
  }
  elseif (preg_match('/Flock/i', $u_agent)) {
    $ub = "flock";
  }
  elseif (preg_match('/Opera/i', $u_agent)) {
    $ub = "opera";
  }

  return $ub;
}

/**
 * wrap content to $len length content, and add '...' to end of wrapped conent
 */
function wrap_content_strip_html($content, $len, $strip_html = FALSE, $sp = '\n\r', $ending = '...') {
  if ($strip_html) {
    $content = strip_tags($content);
    $content = strip_shortcodes($content);
  }
  $c_title_wrapped = wordwrap($content, $len, $sp);
  $w_title = explode($sp, $c_title_wrapped);
  if (strlen($content) <= $len) {
    $ending = '';
  }
  return $w_title[0] . $ending;
}

/* get page id by slug */
function get_ID_by_slug($page_slug) {
  $page = get_page_by_path($page_slug);
  if ($page) {
    return $page->ID;
  }
  else {
    return NULL;
  }
}

/* function convert category slug to category id  */
function getCategoryId($slug) {
  $idObj = get_category_by_slug($slug);
  $id = $idObj->term_id;
  return $id;
}

/* function to stop WordPress from hitting home page when search keyword is empty */
function empty_search_filter($query) {
    // If 's' request variable is set but empty
    if (isset($_GET['s']) && empty($_GET['s']) && $query->is_main_query()){
        $query->is_search = true;
        $query->is_home = false;
    }
    return $query;
}

/* function to get auth0 client ID string */
function auth0_clientID() {
  return defined('CONFIG_AUTH0_CLIENTID') ? CONFIG_AUTH0_CLIENTID : '6ZwZEUo2ZK4c50aLPpgupeg5v2Ffxp9P';
}

add_filter('pre_get_posts','empty_search_filter');

/*function to format text into twitter post*/
function createTwitterPost($text, $permalink) {
  return "http://twitter.com/home?status=" . str_replace('%0A','',urlencode(wrap_content_strip_html(wpautop($text), 100, true,'\n\r')) . " via @topcoder " . $permalink);
}

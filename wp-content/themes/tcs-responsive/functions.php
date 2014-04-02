<?php

// Define once because it's faster
define('THEME_URL', get_template_directory_uri());

@ini_set('display_errors', 1);
require_once 'auth0/vendor/autoload.php';
require_once 'auth0/src/Auth0.php';
require_once 'auth0/vendor/adoy/oauth2/vendor/autoload.php';
require_once 'auth0/client/config.php';
define("auth0_domain",$auth0_cfg['domain']);
define("auth0_client_id",$auth0_cfg['client_id']);
define("auth0_redirect_uri",$auth0_cfg['redirect_uri']);

//define("auth0_state",$auth0_cfg['state']);

define("BLOG", "blog");

locate_template('config/env.php', TRUE);
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


// add featured image
add_theme_support ( 'post-thumbnails' );
if (function_exists('add_theme_support')) {
  add_theme_support('post-thumbnails');
  set_post_thumbnail_size(55, 55); // default Post Thumbnail dimensions
}
if (function_exists('add_image_size')) {
  add_image_size('blog-thumb', 158, 154, TRUE);
  add_image_size('blog-thumb-mobile', 300, 165);
}

// enables tags on pages
function tags_support_all() {
  register_taxonomy_for_object_type('post_tag', 'page');
}

add_action('init', 'tags_support_all');

//locate_template("lib/rss.php", TRUE);
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
$currUrl = curPageURL();
if( strpos($currUrl,ACTIVE_CONTESTS_PERMALINK) !== false ||
	strpos($currUrl,PAST_CONTESTS_PERMALINK) !== false ||
	strpos($currUrl,REVIEW_OPPORTUNITIES_PERMALINK) !== false )
{
	if( strpos($currUrl,"%20") !== false ) {
		$redirectUrl = str_replace("%20", "_", $currUrl);
		$redirectString = "Location: $redirectUrl";
		print_r($redirectString);
		header($redirectString);
		exit;
	}
}




class fixImageMargins {
  public $xs = 0; //change this to change the amount of extra spacing

  public function __construct() {
    add_filter('img_caption_shortcode', array(&$this, 'fixme'), 10, 3);
  }

  public function fixme($x = NULL, $attr, $content) {

    extract(
      shortcode_atts(
        array(
          'id' => '',
          'align' => 'alignnone',
          'width' => '',
          'caption' => ''
        ),
        $attr
      )
    );

    if (1 > (int) $width || empty($caption)) {
      return $content;
    }

    if ($id) {
      $id = 'id="' . $id . '" ';
    }

    return '<div ' . $id . 'class="wp-caption ' . $align . '" style="width: ' . ((int) $width + $this->xs) . 'px">'
    . $content . '<p class="wp-caption-text">' . $caption . '</p></div>';
  }
}

$fixImageMargins = new fixImageMargins();

function curPageURL() {
  $pageURL = 'http';
  if (isset($_SERVER["HTTPS"]) &&  $_SERVER["HTTPS"] == "on") {
    $pageURL .= "s";
  }
  $pageURL .= "://";
  if ($_SERVER["SERVER_PORT"] != "80") {
    $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
  }
  else {
    $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
  }
  return $pageURL;
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
  $output = substr($output, 0, $new_length) . '...';
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

add_action('wp_ajax_get_blog_ajax', 'get_blog_ajax');
add_action('wp_ajax_nopriv_get_blog_ajax', 'get_blog_ajax');
function get_blog_ajax() {
  $postPerPage = get_option("posts_per_page") == "" ? 5 : get_option("posts_per_page");

  $catId = $_GET["catId"];
  $page = $_GET["page"];
  $searchKey = $_GET["searchKey"];
  $authorId = $_GET["authorId"];

  //wp_reset_query();
  $args = "post_type=" . BLOG;
  $args .= "&order=DESC";
  $args .= "&posts_per_page=" . $postPerPage;
  $args .= "&paged=$page";

  if ($catId != "") {
    $args .= "&cat=$catId";
  }
  else {
    if ($searchKey != "") {
      $args .= "&s=$searchKey";
    }
  }

  //$arrPost = query_posts($args);
  $postQuery = new WP_Query($args);
  $arrPost = $postQuery->get_posts();
  if ($arrPost != NULL) :
    foreach ($arrPost as $post) :

      $postId = $post->ID;
      $image = wp_get_attachment_image_src(get_post_thumbnail_id($postId), 'single-post-thumbnail');
      if ($image != NULL) {
        $imageUrl = $image[0];
      }
      else {
        $imageUrl = get_bloginfo('stylesheet_directory') . "/i/story-side-pic.png";
      }

      $dateObj = DateTime::createFromFormat('Y-m-d H:i:s', $post->post_date);
      $dateStr = $dateObj->format('M j, Y');

      $twitterText = urlencode(wrap_content_strip_html(wpautop($subject."\nUrl: ".get_permalink()), 130, true,'\n\r',''));      $title = htmlspecialchars($post->post_title);
      $subject = htmlspecialchars(get_bloginfo('name')) . ' : ' . $title;
      $body = htmlspecialchars($post->post_content);
      $email_article = 'mailto:?subject='.rawurlencode($subject).'&body='.get_permalink();
      $twitterShare = "http://twitter.com/home?status=" . $twitterText;
      $fbShare = "http://www.facebook.com/sharer/sharer.php?s=100&p[url]=" . get_permalink(
        ) . "&p[images][0]=" . $imageUrl . "&p[title]=" . get_the_title() . "&p[summary]=" . $twitterText;
      $gplusShare = "https://plus.google.com/share?url=" . get_permalink();

      $authorObj = get_user_by("id", $post->post_author);
      $authorName = $authorObj->display_name;
      $authorLink = get_bloginfo("wpurl") . "/author/" . $authorObj->user_nicename;
      ?>
      <!-- Blog Item -->
      <div class="blogItem">
        <?php if ($searchKey == "") : ?>
          <!-- Thumb place holder -->
          <div class="mobiThumbPlaceholder">
            <a href="<?php the_permalink(); ?>"><img src="<?php echo $imageUrl; ?>" width="300" height="160"/></a>
          </div>
          <!-- Thumb place holder end -->
        <?php endif; ?>

        <a href="<?php the_permalink(); ?>" class="blogTitle blueLink"><?php echo $post->post_title; ?><a>

            <!-- Blog Desc -->
            <div class="blogDescBox">
              <div class="postDate"><?php echo $dateStr; ?> &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; By:&nbsp;&nbsp;</div>
              <div class="postAuthor"><a href="<?php echo $authorLink; ?>"
                                         class="author blueLink"><?php echo $authorName; ?></a></div>
              <div class="postCategory">In :
                <?php
                $categories = get_the_category($postId);
                $separator = ', ';
                $output = '';
                if ($categories) {
                  foreach ($categories as $key => $category) {
                    if (strtolower($category->name) != BLOG) {
                      $output .= '<a href="' . get_category_link($category->term_id) . '" title="' . esc_attr(
                          sprintf(__("View all posts in %s"), $category->name)
                        ) . '">' . $category->cat_name . '</a>' . $separator;
                    }
                  }
                }
                echo trim($output, $separator);
                ?>
              </div>
            </div>
            <!-- Blog Desc End -->

            <?php if ($searchKey != "") : ?>
              <!-- content wrapper -->
              <div class="contentWrapper">
                <?php
                $excerpt = wrap_content_strip_html(wpautop($post->post_content), 400, TRUE, '\n\r', '');
                echo $excerpt;
                ?>
              </div>
              <!-- content wrapper end -->
              <a href="<?php the_permalink(); ?>" class="continueReading">Continue Reading</a>
            <?php else: ?>
              <!-- Blog Right Section -->
              <div class="blogRightSection">
                <!-- Imageplacehoder -->
                <div class="imagePlaceholder">
                  <a href="<?php the_permalink(); ?>"><img src="<?php echo $imageUrl; ?>" width="158" height="158"/></a>
                </div>
                <!-- Imageplacehoder End -->

                <!-- Content Right -->
                <div class="contentRight">
                  <div class="excerpt">
                    <?php
                    $excerpt = wrap_content_strip_html(wpautop($post->post_content), 400, TRUE, '\n\r', '');
                    echo $excerpt;
                    ?>
                  </div>
                  <div class="shareVia">
                    <span>Share via : </span>
                    <a href="<?php echo $email_article; ?>" class="shareButton"><img
                        src="<?php bloginfo('stylesheet_directory'); ?>/i/shares-mail.png" width="21" height="21"/></a>
                    <a href="<?php echo $fbShare; ?>" class="shareButton"><img
                        src="<?php bloginfo('stylesheet_directory'); ?>/i/shares-fb.png" width="21" height="21"/></a>
                    <a href="<?php echo $twitterShare; ?>" class="shareButton"><img
                        src="<?php bloginfo('stylesheet_directory'); ?>/i/shares-twitter.png" width="21"
                        height="21"/></a>
                    <a href="<?php echo $gplusShare; ?>" class="shareButton"><img
                        src="<?php bloginfo('stylesheet_directory'); ?>/i/shares-gplus.png" width="21" height="21"/></a>
                  </div>
                  <a href="<?php the_permalink(); ?>" class="continueReading">Continue Reading</a>
                </div>
                <!-- Content Right End -->

              </div>
              <!-- Blog Right Section End -->
            <?php endif; ?>

      </div>
      <!-- Blog Item End -->
    <?php
    endforeach;
  endif;
  die();
}

add_action('wp_ajax_get_popular_ajax', 'get_popular_ajax');
add_action('wp_ajax_nopriv_get_popular_ajax', 'get_popular_ajax');
function get_popular_ajax() {
  $page = $_GET["page"];
  $postPerPage = $_GET["posts_per_page"] == "" ? 4 : $_GET["posts_per_page"];

  //wp_reset_query();
  $args = array(
    'post_type' => 'blog',
    'paged' => $page,
    'posts_per_page' => $postPerPage,
    'meta_key' => 'wpb_post_views_count',
    'orderby' => 'meta_value_num',
    'order' => 'DESC'
  );

  //$arrPost = query_posts($args);
  $postQuery = new WP_Query($args);
  $arrPost = $postQuery->get_posts();
  if ($arrPost != NULL) :
    foreach ($arrPost as $post) :
      $postId = $post->ID;
      ?>
      <li>
        <!-- Bug# I-104876 href comes as empty on "show more" -->
        <a class="contentLink" href="<?php echo get_permalink($postId); ?>">
          <img class="contentThumb" src="<?php bloginfo('stylesheet_directory'); ?>/i/content-thumb.png"
               alt="<?php echo $post->post_title; ?>">
          <?php echo $post->post_title; ?>
        </a> <span class="contentBrief"><?php echo wrap_content_strip_html(
            wpautop($post->post_content),
            70,
            TRUE,
            '\n\r',
            '...'
          ); ?></span>
      </li>
    <?php
    endforeach;
  endif;
  die();
  /*
  $arrPost = query_posts($args);
  if ($arrPost != NULL) :
    foreach ($arrPost as $post) :
      $postId = $post->ID;
      ?>
      <li>
        <!-- Bug# I-104876 href comes as empty on "show more" - -->
        <!-- Bug# I-104876 fix href to use rel urls - -->
        <a class="contentLink" href="<?php echo get_rel_url(get_permalink($postId), TRUE); ?>">
          <img class="contentThumb" src="<?php bloginfo('stylesheet_directory'); ?>/i/content-thumb.png"
               alt="<?php echo $post->post_title; ?>">
          <?php echo $post->post_title; ?>
        </a> <span class="contentBrief"><?php echo wrap_content_strip_html(
            wpautop($post->post_content),
            70,
            TRUE,
            '\n\r',
            '...'
          ); ?></span>
      </li>
    <?php
    endforeach;
  endif;
  die();
  */
}

add_action('wp_ajax_subscribe_ajax', 'subscribe_ajax');
add_action('wp_ajax_nopriv_subscribe_ajax', 'subscribe_ajax');
function subscribe_ajax() {
  echo json_encode($_POST);
  die();
}

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

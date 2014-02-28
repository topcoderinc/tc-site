<?php
define('WP_DEBUG_DISPLAY', true);
@ini_set('display_errors',1);
#include 'auth0/vendor/autoload.php';
#include 'auth0/src/Auth0.php';
#include 'auth0/vendor/adoy/oauth2/vendor/autoload.php';
#include 'auth0/client/config.php';
require_once 'auth0/vendor/autoload.php';
require_once 'auth0/src/Auth0.php';
require_once 'auth0/vendor/adoy/oauth2/vendor/autoload.php';
require_once 'auth0/client/config.php';
define("auth0_domain",$auth0_cfg['domain']);
define("auth0_client_id",$auth0_cfg['client_id']);
define("auth0_redirect_uri",$auth0_cfg['redirect_uri']);
define("auth0_state",$auth0_cfg['state']);
include("functions-widget.php");

define("BLOG","blog");

// add featured image
add_theme_support ( 'post-thumbnails' );
if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 55, 55 ); // default Post Thumbnail dimensions
}
if ( function_exists( 'add_image_size' ) ) {
	add_image_size( 'blog-thumb', 158, 154,true );
	add_image_size( 'blog-thumb-mobile', 300, 165);
}

// enables tags on pages
function tags_support_all() {
	register_taxonomy_for_object_type('post_tag', 'page');
}
add_action('init', 'tags_support_all');

/* RSS Feeds for challenge listings */
add_action('init', 'challengesRSS');
function challenges_rss_rewrite_rules( $wp_rewrite ) {
  $new_rules = array(
    'challenges/feed/?' => 'index.php?feed=challenges-feed'
  );
  $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}
function challengesRSS(){
	global $wp_rewrite;
	add_feed('challenges-feed', 'challengesRSSFunc');
	add_action('generate_rewrite_rules', 'challenges_rss_rewrite_rules');
	$wp_rewrite->flush_rules();
}
function challengesRSSFunc(){
	get_template_part('rss', 'challenges');
}
?>
<?php
$includes_path = TEMPLATEPATH . '/lib';
require_once (TEMPLATEPATH . '/rewrite-config.php');

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

class fixImageMargins{
    public $xs = 0; //change this to change the amount of extra spacing

    public function __construct(){
        add_filter('img_caption_shortcode', array(&$this, 'fixme'), 10, 3);
    }
    public function fixme($x=null, $attr, $content){

        extract(shortcode_atts(array(
                'id'    => '',
                'align'    => 'alignnone',
                'width'    => '',
                'caption' => ''
            ), $attr));

        if ( 1 > (int) $width || empty($caption) ) {
            return $content;
        }

        if ( $id ) $id = 'id="' . $id . '" ';

    return '<div ' . $id . 'class="wp-caption ' . $align . '" style="width: ' . ((int) $width + $this->xs) . 'px">'
    . $content . '<p class="wp-caption-text">' . $caption . '</p></div>';
    }
}
$fixImageMargins = new fixImageMargins();

function curPageURL() {
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}

function get_page_link_by_slug($page_slug) {
$page = get_page_by_path($page_slug);
if ($page) :
return get_permalink( $page->ID );
else :
return "#";
endif;
}
function wpb_set_post_views($postID) {
    $count_key = 'wpb_post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}


function wpb_track_post_views ($post_id) {
    if ( !is_single() ) return;
    if ( empty ( $post_id) ) {
        global $post;
        $post_id = $post->ID;
    }
    wpb_set_post_views($post_id);
}
add_action( 'wp_head', 'wpb_track_post_views');
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
	return $query_vars;
}
add_filter ( 'query_vars', 'tcapi_query_vars' );


// Active Contest
add_rewrite_rule ( '^'.ACTIVE_CONTESTS_PERMALINK.'/([^/]*)/?$', 'index.php?pagename=challenges&contest_type=$matches[1]', 'top' );
add_rewrite_rule ( '^'.ACTIVE_CONTESTS_PERMALINK.'/([^/]*)/([0-9]*)/?$', 'index.php?pagename=active-contests&contest_type=$matches[1]&pages=$matches[2]', 'top' );


add_rewrite_rule ( '^'.DESIGN_CONTESTS_PERMALINK.'/([^/]*)/?$', 'index.php?pagename=$matches[1]&contest_type=design', 'top' );
add_rewrite_rule ( '^'.DEVELOP_CONTESTS_PERMALINK.'/([^/]*)/?$', 'index.php?pagename=$matches[1]&contest_type=develop', 'top' );
add_rewrite_rule ( '^'.DATA_CONTESTS_PERMALINK.'/([^/]*)/?$', 'index.php?pagename=$matches[1]&contest_type=data', 'top' );

// Past Contest
add_rewrite_rule ( '^'.PAST_CONTESTS_PERMALINK.'/([^/]*)/?$', 'index.php?pagename=past-contests&contest_type=$matches[1]', 'top' );
add_rewrite_rule ( '^'.PAST_CONTESTS_PERMALINK.'/([^/]*)/([0-9]*)/?$', 'index.php?pagename=past-contests&contest_type=$matches[1]&pages=$matches[2]', 'top' );

// Past Contest
add_rewrite_rule ( '^'.REVIEW_OPPORTUNITIES_PERMALINK.'/([^/]*)/?$', 'index.php?pagename=review-opportunities&contest_type=$matches[1]', 'top' );
add_rewrite_rule ( '^'.REVIEW_OPPORTUNITIES_PERMALINK.'/([^/]*)/([0-9]*)/?$', 'index.php?pagename=review-opportunities&contest_type=$matches[1]&pages=$matches[2]', 'top' );

// Contest Details
add_rewrite_rule ( '^'.CONTEST_DETAILS_PERMALINK.'/([^/]*)/?$', 'index.php?pagename=challenge-details&contestID=$matches[1]', 'top' );

// Member Profile
//add_rewrite_rule ( '^'.MEMBER_PROFILE_PERMALINK.'/([^/]*)/?$', 'index.php?pagename=member-profile&handle=$matches[1]', 'top' );
add_rewrite_rule ( '^'.MEMBER_PROFILE_PERMALINK.'/([^/]*)/?([^/]*)$', 'index.php?pagename=member-profile&handle=$matches[1]&tab=$matches[2]', 'top' );

// Blog Category
//add_rewrite_rule ( '^'.BLOG_PERMALINK.'/([^/]*)/?$', 'index.php?pagename=blog-page&slug=$matches[1]', 'top' );
//add_rewrite_rule ( '^'.BLOG_PERMALINK.'/([^/]*)/page/([0-9]*)/?$', 'index.php?pagename=blog-page&slug=$matches[1]&page=$matches[2]', 'top' );
add_rewrite_rule ( '^'.ACTIVE_CHALLENGES_PERMALINK.'/([^/]*)/?$', 'index.php?pagename=challenges&contest_type=$matches[1]', 'top' );

// Case studies Category
//add_rewrite_rule ( '^'.CASE_STUDIES_PERMALINK.'/([^/]*)/?$', 'index.php?pagename=case-studies&slug=$matches[1]', 'top' );
//add_rewrite_rule ( '^'.CASE_STUDIES_PERMALINK.'/([^/]*)/?$', 'index.php?pagename=case-studies&page=$matches[1]', 'top' );
//add_rewrite_rule ( '^'.CASE_STUDIES_PERMALINK.'/([^/]*)/page/([0-9]*)/?$', 'index.php?pagename=case-studies&slug=$matches[1]&page=$matches[2]', 'top' );

// challenges
add_rewrite_rule( '^challenges/([^/]*)/?$', 'index.php?pagename=challenge-details&contestID=$matches[1]', 'top');

// Blog search
//add_rewrite_rule('^'.BLOG_PERMALINK.'/?$', 'index.php?', 'top');
// Active Challenges
add_rewrite_rule( '^active-challenges/data/?$', 'index.php?pagename=data&contest_type=$matches[1]', 'top');
add_rewrite_rule( '^active-challenges/([^/]*)/?$', 'index.php?pagename=active-challenges&contest_type=$matches[1]', 'top');

// Past Challenges
add_rewrite_rule( '^past-challenges/([^/]*)/?$', 'index.php?pagename=past-challenges&contest_type=$matches[1]', 'top');

// Review Challenges
add_rewrite_rule( '^review-opportunities/([^/]*)/?$', 'index.php?pagename=review-opportunities&contest_type=$matches[1]', 'top');
add_rewrite_rule( '^review-opportunity/([^/]*)/([^/]*)/?$', 'index.php?pagename=review-opportunity-details&contest_type=$matches[1]&contestID=$matches[2]', 'top');

// Bug Races
add_rewrite_rule( '^bug-races/([^/]*)/?$', 'index.php?pagename=bug-races&contest_type=$matches[1]', 'top');

// Search results
add_rewrite_rule('^search/?$', 'index.php?', 'top');

/* flush */
flush_rewrite_rules ();

/* commonly used functions
 -----------------------------------*/
/* excerpt */
function new_excerpt_more( $more ) {
	return '...<br/>'.'<a href="'. get_permalink( get_the_ID() ) . '" class="more">Read More</a>';
}
add_filter('excerpt_more', 'new_excerpt_more');

function custom_excerpt_length( $length ) {
	return 27;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

function custom_excerpt($new_length = 20, $new_more = '...') {
	add_filter('excerpt_length', function () use ($new_length) {
		return $new_length;
	}, 999);
	add_filter('excerpt_more', function () use ($new_more) {
		return $new_more;
	});
	$output = get_the_excerpt();
	$output = apply_filters('wptexturize', $output);
	$output = apply_filters('convert_chars', $output);
	$output = $output;
	echo $output;
}

function custom_content($new_length = 55) {
	$output = get_the_content();
	$output = apply_filters('wptexturize', $output);
	$output = substr($output, 0 , $new_length).'...';
	return  $output;
}

/* singnup function from given theme */
function get_cookie() {
	global $_COOKIE;
	// $_COOKIE['main_user_id_1'] = '22760600|2c3a1c1487520d9aaf15917189d5864';
	$hid = explode ( "|", $_COOKIE ['main_tcsso_1'] );
	$handleName = $_COOKIE ['handleName'];
	// print_r($hid);
	$hname = explode ( "|", $_COOKIE ['direct_sso_user_id_1'] );
	$meta = new stdclass ();
	$meta->handle_id = $hid [0];
	$meta->handle_name = $handleName;
	return $meta;
}

// add menu support
add_theme_support ( 'menus' );

remove_filter( 'the_content', 'wpautop' );


/* Promo Module Post Type */
add_action ( 'init', 'promo_register' );
function promo_register() {
	$strPostName = 'Promo Module';

	$labels = array (
			'name' => _x ( $strPostName . 's', 'post type general name' ),
			'singular_name' => _x ( $strPostName, 'post type singular name' ),
			'add_new' => _x ( 'Add New', $strPostName . ' Post' ),
			'add_new_item' => __ ( 'Add New ' . $strPostName . ' Post' ),
			'edit_item' => __ ( 'Edit ' . $strPostName . ' Post' ),
			'new_item' => __ ( 'New ' . $strPostName . ' Post' ),
			'view_item' => __ ( 'View ' . $strPostName . ' Post' ),
			'search_items' => __ ( 'Search ' . $strPostName ),
			'not_found' => __ ( 'Nothing found' ),
			'not_found_in_trash' => __ ( 'Nothing found in Trash' ),
			'parent_item_colon' => ''
	);

	$args = array (
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'menu_position' => 5,
			'exclude_from_search' => false,
			'show_in_nav_menus' => true,
			'taxonomies' => array (
					'category'
			),
			'supports' => array (
					'title',
					'editor',
					'thumbnail',
					'page-attributes'
			)
	);

	register_post_type ( 'promo', $args );
	flush_rewrite_rules ( false );
	$strPostName = 'Blog';
	$strPostName = 'Blog';

	$labels = array (
			'name' => _x ( $strPostName . 's', 'post type general name' ),
			'singular_name' => _x ( $strPostName, 'post type singular name' ),
			'add_new' => _x ( 'Add New', $strPostName . ' Post' ),
			'add_new_item' => __ ( 'Add New ' . $strPostName . ' Post' ),
			'edit_item' => __ ( 'Edit ' . $strPostName . ' Post' ),
			'new_item' => __ ( 'New ' . $strPostName . ' Post' ),
			'view_item' => __ ( 'View ' . $strPostName . ' Post' ),
			'search_items' => __ ( 'Search ' . $strPostName ),
			'not_found' => __ ( 'Nothing found' ),
			'not_found_in_trash' => __ ( 'Nothing found in Trash' ),
			'parent_item_colon' => ''
	);

	$args = array (
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'menu_position' => 5,
			'exclude_from_search' => false,
			'show_in_nav_menus' => true,
			'taxonomies' => array('category','post_tag'),
			'supports' => array (
					'title',
					'editor',
					'thumbnail',
					'custom-fields',
					'tags',
					'comments'

			)
	);

	register_post_type ( BLOG, $args );
	add_post_type_support( BLOG, 'author' );
}

/* Case studies Module Post Type */
add_action ( 'init', 'case_studies_register' );
function case_studies_register() {
	$strPostName = 'Case Studies';

	$labels = array (
			'name' => _x ( $strPostName . 's', 'post type general name' ),
			'singular_name' => _x ( $strPostName, 'post type singular name' ),
			'add_new' => _x ( 'Add New', $strPostName . ' Post' ),
			'add_new_item' => __ ( 'Add New ' . $strPostName . ' Post' ),
			'edit_item' => __ ( 'Edit ' . $strPostName . ' Post' ),
			'new_item' => __ ( 'New ' . $strPostName . ' Post' ),
			'view_item' => __ ( 'View ' . $strPostName . ' Post' ),
			'search_items' => __ ( 'Search ' . $strPostName ),
			'not_found' => __ ( 'Nothing found' ),
			'not_found_in_trash' => __ ( 'Nothing found in Trash' ),
			'parent_item_colon' => ''
	);

	$args = array (
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'menu_position' => 5,
			'exclude_from_search' => false,
			'show_in_nav_menus' => true,
			'taxonomies' => array('category','post_tag'),
			'supports' => array (
					'title',
					'editor',
					'thumbnail',
					'custom-fields',
					'tags',
					'comments'

			)
	);

	register_post_type ( 'case-studies', $args );

	flush_rewrite_rules ( false );

}

add_action ( 'wp_ajax_get_blog_ajax', 'get_blog_ajax' );
add_action ( 'wp_ajax_nopriv_get_blog_ajax', 'get_blog_ajax' );
function get_blog_ajax() {
	$postPerPage = get_option("posts_per_page") == "" ? 5 : get_option("posts_per_page");

	$catId = $_GET["catId"];
	$page = $_GET["page"];
	$searchKey = $_GET["searchKey"];
	$authorId = $_GET["authorId"];

	wp_reset_query();
	$args = "post_type=".BLOG;
	$args .= "&order=DESC";
	$args .= "&posts_per_page=".$postPerPage;
	$args .= "&paged=$page";

	if($catId!="") {
		$args .= "&cat=$catId";
	}
	else if($searchKey!="") {
		$args .= "&s=$searchKey";
	}

	$arrPost = query_posts($args);
	if ( $arrPost!= null ) :
		foreach ( $arrPost as $post ) :

			$postId = $post->ID;
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $postId ), 'single-post-thumbnail' );
			if($image!=null) $imageUrl = $image[0];
			else $imageUrl = get_bloginfo('stylesheet_directory')."/i/story-side-pic.png";

			$dateObj = DateTime::createFromFormat('Y-m-d H:i:s', $post->post_date);
			$dateStr = $dateObj->format('M j, Y');

			$twitterText = urlencode(wrap_content_strip_html(wpautop($post->post_content), 130, true,'\n\r',''));
			$title = htmlspecialchars($post->post_title);
			$subject = htmlspecialchars(get_bloginfo('name')).' : '.$title;
			$body = htmlspecialchars($post->post_content);
			$email_article = 'mailto:?subject='.rawurlencode($subject).'&body='.rawurlencode($body);
			$twitterShare = "http://twitter.com/home?status=".$twitterText;
			$fbShare = "http://www.facebook.com/sharer/sharer.php?s=100&p[url]=".get_permalink()."&p[images][0]=".$imageUrl."&p[title]=".get_the_title()."&p[summary]=".$twitterText;
			$gplusShare = "https://plus.google.com/share?url=".get_permalink();

			$authorObj = get_user_by("id",$post->post_author);
			$authorName = $authorObj->display_name;
			$authorLink = get_bloginfo("wpurl")."/author/".$authorObj->user_nicename;
	?>
		<!-- Blog Item -->
		<div class="blogItem">
			<?php if($searchKey=="") : ?>
				<!-- Thumb place holder -->
				<div class="mobiThumbPlaceholder">
					<a href="<?php the_permalink();?>"><img src="<?php echo $imageUrl;?>" width="300" height="160" /></a>
				</div>
				<!-- Thumb place holder end -->
			<?php endif ;?>

			<a href="<?php the_permalink();?>" class="blogTitle blueLink"><?php echo $post->post_title;?><a>

			<!-- Blog Desc -->
			<div class="blogDescBox">
				<div class="postDate"><?php echo $dateStr;?> &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; By:&nbsp;&nbsp;</div>
				<div class="postAuthor"><a href="<?php echo $authorLink;?>" class="author blueLink"><?php echo $authorName;?></a></div>
				<div class="postCategory">In :
				<?php
					$categories = get_the_category($postId);
					$separator = ', ';
					$output = '';
					if($categories){
						foreach($categories as $key=>$category) {
							if(strtolower($category->name)!=BLOG)
								$output .= '<a href="'.get_category_link( $category->term_id ).'" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '">'.$category->cat_name.'</a>'.$separator;
						}
					}
					echo trim($output, $separator);
				?>
				</div>
			</div>
			<!-- Blog Desc End -->

		<?php if($searchKey!="") : ?>
			<!-- content wrapper -->
			<div class="contentWrapper">
				<?php
					$excerpt = wrap_content_strip_html(wpautop($post->post_content), 400, true,'\n\r','');
					echo $excerpt;
				?>
			</div>
			<!-- content wrapper end -->
			<a href="<?php the_permalink();?>" class="continueReading">Continue Reading</a>
		<?php else: ?>
			<!-- Blog Right Section -->
			<div class="blogRightSection">
				<!-- Imageplacehoder -->
				<div class="imagePlaceholder">
					<a href="<?php the_permalink();?>"><img src="<?php echo $imageUrl;?>" width="158" height="158" /></a>
				</div>
				<!-- Imageplacehoder End -->

				<!-- Content Right -->
				<div class="contentRight">
					<div class="excerpt">
						<?php
							$excerpt = wrap_content_strip_html(wpautop($post->post_content), 400, true,'\n\r','');
							echo $excerpt;
						?>
					</div>
					<div class="shareVia">
						<span>Share via : </span>
						<a href="<?php echo $email_article;?>" class="shareButton"><img src="<?php bloginfo( 'stylesheet_directory' ); ?>/i/shares-mail.png" width="21" height="21" /></a>
						<a href="<?php echo $fbShare;?>" class="shareButton"><img src="<?php bloginfo( 'stylesheet_directory' ); ?>/i/shares-fb.png" width="21" height="21" /></a>
						<a href="<?php echo $twitterShare;?>" class="shareButton"><img src="<?php bloginfo( 'stylesheet_directory' ); ?>/i/shares-twitter.png" width="21" height="21" /></a>
						<a href="<?php echo $gplusShare;?>" class="shareButton"><img src="<?php bloginfo( 'stylesheet_directory' ); ?>/i/shares-gplus.png" width="21" height="21" /></a>
					</div>
					<a href="<?php the_permalink();?>" class="continueReading">Continue Reading</a>
				</div>
				<!-- Content Right End -->

			</div>
			<!-- Blog Right Section End -->
		<?php endif;?>

		</div>
		<!-- Blog Item End -->
		<?php
			endforeach;
		endif;
	die();
}

add_action ( 'wp_ajax_get_popular_ajax', 'get_popular_ajax' );
add_action ( 'wp_ajax_nopriv_get_popular_ajax', 'get_popular_ajax' );
function get_popular_ajax() {
	$page = $_GET["page"];
	$postPerPage = $_GET["posts_per_page"] == "" ? 4 : $_GET["posts_per_page"];

	wp_reset_query();
	$args = array(  'post_type'=>'blog',
					'paged' => $page,
					'posts_per_page' => $postPerPage,
					'meta_key' => 'wpb_post_views_count',
					'orderby' => 'meta_value_num',
					'order' => 'DESC'  );

	$arrPost = query_posts($args);
	if ( $arrPost!= null ) :
		foreach ( $arrPost as $post ) :
			$postId = $post->ID;
			?>
			<li>
				<a class="contentLink" href="<?php the_permalink() ?>">
				<img class="contentThumb" src="<?php bloginfo( 'stylesheet_directory' ); ?>/i/content-thumb.png" alt="<?php echo $post->post_title; ?>">
				<?php echo $post->post_title; ?>
				</a> <span class="contentBrief"><?php echo wrap_content_strip_html(wpautop($post->post_content), 70, true,'\n\r','...') ?></span>
			</li>
			<?php
		endforeach;
	endif;
	die();
}

add_action ( 'wp_ajax_subscribe_ajax', 'subscribe_ajax' );
add_action ( 'wp_ajax_nopriv_subscribe_ajax', 'subscribe_ajax' );
function subscribe_ajax() {
	echo json_encode($_POST);
	die();
}

function fixIERoundedCorder() {
	$pieHtcLocation = get_bloginfo( 'stylesheet_directory' )."/css/PIE.htc";
?>
	<style>
		.btn, a.btn, .blogCategoryMenu a,.searchBox input, .subscribeBox input {
			behavior: url("<?php echo $pieHtcLocation;?>");
		}
	</style>
<?php
}

function get_user_browser()
{
	$u_agent = $_SERVER['HTTP_USER_AGENT'];
	$ub = '';
	if(preg_match('/MSIE/i',$u_agent))
	{
		$ub = "ie";
	}
	elseif(preg_match('/Firefox/i',$u_agent))
	{
		$ub = "firefox";
	}
	elseif(preg_match('/Safari/i',$u_agent))
	{
		$ub = "safari";
	}
	elseif(preg_match('/Chrome/i',$u_agent))
	{
		$ub = "chrome";
	}
	elseif(preg_match('/Flock/i',$u_agent))
	{
		$ub = "flock";
	}
	elseif(preg_match('/Opera/i',$u_agent))
	{
		$ub = "opera";
	}

	return $ub;
}

/**
 * wrap content to $len length content, and add '...' to end of wrapped conent
 */
function wrap_content_strip_html($content, $len, $strip_html = false, $sp = '\n\r', $ending = '...') {
	if ($strip_html) {
		$content = strip_tags($content);
		$content = strip_shortcodes($content);
	}
	$c_title_wrapped = wordwrap($content, $len, $sp);
	$w_title = explode($sp, $c_title_wrapped);
    if (strlen($content) <= $len) { $ending = ''; }
	return $w_title[0].$ending;
}

/* get page id by slug */
function get_ID_by_slug($page_slug) {
    $page = get_page_by_path($page_slug);
    if ($page) {
        return $page->ID;
    } else {
        return null;
    }
}

/* function convert category slug to category id  */
function getCategoryId($slug) {
  $idObj = get_category_by_slug($slug);
  $id = $idObj->term_id;
  return $id;
}

/**
 * Start of Theme Options Support
 */
function themeoptions_admin_menu() {
	add_theme_page ( "Theme Options", "Theme Options", 'edit_themes', basename ( __FILE__ ), 'themeoptions_page' );
}
add_action ( 'admin_menu', 'themeoptions_admin_menu' );
function themeoptions_page() {
	if ($_POST ['update_themeoptions'] == 'true') {
		themeoptions_update ();
	} // check options update
	// here's the main function that will generate our options page
	?>

<div class="wrap">
	<div id="icon-themes" class="icon32">
		<br />
	</div>
	<h2>TCS Theme Options</h2>

	<form method="POST" action="" enctype="multipart/form-data">
		<input type="hidden" name="update_themeoptions" value="true" />
		<h3>TopCoder API settings</h3>
		<table width="100%">
			<tr>
				<?php $field = 'forumPostPerPage'; ?>
				<td width="150"><label for="<?php echo $field; ?>">Forum post per page:</label></td>
				<td><input type="text" id="<?php echo $field; ?>" name="<?php echo $field; ?>" size="100" value="<?php echo get_option($field); ?>" /></td>
			</tr>
		</table>
		<br />
		<h3>Blog</h3>
		<table width="100%">
			<tr>
				<?php $field = 'blog_page_title'; ?>
				<td width="150"><label for="<?php echo $field; ?>">Blog Page Title:</label></td>
				<td><input type="text" id="<?php echo $field; ?>" name="<?php echo $field; ?>" size="100" value="<?php echo get_option($field); ?>" /></td>
			</tr>
			<tr>
				<?php $field = 'case_studies_per_page'; ?>
				<td width="150"><label for="<?php echo $field; ?>">Case studies post per page:</label></td>
				<td><input type="text" id="<?php echo $field; ?>" name="<?php echo $field; ?>" size="100" value="<?php echo get_option($field); ?>" /></td>
			</tr>
		</table>
		<br />
		<h3>Social Media Links</h3>
		<table width="100%">
			<tr>
				<?php $field = 'facebookURL'; ?>
				<td width="150"><label for="<?php echo $field; ?>">Facebook URL:</label></td>
				<td><input type="text" id="<?php echo $field; ?>" name="<?php echo $field; ?>" size="100" value="<?php echo get_option($field); ?>" /></td>
			</tr>
			<tr>
				<?php $field = 'twitterURL'; ?>
				<td><label for="<?php echo $field; ?>">Twitter URL:</label></td>
				<td><input type="text" id="<?php echo $field; ?>" name="<?php echo $field; ?>" size="100" value="<?php echo get_option($field); ?>" /></td>
			</tr>
			<tr>
				<?php $field = 'linkedInURL'; ?>
				<td><label for="<?php echo $field; ?>">LinkedIn URL:</label></td>
				<td><input type="text" id="<?php echo $field; ?>" name="<?php echo $field; ?>" size="100" value="<?php echo get_option($field); ?>" /></td>
			</tr>
			<tr>
				<?php $field = 'gPlusURL'; ?>
				<td><label for="<?php echo $field; ?>">Google Plus URL:</label></td>
				<td><input type="text" id="<?php echo $field; ?>" name="<?php echo $field; ?>" size="100" value="<?php echo get_option($field); ?>" /></td>
			</tr>
		</table>
		<br />
		<h3>Twitter OAuth Tokens</h3>
		<table width="100%">
			<tr>
				<?php $field = 'twConsumerKey'; ?>
				<td width="150"><label for="<?php echo $field; ?>">Consumer key:</label></td>
				<td><input type="text" id="<?php echo $field; ?>" name="<?php echo $field; ?>" size="100" value="<?php echo get_option($field); ?>" /></td>
			</tr>
			<tr>
				<?php $field = 'twConsumerSecret'; ?>
				<td><label for="<?php echo $field; ?>">Consumer secret:</label></td>
				<td><input type="text" id="<?php echo $field; ?>" name="<?php echo $field; ?>" size="100" value="<?php echo get_option($field); ?>" /></td>
			</tr>
			<tr>
				<?php $field = 'twAccessToken'; ?>
				<td><label for="<?php echo $field; ?>">Access token:</label></td>
				<td><input type="text" id="<?php echo $field; ?>" name="<?php echo $field; ?>" size="100" value="<?php echo get_option($field); ?>" /></td>
			</tr>
			<tr>
				<?php $field = 'twAccessTokenSecret'; ?>
				<td><label for="<?php echo $field; ?>">Access token secret:</label></td>
				<td><input type="text" id="<?php echo $field; ?>" name="<?php echo $field; ?>" size="100" value="<?php echo get_option($field); ?>" /></td>
			</tr>
		</table>
		<br />

		<h3>Challenge Pages Configuration</h3>
		<table width="100%">
			<tr>
				<?php $field = 'tcoTooltipTitle'; ?>
				<td width="150"><label for="<?php echo $field; ?>">TCO Tooltip Title:</label></td>
				<td><input type="text" id="<?php echo $field; ?>" name="<?php echo $field; ?>" size="100" value="<?php echo get_option($field); ?>" /></td>
			</tr>
			<tr>
				<?php $field = 'tcoTooltipMessage'; ?>
				<td><label for="<?php echo $field; ?>">TCO Tooltip Message:</label></td>
				<td><input type="text" id="<?php echo $field; ?>" name="<?php echo $field; ?>" size="100" value="<?php echo get_option($field); ?>" /></td>
			</tr>
		</table>
		<br />

		<h3>JS/CSS versioning</h3>
		<table width="100%">
			<tr>
				<?php $field = 'jsCssVersioning'; ?>
				<td width="150"><label for="<?php echo $field; ?>">JS/CSS Versioning:</label></td>
				<td>
					<input type="radio" name="<?php echo $field; ?>" value="1" <?php if (get_option($field) == 1): ?>checked="checked"<?php endif; ?> /> Yes
					<input type="radio" name="<?php echo $field; ?>" value="0" <?php if (get_option($field) != 1): ?>checked="checked"<?php endif; ?> /> No
				</td>
			</tr>
			<tr>
				<?php $field = 'jsCssCurrentVersion'; ?>
				<td><label for="<?php echo $field; ?>">Current Version:</label></td>
				<td><input type="text" id="<?php echo $field; ?>" name="<?php echo $field; ?>" size="100" value="<?php echo (strlen(trim(get_option($field))) == 0) ? date('Ymd') : get_option($field); ?>" /></td>
			</tr>
		</table>

		<br />
		<p>
			<input type="submit" name="submit" value="Update Options" class="button button-primary" />
		</p>
	</form>

</div>
<?php
}

// Set default options
if (is_admin () && isset ( $_GET ['activated'] ) && $pagenow == 'themes.php') {

	// Other Options
	update_option ( 'forumPostPerPage', '3' );

	// Social Media
	update_option ( 'facebookURL', 'http://www.facebook.com/topcoderinc' );
	update_option ( 'twitterURL', 'http://www.twitter.com/topcoder' );
	update_option ( 'linkedInURL', 'http://www.youtube.com/topcoderinc' );
	update_option ( 'gPlusURL', 'https://plus.google.com/u/0/b/104268008777050019973/104268008777050019973/posts' );

	update_option ( 'tcoTooltipTitle', 'TCO-14' );
	update_option ( 'tcoTooltipMessage', 'Eligible for TCO14' );
}

// Update options function
function themeoptions_update() {
	// Other Options
	update_option ( 'case_studies_per_page', $_POST ['case_studies_per_page'] );
	update_option ( 'forumPostPerPage', $_POST ['forumPostPerPage'] );

	// blog
	update_option ( 'blog_page_title', $_POST ['blog_page_title'] );

	// Social Media
	update_option ( 'facebookURL', $_POST ['facebookURL'] );
	update_option ( 'twitterURL', $_POST ['twitterURL'] );
	update_option ( 'linkedInURL', $_POST ['linkedInURL'] );
	update_option ( 'gPlusURL', $_POST ['gPlusURL'] );

	// Twitter OAuth Tokens
	update_option ( 'twConsumerKey', $_POST ['twConsumerKey'] );
	update_option ( 'twConsumerSecret', $_POST ['twConsumerSecret'] );
	update_option ( 'twAccessToken', $_POST ['twAccessToken'] );
	update_option ( 'twAccessTokenSecret', $_POST ['twAccessTokenSecret'] );

	// Challenges Page
	update_option ( 'tcoTooltipTitle', $_POST ['tcoTooltipTitle'] );
	update_option ( 'tcoTooltipMessage', $_POST ['tcoTooltipMessage'] );

	// JS/CSS versioning - BUGR-10904
	update_option ( 'jsCssVersioning', $_POST['jsCssVersioning'] );
	update_option ( 'jsCssCurrentVersion', $_POST['jsCssCurrentVersion'] );

}
// END OF THEME OPTIONS SUPPORT

/* Register widgets */
include_once 'widget.php';
if (function_exists ( 'register_sidebar' )) {

	/*
	 * Sidebar community
	*/
	register_sidebar ( array (
			'name' => 'Sidebar Community',
			'id' => 'community_sidebar',
			'description' => 'Sidebar widget on community page',
			'before_widget' => '',
			'after_widget' => ''
	) );

	register_sidebar ( array (
	'name' => 'BottomBar Community',
	'id' => 'community_bottombar',
	'description' => 'Bottom bar widget on community page',
	'before_widget' => '',
	'after_widget' => ''
			) );

	// overview template sidebar
	register_sidebar ( array (
	'name' => 'Case studies sidebar',
	'id' => 'case_studies_sidebar',
	'description' => 'Sidebar widget on Case studies single page',
	'before_widget' => '',
	'after_widget' => ''
			) );

	// blog sidebar
	register_sidebar ( array (
		'name' => 'Blog Sidebar',
		'id' => 'blog_sidebar',
		'description' => 'Sidebar on Blog',
		'before_widget' => '',
		'after_widget' => ''
	) );
}

// header menu walker
class nav_menu_walker extends Walker_Nav_Menu {

	// add classes to ul sub-menus
	function start_lvl( &$output, $depth ) {
		// depth dependent classes
		$indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' ); // code indent
		$display_depth = ( $depth + 1); // because it counts the first submenu as 0
		$classes = array('child');
		$class_names = implode( ' ', $classes );

		// build html
		$output .= "\n" . $indent . '<ul class="' . $class_names . '">' . "\n";
	}

	// add main/sub classes to li's and links
	function start_el( &$output, $item, $depth, $args ) {
		global $wp_query;
		$indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' ); // code indent

		// passed classes
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$class_names = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ) );

		// build html
		$output .= $indent . '<li id="nav-menu-item-'. $item->ID . '">';

		// link attributes
		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
		$attributes .= ' class="' . (! empty ( $item->post_name ) ? esc_attr($item->post_name) : '') . '"';

		$item_output = sprintf( '%1$s<a%2$s><i></i>%3$s%4$s%5$s</a>%6$s',
				$args->before,
				$attributes,
				$args->link_before,
				apply_filters( 'the_title', $item->title, $item->ID ),
				$args->link_after,
				$args->after
		);

		// build html
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}

// footer menu walker
class footer_menu_walker extends Walker_Nav_Menu {

	// add classes to ul sub-menus
	function start_lvl( &$output, $depth ) {
		// depth dependent classes
		$indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' ); // code indent
		$display_depth = ( $depth + 1); // because it counts the first submenu as 0
		$classes = array('child');
		$class_names = implode( ' ', $classes );

		// build html
		$output .= "\n" . $indent . '<ul class="' . $class_names . '">' . "\n";
	}

	// add main/sub classes to li's and links
	function start_el( &$output, $item, $depth, $args ) {
		global $wp_query;
		$indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' ); // code indent

		// passed classes
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$class_names = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ) );

		// build html
		$deptClass = "";
		if($depth == 0){
			$deptClass = "rootNode";
		}
		$output .= $indent . '<li id="nav-menu-item-'. $item->ID . '" class="'.$deptClass.'">';



		// link attributes
		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
		$attributes .= ' class="' . (! empty ( $item->post_name ) ? esc_attr($item->post_name) : '') . '"';

		$item_output = sprintf( '%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
				$args->before,
				$attributes,
				$args->link_before,
				apply_filters( 'the_title', $item->title, $item->ID ),
				$args->link_after,
				$args->after
		);

		// build html
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}
?>
<?php
/* comments */
function mytheme_comment($comment, $args, $depth) {
	$GLOBALS ['comment'] = $comment;
	extract ( $args, EXTR_SKIP );
	if ('div' == $args ['style']) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
	?>
<<?php echo $tag ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
<?php if ( 'div' != $args['style'] ) : ?>
<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
	<?php endif; ?>
	<div class="comment-author vcard">
		<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, 90 ); ?>

	</div>
	<div class="commentText">
		<span class="arrow"></span>
		<div class="userRow">
			<a href="<?php get_comment_author_url();?>">
				<?php echo get_comment_author_link();?>
			</a>
			<span class="commentTime"> <?php printf( __('%1$s '), get_comment_date('F j, Y'))?>
			</span>
			<?php
			if ($comment->comment_parent) {
				$parent_comment = get_comment ( $comment->comment_parent );
				echo 'to <a href="' . get_comment_author_url () . '" >' . $parent_comment->comment_author . '</a>';
			}
			?>
		</div>
		<?php if ($comment->comment_approved == '0') : ?>
		<em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.') ?> </em>
		<?php endif; ?>
		<div class="commentData">
			<?php comment_text(); ?>
		</div>
		<!-- /.commentBody -->
		<div class="actionRow">
			<?php if(get_edit_comment_link(__('Edit'),'  ','' ) !=  "" ):?>
			<span class="comment-meta commentmetadata"> <?php edit_comment_link(__('Edit'),'  ','' );?>
			</span>
			<?php endif;?>
			<span class="reply"> <?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'])))?>
			</span>
		</div>
	</div>
	<?php if ( 'div' != $args['style'] ) : ?>
</div>


<?php endif;
}
?>
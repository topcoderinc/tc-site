<?php
/*
Plugin Name: WP to Twitter
Plugin URI: http://www.joedolson.com/articles/wp-to-twitter/
Description: Posts a Tweet when you update your WordPress blog or post to your blogroll, using your URL shortening service. Rich in features for customizing and promoting your Tweets.
Version: 2.8.7
Author: Joseph Dolson
Author URI: http://www.joedolson.com/
*/
/*  Copyright 2008-2014  Joseph C Dolson  (email : plugins@joedolson.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

apply_filters( 'debug', 'WP to Twitter Init' );
global $wp_version;
$wp_content_url = content_url();
$wp_content_dir = str_replace( '/plugins/wp-to-twitter','',plugin_dir_path( __FILE__ ) );
if ( defined('WP_CONTENT_URL') ) { $wp_content_url = constant('WP_CONTENT_URL');}
if ( defined('WP_CONTENT_DIR') ) { $wp_content_dir = constant('WP_CONTENT_DIR');}

define( 'WPT_DEBUG', false );
define( 'WPT_DEBUG_ADDRESS', 'debug@joedolson.com' );
define( 'WPT_FROM', "From: \"".get_option('blogname')."\" <".get_option('admin_email').">" );
// define( 'WPT_DEBUG_ADDRESS', 'debug@joedolson.com, yourname@youraddress.com' ); // for multiple recipients.

$wp_plugin_url = plugins_url();
include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); // required in order to access is_plugin_active()

require_once( plugin_dir_path(__FILE__).'/wp-to-twitter-oauth.php' );
require_once( plugin_dir_path(__FILE__).'/wp-to-twitter-shorteners.php' );
require_once( plugin_dir_path(__FILE__).'/wp-to-twitter-manager.php' );
require_once( plugin_dir_path(__FILE__).'/wpt-functions.php' );
require_once( plugin_dir_path(__FILE__).'/wpt-feed.php' );
require_once( plugin_dir_path(__FILE__).'/wpt-widget.php' );

global $wpt_version;
$wpt_version = "2.8.7";
$plugin_dir = basename(dirname(__FILE__));
load_plugin_textdomain( 'wp-to-twitter', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );

function wpt_pro_compatibility() {
	global $wptp_version;
	$current_wptp_version = '1.6.3';
	if ( version_compare( $wptp_version, $current_wptp_version, '<' ) ) {
		echo "<div class='error notice'><p class='upgrade'>".sprintf( __('The current version of WP Tweets PRO is <strong>%s</strong>. <a href="http://www.joedolson.com/articles/account/">Upgrade for best compatibility!</a>','wp-to-twitter'),$current_wptp_version )."</p></div>";
	}
}

$wpt_donate_url = "http://www.joedolson.com/articles/wp-tweets-pro/";

function wpt_commments_removed() {
	if ( isset($_GET['dismiss']) ) {
		update_option( 'wpt_dismissed', 'true' );
	}
	if ( get_option( 'comment-published-update' ) == 1 && !function_exists( 'wpt_pro_exists' ) && get_option( 'wpt_dismissed' ) != 'true' ) {
		$update_notice = sprintf( __('Tweeting of comments has been moved to <a href="%1$s">WP Tweets PRO</a>. You will need to upgrade in order to Tweet comments. <a href="%2$s">Dismiss</a>'), 'http://www.joedolson.com/articles/wp-tweets-pro/', admin_url( "options-general.php?page=wp-to-twitter/wp-to-twitter.php&dismiss=true" ) );
		if ( is_admin() ) {
			echo "<div class='updated'><p>".($update_notice)."</p></div>";
		}
	}
}

// check for OAuth configuration
function wpt_check_oauth( $auth=false ) {
	if ( !function_exists('wtt_oauth_test') ) {
		$oauth = false;
	} else {
		$oauth = wtt_oauth_test( $auth );
	}
	return $oauth;
}

function wpt_check_version() {
	global $wpt_version;
	$prev_version = get_option( 'wp_to_twitter_version' );
	if ( version_compare( $prev_version,$wpt_version,"<" ) ) {
		wptotwitter_activate();
	}
}

function wptotwitter_activate() {
	global $wpt_version;
	$prev_version = get_option( 'wp_to_twitter_version' );
	// this is a switch to plan for future versions
	$upgrade = version_compare( $prev_version, "2.3.1","<" );
	if ($upgrade) {
		$array = 
			array(
				'post'=> array(
						'post-published-update'=>get_option('newpost-published-update'),
						'post-published-text'=>get_option('newpost-published-text'),
						'post-edited-update'=>get_option('oldpost-edited-update'),
						'post-edited-text'=>get_option('oldpost-edited-text')
					),
				'page'=> array(
						'post-published-update'=>get_option('jd_twit_pages'),
						'post-published-text'=>get_option('newpage-published-text'),
						'post-edited-update'=>get_option('jd_twit_edited_pages'),
						'post-edited-text'=>get_option('oldpage-edited-text')				
					)
			);
		add_option( 'wpt_post_types', $array );
		add_option( 'comment-published-update', 0 );
		add_option( 'comment-published-text', 'New comment on #title# #url#' );
		delete_option('newpost-published-update');
		delete_option('newpost-published-text');
		delete_option('oldpost-edited-update');
		delete_option('oldpost-edited-text');
		delete_option('newpage-published-text');
		delete_option('oldpage-edited-text');
		delete_option( 'newpost-published-showlink' );
		delete_option( 'oldpost-edited-showlink' );
		delete_option( 'jd_twit_pages' );
		delete_option( 'jd_twit_edited_pages' );		
		delete_option( 'jd_twit_postie' );
	}
	$upgrade = version_compare( $prev_version, "2.3.3","<" );
	if ( $upgrade ) {
		delete_option( 'jd_twit_quickpress' );
	}
	$upgrade = version_compare( $prev_version, "2.3.4","<" );
	if ( $upgrade ) {
		add_option( 'wpt_inline_edits', '0' );
	}
	$upgrade = version_compare( $prev_version, "2.3.15","<" );
	if ( $upgrade ) {
		$use = get_option( 'use_tags_as_hashtags' );
		if ( $use == 1 ) {
			$wpt_settings = get_option( 'wpt_post_types' );
			$post_types = get_post_types( '', 'names' );
			foreach ( $post_types as $type ) {
				if ( isset($wpt_settings[$type]) ) {
					$t1 = $wpt_settings[$type]['post-published-text'].' #tags#';
					$t2 = $wpt_settings[$type]['post-edited-text'].' #tags#';
					$wpt_settings[$type]['post-published-text'] = $t1;
					$wpt_settings[$type]['post-edited-text'] = $t2;
				}
			}
			update_option('wpt_post_types',$wpt_settings );
		}
		delete_option( 'use_tags_as_hashtags' );
	}
	$upgrade = version_compare( $prev_version, "2.4.0","<" );
	if ( $upgrade ) {
		$perms = get_option('wtt_user_permissions');
		switch( $perms ) {
			case 'read':$update = 'subscriber';	break;
			case 'edit_posts':$update = 'contributor';	break;
			case 'publish_posts':$update = 'author';	break;
			case 'moderate_comments':$update = 'editor';	break;
			case 'manage_options':$update = 'administrator';	break;
			default:$update = 'administrator';
		}
		update_option( 'wtt_user_permissions',$update );
	}
	$upgrade = version_compare( $prev_version, "2.4.1","<" );
	if ( $upgrade ) {
		$subscriber = get_role('subscriber');
		$contributor = get_role('contributor');
		$author = get_role('author');
		$editor = get_role('editor');
		$administrator = get_role('administrator');
		$administrator->add_cap('wpt_twitter_oauth');
		$administrator->add_cap('wpt_twitter_custom');
		$administrator->add_cap('wpt_twitter_switch'); // can toggle tweet/don't tweet
		switch ( get_option('wtt_user_permissions') ) { // users that can add twitter information
			case 'subscriber': $subscriber->add_cap('wpt_twitter_oauth'); $contributor->add_cap('wpt_twitter_oauth'); $author->add_cap('wpt_twitter_oauth'); $editor->add_cap('wpt_twitter_oauth');   break;
			case 'contributor': $contributor->add_cap('wpt_twitter_oauth'); $author->add_cap('wpt_twitter_oauth'); $editor->add_cap('wpt_twitter_oauth');  break;
			case 'author': $author->add_cap('wpt_twitter_oauth'); $editor->add_cap('wpt_twitter_oauth'); break;
			case 'editor':$editor->add_cap('wpt_twitter_oauth'); break;
			case 'administrator': break;
			default: 
				$role = get_role( get_option('wtt_user_permissions') ); 
				if ( is_object($role) ) {			
					$role->add_cap('wpt_twitter_oauth');
				}
			break;
		}
		switch ( get_option('wtt_show_custom_tweet') ) { // users that can compose a custom tweet
			case 'subscriber': $subscriber->add_cap('wpt_twitter_custom'); $contributor->add_cap('wpt_twitter_custom'); $author->add_cap('wpt_twitter_custom'); $editor->add_cap('wpt_twitter_custom');   break;
			case 'contributor': $contributor->add_cap('wpt_twitter_custom'); $author->add_cap('wpt_twitter_custom'); $editor->add_cap('wpt_twitter_custom');  break;
			case 'author': $author->add_cap('wpt_twitter_custom'); $editor->add_cap('wpt_twitter_custom'); break;
			case 'editor':$editor->add_cap('wpt_twitter_custom'); break;
			case 'administrator': break;
			default: 
				$role = get_role( get_option('wtt_show_custom_tweet') );
				if ( is_object($role) ) {
					$role->add_cap('wpt_twitter_custom');
				}
			break;
		}
	}
	$upgrade = version_compare( $prev_version, "2.4.13","<" );
	if ( $upgrade ) {
		$administrator = get_role('administrator');
			$administrator->add_cap('wpt_can_tweet');
		$editor = get_role('editor');
			if ( is_object( $editor ) ) { $editor->add_cap('wpt_can_tweet'); }
		$author = get_role('author');
			if ( is_object( $author ) ) { $author->add_cap('wpt_can_tweet'); }
		$contributor = get_role('contributor');
			if ( is_object( $contributor ) ) { $contributor->add_cap('wpt_can_tweet'); }
		update_option('wpt_can_tweet','contributor');
	}
	update_option( 'wp_to_twitter_version',$wpt_version );
}	
	
// Function checks for an alternate URL to be Tweeted. Contribution by Bill Berry.	
function wpt_link( $post_ID ) {
	$ex_link = false;
       $external_link = get_option('jd_twit_custom_url'); 
       $permalink = get_permalink( $post_ID );
			if ( $external_link != '' ) {
				$ex_link = get_post_meta( $post_ID, $external_link, true );
			}
       return ( $ex_link ) ? $ex_link : $permalink;
}

function wpt_saves_error( $id, $auth, $twit, $error, $http_code, $ts ) {
	$http_code = (int) $http_code;
	if ( $http_code != 200 ) {
		add_post_meta( $id, '_wpt_failed', array( 'author'=>$auth, 'sentence'=>$twit, 'error'=>$error,'code'=>$http_code, 'timestamp'=>$ts ) );
	}
}

// This function performs the API post to Twitter
function jd_doTwitterAPIPost( $twit, $auth=false, $id=false, $media=false ) {
	if ( !wpt_check_oauth( $auth ) ) {
		$error = __('This account is not authorized to post to Twitter.','wp-to-twitter' );
		wpt_saves_error( $id, $auth, $twit, $error, '401', time() );
		wpt_set_log( 'wpt_status_message', $id, $error );
		return true;
	} // exit silently if not authorized
	$check = ( !$auth ) ? get_option('jd_last_tweet') : get_user_meta( $auth, 'wpt_last_tweet', true ); // get user's last tweet
	// prevent duplicate Tweets
	if ( $check == $twit ) {
		if ( WPT_DEBUG && function_exists( 'wpt_pro_exists' ) ) {
			wpt_mail( "Matched: tweet identical: #$id","$twit, $auth, $id" ); // DEBUG
		}
		$error = __( 'This tweet is identical to another Tweet recently sent to this account.','wp-to-twitter' ).' '.__( 'Twitter requires all Tweets to be unique.', 'wp-to-twitter' );
		wpt_saves_error( $id, $auth, $twit, $error, '403', time() );
		wpt_set_log( 'wpt_status_message', $id, $error );
		return true;
	} else if ( $twit == '' || !$twit ) {
		if ( WPT_DEBUG && function_exists( 'wpt_pro_exists' ) ) {
			wpt_mail( "Tweet check: empty sentence: #$id","$twit, $auth, $id"); // DEBUG
		}
		$error = __('This tweet was blank and could not be sent to Twitter.','wp-tweets-pro');
		wpt_saves_error( $id, $auth, $twit, $error, '403', time() );
		wpt_set_log( 'wpt_status_message', $id, $error );
		return true;	
	} else {
		// must be designated as media and have a valid attachment
		$attachment = ( $media ) ? wpt_post_attachment( $id ) : false;
		if ( $attachment ) {
			$meta = wp_get_attachment_metadata($attachment);
			if ( !isset( $meta['width'], $meta['height'] ) ) {
				if ( WPT_DEBUG && function_exists( 'wpt_pro_exists' ) ) {
					wpt_mail( "Image Data Does not Exist for Attachment #$attachment", print_r( $args, 1 ) );
				}
				$attachment = false;
			}
		}
		// support for HTTP deprecated as of 1/14/2014 -- https://dev.twitter.com/discussions/24239
		$api = ( $media && $attachment )?"https://api.twitter.com/1.1/statuses/update_with_media.json":"https://api.twitter.com/1.1/statuses/update.json";
		if ( wtt_oauth_test( $auth ) && ( $connection = wtt_oauth_connection( $auth ) ) ) {
			if ( $media && $attachment ) {
				$connection->media( $api, array( 'status' => $twit, 'source' => 'wp-to-twitter', 'include_entities' => 'true', 'id'=>$id, 'auth'=>$auth ) );
			} else {
				$connection->post( $api, array( 'status' => $twit, 'source' => 'wp-to-twitter', 'include_entities' => 'true' ) );
			}
			$http_code = ( $connection ) ? $connection->http_code : 'failed';
		} else if ( wtt_oauth_test( false ) && ( $connection = wtt_oauth_connection( false ) ) ) {
			if ( $media ) {
				$connection->media( $api, array( 'status' => $twit, 'source' => 'wp-to-twitter', 'include_entities' => 'true', 'id'=>$id, 'auth'=>$auth ) );				
			} else {
				$connection->post( $api, array( 'status' => $twit, 'source' => 'wp-to-twitter', 'include_entities' => 'true'	) );
			}
			$http_code = ( $connection ) ? $connection->http_code : 'failed';
		}
		if ( WPT_DEBUG && function_exists( 'wpt_pro_exists' ) ) {
			wpt_mail( 'Twitter Connection', print_r( $connection, 1 ) );
		}
		if ( $connection ) {
			if ( isset( $connection->http_header['x-access-level'] ) && $connection->http_header['x-access-level'] == 'read' ) { $supplement = sprintf( __('Your Twitter application does not have read and write permissions. Go to <a href="%s">your Twitter apps</a> to modify these settings.','wp-to-twitter'), 'https://dev.twitter.com/apps/' ); } else { $supplement = ''; }
			$return = false;
			switch ($http_code) {
				case '200':
					$return = true;
					$error = __("200 OK: Success!",'wp-to-twitter');
					update_option('wpt_authentication_missing', false );
					break;
				case '304':
					$error = __("304 Not Modified: There was no new data to return",'wp-to-twitter');
					break;					
				case '400':
					$error = __("400 Bad Request: The request was invalid. This is the status code returned during rate limiting.",'wp-to-twitter');
					break;
				case '401':
					$error = __("401 Unauthorized: Authentication credentials were missing or incorrect.",'wp-to-twitter');
					update_option( 'wpt_authentication_missing',"$auth");
					break;
				case '403':
					$error = __("403 Forbidden: The request is understood, but has been refused by Twitter. Possible reasons: too many Tweets, same Tweet submitted twice, Tweet longer than 140 characters.",'wp-to-twitter');
					break;
				case '404':
					$error = __("404 Not Found: The URI requested is invalid or the resource requested does not exist.",'wp-to-twitter');
					break;	
				case '406':
					$error = __("406 Not Acceptable: Invalid Format Specified.",'wp-to-twitter');
					break;
				case '422':
					$error = __("422 Unprocessable Entity: The image uploaded could not be processed..",'wp-to-twitter');
					break;
				case '429':
					$error = __("429 Too Many Requests: You have exceeded your rate limits.",'wp-to-twitter');
					break;					
				case '500':
					$error = __("500 Internal Server Error: Something is broken at Twitter.",'wp-to-twitter');
					break;
				case '502':
					$error = __("502 Bad Gateway: Twitter is down or being upgraded.",'wp-to-twitter');
					break;					
				case '503':
					$error = __("503 Service Unavailable: The Twitter servers are up, but overloaded with requests - Please try again later.",'wp-to-twitter');
					break;
				case '504':
					$error = __("504 Gateway Timeout: The Twitter servers are up, but the request couldn't be serviced due to some failure within our stack. Try again later.",'wp-to-twitter');
					break;
				default:
					$error = __("<strong>Code $http_code</strong>: Twitter did not return a recognized response code.",'wp-to-twitter');
					break;
			}
			$error .= ($supplement != '')?" $supplement":'';
			// debugging
				if ( WPT_DEBUG && function_exists( 'wpt_pro_exists' ) ) {
					wpt_mail( "Twitter Response: $http_code, #$id","$http_code, $error"); // DEBUG
				}			
			// end debugging
			$update = ( !$auth )?update_option( 'jd_last_tweet',$twit ):update_user_meta( $auth, 'wpt_last_tweet',$twit );
			wpt_saves_error( $id, $auth, $twit, $error, $http_code, time() );
			if ( $http_code == '200' ) {
				$jwt = get_post_meta( $id, '_jd_wp_twitter', true );
				if ( !is_array( $jwt ) ){ $jwt=array(); }
				$jwt[] = urldecode( $twit );
				if ( empty($_POST) ) { $_POST = array(); }
				$_POST['_jd_wp_twitter'] = $jwt;
				update_post_meta( $id, '_jd_wp_twitter', $jwt );
				if ( !function_exists( 'wpt_pro_exists' ) ) {
					// schedule a one-time promotional box for 4 weeks after first successful Tweet.
					if ( get_option( 'wpt_promotion_scheduled' ) == false ) {
						wp_schedule_single_event( time()+( 60*60*24*7*4 ), 'wpt_schedule_promotion_action' );
						update_option( 'wpt_promotion_scheduled', 1 );
					}
				}
			}
			if ( !$return ) {
				wpt_set_log( 'wpt_status_message', $id, $error );
			} else {
				wpt_set_log( 'wpt_status_message', $id, __( 'Tweet sent successfully.','wp-to-twitter' ) );	
			}
			return $return;			
		} else {
			wpt_set_log( 'wpt_status_message', $id, __( 'No Twitter OAuth connection found.','wp-to-twitter' ) );
			return false;
		}
	}
}

function fake_normalize( $string ) {
	if ( version_compare( PHP_VERSION, '5.0.0', '>=' ) && function_exists('normalizer_normalize') && 1==2 ) {
		if ( normalizer_is_normalized( $string ) ) { return $string; }
		return normalizer_normalize( $string );
	} else {
		return preg_replace( '~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml|mp);~i', '$1', htmlentities( $string, ENT_NOQUOTES, 'UTF-8' ) );
	}
}

function wpt_is_ssl( $url ) {
	if ( stripos( $url, 'https' ) ) { return true; } else { return false; }
}

function jd_truncate_tweet( $tweet, $post, $post_ID, $retweet=false, $ref=false ) {
	// media file occupies 22 characters, need to account for in shortening.
	$tweet_length = ( wpt_post_with_media( $post_ID ) ) ? 117 : 139; 
	$tweet = trim(custom_shortcodes( $tweet, $post_ID ));
	$shrink = ( $post['shortUrl'] != '' )?$post['shortUrl']:apply_filters( 'wptt_shorten_link', $post['postLink'], $post['postTitle'], $post_ID, false );
	// generate all template variable values
	$auth = $post['authId'];
	$title = trim( apply_filters( 'wpt_status', $post['postTitle'], $post_ID, 'title' ) );
	$blogname = trim($post['blogTitle']);
	$excerpt = trim( apply_filters( 'wpt_status', $post['postExcerpt'], $post_ID, 'post' ) );
	$thisposturl = trim($shrink);
	$category = trim($post['category']);
	$cat_desc = trim($post['cat_desc']);
	$user_account = get_user_meta( $auth,'wtt_twitter_username', true ) ;
	$tags = wpt_generate_hash_tags( $post_ID );
	$account = get_option('wtt_twitter_username');
	$date = trim($post['postDate']);
	$modified = trim($post['postModified']);
	if ( get_option( 'jd_individual_twitter_users' ) == 1 ) {
		if ( $user_account == '' ) {
			if ( get_user_meta( $auth, 'wp-to-twitter-enable-user',true ) == 'mainAtTwitter' ) {
				$account = stripcslashes(get_user_meta( $auth, 'wp-to-twitter-user-username',true ));
			} else if ( get_user_meta( $auth, 'wp-to-twitter-enable-user',true ) == 'mainAtTwitterPlus' ) {
				$account = stripcslashes(get_user_meta( $auth, 'wp-to-twitter-user-username',true ) . ' @' . get_option( 'wtt_twitter_username' ));
			}
		} else {
			$account = "$user_account";
		}
	}
	$display_name = get_the_author_meta( 'display_name', $auth );	
	// value of #author#
	$author = ( $account != '' )?"@$account":$display_name;	
	// value of #account# 
	$account = ( $account != '' )?"@$account":'';
	// value of #@# 
	$uaccount = ( $user_account != '' )?"@$user_account":"$account";	
	// clean up data if extra @ included //
	$account = str_ireplace( '@@','@',$account );
	$uaccount = str_ireplace( '@@', '@', $uaccount );
	$author = str_ireplace( '@@', '@', $author );
	
	if ( get_user_meta( $auth, 'wpt-remove', true ) == 'on' ) { $account = ''; }
	if ( get_option( 'jd_twit_prepend' ) != "" && $tweet != '' ) {
		$tweet = stripslashes(get_option( 'jd_twit_prepend' )) . " " . $tweet;
	}
	if ( get_option( 'jd_twit_append' ) != "" && $tweet != '' ) {
		$tweet = $tweet . " " . stripslashes(get_option( 'jd_twit_append' ));
	}
	$encoding = get_option('blog_charset');
	if ( $encoding == '' ) { $encoding = 'UTF-8'; } 
	
	$has_excerpt_tag = ( strpos( $tweet, '#post#' ) === false ) ? false : true; 
	
	if ( strpos( $tweet, '#url#' ) === false 
		&& strpos( $tweet, '#title#' ) === false
		&& strpos( $tweet, '#blog#' ) === false
		&& strpos( $tweet, '#post#' ) === false
		&& strpos( $tweet, '#category#' ) === false
		&& strpos( $tweet, '#date#' ) === false
		&& strpos( $tweet, '#author#' ) === false
		&& strpos( $tweet, '#displayname#' ) === false
		&& strpos( $tweet, '#tags#' ) === false
		&& strpos( $tweet, '#modified#' ) === false	
		&& strpos( $tweet, '#reference#' ) === false		
		&& strpos( $tweet, '#account#' ) === false	
		&& strpos( $tweet, '#@#' ) === false
		&& strpos( $tweet, '#cat_desc' ) === false
	) {
		// there are no tags in this Tweet. Truncate and return.
		$post_tweet = mb_substr( $tweet, 0, $tweet_length, $encoding ); 
		return $post_tweet;
	}
	if ( function_exists('wpt_pro_exists') && wpt_pro_exists() == true  ) {
		$reference = ( $ref ) ? $account : '@' . get_option( 'wtt_twitter_username' );
	}
	// create full unconditional post tweet - prior to truncation
	$replace = ( function_exists('wpt_pro_exists') && wpt_pro_exists() == true ) ? $reference : '' ;
	$search = array( '#account#', '#@#', '#reference#', '#url#', '#title#', '#blog#', '#post#', '#category#', '#cat_desc#', '#date#', '#author#', '#displayname#', '#tags#', '#modified#' );
	$replace = array( $account, $uaccount, $replace, $thisposturl, $title, $blogname, $excerpt, $category, $cat_desc, $date, $author, $display_name, $tags, $modified );
	$post_tweet = str_ireplace( $search, $replace, $tweet );

	$url_strlen = mb_strlen( urldecode( fake_normalize( $thisposturl ) ), $encoding );
	// check total length 
	$str_length = mb_strlen( urldecode( fake_normalize( $post_tweet ) ), $encoding );
	if ( $str_length < $tweet_length+1 ) {
		if ( mb_strlen( fake_normalize ( $post_tweet ) ) > $tweet_length+1 ) { 
			$post_tweet = mb_substr( $post_tweet,0,$tweet_length,$encoding ); 
		}
		return $post_tweet; // return early if all is well without replacements. 
	} else {
		// what is the excerpt supposed to be?
		$length = get_option( 'jd_post_excerpt' );
		// build an array of variable names and the number of characters in that variable.
		$length_array = array();
		$length_array['excerpt'] = mb_strlen( fake_normalize( $excerpt ),$encoding );
		$length_array['title'] = mb_strlen( fake_normalize( $title ),$encoding );
		$length_array['date'] = mb_strlen( fake_normalize( $date ),$encoding );		
		$length_array['category'] = mb_strlen( fake_normalize( $category ),$encoding );
		$length_array['cat_desc'] = mb_strlen( fake_normalize( $cat_desc ),$encoding );
		$length_array['@'] = mb_strlen( fake_normalize( $uaccount ),$encoding );
		$length_array['blogname'] = mb_strlen( fake_normalize( $blogname ),$encoding );
		$length_array['author'] = mb_strlen( fake_normalize( $author ),$encoding );
		$length_array['account'] = mb_strlen( fake_normalize( $account ),$encoding );
		if ( function_exists('wpt_pro_exists') && wpt_pro_exists() == true  ) {
			$length_array['reference'] = mb_strlen( fake_normalize( $reference ),$encoding );
		}
		$length_array['tags'] = mb_strlen( fake_normalize( $tags ),$encoding );
		$length_array['modified'] = mb_strlen( fake_normalize( $modified ),$encoding );
		// if the total length is too long, truncate items until the length is appropriate. 
		// Twitter's t.co shortener is mandatory. All URLS are max-character value set by Twitter.			
		$tco = ( wpt_is_ssl( $thisposturl ) )?23:22;
		$order = get_option( 'wpt_truncation_order' );
		if ( is_array( $order ) ) {
			asort($order);
			$preferred = array();
			foreach ( $order as $k => $v ) {
				$preferred[$k] = $length_array[$k];
			}
		} else {
			$preferred = $length_array;
		}
		$diff = ( ($url_strlen - $tco) > 0 )?$url_strlen-$tco:0;
		if ( $str_length > ( $tweet_length+ 1 + $diff ) ) {
			foreach ( $preferred AS $key=>$value ) {
				// don't truncate content of post excerpt if excerpt tag not in use
				if ( !( $key == 'excerpt' && !$has_excerpt_tag ) ) {
					$str_length = mb_strlen( urldecode( fake_normalize( trim( $post_tweet ) ) ),$encoding );
					if ( $str_length > ( $tweet_length + 1 + $diff ) ) {
						$trim = $str_length - ( $tweet_length + 1 + $diff );
						$old_value = ${$key};
						// prevent URL from being modified
						$post_tweet = str_ireplace( $thisposturl, '#url#', $post_tweet ); 
						// modify the value and replace old with new
						if ( $key == 'account' || $key == 'author' || $key == 'category' || $key == 'date' || $key == 'modified' || $key == 'reference' || $key == '@' ) {
							// these elements make no sense if truncated, so remove them entirely.
							$new_value = '';
						} else if ( $key == 'tags' ) {
							// remove any stray hash characters due to string truncation
							if (mb_strlen($old_value)-$trim <= 2) {
								$new_value = '';
							} else {
								$new_value = $old_value;
								while ((mb_strlen($old_value)-$trim) < mb_strlen($new_value)) {
									$new_value = trim(mb_substr($new_value,0,mb_strrpos($new_value,'#',$encoding)-1));
								}
							}
						} else {
							$new_value = mb_substr( $old_value,0,-( $trim ),$encoding );					
						}
						$post_tweet = str_ireplace( $old_value,$new_value,$post_tweet );
						// put URL back before checking length
						$post_tweet = str_ireplace( '#url#', $thisposturl, $post_tweet ); 					
					} else {
						if ( mb_strlen( fake_normalize ( $post_tweet ),$encoding ) > ( $tweet_length + 1 + $diff ) ) { 
							$post_tweet = mb_substr( $post_tweet,0,( $tweet_length + $diff ),$encoding ); 
						}
					}
				}
			}
		}
		// this is needed in case a tweet needs to be truncated outright and the truncation values aren't in the above.
		// 1) removes URL 2) checks length of remainder 3) Replaces URL
		if ( mb_strlen( fake_normalize( $post_tweet ) ) > $tweet_length + 1 ) {
			$temp = str_ireplace( $thisposturl, '#url#', $post_tweet );
			if ( mb_strlen( fake_normalize( $temp ) ) > ( ( $tweet_length + 1 ) - $tco) && $temp != $post_tweet ) { 
				$post_tweet = trim(mb_substr( $temp,0,( ( $tweet_length + 1 ) -$tco),$encoding ));
				// it's possible to trim off the #url# part in this process. If that happens, put it back.
				$sub_sentence = (strpos( $tweet, '#url#' )===false )?$post_tweet:$post_tweet .' '. $thisposturl;
				$post_tweet = ( strpos( $post_tweet,'#url#' ) === false )?$sub_sentence:str_ireplace( '#url#',$thisposturl,$post_tweet );
			} 
		}
	}
	return $post_tweet; // catch all, should never happen. But no reason not to include it.
}

function wpt_in_allowed_category( $array ) {
	$allowed_categories =  get_option( 'tweet_categories' );
	if ( is_array( $array ) && is_array( $allowed_categories ) ) {
	$common = @array_intersect( $array,$allowed_categories );
		if ( WPT_DEBUG && function_exists( 'wpt_pro_exists' ) ) {
			wpt_mail( 'Category Limits Results: ---', print_r($common,1) );
		}	
		if ( count( $common ) >= 1 ) {
			return true;
		} else {
			return false;
		}
	} else {
		return true;
	}
}

function jd_post_info( $post_ID ) {
	$encoding = get_option('blog_charset'); 
	if ( $encoding == '' ) { $encoding = 'UTF-8'; }
	$post = get_post( $post_ID );
	$category_ids = false;
	$values = array();
	$values['id'] = $post_ID;
	// get post author
	$values['postinfo'] = $post;
	$values['authId'] = $post->post_author;
		$postdate = $post->post_date;
		$altformat = "Y-m-d H:i:s";	
		$dateformat = (get_option('jd_date_format')=='')?get_option('date_format'):get_option('jd_date_format');
		$thisdate = mysql2date( $dateformat,$postdate );
		$altdate = mysql2date( $altformat, $postdate );
	$values['_postDate'] = $altdate;
	$values['postDate'] = $thisdate;
		$moddate = $post->post_modified;
	$values['_postModified'] = mysql2date( $altformat,$moddate );
	$values['postModified'] = mysql2date( $dateformat,$moddate );
	// get first category
	$category = $cat_desc = null;
	$categories = get_the_category( $post_ID );
	if ( is_array( $categories ) ) {
		if ( count($categories) > 0 ) {
			$category = $categories[0]->cat_name;
			$cat_desc = $categories[0]->description;
		} 
		foreach ($categories AS $cat) {
			$category_ids[] = $cat->term_id;
		}
	} else {
		$category = '';
		$cat_desc = '';
		$category_ids = array();
	}
	$values['categoryIds'] = $category_ids;
	$values['category'] = html_entity_decode( $category, ENT_COMPAT, $encoding );
	$values['cat_desc'] = html_entity_decode( $cat_desc, ENT_COMPAT, $encoding );
		$excerpt_length = get_option( 'jd_post_excerpt' );
	$post_excerpt = ( trim( $post->post_excerpt ) == "" )?@mb_substr( strip_tags( strip_shortcodes( $post->post_content ) ), 0, $excerpt_length ):@mb_substr( strip_tags( strip_shortcodes( $post->post_excerpt ) ), 0, $excerpt_length );
	$values['postExcerpt'] = html_entity_decode( $post_excerpt, ENT_COMPAT, $encoding );
	$thisposttitle =  stripcslashes( strip_tags( $post->post_title ) );
	if ( $thisposttitle == "" && isset( $_POST['title'] ) ) {
		$thisposttitle = stripcslashes( strip_tags( $_POST['title'] ) );
	}
	$values['postTitle'] = html_entity_decode( $thisposttitle, ENT_COMPAT, $encoding );
	$values['postLink'] = wpt_link( $post_ID );
	$values['blogTitle'] = get_bloginfo( 'name' );
	$values['shortUrl'] = wpt_short_url( $post_ID );
	$values['postStatus'] = $post->post_status;
	$values['postType'] = $post->post_type;
	$values = apply_filters( 'wpt_post_info',$values, $post_ID );
	return $values;
}

function wpt_short_url( $post_id ) {
	global $post_ID;
	if ( !$post_id ) { $post_id = $post_ID; }
	$jd_short = get_post_meta( $post_id, '_wp_jd_bitly', true );
	if ( $jd_short == "" ) {$jd_short = get_post_meta( $post_id, '_wp_jd_goo', true );}
	if ( $jd_short == "" ) {$jd_short = get_post_meta( $post_id, '_wp_jd_supr', true );	}
	if ( $jd_short == "" ) {$jd_short = get_post_meta( $post_id, '_wp_jd_wp', true );	}	
	if ( $jd_short == "" ) {$jd_short = get_post_meta( $post_id, '_wp_jd_ind', true );	}		
	if ( $jd_short == "" ) {$jd_short = get_post_meta( $post_id, '_wp_jd_yourls', true );}
	if ( $jd_short == "" ) {$jd_short = get_post_meta( $post_id, '_wp_jd_url', true );}
	if ( $jd_short == "" ) {$jd_short = get_post_meta( $post_id, '_wp_jd_joturl', true );}	
	if ( $jd_short == "" ) {$jd_short = get_post_meta( $post_id, '_wp_jd_target', true );}
	if ( $jd_short == "" ) {$jd_short = get_post_meta( $post_id, '_wp_jd_clig', true );}	
	return $jd_short;
}

function wpt_post_with_media( $post_ID, $post_info=array() ) {
	$return = false;
	if ( isset( $post_info['wpt_image'] ) && $post_info['wpt_image'] == 1 ) return $return;
	
	if ( !function_exists( 'wpt_pro_exists' ) || get_option( 'wpt_media') != '1' ) { 
		$return = false; 
	} else {
		if ( has_post_thumbnail( $post_ID ) || wpt_post_attachment( $post_ID ) ) {
			$return = true;
		}
	}
	return apply_filters( 'wpt_upload_media', $return, $post_ID );

}

function wpt_category_limit( $post_type, $post_info, $post_ID ) {
	$post_type_cats = get_object_taxonomies( $post_type );
	$continue = true; 					
	if ( in_array( 'category', $post_type_cats ) ) { 
	// 'category' is assigned to this post type, so apply filters.
		if ( get_option('jd_twit_cats') == '1' ) {
			$continue = ( !wpt_in_allowed_category( $post_info['categoryIds'] ) )?true:false;
		} else {
			$continue = ( wpt_in_allowed_category( $post_info['categoryIds'] ) )?true:false;
		}
	}
	if ( WPT_DEBUG && function_exists( 'wpt_pro_exists' ) && !$continue ) {
		wpt_mail(  "3b: Category limits applied #$post_ID", print_r($post_info['categoryIds'],1));
	}
	$continue = ( get_option('limit_categories') == '0' )?true:$continue;
	$args = array( 'type'=>$post_type, 'info'=>$post_info, 'id'=>$post_ID );
	return apply_filters( 'wpt_filter_terms', $continue, $args );
}

function jd_twit( $post_ID, $type='instant' ) {
	if ( WPT_DEBUG && function_exists( 'wpt_pro_exists' ) ) {
		wpt_mail(  "0: jd_twit running #$post_ID","Post ID: $post_ID / $type"); // DEBUG
	}	
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE || wp_is_post_revision($post_ID) ) { return $post_ID; }
	wpt_check_version();
	$jd_tweet_this = get_post_meta( $post_ID, '_jd_tweet_this', true );
	$newpost = $oldpost = $is_inline_edit = false;
	$sentence = $template = '';
	if ( get_option('wpt_inline_edits') != 1 ) {
		if ( isset( $_POST['_inline_edit'] ) || isset( $_REQUEST['bulk_edit'] ) ) { return; }
	} else {
		if ( isset( $_POST['_inline_edit'] ) || isset( $_REQUEST['bulk_edit'] ) ) { $is_inline_edit = true; }
	}
	if ( WPT_DEBUG && function_exists( 'wpt_pro_exists' ) ) {
		wpt_mail(  "1: JD Tweet This Value: #$post_ID","Tweet this: $jd_tweet_this /". get_option('jd_tweet_default')." / $type"); // DEBUG
	}
	if ( get_option('jd_tweet_default') == 0 ) { 
		$test = ( $jd_tweet_this != 'no' ) ? true : false;
	} else { 
		$test = ( $jd_tweet_this == 'yes' ) ? true : false;
	}
	if ( $test ) { // test switch: depend on default settings.
		$post_info = jd_post_info( $post_ID );
		$media = wpt_post_with_media( $post_ID, $post_info );
		if ( function_exists( 'wpt_pro_exists' ) && wpt_pro_exists() == true ) {
			$auth = ( get_option( 'wpt_cotweet_lock' ) == 'false' || !get_option('wpt_cotweet_lock') )?$post_info['authId']:get_option('wpt_cotweet_lock');
		} else {
			$auth = $post_info['authId'];
		}
		/* debug data */
		if ( WPT_DEBUG && function_exists( 'wpt_pro_exists' ) ) {
			wpt_mail( "2: POST Debug Data #$post_ID","Post_Info: ".print_r($post_info,1)."\n\nPOST: ".print_r($_POST, 1). " / $type" );
		}
		if ( function_exists( 'wpt_pro_exists' ) && wpt_pro_exists() == true && function_exists('wpt_filter_post_info') ) {
			$filter = wpt_filter_post_info( $post_info );
			if ( $filter == true ) {
				if ( WPT_DEBUG && function_exists( 'wpt_pro_exists' ) ) { 
					wpt_mail(  "3a: Post filtered: #$post_ID",print_r($post_info,1)." / $type"); 
				}
				return false;
			}
		}
		/* Filter Tweet based on POST data -- allows custom filtering of unknown plug-ins, etc. */
		$filter = apply_filters( 'wpt_filter_post_data', false, $_POST );
		if ( $filter ) {
			return false;
		}
		$post_type = $post_info['postType'];
		if ( $type == 'future' || get_post_meta( $post_ID, 'wpt_publishing' ) == 'future' ) { 
			$new = 1; // if this is a future action, then it should be published regardless of relationship
			if ( WPT_DEBUG && function_exists( 'wpt_pro_exists' ) ) { 
				wpt_mail(  "4: Future post: #$post_ID",print_r($post_info,1)." / $type"); 
			}
			delete_post_meta( $post_ID, 'wpt_publishing' );
		} else {
			// if the post modified date and the post date are the same, this is new.
			// true if first date before or equal to last date
			$new = wpt_date_compare( $post_info['_postModified'], $post_info['_postDate'] );
		}
		// post is not previously published but has been backdated: 
		// (post date is edited, but save option is 'publish')
		if ( $new == 0 && ( isset( $_POST['edit_date'] ) && $_POST['edit_date'] == 1 && !isset( $_POST['save'] ) ) ) { $new = 1; }
		// can't catch posts that were set to a past date as a draft, then published. 
		$post_type_settings = get_option('wpt_post_types');
		$post_types = array_keys($post_type_settings);
		if ( in_array( $post_type, $post_types ) ) {
			// identify whether limited by category/taxonomy
			$continue = wpt_category_limit( $post_type, $post_info, $post_ID );
			if ( $continue == false ) { return; }
			// create Tweet and ID whether current action is edit or new
			$cT = get_post_meta( $post_ID, '_jd_twitter', true );
			if ( isset( $_POST['_jd_twitter'] ) && $_POST['_jd_twitter'] != '' ) { $cT = $_POST['_jd_twitter']; }
			$customTweet = ( $cT != '' )?stripcslashes( trim( $cT ) ):'';
			// if ops is set and equals 'publish', this is being edited. Otherwise, it's a new post.
			if ( $new == 0 || $is_inline_edit == true ) {
				// if this is an old post and editing updates are enabled				
				if ( get_option( 'jd_tweet_default_edit' ) == 1 ) {
					$jd_tweet_this = apply_filters( 'wpt_tweet_this_edit', $jd_tweet_this, $_POST );
					if ( $jd_tweet_this != 'yes' ) {
						if ( WPT_DEBUG && function_exists( 'wpt_pro_exists' ) ) {
							wpt_mail(  "3c: Tweet this: not equal to yes","Exit // Post ID: $post_ID"); // DEBUG
						}
						return;
					}
				}
				if ( WPT_DEBUG && function_exists( 'wpt_pro_exists' ) ) {
					wpt_mail(  "4a: Edited post #$post_ID","Tweet this: ".print_r($post_info,1)." / $type"); // DEBUG
				}
				if ( $post_type_settings[$post_type]['post-edited-update'] == '1' ) {
					$nptext = stripcslashes( $post_type_settings[$post_type]['post-edited-text'] );
					$oldpost = true;
				}
			} else {
				if ( WPT_DEBUG && function_exists( 'wpt_pro_exists' ) ) {
					wpt_mail(  "4b: New Post #$post_ID","Tweet this: ".print_r($post_info,1)." / $type"); // DEBUG
				}				
				if ( $post_type_settings[$post_type]['post-published-update'] == '1' ) {
					$nptext = stripcslashes( $post_type_settings[$post_type]['post-published-text'] );			
					$newpost = true;
				}
			}
			if ( $newpost || $oldpost ) {
				$template = ( $customTweet != "" ) ? $customTweet : $nptext;
				$sentence = jd_truncate_tweet( $template, $post_info, $post_ID );
				if ( WPT_DEBUG && function_exists( 'wpt_pro_exists' ) ) {
					wpt_mail(  "5: Tweet Truncated #$post_ID","Truncated Tweet: $sentence / $template / $type"); // DEBUG
				}
				if ( function_exists('wpt_pro_exists') && wpt_pro_exists() == true  ) {
					$sentence2 = jd_truncate_tweet( $template, $post_info, $post_ID, false, $auth );
				}
			}
			if ( $sentence != '' ) {
				// WPT PRO //
				if ( function_exists( 'wpt_pro_exists' ) && wpt_pro_exists() == true ) {
					$wpt_selected_users = $post_info['wpt_authorized_users'];
					/* set up basic author/main account values */
					$auth_verified = wtt_oauth_test( $auth,'verify' );						
					if ( empty( $wpt_selected_users ) && get_option( 'jd_individual_twitter_users' ) == 1 ) { 
						$wpt_selected_users = ($auth_verified)? array( $auth ) : array( false ); 
					}
					if ( $post_info['wpt_cotweet'] == 1 || get_option( 'jd_individual_twitter_users' ) != 1 ) { 
						$wpt_selected_users['main'] = false; 
					}
					// filter selected users before using
					$wpt_selected_users = apply_filters( 'wpt_filter_users', $wpt_selected_users, $post_info );
					if ( $post_info['wpt_delay_tweet'] == 0 || $post_info['wpt_delay_tweet'] == '' || $post_info['wpt_no_delay'] == 'on' ) {
						foreach ( $wpt_selected_users as $acct ) {
							if ( wtt_oauth_test( $acct, 'verify' ) ) {
								$tweet = jd_doTwitterAPIPost( $sentence2, $acct, $post_ID, $media );
							}
						}
					} else {
						foreach ( $wpt_selected_users as $acct ) {
							if ( $auth != $acct ) {
								$offset = rand( 60,480 ); // offset by 1-8 minutes for additional users
							} else {
								$offset = 0;
							}
							if ( wtt_oauth_test( $acct,'verify' ) ) {
								$time = apply_filters( 'wpt_schedule_delay',( (int) $post_info['wpt_delay_tweet'] )*60, $acct );
								$scheduled = wp_schedule_single_event( time()+$time+$offset, 'wpt_schedule_tweet_action', array( 'id'=>$acct, 'sentence'=>$sentence, 'rt'=>0, 'post_id'=>$post_ID ) );
								$tweet = true; // if scheduled, return true.
								if ( WPT_DEBUG && function_exists( 'wpt_pro_exists' ) ) {
									if ( $acct ) { $author_id = "#$acct"; } else { $author_id = 'Main'; }
									wpt_mail(  "7a: Tweet Scheduled for Auth ID $author_id #$post_ID", print_r( array( 'id'=>$acct, 'sentence'=>$sentence, 'rt'=>0, 'post_id'=>$post_ID, 'timestamp'=>time()+$time+$offset, 'current_time'=>time(), 'timezone'=>get_option('gmt_offset'), 'scheduled'=>$scheduled, 'timestamp_string'=>date( 'Y-m-d H:i:s',time()+$time+$offset ),'current_time_string'=>date( 'Y-m-d H:i:s',time() ), ),1 ) ); // DEBUG
								}
							}
						}
					}
					/* This cycle handles scheduling the automatic retweets */
					if ( $post_info['wpt_retweet_after'] != 0 && $post_info['wpt_no_repost'] != 'on' ) {
						$repeat = $post_info['wpt_retweet_repeat'];
						$first = true;
						foreach ( $wpt_selected_users as $acct ) {
							if ( wtt_oauth_test( $acct,'verify' ) ) {
								for ( $i=1;$i<=$repeat;$i++ ) {
									switch( $i ) {
										case 1:
										$prepend = ( get_option('wpt_prepend') == 1 )?'':get_option('wpt_prepend_rt');
										$append = ( get_option('wpt_prepend') != 1 )?'':get_option('wpt_prepend_rt');
										break;
										case 2:
										$prepend = ( get_option('wpt_prepend') == 1 )?'':get_option('wpt_prepend_rt2');
										$append = ( get_option('wpt_prepend') != 1 )?'':get_option('wpt_prepend_rt2');
										break;
										case 3:
										$prepend = ( get_option('wpt_prepend') == 1 )?'':get_option('wpt_prepend_rt3');
										$append = ( get_option('wpt_prepend') != 1 )?'':get_option('wpt_prepend_rt3');
										break;
									}
									if ( get_option( 'wpt_custom_type' ) == 'template' ) {
										$retweet = jd_truncate_tweet( trim( $prepend.$append ), $post_info, $post_ID, true, $acct );
									} else {
										$retweet = jd_truncate_tweet( trim( $prepend.$template.$append ), $post_info, $post_ID, true, $acct );
									}
									// add original delay to schedule
									$delay = ( isset($post_info['wpt_delay_tweet'] ) )?( (int) $post_info['wpt_delay_tweet'] )*60:0;
									/* Don't delay the first Tweet of the group */
									$offset = ( $first == true )?0:rand(60,240); // delay each co-tweet by 1-4 minutes
									$time = apply_filters( 'wpt_schedule_retweet',($post_info['wpt_retweet_after'])*(60*60)*$i, $acct, $i, $post_info );
									wp_schedule_single_event( time()+$time+$offset+$delay, 'wpt_schedule_tweet_action', array( 'id'=>$acct, 'sentence'=>$retweet, 'rt'=>$i, 'post_id'=>$post_ID ) );
									$tweet = true;
									if ( $i == 4 ) { break; }
								}
							}
							$first = false;
						}
					}
				} else {
					$tweet = jd_doTwitterAPIPost( $sentence, false, $post_ID, $media );
				}
				// END WPT PRO //
			}
		} else {
			if ( WPT_DEBUG && function_exists( 'wpt_pro_exists' ) ) {
				wpt_mail( "3c: Not a Tweeted post type #$post_ID","Post_Info: ".print_r($post_info,1). " / $type" );
			}		
			return $post_ID;
		}
	}
	return $post_ID;
}

// Add Tweets on links in Blogroll
function jd_twit_link( $link_ID )  {
	wpt_check_version();
	global $wpt_version;
	$thislinkprivate = $_POST['link_visible'];
	if ($thislinkprivate != 'N') {
		$thislinkname = stripcslashes( $_POST['link_name'] );
		$thispostlink =  $_POST['link_url'] ;
		$thislinkdescription =  stripcslashes( $_POST['link_description'] );
		$sentence = stripcslashes( get_option( 'newlink-published-text' ) );
		$sentence = str_ireplace("#title#",$thislinkname,$sentence);
		$sentence = str_ireplace("#description#",$thislinkdescription,$sentence);		 

		if (mb_strlen( $sentence ) > 118) {
			$sentence = mb_substr($sentence,0,114) . '...';
		}
		$shrink = apply_filters( 'wptt_shorten_link', $thispostlink, $thislinkname, false, 'link' );
			if ( stripos($sentence,"#url#") === FALSE ) {
				$sentence = $sentence . " " . $shrink;
			} else {
				$sentence = str_ireplace("#url#",$shrink,$sentence);
			}						
			if ( $sentence != '' ) {
				$tweet = jd_doTwitterAPIPost( $sentence, false, $link_ID );
			}
		return $link_ID;
	} else {
		return;
	}
}

function wpt_generate_hash_tags( $post_ID ) {
	$hashtags = '';
	$term_meta = $t_id = false;
	$max_tags = get_option( 'jd_max_tags' );
	$max_characters = get_option( 'jd_max_characters' );
	$max_characters = ( $max_characters == 0 || $max_characters == "" )?100:$max_characters + 1;
	if ($max_tags == 0 || $max_tags == "") { $max_tags = 100; }
		$tags = get_the_tags( $post_ID );
		if ( $tags > 0 ) {
		$i = 1;
			foreach ( $tags as $value ) {
				if ( function_exists( 'wpt_pro_exists' ) ) {
					$t_id = $value->term_id; 
					$term_meta = get_option( "wpt_taxonomy_$t_id" );
				}	
				if ( get_option('wpt_tag_source') == 'slug' ) {
					$tag = $value->slug;
				} else {
					$tag = $value->name;
				}
				$strip = get_option( 'jd_strip_nonan' );
				$search = "/[^\p{L}\p{N}\s]/u";
				$replace = get_option( 'jd_replace_character' );
				$replace = ( $replace == "[ ]" || $replace == "" )?"":$replace;
				$tag = str_ireplace( " ",$replace,trim( $tag ) );
				$tag = preg_replace( '/[\/]/',$replace,$tag ); // remove forward slashes.
				if ($strip == '1') { $tag = preg_replace( $search, $replace, $tag ); }
				switch ( $term_meta ) {
					case 1 : $newtag = "#$tag"; break;
					case 2 : $newtag = "$$tag"; break;
					case 3 : $newtag = ''; break;
					default: $newtag = apply_filters( 'wpt_tag_default', "#", $t_id ).$tag;
				}
				if ( mb_strlen( $newtag ) > 2 && (mb_strlen( $newtag ) <= $max_characters) && ($i <= $max_tags) ) {
					$hashtags .= "$newtag ";
					$i++;
				}
			}
		}
	$hashtags = trim( $hashtags );
	if ( mb_strlen( $hashtags ) <= 1 ) { $hashtags = ""; }
	return $hashtags;	
}

add_action('admin_menu','jd_add_twitter_outer_box');

function jd_add_twitter_outer_box() {
	wpt_check_version();
	// add Twitter panel to post types where it's enabled.
	$wpt_post_types = get_option('wpt_post_types');
	if ( function_exists( 'add_meta_box' )) {
		if ( is_array( $wpt_post_types ) ) {
			foreach ($wpt_post_types as $key=>$value) {
				if ( $value['post-published-update'] == 1 || $value['post-edited-update'] == 1 ) {
					add_meta_box( 'wp2t','WP to Twitter', 'jd_add_twitter_inner_box', $key, 'side' );
				}
			}
		}
	}
}

function jd_add_twitter_inner_box( $post ) {
	if ( current_user_can( 'wpt_can_tweet' ) ) {
		$is_pro = ( function_exists( 'wpt_pro_exists' ) ) ? 'pro' : 'free';
		echo "<div class='wp-to-twitter $is_pro'>";
		global $wpt_donate_url;
		$tweet_status = '';
		$options = get_option('wpt_post_types');
		if ( is_object( $post ) ) {
			$type = $post->post_type;
			$status = $post->post_status;
			$post_id = $post->ID;
		}
		$jd_tweet_this = get_post_meta( $post_id, '_jd_tweet_this', true );
		if ( !$jd_tweet_this ) { 
			$jd_tweet_this = ( get_option( 'jd_tweet_default' ) == '1' ) ? 'no':'yes'; 
		}
		if ( isset( $_GET['action'] ) && $_GET['action'] == 'edit' && get_option( 'jd_tweet_default_edit' ) == '1' ) { $jd_tweet_this = 'no'; }
		if ( isset( $_REQUEST['message'] ) && $_REQUEST['message'] != 10 ) { // don't display when draft is updated or if no message
			if ( !( ( $_REQUEST['message'] == 1 ) && ( $status == 'publish' && $options[$type]['post-edited-update'] != 1 ) ) ) {
				$log = wpt_log( 'wpt_status_message', $post_id );
				$class = ( $log != 'Tweet sent successfully.' ) ? 'error' : 'updated' ;
				if ( $log != '' ) {
					echo "<div class='$class'><p>".wpt_log( 'wpt_status_message', $post_id )."</p></div>";
				}
			}
		}
		$previous_tweets = get_post_meta ( $post_id, '_jd_wp_twitter', true );
		$failed_tweets = get_post_meta( $post_id, '_wpt_failed' );
		$tweet = esc_attr( stripcslashes( get_post_meta( $post_id, '_jd_twitter', true ) ) );
		$tweet = apply_filters( 'wpt_user_text', $tweet, $status );
		$jd_template = ( $status == 'publish' )?$options[$type]['post-edited-text']:$options[$type]['post-published-text'];

		if ( $status == 'publish' && $options[$type]['post-edited-update'] != 1 ) {
			$tweet_status = sprintf(__('Tweeting %s edits is disabled.','wp-to-twitter'), $type );
		}
		
		if ( current_user_can('update_core') && function_exists( 'wpt_pro_exists' ) ) { 
			wpt_pro_compatibility(); 
		}
		if ( $tweet_status != '' ) { echo "<p class='disabled'>$tweet_status</p>"; } 
		if ( current_user_can( 'wpt_twitter_custom' ) || current_user_can('update_core') ) { ?>
			<p class='jtw'>
			<label for="jtw"><?php _e("Custom Twitter Post", 'wp-to-twitter', 'wp-to-twitter') ?></label><br /><textarea class="attachmentlinks" name="_jd_twitter" id="jtw" rows="2" cols="60"><?php echo esc_attr( $tweet ); ?></textarea>
			</p>
			<?php
			$jd_expanded = $jd_template;
				if ( get_option( 'jd_twit_prepend' ) != "" ) {
					$jd_expanded = "<span title='".__('Your prepended Tweet text; not part of your template.','wp-to-twitter')."'>".stripslashes( get_option( 'jd_twit_prepend' )) . "</span> " . $jd_expanded;
				}
				if ( get_option( 'jd_twit_append' ) != "" ) {
					$jd_expanded = $jd_expanded . " <span title='".__('Your appended Tweet text; not part of your template.','wp-to-twitter')."'>" . stripslashes(get_option( 'jd_twit_append' ))."</span>";
				}
			?>
			<p class='template'><?php _e('Your template:','wp-to-twitter'); ?> <code><?php echo stripcslashes( $jd_expanded ); ?></code></p>
			<?php 
			if ( get_option('jd_keyword_format') == 2 ) {
				$custom_keyword = get_post_meta( $post_id, '_yourls_keyword', true );
				echo "<label for='yourls_keyword'>".__('YOURLS Custom Keyword','wp-to-twitter')."</label> <input type='text' name='_yourls_keyword' id='yourls_keyword' value='$custom_keyword' />";
			}
		} else { ?>
			<input type="hidden" name='_jd_twitter' value='<?php echo esc_attr($tweet); ?>' />
			<?php 
		} 
		if ( current_user_can( 'wpt_twitter_switch' ) || current_user_can('update_core') ) {
			// "no" means 'Don't Tweet' (is checked)
			$nochecked = ( $jd_tweet_this == 'no' )?' checked="checked"':'';
			$yeschecked = ( $jd_tweet_this == 'yes' )?' checked="checked"':'';
			?>
			<p><input type="radio" name="_jd_tweet_this" value="no" id="jtn"<?php echo $nochecked; ?> /> <label for="jtn"><?php _e("Don't Tweet this post.", 'wp-to-twitter'); ?></label> <input type="radio" name="_jd_tweet_this" value="yes" id="jty"<?php echo $yeschecked; ?> /> <label for="jty"><?php _e("Tweet this post.", 'wp-to-twitter'); ?></label></p>
			<?php 
			} else { 
			?>
			<input type='hidden' name='_jd_tweet_this' value='<?php echo $jd_tweet_this; ?>' />
			<?php 
		} 
		?>
		<div class='wpt-options'>
			<?php 
				if ( $is_pro == 'pro' ) { $pro_active = " class='active'"; $free_active = ''; } else { $free_active = " class='active'"; $pro_active = ''; } 
			?>
			<ul class='tabs'>
				<li><a href='#authors'<?php echo $pro_active; ?>>Tweet to</a></li>
				<li><a href='#custom'>Options</a></li>
				<li><a href='#notes'<?php echo $free_active; ?>>Notes</a></li>
			</ul>
			<?php
		/* WPT PRO OPTIONS */ 
		if ( current_user_can( 'edit_others_posts' ) ) {
			echo "<div class='wptab' id='authors'>";		
			if ( get_option( 'jd_individual_twitter_users' ) == 1 ) {
				$selected = ( get_post_meta( $post_id, '_wpt_authorized_users', true ) )?get_post_meta( $post_id, '_wpt_authorized_users', true ):array();
				if ( function_exists( 'wpt_authorized_users' ) ) {
					echo wpt_authorized_users( $selected );
					do_action( 'wpt_authors_tab', $post_id, $selected );
				} else {
					echo "<p>";
						if ( function_exists( 'wpt_pro_exists' ) ) { 
							printf( __( 'WP Tweets PRO 1.5.2+ allows you to select Twitter accounts. <a href="%s">Log in and download now!</a>', 'wp-to-twitter' ), 'http://www.joedolson.com/articles/account/' );
						} else {
							printf( __( 'Upgrade to WP Tweets PRO to select Twitter accounts! <a href="%s">Upgrade now!</a>', 'wp-to-twitter' ), 'http://www.joedolson.com/articles/wp-tweets-pro/' );						
						}
					echo "</p>";				
				}
			}
			echo "</div>";
		} 
		?>
		<div class='wptab' id='custom'><?php
		if ( function_exists('wpt_pro_exists') && wpt_pro_exists() == true  && ( current_user_can( 'wpt_twitter_custom' ) || current_user_can( 'update_core' ) ) ) {
				wpt_schedule_values( $post_id ); 
				do_action( 'wpt_custom_tab', $post_id, 'visible' );			
		} else {
			printf( "<p>".__( 'Upgrade to WP Tweets PRO to configure options! <a href="%s">Upgrade now!</a>'."</p>", 'wp-to-twitter' ), 'http://www.joedolson.com/articles/wp-tweets-pro/' );						
		}
		?></div>
		<?php		
		/* WPT PRO */
		if ( !current_user_can( 'wpt_twitter_custom' ) && !current_user_can( 'update_core' ) ) { ?>
			<div class='wptab' id='custom'>
			<p><?php _e('Access to customizing WP to Twitter values is not allowed for your user role.','wp-to-twitter'); ?></p>
			<?php 
			if ( function_exists('wpt_pro_exists') && wpt_pro_exists() == true ) { 
				wpt_schedule_values( $post_id, 'hidden' );
				do_action( 'wpt_custom_tab', $post_id, 'hidden' );				
			} ?>
			</div>
			<?php 
		}			
		if ( current_user_can( 'wpt_twitter_custom' ) || current_user_can( 'update_core' ) ) { ?>
			<div class='wptab' id='notes'>
			<p>
			<?php _e("Tweets must be less than 140 characters; Twitter counts URLs as 22 or 23 characters. Template Tags: <code>#url#</code>, <code>#title#</code>, <code>#post#</code>, <code>#category#</code>, <code>#date#</code>, <code>#modified#</code>, <code>#author#</code>, <code>#account#</code>, <code>#tags#</code>, or <code>#blog#</code>.", 'wp-to-twitter');
			do_action( 'wpt_notes_tab', $post_id );
			?> 
			</p>
			</div>
			<?php 
		} ?>
		</div>		
		<p class="wpt-support">
		<?php if ( !function_exists( 'wpt_pro_exists' ) ) { ?>
			<a target="_blank" href="<?php echo admin_url('options-general.php?page=wp-to-twitter/wp-to-twitter.php'); ?>#get-support"><?php _e('Get Support', 'wp-to-twitter', 'wp-to-twitter') ?></a> &bull; <strong><a target="__blank" href="<?php echo $wpt_donate_url; ?>"><?php _e('Upgrade to WP Tweets Pro', 'wp-to-twitter', 'wp-to-twitter') ?></a></strong> &raquo;
		<?php } else { ?>
			<a target="_blank" href="<?php echo admin_url('admin.php?page=wp-tweets-pro'); ?>#get-support"><?php _e('Get Support', 'wp-to-twitter', 'wp-to-twitter') ?></a> &raquo;
		<?php } ?>
		</p>
		<?php wpt_show_tweets( $previous_tweets, $failed_tweets ); ?>
		</div>
		<?php
	} else { // permissions: this user isn't allowed to Tweet;
		_e('Your role does not have the ability to Post Tweets from this site.','wp-to-twitter'); ?> <input type='hidden' name='_jd_tweet_this' value='no' /> <?php
	}
} 

function wpt_show_tweets( $previous_tweets, $failed_tweets ) {
	if ( !is_array( $previous_tweets ) && $previous_tweets != '' ) { $previous_tweets = array( 0=>$previous_tweets ); }
	if ( ! empty( $previous_tweets ) || ! empty( $failed_tweets ) ) { ?>
		<hr>
		<p class='error'><em><?php _e('Previous Tweets','wp-to-twitter'); ?>:</em></p>
		<ul>
		<?php
		$has_history = false;
		$hidden_fields = '';
			if ( is_array( $previous_tweets ) ) {
				foreach ( $previous_tweets as $previous_tweet ) {
					if ( $previous_tweet != '' ) {
						$has_history = true;
						$hidden_fields .= "<input type='hidden' name='_jd_wp_twitter[]' value='".esc_attr($previous_tweet)."' />";
						echo "<li>$previous_tweet <a href='http://twitter.com/intent/tweet?text=".urlencode($previous_tweet)."'>Retweet this</a></li>";
					}
				}
			}
		?>
		</ul>
		<?php
		$list = false; $error_list = '';
		if ( is_array( $failed_tweets ) ) {
			foreach ( $failed_tweets as $failed_tweet ) {
				if ( !empty($failed_tweet) ) {
					$ft = $failed_tweet['sentence'];
					$reason = $failed_tweet['code'];
					$error = $failed_tweet['error'];
					$list = true;
					$error_list .= "<li> <code>Error: $reason</code> $ft <a href='http://twitter.com/intent/tweet?text=".urlencode($ft)."'>Tweet this</a><br /><em>$error</em></li>";
				}
			}
			if ( $list == true ) {
				"<p class='error'><em>".__('Failed Tweets','wp-to-twitter').":</em></p>
				<ul>$error_list</ul>";
			}
		}
		echo "<div>".$hidden_fields."</div>";
		if ( $has_history || $list ) {
			echo "<p><input type='checkbox' name='wpt_clear_history' id='wptch' value='clear' /> <label for='wptch'>".__('Delete Tweet History', 'wp-to-twitter' )."</label></p>";
		}
	}
}

function wpt_admin_scripts( $hook ) {
global $current_screen;
	if ( $current_screen->base == 'post' || $current_screen->id == 'wp-tweets-pro_page_wp-to-twitter-schedule' ) {
		wp_enqueue_script(  'charCount', plugins_url( 'wp-to-twitter/js/jquery.charcount.js'), array('jquery') );
	}
	//echo $current_screen->id;
	if ( $current_screen->id == 'settings_page_wp-to-twitter/wp-to-twitter' || $current_screen->id == 'toplevel_page_wp-tweets-pro'  ) {
		wp_register_script( 'wpt.tabs', plugins_url( 'js/tabs.js', __FILE__ ), array( 'jquery' ) );
		wp_enqueue_script( 'wpt.tabs' );
		wp_localize_script( 'wpt.tabs', 'firstItem', 'wpt_post' );
		wp_enqueue_script( 'dashboard' );		
	}
}
add_action( 'admin_enqueue_scripts', 'wpt_admin_scripts', 10, 1 );

function wpt_admin_script( $hook ) {
global $current_screen;
if ( $current_screen->base == 'post' || $current_screen->id == 'wp-tweets-pro_page_wp-to-twitter-schedule' ) {
	wp_register_style( 'wpt-post-styles', plugins_url('css/post-styles.css',__FILE__) );
	wp_enqueue_style('wpt-post-styles');
	if ( $current_screen->base == 'post' ) {
		$allowed = 140 - mb_strlen( get_option('jd_twit_prepend').get_option('jd_twit_append') );
	} else {
		$allowed = ( wpt_is_ssl( home_url() ) )?137:138;		
	}
	if ( function_exists( 'wpt_pro_exists' ) && get_option( 'jd_individual_twitter_users' ) == 1 ) { 
		$first = '#authors'; 
	} else if ( function_exists( 'wpt_pro_exists' ) ) { 
		$first = '#custom';
	} else {
		$first = '#notes'; 
	}
	// yuck, this is ugly. I should do something about it. Like localize, man.
	echo "
<script type='text/javascript'>
	jQuery(document).ready(function(\$){	
		\$('#jtw').charCount( { allowed: $allowed, counterText: '".__('Characters left: ','wp-to-twitter')."' } );
	});
	jQuery(document).ready(function(\$){
		\$('#side-sortables .tabs a[href=\"$first\"]').addClass('active');
		\$('#side-sortables .wptab').not('$first').hide();
		\$('#side-sortables .tabs a').on('click',function(e) {
			e.preventDefault();
			\$('#side-sortables .tabs a').removeClass('active');
			\$(this).addClass('active');
			var target = $(this).attr('href');
			\$('#side-sortables .wptab').not(target).hide();
			\$(target).show();
		});
	});
</script>
<style type='text/css'>
#wp2t h3 span { padding-left: 30px; background: url(".plugins_url('wp-to-twitter/images/twitter-bird-light-bgs.png').") left 50% no-repeat; }
</style>";
	}
}
add_action( 'admin_head', 'wpt_admin_script' );

// Post the Custom Tweet into the post meta table
function post_jd_twitter( $id ) {
	if ( empty($_POST) || ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) || wp_is_post_revision($id) || isset($_POST['_inline_edit'] ) || ( defined('DOING_AJAX') && DOING_AJAX ) || !wpt_in_post_type( $id ) ) { return; }
	if ( isset( $_POST['_yourls_keyword'] ) ) {
		$yourls = $_POST[ '_yourls_keyword' ];
		$update = update_post_meta( $id, '_yourls_keyword', $yourls );
	}
	if ( isset( $_POST[ '_jd_twitter' ] ) ) {
		$jd_twitter = $_POST[ '_jd_twitter' ];
		$update = update_post_meta( $id, '_jd_twitter', $jd_twitter );
	} 
	if ( isset( $_POST[ '_jd_wp_twitter' ] ) && $_POST['_jd_wp_twitter'] != '' ) {
		$jd_wp_twitter = $_POST[ '_jd_wp_twitter' ];
		$update = update_post_meta( $id, '_jd_wp_twitter', $jd_wp_twitter );
	}
	if ( isset( $_POST[ '_jd_tweet_this' ] ) ) {
		$jd_tweet_this = ( $_POST[ '_jd_tweet_this' ] == 'no')?'no':'yes';
		$update = update_post_meta( $id, '_jd_tweet_this', $jd_tweet_this );
	} else {
		if ( isset($_POST['_wpnonce'] ) ) {
			$jd_tweet_default = ( get_option( 'jd_tweet_default' ) == 1 )?'no':'yes';
			$update = update_post_meta( $id, '_jd_tweet_this', $jd_tweet_default );
		}
	}
	if ( isset( $_POST['wpt_clear_history'] ) && $_POST['wpt_clear_history'] == 'clear' ) {
		// delete stored Tweets and errors
		delete_post_meta( $id, '_wpt_failed' );
		delete_post_meta( $id, '_jd_wp_twitter' );
		// delete stored short URLs
		delete_post_meta( $id, '_wp_jd_supr' );
		delete_post_meta( $id, '_wp_jd_ind' );
		delete_post_meta( $id, '_wp_jd_bitly' );
		delete_post_meta( $id, '_wp_jd_wp' );
		delete_post_meta( $id, '_wp_jd_yourls' );
		delete_post_meta( $id, '_wp_jd_url' );
		delete_post_meta( $id, '_wp_jd_goo' );
		delete_post_meta( $id, '_wp_jd_target' );
		delete_post_meta( $id, '_wp_jd_clig' );
		delete_post_meta( $id, '_wp_jd_joturl' );
	}
	// WPT PRO //
	$update = apply_filters( 'wpt_insert_post', $_POST, $id );
	// WPT PRO //	
	// only send debug data if post meta is updated. 
	if ( WPT_DEBUG && function_exists( 'wpt_pro_exists' ) && ( $update == true || is_int( $update ) ) ) {
		wpt_mail( "Post Meta Inserted: #$id",print_r($_POST,1) ); // DEBUG
	}		
}

function jd_twitter_profile() {
	global $user_ID;
	get_currentuserinfo();
	if ( current_user_can( 'wpt_twitter_oauth' ) || current_user_can('update_core') ) {
		$user_edit = ( isset($_GET['user_id']) )?(int) $_GET['user_id']:$user_ID; 

		$is_enabled = get_user_meta( $user_edit, 'wp-to-twitter-enable-user',true );
		$twitter_username = get_user_meta( $user_edit, 'wp-to-twitter-user-username',true );
		$wpt_remove = get_user_meta( $user_edit, 'wpt-remove', true );
		?>
		<h3><?php _e('WP Tweets User Settings', 'wp-to-twitter'); ?></h3>
		<?php if ( function_exists('wpt_connect_oauth_message') ) { wpt_connect_oauth_message( $user_edit ); } ?>
		<table class="form-table">
		<tr>
			<th scope="row"><?php _e("Use My Twitter Username", 'wp-to-twitter'); ?></th>
			<td><input type="radio" name="wp-to-twitter-enable-user" id="wp-to-twitter-enable-user-3" value="mainAtTwitter"<?php if ($is_enabled == "mainAtTwitter") { echo " checked='checked'"; } ?> /> <label for="wp-to-twitter-enable-user-3"><?php _e("Tweet my posts with an @ reference to my username.", 'wp-to-twitter'); ?></label><br />
			<input type="radio" name="wp-to-twitter-enable-user" id="wp-to-twitter-enable-user-4" value="mainAtTwitterPlus"<?php if ($is_enabled == "mainAtTwitterPlus") { echo " checked='checked'"; } ?> /> <label for="wp-to-twitter-enable-user-3"><?php _e("Tweet my posts with an @ reference to both my username and to the main site username.", 'wp-to-twitter'); ?></label>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="wp-to-twitter-user-username"><?php _e("Your Twitter Username", 'wp-to-twitter'); ?></label></th>
			<td><input type="text" name="wp-to-twitter-user-username" id="wp-to-twitter-user-username" value="<?php echo esc_attr( $twitter_username ); ?>" /> <?php _e('Enter your own Twitter username.', 'wp-to-twitter'); ?></td>
		</tr>
		<tr>
			<th scope="row"><label for="wpt-remove"><?php _e("Hide account name in Tweets", 'wp-to-twitter'); ?></label></th>
			<td><input type="checkbox" name="wpt-remove" id="wpt-remove" value="on"<?php if ( $wpt_remove == 'on' ) { echo ' checked="checked"'; } ?> /> <?php _e('Do not display my account in the #account# template tag.', 'wp-to-twitter'); ?></td>
		</tr>
		<?php if ( !function_exists('wpt_pro_exists') ) { add_filter( 'wpt_twitter_user_fields',create_function('','return;') ); } ?>
		<?php echo apply_filters('wpt_twitter_user_fields',$user_edit ); ?>
		</table>
		<?php 
		if ( function_exists('wpt_schedule_tweet') ) {
			if ( function_exists('wtt_connect_oauth') ) { wtt_connect_oauth( $user_edit ); }
		}
	}
}

function custom_shortcodes( $sentence, $post_ID ) {
	$pattern = '/([([\[\]?)([A-Za-z0-9-_])*(\]\]]?)+/';
	$params = array(0=>"[[",1=>"]]");
	preg_match_all($pattern,$sentence, $matches);
	if ($matches && is_array($matches[0])) {
		foreach ($matches[0] as $value) {
			$shortcode = "$value";
			$field = str_replace($params, "", $shortcode);
			$custom = apply_filters( 'wpt_custom_shortcode',strip_tags(get_post_meta( $post_ID, $field, TRUE )), $post_ID, $field );
			$sentence = str_replace( $shortcode, $custom, $sentence );
		}
		return $sentence;
	} else {
		return $sentence;
	}
}

function jd_twitter_save_profile(){
	global $user_ID;
	get_currentuserinfo();
	if ( isset($_POST['user_id']) ) {
		$edit_id = (int) $_POST['user_id']; 
	} else {
		$edit_id = $user_ID;
	}
	$enable = ( isset($_POST['wp-to-twitter-enable-user']) )?$_POST['wp-to-twitter-enable-user']:'';
	$username = ( isset($_POST['wp-to-twitter-user-username']) )?$_POST['wp-to-twitter-user-username']:'';
	$wpt_remove = ( isset($_POST['wpt-remove']) )?'on':'';
	update_user_meta($edit_id ,'wp-to-twitter-enable-user' , $enable );
	update_user_meta($edit_id ,'wp-to-twitter-user-username' , $username );
	update_user_meta($edit_id ,'wpt-remove' , $wpt_remove );
	//WPT PRO
	apply_filters( 'wpt_save_user', $edit_id, $_POST );
}

// Add the administrative settings to the "Settings" menu.
function jd_addTwitterAdminPages() {
    if ( function_exists( 'add_options_page' ) && !function_exists( 'wpt_pro_functions') ) {
		 $plugin_page = add_options_page( 'WP to Twitter', 'WP to Twitter', 'manage_options', __FILE__, 'wpt_update_settings' );
    }
}
add_action( 'admin_head', 'jd_addTwitterAdminStyles' );
function jd_addTwitterAdminStyles() {
	if ( isset($_GET['page']) && ( $_GET['page'] == "wp-to-twitter" || $_GET['page'] == "wp-to-twitter/wp-to-twitter.php" || $_GET['page'] == "wp-tweets-pro" || $_GET['page'] == "wp-to-twitter-schedule" || $_GET['page'] == "wp-to-twitter-tweets" || $_GET['page'] == "wp-to-twitter-errors" ) ) {
		wp_enqueue_style( 'wpt-styles', plugins_url( '/wp-to-twitter/styles.css' ) );
	}
}

function jd_plugin_action($links, $file) {
	if ( $file == plugin_basename(dirname(__FILE__).'/wp-to-twitter.php') ) {
		$admin_url = ( is_plugin_active('wp-tweets-pro/wpt-pro-functions.php') )?admin_url('admin.php?page=wp-tweets-pro'):admin_url('options-general.php?page=wp-to-twitter/wp-to-twitter.php');
		global $wpt_donate_url;
		$links[] = "<a href='$admin_url'>" . __('Settings', 'wp-to-twitter', 'wp-to-twitter') . "</a>";
		if ( ! function_exists( 'wpt_pro_exists' ) ) { $links[] = "<a href='$wpt_donate_url'>" . __('Upgrade', 'wp-to-twitter', 'wp-to-twitter') . "</a>"; }	
	}
	return $links;
}
//Add Plugin Actions to WordPress
add_filter('plugin_action_links', 'jd_plugin_action', -10, 2);

if ( get_option( 'jd_individual_twitter_users')=='1') {
	add_action( 'show_user_profile', 'jd_twitter_profile' );
	add_action( 'edit_user_profile', 'jd_twitter_profile' );
	add_action( 'profile_update', 'jd_twitter_save_profile');
}

$admin_url = ( is_plugin_active('wp-tweets-pro/wpt-pro-functions.php') )?admin_url('admin.php?page=wp-tweets-pro'):admin_url('options-general.php?page=wp-to-twitter/wp-to-twitter.php');

add_action( 'in_plugin_update_message-wp-to-twitter/wp-to-twitter.php', 'wpt_plugin_update_message' );
function wpt_plugin_update_message() {
	global $wpt_version;
	$note = '';
	define('WPT_PLUGIN_README_URL',  'http://svn.wp-plugins.org/wp-to-twitter/trunk/readme.txt');
	$response = wp_remote_get( WPT_PLUGIN_README_URL, array ('user-agent' => 'WordPress/WP to Twitter' . $wpt_version . '; ' . get_bloginfo( 'url' ) ) );
	if ( ! is_wp_error( $response ) || is_array( $response ) ) {
		$data = $response['body'];
		$bits=explode('== Upgrade Notice ==',$data);
		$note = '<div id="wpt-upgrade"><p><strong style="color:#c22;">Upgrade Notes:</strong> '.nl2br(trim($bits[1])).'</p></div>';
	} else {
		printf(__('<br /><strong>Note:</strong> Please review the <a class="thickbox" href="%1$s">changelog</a> before upgrading.','wp-to-twitter'),'plugin-install.php?tab=plugin-information&amp;plugin=wp-to-twitter&amp;TB_iframe=true&amp;width=640&amp;height=594');
	}
	echo $note;
}

if ( get_option( 'jd_twit_blogroll' ) == '1' ) {
	add_action( 'add_link', 'jd_twit_link' );
}

add_action( 'save_post', 'wpt_twit', 16 );
add_action( 'save_post', 'post_jd_twitter', 10 ); 

function wpt_in_post_type( $id ) {
	$post_type_settings = get_option('wpt_post_types');
	$post_types = array_keys($post_type_settings);
	$type = get_post_type( $id );
	if ( in_array( $type, $post_types ) ) {
		if ( $post_type_settings[$type]['post-edited-update'] == '1' || $post_type_settings[$type]['post-published-update'] == '1' ) {
			return true;
		}
	}
	return false;
}

add_action( 'future_to_publish', 'wpt_future_to_publish', 16 );
function wpt_future_to_publish( $post ) {
	$id = $post->ID;
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE || wp_is_post_revision( $id ) || !wpt_in_post_type( $id ) ) { return; }
	wpt_twit_future( $id );
}

function wpt_twit( $id ) {
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE || wp_is_post_revision( $id ) || !wpt_in_post_type( $id ) ) return;
	$post = get_post( $id );
	if ( $post->post_status != 'publish' ) return; // is there any reason to accept any other status?
	wpt_twit_instant( $id );
}
add_action( 'xmlrpc_publish_post', 'wpt_twit_xmlrpc' ); 
add_action( 'publish_phone', 'wpt_twit_xmlrpc' );	

function wpt_twit_future( $id ) {
	set_transient( '_wpt_twit_future', $id, 10 );
	// instant action has already run for this post. // prevent running actions twice (need both for older WP)
	if ( get_transient ( '_wpt_twit_instant' ) && get_transient( '_wpt_twit_instant' ) == $id ) {
		delete_transient( '_wpt_twit_instant' );
		return;
	}	
	jd_twit( $id, 'future' );
}
function wpt_twit_instant( $id ) {
	set_transient( '_wpt_twit_instant', $id, 10 );
	// future action has already run for this post.
	if ( get_transient ( '_wpt_twit_future' ) && get_transient( '_wpt_twit_future' ) == $id ) {
		delete_transient( '_wpt_twit_future' );
		return;
	}
	// xmlrpc action has already run for this post.
	if ( get_transient ( '_wpt_twit_xmlrpc' ) && get_transient( '_wpt_twit_xmlrpc' ) == $id ) {
		delete_transient( '_wpt_twit_xmlrpc' );
		return;
	}	
	jd_twit( $id, 'instant' );
}
function wpt_twit_xmlrpc( $id ) {
	set_transient( '_wpt_twit_xmlrpc', $id, 10 );
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE || wp_is_post_revision( $id ) || !wpt_in_post_type( $id )  ) { return $id; }
	jd_twit( $id, 'xmlrpc' );
}

add_action( 'wpt_schedule_promotion_action', 'wpt_schedule_promotion' );
function wpt_schedule_promotion() {
	if ( !function_exists( 'wpt_pro_exists' ) && get_option( 'wpt_promotion_scheduled' ) == 1 ) {
		update_option( 'wpt_promotion_scheduled', 2 );
	}
}

function wpt_dismiss_promotion() {
	if ( isset($_GET['dismiss']) && $_GET['dismiss'] == 'promotion' ) {
		update_option( 'wpt_promotion_scheduled', 3 ); 
	}
}	
wpt_dismiss_promotion(); 

add_action( 'admin_notices', 'wpt_promotion_notice' );
function wpt_promotion_notice() {
	if ( current_user_can( 'activate_plugins' ) && get_option( 'wpt_promotion_scheduled' ) == 2 && get_option( 'jd_donations' ) != 1 ) {
		$upgrade = "http://www.joedolson.com/articles/wp-tweets-pro/";
		$dismiss = admin_url('options-general.php?page=wp-to-twitter/wp-to-twitter.php&dismiss=promotion');
		echo "<div class='updated fade'><p>".sprintf( __("I hope you've enjoyed <strong>WP to Twitter</strong>! Take a look at <a href='%s'>upgrading to WP Tweets PRO</a> for advanced Tweeting with WordPress! <a href='%s'>Dismiss</a>",'wp-to-twitter'), $upgrade, $dismiss )."</p></div>";
	}
}

add_action( 'admin_menu', 'jd_addTwitterAdminPages' );

/* Enqueue styles for Twitter feed */
add_action('wp_enqueue_scripts', 'wpt_stylesheet');
function wpt_stylesheet() {
	$apply = apply_filters( 'wpt_enqueue_feed_styles', true );
	if ( $apply ) {
		$file = apply_filters( 'wpt_feed_stylesheet', plugins_url( 'css/twitter-feed.css', __FILE__ ) );
		wp_register_style( 'wpt-twitter-feed', $file );
		wp_enqueue_style( 'wpt-twitter-feed' );
	}
}

add_filter( 'wpt_enqueue_feed_styles', 'wpt_permit_feed_styles' );
function wpt_permit_feed_styles( $value ) {
	if ( get_option( 'wpt_permit_feed_styles' ) == 1 ) {
		$value = false;
	}
	return $value;
}
/*
// Add notes about Tweet status to posts admin 
function wpt_column($cols) {
	$cols['wpt'] = __('Tweet Status','wp-to-twitter');
	return $cols;
}

// Echo the ID for the new column
function wpt_value($column_name, $id) {
	if ($column_name == 'wpt') {
		$marked = ucfirst( ( get_post_meta($id,'_jd_tweet_this',true) ) );
		echo $marked;
	}
}

function wpt_return_value($value, $column_name, $id) {
	if ( $column_name == 'wpt' ) {
		$value = $id;
	}
	return $value;
}
*/
// Output CSS for width of new column
add_action('admin_head', 'wpt_css');
function wpt_css() {
?>
<style type="text/css">
th#wpt { width: 60px; } 
.wpt_twitter .authorized { padding: 1px 3px; border-radius: 3px; background: #070; color: #fff; }
</style>
<?php	
}
/*
// Actions/Filters for various tables and the css output
add_action('admin_init', 'wpt_add');
function wpt_add() {
	$post_type_settings = get_option('wpt_post_types');
	$post_types = array_keys($post_type_settings);
	
	add_action('admin_head', 'wpt_css');
	if ( !$post_types || in_array( 'post', $post_types ) ) {
		add_filter('manage_posts_columns', 'wpt_column');
		add_action('manage_posts_custom_column', 'wpt_value', 10, 2);
	}
	if ( !$post_types || in_array( 'page', $post_types ) ) {
		add_filter('manage_pages_columns', 'wpt_column');
		add_action('manage_pages_custom_column', 'wpt_value', 10, 2);
	}	
	foreach ( $post_types as $types ) {
		add_action("manage_${types}_columns", 'wpt_column');			
		add_filter("manage_${types}_custom_column", 'wpt_value', 10, 2);
	}
} */
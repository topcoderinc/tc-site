<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// FUNCTION to see if checkboxes should be checked
function jd_checkCheckbox( $field,$sub1=false,$sub2='' ) {
	if ( $sub1 ) {
		$setting = get_option($field);
		if ( isset( $setting[$sub1] ) ) {
			$value = ( $sub2 != '' )?$setting[$sub1][$sub2]:$setting[$sub1];
		} else {
			$value = 0;
		}
		if ( $value == 1 ) {
			return 'checked="checked"';
		}
	}
	if( get_option( $field ) == '1'){
		return 'checked="checked"';
	}
}

function jd_checkSelect( $field, $value, $type='select' ) {
	if( get_option( $field ) == $value ) {
		return ( $type == 'select' )?'selected="selected"':'checked="checked"';
	}
}

function wpt_set_log( $data, $id, $message ) {
	if ( $id == 'test' ) {
		$log = update_option( $data, $message );
	} else {
		$log = update_post_meta( $id,'_'.$data, $message );
	}
	$last = update_option( $data.'_last', array( $id, $message ) );
}

function wpt_log( $data, $id ) {
	if ( $id == 'test' ) {
		$log = get_option( $data );
	} else if ( $id == 'last' ) {
		$log = get_option( $data.'_last' );
	} else {
		$log = get_post_meta( $id, '_'.$data, true );
	}
	return $log;
}

function jd_check_functions() {
	$message = "<div class='update'><ul>";
	// grab or set necessary variables
	$testurl =  get_bloginfo( 'url' );
	$shortener = get_option( 'jd_shortener' );
	$title = urlencode( 'Your blog home' );
	$shrink = apply_filters( 'wptt_shorten_link', $testurl, $title, false, true );
	if ( $shrink == false ) {
		$error = htmlentities( get_option('wpt_shortener_status') );
		$message .= __("<li class=\"error\"><strong>WP to Twitter was unable to contact your selected URL shortening service.</strong></li>",'wp-to-twitter');
		if ( $error != '' ) {
			$message .= "<li><code>$error</code></li>";
		} else {
			$message .= "<li><code>".__('No error message was returned.','wp-to-twitter' )."</code></li>";
		}
	} else {
		$message .= __("<li><strong>WP to Twitter successfully contacted your selected URL shortening service.</strong>  The following link should point to your blog homepage:",'wp-to-twitter');
		$message .= " <a href='$shrink'>$shrink</a></li>";	
	}
	//check twitter credentials
	if ( wtt_oauth_test() ) {
		$rand = rand( 1000000,9999999 );
		$testpost = jd_doTwitterAPIPost( "This is a test of WP to Twitter. $shrink ($rand)" );
			if ( $testpost ) {
				$message .= __("<li><strong>WP to Twitter successfully submitted a status update to Twitter.</strong></li>",'wp-to-twitter'); 
			} else {
				$error = wpt_log( 'wpt_status_message', 'test' );
				$message .=	__("<li class=\"error\"><strong>WP to Twitter failed to submit an update to Twitter.</strong></li>",'wp-to-twitter'); 
				$message .= "<li class=\"error\">$error</li>";
				}
	} else {
		$message .= "<strong>"._e('You have not connected WordPress to Twitter.','wp-to-twitter')."</strong> ";
	}
		// If everything's OK, there's  no reason to do this again.	
		if ($testpost == FALSE && $shrink == FALSE  ) {
			$message .= __("<li class=\"error\"><strong>Your server does not appear to support the required methods for WP to Twitter to function.</strong> You can try it anyway - these tests aren't perfect.</li>", 'wp-to-twitter');
		} else { 
		}
	if ( $testpost && $shrink ) {
	$message .= __("<li><strong>Your server should run WP to Twitter successfully.</strong></li>", 'wp-to-twitter');
	}
	$message .= "</ul>
	</div>";
	return $message;
}

function wpt_update_settings() {
	wpt_check_version();

	if ( !empty($_POST) ) {
		$nonce=$_REQUEST['_wpnonce'];
		if (! wp_verify_nonce($nonce,'wp-to-twitter-nonce') ) die("Security check failed");  
	}

	if ( isset($_POST['oauth_settings'] ) ) {
		$oauth_message = jd_update_oauth_settings( false, $_POST );
	}

	$message = "";

	// SET DEFAULT OPTIONS
	if ( get_option( 'twitterInitialised') != '1' ) {
		$initial_settings = array( 
			'post'=> array( 
					'post-published-update'=>1,
					'post-published-text'=>'New post: #title# #url#',
					'post-edited-update'=>1,
					'post-edited-text'=>'Post Edited: #title# #url#'
					),
			'page'=> array( 
					'post-published-update'=>0,
					'post-published-text'=>'New page: #title# #url#',
					'post-edited-update'=>0,
					'post-edited-text'=>'Page edited: #title# #url#'
					)
			);
		update_option( 'wpt_post_types', $initial_settings );
		update_option( 'jd_twit_blogroll', '1');
		update_option( 'newlink-published-text', 'New link: #title# #url#' );
		update_option( 'jd_shortener', '1' );
		update_option( 'jd_strip_nonan', '0' );
		update_option('jd_max_tags',3);
		update_option('jd_max_characters',15);	
		update_option('jd_replace_character','');
		update_option('wtt_user_permissions','administrator');
		$administrator = get_role('administrator');
		$administrator->add_cap('wpt_twitter_oauth');
		$administrator->add_cap('wpt_twitter_custom');
		$administrator->add_cap('wpt_twitter_switch');
		$administrator->add_cap('wpt_can_tweet');
		$editor = get_role('editor');
		if ( is_object( $editor ) ) { $editor->add_cap('wpt_can_tweet'); }
		$author = get_role('author');
		if ( is_object( $author ) ) { $author->add_cap('wpt_can_tweet'); }
		$contributor = get_role('contributor');
		if ( is_object( $contributor ) ) { $contributor->add_cap('wpt_can_tweet'); }
		update_option('wpt_can_tweet','contributor');
		update_option('wtt_show_custom_tweet','administrator');

		update_option( 'jd_twit_remote', '0' );
		update_option( 'jd_post_excerpt', 30 );
		// Use Google Analytics with Twitter
		update_option( 'twitter-analytics-campaign', 'twitter' );
		update_option( 'use-twitter-analytics', '0' );
		update_option( 'jd_dynamic_analytics','0' );
		update_option( 'no-analytics', 1 );
		update_option( 'use_dynamic_analytics','category' );			
		// Use custom external URLs to point elsewhere. 
		update_option( 'jd_twit_custom_url', 'external_link' );	
		// Error checking
		update_option( 'wp_url_failure','0' );
		// Default publishing options.
		update_option( 'jd_tweet_default', '0' );
		update_option( 'jd_tweet_default_edit','0' );
		update_option( 'wpt_inline_edits', '0' );
		// Note that default options are set.
		update_option( 'twitterInitialised', '1' );	
		//YOURLS API
		update_option( 'jd_keyword_format', '0' );
	}
	if ( get_option( 'twitterInitialised') == '1' && get_option( 'jd_post_excerpt' ) == "" ) { 
		update_option( 'jd_post_excerpt', 30 );
	}

// notifications from oauth connection		
    if ( isset( $_POST['oauth_settings'] ) ) {
		if ( $oauth_message == "success" ) {
			print('
				<div id="message" class="updated fade">
					<p>'.__('WP to Twitter is now connected with Twitter.', 'wp-to-twitter').'</p>
				</div>
			');
		} else if ( $oauth_message == "failed" ) {
			print('
				<div id="message" class="error fade">
					<p>'.__( 'WP to Twitter failed to connect with Twitter.', 'wp-to-twitter' ).' <strong>'.__('Error:','wp-to-twitter').'</strong> '.get_option( 'wpt_error' ).'</p>
				</div>
			');
		} else if ( $oauth_message == "cleared" ) {
			print('
				<div id="message" class="updated fade">
					<p>'.__('OAuth Authentication Data Cleared.', 'wp-to-twitter').'</p>
				</div>
			');		
		} else  if ( $oauth_message == 'nosync' ) {
			print('
				<div id="message" class="error fade">
					<p>'.__('OAuth Authentication Failed. Your server time is not in sync with the Twitter servers. Talk to your hosting service to see what can be done.', 'wp-to-twitter').'</p>
				</div>
			');
		} else {
			print('
				<div id="message" class="error fade">
					<p>'.__('OAuth Authentication response not understood.', 'wp-to-twitter').'</p>
				</div>			
			');
		}
	}
		
	if ( isset( $_POST['submit-type'] ) && $_POST['submit-type'] == 'advanced' ) {
		update_option( 'jd_tweet_default', ( isset( $_POST['jd_tweet_default'] ) )?$_POST['jd_tweet_default']:0 );
		update_option( 'jd_tweet_default_edit', ( isset( $_POST['jd_tweet_default_edit'] ) )?$_POST['jd_tweet_default_edit']:0 );		
		update_option( 'wpt_inline_edits', ( isset( $_POST['wpt_inline_edits'] ) )?$_POST['wpt_inline_edits']:0 );		
		update_option( 'jd_twit_remote',( isset( $_POST['jd_twit_remote'] ) )?$_POST['jd_twit_remote']:0 );
		update_option( 'jd_twit_custom_url', $_POST['jd_twit_custom_url'] );
		update_option( 'jd_strip_nonan', ( isset( $_POST['jd_strip_nonan'] ) )?$_POST['jd_strip_nonan']:0 );
		update_option( 'jd_twit_prepend', $_POST['jd_twit_prepend'] );	
		update_option( 'jd_twit_append', $_POST['jd_twit_append'] );
		update_option( 'jd_post_excerpt', $_POST['jd_post_excerpt'] );	
		update_option( 'jd_max_tags',$_POST['jd_max_tags']);
		update_option( 'wpt_tag_source', ( ( isset($_POST['wpt_tag_source']) && $_POST['wpt_tag_source'] == 'slug' )?'slug':'' ) );
		update_option( 'jd_max_characters',$_POST['jd_max_characters']);	
		update_option( 'jd_replace_character',$_POST['jd_replace_character']);
		update_option( 'jd_date_format',$_POST['jd_date_format'] );	
		update_option( 'jd_dynamic_analytics',$_POST['jd-dynamic-analytics'] );		
		
		$twitter_analytics = ( isset($_POST['twitter-analytics']) )?$_POST['twitter-analytics']:0;
		if ( $twitter_analytics == 1 ) {
			update_option( 'use_dynamic_analytics', 0 );
			update_option( 'use-twitter-analytics', 1 );
			update_option( 'no-analytics', 0 );			
		} else if ( $twitter_analytics == 2 ) {
			update_option( 'use_dynamic_analytics', 1 );
			update_option( 'use-twitter-analytics', 0 );
			update_option( 'no-analytics', 0 );
		} else {
			update_option( 'use_dynamic_analytics', 0 );
			update_option( 'use-twitter-analytics', 0 );
			update_option( 'no-analytics', 1 );
		}
		
		update_option( 'twitter-analytics-campaign', $_POST['twitter-analytics-campaign'] );
		update_option( 'jd_individual_twitter_users', ( isset( $_POST['jd_individual_twitter_users']  )? $_POST['jd_individual_twitter_users']:0 ) );
		$wtt_user_permissions = $_POST['wtt_user_permissions'];
		$prev = get_option('wtt_user_permissions');
		if ( $wtt_user_permissions != $prev ) {
			$subscriber = get_role('subscriber'); $subscriber->remove_cap('wpt_twitter_oauth');
			$contributor = get_role('contributor'); $contributor->remove_cap('wpt_twitter_oauth');
			$author = get_role('author'); $author->remove_cap('wpt_twitter_oauth');
			$editor = get_role('editor'); $editor->remove_cap('wpt_twitter_oauth');
			switch ( $wtt_user_permissions ) {
				case 'subscriber': 
					if ( is_object( $subscriber ) ) { 
						$subscriber->add_cap('wpt_twitter_oauth'); $contributor->add_cap('wpt_twitter_oauth'); $author->add_cap('wpt_twitter_oauth'); $editor->add_cap('wpt_twitter_oauth');   break;
					}					
				case 'contributor': 
					if ( is_object( $contributor ) ) { 
						$contributor->add_cap('wpt_twitter_oauth'); $author->add_cap('wpt_twitter_oauth'); $editor->add_cap('wpt_twitter_oauth');  break;
					}
				case 'author': 
					if ( is_object( $author ) ) { 
						$author->add_cap('wpt_twitter_oauth'); $editor->add_cap('wpt_twitter_oauth'); break;
					}
				case 'editor':
					if ( is_object( $editor ) ) { 
						$editor->add_cap('wpt_twitter_oauth'); break;
					}
				default: 
					$role = get_role( $wtt_user_permissions ); 
					$role->add_cap('wpt_twitter_oauth');
				break;
			}
		}
		update_option( 'wtt_user_permissions',$wtt_user_permissions);
		
		$wtt_show_custom_tweet = $_POST['wtt_show_custom_tweet'];
		$prev = get_option('wtt_show_custom_tweet');
		if ( $wtt_show_custom_tweet != $prev ) {
			$subscriber = get_role('subscriber'); $subscriber->remove_cap('wpt_twitter_custom');
			$contributor = get_role('contributor'); $contributor->remove_cap('wpt_twitter_custom');
			$author = get_role('author'); $author->remove_cap('wpt_twitter_custom');
			$editor = get_role('editor'); $editor->remove_cap('wpt_twitter_custom');
			switch ( $wtt_show_custom_tweet ) {
				case 'subscriber': 
					if ( is_object( $subscriber ) ) { 
						$subscriber->add_cap('wpt_twitter_custom'); $contributor->add_cap('wpt_twitter_custom'); $author->add_cap('wpt_twitter_custom'); $editor->add_cap('wpt_twitter_custom');   break;
					}
				case 'contributor': 
					if ( is_object( $contributor ) ) { 
						$contributor->add_cap('wpt_twitter_custom'); $author->add_cap('wpt_twitter_custom'); $editor->add_cap('wpt_twitter_custom');  break;
					}
				case 'author': 
					if ( is_object( $author ) ) { 
						$author->add_cap('wpt_twitter_custom'); $editor->add_cap('wpt_twitter_custom'); break;
					}
				case 'editor':
					if ( is_object( $editor ) ) { 
						$editor->add_cap('wpt_twitter_custom'); break;
					}
				default: 
					$role = get_role( $wtt_show_custom_tweet ); 
					$role->add_cap('wpt_twitter_custom');
				break;
			}
		}
		update_option( 'wtt_show_custom_tweet',$wtt_show_custom_tweet);
		
		$wpt_twitter_switch = $_POST['wpt_twitter_switch'];
		$prev = get_option('wpt_twitter_switch');
		if ( $wpt_twitter_switch != $prev ) {
			$subscriber = get_role('subscriber'); $subscriber->remove_cap('wpt_twitter_switch');
			$contributor = get_role('contributor'); $contributor->remove_cap('wpt_twitter_switch');
			$author = get_role('author'); $author->remove_cap('wpt_twitter_switch');
			$editor = get_role('editor'); $editor->remove_cap('wpt_twitter_switch');
			switch ( $wpt_twitter_switch ) {
				case 'subscriber': 
					if ( is_object( $subscriber ) ) { 
						$subscriber->add_cap('wpt_twitter_switch'); $contributor->add_cap('wpt_twitter_switch'); $author->add_cap('wpt_twitter_switch'); $editor->add_cap('wpt_twitter_switch');   break;
					}
				case 'contributor': 
					if ( is_object( $contributor ) ) { 
						$contributor->add_cap('wpt_twitter_switch'); $author->add_cap('wpt_twitter_switch'); $editor->add_cap('wpt_twitter_switch');  break;
					}
				case 'author': 
					if ( is_object( $author ) ) { 
						$author->add_cap('wpt_twitter_switch'); $editor->add_cap('wpt_twitter_switch'); break;
					}
				case 'editor':
					if ( is_object( $editor ) ) { 
						$editor->add_cap('wpt_twitter_switch'); break;
					}
				default: 
					$role = get_role( $wpt_twitter_switch ); 
					$role->add_cap('wpt_twitter_switch');
				break;
			}
		}
		update_option( 'wpt_twitter_switch',$wpt_twitter_switch);
		
		$wpt_can_tweet = $_POST['wpt_can_tweet'];
		$prev = get_option('wpt_can_tweet');
		if ( $wpt_can_tweet != $prev ) {
			$subscriber = get_role('subscriber'); $subscriber->remove_cap('wpt_can_tweet');
			$contributor = get_role('contributor'); $contributor->remove_cap('wpt_can_tweet');
			$author = get_role('author'); $author->remove_cap('wpt_can_tweet');
			$editor = get_role('editor'); $editor->remove_cap('wpt_can_tweet');
			switch ( $wpt_can_tweet ) {
				case 'subscriber': 
					if ( is_object( $subscriber ) ) { 
						$subscriber->add_cap('wpt_can_tweet'); $contributor->add_cap('wpt_can_tweet'); $author->add_cap('wpt_can_tweet'); $editor->add_cap('wpt_can_tweet');   break;
					}
				case 'contributor': 
					if ( is_object( $contributor ) ) { 
						$contributor->add_cap('wpt_can_tweet'); $author->add_cap('wpt_can_tweet'); $editor->add_cap('wpt_can_tweet');  break;
					}
				case 'author': 
					if ( is_object( $author ) ) { 
						$author->add_cap('wpt_can_tweet'); $editor->add_cap('wpt_can_tweet'); break;
					}
				case 'editor':
					if ( is_object( $editor ) ) { 
						$editor->add_cap('wpt_can_tweet'); break;
					}
				default: 
					$role = get_role( $wpt_can_tweet ); 
					$role->add_cap('wpt_can_tweet');
				break;
			}
		}
		update_option( 'wpt_can_tweet',$wpt_can_tweet);	
		update_option( 'wpt_permit_feed_styles', ( isset( $_POST['wpt_permit_feed_styles'] ) ) ? 1 : 0 );		
		update_option( 'wp_debug_oauth' , ( isset( $_POST['wp_debug_oauth'] ) )? 1 : 0 );
		update_option( 'jd_donations' , ( isset( $_POST['jd_donations'] ) )? 1 : 0 );
		$wpt_truncation_order = $_POST['wpt_truncation_order'];
		update_option( 'wpt_truncation_order', $wpt_truncation_order );
		$message .= __( 'WP to Twitter Advanced Options Updated' , 'wp-to-twitter');
	}
	
	if ( isset($_POST['submit-type']) && $_POST['submit-type'] == 'options' ) {
		// UPDATE OPTIONS
		$wpt_settings = get_option('wpt_post_types');
		foreach($_POST['wpt_post_types'] as $key=>$value) {
				$array = array( 
					'post-published-update'=>( isset( $value["post-published-update"] ) )?$value["post-published-update"]:"",
					'post-published-text'=>$value["post-published-text"],
					'post-edited-update'=>( isset( $value["post-edited-update"] ) )?$value["post-edited-update"]:"",
					'post-edited-text'=>$value["post-edited-text"]
					);
				$wpt_settings[$key] = $array;
		}
		update_option( 'wpt_post_types', $wpt_settings );
		update_option( 'newlink-published-text', $_POST['newlink-published-text'] );
		update_option( 'jd_twit_blogroll',(isset($_POST['jd_twit_blogroll']) )?$_POST['jd_twit_blogroll']:"" );
		$message = wpt_select_shortener( $_POST );	
		$message .= __( 'WP to Twitter Options Updated' , 'wp-to-twitter');
		$message = apply_filters( 'wpt_settings', $message, $_POST );
	}
	
	if ( isset($_POST['wpt_shortener_update']) && $_POST['wpt_shortener_update'] == 'true' ) {
		$message = wpt_shortener_update( $_POST );
	}
	
	// Check whether the server has supported for needed functions.
	if (  isset($_POST['submit-type']) && $_POST['submit-type'] == 'check-support' ) {
		$message = jd_check_functions();
	}
?>
<div class="wrap" id="wp-to-twitter">
<?php wpt_commments_removed(); ?>
<?php if ( $message ) { ?>
<div id="message" class="updated fade"><p><?php echo $message; ?></p></div>
<?php }
	$log = wpt_log( 'wpt_status_message', 'last' );
	if ( !empty( $log ) && is_array( $log ) ) {
		$post_ID = $log[0];
		$post = get_post( $post_ID );
		$title = $post->post_title;
		$notice = $log[1];
		echo "<div class='updated fade'><p><strong>".__('Last Tweet','wp-to-twitter')."</strong>: <a href='".get_edit_post_link( $post_ID )."'>$title</a> &raquo; $notice</p></div>";
	}
	if ( isset( $_POST['submit-type'] ) && $_POST['submit-type'] == 'clear-error' ) {
		delete_option( 'wp_url_failure' );
	}
	if ( get_option( 'wp_url_failure' ) == '1' ) { ?>
		<div class="error">
		<?php
		if ( get_option( 'wp_url_failure' ) == '1' ) {
			_e("<p>The query to the URL shortener API failed, and your URL was not shrunk. The full post URL was attached to your Tweet. Check with your URL shortening provider to see if there are any known issues.</p>", 'wp-to-twitter');
		} 
		$admin_url = ( is_plugin_active('wp-tweets-pro/wpt-pro-functions.php') )?admin_url('admin.php?page=wp-tweets-pro'):admin_url('options-general.php?page=wp-to-twitter/wp-to-twitter.php'); ?>
		<form method="post" action="<?php echo $admin_url; ?>">
		<div><input type="hidden" name="submit-type" value="clear-error" /></div>
		<?php $nonce = wp_nonce_field('wp-to-twitter-nonce', '_wpnonce', true, false).wp_referer_field(false);  echo "<div>$nonce</div>"; ?>	
		<p><input type="submit" name="submit" value="<?php _e("Clear 'WP to Twitter' Error Messages", 'wp-to-twitter'); ?>" class="button-primary" /></p>
		</form>		
		</div>
<?php
}
?>	
<h2><?php _e("WP to Twitter Options", 'wp-to-twitter'); ?></h2>
<div id="wpt_settings_page" class="postbox-container jcd-wide">

<?php 
	if ( isset($_GET['debug']) && $_GET['debug'] == 'true' ) {
		$debug = get_option( 'wpt_debug' );
		echo "<pre>";
			print_r( $debug );
		echo "</pre>";
	}
	if ( isset($_GET['debug']) && $_GET['debug'] == 'delete' ) {
		delete_option( 'wpt_debug' );
	}
	$wp_to_twitter_directory = get_bloginfo( 'wpurl' ) . '/' . WP_PLUGIN_DIR . '/' . dirname( plugin_basename(__FILE__) ); ?>
		
<div class="metabox-holder">

<?php if (function_exists('wtt_connect_oauth') ) { wtt_connect_oauth(); } ?>
<?php if (function_exists( 'wpt_pro_functions' ) ) { wpt_pro_functions(); } ?>
<div class="ui-sortable meta-box-sortables">
<div class="postbox">
	<div class="handlediv"><span class="screen-reader-text">Click to toggle</span></div>
	<h3 class='hndle'><span><?php _e('Status Update Templates','wp-to-twitter'); ?></span></h3>
	<div class="inside wpt-settings">
	<form method="post" action="">
	<?php $nonce = wp_nonce_field('wp-to-twitter-nonce', '_wpnonce', true, false).wp_referer_field(false);  echo "<div>$nonce</div>"; ?>
	<div>	
		<input type="submit" name="submit" value="<?php _e("Save WP to Twitter Options", 'wp-to-twitter'); ?>" class="button-primary button-side" />	
		<?php echo apply_filters('wpt_pick_shortener',''); ?>
		<?php 
			$post_types = get_post_types( array( 'public'=>true ), 'objects' );
			$wpt_settings = get_option('wpt_post_types');
			$tabs = "<ul class='tabs'>";
				foreach( $post_types as $type ) {
					$name = $type->labels->name;
					$singular = $type->labels->singular_name;
					$slug = $type->name;
					if ( $slug == 'attachment' || $slug == 'nav_menu_item' || $slug == 'revision' ) {
					} else {				
						$tabs .= "<li><a href='#wpt_$slug'>$name</a></li>";
					}
				}
			$tabs .= "<li><a href='#wpt_links'>".__('Links','wp-to-twitter')."</a></li>
			</ul>";
			echo $tabs;
				foreach( $post_types as $type ) {
					$name = $type->labels->name;
					$singular = $type->labels->singular_name;
					$slug = $type->name;
					if ( $slug == 'attachment' || $slug == 'nav_menu_item' || $slug == 'revision' ) {
					} else {
						$vowels = array( 'a','e','i','o','u' );
						foreach ( $vowels as $vowel ) {
							if ( strpos($name, $vowel ) === 0 ) { $word = 'an'; break; } else { $word = 'a'; }
						}
				?>
			<div class='wptab wpt_types wpt_<?php echo $slug; ?>' id='wpt_<?php echo $slug; ?>'>
			<?php 
			if ( get_option( 'limit_categories' ) != '0' && $slug == 'post' ) {
				$falseness = get_option( 'jd_twit_cats' );
				$categories = get_option( 'tweet_categories' );
				if ( $falseness == 1 ) { 
					echo "<p>".__('These categories are currently <strong>excluded</strong> by the deprecated WP to Twitter category filters.','wp-to-twitter' )."</p>"; 
				} else {
					echo "<p>".__('These categories are currently <strong>allowed</strong> by the deprecated WP to Twitter category filters.','wp-to-twitter' )."</p>"; 				
				}
				echo "<ul>";
				foreach ( $categories as $cat ) {
					$category = get_the_category_by_ID( $cat );
					echo "<li>$category</li>";
				}
				echo "</ul>";
				if ( !function_exists( 'wpt_pro_exists' ) ) {
					printf( __('<a href="%s">Upgrade to WP Tweets PRO</a> to filter posts in all custom post types on any taxonomy.','wp-to-twitter' ), "https://www.joedolson.com/articles/wp-tweets-pro/" );
				} else {
					_e( 'Updating the WP Tweets PRO taxonomy filters will overwrite your old category filters.','wp-to-twitter' );
				}				
			}
			?>
			<fieldset>
			<legend><span><?php echo $name ?></span></legend>
			<p>
				<input type="checkbox" name="wpt_post_types[<?php echo $slug; ?>][post-published-update]" id="<?php echo $slug; ?>-post-published-update" value="1" <?php echo jd_checkCheckbox('wpt_post_types',$slug,'post-published-update')?> />
				<label for="<?php echo $slug; ?>-post-published-update"><strong><?php printf(__('Update when %1$s %2$s is published','wp-to-twitter'),$word, $singular); ?></strong></label> <label for="<?php echo $slug; ?>-post-published-text"><br /><?php printf(__('Template for new %1$s updates','wp-to-twitter'),$name); ?></label><br /><input type="text" class="wpt-template" name="wpt_post_types[<?php echo $slug; ?>][post-published-text]" id="<?php echo $slug; ?>-post-published-text" size="60" maxlength="120" value="<?php if ( isset( $wpt_settings[$slug] ) ) { echo esc_attr( stripslashes( $wpt_settings[$slug]['post-published-text'] ) ); } ?>" />
			</p>
			<p>
				<input type="checkbox" name="wpt_post_types[<?php echo $slug; ?>][post-edited-update]" id="<?php echo $slug; ?>-post-edited-update" value="1" <?php echo jd_checkCheckbox('wpt_post_types',$slug,'post-edited-update')?> />
				<label for="<?php echo $slug; ?>-post-edited-update"><strong><?php printf(__('Update when %1$s %2$s is edited','wp-to-twitter'),$word, $singular); ?></strong></label><br /><label for="<?php echo $slug; ?>-post-edited-text"><?php printf(__('Template for %1$s editing updates','wp-to-twitter'),$name); ?></label><br /><input type="text" class="wpt-template" name="wpt_post_types[<?php echo $slug; ?>][post-edited-text]" id="<?php echo $slug; ?>-post-edited-text" size="60" maxlength="120" value="<?php if ( isset( $wpt_settings[$slug] ) ) { echo esc_attr( stripslashes( $wpt_settings[$slug]['post-edited-text'] ) ); } ?>" />	
			</p>
			</fieldset>
			<?php if ( function_exists( 'wpt_list_terms' ) ) { wpt_list_terms( $slug, $name ); } ?>
			</div>
			<?php
					}
				} 
			?>
			<div class='wptab wpt_types wpt_links' id="wpt_links">
				<fieldset>
				<legend><span><?php _e('Links','wp-to-twitter'); ?></span></legend>
				<p>
					<input type="checkbox" name="jd_twit_blogroll" id="jd_twit_blogroll" value="1" <?php echo jd_checkCheckbox('jd_twit_blogroll')?> />
					<label for="jd_twit_blogroll"><strong><?php _e("Update Twitter when you post a Blogroll link", 'wp-to-twitter'); ?></strong></label><br />				
					<label for="newlink-published-text"><?php _e("Text for new link updates:", 'wp-to-twitter'); ?></label> <input aria-labelledby="newlink-published-text-label" type="text" class="wpt-template" name="newlink-published-text" id="newlink-published-text" size="60" maxlength="120" value="<?php echo ( esc_attr( stripslashes( get_option( 'newlink-published-text' ) ) ) ); ?>" /><br /><span id="newlink-published-text-label"><?php _e('Available shortcodes: <code>#url#</code>, <code>#title#</code>, and <code>#description#</code>.','wp-to-twitter'); ?></span>
				</p>
				</fieldset>
			</div>
			<br class='clear' />
				<div>
		<input type="hidden" name="submit-type" value="options" />
		</div>
	<input type="submit" name="submit" value="<?php _e("Save WP to Twitter Options", 'wp-to-twitter'); ?>" class="button-primary" />	
	</div>
	</form>
</div>
</div>
</div>

<?php echo apply_filters( 'wpt_shortener_controls', '' ); ?>

<div class="ui-sortable meta-box-sortables">
<div class="postbox">
	<div class="handlediv"><span class="screen-reader-text">Click to toggle</span></div>
	<h3 class='hndle'><span><?php _e('Advanced Settings','wp-to-twitter'); ?></span></h3>
	<div class="inside">
	<form method="post" action="">
	<div>		
	<?php $nonce = wp_nonce_field('wp-to-twitter-nonce', '_wpnonce', true, false).wp_referer_field(false);  echo "<div>$nonce</div>"; ?>	
	<input type="submit" name="submit" value="<?php _e("Save Advanced WP to Twitter Options", 'wp-to-twitter'); ?>" class="button-primary button-side" />	
		
			<fieldset>
				<legend><?php _e('Tags','wp-to-twitter'); ?></legend>
			<p>
				 <input type="checkbox" name="jd_strip_nonan" id="jd_strip_nonan" value="1" <?php echo jd_checkCheckbox('jd_strip_nonan'); ?> /> <label for="jd_strip_nonan"><?php _e("Strip nonalphanumeric characters from tags",'wp-to-twitter'); ?></label>
			</p>
			<p>
			<input type="checkbox" name="wpt_tag_source" id="wpt_tag_source" value="slug" <?php echo jd_checkSelect( 'wpt_tag_source', 'slug', 'checkbox' ); ?> /> <label for="wpt_tag_source"><?php _e("Use tag slug as hashtag value",'wp-to-twitter'); ?></label><br />
			</p>
			<p>
				<label for="jd_replace_character"><?php _e("Spaces in tags replaced with:",'wp-to-twitter'); ?></label> <input type="text" name="jd_replace_character" id="jd_replace_character" value="<?php echo esc_attr( get_option('jd_replace_character') ); ?>" size="3" />
			</p>
			<p>
			<label for="jd_max_tags"><?php _e("Maximum number of tags to include:",'wp-to-twitter'); ?></label> <input aria-labelledby="jd_max_characters_label" type="text" name="jd_max_tags" id="jd_max_tags" value="<?php echo esc_attr( get_option('jd_max_tags') ); ?>" size="3" />
			<label for="jd_max_characters"><?php _e("Maximum length in characters for included tags:",'wp-to-twitter'); ?></label> <input type="text" name="jd_max_characters" id="jd_max_characters" value="<?php echo esc_attr( get_option('jd_max_characters') ); ?>" size="3" />
			</p>
			</fieldset>
			<fieldset>
			<legend><?php _e('Template Tag Settings','wp-to-twitter'); ?></legend>
			<p>
				<label for="jd_post_excerpt"><?php _e("Length of post excerpt (in characters):", 'wp-to-twitter'); ?></label> <input aria-labelledby="jd_post_excerpt_label" type="text" name="jd_post_excerpt" id="jd_post_excerpt" size="3" maxlength="3" value="<?php echo ( esc_attr( get_option( 'jd_post_excerpt' ) ) ) ?>" /> (<em id="jd_post_excerpt_label"><?php _e("Extracted from the post. If you use the 'Excerpt' field, it will be used instead.", 'wp-to-twitter'); ?></em>)
			</p>				
			<p>
				<label for="jd_date_format"><?php _e("WP to Twitter Date Formatting:", 'wp-to-twitter'); ?></label> <input type="text" aria-labelledby="date_format_label" name="jd_date_format" id="jd_date_format" size="12" maxlength="12" value="<?php if (get_option('jd_date_format')=='') { echo ( esc_attr( stripslashes( get_option('date_format') ) ) ); } else { echo ( esc_attr( get_option( 'jd_date_format' ) ) ); }?>" /> <?php if ( get_option( 'jd_date_format' ) != '' ) { echo date_i18n( get_option( 'jd_date_format' ) ); } else { echo "<em>".date_i18n( get_option( 'date_format' ) )."</em>"; } ?> (<em id="date_format_label"><?php _e("Default is from your general settings. <a href='http://codex.wordpress.org/Formatting_Date_and_Time'>Date Formatting Documentation</a>.", 'wp-to-twitter'); ?></em>)
			</p>
			
			<p>
				<label for="jd_twit_prepend"><?php _e("Custom text before all Tweets:", 'wp-to-twitter'); ?></label> <input type="text" name="jd_twit_prepend" id="jd_twit_prepend" size="20" value="<?php echo ( esc_attr( stripslashes( get_option( 'jd_twit_prepend' ) ) ) ) ?>" />
			</p>
			<p>
				<label for="jd_twit_append"><?php _e("Custom text after all Tweets:", 'wp-to-twitter'); ?></label> <input type="text" name="jd_twit_append" id="jd_twit_append" size="20" value="<?php echo ( esc_attr( stripslashes( get_option( 'jd_twit_append' ) ) ) ) ?>" />
			</p>
			<p>
				<label for="jd_twit_custom_url"><?php _e("Custom field for an alternate URL to be shortened and Tweeted:", 'wp-to-twitter'); ?></label> <input type="text" name="jd_twit_custom_url" id="jd_twit_custom_url" size="40" maxlength="120" value="<?php echo ( esc_attr( stripslashes( get_option( 'jd_twit_custom_url' ) ) ) ) ?>" />
			</p>
			</fieldset>
			
			<?php
			$inputs = '';
			$default_order = array( 
				'excerpt'=>0,
				'title'=>1,
				'date'=>2,
				'category'=>3,
				'blogname'=>4,
				'author'=>5,
				'account'=>6,
				'tags'=>7,
				'modified'=>8,
				'@'=>9,
				'cat_desc'=>10
				);
			$preferred_order = get_option( 'wpt_truncation_order' );
			if ( !$preferred_order ) $preferred_order = array();
			$preferred_order = array_merge( $default_order, $preferred_order );
			if ( is_array( $preferred_order ) ) { $default_order = $preferred_order; }
			asort($default_order);
			foreach ( $default_order as $k=>$v ) {
				$label = ucfirst($k);
				$inputs .= "<input type='text' size='2' value='$v' name='wpt_truncation_order[$k]' /> <label for='$k-$v'>$label</label><br />";
			}
			?>
			<fieldset>
			<legend><?php _e('Template tag priority order','wp-to-twitter'); ?></legend>
			<p><?php _e('The order in which items will be abbreviated or removed from your Tweet if the Tweet is too long to send to Twitter.','wp-to-twitter'); ?></p>
			<p>
			<?php echo $inputs; ?>
			</p>
			</fieldset>
		<fieldset>
		<legend><?php _e( "Special Cases when WordPress should send a Tweet",'wp-to-twitter' ); ?></legend>
			<p>
				<input type="checkbox" name="jd_tweet_default" id="jd_tweet_default" value="1" <?php echo jd_checkCheckbox('jd_tweet_default')?> />
				<label for="jd_tweet_default"><?php _e("Do not post Tweets by default", 'wp-to-twitter'); ?></label><br />
				<input type="checkbox" name="jd_tweet_default_edit" id="jd_tweet_default_edit" value="1" <?php echo jd_checkCheckbox('jd_tweet_default_edit')?> />
				<label for="jd_tweet_default_edit"><?php _e("Do not post Tweets by default (editing only)", 'wp-to-twitter'); ?></label><br />
				<input type="checkbox" name="wpt_inline_edits" id="wpt_inline_edits" value="1" <?php echo jd_checkCheckbox('wpt_inline_edits')?> />
				<label for="wpt_inline_edits"><?php _e("Allow status updates from Quick Edit", 'wp-to-twitter'); ?></label><br />
			</p>
		</fieldset>
		<fieldset>
		<legend><?php _e( "Google Analytics Settings",'wp-to-twitter' ); ?></legend>
				<p><?php _e("You can track the response from Twitter using Google Analytics by defining a campaign identifier here. You can either define a static identifier or a dynamic identifier. Static identifiers don't change from post to post; dynamic identifiers are derived from information relevant to the specific post. Dynamic identifiers will allow you to break down your statistics by an additional variable.","wp-to-twitter"); ?></p>	
			<p>
				<input type="radio" name="twitter-analytics" id="use-twitter-analytics" value="1" <?php echo jd_checkCheckbox('use-twitter-analytics')?> />
				<label for="use-twitter-analytics"><?php _e("Use a Static Identifier with WP-to-Twitter", 'wp-to-twitter'); ?></label><br />
				<label for="twitter-analytics-campaign"><?php _e("Static Campaign identifier for Google Analytics:", 'wp-to-twitter'); ?></label> <input type="text" name="twitter-analytics-campaign" id="twitter-analytics-campaign" size="40" maxlength="120" value="<?php echo ( esc_attr( get_option( 'twitter-analytics-campaign' ) ) ) ?>" /><br />
			</p>
			<p>
				<input type="radio" name="twitter-analytics" id="use-dynamic-analytics" value="2" <?php echo jd_checkCheckbox('use_dynamic_analytics')?> />
				<label for="use-dynamic-analytics"><?php _e("Use a dynamic identifier with Google Analytics and WP-to-Twitter", 'wp-to-twitter'); ?></label><br />
			<label for="jd-dynamic-analytics"><?php _e("What dynamic identifier would you like to use?","wp-to-twitter"); ?></label> 
				<select name="jd-dynamic-analytics" id="jd-dynamic-analytics">
					<option value="post_category"<?php echo jd_checkSelect( 'jd_dynamic_analytics','post_category'); ?>><?php _e("Category","wp-to-twitter"); ?></option>
					<option value="post_ID"<?php echo jd_checkSelect( 'jd_dynamic_analytics','post_ID'); ?>><?php _e("Post ID","wp-to-twitter"); ?></option>
					<option value="post_title"<?php echo jd_checkSelect( 'jd_dynamic_analytics','post_title'); ?>><?php _e("Post Title","wp-to-twitter"); ?></option>
					<option value="post_author"<?php echo jd_checkSelect( 'jd_dynamic_analytics','post_author'); ?>><?php _e("Author","wp-to-twitter"); ?></option>
				</select><br />
			</p>
			<p>
				<input type="radio" name="twitter-analytics" id="no-analytics" value="3" <?php echo jd_checkCheckbox('no-analytics'); ?> />	<label for="no-analytics"><?php _e("No Analytics", 'wp-to-twitter'); ?></label>
			</p>
		</fieldset>
		<fieldset id="indauthors">
		<legend><?php _e('Author Settings','wp-to-twitter'); ?></legend>
			<p>
				<input aria-labelledby="jd_individual_twitter_users_label" type="checkbox" name="jd_individual_twitter_users" id="jd_individual_twitter_users" value="1" <?php echo jd_checkCheckbox('jd_individual_twitter_users')?> />
				<label for="jd_individual_twitter_users"><?php _e("Authors have individual Twitter accounts", 'wp-to-twitter'); ?></label>
			</p>
			<p id="jd_individual_twitter_users_label"><?php _e('Authors can add their username in their user profile. With the free edition of WP to Twitter, it adds an @reference to the author. The @reference is placed using the <code>#account#</code> shortcode, which will pick up the main account if the user account isn\'t configured.', 'wp-to-twitter'); ?>
			</p>
		</fieldset>
		<fieldset>
		<legend><?php _e('Permissions','wp-to-twitter'); ?></legend>
		<?php
		global $wp_roles;
		$roles = $wp_roles->get_names();
		$roles = array_map( 'translate_user_role', $roles );
		$options = $permissions = $switcher = $can_tweet = '';
		foreach ( $roles as $role=>$rolename ) {
			$permissions .= ($role !='subscriber')?"<option value='$role'".wtt_option_selected(get_option('wtt_user_permissions'),$role,'option').">$rolename</option>\n":'';
			$options .= ($role !='subscriber')?"<option value='$role'".wtt_option_selected(get_option('wtt_show_custom_tweet'),$role,'option').">$rolename</option>\n":'';
			$switcher .= ($role !='subscriber')?"<option value='$role'".wtt_option_selected(get_option('wpt_twitter_switch'),$role,'option').">$rolename</option>\n":'';
			$can_tweet .= ($role !='subscriber')?"<option value='$role'".wtt_option_selected(get_option('wpt_can_tweet'),$role,'option').">$rolename</option>\n":'';
		}
		?>
		    <p>
			<select id="wtt_user_permissions" name="wtt_user_permissions">
				<?php echo $permissions; ?>
			</select> <label for="wtt_user_permissions"><?php _e('The lowest user group that can add their Twitter information','wp-to-twitter'); ?></label> 
			</p>
		    <p>
			<select id="wtt_show_custom_tweet" name="wtt_show_custom_tweet">
				<?php echo $options; ?>
			</select> <label for="wtt_show_custom_tweet"><?php _e('The lowest user group that can see the Custom Tweet options when posting','wp-to-twitter'); ?></label> 
			</p>
			<p>
			<select id="wpt_twitter_switch" name="wpt_twitter_switch">
				<?php echo $switcher; ?>
			</select> <label for="wpt_twitter_switch"><?php _e('The lowest user group that can toggle the Tweet/Don\'t Tweet option','wp-to-twitter'); ?></label> 
			</p>
			<p>
			<select id="wpt_can_tweet" name="wpt_can_tweet">
				<?php echo $can_tweet; ?>
			</select> <label for="wpt_can_tweet"><?php _e('The lowest user group that can send Twitter updates','wp-to-twitter'); ?></label> 
			</p>			
		</fieldset>
		<fieldset>
		<legend><?php _e('Error Messages and Debugging','wp-to-twitter'); ?></legend>
			<ul>
			<li><input type="checkbox" name="wpt_permit_feed_styles" id="wpt_permit_feed_styles" value="1" <?php echo jd_checkCheckbox('wpt_permit_feed_styles')?> /> <label for="wpt_permit_feed_styles"><?php _e("Disable Twitter Feed Stylesheet", 'wp-to-twitter'); ?></label></li>
			<li><input type="checkbox" name="wp_debug_oauth" id="wp_debug_oauth" value="1" <?php echo jd_checkCheckbox('wp_debug_oauth')?> /> <label for="wp_debug_oauth"><?php _e("Get Debugging Data for OAuth Connection", 'wp-to-twitter'); ?></label></li>
			<li><input type="checkbox" name="jd_donations" id="jd_donations" value="1" <?php echo jd_checkCheckbox('jd_donations')?> />	<label for="jd_donations"><strong><?php _e("I made a donation, so stop whinging at me, please.", 'wp-to-twitter'); ?></strong></label></li>
			</ul>
		</fieldset>
		<div>
		<input type="hidden" name="submit-type" value="advanced" />
		</div>
	<input type="submit" name="submit" value="<?php _e("Save Advanced WP to Twitter Options", 'wp-to-twitter'); ?>" class="button-primary" />	
	</div>
	</form>
</div>
</div>
</div>

	<div class="postbox" id="get-support">
	<div class="handlediv"><span class="screen-reader-text">Click to toggle</span></div>
	<h3 class='hndle'><span><?php _e('Get Plug-in Support','wp-to-twitter'); ?></span></h3>
		<div class="inside">
			<?php wpt_get_support_form(); ?>
		</div>
	</div>
	
	<form method="post" action="">
	<fieldset>
	<input type="hidden" name="submit-type" value="check-support" />
	<?php $nonce = wp_nonce_field('wp-to-twitter-nonce', '_wpnonce', true, false).wp_referer_field(false);  echo "<div>$nonce</div>"; ?>	
		<p>
		<input type="submit" name="submit" value="<?php _e('Check Support','wp-to-twitter'); ?>" class="button-primary" /> <?php _e('Check whether your server supports <a href="http://www.joedolson.com/articles/wp-to-twitter/">WP to Twitter\'s</a> queries to the Twitter and URL shortening APIs. This test will send a status update to Twitter and shorten a URL using your selected methods.','wp-to-twitter'); ?>
		</p>
	</fieldset>
	</form>		
</div>
</div>
<?php wpt_sidebar(); ?>
</div>
</div>
<?php global $wp_version; }

function wpt_sidebar() {
?>
<div class="postbox-container jcd-narrow">
<div class="metabox-holder">
	<div class="ui-sortable meta-box-sortables">
		<div class="postbox">
			<div class="handlediv"><span class="screen-reader-text">Click to toggle</span></div>
			<?php if (  !function_exists( 'wpt_pro_exists' ) ) { ?>
			<h3 class='hndle'><span><strong><?php _e('Support WP to Twitter','wp-to-twitter'); ?></strong></span></h3>
			<?php } else { ?>
			<h3 class='hndle'><span><strong><?php _e('WP to Twitter Support','wp-to-twitter'); ?></strong></span></h3>			
			<?php } ?>
			<div class="inside resources">
			<p>
			<a href="https://twitter.com/intent/follow?screen_name=joedolson" class="twitter-follow-button" data-size="small" data-related="joedolson">Follow @joedolson</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			</p>
			<?php if ( function_exists( 'wpt_pro_exists' ) ) { $support = admin_url('admin.php?page=wp-tweets-pro'); } else { $support = admin_url('options-general.php?page=wp-to-twitter/wp-to-twitter.php'); } ?>
			<a href="<?php echo $support; ?>#get-support"><?php _e("Get Support",'wp-to-twitter'); ?></a>
			<?php if ( get_option('jd_donations') != 1 && !function_exists( 'wpt_pro_exists' )  ) { ?>
			<p><?php _e('<a href="http://www.joedolson.com/donate.php">Make a donation today!</a><br />Every donation matters - donate $5, $20, or $100 today!','wp-to-twitter'); ?></p>
			<div class='donations'>
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
				<div>
				<input type="hidden" name="cmd" value="_s-xclick" />
				<input type="hidden" name="hosted_button_id" value="8490399" />
				<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" name="submit" alt="Donate" />
				<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
				</div>
			</form>
			<a href="http://flattr.com/thing/559528/WP-to-Twitter"><img src="http://api.flattr.com/button/flattr-badge-large.png" alt="Flattr WP to Twitter" /></a>				
			</div>
		<?php } ?>
			</div>
		</div>
	</div>
	<?php if ( !function_exists( 'wpt_pro_exists' )  ) { ?>
	<div class="ui-sortable meta-box-sortables">
		<div class="postbox">
			<div class="handlediv"><span class="screen-reader-text">Click to toggle</span></div>
			<h3 class='wpt-upgrade hndle'><span><strong><?php _e('Upgrade Now!','wp-to-twitter'); ?></strong></span></h3>
			<div class="inside purchase">
				<strong><a href="http://www.joedolson.com/articles/wp-tweets-pro/"><?php _e('Upgrade to <strong>WP Tweets PRO</strong>!','wp-to-twitter'); ?></a></strong>
			<p><?php _e('Bonuses in the PRO upgrade:','wp-to-twitter'); ?></p>
			<ul>
				<li><?php _e('Authors can post to their own Twitter accounts','wp-to-twitter'); ?></li>
				<li><?php _e('Delay Tweets minutes or hours after you publish','wp-to-twitter'); ?></li>
				<li><?php _e('Automatically schedule Tweets to post again later','wp-to-twitter'); ?></li>
				<li><?php _e('Send Tweets for approved comments','wp-to-twitter'); ?></li>
				<li><?php _e('Filter Tweets by category, tag, or custom taxonomy','wp-to-twitter'); ?></li>
			</ul>
			
			</div>
		</div>
	</div>
	<?php 
	} else { 
		if ( function_exists( 'wpt_notes' ) ) { 
			wpt_notes();
		}
	} ?>
	<div class="ui-sortable meta-box-sortables">
		<div class="postbox">
			<div class="handlediv"><span class="screen-reader-text">Click to toggle</span></div>
			<h3 class='hndle'><span><?php _e('Shortcodes','wp-to-twitter'); ?></span></h3>
		<div class="inside">
			<p><?php _e("Available in post update templates:", 'wp-to-twitter'); ?></p>
			<ul>
			<li><?php _e("<code>#title#</code>: the title of your blog post", 'wp-to-twitter'); ?></li>
			<li><?php _e("<code>#blog#</code>: the title of your blog", 'wp-to-twitter'); ?></li>
			<li><?php _e("<code>#post#</code>: a short excerpt of the post content", 'wp-to-twitter'); ?></li>
			<li><?php _e("<code>#category#</code>: the first selected category for the post", 'wp-to-twitter'); ?></li>
			<li><?php _e("<code>#cat_desc#</code>: custom value from the category description field",'wp-to-twitter'); ?></li>			
			<li><?php _e("<code>#date#</code>: the post date", 'wp-to-twitter'); ?></li>
			<li><?php _e("<code>#modified#</code>: the post modified date", 'wp-to-twitter'); ?></li>
			<li><?php _e("<code>#url#</code>: the post URL", 'wp-to-twitter'); ?></li>
			<li><?php _e("<code>#author#</code>: the post author (@reference if available, otherwise display name)",'wp-to-twitter'); ?></li>
			<li><?php _e("<code>#displayname#</code>: post author's display name", 'wp-to-twitter'); ?></li>
			<li><?php _e("<code>#account#</code>: the twitter @reference for the account (or the author, if author settings are enabled and set.)",'wp-to-twitter'); ?></li>
			<li><?php _e("<code>#@#</code>: the twitter @reference for the author or blank, if not set",'wp-to-twitter'); ?></li>
			<li><?php _e("<code>#tags#</code>: your tags modified into hashtags. See options in the Advanced Settings section, below.",'wp-to-twitter'); ?></li>
<?php if ( function_exists('wpt_pro_exists') && wpt_pro_exists() == true ) { ?>
			<li><?php _e("<code>#reference#</code>: Used only in co-tweeting. @reference to main account when posted to author account, @reference to author account in post to main account.",'wp-to-twitter'); ?></li> 
<?php } ?>
			</ul>
			<p><?php _e("You can also create custom shortcodes to access WordPress custom fields. Use doubled square brackets surrounding the name of your custom field to add the value of that custom field to your status update. Example: <code>[[custom_field]]</code></p>", 'wp-to-twitter'); ?>
		</div>
		</div>
	</div>
</div>
<?php
}
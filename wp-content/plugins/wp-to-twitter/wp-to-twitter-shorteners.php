<?php
//jd_shorten_link
//jd_expand_url
//jd_expand_yourl
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( !function_exists( 'jd_shorten_link' ) ) { // prep work for future plug-in replacement.
	add_filter( 'wptt_shorten_link','jd_shorten_link', 10, 4 );

	function jd_shorten_link( $url, $thisposttitle, $post_ID, $testmode=false ) {
		if ( WPT_DEBUG && function_exists( 'wpt_pro_exists' ) ) {
			wpt_mail( "Initial Link Data: #$post_ID","$url, $thisposttitle, $post_ID, $testmode" ); // DEBUG
		}
			// filter link before sending to shortener or adding analytics
			$url = apply_filters('wpt_shorten_link',$url,$post_ID );
			if ($testmode == false ) {
				if ( get_option('use-twitter-analytics') == 1 || get_option('use_dynamic_analytics') == 1 ) {
					if ( get_option('use_dynamic_analytics') == '1' ) {
						$campaign_type = get_option('jd_dynamic_analytics');
						if ( $campaign_type == "post_category" && $testmode != 'link' ) {
							$category = get_the_category( $post_ID );
							$campaign = sanitize_title( $category[0]->cat_name );
						} else if ( $campaign_type == "post_ID") {
							$campaign = $post_ID;
						} else if ( $campaign_type == "post_title" && $testmode != 'link' ) {
							$post = get_post( $post_ID );
							$campaign = sanitize_title( $post->post_title ); 
						} else {
							if ( $testmode != 'link' ) {
								$post = get_post( $post_ID );
								$post_author = $post->post_author;
								$campaign = get_the_author_meta( 'user_login',$post_author );
							} else {
								$post_author = '';
								$campaign = '';
							}
						}
					} else {
						$campaign = get_option('twitter-analytics-campaign');
					}
					if ( strpos( $url,"%3F" ) === FALSE && strpos( $url,"?" ) === FALSE ) {
						$ct = "?";
					} else {
						$ct = "&";
					}
					$medium = apply_filters( 'wpt_utm_medium', 'twitter' );
					$source = apply_filters( 'wpt_utm_source', 'twitter' );
					$ga = "utm_campaign=$campaign&utm_medium=$medium&utm_source=$source";
					$url .= $ct .= $ga;
				}
				$url = urldecode(trim($url)); // prevent double-encoding
				$encoded = urlencode($url);
			} else {
				$url = urldecode(trim($url)); // prevent double-encoding
				$encoded = urlencode($url);
			}

			// custom word setting
			$keyword_format = ( get_option( 'jd_keyword_format' ) == '1' )?$post_ID:false;
			$keyword_format = ( get_option( 'jd_keyword_format' ) == '2' )?get_post_meta( $post_ID,'_yourls_keyword',true ):$keyword_format;
			$error = '';
			// Generate and grab the short url
			switch ( get_option( 'jd_shortener' ) ) {
				case 0:
				case 1:
				case 3:
					$shrink = $url;
					break;
				case 4:
					if ( function_exists('wp_get_shortlink') ) {
						// wp_get_shortlink doesn't natively support custom post types; but don't return an error in that case.
						$shrink = ( $post_ID != false ) ? wp_get_shortlink( $post_ID, 'post' ) : $url;
					}
					if ( !$shrink ) { $shrink = $url; }
					break;
				case 2: // updated to v3 3/31/2010
					$bitlyapi = trim ( get_option( 'bitlyapi' ) );
					$bitlylogin = trim ( strtolower( get_option( 'bitlylogin' ) ) );				
					$decoded = jd_remote_json( "https://api-ssl.bitly.com/v3/shorten?longUrl=".$encoded."&login=".$bitlylogin."&apiKey=".$bitlyapi."&format=json" );
					if ($decoded) {
						if ($decoded['status_code'] != 200) {
							$shrink = $decoded;
							$error = $decoded['status_txt'];
						} else {
							$shrink = $decoded['data']['url'];		
						}
					} else {
						$shrink = false;
					}	
					if ( !is_valid_url($shrink) ) { $shrink = false; }
					break;
				case 5:
					// local YOURLS installation
					global $yourls_reserved_URL;
					define('YOURLS_INSTALLING', true); // Pretend we're installing YOURLS to bypass test for install or upgrade
					define('YOURLS_FLOOD_DELAY_SECONDS', 0); // Disable flood check
					$opath = get_option( 'yourlspath' );
					$ypath = str_replace( 'user','includes', $opath );
					if ( file_exists( dirname( $ypath ).'/load-yourls.php' ) ) { // YOURLS 1.4+
						require_once( dirname( $ypath ).'/load-yourls.php' );
						global $ydb;
						if ( function_exists( 'yourls_add_new_link' ) ) {
							$yourls_result = yourls_add_new_link( $url, $keyword_format, $thisposttitle );
						} else {
							$yourls_result = $url;
						}
					} else { // YOURLS 1.3
						if ( file_exists( get_option( 'yourslpath' ) ) ) {
							require_once( get_option( 'yourlspath' ) ); 
							$yourls_db = new wpdb( YOURLS_DB_USER, YOURLS_DB_PASS, YOURLS_DB_NAME, YOURLS_DB_HOST );
							$yourls_result = yourls_add_new_link( $url, $keyword_format, $yourls_db );
						}
					}
					if ($yourls_result) {
						$shrink = $yourls_result['shorturl'];			
					} else {
						$shrink = false;
					}
					break;
				case 6:
					// remote YOURLS installation
					$yourlslogin =  trim ( get_option( 'yourlslogin') );
					$yourlsapi = stripcslashes( get_option( 'yourlsapi' ) );					
					$api_url = sprintf( get_option('yourlsurl') . '?username=%s&password=%s&url=%s&format=json&action=shorturl&keyword=%s',
						$yourlslogin, $yourlsapi, $encoded, $keyword_format );
					$json = jd_remote_json( $api_url, false );
					if ($json) {
						$shrink = $json->shorturl;
					} else {
						$shrink = false;
					}
					break;
				case 7:
					$suprapi =  trim ( get_option( 'suprapi' ) );
					$suprlogin = trim ( get_option( 'suprlogin' ) );				
					if ( $suprapi != '') {
						$decoded = jd_remote_json( "http://su.pr/api/shorten?longUrl=".$encoded."&login=".$suprlogin."&apiKey=".$suprapi );
					} else {
						$decoded = jd_remote_json( "http://su.pr/api/shorten?longUrl=".$encoded );
					}
					if ($decoded['statusCode'] == 'OK') {
						$page = str_replace("&","&#38;", urldecode($url));
						$shrink = $decoded['results'][$page]['shortUrl'];
						$error = $decoded['errorMessage'];
					} else {
						$shrink = false;
						$error = $decoded['errorMessage'];
					}	
					if ( !is_valid_url($shrink) ) { $shrink = false; }
					break;
				case 8:
				// Goo.gl
					$target = "https://www.googleapis.com/urlshortener/v1/url?key=AIzaSyBSnqQOg3vX1gwR7y2l-40yEG9SZiaYPUQ";					
					$body = "{'longUrl':'$url'}";
					//$body = json_encode($data);
					$json = jd_fetch_url( $target, 'POST', $body, 'Content-Type: application/json' );
					$decoded = json_decode($json);
					//$url = $decoded['id'];
					$shrink = $decoded->id;
					if ( !is_valid_url($shrink) ) { $shrink = false; }
					break;
				case 9:
				// Twitter Friendly Links
					$shrink = $url;
					if ( function_exists( 'twitter_link' ) ) { // use twitter_link if available
						$shrink = twitter_link( $post_ID );
					}
					break;
				case 10: // jotURL			
					//jotURL, added: 2013-04-10
					$joturlapi = trim(get_option('joturlapi'));
					$joturllogin = trim(get_option('joturllogin'));				
					$joturl_longurl_params = trim( get_option('joturl_longurl_params') );
					if ($joturl_longurl_params != '') {
					   if (strpos($url, "%3F") === FALSE && strpos($url, "?") === FALSE) {
						  $ct = "?";
					   } else {
						  $ct = "&";
					   }
					   $url .= $ct . $joturl_longurl_params;
					   $encoded = urlencode(urldecode(trim($url))); // prevent double-encoding
					}
					//\jotURL
					$decoded = jd_fetch_url("https://api.joturl.com/a/v1/shorten?url=" . $encoded . "&login=" . $joturllogin . "&key=" . $joturlapi . "&format=plain");
					if ($decoded !== false) {
					   $shrink = $decoded;
					   //jotURL, added: 2013-04-10
					   $joturl_shorturl_params = trim( get_option('joturl_shorturl_params') );
					   if ($joturl_shorturl_params != '') {
						  if (strpos($shrink, "%3F") === FALSE && strpos($shrink, "?") === FALSE) {
							 $ct = "?";
						  } else {
							 $ct = "&";
						  }
						  $shrink .= $ct . $joturl_shorturl_params;
					   }
						//\jotURL
					} else {
					   $error = $decoded;
					   $shrink = false;
					}
					if (!is_valid_url($shrink)) {
					   $shrink = false;
					}
				break;
				update_option( 'wpt_shortener_status', "$shrink : $error" );
			}
			if ( !$testmode ) {
				if ( $shrink === false || ( filter_var($shrink, FILTER_VALIDATE_URL) === false ) ) {
				update_option( 'wp_url_failure','1' );
				$shrink = urldecode( $url );
				} else {
					update_option( 'wp_url_failure','0' );
				}
			}
			wpt_store_url( $post_ID, $shrink );
		return $shrink;
	}

	function wpt_store_url($post_ID, $url) {
		if ( function_exists('jd_shorten_link') ) {
			$shortener = get_option( 'jd_shortener' );
			switch ($shortener) {
				case 0:	case 1:	case 4: $ext = '_wp';break;
				case 2:	$ext = '_bitly';break;
				case 3:	$ext = '_url';break;
				case 5:	case 6:	$ext = '_yourls';break;
				case 7:	$ext = '_supr';	break;
				case 8:	$ext = '_goo';	break;
				case 9: $ext = '_tfl'; break;
				case 10:$ext = '_joturl'; break;				
				default:$ext = '_ind';
			}
			if ( get_post_meta ( $post_ID, "_wp_jd$ext", TRUE ) != $url ) {
				update_post_meta ( $post_ID, "_wp_jd$ext", $url );
			}
			switch ( $shortener ) {
				case 0: case 1: case 2: case 7: case 8: $target = jd_expand_url( $url );break;
				case 5: case 6: $target = jd_expand_yourl( $url, $shortener );break;
				case 9: $target = $url; 
				default: $target = $url;
			}
		} else {
			$target = $url;
		}
		update_post_meta( $post_ID, '_wp_jd_target', $target );
	}	
	
	function jd_expand_url( $short_url ) {
		$short_url = urlencode( $short_url );
		$decoded = jd_remote_json("http://api.longurl.org/v2/expand?format=json&url=" . $short_url );
		if ( isset( $decoded['long-url'] ) ) {
			$url = $decoded['long-url'];
		} else {
			$url = $short_url;
		}
		return $url;
		//return $short_url;
	}
	function jd_expand_yourl( $short_url, $remote ) {
		if ( $remote == 6 ) {
			$short_url = urlencode( $short_url );
			$yourl_api = get_option( 'yourlsurl' );
			$user = get_option( 'yourlslogin' );
			$pass = stripcslashes( get_option( 'yourlsapi' ) );
			$decoded = jd_remote_json( $yourl_api . "?action=expand&shorturl=$short_url&format=json&username=$user&password=$pass" );
			$url = $decoded['longurl'];
			return $url;
		} else {
			global $yourls_reserved_URL;
			define('YOURLS_INSTALLING', true); // Pretend we're installing YOURLS to bypass test for install or upgrade
			define('YOURLS_FLOOD_DELAY_SECONDS', 0); // Disable flood check
			if ( file_exists( dirname( get_option( 'yourlspath' ) ).'/load-yourls.php' ) ) { // YOURLS 1.4
				global $ydb;
				require_once( dirname( get_option( 'yourlspath' ) ).'/load-yourls.php' ); 
				$yourls_result = yourls_api_expand( $short_url );
			} else { // YOURLS 1.3
				if ( file_exists( get_option( 'yourlspath' ) ) ) {
					require_once( get_option( 'yourlspath' ) ); 
					$yourls_db = new wpdb( YOURLS_DB_USER, YOURLS_DB_PASS, YOURLS_DB_NAME, YOURLS_DB_HOST );
					$yourls_result = yourls_api_expand( $short_url );
				}
			}	
			$url = $yourls_result['longurl'];
			return $url;
		}
	}
	
	add_filter( 'wpt_shortener_controls', 'wpt_shortener_controls' );
	function wpt_shortener_controls() {
	// for the moment, this just displays the fields. Eventually, a real filter.
	?>
<div class="ui-sortable meta-box-sortables">
<div class="postbox">
	<div class="handlediv"><span class="screen-reader-text">Click to toggle</span></div>
	<h3 class='hndle'><span><?php _e('<abbr title="Uniform Resource Locator">URL</abbr> Shortener Account Settings','wp-to-twitter'); ?></span></h3>
	<div class="inside">
		<?php if ( get_option('jd_shortener') == 7 ) { ?>
		<div class="panel">
		<h4 class="supr"><span><?php _e("Your Su.pr account details", 'wp-to-twitter'); ?> <?php _e('(optional)','wp-to-twitter'); ?></span></h4>
			<form method="post" action="">
			<div><input type='hidden' name='wpt_shortener_update' value='true' /></div>
			<div>
				<p>
				<label for="suprlogin"><?php _e("Your Su.pr Username:", 'wp-to-twitter'); ?></label>
				<input type="text" name="suprlogin" id="suprlogin" size="40" value="<?php echo ( esc_attr( get_option( 'suprlogin' ) ) ) ?>" />
				</p>
				<p>
				<label for="suprapi"><?php _e("Your Su.pr <abbr title='application programming interface'>API</abbr> Key:", 'wp-to-twitter'); ?></label>
				<input type="text" name="suprapi" id="suprapi" size="40" value="<?php echo ( esc_attr( get_option( 'suprapi' ) ) ) ?>" />
				</p>
				<div>
				<input type="hidden" name="submit-type" value="suprapi" />
				</div>
				<?php $nonce = wp_nonce_field('wp-to-twitter-nonce', '_wpnonce', true, false).wp_referer_field(false);  echo "<div>$nonce</div>"; ?>	
				<p><input type="submit" name="submit" value="Save Su.pr API Key" class="button-primary" /> <input type="submit" name="clear" value="Clear Su.pr API Key" />&raquo; <small><?php _e("Don't have a Su.pr account or API key? <a href='http://su.pr/'>Get one here</a>!<br />You'll need an API key in order to associate the URLs you create with your Su.pr account.", 'wp-to-twitter'); ?></small></p>
			</div>
			</form>
		</div>
	<?php } else if ( get_option('jd_shortener') == 2 ) { ?>
	<div class="panel">
	<h4 class="bitly"><span><?php _e("Your Bit.ly account details", 'wp-to-twitter'); ?></span></h4>
		<form method="post" action="">
		<div><input type='hidden' name='wpt_shortener_update' value='true' /></div>
		<div>
			<p>
			<label for="bitlylogin"><?php _e("Your Bit.ly username:", 'wp-to-twitter'); ?></label>
			<input type="text" name="bitlylogin" id="bitlylogin" value="<?php echo ( esc_attr( get_option( 'bitlylogin' ) ) ) ?>" />
			</p>	
			<p>
			<label for="bitlyapi"><?php _e("Your Bit.ly <abbr title='application programming interface'>API</abbr> Key:", 'wp-to-twitter'); ?></label>
			<input type="text" name="bitlyapi" id="bitlyapi" size="40" value="<?php echo ( esc_attr( get_option( 'bitlyapi' ) ) ) ?>" />
			</p>
			<p><a href="http://bitly.com/a/your_api_key"><?php _e('View your Bit.ly username and API key','wp-to-twitter'); ?></a></p>
			<div>
			<input type="hidden" name="submit-type" value="bitlyapi" />
			</div>
		<?php $nonce = wp_nonce_field('wp-to-twitter-nonce', '_wpnonce', true, false).wp_referer_field(false);  echo "<div>$nonce</div>"; ?>	
			<p><input type="submit" name="submit" value="<?php _e('Save Bit.ly API Key','wp-to-twitter'); ?>" class="button-primary" /> <input type="submit" name="clear" value="<?php _e('Clear Bit.ly API Key','wp-to-twitter'); ?>" /><br /><small><?php _e("A Bit.ly API key and username is required to shorten URLs via the Bit.ly API and WP to Twitter.", 'wp-to-twitter' ); ?></small></p>
		</div>
		</form>	
	</div>
	<?php } else if ( get_option('jd_shortener') == 5 || get_option('jd_shortener') == 6 ) { ?>
	<div class="panel">
	<h4 class="yourls"><span><?php _e("Your YOURLS account details", 'wp-to-twitter'); ?></span></h4>
		<form method="post" action="">
		<div><input type='hidden' name='wpt_shortener_update' value='true' /></div>
		<div>
			<p>
			<label for="yourlspath"><?php _e('Path to your YOURLS config file (Local installations)','wp-to-twitter'); ?></label> <input type="text" id="yourlspath" name="yourlspath" size="60" value="<?php echo ( esc_attr( get_option( 'yourlspath' ) ) ); ?>"/>
			<small><?php _e('Example:','wp-to-twitter'); ?> <code>/home/username/www/www/yourls/includes/config.php</code></small>
			</p>				
			<p>
			<label for="yourlsurl"><?php _e('URI to the YOURLS API (Remote installations)','wp-to-twitter'); ?></label> <input type="text" id="yourlsurl" name="yourlsurl" size="60" value="<?php echo ( esc_attr( get_option( 'yourlsurl' ) ) ); ?>"/>
			<small><?php _e('Example:','wp-to-twitter'); ?> <code>http://domain.com/yourls-api.php</code></small>
			</p>
			<p>
			<label for="yourlslogin"><?php _e("Your YOURLS username:", 'wp-to-twitter'); ?></label>
			<input type="text" name="yourlslogin" id="yourlslogin" size="30" value="<?php echo ( esc_attr( get_option( 'yourlslogin' ) ) ) ?>" />
			</p>	
			<p>
			<label for="yourlsapi"><?php _e("Your YOURLS password:", 'wp-to-twitter'); ?> <?php if ( get_option( 'yourlsapi' ) != '') { _e("<em>Saved</em>",'wp-to-twitter'); } ?></label>
			<input type="password" name="yourlsapi" id="yourlsapi" size="30" value="" />
			</p>
			<p>
			<input type="radio" name="jd_keyword_format" id="jd_keyword_id" value="1" <?php echo jd_checkSelect( 'jd_keyword_format',1,'checkbox' ); ?> /> 		<label for="jd_keyword_id"><?php _e("Post ID for YOURLS url slug.",'wp-to-twitter'); ?></label><br />
			<input type="radio" name="jd_keyword_format" id="jd_keyword" value="2" <?php echo jd_checkSelect( 'jd_keyword_format',2,'checkbox' ); ?> /> 		<label for="jd_keyword"><?php _e("Custom keyword for YOURLS url slug.",'wp-to-twitter'); ?></label><br />
			<input type="radio" name="jd_keyword_format" id="jd_keyword_default" value="0" <?php echo jd_checkSelect( 'jd_keyword_format',0,'checkbox' ); ?> /> <label for="jd_keyword_default"><?php _e("Default: sequential URL numbering.",'wp-to-twitter'); ?></label>
			</p>
			<div>
			<input type="hidden" name="submit-type" value="yourlsapi" />
			</div>
		<?php $nonce = wp_nonce_field('wp-to-twitter-nonce', '_wpnonce', true, false).wp_referer_field(false);  echo "<div>$nonce</div>"; ?>	
			<p><input type="submit" name="submit" value="<?php _e('Save YOURLS Account Info','wp-to-twitter'); ?>" class="button-primary" /> <input type="submit" name="clear" value="<?php _e('Clear YOURLS password','wp-to-twitter'); ?>" /><br /><small><?php _e("A YOURLS password and username is required to shorten URLs via the remote YOURLS API and WP to Twitter.", 'wp-to-twitter' ); ?></small></p>
		</div>
		</form>		
		</div>		
    <?php } else if (get_option('jd_shortener') == 10) {   ?>
	<div class="panel">
		<h4 class="joturl"><span><?php _e("Your jotURL account details", 'wp-to-twitter'); ?></span></h4>
		<form method="post" action="">
		<div><input type='hidden' name='wpt_shortener_update' value='true' /></div>
		<div>
		<p><label for="joturllogin"><?php _e("Your jotURL public <abbr title='application programming interface'>API</abbr> key:", 'wp-to-twitter'); ?></label> <input type="text" name="joturllogin" id="joturllogin" value="<?php echo (esc_attr(get_option('joturllogin')))?>" /></p>
		<p><label for="joturlapi"><?php _e("Your jotURL private <abbr title='application programming interface'>API</abbr> key:", 'wp-to-twitter'); ?></label> <input type="text" name="joturlapi" id="joturlapi" size="40" value="<?php echo (esc_attr(get_option('joturlapi')))?>" /></p>
		<p><label for="joturl_longurl_params"><?php _e("Parameters to add to the long URL (before shortening):", 'wp-to-twitter'); ?></label> <input type="text" name="joturl_longurl_params" id="joturl_longurl_params" size="40" value="<?php echo (esc_attr(get_option('joturl_longurl_params')))?>" /></p>		<p><label for="joturl_shorturl_params"><?php _e("Parameters to add to the short URL (after shortening):", 'wp-to-twitter'); ?></label> <input type="text" name="joturl_shorturl_params" id="joturl_shorturl_params" size="40" value="<?php echo (esc_attr(get_option('joturl_shorturl_params')))?>" /></p>
		<p><a href="https://www.joturl.com/reserved/api.html"><?php _e('View your jotURL public and private API key', 'wp-to-twitter'); ?></a></p>
		<div><input type="hidden" name="submit-type" value="joturlapi" /></div>
		<?php $nonce = wp_nonce_field('wp-to-twitter-nonce', '_wpnonce', true, false) . wp_referer_field(false); echo "<div>$nonce</div>"; ?>	
		<p><input type="submit" name="submit" value="<?php _e('Save jotURL settings', 'wp-to-twitter');	 ?>" class="button-primary" /> <input type="submit" name="clear" value="<?php _e('Clear jotURL settings', 'wp-to-twitter'); ?>" /> <br />
		<small><?php _e("A jotURL public and private API key is required to shorten URLs via the jotURL API and WP to Twitter.", 'wp-to-twitter'); ?></small></p>
		</div>
		</form>
	</div>
	<?php } else { ?>
	<?php _e('Your shortener does not require any account settings.','wp-to-twitter'); ?>
	<?php } ?>
		</div>
</div>
</div>
	<?php
	}
	
	function wpt_shortener_update( $post ) {
		if ( isset($post['submit-type']) && $post['submit-type'] == 'yourlsapi' ) {
			if ( $post['yourlsapi'] != '' && isset( $post['submit'] ) ) {
				update_option( 'yourlsapi', trim($post['yourlsapi']) );
				$message = __("YOURLS password updated. ", 'wp-to-twitter');
			} else if ( isset( $post['clear'] ) ) {
				update_option( 'yourlsapi','' );
				$message = __( "YOURLS password deleted. You will be unable to use your remote YOURLS account to create short URLS.", 'wp-to-twitter');
			} else {
				$message = __( "Failed to save your YOURLS password! ", 'wp-to-twitter' );
			}
			if ( $post['yourlslogin'] != '' ) {
				update_option( 'yourlslogin', trim($post['yourlslogin']) );
				$message .= __( "YOURLS username added. ",'wp-to-twitter' ); 
			}
			if ( $post['yourlsurl'] != '' ) {
				update_option( 'yourlsurl', trim($post['yourlsurl']) );
				$message .= __( "YOURLS API url added. ",'wp-to-twitter' ); 
			} else {
				update_option('yourlsurl','');
				$message .= __( "YOURLS API url removed. ",'wp-to-twitter' ); 			
			}
			if ( $post['yourlspath'] != '' ) {
				update_option( 'yourlspath', trim($post['yourlspath']) );	
				if ( file_exists( $post['yourlspath'] ) ) {
				$message .= __( "YOURLS local server path added. ",'wp-to-twitter'); 
				} else {
				$message .= __( "The path to your YOURLS installation is not correct. ",'wp-to-twitter' );
				}
			} else {
				update_option( 'yourlspath','' );
				$message .= __( "YOURLS local server path removed. ",'wp-to-twitter');
			}
			if ( $post['jd_keyword_format'] != '' ) {
				update_option( 'jd_keyword_format', $post['jd_keyword_format'] );
				if ( $post['jd_keyword_format'] == 1 ) {
				$message .= __( "YOURLS will use Post ID for short URL slug.",'wp-to-twitter');
				} else {
				$message .= __( "YOURLS will use your custom keyword for short URL slug.",'wp-to-twitter');
				}
			} else {
				update_option( 'jd_keyword_format','' );
				$message .= __( "YOURLS will not use Post ID for the short URL slug.",'wp-to-twitter');
			}
		} 
		
		if ( isset($post['submit-type']) && $post['submit-type'] == 'suprapi' ) {
			if ( $post['suprapi'] != '' && isset( $post['submit'] ) ) {
				update_option( 'suprapi', trim($post['suprapi']) );
				update_option( 'suprlogin', trim($post['suprlogin']) );
				$message = __("Su.pr API Key and Username Updated", 'wp-to-twitter');
			} else if ( isset( $post['clear'] ) ) {
				update_option( 'suprapi','' );
				update_option( 'suprlogin','' );
				$message = __("Su.pr API Key and username deleted. Su.pr URLs created by WP to Twitter will no longer be associated with your account. ", 'wp-to-twitter');
			} else {
				$message = __("Su.pr API Key not added - <a href='http://su.pr/'>get one here</a>! ", 'wp-to-twitter');
			}
		} 
		if ( isset($post['submit-type']) && $post['submit-type'] == 'bitlyapi' ) {
			if ( $post['bitlyapi'] != '' && isset( $post['submit'] ) ) {
				update_option( 'bitlyapi', trim($post['bitlyapi']) );
				$message = __("Bit.ly API Key Updated.", 'wp-to-twitter');
			} else if ( isset( $post['clear'] ) ) {
				update_option( 'bitlyapi','' );
				$message = __("Bit.ly API Key deleted. You cannot use the Bit.ly API without an API key. ", 'wp-to-twitter');
			} else {
				$message = __("Bit.ly API Key not added - <a href='http://bit.ly/account/'>get one here</a>! An API key is required to use the Bit.ly URL shortening service.", 'wp-to-twitter');
			}
			if ( $post['bitlylogin'] != '' && isset( $post['submit'] ) ) {
				update_option( 'bitlylogin', trim($post['bitlylogin']) );
				$message .= __(" Bit.ly User Login Updated.", 'wp-to-twitter');
			} else if ( isset( $post['clear'] ) ) {
				update_option( 'bitlylogin','' );
				$message = __("Bit.ly User Login deleted. You cannot use the Bit.ly API without providing your username. ", 'wp-to-twitter');
			} else {
				$message = __("Bit.ly Login not added - <a href='http://bit.ly/account/'>get one here</a>! ", 'wp-to-twitter');
			}
		}
		if (isset($post['submit-type']) && $post['submit-type'] == 'joturlapi') {
			if ($post['joturlapi'] != '' && isset($post['submit'])) {
				update_option('joturlapi', trim($post['joturlapi']));
				$message = __("jotURL private API Key Updated. ", 'wp-to-twitter');
			} else if (isset($post['clear'])) {
				update_option('joturlapi', '');
				$message = __("jotURL private API Key deleted. You cannot use the jotURL API without a private API key. ", 'wp-to-twitter');
			} else {
				$message = __("jotURL private API Key not added - <a href='https://www.joturl.com/reserved/api.html'>get one here</a>! A private API key is required to use the jotURL URL shortening service. ", 'wp-to-twitter');
			}
			if ($post['joturllogin'] != '' && isset($post['submit'])) {
				update_option('joturllogin', trim($post['joturllogin']));
				$message .= __("jotURL public API Key Updated. ", 'wp-to-twitter');
			} else if (isset($post['clear'])) {
				update_option('joturllogin', '');
				$message = __("jotURL public API Key deleted. You cannot use the jotURL API without providing your public API Key. ", 'wp-to-twitter');
			} else {
				$message = __("jotURL public API Key not added - <a href='https://www.joturl.com/reserved/api.html'>get one here</a>! ", 'wp-to-twitter');
			}
			if ($post['joturl_longurl_params'] != '' && isset($post['submit'])) {
				$v = trim($post['joturl_longurl_params']);
				if (substr($v, 0, 1) == '&' || substr($v, 0, 1) == '?') { $v = substr($v, 1); }
				update_option('joturl_longurl_params', $v);
				$message .= __("Long URL parameters added. ", 'wp-to-twitter');
			} else if (isset($post['clear'])) {
				update_option('joturl_longurl_params', '');
				$message = __("Long URL parameters deleted. ", 'wp-to-twitter');
			}
			if ($post['joturl_shorturl_params'] != '' && isset($post['submit'])) { 
				$v = trim($post['joturl_shorturl_params']);
				if (substr($v, 0, 1) == '&' || substr($v, 0, 1) == '?') {$v = substr($v, 1);}
				update_option('joturl_shorturl_params', $v);
				$message .= __("Short URL parameters added. ", 'wp-to-twitter');
			} else if (isset($post['clear'])) {
				update_option('joturl_shorturl_params', '');
				$message = __("Short URL parameters deleted. ", 'wp-to-twitter');
			}			
		}	
		return $message;
	}
	
	function wpt_select_shortener( $post ) {
		update_option( 'jd_shortener', $post['jd_shortener'] );
		if ( $post['jd_shortener'] == get_option('jd_shortener') ) return; // no message if no change.
		if ( get_option( 'jd_shortener' ) == 2 && ( get_option( 'bitlylogin' ) == "" || get_option( 'bitlyapi' ) == "" ) ) {
			$message .= __( 'You must add your Bit.ly login and API key in order to shorten URLs with Bit.ly.' , 'wp-to-twitter');
			$message .= "<br />";
		}
		if (get_option('jd_shortener') == 10 && (get_option('joturllogin') == "" || get_option('joturlapi') == "")) {
			$message .= __('You must add your jotURL public and private API key in order to shorten URLs with jotURL.', 'wp-to-twitter');
			$message .= "<br />";
		}		
		if ( get_option( 'jd_shortener' ) == 6 && ( get_option( 'yourlslogin' ) == "" || get_option( 'yourlsapi' ) == "" || get_option( 'yourlsurl' ) == "" ) ) {
			$message .= __( 'You must add your YOURLS remote URL, login, and password in order to shorten URLs with a remote installation of YOURLS.' , 'wp-to-twitter');
			$message .= "<br />";
		}
		if ( get_option( 'jd_shortener' ) == 5 && ( get_option( 'yourlspath' ) == "" ) ) {
			$message .= __( 'You must add your YOURLS server path in order to shorten URLs with a remote installation of YOURLS.' , 'wp-to-twitter');
			$message .= "<br />";
		}
		return $message;
	}
	
	add_filter( 'wpt_pick_shortener','wpt_pick_shortener');
	function wpt_pick_shortener() {
		?>
			<p>	
			<label><?php _e("Choose a short URL service (account settings below)",'wp-to-twitter' ); ?></label>
			<select name="jd_shortener" id="jd_shortener">
				<option value="3" <?php echo jd_checkSelect('jd_shortener','3'); ?>><?php _e("Don't shorten URLs.", 'wp-to-twitter'); ?></option>
				<option value="7" <?php echo jd_checkSelect('jd_shortener','7'); ?>>Su.pr</option> 
				<option value="2" <?php echo jd_checkSelect('jd_shortener','2'); ?>>Bit.ly</option>
				<option value="8" <?php echo jd_checkSelect('jd_shortener','8'); ?>>Goo.gl</option> 				
				<option value="5" <?php echo jd_checkSelect('jd_shortener','5'); ?>><?php _e("YOURLS (on this server)", 'wp-to-twitter'); ?></option>
				<option value="6" <?php echo jd_checkSelect('jd_shortener','6'); ?>><?php _e("YOURLS (on a remote server)", 'wp-to-twitter'); ?></option>		
				<option value="4" <?php echo jd_checkSelect('jd_shortener','4'); ?>>WordPress</option>
				<option value="10" <?php echo jd_checkSelect('jd_shortener', '10'); ?>>jotURL</option>
				<?php if ( function_exists( 'twitter_link' ) ) { ?><option value="9" <?php echo jd_checkSelect('jd_shortener','9'); ?>><?php _e("Use Twitter Friendly Links.", 'wp-to-twitter'); ?></option><?php } ?>
			</select>
			</p>
		<?php
	}
}
<?php 
// This file contains secondary functions supporting WP to Twitter

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function wpt_mail( $subject, $body ) {
	$use_email = true;
	if ( $use_email ) {
		wp_mail( WPT_DEBUG_ADDRESS, $subject, $body, WPT_FROM );
	} else {
		$debug = get_option( 'wpt_debug' );
		$debug[date( 'Y-m-d H:i:s' )] = array( $subject, $body );
		update_option( 'wpt_debug', $debug );
	}
}

function jd_remote_json( $url, $array=true ) {
	$input = jd_fetch_url( $url );
	$obj = json_decode( $input, $array );
	if ( function_exists( 'json_last_error' ) ) { // > PHP 5.3
		try {
			if ( is_null( $obj ) ) {
				switch ( json_last_error() ) {
					case JSON_ERROR_DEPTH :
						$msg = ' - Maximum stack depth exceeded';
						break;
					case JSON_ERROR_STATE_MISMATCH :
						$msg = ' - Underflow or the modes mismatch';
						break;
					case JSON_ERROR_CTRL_CHAR :
						$msg = ' - Unexpected control character found';
						break;
					case JSON_ERROR_SYNTAX :
						$msg = ' - Syntax error, malformed JSON';
						break;
					case JSON_ERROR_UTF8 :
						$msg = ' - Malformed UTF-8 characters, possibly incorrectly encoded';
						break;
					default :
						$msg = ' - Unknown error';
						break;
				}
				throw new Exception($msg);
			}
		} catch ( Exception $e ) {
			return $e -> getMessage();
		}
	}
	return $obj;
}			

function is_valid_url( $url ) {
    if ( is_string( $url ) ) {
		$url = urldecode( $url );
		return preg_match( '|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url );
	} else {
		return false;
	}
}
// Fetch a remote page. Input url, return content
function jd_fetch_url( $url, $method='GET', $body='', $headers='', $return='body' ) {
	$request = new WP_Http;
	$result = $request->request( $url , array( 'method'=>$method, 'body'=>$body, 'headers'=>$headers, 'sslverify'=>false, 'user-agent'=>'WP to Twitter/http://www.joedolson.com/articles/wp-to-twitter/' ) );
	// Success?
	if ( !is_wp_error( $result ) && isset( $result['body'] ) ) {
		if ( $result['response']['code'] == 200 ) {
			if ( $return == 'body' ) {
				return $result['body'];
			} else {
				return $result;
			}
		} else {
			return $result['response']['code'];
		}
	// Failure (server problem...)
	} else {
		return false;
	}
}

if (!function_exists('mb_strlen')) {
	/**
	 * Fallback implementation of mb_strlen, hardcoded to UTF-8.
	 * @param string $str
	 * @param string $enc optional encoding; ignored
	 * @return int
	 */
	function mb_strlen( $str, $enc = '' ) {
		$counts = count_chars( $str );
		$total = 0;

		// Count ASCII bytes
		for( $i = 0; $i < 0x80; $i++ ) {
			$total += $counts[$i];
		}

		// Count multibyte sequence heads
		for( $i = 0xc0; $i < 0xff; $i++ ) {
			$total += $counts[$i];
		}
		return $total;
	}
}

if (!function_exists('mb_substr')) {
	function mb_substr( $str, $start, $count = 'end' ) {
		if ( $start != 0 ) {
			$split = self::mb_substr_split_unicode( $str, intval( $start ) );
			$str = substr( $str, $split );
		}

		if ( $count !== 'end' ) {
			$split = self::mb_substr_split_unicode( $str, intval( $count ) );
			$str = substr( $str, 0, $split );
		}
		return $str;
    }
}

// filter_var substitution for PHP <5.2
if ( !function_exists( 'filter_var' ) ) {
	function filter_var( $url ) {
		// this does not emulate filter_var; merely the usage of filter_var in WP to Twitter.
		return ( stripos( $url, 'https:' ) !== false || stripos( $url, 'http:' ) !== false )?true:false;
	}
}

if ( !function_exists( 'mb_strrpos' ) ) {
	/**
	 * Fallback implementation of mb_strrpos, hardcoded to UTF-8.
	 * @param $haystack String
	 * @param $needle String
	 * @param $offset String: optional start position
	 * @param $encoding String: optional encoding; ignored
	 * @return int
	 */
	function mb_strrpos( $haystack, $needle, $offset = 0, $encoding = '' ) {
		$needle = preg_quote( $needle, '/' );

		$ar = array();
		preg_match_all( '/' . $needle . '/u', $haystack, $ar, PREG_OFFSET_CAPTURE, $offset );

		if( isset( $ar[0] ) && count( $ar[0] ) > 0 &&
			isset( $ar[0][count( $ar[0] ) - 1][1] ) ) {
			return $ar[0][count( $ar[0] ) - 1][1];
		} else {
			return false;
		}
	}
}

// str_ireplace substitution for PHP4
if ( !function_exists( 'str_ireplace' ) ) {
	function str_ireplace( $needle, $str, $haystack ) {
		$needle = preg_quote( $needle, '/' );
		return preg_replace( "/$needle/i", $str, $haystack );
	}
}
// str_split substitution for PHP4
if( !function_exists( 'str_split' ) ) {
    function str_split( $string,$string_length=1 ) {
        if( strlen( $string )>$string_length || !$string_length ) {
            do {
                $c = strlen($string);
                $parts[] = substr($string,0,$string_length);
                $string = substr($string,$string_length);
            } while($string !== false);
        } else {
            $parts = array($string);
        }
        return $parts;
    }
}
// mb_substr_replace substition for PHP4
if ( !function_exists( 'mb_substr_replace' ) ) {
    function mb_substr_replace( $string, $replacement, $start, $length = null, $encoding = null ) {
        if ( extension_loaded( 'mbstring' ) === true ) {
            $string_length = (is_null($encoding) === true) ? mb_strlen($string) : mb_strlen($string, $encoding);   
            if ( $start < 0 ) {
                $start = max(0, $string_length + $start);
            } else if ( $start > $string_length ) {
                $start = $string_length;
            }
            if ( $length < 0 ) {
                $length = max( 0, $string_length - $start + $length );
            } else if ( ( is_null( $length ) === true ) || ( $length > $string_length ) ) {
                $length = $string_length;
            }
            if ( ( $start + $length ) > $string_length) {
                $length = $string_length - $start;
            }
            if ( is_null( $encoding ) === true) {
                return mb_substr( $string, 0, $start ) . $replacement . mb_substr( $string, $start + $length, $string_length - $start - $length );
            }
		return mb_substr( $string, 0, $start, $encoding ) . $replacement . mb_substr( $string, $start + $length, $string_length - $start - $length, $encoding );
        }
	return ( is_null( $length ) === true ) ? substr_replace( $string, $replacement, $start ) : substr_replace( $string, $replacement, $start, $length );
    }
}

function wtt_option_selected($field,$value,$type='checkbox') {
	switch ($type) {
		case 'radio':		
		case 'checkbox':
		$result = ' checked="checked"';
		break;
		case 'option':
		$result = ' selected="selected"';
		break;
	}	
	if ($field == $value) {
		$output = $result;
	} else {
		$output = '';
	}
	return $output;
}

function wpt_date_compare( $early,$late ) {
	$modifier = apply_filters( 'wpt_edit_sensitivity', 0 ); // alter time in seconds to modified date.
	$firstdate = strtotime($early)+$modifier;
	$lastdate = strtotime($late);
	if ($firstdate <= $lastdate ) { // if post_modified is before or equal to post_date
		return 1;
	} else {
		return 0;
	}	
}

/** 
* Gets the first attachment for the supplied post.
* 
* @param type $post_ID The post ID
* @return An Attachment ID.
*/
function wpt_post_attachment($post_ID) {
	if ( has_post_thumbnail( $post_ID ) ) {
		$attachment = get_post_thumbnail_id( $post_ID );
		return $attachment;
	} else {
		$args = array( 
			'post_type' => 'attachment', 
			'numberposts' => 1, 
			'post_status' => 'published', 
			'post_parent' => $post_ID, 
			'post_mime_type'=>'image' 
		);
		$attachments = get_posts($args);
		if ($attachments) {
			return $attachments[0]->ID; //Return the first attachment.
		} else {
			return false;
		}
	}
	return false; 
}

function wpt_get_support_form() {
global $current_user, $wpt_version;
get_currentuserinfo();
	$request = '';
	// send fields for WP to Twitter
	$license = ( get_option('wpt_license_key') != '' )?get_option('wpt_license_key'):'none'; 
	$license = "License Key: ".$license; 
	
	$version = $wpt_version;
	$wtt_twitter_username = get_option('wtt_twitter_username');
	// send fields for all plugins
	$wp_version = get_bloginfo('version');
	$home_url = home_url();
	$wp_url = site_url();
	$language = get_bloginfo('language');
	$charset = get_bloginfo('charset');
	// server
	$php_version = phpversion();

	// theme data
	if ( function_exists( 'wp_get_theme' ) ) {
	$theme = wp_get_theme();
		$theme_name = $theme->Name;
		$theme_uri = $theme->ThemeURI;
		$theme_parent = $theme->Template;
		$theme_version = $theme->Version;	
	} else {
	$theme_path = get_stylesheet_directory().'/style.css';
	$theme = get_theme_data($theme_path);
		$theme_name = $theme['Name'];
		$theme_uri = $theme['ThemeURI'];
		$theme_parent = $theme['Template'];
		$theme_version = $theme['Version'];
	}
	// plugin data
	$plugins = get_plugins();
	$plugins_string = '';
		foreach( array_keys($plugins) as $key ) {
			if ( is_plugin_active( $key ) ) {
				$plugin =& $plugins[$key];
				$plugin_name = $plugin['Name'];
				$plugin_uri = $plugin['PluginURI'];
				$plugin_version = $plugin['Version'];
				$plugins_string .= "$plugin_name: $plugin_version; $plugin_uri\n";
			}
		}
	global $wpt_server_string;
	$wpt_server_string = trim(strip_tags( $wpt_server_string ) );
	$data = "
================ Installation Data ====================
==WP to Twitter==
Version: $version
Twitter username: http://twitter.com/$wtt_twitter_username
$license
$wpt_server_string

==WordPress:==
Version: $wp_version
URL: $home_url
Install: $wp_url
Language: $language
Charset: $charset
Admin Email: $current_user->user_email

==Extra info:==
PHP Version: $php_version
Server Software: $_SERVER[SERVER_SOFTWARE]
User Agent: $_SERVER[HTTP_USER_AGENT]

==Theme:==
Name: $theme_name
URI: $theme_uri
Parent: $theme_parent
Version: $theme_version

==Active Plugins:==
$plugins_string
";
	if ( isset($_POST['wpt_support']) ) {
		$nonce=$_REQUEST['_wpnonce'];
		if (! wp_verify_nonce($nonce,'wp-to-twitter-nonce') ) die("Security check failed");	
		$request = ( !empty($_POST['support_request']) )?stripslashes($_POST['support_request']):false;
		$has_donated = ( $_POST['has_donated'] == 'on')?"Donor":"No donation";
		$has_read_faq = ( $_POST['has_read_faq'] == 'on')?"Read FAQ":false;
		if ( function_exists( 'wpt_pro_exists' ) && wpt_pro_exists() == true ) { $pro = " PRO"; } else { $pro = ''; }
		$subject = "WP to Twitter$pro support request. $has_donated";
		$message = $request ."\n\n". $data;
		$from = "From: \"$current_user->display_name\" <$current_user->user_email>\r\n";

		if ( !$has_read_faq ) {
			echo "<div class='message error'><p>".__('Please read the FAQ and other Help documents before making a support request.','wp-to-twitter')."</p></div>";
		} else if ( !$request ) {
			echo "<div class='message error'><p>".__('Please describe your problem. I\'m not psychic.','wp-to-twitter')."</p></div>";
		} else {
			$sent = wp_mail( "plugins@joedolson.com",$subject,$message,$from );
			if ( $sent ) {
				if ( $has_donated == 'Donor' || $has_purchased == 'Purchaser' ) {
					echo "<div class='message updated'><p>".sprintf(__('Thank you for supporting the continuing development of this plug-in! I\'ll get back to you as soon as I can. Please ensure that you can receive email at <code>%s</code>.','wp-to-twitter'),$current_user->user_email)."</p></div>";		
				} else {
					echo "<div class='message updated'><p>".sprintf(__("Thanks for using WP to Twitter. Please ensure that you can receive email at <code>%s</code>.",'wp-to-twitter'),$current_user->user_email)."</p></div>";				
				}
			} else {
				echo "<div class='message error'><p>".__( "Sorry! I couldn't send that message. Here's the text of your message:", 'wp-to-twitter' )."</p><pre>$request</pre></div>";
			}
		}
	}
	if ( function_exists( 'wpt_pro_exists' ) && wpt_pro_exists() == true ) { $checked="checked='checked'"; } else { $checked=''; }
		$admin_url = ( is_plugin_active('wp-tweets-pro/wpt-pro-functions.php') )?admin_url('admin.php?page=wp-tweets-pro'):admin_url('options-general.php?page=wp-to-twitter/wp-to-twitter.php');
	echo "
	<form method='post' action='$admin_url'>
		<div><input type='hidden' name='_wpnonce' value='".wp_create_nonce('wp-to-twitter-nonce')."' /></div>
		<div>";
		if ( function_exists( 'wpt_pro_exists' ) && wpt_pro_exists() == true  ) {
		} else { 
		echo "
		<p>".
		__('<strong>Please note</strong>: I do keep records of those who have donated, but if your donation came from somebody other than your account at this web site, you must note this in your message.','wp-to-twitter')
		."</p>";
		echo "
		<p>".
		__("If you're having trouble with WP to Twitter, please try to answer these questions in your message:",'wp-to-twitter')
		."</p>";
		echo "<ul>";
			echo "<li>".__('Did this error happen only once, or repeatedly?','wp-to-twitter')."</li>";
			echo "<li>".__('What was the Tweet, or an example Tweet, that produced this error?','wp-to-twitter')."</li>";
			echo "<li>".__('If there was an error message from WP to Twitter, what was it?','wp-to-twitter')."</li>";
			echo "<li>".__('What is the template you\'re using for your Tweets?','wp-to-twitter')."</li>";
		echo "</ul>";
		}
		echo "
		<p>
		<code>".__('Reply to:','wp-to-twitter')." \"$current_user->display_name\" &lt;$current_user->user_email&gt;</code>
		</p>
		<p>
		<input type='checkbox' name='has_read_faq' id='has_read_faq' value='on' required='required' aria-required='true' /> <label for='has_read_faq'>".sprintf(__('I have read <a href="%1$s">the FAQ for this plug-in</a> <span>(required)</span>','wp-to-twitter'),'http://www.joedolson.com/articles/wp-to-twitter/support-2/')."
        </p>
        <p>
        <input type='checkbox' name='has_donated' id='has_donated' value='on' $checked /> <label for='has_donated'>".sprintf(__('I have <a href="%1$s">made a donation to help support this plug-in</a>','wp-to-twitter'),'http://www.joedolson.com/donate.php')."</label>
        </p>
        <p>
        <label for='support_request'>".__('Support Request:','wp-to-twitter')."</label><br /><textarea class='support-request' name='support_request' id='support_request' cols='80' rows='10'>".stripslashes($request)."</textarea>
		</p>
		<p>
		<input type='submit' value='".__('Send Support Request','wp-to-twitter')."' name='wpt_support' class='button-primary' />
		</p>
		<p>".
		__('The following additional information will be sent with your support request:','wp-to-twitter')
		."</p>
		<div class='mc_support'>
		".wpautop($data)."
		</div>
		</div>
	</form>";
}

function wpt_is_writable( $file ) {
	$is_writable = false;
	if ( function_exists( 'wp_is_writable' ) ) {
		$is_writable = wp_is_writable( $file );
	} else {
		$is_writable = is_writeable( $file );
	}
	return $is_writable;
}
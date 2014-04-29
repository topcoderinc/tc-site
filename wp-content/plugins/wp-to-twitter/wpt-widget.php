<?php
/**
* wpt Latest Tweets widget class.
*/

function wpt_get_user( $twitter_ID=false ) {
	if ( !$twitter_ID ) return;
    $options = array('screen_name' => $twitter_ID );
	$key = get_option('app_consumer_key');
	$secret = get_option('app_consumer_secret');
	$token = get_option('oauth_token');
	$token_secret = get_option('oauth_token_secret');	
    $connection = new jd_TwitterOAuth($key, $secret, $token, $token_secret);
    $result = $connection->get( "https://api.twitter.com/1.1/users/show.json?screen_name=$twitter_ID", $options);
	return json_decode($result);
}

add_shortcode( 'get_tweets', 'wpt_get_twitter_feed' );
function wpt_get_twitter_feed( $atts, $content ) {
	extract( shortcode_atts( array( 
			'id' => false,
			'num' => 10,
			'duration' => 3600,
			'replies' => 0,
			'rts' => 1,
			'links' => 1,
			'mentions' => 1,
			'hashtags' => 0,
			'intents' => 1,
			'source' => 0
		), $atts, 'get_tweets' ) );
		$instance = array( 
			'twitter_id' => $id,
			'twitter_num' => $num,
			'twitter_duration' => $duration,
			'twitter_hide_replice' => $replies,
			'twitter_include_rts' => $rts,
			'link_links' => $links,
			'link_mentions' => $mentions,
			'link_hashtags' => $hashtags,
			'intents' => $intents,
			'source' => $source );
	return wpt_twitter_feed( $instance );
}

function wpt_twitter_feed( $instance ) {
	$return = '<div class="wpt-header">';
		$user = wpt_get_user( $instance['twitter_id'] );		
		if ( isset($user->errors) && $user->errors[0]->message ) {
			return __("Error: ",'wp-to-twitter'). $user->errors[0]->message;
		}
		$avatar = $user->profile_image_url_https;
		$name = $user->name;
		$verified = $user->verified;
		$img_alignment = ( is_rtl() )?'wpt-right':'wpt-left';
		$follow_alignment = ( is_rtl() )?'wpt-left':'wpt-right';
		$follow_url = esc_url( 'https://twitter.com/'.$instance['twitter_id'] );
		$follow_button = apply_filters ( 'wpt_follow_button', "<a href='$follow_url' class='twitter-follow-button $follow_alignment' data-width='30px' data-show-screen-name='false' data-size='large' data-show-count='false' data-lang='en'>Follow @$instance[twitter_id]</a>" );
		$return .= "<p>
			$follow_button
			<img src='$avatar' alt='' class='wpt-twitter-avatar $img_alignment' />
			<span class='wpt-twitter-name'>$name</span><br />
			<span class='wpt-twitter-id'><a href='$follow_url'>@$instance[twitter_id]</a></span>
			</p>";
	$return .= '</div>';
	$return .= '<ul>' . "\n";

	$options['exclude_replies'] = ( isset( $instance['twitter_hide_replies'] ) ) ? $instance['twitter_hide_replies'] : false;
	$options['include_rts'] = $instance['twitter_include_rts'];
	$opts['links'] = $instance['link_links'];
	$opts['mentions'] = $instance['link_mentions'];
	$opts['hashtags'] = $instance['link_hashtags'];
	$rawtweets = WPT_getTweets($instance['twitter_num'], $instance['twitter_id'], $options);

	if ( isset( $rawtweets['error'] ) ) {
		$return .= "<li>".$rawtweets['error']."</li>";
	} else {
		/** Build the tweets array */
		$tweets = array();
		foreach ( $rawtweets as $tweet ) {

		if ( is_object( $tweet ) ) {
			$tweet = json_decode( json_encode( $tweet ), true );
		}
		if ( $instance['source'] ) {
			$source = $tweet['source'];
			$timetweet = sprintf( __( '<a href="%3$s">about %1$s ago</a> via %2$s', 'wp-to-twitter' ), human_time_diff( strtotime( $tweet['created_at'] ) ), $source, "http://twitter.com/$instance[twitter_id]/status/$tweet[id_str]" );
		} else {
			$timetweet = sprintf( __( '<a href="%2$s">about %1$s ago</a>', 'wp-to-twitter' ), human_time_diff( strtotime( $tweet['created_at'] ) ), "http://twitter.com/$instance[twitter_id]/status/$tweet[id_str]" );
		}
		$tweet_classes = wpt_generate_classes( $tweet );
		
		$intents = ( $instance['intents'] )?"<div class='wpt-intents-border'></div><div class='wpt-intents'><a class='wpt-reply' href='https://twitter.com/intent/tweet?in_reply_to=$tweet[id_str]'><span></span>Reply</a> <a class='wpt-retweet' href='https://twitter.com/intent/retweet?tweet_id=$tweet[id_str]'><span></span>Retweet</a> <a class='wpt-favorite' href='https://twitter.com/intent/favorite?tweet_id=$tweet[id_str]'><span></span>Favorite</a></div>":'';
		/** Add tweet to array */
		$tweets[] = '<li class="'.$tweet_classes.'">' . WPT_tweet_linkify( $tweet['text'], $opts ) . "<br /><span class='wpt-tweet-time'>$timetweet</span> $intents</li>\n";
		}
	}
	if ( is_array( $tweets ) ) {
		foreach( $tweets as $tweet ) {
			$return .= $tweet;
		}
	}
	$return .= '</ul>' . "\n";
	return $return;
}


class WPT_Latest_Tweets_Widget extends WP_Widget {

/**
* Holds widget settings defaults, populated in constructor.
*
* @var array
*/
protected $defaults;

/**
* Constructor. Set the default widget options and create widget.
*
* @since 0.1.8
*/
function __construct() {

	$this->defaults = array(
		'title' => '',
		'twitter_id' => '',
		'twitter_num' => '',
		'twitter_duration' => '',
		'twitter_hide_replies' => 0,
		'twitter_include_rts' => 0,
		'link_links'=>'',
		'link_mentions'=>'',
		'link_hashtags'=>'',
		'intents'=>'',
		'source'=>''
	);

	$widget_ops = array(
		'classname' => 'wpt-latest-tweets',
		'description' => __( 'Display a list of your latest tweets.', 'wp-to-twitter' ),
	);

	$control_ops = array(
		'id_base' => 'wpt-latest-tweets',
		'width' => 200,
		'height' => 250,
	);
	$this->WP_Widget( 'wpt-latest-tweets', __( 'WP to Twitter - Latest Tweets', 'wp-to-twitter' ), $widget_ops, $control_ops );
}

/**
* Echo the widget content.
*
* @param array $args Display arguments including before_title, after_title, before_widget, and after_widget.
* @param array $instance The settings for the particular instance of the widget
*/

function widget( $args, $instance ) {
	extract( $args );
	wp_enqueue_script( 'twitter-platform', "https://platform.twitter.com/widgets.js" );
	/** Merge with defaults */
	$instance = wp_parse_args( (array) $instance, $this->defaults );
	echo $before_widget;
	if ( $instance['title'] ) {
		echo $before_title . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . $after_title;
	}
	echo wpt_twitter_feed( $instance );
	echo $after_widget;
}

/**
* Update a particular instance.
*
* This function should check that $new_instance is set correctly.
* The newly calculated value of $instance should be returned.
* If "false" is returned, the instance won't be saved/updated.
*
* @since 0.1
*
* @param array $new_instance New settings for this instance as input by the user via form()
* @param array $old_instance Old settings for this instance
* @return array Settings to save or bool false to cancel saving
*/
function update( $new_instance, $old_instance ) {
	/** Force the transient to refresh */
	delete_transient( 'wpt_tdf_cache_expire' );
	$new_instance['title'] = strip_tags( $new_instance['title'] );
	return $new_instance;
}

/**
* Echo the settings update form.
*
* @param array $instance Current settings
*/
function form( $instance ) {

/** Merge with defaults */
$instance = wp_parse_args( (array) $instance, $this->defaults );

?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'wp-to-twitter' ); ?>:</label>
<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat" />
</p>

<p>
<label for="<?php echo $this->get_field_id( 'twitter_id' ); ?>"><?php _e( 'Twitter Username', 'wp-to-twitter' ); ?>:</label>
<input type="text" id="<?php echo $this->get_field_id( 'twitter_id' ); ?>" name="<?php echo $this->get_field_name( 'twitter_id' ); ?>" value="<?php echo esc_attr( $instance['twitter_id'] ); ?>" class="widefat" />
</p>

<p>
<label for="<?php echo $this->get_field_id( 'twitter_num' ); ?>"><?php _e( 'Number of Tweets to Show', 'wp-to-twitter' ); ?>:</label>
<input type="text" id="<?php echo $this->get_field_id( 'twitter_num' ); ?>" name="<?php echo $this->get_field_name( 'twitter_num' ); ?>" value="<?php echo esc_attr( $instance['twitter_num'] ); ?>" size="3" />
</p>

<p>
<input id="<?php echo $this->get_field_id( 'twitter_hide_replies' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'twitter_hide_replies' ); ?>" value="1" <?php checked( $instance['twitter_hide_replies'] ); ?>/>
<label for="<?php echo $this->get_field_id( 'twitter_hide_replies' ); ?>"><?php _e( 'Hide @ Replies', 'wp-to-twitter' ); ?></label>
</p>

<p>
<input id="<?php echo $this->get_field_id( 'twitter_include_rts' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'twitter_include_rts' ); ?>" value="1" <?php checked( $instance['twitter_include_rts'] ); ?>/>
<label for="<?php echo $this->get_field_id( 'twitter_include_rts' ); ?>"><?php _e( 'Include Retweets', 'wp-to-twitter' ); ?></label>
</p>

<p>
<input id="<?php echo $this->get_field_id( 'link_links' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'link_links' ); ?>" value="1" <?php checked( $instance['link_links'] ); ?>/>
<label for="<?php echo $this->get_field_id( 'link_links' ); ?>"><?php _e( 'Parse links', 'wp-to-twitter' ); ?></label>
</p>

<p>
<input id="<?php echo $this->get_field_id( 'link_mentions' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'link_mentions' ); ?>" value="1" <?php checked( $instance['link_mentions'] ); ?>/>
<label for="<?php echo $this->get_field_id( 'link_mentions' ); ?>"><?php _e( 'Parse @mentions', 'wp-to-twitter' ); ?></label>
</p>

<p>
<input id="<?php echo $this->get_field_id( 'link_hashtags' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'link_hashtags' ); ?>" value="1" <?php checked( $instance['link_hashtags'] ); ?>/>
<label for="<?php echo $this->get_field_id( 'link_hashtags' ); ?>"><?php _e( 'Parse #hashtags', 'wp-to-twitter' ); ?></label>
</p>

<p>
<input id="<?php echo $this->get_field_id( 'intents' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'intents' ); ?>" value="1" <?php checked( $instance['intents'] ); ?>/>
<label for="<?php echo $this->get_field_id( 'intents' ); ?>"><?php _e( 'Include Reply/Retweet/Favorite Links', 'wp-to-twitter' ); ?></label>
</p>

<p>
<input id="<?php echo $this->get_field_id( 'source' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'source' ); ?>" value="1" <?php checked( $instance['source'] ); ?>/>
<label for="<?php echo $this->get_field_id( 'source' ); ?>"><?php _e( 'Include Tweet source', 'wp-to-twitter' ); ?></label>
</p>

<?php

}

}

add_action( 'widgets_init', create_function( '', "register_widget('WPT_Latest_Tweets_Widget');" ) );

/**
* Adds links to the contents of a tweet.
* Forked form genesis_tweet_linkify, removed the taraget = _blank
*
* Takes the content of a tweet, detects @replies, #hashtags, and
* http:// links, and links them appropriately.
*
* @since 0.1
*
* @link http://www.snipe.net/2009/09/php-twitter-clickable-links/
*
* @param string $text A string representing the content of a tweet
*
* @return string Linkified tweet content
*/
function WPT_tweet_linkify( $text, $opts ) {
	$text = ( $opts['links'] == true )?preg_replace( "#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", '\\1<a href="\\2" rel="nofollow">\\2</a>', $text ):$text;
	$text = ( $opts['links'] == true )?preg_replace( "#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", '\\1<a href="http://\\2" rel="nofollow">\\2</a>', $text ):$text;
	$text = ( $opts['mentions'] == true )?preg_replace( '/@(\w+)/', '<a href="http://www.twitter.com/\\1" rel="nofollow">@\\1</a>', $text ):$text;
	$text = ( $opts['hashtags'] == true )?preg_replace( '/#(\w+)/', '<a href="http://search.twitter.com/search?q=\\1" rel="nofollow">#\\1</a>', $text ):$text;
	return $text;
}

/* implement getTweets */
function WPT_getTweets($count = 20, $username = false, $options = false) {

  $config['key'] = get_option('app_consumer_key');
  $config['secret'] = get_option('app_consumer_secret');
  $config['token'] = get_option('oauth_token');
  $config['token_secret'] = get_option('oauth_token_secret');
  $config['screenname'] = get_option('wtt_twitter_username');
  $config['cache_expire'] = intval( apply_filters( 'wpt_cache_expire', 3600 ) );
  if ($config['cache_expire'] < 1) $config['cache_expire'] = 3600;
  $config['directory'] = plugin_dir_path(__FILE__);
  
  $obj = new WPT_TwitterFeed($config);
  $res = $obj->getTweets($count, $username, $options);
  update_option('wpt_tdf_last_error',$obj->st_last_error);
  return $res;
  
}

function wpt_generate_classes( $tweet ) {
	// take Tweet array and parse selected options into classes.
	$classes[] = ( $tweet['favorited'] )?'favorited':'';
	$clasees[] = ( $tweet['retweeted'] )?'retweeted':'';
	$classes[] = ( isset( $tweet['possibly_sensitive'] ) )?'sensitive':'';
	$classes[] = 'lang-'.$tweet['lang'];
	$class = trim( implode( ' ', $classes ) );
	return $class;
}
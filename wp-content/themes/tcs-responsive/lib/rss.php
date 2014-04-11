<?php

/* RSS Feeds for challenge listings */
add_action('init', 'challengesRSS');

function challengesRSS() {
  add_feed('challenges-feed', 'feed_challenges', 10, 1);
}

function feed_challenges() {
  get_template_part('rss', 'challenges');
}

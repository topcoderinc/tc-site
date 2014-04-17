<?php

add_action('init', 'tc_add_custom_rewrite_rules');

function tc_add_custom_rewrite_rules() {
  // Active Contest
  add_rewrite_rule(
    '^' . ACTIVE_CONTESTS_PERMALINK . '/([^/]*)/?$',
    'index.php?pagename=challenges&contest_type=$matches[1]',
    'top'
  );
  add_rewrite_rule(
    '^' . ACTIVE_CONTESTS_PERMALINK . '/([^/]*)/([0-9]*)/?$',
    'index.php?pagename=active-contests&contest_type=$matches[1]&pages=$matches[2]',
    'top'
  );


  add_rewrite_rule(
    '^' . DESIGN_CONTESTS_PERMALINK . '/([^/]*)/?$',
    'index.php?pagename=$matches[1]&contest_type=design',
    'top'
  );
  add_rewrite_rule(
    '^' . DEVELOP_CONTESTS_PERMALINK . '/([^/]*)/?$',
    'index.php?pagename=$matches[1]&contest_type=develop',
    'top'
  );
  add_rewrite_rule(
    '^' . DATA_CONTESTS_PERMALINK . '/([^/]*)/?$',
    'index.php?pagename=$matches[1]&contest_type=data',
    'top'
  );

  // Past Contest
  add_rewrite_rule(
    '^' . PAST_CONTESTS_PERMALINK . '/([^/]*)/?$',
    'index.php?pagename=past-contests&contest_type=$matches[1]',
    'top'
  );
  add_rewrite_rule(
    '^' . PAST_CONTESTS_PERMALINK . '/([^/]*)/([0-9]*)/?$',
    'index.php?pagename=past-contests&contest_type=$matches[1]&pages=$matches[2]',
    'top'
  );

  // Past Contest
  add_rewrite_rule(
    '^' . REVIEW_OPPORTUNITIES_PERMALINK . '/([^/]*)/?$',
    'index.php?pagename=review-opportunities&contest_type=$matches[1]',
    'top'
  );
  add_rewrite_rule(
    '^' . REVIEW_OPPORTUNITIES_PERMALINK . '/([^/]*)/([0-9]*)/?$',
    'index.php?pagename=review-opportunities&contest_type=$matches[1]&pages=$matches[2]',
    'top'
  );

  // Contest Details
  add_rewrite_rule(
    '^' . CONTEST_DETAILS_PERMALINK . '/([^/]*)/?$',
    'index.php?pagename=challenge-details&contestID=$matches[1]',
    'top'
  );

  // Member Profile
  //add_rewrite_rule ( '^'.MEMBER_PROFILE_PERMALINK.'/([^/]*)/?$', 'index.php?pagename=member-profile&handle=$matches[1]', 'top' );
  add_rewrite_rule(
    '^' . MEMBER_PROFILE_PERMALINK . '/([^/]*)/?([^/]*)$',
    'index.php?pagename=member-profile&handle=$matches[1]&tab=$matches[2]',
    'top'
  );

  // Blog Category
  //add_rewrite_rule ( '^'.BLOG_PERMALINK.'/([^/]*)/?$', 'index.php?pagename=blog-page&slug=$matches[1]', 'top' );
  //add_rewrite_rule ( '^'.BLOG_PERMALINK.'/([^/]*)/page/([0-9]*)/?$', 'index.php?pagename=blog-page&slug=$matches[1]&page=$matches[2]', 'top' );
  add_rewrite_rule(
    '^' . ACTIVE_CONTESTS_PERMALINK . '/([^/]*)/?$',
    'index.php?pagename=challenges&contest_type=$matches[1]',
    'top'
  );

  // Case studies Category
  //add_rewrite_rule ( '^'.CASE_STUDIES_PERMALINK.'/([^/]*)/?$', 'index.php?pagename=case-studies&slug=$matches[1]', 'top' );
  //add_rewrite_rule ( '^'.CASE_STUDIES_PERMALINK.'/([^/]*)/?$', 'index.php?pagename=case-studies&page=$matches[1]', 'top' );
  //add_rewrite_rule ( '^'.CASE_STUDIES_PERMALINK.'/([^/]*)/page/([0-9]*)/?$', 'index.php?pagename=case-studies&slug=$matches[1]&page=$matches[2]', 'top' );

  // challenges
  add_rewrite_rule('^challenges/([^/]*)/?$', 'index.php?pagename=challenge-details&contestID=$matches[1]', 'top');

  // challenge terms
  add_rewrite_rule(
    '^challenge-details/terms/detail/([^/]*)/?$',
    'index.php?pagename=term-details&termsOfUseID=$matches[1]',
    'top'
  );

  // challenge term details
  add_rewrite_rule('^challenge-details/terms/([^/]*)/?$', 'index.php?pagename=terms-list&contestID=$matches[1]', 'top');

  // register to challenge
  add_rewrite_rule(
    '^challenge-details/register/([^/]*)/?$',
    'index.php?pagename=challenge-details&contestID=$matches[1]&autoRegister=true',
    'top'
  );

  // submit to challenge
  add_rewrite_rule( '^challenge-details/([^/]*)/submit/?$', 'index.php?pagename=challenge-submit&contestID=$matches[1]', 'top');


  // Blog search
  //add_rewrite_rule('^'.BLOG_PERMALINK.'/?$', 'index.php?', 'top');
  // Active Challenges
  add_rewrite_rule(
    '^active-challenges/([^/]*)/?$',
    'index.php?pagename=active-challenges&contest_type=$matches[1]',
    'top'
  );

  // Past Challenges
  add_rewrite_rule('^past-challenges/([^/]*)/?$', 'index.php?pagename=past-challenges&contest_type=$matches[1]', 'top');

  // Upcoming Challenges
  add_rewrite_rule('^upcoming-challenges/([^/]*)/?$', 'index.php?pagename=upcoming-challenges&contest_type=$matches[1]', 'top');
  
  // Review Challenges
  add_rewrite_rule(
    '^review-opportunities/([^/]*)/?$',
    'index.php?pagename=review-opportunities&contest_type=$matches[1]',
    'top'
  );
  add_rewrite_rule(
    '^review-opportunity/([^/]*)/([^/]*)/?$',
    'index.php?pagename=review-opportunity-details&contest_type=$matches[1]&contestID=$matches[2]',
    'top'
  );

  // Bug Races
  add_rewrite_rule('^bug-races/([^/]*)/?$', 'index.php?pagename=bug-races&contest_type=$matches[1]', 'top');

  // Search results
  add_rewrite_rule('^search/?$', 'index.php?', 'top');
}

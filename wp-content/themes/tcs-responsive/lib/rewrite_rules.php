<?php


add_action('init', 'tc_add_custom_rewrite_rules');

function tc_add_custom_rewrite_rules() {

  // rss for challenges
  add_rewrite_rule('^challenges/feed/?$','index.php?feed=challenges-feed', 'top');

  // Contest Details
  add_rewrite_rule(
    '^' . CONTEST_DETAILS_PERMALINK . '/([^/]*)/?$',
    'index.php?pagename=challenge-details&contestID=$matches[1]',
    'top'
  );

    /* Angular Contest Details
    add_rewrite_rule(
        '^' . CONTEST_DETAILS_PERMALINK . '/([^/]*)/?$',
        'index.php?pagename=angular-challenge-details&contestID=$matches[1]',
        'top'
    );*/

  // Member Profile
  //add_rewrite_rule ( '^'.MEMBER_PROFILE_PERMALINK.'/([^/]*)/?$', 'index.php?pagename=member-profile&handle=$matches[1]', 'top' );
  add_rewrite_rule(
    '^' . MEMBER_PROFILE_PERMALINK . '/([^/]*)/?([^/]*)$',
    'index.php?pagename=member-profile&handle=$matches[1]&tab=$matches[2]',
    'top'
  );

  add_rewrite_rule(
    '^' . MEMBER_PROFILE_ANGULAR_PERMALINK . '/([^/]*)/?([^/]*)$',
    'index.php?pagename=member-profile-angular&handle=$matches[1]&tab=$matches[2]',
    'top'
  );

  // Case studies Category
  //add_rewrite_rule ( '^'.CASE_STUDIES_PERMALINK.'/([^/]*)/?$', 'index.php?pagename=case-studies&slug=$matches[1]', 'top' );
  //add_rewrite_rule ( '^'.CASE_STUDIES_PERMALINK.'/([^/]*)/?$', 'index.php?pagename=case-studies&page=$matches[1]', 'top' );
  //add_rewrite_rule ( '^'.CASE_STUDIES_PERMALINK.'/([^/]*)/page/([0-9]*)/?$', 'index.php?pagename=case-studies&slug=$matches[1]&page=$matches[2]', 'top' );

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

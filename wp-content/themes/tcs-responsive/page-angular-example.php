<?php
/**
 * Template Name: Example AngularJS Template
 */

/**
 * @file
 * This template shows a list of challenges
 */

// Add the angluar libraries to WP
tc_setup_angular();

// Get the default header
get_header();
?>

<div ng-app="tc" class="content">
  <div id="main" ng-controller="ChallengeListingCtrl">
    <article id="mainContent" class="layChallenges">
      <div class="container">
        <header>
          <h1>
            Example Angular Listing Page
            <?php get_template_part("content", "rss-icon"); ?>
          </h1>
        </header>
        <div id="tableView" class="viewTab">
          <div class="tableWrap tcoTableWRap" ng-grid="gridOptions"></div>
        </div>
      </div>
    </article>
  </div>
</div>

<?php get_footer(); ?>
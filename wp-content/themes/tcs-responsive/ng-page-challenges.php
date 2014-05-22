<?php
/** 
* Template Name: Challenges Active Contest List Page - Responsive */
/** 
* @file 
* This template shows a list of challenges */
// Add the angluar libraries to WP 
tc_setup_angular(); //tcs_responsive_scripts(); 
// Get the default header
get_header(); ?>
<script>
  window.wordpressConfig = {
    permalink: '<?php echo get_permalink();?>',
    stylesheetDirectoryUri: '<?php echo get_stylesheet_directory_uri(); ?>'
  }
</script>

<div data-ng-app="tc" class="content">
  <div id="main" data-ng-controller="ChallengeListingCtrl">
    <div data-tc-challenges-tabs contest="contest"></div>


    <article id="mainContent" class="layChallenges">
      <div class="container">
        <header>
          <h1>
            {{titles[contest.contestType]}}
            <?php get_template_part("content", "rss-icon"); ?>
          </h1>
          <aside class="rt" ng-show="contest.listType !== 'past'">
            <span class="views">
              <a href="" ng-click="view = 'grid'" class="gridView" ng-class="{isActive: view == 'grid'}"></a>
              <a href=""  ng-click="view = 'table'" class="listView" ng-class="{isActive: view == 'table'}"></a>
            </span>
          </aside>
        </header>
        <div data-tc-challenges-actions contest="contest" search="search" ng-show="contest.contestType && contest.contestType != ''"></div>
        <div class="searchFilter hide" tc-challenges-search ng-show="search.show" on-submit="submit" contest="contest" search="search" style="display: block;"></div>
        <div class="upcomingCaption" ng-show="contest.listType === 'upcoming'">All upcoming challenges may change</div>
        <div ng-show="challenges.length > 0">
          <div id="tableView" class="viewTab" ng-show="view == 'table'">
            <div class="tableWrap tcoTableWRap dataTable tcoTable challengesGrid" ng-grid="gridOptions"></div>
          </div>
        </div>
        <div id="gridView2" class="viewTab hide" style="display: block;" ng-show="view == 'grid'" ng-class="{contestAll: contest.contestType == ''}">
          <div class="alt" id="gridAll" ng-class="{contestGrid: true}">
            <div ng-repeat="challenge in challenges" tc-contest-grid challenge="challenge"></div>
          </div>
        </div>
        <div class="dataChanges">
          <div class="lt">
            <a class="viewAll" data-ng-click="challenges = allChallenges">View All</a>
          </div>
          <div id="challengeNav" class="rt">
            <a class="prevLink" ng-show="page > 1" ng-click="page = page - 1">
              <i></i> Prev
            </a>
            <a class="nextLink" ng-show="totalServerItems > page * pageSize" ng-click="page = page + 1">
              Next <i></i>
            </a>
          </div>
          <div class="mid onMobi">
            <a href="#" class="viewActiveCh">
              View Active Challenges<i></i>
            </a>
            <a href="#" class="viewPastCh">
              View Past Challenges<i></i>
            </a>
          </div>
        </div>
        <div style="font-size:20px;" ng-show="challenges.length === 0">There are no active challenges under this category. Please check back later</div>
      </div>
    </article>
  </div>
</div>

<?php get_footer(); ?>
<?php
/**
 * Template Name: Member Profile Using AngularJS
 */

/**
 * @file
 * This file shows member profile using AngularJS
 */

// Add angular libraries
tc_setup_angular();

// Get the default header
get_header();

$activity = get_activity_summary ();

$handle = get_query_var ( 'handle' );

// This is needed to by content-member-*.php templates
$coder = get_raw_coder ( $handle, '' );

# if handle not found redirect to 404 - member not found (Dan.V - I-104783)
if (!isset($coder->handle) ) {
  wp_redirect(site_url('404.php'));
  exit;
}


$tab = get_query_var ( 'tab' );
$track = "data/srm";

if ($tab == "algo") {
	$track = "data/srm";
} else if ($tab == "develop") {
	$track = "develop";
} else if ($tab == "design") {
	$track = "design";
}
$ct = get_query_var ( 'ct' );
if ($ct == 'marathon') {
	$track = "data/marathon";
}

$userkey = get_option ( 'api_user_key' );
// rint_r($coder);

?>

<script>var user="<?php echo $handle;?>";var THEME_URL="<?php echo THEME_URL;?>";</script>

<div class="content" ng-app="tc.memberProfile" ng-controller="MemberProfileController">
	<div id="main" class="coderProfile">
    <ng-include src="templateUrl"></ng-include>

		<!-- /#hero -->
<?php
// $coder = get_member_statistics ( $handle, $track );
?>

		<article id="mainContent" class="noShadow">
			<article class="coderRatings">
				<div class="container">
					<div class="actions track<?php echo $tab;?>">
						<ul class="trackSwitch switchBtns">
							<li class="first"><a href="./?tab=design" class="<?php if($tab == "design"){ echo "isActive";}?>">Design</a></li>
							<li><a href="./?tab=develop" class="<?php if($tab == "develop"){ echo "isActive";}?>">Develop</a></li>
							<li class="last"><a href="./?tab=algo" class="<?php if($tab == "algo" || $tab == "marathon" || $tab == null || $tab == ""){ echo "isActive";}?>">Data Science</a></li>
						</ul>
						<!-- /.trackSwitch -->
						<ul class="viewSwitch switchBtns">
							<li class="graphView first"><a id="graphButton" href="#graphView"></a></li>
							<li class="tabularView last"><a id="tableButton" href="#tabularView" class="isActive"></a></li>
						</ul>
					</div>
					<!-- /.actions -->
					<div class="dataTabs">
						<?php
						if ($tab == "design") {
							get_template_part ( 'content', 'member-design' );
						} else if ($tab == "develop") {
							get_template_part ( 'content', 'member-develop' );
						} else if ($ct == "marathon") {
							get_template_part ( 'content', 'member-marathon' );
						} else {
							get_template_part ( 'content', 'member-algo' );
						}
						?>
					</div>
					<!-- /.dataTabs -->
				</div>
				<div class="clear"></div>
				<div class="forumWrap hide">
						<?php // get_template_part('content', 'forum');?>
				</div>
				<!-- /.forumWrap -->
			</article>
			<!-- /.coderRatings -->
		</article>
		<!-- /#mainContent -->
<?php get_footer(); ?>

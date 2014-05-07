<?php
/**
 * Template Name: Challenges Past Contest List Page
 */

$listType = "Past";

include locate_template('header-challenge-landing.php');

?>

<div class="content">
	<div id="main">

	<?php if(have_posts()) : the_post();?>
		<?php the_content();?>
	<?php endif; wp_reset_query();?>

		<?php include(locate_template('nav-challenges-list-tabs.php'));?>

		<article id="mainContent" class="layChallenges">
			<div class="container">
				<header>
                  <h1>
                    <?php echo $page_title; ?>
                    <?php get_template_part("content", "rss-icon"); ?>
                  </h1>

				</header>
				<div class="actions alt">
					<?php include(locate_template('nav-challenges-list-type.php'));?>
					<div class="rt">
                      <span class="subscribeTopWrapper" style="border-bottom:0px;height:30px;margin-bottom:0px">

                      </span>
						<a href="javascript:;" class="searchLink advSearch">
							<i></i>Advanced Search
						</a>
					</div>
				</div>
				<!-- /.actions -->
              <?php get_template_part("contest-advanced-search"); ?>
				<!-- /.searchFilter -->
				<div id="tableView" class=" viewTab">
					<div class="tableWrap tcoTableWrap">
						<table class="dataTable tcoTable">
							<thead>
								<tr>
									<th class="colCh" data-placeholder="challengeName">Challenges<i></i></th>
									<th class="colType" data-placeholder="challengeType">Type<i></i></th>
									<th class="colTime desc" data-placeholder="submissionEndDate">Timeline<i></i></th>
									<th class="colPur" data-placeholder="prize1">Prizes<i></i></th>
									<th class="colPhase" data-placeholder="currentPhaseName">Current Phase<i></i></th>
									<th class="colReg" data-placeholder="numRegistrants">Registrants<i></i></th>
									<th class="colSub" data-placeholder="numSubmissions">Eligible Submissions<i></i></th>
									<th class="colReg noSort" data-placeholder="winners">Winners<i></i></th>
									<th class="colAccessLevel noSort" data-placeholder="visibility">Public/Private<i></i></th>
								</tr>
							</thead>
							<tbody>

							</tbody>
						</table>
					</div>
				</div>
				<!-- /#tableView -->

				<div class="dataChanges">
					<div class="lt">
						<!--<a href="javascript:;" class="viewAll">View All</a>-->
					</div>
					<div id="challengeNav" class="rt">
						<a href="javascript:;" class="prevLink">
							<i></i> Prev
						</a>
						<a href="javascript:;" class="nextLink">
							Next <i></i>
						</a>
					</div>
					<div class="mid onMobi">
						<a href="#" class="viewActiveCh">
							View Active Challenges<i></i>
						</a>
						<a href="#" class="viewPastCh">
							View Upcoming Challenges<i></i>
						</a>
					</div>
				</div>
				<!-- /.dataChanges -->
			</div>
		</article>
		<!-- /#mainContent -->
<?php get_footer(); ?>

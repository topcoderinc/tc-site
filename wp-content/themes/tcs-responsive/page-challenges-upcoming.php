<?php
/**
 * Template Name: Challenges Upcoming Contest List Page
 */

$listType = get_post_meta($postId,"List Type",true) =="" ? "Upcoming" : get_post_meta($postId,"List Type",true);

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
					<aside class="rt">
						<span class="views"> <a href="#gridView" class="gridView"></a> <a href="#tableView" class="listView isActive"></a>
						</span>
					</aside>
				</header>
				<div class="actions">
					<?php include(locate_template('nav-challenges-list-type.php'));?>
					<div class="rt">
                        <a href="javascript:;" class="searchLink advSearch">
                            <i></i>Advanced Search
                        </a>
                    </div>
                </div>
                <!-- /.actions -->

                <?php get_template_part("contest-advanced-search"); ?>

				<div id="tableView" class=" viewTab">
					<div class="tableWrap tcoTableWrap">
						<table class="dataTable tcoTable">
						<caption>All upcoming challenges may change</caption>
							<thead>
								<tr>
									<th class="colCh" data-placeholder="challengeName">Contest Name<i></i></th>
									<th class="colType" data-placeholder="challengeType">Type<i></i></th>
									<th class="colTime desc" data-placeholder="postingDate">Timeline<i></i></th>
									<th class="colDur noSort" data-placeholder="contestDuration">Duration (days)<i></i></th>
									<th class="colPur noSort" data-placeholder="prize">First Prize<i></i></th>
									<th class="colTech noSort" data-placeholder="technologies">Technologies<i></i></th>
									<th class="colStat noSort" data-placeholder="status">Status<i></i></th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
				<!-- /#tableView -->
				<div id="gridView" class="viewTab hide">
					<div class="contestGrid alt">

					</div>
					<!-- /.contestGrid -->
				</div>
				<!-- /#gridView -->
				<div class="dataChanges">
					<div class="lt">
						<a href="javascript:;" class="viewAll">View All</a>
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
							View Past Challenges<i></i>
						</a>
					</div>
				</div>
				<!-- /.dataChanges -->
			</div>
		</article>
		<!-- /#mainContent -->
<?php
get_footer();
?>
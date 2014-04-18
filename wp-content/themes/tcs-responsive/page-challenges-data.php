
<script type="text/javascript" >
	var siteurl = "<?php bloginfo('siteurl');?>";

	var reviewType = "data";
	var ajaxAction = "get_active_data_challenges";
	var stylesheet_dir = "<?php bloginfo('stylesheet_directory');?>";
	var currentPage = 1;
	var postPerPage = <?php echo $postPerPage;?>;
	var contest_type = "<?php echo $contest_type;?>";
	var listType = "<?php echo $listType;?>";
</script>
<div class="content">
	<div id="main">

	<?php if(have_posts()) : the_post();?>
		<?php the_content();?>
	<?php endif; wp_reset_query();?>

		<?php include(locate_template('nav-challenges-list-tabs.php'));?>

		<article id="mainContent" class="layChallenges">
			<div class="container">
				<header>
					<h1>Data Challenges</h1>
				</header>
				<div class="subscribeTopWrapper" style="border-bottom:0px;height:30px;margin-bottom:0px">
					<?php
					//mock upcoming as active cause upcoming data api does not work yet
					if (strtolower($listType) === "upcoming") {
						$list = "active";
					} else {
						$list = $listType;
					}
					$FeedURL = get_bloginfo('wpurl')."/challenges/feed?list=" . $list . "&contestType=data";
					?>
					<a class="feedBtn" href="<?php echo $FeedURL;?>">Subscribe to data-science challenges </a>
				</div>
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
						<table class="dataTable tcoTable centeredTable reviewTable">
						<?php
						if (strtolower($listType) === "upcoming") {
						?>
						<caption>All upcoming challenges may change</caption>
						<?php
						}
						?>
							<thead>
								<tr>
									<th class="colCh  noSort" data-placeholder="">Contest Name<i></i></th>
									<th class="colRstart noSort" data-placeholder="">Type<i></i></th>
									<th class="colRstart noSort" data-placeholder="">Start<i></i></th>
									<th class="colSub noSort" data-placeholder="">Registrants<i></i></th>
								</tr>
							</thead>
							<tbody>
								<!-- demo records will be automatically deleted while loading data using AJAX -->

							</tbody>
						</table>
					</div>
				</div>
				<!-- /#tableView -->

				<div class="dataChanges">
					<div class="lt">

					</div>
					<div id="challengeNav" class="rt">
						<a href="javascript:;" class="prevLink">
							<i></i> Prev
						</a>
						<a href="javascript:;" class="nextLink">
							Next <i></i>
						</a>
					</div>
				</div>
				<!-- /.dataChanges -->

			</div>
		</article>
		<!-- /#mainContent -->

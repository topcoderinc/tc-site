<?php
/**
 * Template Name: Challenge Page
 */
?>
<?php get_header(); 


$values = get_post_custom ( $post->ID );

$userkey = get_option ( 'api_user_key' );
$siteURL = site_url ();
?>

<?php
// get contest details
	$contest_type = get_query_var ( 'contest_type' );
	$contest_type = str_replace("_", " ", $contest_type);
	$postPerPage = get_option("contest_per_page") == "" ? 30 : get_option("contest_per_page");
?>
<script type="text/javascript" >
	var siteurl = "<?php bloginfo('siteurl');?>";
	var activePastContest = "active";
	var challengeDetailsUrl =  "<?php echo get_page_link_by_slug('challenge-details'); ?>";
	$(document).ready(function() {
		app.buildRequestData("activeContest", "<?php echo $contest_type;?>");
		app.challenges.init();
		//listActiveContest("activeContest","activeContest","<?php // echo $contest_type;?>");
	});
</script>
<div class="content">
	<div id="main">
	
	<?php if(have_posts()) : the_post();?>
		<?php the_content();?>
	<?php endif; wp_reset_query();?>



		<article id="mainContent" class="layChallenges">
			<input type="hidden" class="contestType" value="activeContest"/>
			<input type="hidden" class="postPerPage" value="<?php echo $postPerPage;?>"/>
					<div  id="activeContest" class="container">
						<header>
							<h1>Open Challenges</h1>
							<aside class="rt">
								<!-- <a href="javascript:;" class="link viewPastCh">View Past Challenges</a> -->
								<span class="views"> <a href="#gridView" class="gridView"></a> <a href="#tableView" class="listView isActive"></a>
								</span>
							</aside>
						</header>
						<div class="actions">
							<div class="mid challengeType">
								<ul>
									<li><a href="all" class="active link">All</a></li>
									<li><a href="design" class="link design">Design</a></li>
									<li><a href="develop" class="link develop">Develop </a></li>
									<li><a href="data" class="link data">Data Science</a></li>
								</ul>

							</div>
							<div class="lt">
								<!-- <span>Sort by</span>
								<div class="ddWrap">
								<a href="javascript:;" class="upDown val">
									End Date <i></i>
								</a>
								<ul class="list">
									<li class="active">End date</li>
									<li>Challenge title</li>
									<li>Prize</li>
								</ul>
								</div>
								-->
							</div>
							<div class="rt">
							<div class="subscribeTopWrapper" style="border-bottom:0px;height:30px;margin-bottom:0px">
								<?php
								//$contest_type="";
								$FeedURL = get_bloginfo('wpurl')."/challenges/feed?list=active&contestType=".$contest_type;
								?>
								<a class="feedBtn" href="<?php echo $FeedURL;?>">Subscribe to <?php echo $contest_type; ?> challenges </a>
							</div>
								<!-- 
								<a href="javascript:;" class="searchLink">
									<i></i>Advanced Search
								</a>
								-->
							</div>
						</div>
						<!-- /.actions -->
						<div id="tableView" class=" viewTab">
							<div class="tableWrap">
								<table class="dataTable">
									<thead>
										<tr class="head">
											<th class="colCh asc" char="contestName" >Challenges</th>
											<th class="colType" char="contestType" >Type</th>
											<th class="colTime" char="startDate" >Timeline</th>
											<th class="colTLeft" char="">Time Left</th>
											<th class="colPur" char="purse" >Prizes</th>
											<th class="colReg" char="registrants">Registrants</th>
											<th class="colSub" char="submissions">Submissions</th>
											<th>&nbsp;</th>
										</tr>
										
									</thead>
									<tbody>
										<!-- demo records will be automatically deleted while loading data using AJAX -->
										
									</tbody>
								</table>
							</div>
						</div>
						<!-- /#tableView -->
						<div id="gridView" class="viewTab hide">
							<div class="contestGrid">
								<div class="contest trackSD">
									<div class="cgCh">
										<a href="#" class="contestName">
											<i></i>Cornell - Responsive Storyboard Economics Department Site Redesign Contest
										</a>
									</div>
									<div class="cgTime">
										<div>
											<div class="row">
												<label class="lbl">Start Date</label>
												<div class="val">10/25/2013 &nbsp;12:00 EST</div>
											</div>
											<div class="row">
												<label class="lbl">Round End</label>
												<div class="val">10/25/2013 &nbsp;12:00 EST</div>
											</div>
											<div class="row">
												<label class="lbl">End Date</label>
												<div class="val">10/25/2013 &nbsp;12:00 EST</div>
											</div>
										</div>
									</div>
									<div class="genInfo">
										<p class="cgTLeft">
											<i></i>3d
										</p>
										<p class="cgPur">
											<i></i>$1500
										</p>
										<p class="cgReg">
											<i></i>10
										</p>
										<p class="cgSub">
											<i></i>10
										</p>
									</div>
								</div>
								<!-- /.contest -->
								<div class="contest trackUX">
									<div class="cgCh">
										<a href="#" class="contestName">
											<i></i>Cornell - Responsive Storyboard Economics Department Site Redesign Contest
										</a>
									</div>
									<div class="cgTime">
										<div>
											<div class="row">
												<label class="lbl">Start Date</label>
												<div class="val">10/25/2013 &nbsp;12:00 EST</div>
											</div>
											<div class="row">
												<label class="lbl">Round End</label>
												<div class="val">10/25/2013 &nbsp;12:00 EST</div>
											</div>
											<div class="row">
												<label class="lbl">End Date</label>
												<div class="val">10/25/2013 &nbsp;12:00 EST</div>
											</div>
										</div>
									</div>
									<div class="genInfo">
										<p class="cgTLeft">
											<i></i>3d
										</p>
										<p class="cgPur">
											<i></i>$1500
										</p>
										<p class="cgReg">
											<i></i>10
										</p>
										<p class="cgSub">
											<i></i>10
										</p>
									</div>
								</div>
								<!-- /.contest -->
								<div class="contest trackAn">
									<div class="cgCh">
										<a href="#" class="contestName">
											<i></i>Cornell - Responsive Storyboard Economics Department Site Redesign Contest
										</a>
									</div>
									<div class="cgTime">
										<div>
											<div class="row">
												<label class="lbl">Start Date</label>
												<div class="val">10/25/2013 &nbsp;12:00 EST</div>
											</div>
											<div class="row">
												<label class="lbl">Round End</label>
												<div class="val">10/25/2013 &nbsp;12:00 EST</div>
											</div>
											<div class="row">
												<label class="lbl">End Date</label>
												<div class="val">10/25/2013 &nbsp;12:00 EST</div>
											</div>
										</div>
									</div>
									<div class="genInfo">
										<p class="cgTLeft">
											<i></i>3d
										</p>
										<p class="cgPur">
											<i></i>$1500
										</p>
										<p class="cgReg">
											<i></i>10
										</p>
										<p class="cgSub">
											<i></i>10
										</p>
									</div>
								</div>
								<!-- /.contest -->
								<div class="contest trackSD">
									<div class="cgCh">
										<a href="#">
											<i></i>Cornell - Responsive Storyboard Economics Department Site Redesign Contest
										</a>
									</div>
									<div class="cgTime">
										<div>
											<div class="row">
												<label class="lbl">Start Date</label>
												<div class="val">10/25/2013 &nbsp;12:00 EST</div>
											</div>
											<div class="row">
												<label class="lbl">Round End</label>
												<div class="val">10/25/2013 &nbsp;12:00 EST</div>
											</div>
											<div class="row">
												<label class="lbl">End Date</label>
												<div class="val">10/25/2013 &nbsp;12:00 EST</div>
											</div>
										</div>
									</div>
									<div class="genInfo">
										<p class="cgTLeft">
											<i></i>3d
										</p>
										<p class="cgPur">
											<i></i>$1500
										</p>
										<p class="cgReg">
											<i></i>10
										</p>
										<p class="cgSub">
											<i></i>10
										</p>
									</div>
								</div>
								<!-- /.contest -->
							</div>
							<!-- /.contestGrid -->
						</div>
						<!-- /#gridView -->
						<div class="pagingWrapper"></div><!-- /.pagingWrapper -->
						<div class="dataChanges">
							<div class="lt">
								<a href="javascript:;" class="viewAll">View All</a>
							</div>
							<div class="rt">
								<a href="#0" class="prevLink hide">
									<i></i> Prev
								</a>
								<a href="#2" class="nextLink">
									Next <i></i>
								</a>
							</div>
							<div class="mid onMobi">
								<a href="#" class="viewPastCh">
									View Past Challenges<i></i>
								</a>
							</div>
						</div>
						<!-- /.dataChanges -->
					</div>
				</article>		
		<!-- /#mainContent -->
<?php get_footer(); ?>
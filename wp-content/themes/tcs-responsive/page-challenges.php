<?php
/**
 * Template Name: Challenge Page
 * Author : evilkyro1965
 */
get_header('challenge-landing'); 


$values = get_post_custom ( $post->ID );

$siteURL = site_url ();
$postId = $post->ID;
?>

<?php
	// get contest details
	$contest_type = "";
	$listType = "AllActive";
	$postPerPage = get_option("challenges_per_page") == "" ? 10 : get_option("challenges_per_page");
?>

<script type="text/javascript" >
	var siteurl = "<?php bloginfo('siteurl');?>";
	
	var reviewType = "contest";
	var isBugRace = false;
	var ajaxAction = "get_challenges";
	var stylesheet_dir = "<?php bloginfo('stylesheet_directory');?>";
	var currentPage = 1;
	var postPerPage = <?php echo $postPerPage;?>;
	var contest_type = "<?php echo $contest_type;?>";
	var listType = "<?php echo $listType;?>";
</script>
<div class="content">
	<div id="main">
	
	<?php if(have_posts()) : the_post();?>
		<?php //the_content();?>
	<?php endif; wp_reset_query();?>

		<div id="hero">
			<?php 
				$activeDesignChallengesLink = get_bloginfo('siteurl')."/active-challenges/design/";
				$activeDevlopChallengesLink = get_bloginfo('siteurl')."/active-challenges/develop/";
				$activeDataChallengesLink = get_bloginfo('siteurl')."/active-challenges/data/";
			?>
			<div class="container grid grid-float">
				<div class="grid-3-1 track trackUX<?php if($contest_type=="design") echo " isActive"; ?>" >
					<a href="<?php echo $activeDesignChallengesLink;?>"><i></i>Graphic Design Challenges
					</a><span class="arrow"></span>
				</div>
				<div class="grid-3-1 track trackSD<?php if($contest_type=="develop") echo " isActive"; ?>" >
					<a href="<?php echo $activeDevlopChallengesLink;?>"><i></i>Development Challenges
					</a><span class="arrow"></span>
				</div>
				<div class="grid-3-1 track trackAn<?php if($contest_type=="data") echo " isActive"; ?>" >
					<a href="<?php echo $activeDataChallengesLink;?>">
						<i></i>Data Science Challenges
					</a><span class="arrow"></span>
				</div>
			</div>
		</div>
		<!-- /#hero -->
				
		<article id="mainContent" class="layChallenges landingChallenges">
			<div class="container">

				<header>
					<h1>Open Challenges</h1>
					<aside class="rt">
						<span class="views"> <a href="#gridView" class="gridView"></a> <a href="#tableView" class="listView isActive"></a>
						</span>
					</aside>
				</header>
				<div class="actions">
					<div class="mid challengeType">
						<?php 
							$activeDesignChallengesLink = get_bloginfo('siteurl')."/active-challenges/design/";
							$activeDevlopChallengesLink = get_bloginfo('siteurl')."/active-challenges/develop/";
							$activeDataChallengesLink = get_bloginfo('siteurl')."/active-challenges/data/";
						?>
						<ul>
							<li><a href="<?php echo get_bloginfo('siteurl')."/challenges"; ?>" class="active link">All</a></li>
							<li><a href="<?php echo $activeDesignChallengesLink;?>" class="link design">Design</a></li>
							<li><a href="<?php echo $activeDevlopChallengesLink;?>" class="link develop">Develop </a></li>
							<li><a href="<?php echo $activeDataChallengesLink;?>" class="link data">Data Science</a></li>
						</ul>
					</div>
					<div class="rt">
						<div class="subscribeTopWrapper" style="border-bottom:0px;height:30px;margin-bottom:0px">
							<?php
							//$contest_type="";
							$FeedURL = get_bloginfo('wpurl')."/challenges/feed?list=active&contestType=".$contest_type;
							?>
							<a class="feedBtn" href="<?php echo $FeedURL;?>">Subscribe to <?php echo $contest_type; ?> challenges </a>
						</div>
					</div>
				</div>
				<!-- /.actions -->
				
				<div id="tableView" class="viewTab">
					<div class="tableWrap tcoTableWrap">
						<table id="tcoTableAllContest" class="dataTable tcoTable">
							<thead>
								<tr>
									<th class="colCh" data-placeholder="challengeName">Challenges<i></i></th>
									<th class="colType" data-placeholder="challengeType">Type<i></i></th>
									<th class="colTime" data-placeholder="submissionEndDate">Timeline<i></i></th>
									<th class="colTLeft noSort" data-placeholder="currentPhaseRemainingTime">Time Left<i></i></th>
									<th class="colPur noSort" data-placeholder="prize">Prize<i></i></th>
									<th class="colReg noSort" data-placeholder="numRegistrants">Registrants<i></i></th>
									<th class="colSub noSort" data-placeholder="numSubmissions">Submissions<i></i></th>
								</tr>
							</thead>
							<tbody>
									
							</tbody>
						</table>
					</div>
				</div>
				<!-- /#tableView -->
				<div id="gridView" class="contestAll viewTab hide">
					<div id="gridAll" class="contestGrid alt">
						
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

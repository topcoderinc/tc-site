<?php
/**
 * Template Name: Challenges Past Contest List Page
 * Author : evilkyro1965
 */
get_header('challenge-landing');

$values = get_post_custom ( $post->ID );

$siteURL = site_url ();
$postId = $post->ID;
?>

<?php
	$tcoTooltipTitle = get_option("tcoTooltipTitle");
	$tcoTooltipMessage = get_option("tcoTooltipMessage");

	// get contest details
	$contest_type = get_query_var("contest_type") == "" ? "design" : get_query_var("contest_type");
	$listType = "Past";
	$postPerPage = get_post_meta($postId,"Contest Per Page",true) == "" ? 10 : get_post_meta($postId,"Contest Per Page",true);
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
	<?php
		if($tcoTooltipTitle) echo "var tcoTooltipTitle= '$tcoTooltipTitle';";
		if($tcoTooltipMessage) echo "var tcoTooltipMessage= '$tcoTooltipMessage';";
	?>
</script>
<script type="text/javascript">
	var dataUrl = ajaxUrl+"?action=get_challenges&contest_type="+contest_type+"&listType="+listType+"&pageIndex="+currentPage+"&pageSize="+postPerPage;
</script>
<div class="content">
	<div id="main">

	<?php if(have_posts()) : the_post();?>
		<?php the_content();?>
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
					<a href="<?php echo $activeDevlopChallengesLink;?>"><i></i>Software Development Challenges
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

		<article id="mainContent" class="layChallenges">
			<div class="container">
				<header>
					<h1><?php echo ($contest_type=="design" ? "Graphic Design Challenges" : "Software Development Challenges" ); ?></h1>

				</header>
				<div class="subscribeTopWrapper" style="border-bottom:0px;height:30px;margin-bottom:0px">
					<?php
					$FeedURL = get_bloginfo('wpurl')."/challenges/feed?list=past&contestType=".$contest_type;
					?>
					<a class="feedBtn" href="<?php echo $FeedURL;?>">Subscribe to <?php echo $contest_type; ?> challenges </a>
				</div>
				<div class="actions alt">
					<div class="lt challengeType">
						<?php
							$activeChallenges = get_bloginfo('siteurl')."/active-challenges/".$contest_type."/";
						?>
						<ul>
							<li><a href="<?php echo $activeChallenges;?>" class="link">Open Challenges</a></li>
							<li><a href="javascript:;" class="active link">Past Challenges</a></li>
						</ul>
					</div>
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
									<th class="colPur noSort" data-placeholder="prize">Prizes<i></i></th>
									<th class="colPhase noSort" data-placeholder="currentPhase">Current Phase<i></i></th>
									<th class="colReg noSort" data-placeholder="numRegistrants">Registrants<i></i></th>
									<th class="coleSub noSort" data-placeholder="numSubmissions">Eligible Submissions<i></i></th>
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

<?php
/**
 * Template Name: Challenges Active Contest List Page
 */

$values = get_post_custom ( $post->ID );

$siteURL = site_url ();
$postId = $post->ID;

$tcoTooltipTitle = get_option("tcoTooltipTitle");
$tcoTooltipMessage = get_option("tcoTooltipMessage");

// get contest details
$contest_type = get_query_var("contest_type") == "" ? "design" : get_query_var("contest_type");
$listType = get_post_meta($postId,"List Type",true) =="" ? "Active" : get_post_meta($postId,"List Type",true);
$postPerPage = get_post_meta($postId,"Contest Per Page",true) == "" ? 10 : get_post_meta($postId,"Contest Per Page",true);

$page_title = '';

switch ($contest_type) {
  case "design":
    $page_title = "Graphic Design Challenges";
    break;
  case "data":
    $page_title = "Data Science Challenges";
    break;
  case "develop":
  default:
    $page_title = "Software Development Challenges";
}

get_header('challenge-landing');
?>


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
					<div class="lt challengeType">
						<?php
							$pastChallenges = get_bloginfo('siteurl')."/past-challenges/".$contest_type."/";
						?>
						<ul>
							<li><a href="javascript:;" class="active link">Open Challenges</a></li>
							<li><a href="<?php echo $pastChallenges;?>" class="link">Past Challenges</a></li>
						</ul>
					</div>
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
							<thead>
								<tr>
									<th class="colCh" data-placeholder="challengeName">Challenges<i></i></th>
									<th class="colType" data-placeholder="challengeType">Type<i></i></th>
									<th class="colTime desc" data-placeholder="postingDate">Timeline<i></i></th>
									<th class="colTLeft noSort" data-placeholder="currentPhaseRemainingTime">Time Left<i></i></th>
									<th class="colPur noSort" data-placeholder="prize">Prizes<i></i></th>
									<th class="colPhase noSort" data-placeholder="currentPhase">Current Phase<i></i></th>
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

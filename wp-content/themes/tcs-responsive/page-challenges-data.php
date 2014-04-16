<?php
/**
 * Template Name: Challenges Data Page
 * Author : evilkyro1965
 */
get_header('challenge-landing');

$values = get_post_custom ( $post->ID );

$siteURL = site_url ();
$postId = $post->ID;
?>

<?php
	// get contest details
	$contest_type = "data";
	$postPerPage = get_post_meta($postId,"Contest Per Page",true) == "" ? 10 : get_post_meta($postId,"Contest Per Page",true);

?>

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
					<h1>Data Challenges</h1>
				</header>
				<div class="subscribeTopWrapper" style="border-bottom:0px;height:30px;margin-bottom:0px">
					<?php
					$FeedURL = get_bloginfo('wpurl')."/challenges/feed?list=active&contestType=data";
					?>
					<a class="feedBtn" href="<?php echo $FeedURL;?>">Subscribe to data challenges </a>
				</div>
				<div id="tableView" class=" viewTab">
					<div class="tableWrap tcoTableWrap">
						<table class="dataTable tcoTable centeredTable reviewTable">
							<thead>
								<tr>
									<th class="colCh  noSort" data-placeholder="">Challenges<i></i></th>
									<th class="colRstart noSort" data-placeholder="">Type<i></i></th>
									<th class="colRstart noSort" data-placeholder="">Timeline<i></i></th>
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
<?php get_footer(); ?>

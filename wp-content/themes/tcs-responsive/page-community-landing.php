<?php
/**
 * Template Name: Community Landing Page 
 */
?>
<?php

get_header ();

$values = get_post_custom ( $post->ID );

$userkey = get_option ( 'api_user_key' );
$siteURL = site_url ();
?>

<?php
// get contest details
//$contest_type = get_query_var ( 'contest_type' ); //design,develop,data-marathon
$contest_type = $_GET['contest_type']!="" ? $_GET['contest_type'] : "all" ;
$contest_type = str_replace ( "_", " ", $contest_type );
$postPerPage = get_option ( "contest_per_page" ) == "" ? 30 : get_option ( "contest_per_page" );
?>
<?php if(have_posts()) : the_post();
	$pid = $post->ID;
	$_POST["postId"] = $pid;
	$contest_track = get_post_meta($pid,'Contest Track',true) !="" ? get_post_meta($pid,'Contest Track',true) : "design";
	$_POST["contestTrack"] = $contest_track;
							
				
	
	$arrPromoCat =  array(
						"design"=>"promo-design",
						"develop"=>"promo-development",
						"algorithm"=>"promo-algorithm"
					);
	$promoCat =  $arrPromoCat["".$contest_track];
	
	$topcoderLink = get_post_meta($pid,'Topcoder Link',true) !="" ? get_post_meta($pid,'Topcoder Link',true) : "http://www.topcoder.com";
?>
<?php endif; wp_reset_query();?>
	
<script type="text/javascript">
	var siteurl = "<?php bloginfo('siteurl');?>";
	var activePastContest = "active";
	var contest_track = "<?php echo $contest_track; ?>"
	$(document).ready(function() {
		app.buildRequestData("activeContest","<?php echo $contest_type;?>","design");
		app.communityLanding.init();
		//listActiveContest("activeContest","activeContest","<?php // echo $contest_type;?>");
	});
</script>
<div id="communityDetailsContent" class="content">
	<div id="main">

		<div id="banner" class="<?php echo $contest_track;?>">
			<div class="inner">
				<div class="container">
					<ul class="slider">
						<?php 
						$args = array (

								'post_type' => 'promo',
								'category_name' => $promoCat,
								'orderby' => 'menu_order'

						);
						wp_reset_query();
						$promos = new WP_Query ( $args );
							
						if ($promos->have_posts ()) :

						while ( $promos->have_posts () ) :

						$promos->the_post ();
						?>
						<li class="welcome"><?php the_content(); ?>
						</li>
						<?php endwhile; endif; wp_reset_query();?>
					</ul>
				</div>
			</div>
		</div>
		<div id="stats">
			<div id="communityLandingStats" class="container">
				<p>
					<em class="members"><?php echo get_activity_summary("memberCount"); ?></em> of the world's best minds competing

				</p>
				<a class="btn btnAlt" href="<?php echo $topcoderLink;?>">Join topcoder</a>
			</div>
		</div>
		<!-- /#stats -->	

		<article id="mainContent" class="splitLayout ">
			<input type="hidden" class="contestType" value="activeContest"/>
			<input type="hidden" class="postPerPage" value="<?php echo $postPerPage;?>"/>

			<div class="container">
				<div class="rightSplit  grid-3-3">
					<div class="mainStream partialList  grid-2-3">
						<!-- content wrapper -->
						<div id="communityLandingContent" class="contentWrapper pageContent">
							<?php the_content();?>
						</div>
						<!-- content wrapper end -->
						<div class=" viewTab">
							<div class="tableWrap">
								<table class="dataTable challenges">
									<thead>
										<tr>
											<th class="colCh">Challenges</th>
											<th class="colTime">Timeline</th>
											<th class="colPur">Prizes</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td colspan="3">&nbsp;</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<!-- /#tableView -->
						<div class="dataChanges">
							<div class="rt">
								<a href="javascript:;" class="viewAll">View All</a>
							</div>
						</div>
						<!-- /.dataChanges -->
					</div>
					<!-- /.mainStream -->
					<aside class="sideStream  grid-1-3">
						<div class="diagnostics">
							<div class="activeMembers">
								<p class="val"><?php 
								$summary = get_activity_summary();
								echo number_format($summary->memberCount); ?></p>
								<label class="lbl">ACTIVE MEMBERS</label>
							</div>
							<div class="competingToday">
								<p class="val"><?php echo number_format($summary->activeMembersCount); ?></p>
								<label class="lbl">COMPETING TODAY</label>
							</div>
							<div class="availPrize">
								<p class="val"><?php echo '$' . number_format($summary->prizePurse); ?></p>
								<label class="lbl">AVAILABLE PRIZE</label>
							</div>
							<div class="activeChallenges">
								<p class="val"><?php echo number_format($summary->activeContestsCount); ?></p>
								<label class="lbl">ACTIVE CHALLENGES</label>
							</div>
							<div class="shadow"></div>
						</div>
						<!-- /.diagnostics -->
								<?php dynamic_sidebar('Sidebar Community');?>								
							</aside>
					<!-- /.sideStream -->
					<div class="clear"></div>
				</div>
				<!-- /.rightSplit -->
						
			</div>
		</article>
		<!-- /#mainContent -->
		
		<?php echo get_post_meta($pid,'Stars Of Month Section',true); ?>
		
		<div id="whatsHappening">
			<div class="container grid grid-float">
				<div class="inner">
					<div class="grid-3-1">
						<section>
							<h2>News</h2>
							<div class="slider">
								<ul>
								<?php
								$args = array (
										'post_type' => 'post',
										'category_name' => 'News' 
								);
								$news = new WP_Query ( $args );
								
								if ($news->have_posts ()) :
									while ( $news->have_posts () ) :
										$news->the_post ();
										$thumbId = get_post_thumbnail_id ( $news->ID );
										$iurl = wp_get_attachment_url ( $thumbId );
										?>	
										<li class="slide">
										<div class="slideCon">
											<?php if($iurl != null):?>
											<div class="featuredImg">
												<img src="<?php echo $iurl;?>" alt="<?php the_title();?>" />
											</div>
											<?php endif;?>
												<p class="title"><?php the_title();?></p>
											<p class="postedBy">
												Posted by
												<a href="<?php bloginfo('wpurl');?>/member-profile/<?php the_author();?>" class="coderTextOrange"><?php the_author();?></a>
													on <?php the_time('F jS, Y');?>
												</p>
											<div class="excerpt">
													<?php echo the_excerpt();?>
												</div>
										</div>
									</li>										
								<?php endwhile; endif; wp_reset_query();?>
									</ul>
							</div>
						</section>
						<!-- /News -->
					</div>
					<div class="grid-3-1 grid-mid">
						<section>
							<h2>Events</h2>
							<div class="slider">
								<ul>
										<?php
										$args = array (
												'post_type' => 'post',
												'category_name' => 'Events' 
										);
										$news = new WP_Query ( $args );
										
										if ($news->have_posts ()) :
											while ( $news->have_posts () ) :
												$news->the_post ();
												$thumbId = get_post_thumbnail_id ( $news->ID );
												$iurl = wp_get_attachment_url ( $thumbId );
												?>	
										<li class="slide">
										<div class="slideCon">
											<?php if($iurl != null):?>
											<div class="featuredImg">
												<img src="<?php echo $iurl;?>" alt="<?php the_title();?>" />
											</div>
											<?php endif;?>
												<p class="title"><?php the_title();?></p>
											<p class="postedBy">
												Posted by
												<a href="<?php bloginfo('wpurl');?>/member-profile/<?php the_author();?>" class="coderTextOrange"><?php the_author();?></a>
													on <?php the_time('F jS, Y');?>
												</p>
											<div class="excerpt">
													<?php echo the_excerpt();?>
												</div>
										</div>
									</li>										
								<?php endwhile; endif; wp_reset_query();?>
									</ul>
							</div>
						</section>
						<!-- /Events -->
					</div>
					<div class="grid-3-1 grid-rt">
						<section>
							<h2>Community Highlights</h2>
							<div class="slider">
								<ul>
								<?php
								$args = array (
										'post_type' => 'post',
										'category_name' => 'Community Highlights' 
								);
								$news = new WP_Query ( $args );
								
								if ($news->have_posts ()) :
									while ( $news->have_posts () ) :
										$news->the_post ();
										$thumbId = get_post_thumbnail_id ( $news->ID );
										$iurl = wp_get_attachment_url ( $thumbId );
										?>	
										<li class="slide">
										<div class="slideCon">
											<?php if($iurl != null):?>
											<div class="featuredImg">
												<img src="<?php echo $iurl;?>" alt="<?php the_title();?>" />
											</div>
											<?php endif;?>
												<p class="title"><?php the_title();?></p>
											<p class="postedBy">
												Posted by
												<a href="<?php bloginfo('wpurl');?>/member-profile/<?php the_author();?>" class="coderTextOrange"><?php the_author();?></a>
													on <?php the_time('F jS, Y');?>
												</p>
											<div class="excerpt">
													<?php echo the_excerpt();?>
												</div>
										</div>
									</li>										
								<?php endwhile; endif; wp_reset_query();?>
							</ul>
							</div>
						</section>
						<!-- /Community Highlights -->
					</div>
				</div>
			</div>
		</div>
		<!-- /#whatsHappening -->

		<article>
			<div id="sloganSection" class="container">
				<?php echo do_shortcode(get_post_meta($pid,'Slogan Content',true)); ?>
			</div>
		</article>
		
		<article id="featuredContent">
			<div class="container">
				<?php echo do_shortcode(get_post_meta($pid,'Featured content',true)); ?>
			</div>
		</article>
		<!-- /#featuredContent -->

<?php get_footer(); ?>
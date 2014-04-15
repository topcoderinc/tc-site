<?php
/**
 * Template Name: Community Page
 */

get_header ();

$values = get_post_custom ( $post->ID );

$userkey = get_option ( 'api_user_key' );
$siteURL = site_url ();

// get contest details
$contest_type = get_query_var ( 'contest_type' );
$contest_type = str_replace ( "_", " ", $contest_type );
$postPerPage = get_option ( "contest_per_page" ) == "" ? 30 : get_option ( "contest_per_page" );

?>

<style>
.bx-controls{display:none}
</style>
<div class="content">
	<div id="main">

	<?php if(have_posts()) : the_post();?>
		<?php the_content();?>
	<?php endif; wp_reset_query();?>



		<article id="mainContent" class="splitLayout ">
			<input type="hidden" class="contestType" value="activeContest"/>
			<input type="hidden" class="postPerPage" value="<?php echo $postPerPage;?>"/>

			<div class="container">
				<div class="rightSplit  grid-3-3">
					<div class="mainStream partialList  grid-2-3">
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
								<a href="/challenges" class="viewAll">View All</a>
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
						<?php dynamic_sidebar('BottomBar Community');?>
					</div>
		</article>
		<!-- /#mainContent -->
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
										'post_type' => 'blog',
										'posts_per_page' => '1',
										'category_name' => 'news-blog'
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
											<!-- <p class="postedBy">
												Posted by
												<a href="<?php bloginfo('wpurl');?>/member-profile/<?php the_author();?>" class="coderTextOrange"><?php the_author();?></a>
													on <?php the_time('F jS, Y');?>
												</p>
											-->
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
												'post_type' => 'blog',
												'posts_per_page' => '1',
												'category_name' => 'events'
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
											<!-- <p class="postedBy">
												Posted by
												<a href="<?php bloginfo('wpurl');?>/member-profile/<?php the_author();?>" class="coderTextOrange"><?php the_author();?></a>
													on <?php the_time('F jS, Y');?>
												</p>
											-->
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
										'post_type' => 'blog',
										'posts_per_page' => '1',
										'category_name' => 'community-highlights'
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
											<!--<p class="postedBy">
												Posted by
												<a href="<?php bloginfo('wpurl');?>/member-profile/<?php the_author();?>" class="coderTextOrange"><?php the_author();?></a>
													on <?php the_time('F jS, Y');?>
												</p>
											-->
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

<?php get_footer(); ?>

<script type="text/javascript">
  var siteurl = "<?php bloginfo('siteurl');?>";
  var activePastContest = "active";
  $(document).ready(function() {
    app.buildRequestData("activeContest","<?php echo $contest_type;?>","");
    app.community.init();
    //listActiveContest("activeContest","activeContest","<?php // echo $contest_type;?>");
  });
</script>

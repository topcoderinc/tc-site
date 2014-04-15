<?php
/**
 * Template Name: Single CaseStudies template
 */
?>
<?php

get_header ();

$values = get_post_custom ( $post->ID );

$userkey = get_option ( 'api_user_key' );
$siteURL = site_url ();
?>

<script type="text/javascript">
	var siteurl = "<?php bloginfo('siteurl');?>";	
</script>
<div class="content">
	<div id="main">

	<?php
	
	if (have_posts ()) :
		the_post ();
		$quote = get_post_meta ( $post->ID, "Quote", true );
		$qAuthor = get_post_meta ( $post->ID, "Quote author", true );
		
		$pid = $post->ID;
		$thumbId = get_post_thumbnail_id ( $pid );
		$iurl = wp_get_attachment_url ( $thumbId );
		$sector = get_post_meta ( $pid, "Sector", true );
		$quote = get_post_meta ( $pid, "Quote", true );
		$qAuthor = get_post_meta ( $pid, "Quote author", true );
		$qAutDesig = get_post_meta ( $pid, "Author designation", true );
		$banner = get_the_content();
		$sidebar = get_post_meta ( $pid, "Sidebar Content", true );
		$customContent = get_post_meta ( $pid, "Description Content", true );
		
		?>
	<!-- Start Overview Page-->
		<div class="pageTitleWrapper">
			<div class="pageTitle pageTitleWithBackLink container">
				<h2 class="casePageTitle">Case Studies</h2>
				<a href="<?php echo get_site_url().'/case-studies'; ?>" class="leftArrowLink backLink">
					Case Study Index<i></i>
				</a>
			</div>
		</div>
		<article id="mainContent" class="casePage">
			<div class="container grid-3">
				<div class="caseDetailsWrapper">
					<div class="caseTitle">
						<img src="<?php echo $iurl;?>" width="230" height="112" alt="">
						<h3><?php the_title();?></h3>
						<p><?php echo $sector;?></p>
					</div>
					<div class="caseBanner">
						<?php echo $banner;?>
					</div>
					<div class="caseDetails  rightSplit grid-3-3 group">
						<aside class="grid-1-3">
							<div class="caseQuote">
								<p class="quoteTxt">“<?php echo $quote;?>”</p>
								<p class="quoterName"><?php echo $qAuthor;?></p>
								<p class="quoterTitle"><?php echo $qAutDesig;?></p>
							</div>
							
							<div class="caseVideo">
								<?php echo $sidebar;?>
							</div>
						</aside>
						<article class="caseBrief grid-2-3">
							<?php echo $customContent; ?>
						</article>
					</div>
				</div>
			</div>


		</article>
		<!-- /#mainContent -->
	<?php endif; wp_reset_query ();?>	
<?php get_footer(); ?>
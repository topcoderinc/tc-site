<?php
/*
Template Name: Single Column
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
		?>
	<!-- Start Overview Page-->
		<div class="pageTitleWrapper">
			<div class="pageTitle container">
				<h2 class="overviewPageTitle <?php echo str_replace(" ", "", get_the_title());?>PageTitle"><?php the_title();?></h2>
			</div>
		</div>




		<article id="mainContent" class="splitLayout singleColumnPage">
			<div class="container">
				<div class="rightSplit  grid-1-1">
					<div class="mainStream postContent pageContent grid-1-1">
						
					<?php the_content();?>
					<?php endif; wp_reset_query();?>
					
					
						<!-- /.pageContent -->

					</div>
					<!-- /.mainStream -->
					
					<div class="clear"></div>
				</div>
				<!-- /.rightSplit -->
			</div>
		</article>
		<!-- /#mainContent -->
<?php get_footer(); ?>
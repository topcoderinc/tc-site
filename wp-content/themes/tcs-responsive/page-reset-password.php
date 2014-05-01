<?php
/*
Template Name: Reset Password
*/
get_header ();

$values = get_post_custom ( $post->ID );

$handle = $_GET["handle"];
$unlockCode = $_GET["unlock_code"];
$updatePassword = $_POST["updatePassword"];

$msg = null;
$error = null;
if( $updatePassword=="true" ) {
	$handle = $_POST["handle"];
	$unlockCode = $_POST["unlockCode"];
	$password = $_POST["password"];
	
	$response = changePassword($handle,$password,$unlockCode);

	$obj = json_decode($response['body']);
	if( $response['response']['code']==200 ) {		
		if ( isset($obj->error) ) {
			$error = $obj->error;
		} else {
			$msg = $obj->description;		
		}
	}
	else {
		$error = $obj->error->details;
	}
}
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
		?>
	<!-- Start Overview Page-->
		<div class="pageTitleWrapper">
			<div class="pageTitle container">
				<h2 class="overviewPageTitle"><?php the_title();?></h2>
			</div>
		</div>

		<article id="mainContent" class="splitLayout overviewPage">
			<div class="container">
				<div class="rightSplit  grid-3-3">
					<div class="mainStream grid-2-3">
						<section class="passwordRecovery" class="pageContent">
							<?php the_content();?>
							<?php 
								if($msg==null) :
							?>
								<?php if ($error!=null) : ?>
								<p class="row info lSpace"><strong><?php echo $error;?></strong></p>
								<?php endif; ?>
								
								<form id="formChangePassword" name="formResetPassword" action="" method="POST">
									<input type="hidden" class="updatePassword" name="updatePassword" value="true" />
									<div class="row">
										<input class="handle" type="text" maxlength="40" name="handle" placeholder="Handle:" size="50" />
										<span class="err1 error" style="display: none;">Required field</span>
									</div>
									<div class="row">
										<input class="password" type="password" maxlength="40" name="password" placeholder="New Password:" size="50" />
										<span class="err1 error" style="display: none;">Required field</span>
										<span class="err2 error" style="display: none;">Password strength is weak</span>
										<span class="err4 error" style="display: none;">Password must be between 7 and 30 characters</span>
										<span class="valid" style="display: none;">Strong</span>
									</div>
									<p class="row info lSpace">
										<span class="strength">
											<span class="field"></span>
											<span class="field"></span>
											<span class="field"></span>
											<span class="field"></span>
										</span>
										7 characters with letters, numbers, &amp; symbols
									</p>
									<div class="row">
										<input class="confirm" type="password" maxlength="40" name="confirm" placeholder="Confirm password:" size="50" />
										<span class="err1 error" style="display: none;">Required field</span>
										<span class="err2 error" style="display: none;">Password confirmation different from above field</span>
									</div>
									<div class="row">
										<input class="unlockCode" type="text" maxlength="40" name="unlockCode" placeholder="Unlock Code:" size="50" />
										<span class="err1 error" style="display: none;">Required field</span>
									</div>
									<div class="row">
										<input type="button" class="btnSubmit" value="Submit" />
									</div>
								</form>
							<?php else : ?>
								<h3><?php echo $msg;?></h3>
							<?php endif; ?>							

						</section>
					<?php endif; wp_reset_query();?>
						<!-- /.pageContent -->

					</div>
					<!-- /.mainStream -->
					<aside class="sideStream  grid-1-3">
						<div class="sideFindRelatedContent">
							<h3>Related Content</h3>

							<ul class="relatedContentList">
							<?php
							// for use in the loop, list 4 post titles related to first tag on current post
							$tags = wp_get_post_tags ( $post->ID );
							if ($tags) {
								$first_tag = $tags [0]->term_id;
								$args = array (
										'tag__in' => array (
												$first_tag 
										),
										'post__not_in' => array (
												$post->ID 
										),
										'post_type' => array (
												'post',
												'page' 
										),
										'posts_per_page' => 4,
										'ignore_sticky_posts' => 1 
								);
								$related_query = new WP_Query ( $args );
								if ($related_query->have_posts ()) {
									while ( $related_query->have_posts () ) :
										$related_query->the_post ();
										
										$pid = $post->ID;
										$thumbId = get_post_thumbnail_id ( $pid );
										$iurl = wp_get_attachment_url ( $thumbId );
										?>
									<li><a class="contentLink" href="<?php the_permalink() ?>">
										<img class="contentThumb" src="<?php echo $iurl;?>" alt="<?php the_title(); ?>">
										<?php the_title(); ?>
									</a> <span class="contentBrief"><?php echo custom_excerpt(10) ?></span></li>
									
							<?php
									endwhile
									;
								}
								wp_reset_query ();
							}
							?>
								</ul>
						</div>
						<!-- /.sideFindRelatedContent -->
						<?php if($quote!=""&&$qAuthor!="") : ?>
						<div class="sideQuote">
							<p class="quoteTxt"><?php echo $quote;?></p>
							<p class="quoterName"><?php echo $qAuthor;?></p>
						</div>
						<!-- /.sideQuote -->
						<?php endif;?>
						<div class="sideMostRecentChallenges">
							<h3>Most Recent Challenges</h3>
							<?php 
								$contest= get_active_contests('develop','30000000');								
							?>
							<ul>									
								<li><a class="contestName contestType1" href="<?php bloginfo('wpurl');?>/challenges/<?php echo $contest->contestId?>">
										<i></i><?php echo $contest->contestName ?>
									</a></li>
								<li class="alt"><a class="contestName contestType2" href="<?php bloginfo('wpurl');?>/challenges/<?php echo $contest->contestId?>">
										<i></i><?php echo $contest->contestName ?>
									</a></li>
								<li><a class="contestName contestType3" href="<?php bloginfo('wpurl');?>/challenges/<?php echo $contest->contestId?>">
										<i></i><?php echo $contest->contestName ?>
									</a></li>
							</ul>
						</div>
						<!-- /.sideMostRecentChallenges -->						
					</aside>
					<!-- /.sideStream -->
					<div class="clear"></div>
				</div>
				<!-- /.rightSplit -->
			</div>
		</article>
		<!-- /#mainContent -->
<?php get_footer(); ?>
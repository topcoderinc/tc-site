<?php
/*
Template Name: Password Recovery
*/
get_header ();

$values 	= get_post_custom ( $post->ID );
$userkey 	= get_option ( 'api_user_key' );

$changePasswordPage = get_page_link_by_slug("password-reset");
$redirectDelay 		= get_option("tcPasswordRecoveryRedirectDelay")==null ? 3000 : get_option("tcPasswordRecoveryRedirectDelay");

$handle 	= $_POST["handle"];
$msg 		= null;
$redirect 	= false;
$tokenObj 	= null;
if(trim($handle)!='') {
	$response = generateResetToken($handle);
	
	$obj = json_decode($response['body']);
    if ($response['response']['code']== 200) {
		$tokenObj = $obj;
	} else {
		$msg = $obj->error->details;
	}
	
}

if($tokenObj!=null) {
	if ( $tokenObj->successful ) {
		$changePasswordLink = $changePasswordPage;
		$msg = "Sit tight! A confirmation code is on its way to your email.";
		$redirect = true;		
	} elseif( isset($tokenObj->socialProvider) ) {
		$msg = "It looks like you have a social account associated with your profile. Please login using your social provider.";
	}
}
/**
 * Redirect to reset password page 
 */ 
	if($redirect) :
?>
    <script type="text/javascript">
		$(document).ready(
			function () {
				setTimeout(function() {
					window.location.href = '<?php echo $changePasswordLink;?>';
				}, <?php echo $redirectDelay;?>);
			}
		);
	</script>
<?php endif; ?>

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
								<form id="formResetPassword" class="formResetPassword" name="formResetPassword" action="" method="POST">
									<div class="row">
										<input type="text" class="handleOrEmail" maxlength="40" name="handle" placeholder="Enter your handle or email:" size="50" />
										<span class="err1 error" style="display: none;">Required field</span>
									</div>
									<div class="row">
										<input type="button" class="btnSubmit" value="Submit" />
									</div>
								</form>
                <br />
                <p>Or <a href="/password-reset">click here</a> if you already have a confirmation code.</p>
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
						
						<?php get_template_part("content", "recent-challenges"); ?>
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

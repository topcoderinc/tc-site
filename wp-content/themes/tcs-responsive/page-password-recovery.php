<?php
/*
Template Name: Password Recovery
*/
get_header ();

$values 	= get_post_custom ( $post->ID );
$userkey 	= get_option ( 'api_user_key' );

$changePasswordPage = get_page_link_by_slug("reset-password");
/*
$emailSubject 		= get_option("tcPasswordRecoverySubject")==null ? "Topcoder Password Recovery" : get_option("tcPasswordRecoverySubject"); 
$emailMessage 		= get_option("tcPasswordRecoveryContent")==null ? $recoveryPasswordMessage : get_option("tcPasswordRecoveryContent");
*/
$redirectDelay 		= get_option("tcPasswordRecoveryRedirectDelay")==null ? 3000 : get_option("tcPasswordRecoveryRedirectDelay");

$handle 	= $_POST["handle"];
$msg 		= null;
$redirect 	= false;
$tokenObj 	= null;
if(trim($handle)!='') {
	$tokenObj = generateResetToken($handle);
}

if($tokenObj!=null) {
	if(property_exists($tokenObj, 'socialProvider')) {
		$msg = "You have registered using social login. Please login using ".$takenObj->socialProvider." service";
	}
	else if(property_exists($tokenObj, 'token')) {
		
		$changePasswordLink = $changePasswordPage."?unlock_code=".$tokenObj->token;
		
		/*		
		$emailMessage = str_replace("varHandle", $handle, $emailMessage);
		$emailMessage = str_replace("varCode", $tokenObj->token, $emailMessage);
		$emailMessage = str_replace("varChangePasswordPage", $changePasswordPage, $emailMessage);
		$emailMessage = str_replace("varChangePasswordLink", $changePasswordLink, $emailMessage);
		$emailMessage = str_replace("varThemeUrl", THEME_URL, $emailMessage);
		
		$body = $emailMessage;

		$mail = getMailObject();
		$username = get_option("tcEmailUsername")==null ? "topcoderprojectmailtest@gmail.com" : get_option("tcEmailUsername");
		$emailForm = get_option("tcEmailForm")==null ? "Topcoder" : get_option("tcEmailForm");
		
		$mail->SetFrom($username,$emailForm);
		$mail->Subject = $emailSubject;
		$body = str_replace("\\", "", $body);
		$mail->Body = $body;
		if(filter_var($handle, FILTER_VALIDATE_EMAIL)) {
			$toAddress = $handle;
		}
		else {
			$userEmail = getUserEmail($handle);
			$toAddress = $userEmail;
		}

		$arrToAddress = explode(",", $toAddress);
		if($arrToAddress!=null) 
		foreach($arrToAddress as $value) {
			$mail->AddAddress(trim($value));
		}
		
		try {
			if($mail->Send()) {
				$msg = "Sit tight we have emailed you a confirmation code";
				$redirect = true;
			}
		}catch(Exception $e) {
			print_r($e);
		}
		*/
		
		
		$msg = "Sit tight we have emailed you a confirmation code";
		$redirect = true;
		
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
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

					<!-- /.sideStream -->
					<div class="clear"></div>
				</div>
				<!-- /.rightSplit -->
			</div>
		</article>
		<!-- /#mainContent -->
<?php get_footer(); ?>
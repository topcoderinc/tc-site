<?php get_header();

global $activity;
$activity = get_activity_summary ();

$handle = get_query_var ( 'handle' );

$coder = get_raw_coder ( $handle, '' );

# if handle not found redirect to 404 - member not found (Dan.V - I-104783)
if (!isset($coder->handle) ) {
  wp_redirect(site_url('404.php'));
  exit;
}

$tab = get_query_var ( 'tab' );

$track = "data/srm";

if ($tab == "algo") {
	$track = "data/srm";
} else if ($tab == "develop") {
	$track = "develop";
} else if ($tab == "design") {
	$track = "design";
}
$ct = get_query_var ( 'ct' );
if ($ct == 'marathon') {
	$track = "data/marathon";
}

echo "<script>
		var basicCoderData=".json_encode($coder).";
		var tab='".$tab."';
		var currTab='".$ct."';
	</script>";

$userkey = get_option ( 'api_user_key' );
// rint_r($coder);

?>
<script type="text/javascript">
<!--
var activeTrack = "<?php echo $track;?>";
 $(document).ready(function(){
	coder.initMemberEvents();
});
//-->
</script>


<?php

if ($coder->photoLink != '')
	$photoLink = 'http://community.topcoder.com' . $coder->photoLink;
else
	$photoLink = THEME_URL . '/i/default-photo.png';

$quote = ($coder->quote == '') ? "Member of the world's largest global competitive community. " : $coder->quote;

?>
<div class="content">
	<div id="main" class="coderProfile">
		<div id="hero">
			<div class="inner">
				<div class="container">
					<article class="aboutCoder">
						<div class="details">
							<figure class="coderPicWrap">
								<img alt="<?php echo $coder->handle;?>" src="<?php echo $photoLink;?>">
							</figure>
							<div class="info">
								<div class="handle">
									<a href="#"><?php echo $coder->handle;?></a>
								</div>
								<div class="country"><?php echo $coder->country; ?></div>
								<div class="memberSince">
									<label>Member Since:</label>
									<div class="val"><?php
									$memSince = $coder->memberSince;
									echo date ( "M d, Y", strtotime ( $memSince ) );
									?></div>
								</div>
								<?php if (isset($coder->overallEarning)) :?>
								<div class="memberSince">
									<label>Total Earnings :</label>
									<div class="val"><?php echo '$'.$coder->overallEarning;?></div>
								</div>
								<?php endif;?>
							</div>
						</div>
						<blockquote class="coderQuote">“<?php echo $quote;?>”</blockquote>
					</article>
					<!-- /.aboutCoder -->
				</div>
			</div>
		</div>
		<!-- /#hero -->

		<article id="mainContent" class="noShadow">
			<article class="coderRatings">
				<div class="container">
					<div class="actions track<?php echo $tab;?>">
						<ul class="trackSwitch switchBtns">
							<li class="first"><a href="./?tab=design" class="<?php if($tab == "design"){ echo "isActive";}?>">Design</a></li>
							<li><a href="./?tab=develop" class="<?php if($tab == "develop"){ echo "isActive";}?>">Develop</a></li>
							<li class="last"><a href="./?tab=algo" class="<?php if($tab == "algo" || $tab == "marathon" || $tab == null || $tab == ""){ echo "isActive";}?>">Data Science</a></li>
						</ul>
						<!-- /.trackSwitch -->
						<ul class="viewSwitch switchBtns">
							<li class="graphView first"><a id="graphButton" href="#graphView"></a></li>
							<li class="tabularView last"><a id="tableButton" href="#tabularView" class="isActive"></a></li>
						</ul>
					</div>
					<!-- /.actions -->
					<div class="dataTabs" id="profileInfo">
						<script type="text/javascript">
							<!--
							var activeTrack = "<?php echo $track;?>";
							 $(document).ready(function(){
								app.ajaxProfileRequest();
							});
							//-->
						</script>
						<div class="loadingPlaceholder"></div>
					</div>
					<!-- /.dataTabs -->
				</div>
				<div class="clear"></div>
				<div class="forumWrap hide">
						<?php // get_template_part('content', 'forum');?>
				</div>
				<!-- /.forumWrap -->
			</article>
			<!-- /.coderRatings -->
		</article>
		<!-- /#mainContent -->
<?php get_footer(); ?>

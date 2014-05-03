<?php
// coder info
$track = "data/srm";
if ($tab == "algo") {
	$track = "data/srm";
} else if ($tab == "develop") {
	$track = "develop";
} else if ($tab == "design") {
	$track = "design";
}
global $coder;
$coder = get_member_statistics ( $handle, $track );
$WebDesign = $coder->Tracks->WebDesign;
$recentWins = get_stat_design_recentwins($handle)->recentWinningSubmissions;
#print_r($recentWins);
$img_locked = get_bloginfo( 'stylesheet_directory' )."/i/img-locked.png";
?>



<div id="develop" class="tab algoLayout designLayout">
	<div class="ratingInfo">
		<div class="submissonInfo">
			<figure class="submissionThumb">
				<!-- <img alt="" src="<?php // echo $WebDesign->recentWinningSubmission; ?>">  -->
				<img alt="" src="<?php echo ( $recentWins[0]->viewable == false ? $img_locked: $recentWins[0]->preview);?>" />
				
			</figure>
			<div class="rwsDetails">
				<header class="head">
					<h4>Recent Winning Submission</h4>
				</header>
				<div class="winInfo">
					<a href="<?php echo get_bloginfo('siteurl') . "/challenge-details/" . $recentWins[0]->challengeId . "/?type=design" ; ?>" class="contestTitle">
						<i></i><?php echo $recentWins[0]->contestName;?>
					</a>
					<div id="badgeImg" class="badgeImg rank<?php echo $recentWins[0]->rank;?>"></div>
					<div class="prizeAmount">
						<span class="val"><i></i>$<?php echo $recentWins[0]->prize;?></span>
					</div>
					<div class="submittedOn">
						Submitted on: <span class="time"><?php echo date("M d, Y H:i",strtotime($recentWins[0]->submissionDate)) . " EST";?></span>
					</div>
				</div>
			</div>
			<!-- /.rwsDetails -->
			<div class="submissionCarousel">
				<div class="carouselWrap">
					<div class="slider">
						<?php
						$fullImages = "";
						foreach ( $recentWins as $r ):
						// define full image source by replacing small to full
						$fullImages .= '<img alt="" src="'.str_replace("small","full",( $r->viewable == false ? $img_locked: $r->preview)).'" />';
								
						?>						
						
						<div class="slide">
							<figure>
								<img alt="" src="<?php echo ( $r->viewable == false ? $img_locked: $r->preview);?>" /> 
							</figure>
							<div class="hide comptetionData">
								<input class="name" type="hidden" value="<?php echo $r->contestName;?>" />
								<input class="prize" type="hidden" value="$<?php echo number_format($r->prize);?>" />
								<input class="rank" type="hidden" value="<?php echo $r->rank;?>" />
								<input class="submissionDate" type="hidden" value="<?php echo date("M d, Y H:i",strtotime($r->submissionDate)) . " EST";?>" />
							</div>
						</div>
						
						<?php
						endforeach;
						?>						
					</div>
				</div>
				<!--- preloaded images -->
				<div class="hide">
				<?php echo $fullImages; ?>				
				</div>
			</div>
			<!-- /.submissionCarousel -->
		</div>
		<!-- /.submissonInfo -->

	</div>
	<!-- /.ratingInfo -->
	<aside class="badges">
		<header class="head">
			<h4>Badges</h4>
		</header>
		<?php get_template_part('content', 'badges');?>		
	</aside>
	<!-- /.badges -->
</div>
<!-- /.algoLayout -->
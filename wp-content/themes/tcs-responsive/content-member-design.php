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

?>



<div id="develop" class="tab algoLayout designLayout">
	<div class="ratingInfo">
		<div class="submissonInfo">
			<figure class="submissionThumb">
				<!-- <img alt="" src="<?php // echo $WebDesign->recentWinningSubmission; ?>">  -->
				<img alt="" src="<?php bloginfo( 'stylesheet_directory' ); ?>/i/desing-sub.png" />
				
			</figure>
			<div class="rwsDetails">
				<header class="head">
					<h4>Recent Winning Submission</h4>
				</header>
				<div class="winInfo">
					<a href="#" class="contestTitle">
						<i></i>TC - CS Storyboard Redesign Lorem Ipsum Dolor sit Amet 2
					</a>
					<div class="badgeImg"></div>
					<div class="prizeAmount">
						<span class="val"><i></i>$1200.00</span>
					</div>
					<div class="submittedOn">
						Submitted on: <span class="time">10.31.2013 at 07:58 EST</span>
					</div>
				</div>
			</div>
			<!-- /.rwsDetails -->
			<div class="submissionCarousel">
				<div class="carouselWrap">
					<div class="slider">
						<div class="slide">
							<figure>
								<img alt="" src="<?php bloginfo( 'stylesheet_directory' ); ?>/i/desing-sub.png" />
							</figure>
							<div class="hide comptetionData">
								<input class="name" type="hidden" value="TC - CS Storyboard Redesign Lorem Ipsum Dolor sit Amet 2" />
								<input class="prize" type="hidden" value="$1300.00" />
								<input class="submiissionDate" type="hidden" value="10.31.2013 at 07:58 EST" />
							</div>
						</div>
						<div class="slide">
							<figure>
								<img alt="" src="<?php bloginfo( 'stylesheet_directory' ); ?>/i/img-locked.png" />
							</figure>
							<div class="hide comptetionData">
								<input class="name" type="hidden" value="TC - CS Storyboard Redesign Lorem Ipsum Dolor sit Amet 2" />
								<input class="prize" type="hidden" value="$1300.00" />
								<input class="submiissionDate" type="hidden" value="10.31.2013 at 07:58 EST" />
							</div>
						</div>
						<div class="slide">
							<figure>
								<img alt="" src="<?php bloginfo( 'stylesheet_directory' ); ?>/i/car-img.png" />
							</figure>
							<div class="hide comptetionData">
								<input class="name" type="hidden" value="TC - CS Storyboard Redesign Lorem Ipsum " />
								<input class="prize" type="hidden" value="$1200.00" />
								<input class="submiissionDate" type="hidden" value="10.30.2013 at 07:58 EST" />
							</div>
						</div>
						<div class="slide">
							<figure>
								<img alt="" src="<?php bloginfo( 'stylesheet_directory' ); ?>/i/car-img.png" />
							</figure>
							<div class="hide comptetionData">
								<input class="name" type="hidden" value="TC - CS Lorem Ipsum Dolor sit Amet 2" />
								<input class="prize" type="hidden" value="$1300.00" />
								<input class="submiissionDate" type="hidden" value="10.22.2013 at 07:58 EST" />
							</div>
						</div>
						<div class="slide">
							<figure>
								<img alt="" src="<?php bloginfo( 'stylesheet_directory' ); ?>/i/img-locked.png" />
							</figure>
							<div class="hide comptetionData">
								<input class="name" type="hidden" value="TC - CS Storyboard Redesign Lorem Ipsum Dolor sit Amet 2" />
								<input class="prize" type="hidden" value="$1300.00" />
								<input class="submiissionDate" type="hidden" value="10.31.2013 at 07:58 EST" />
							</div>
						</div>
						<div class="slide">
							<figure>
								<img alt="" src="<?php bloginfo( 'stylesheet_directory' ); ?>/i/img-locked.png" />
							</figure>
							<div class="hide comptetionData">
								<input class="name" type="hidden" value="TC - CS Storyboard Redesign Lorem Ipsum Dolor sit Amet 2" />
								<input class="prize" type="hidden" value="$1300.00" />
								<input class="submiissionDate" type="hidden" value="10.31.2013 at 07:58 EST" />
							</div>
						</div>
						<div class="slide">
							<figure>
								<img alt="" src="<?php bloginfo( 'stylesheet_directory' ); ?>/i/car-img.png" />
							</figure>
							<div class="hide comptetionData">
								<input class="name" type="hidden" value="TC  Redesign Lorem Ipsum Dolor sit Amet 2" />
								<input class="prize" type="hidden" value="$2000.00" />
								<input class="submiissionDate" type="hidden" value="09.31.2013 at 07:58 EST" />
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- /.submissionCarousel -->
		</div>
		<!-- /.submissonInfo -->

	</div>
	<!-- /.ratingInfo -->
<!-- 	<aside class="badges">
		<header class="head">
			<h4>Badges</h4>
		</header>
		<?php get_template_part('content', 'badges');?>		
	</aside>
 -->	<!-- /.badges -->
</div>
<!-- /.algoLayout -->
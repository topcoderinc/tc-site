<?php
// coder info
// stats url: v2/develop/statistics/{handle}/{challengeType}
$activeTrack = 'Development';
global $track;
global $coder;
global $dev;
global $ct;
if(empty($ct)){
	$ct = $_POST['ct'];
}
$handle = $_POST['handle'];
$track = $_POST['track'];
$coder = get_member_statistics ( $handle, $track);


$tracks = $coder->Tracks;
$competitionHistory = $coder->CompetitionHistory;

if ($track == "develop") {
	$activeTrack = 'Development';
	$currentChallengetype = 'Development';
}

// chart
include_once TEMPLATEPATH . '/chart/Highchart.php';

// line chart
$chart = new Highchart ();
$challengetypes = array ();
$challengetypes = get_all_contest ();

array_push ( $challengetypes, 'Design' );
array_push ( $challengetypes, 'Assembly Competition' );
array_push ( $challengetypes, 'Development' );
array_push ( $challengetypes, 'Specification' );
array_push ( $challengetypes, 'Bug Hunt' );
array_push ( $challengetypes, 'UI Prototype Competition' );
array_push ( $challengetypes, 'UI Prototypes' );
?>

<div id="develop" class="tab algoLayout">
	<div class="ratingInfo">

		<div class="subTrackTabs">
		<?php echo "<script>
		var coderData=".json_encode($coder).";
		</script>";?>
			<nav class="tabNav">
				<ul>
						<?php
						foreach ( $challengetypes as &$challengetype ) {

								$underscoredChallengeType = str_replace ( ' ', '_', $challengetype );
								$underscoredChallengeType = strtolower ( $underscoredChallengeType );

							if (! empty ( $tracks->{$challengetype} ) && ! empty ( $tracks->{$challengetype}->rating )) {
								$currentChallengetype = $challengetype;

								if ($underscoredChallengeType == 'ui_prototype_competition') {
									$underscoredChallengeType = 'ui_prototypes';
									$currentChallengetype = $underscoredChallengeType;
								}
								if ($underscoredChallengeType == 'ria_build_competition') {
									$underscoredChallengeType = 'ria_build';
									$currentChallengetype = $underscoredChallengeType;
								}

								$class = ($underscoredChallengeType == $currentunderscoredChallengeType) ? 'isActive' : '';
								echo '<li><a class="' . $class . '" href="' . $underscoredChallengeType . '">' . $challengetype . '</a></li>';
							}
						}
						if (! empty ( $tracks->{$currentChallengetype} )) {
							$dev = $tracks->{$currentChallengetype};
							$devHistory = $competitionHistory->{$currentChallengetype};
						} else {
							echo "<h3>Develop</h3>";
						}
						?>
				</ul>
			</nav>
			<?php  if(empty($tracks->{$currentChallengetype})):?>
			<header class="head">
				<h3 class="nocontestStatus text">Member rating unavailable or member didn't participated in any Develop contest.</h3>
			</header>
			<?php else:?>
			
			<header class="head">
				<div class="trackNRating">
					<h4 class="trackName"><?php echo $currentChallengetype;
								$underscoredCurrentChallengeType = str_replace ( ' ', '_', $currentChallengetype );
								$underscoredCurrentChallengeType = strtolower ( $underscoredCurrentChallengeType );?>
					</h4>
					<div class="rating <?php echo do_shortcode("[tc_rating_color score='".$dev->rating."' ]") ?>"><?php echo $dev->rating;?></div>
					<div class="lbl">Rating</div>
					<div class="lbl">
					</div>
				</div>



				<div class="detailedRating">
					<div class="row fieldPercentile">
						<label>Percentile:</label>
						<div class="val"><?php echo $dev->overallPercentile;?></div>
						<input type="hidden" class="fieldId" value="overallPercentile">
					</div>
					<div class="row fieldVolatility">
						<label>Volatility:</label>
						<div class="val"><?php echo $dev->volatility;?></div>
						<input type="hidden" class="fieldId" value="volatility">
					</div>
					<div class="row fieldRank">
						<label>Rank:</label>
						<div class="val"><?php echo $dev->activeRank;?></div>
						<input type="hidden" class="fieldId" value="activeRank">
					</div>
					<div class="row fieldCtryRank">
						<label>Country Rank:</label>
						<div class="val"><?php echo $dev->overallCountryRank;?></div>
						<input type="hidden" class="fieldId" value="overallCountryRank">
					</div>
					<div class="row fieldScRank">
						<label>School Rank:</label>
						<div class="val"><?php echo $dev->activeSchoolRank;?></div>
						<input type="hidden" class="fieldId" value="activeSchoolRank">
					</div>
					<div class="row fieldCompetitions">
						<label>Competitions:</label>
						<div class="val">
							<a href="#"><?php echo $dev->competitions;?></a>
						</div>
						<input type="hidden" class="fieldId" value="competitions">
					</div>
					<div class="row fieldMaxRating">
						<label>Maximum Rating: </label>
						<div class="val"><?php echo $dev->maximumRating;?></div>
						<input type="hidden" class="fieldId" value="maximumRating">
					</div>
					<div class="row fieldMinRating">
						<label>Minimum Rating: </label>
						<div class="val"><?php echo $dev->minimumRating;?></div>
						<input type="hidden" class="fieldId" value="minimumRating">
					</div>
					<div class="row fieldRevRating">
						<label>Reviewer Rating: </label>
						<div class="val">
							<a href="#"><?php if( !empty($dev->reviewerRating)){echo number_format( $dev->reviewerRating, 2, '.', '');}else{echo "N/A";}?></a>
						</div>
						<input type="hidden" class="fieldId" value="reviewerRating">
					</div>
					<div class="row fieldRevRating">
						<label>Reliability: </label>
						<div class="val">
							<a href="#"><?php echo ($dev->reliability*100) ."%";?></a>
						</div>
						<input type="hidden" class="fieldId" value="reliability">
					</div>
					<div class="clear"></div>
				</div>
			</header>
			<div class="ratingViews">
				<div id="graphView" class="hide">
					<div class="subTrackTabs">
						<div class="srm subTrackTab">
							<div class="chartWrap">
								<div class="chartTypeSwitcher">
									<a class="btn btnHistory isActive">Rating History</a> <a class="btn btnDist">Rating Distribution</a>
								</div>
							<?php echo apply_filters('the_content','[tc_ratings_chart_dev handle="'.$handle.'"  challengetype="'.$underscoredCurrentChallengeType.'"]');?>
							
						</div>
						</div>
					</div>
					<!-- /.subTrackTabs -->
				</div>
				<!-- /#graphView -->
				<div id="tabularView">
					<div class="srm subTrackTab">
						<div class="tableView">
							<article class="mainTabStream">
								<div class="tableWrap">
									<table class="ratingTable">
										<caption>Submissions</caption>
										<thead>
											<tr>
												<th class="colDetails">Details</th>
												<th class="colTotal">Total</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td class="colDetails">Inquiries</td>
												<td class="colTotal"><span><?php echo ($devHistory->inquiries) ? $devHistory->inquiries : 'n/a'; ?></span>
													<input type="hidden" class="valId" value="inquiries" />
												</td>
											</tr>
											<tr class="alt">
												<td class="colDetails">Submissions</td>
												<td class="colTotal"><span><?php echo ($devHistory->submissions) ? $devHistory->submissions : 'n/a'; ?></span>
													<input type="hidden" class="valId" value="submissions" /></td>
											</tr>

											<tr>
												<td class="colDetails">Submission Rate</td>
												<td class="colTotal"><span><?php echo ($devHistory->submissionRate) ? $devHistory->submissionRate : 'n/a'; ?></span>
													<input type="hidden" class="valId" value="submissionRate" /></td>
											</tr>
											<tr class="alt">
												<td class="colDetails">Passed Screening</td>
												<td class="colTotal"><span><?php echo ($devHistory->passedScreening) ? $devHistory->passedScreening : 'n/a'; ?></span>
													<input type="hidden" class="valId" value="passedScreening" /></td>
											</tr>
											<tr>
												<td class="colDetails">Screening Success Rate</td>
												<td class="colTotal"><span><?php echo ($devHistory->screeningSuccessRate) ? $devHistory->screeningSuccessRate : 'n/a'; ?></span>
													<input type="hidden" class="valId" value="screeningSuccessRate" /></td>
											</tr>
											<tr class="alt">
												<td class="colDetails">Passed Review</td>
												<td class="colTotal"><span><?php echo ($devHistory->passedReview) ? $devHistory->passedReview : 'n/a'; ?></span>
													<input type="hidden" class="valId" value="passedReview" /></td>
											</tr>
											<tr>
												<td class="colDetails">Review Success Rate</td>
												<td class="colTotal"><span><?php echo ($devHistory->reviewSuccessRate) ? $devHistory->reviewSuccessRate : 'n/a'; ?></span>
													<input type="hidden" class="valId" value="reviewSuccessRate" /></td>
											</tr>
											<tr class="alt">
												<td class="colDetails">Maximum Score</td>
												<td class="colTotal"><span><?php echo ($devHistory->maximumScore) ? $devHistory->maximumScore : 'n/a'; ?></span>
													<input type="hidden" class="valId" value="maximumScore" /></td>
											</tr>
											<tr>
												<td class="colDetails">Minimum Score</td>
												<td class="colTotal"><span><?php echo ($devHistory->minimumScore) ? $devHistory->minimumScore : 'n/a'; ?></span>
													<input type="hidden" class="valId" value="minimumScore" /></td>
											</tr>
											<tr class="alt">
												<td class="colDetails">Appeals</td>
												<td class="colTotal"><span><?php echo ($devHistory->appeals) ? $devHistory->appeals : 'n/a'; ?></span>
													<input type="hidden" class="valId" value="appeals" /></td>
											</tr>
											<tr>
												<td class="colDetails">Appeal Success Rate</td>
												<td class="colTotal"><span><?php echo ($devHistory->appealSuccessRate) ? $devHistory->appealSuccessRate : 'n/a'; ?></span>
													<input type="hidden" class="valId" value="appealSuccessRate" /></td>
											</tr>
											<tr class="alt">
												<td class="colDetails">Average Score</td>
												<td class="colTotal"><span><?php echo ($devHistory->averageScore) ? $devHistory->averageScore : 'n/a'; ?></span>
													<input type="hidden" class="valId" value="averageScore" /></td>
											</tr>
											<tr>
												<td class="colDetails">Average Placement</td>
												<td class="colTotal"><span><?php echo ($devHistory->averagePlacement) ? $devHistory->averagePlacement : 'n/a'; ?></span>
													<input type="hidden" class="valId" value="averagePlacement" /></td>
											</tr>
											<tr class="alt">
												<td class="colDetails">Wins</td>
												<td class="colTotal"><span><?php echo ($devHistory->wins) ? $devHistory->wins : 'n/a'; ?></span>
													<input type="hidden" class="valId" value="wins" /></td>
											</tr>
											<tr>
												<td class="colDetails">Win Percentage</td>
												<td class="colTotal"><span><?php echo ($devHistory->winPercentage) ? $devHistory->winPercentage : 'n/a'; ?></span>
													<input type="hidden" class="valId" value="winPercentage" /></td>
											</tr>

										</tbody>
									</table>
								</div>
							</article>
							<!-- /.mainTabStream -->
						</div>
						<!-- /.tableView -->
					</div>
				</div>
				<!-- /.subTrackTabs -->
			</div>
			<!-- /#tabularView -->
			<?php endif;?>
		
		</div>
		<!-- /.ratingViews -->
	</div>
	<!-- /.ratingInfo -->
	<?php if($_POST['renderBadges']==="true"):?>
		<aside class="badges">
			<header class="head">
				<h4>Badges</h4>
			</header>
			<?php
				get_template_part('content', 'badges');
			?>
		</aside>
		<!-- /.badges -->
	<?php endif;?>
</div>
<!-- /.algoLayout -->
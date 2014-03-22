<?php
// coder info
// stats url: v2/develop/statistics/{handle}/{challengeType}
$activeTrack = 'Development';
global $track;
global $coder;
global $dev;
global $ct;
$coder = get_member_statistics ( $handle, $track );

echo "<script>
		var coderData=".json_encode($coder).";
		</script>";

$tracks = $coder->Tracks;
if ($track == "develop") {
	$activeTrack = 'Development';
	$currentChallengetype = 'Development'; 
}
if(!empty($ct)){
	$currentChallengeId = $ct;
}

// chart
include_once TEMPLATEPATH . '/chart/Highchart.php';

// line chart
$chart = new Highchart ();
$chart->printScripts ();
$challengetypes = array ();
$challengetypes = get_all_contest ();

array_push ( $challengetypes, 'Design' );
array_push ( $challengetypes, 'Assembly Competition' );
array_push ( $challengetypes, 'Development' );
array_push ( $challengetypes, 'Specification' );
array_push ( $challengetypes, 'Bug Hunt' );
array_push ( $challengetypes, 'UI Prototype Competition' );
?>

<div id="develop" class="tab algoLayout">
	<div class="ratingInfo">

		<div class="subTrackTabs">
			<nav class="tabNav">
				<table>
					<thead class="tabNavHead">
						<tr>
						<?php
						foreach ( $challengetypes as &$challengetype ) {
							if (! empty ( $tracks->{$challengetype} ) && ! empty ( $tracks->{$challengetype}->rating )) {
								$challengeName = $challengetype;
								$challengeID = str_replace ( ' ', '_', $challengetype );
								$challengeID = strtolower ( $challengeID );
								
								if ($challengeID == 'ui_prototype_competition') {
									$challengeID = 'ui_prototypes';
									$currentChallengetype = $challengeID;
								}
								
								if($currentChallengeId == $challengeID){
									$currentChallengetype = $challengetype;
								}
								
								$class = ($challengeID == $currentChallengeId) ? 'isActive' : '';
								echo '<th><a class="' . $class . '" href="' . $challengeID . '">' . $challengetype . '</a></th>';
							}
						}
						if (! empty ( $tracks->{$currentChallengetype} )) {
							$dev = $tracks->{$currentChallengetype};
						} else {
							echo "<h3>Develop</h3>";
						}
						?>
						</tr>
					</thead>
				</table>
			</nav>
			<?php  if(empty($tracks->{$currentChallengetype})):?>
			<header class="head">
				<h3 class="nocontestStatus text">Member rating unavailable or member didn't participated in any Develop contest.</h3>
			</header>
			<?php else:?>
			
			<header class="head">
				<div class="trackNRating">
					<h4 class="trackName"><?php echo $currentChallengetype;?></h4>
					<div class="rating <?php echo do_shortcode("[tc_rating_color score='".$dev->rating."' ]") ?>"><?php echo $dev->rating;?></div>
					<div class="lbl">Rating</div>
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
							<a href="#"><?php echo $dev->reliability;?></a>
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
							<?php echo apply_filters('the_content','[tc_ratings_chart_dev handle="'.$handle.'"  challengetype="'.$currentChallengetype.'"]');?>
							
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
												<td class="colTotal"><span><?php echo $dev->inquiries; ?></span>
													<input type="hidden" class="valId" value="inquiries" />
												</td>
											</tr>
											<tr class="alt">
												<td class="colDetails">Submissions</td>
												<td class="colTotal"><span><?php echo $dev->submissions; ?></span>
													<input type="hidden" class="valId" value="submissions" /></td>
											</tr>

											<tr>
												<td class="colDetails">Submission Rate</td>
												<td class="colTotal"><span><?php echo $dev->submissionRate; ?></span>
													<input type="hidden" class="valId" value="submissionRate" /></td>
											</tr>
											<tr class="alt">
												<td class="colDetails">Passed Screening</td>
												<td class="colTotal"><span><?php echo $dev->passedScreening; ?></span>
													<input type="hidden" class="valId" value="passedScreening" /></td>
											</tr>
											<tr>
												<td class="colDetails">Screening Success Rate</td>
												<td class="colTotal"><span><?php echo $dev->screeningSuccessRate; ?></span>
													<input type="hidden" class="valId" value="screeningSuccessRate" /></td>
											</tr>
											<tr class="alt">
												<td class="colDetails">Passed Review</td>
												<td class="colTotal"><span><?php echo $dev->passedReview; ?></span>
													<input type="hidden" class="valId" value="passedReview" /></td>
											</tr>
											<tr>
												<td class="colDetails">Review Success Rate</td>
												<td class="colTotal"><span><?php echo $dev->reviewSuccessRate; ?></span>
													<input type="hidden" class="valId" value="reviewSuccessRate" /></td>
											</tr>
											<tr class="alt">
												<td class="colDetails">Maximum Score</td>
												<td class="colTotal"><span><?php echo $dev->maximumScore; ?></span>
													<input type="hidden" class="valId" value="maximumScore" /></td>
											</tr>
											<tr>
												<td class="colDetails">Minimum Score</td>
												<td class="colTotal"><span><?php echo $dev->minimumScore; ?></span>
													<input type="hidden" class="valId" value="minimumScore" /></td>
											</tr>
											<tr class="alt">
												<td class="colDetails">Appeals</td>
												<td class="colTotal"><span><?php echo $dev->appeals; ?></span>
													<input type="hidden" class="valId" value="appeals" /></td>
											</tr>
											<tr>
												<td class="colDetails">Appeal Success Rate</td>
												<td class="colTotal"><span><?php echo $dev->appealSuccessRate; ?></span>
													<input type="hidden" class="valId" value="appealSuccessRate" /></td>
											</tr>
											<tr class="alt">
												<td class="colDetails">Average Score</td>
												<td class="colTotal"><span><?php echo $dev->averageScore; ?></span>
													<input type="hidden" class="valId" value="averageScore" /></td>
											</tr>
											<tr>
												<td class="colDetails">Average Placement</td>
												<td class="colTotal"><span><?php echo $dev->averagePlacement; ?></span>
													<input type="hidden" class="valId" value="averagePlacement" /></td>
											</tr>
											<tr class="alt">
												<td class="colDetails">Wins</td>
												<td class="colTotal"><span><?php echo $dev->wins; ?></span>
													<input type="hidden" class="valId" value="wins" /></td>
											</tr>
											<tr>
												<td class="colDetails">Win Percentage</td>
												<td class="colTotal"><span><?php echo $dev->winPercentage; ?></span>
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
	<aside class="badges">
		<header class="head">
			<h4>Badges Cabinet</h4>
		</header>
		<?php get_template_part('content', 'badges');?>		
	</aside>
	<!-- /.badges -->
</div>
<!-- /.algoLayout -->
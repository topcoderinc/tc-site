<?php
// coder info
global $track;
global $coder;
$coder = get_member_statistics ( $handle, $track );
$rating = $coder->rating;
if($rating>0){
	// chart
	include_once TEMPLATEPATH . '/chart/Highchart.php';
	
	// add chart script chart
	$chart = new Highchart ();
	$chart->printScripts ();
}

?>



<div id="marathon" class="tab algoLayout">
	<div class="ratingInfo">
		<div class="subTrackTabs">
			<nav class="tabNav">
				<table>
					<colgroup>
						<col width="50%" />
						<col width="50%" />
					</colgroup>
					<thead class="tabNavHead">
						<tr>
							<th><a href="?tab=algo" class="link">Algorithm</a></th>
							<th><a href="?tab=algo&ct=marathon" class="isActive link">Marathon</a></th>
						</tr>
					</thead>
				</table>
			</nav>
		</div>
		<?php if($rating>0):?>
		<header class="head">
			<div class="trackNRating">
				<h4 class="trackName">Data Science Competitions</h4>
				<div class="rating <?php echo do_shortcode("[tc_rating_color score='".$coder->rating."' ]") ?>"><?php echo $coder->rating;?></div>
				<div class="lbl">Rating</div>
			</div>
			<div class="detailedRating">
				<div class="row">
					<label>Percentile:</label>
					<div class="val"><?php echo $coder->percentile;?></div>
				</div>
				<div class="row">
					<label>Volatility:</label>
					<div class="val"><?php echo $coder->volatility;?></div>
				</div>
				<div class="row">
					<label>Rank:</label>
					<div class="val"><?php echo $coder->rank;?>
						<!-- of <?php echo $coder->activeMembers;?>-->
					</div>
				</div>
				<div class="row">
					<label>Default Language:</label>
					<div class="val"><?php echo $coder->defaultLanguage;?></div>
				</div>
				<div class="row">
					<label>Country Rank:</label>
					<div class="val"><?php echo $coder->countryRank;?></div>
				</div>
				<div class="row">
					<label>Competitions:</label>
					<div class="val"><?php echo $coder->competitions;?></div>
				</div>
				<div class="row">
					<label>Maximum Rating: </label>
					<div class="val"><?php echo $coder->maximumRating;?></div>
				</div>
				<div class="row">
					<label>Most Recent Event: </label>
					<div title="<?php echo $coder->mostRecentEventName;?> - <?php echo $coder->mostRecentEventDate;?>" class="val"><?php echo $coder->mostRecentEventName;?> - <?php echo $coder->mostRecentEventDate;?></div>
				</div>
				<div class="row">
					<label>Minimum Rating: </label>
					<div class="val"><?php echo $coder->minimumRating;?></div>
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
								<a class="btn btnHistory">Rating History</a> <a class="btn btnDist">Rating Distribution</a>
							</div>	
						<?php echo apply_filters('the_content','[tc_ratings_chart_ds contest="'.$track.'" handle="'.$handle.'"]');?>
						
						
						</div>
					</div>
				</div>
				<!-- /.subTrackTabs -->
			</div>
			<!-- /#graphView -->
			<div id="tabularView" class="show">
				<div class="subTrackTabs">
					<div class="srm subTrackTab">
						<div class="tableView">
							<article class="mainTabStream">
								<div class="tableWrap">
									<table class="ratingTable">
										<thead>
											<tr>
												<th class="colDetails">Details</th>
												<th class="colTotal">Total</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td class="colDetails">Best Rank</td>
												<td class="colTotal"><?php echo $coder->bestRank; ?></td>
											</tr>
											<tr class="alt">
												<td class="colDetails">Wins</td>
												<td class="colTotal"><?php echo $coder->wins; ?></td>
											</tr>

											<tr>
												<td class="colDetails">Top Five Finishes</td>
												<td class="colTotal"><?php echo $coder->topFiveFinishes; ?></td>
											</tr>
											<tr class="alt">
												<td class="colDetails">Top Ten Finishes</td>
												<td class="colTotal"><?php echo $coder->topTenFinishes; ?></td>
											</tr>
											<tr>
												<td class="colDetails">Avg.Rank</td>
												<td class="colTotal"><?php echo $coder->avgRank; ?></td>
											</tr>
											<tr class="alt">
												<td class="colDetails">Avg.Num.Submissions</td>
												<td class="colTotal"><?php echo $coder->avgNumSubmissions; ?></td>
											</tr>
											<tr>
												<td class="colDetails">Competitions</td>
												<td class="colTotal"><?php echo $coder->competitions; ?></td>
											</tr>
											<tr class="alt">
												<td class="colDetails">Most Recent Event</td>
												<td class="colTotal"><?php echo $coder->mostRecentEventName; ?></td>
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
		</div>
		<!-- /.ratingViews -->
		<?php else:?>
		<div class="noParticipation">
			<h2>Member rating unavailable or member didn't participated in any Marathon competition.</h2>
		</div>
		<?php endif;?>
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
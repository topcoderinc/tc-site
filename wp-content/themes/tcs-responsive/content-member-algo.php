<?php
// coder info
global $track;
global $coder;
$coder = get_member_statistics ( $handle, $track );

$rating = $coder->rating;

if ($rating > 0) {
	
	$div1 = $coder->Divisions->{'Division I'};
	$div2 = $coder->Divisions->{'Division II'};
	$divCh = $coder->Challenges->Levels;
	$srmD1 = ( float ) ($div1->{"Level Total"}->{'success%'});
	$srmD2 = ( float ) ($div2->{"Level Total"}->{'success%'});
	$srmClngeVal = ( float ) ($divCh->Total->{'success%'});
	
	// chart
	include_once TEMPLATEPATH . '/chart/Highchart.php';
	
	// add chart script chart
	$chart = new Highchart ();
	$chart->printScripts ();
	
	// donut chart
	$srmD1Chart = new Highchart ();
	$srmD1Chart->credits = array (
			'enabled' => false 
	);
	$srmD1Chart->chart = array (
			'renderTo' => 'srmD1Chart',
			'type' => 'pie',
			'margin' => 0,
			'marginRight' => 0,
			'borderWidth' => 0,
			'marginBottom' => 0,
			width => 236,
			height => 164 
	);
	$srmD1Chart->plotOptions->pie->dataLabels->enabled = false;
	$srmD1Chart->plotOptions->pie->borderWidth = 0;
	$srmD1Chart->title->text = null;
	$srmD1Chart->yAxis->title->enabled = false;
	$srmD1Chart->plotOptions->pie->shadow = false;
	$srmD1Chart->plotOptions->pie->states->hover = false;
	$srmD1Chart->tooltip->enabled = false;
	$srmD1Chart->series [] = array (
			'type' => "pie",
			'innerSize' => "90%",
			'name' => "Rating",
			'data' => array (
					array (
							'name' => "Division 1",
							'color' => "#81bc01",
							'y' => $srmD1 
					),
					array (
							'name' => "null",
							'color' => "#eeeeee",
							'y' => (100 - $srmD1) 
					) 
			) 
	);
	
	// donut chart
	$srmD2Chart = new Highchart ();
	$srmD2Chart->credits = array (
			'enabled' => false 
	);
	$srmD2Chart->chart = array (
			'renderTo' => 'srmD2Chart',
			'type' => 'pie',
			'margin' => 0,
			'marginRight' => 0,
			'marginBottom' => 0,
			width => 236,
			height => 164 
	);
	$srmD2Chart->plotOptions->pie->dataLabels->enabled = false;
	$srmD2Chart->plotOptions->pie->borderWidth = 0;
	$srmD2Chart->title->text = null;
	$srmD2Chart->yAxis->title->enabled = false;
	$srmD2Chart->plotOptions->pie->shadow = false;
	$srmD2Chart->plotOptions->pie->states->hover = false;
	$srmD2Chart->tooltip->enabled = false;
	$srmD2Chart->series [] = array (
			'type' => "pie",
			'innerSize' => "90%",
			'name' => "Rating",
			'data' => array (
					array (
							'name' => "Division 2",
							'color' => "#81bc01",
							'y' => $srmD2 
					),
					array (
							'name' => "null",
							'color' => "#eeeeee",
							'y' => (100 - $srmD2) 
					) 
			) 
	);
	
	// donut chart
	$srmClnge = new Highchart ();
	$srmClnge->credits = array (
			'enabled' => false 
	);
	$srmClnge->chart = array (
			'renderTo' => 'srmChallenge',
			'type' => 'pie',
			'margin' => 0,
			'marginRight' => 0,
			'marginBottom' => 0,
			width => 236,
			height => 164 
	);
	$srmClnge->plotOptions->pie->dataLabels->enabled = false;
	$srmClnge->plotOptions->pie->borderWidth = 0;
	$srmClnge->title->text = null;
	$srmClnge->yAxis->title->enabled = false;
	$srmClnge->plotOptions->pie->shadow = false;
	$srmClnge->plotOptions->pie->states->hover = false;
	$srmClnge->tooltip->enabled = false;
	$srmClnge->series [] = array (
			'type' => "pie",
			'innerSize' => "90%",
			'name' => "Rating",
			'data' => array (
					array (
							'name' => "Challenge",
							'color' => "#ffae00",
							'y' => $srmClngeVal 
					),
					array (
							'name' => "null",
							'color' => "#eeeeee",
							'y' => (100 - $srmClngeVal) 
					) 
			) 
	);
}

?>



<div id="algorithm" class="tab algoLayout">
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
							<th><a href="?tab=algo" class="isActive link">Algorithm</a></th>
							<th><a href="?tab=algo&ct=marathon" class="link">Marathon</a></th>
						</tr>
					</thead>
				</table>
			</nav>
		</div>
		
		<?php if ($rating > 0):?>
		<header class="head">
			<div class="trackNRating">
				<h4 class="trackName">Algorithm Competitions</h4>
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
					<div class="val"><?php echo $coder->mostRecentEventName;?> - <?php echo $coder->mostRecentEventDate;?></div>
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
								<a class="btn btnHistory isActive">Rating History</a> <a class="btn btnDist">Rating Distribution</a>
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
						<div class="tableView leftTabSplit">
							<aside class="leftTabStream">
								<div class="chartGroup">
									<div class="chartWrap">
										<div id="srmD1Chart" class="chart"></div>
										<div class="chartLbl">
											<em><?php echo $srmD1?>%</em>Division 1
										</div>
										<script type="text/javascript">
								            <?php echo $srmD1Chart->render("srmD1Chart"); ?>
								        </script>
									</div>
									<div class="chartWrap">
										<div id="srmD2Chart" class="chart"></div>
										<div class="chartLbl">
											<em><?php echo $srmD2?>%</em>Division 2
										</div>
										<script type="text/javascript">
								            <?php echo $srmD2Chart->render("srmD2Chart"); ?>
								        </script>
									</div>
									<div class="chartWrap chartYellow">
										<div id="srmChallenge" class="chart"></div>
										<div class="chartLbl">
											<em><?php echo $srmClngeVal?>%</em>Challenge
										</div>
										<script type="text/javascript">
								            <?php echo $srmClnge->render("srmClnge"); ?>
								        </script>
									</div>

								</div>
							</aside>
							<article class="mainTabStream">
								<div class="tableWrap">
									<table class="ratingTable">
										<caption>Division I Submission</caption>
										<thead>
											<tr>
												<th class="colProblem">Problem</th>
												<th>Submitted</th>
												<th>Failed Challenge</th>
												<th>Failed Sys.Test</th>
												<th>Success %</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td class="colProblem">Level One</td>
												<td><?php echo $div1->{"Level One"}->submitted; ?></td>
												<td><?php echo $div1->{"Level One"}->failedChallenge; ?></td>
												<td><?php echo $div1->{"Level One"}->{'failedSys.Test'}; ?></td>
												<td><?php echo $div1->{"Level One"}->{'success%'}; ?></td>
											</tr>
											<tr class="alt">
												<td class="colProblem">Level Two</td>
												<td><?php echo $div1->{"Level Two"}->submitted; ?></td>
												<td><?php echo $div1->{"Level Two"}->failedChallenge; ?></td>
												<td><?php echo $div1->{"Level Two"}->{'failedSys.Test'}; ?></td>
												<td><?php echo $div1->{"Level Two"}->{'success%'}; ?></td>
											</tr>
											<tr>
												<td class="colProblem">Level Three</td>
												<td><?php echo $div1->{"Level Three"}->submitted; ?></td>
												<td><?php echo $div1->{"Level Three"}->failedChallenge; ?></td>
												<td><?php echo $div1->{"Level Three"}->{'failedSys.Test'}; ?></td>
												<td><?php echo $div1->{"Level Three"}->{'success%'}; ?></td>
											</tr>
											<tr class="alt">
												<td class="colProblem">Total</td>
												<td><?php echo $div1->{"Level Total"}->submitted; ?></td>
												<td><?php echo $div1->{"Level Total"}->failedChallenge; ?></td>
												<td><?php echo $div1->{"Level Total"}->{'failedSys.Test'}; ?></td>
												<td><?php echo $div1->{"Level Total"}->{'success%'}; ?></td>
											</tr>

										</tbody>
									</table>
									<table class="ratingTable">
										<caption>Division II Submission</caption>
										<thead>
											<tr>
												<th class="colProblem">Problem</th>
												<th>Submitted</th>
												<th>Failed Challenge</th>
												<th>Failed Sys.Test</th>
												<th>Success %</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td class="colProblem">Level One</td>
												<td><?php echo $div2->{"Level One"}->submitted; ?></td>
												<td><?php echo $div2->{"Level One"}->failedChallenge; ?></td>
												<td><?php echo $div2->{"Level One"}->{'failedSys.Test'}; ?></td>
												<td><?php echo $div2->{"Level One"}->{'success%'}; ?></td>
											</tr>
											<tr class="alt">
												<td class="colProblem">Level Two</td>
												<td><?php echo $div2->{"Level Two"}->submitted; ?></td>
												<td><?php echo $div2->{"Level Two"}->failedChallenge; ?></td>
												<td><?php echo $div2->{"Level Two"}->{'failedSys.Test'}; ?></td>
												<td><?php echo $div2->{"Level Two"}->{'success%'}; ?></td>
											</tr>
											<tr>
												<td class="colProblem">Level Three</td>
												<td><?php echo $div2->{"Level Three"}->submitted; ?></td>
												<td><?php echo $div2->{"Level Three"}->failedChallenge; ?></td>
												<td><?php echo $div2->{"Level Three"}->{'failedSys.Test'}; ?></td>
												<td><?php echo $div2->{"Level Three"}->{'success%'}; ?></td>
											</tr>
											<tr class="alt">
												<td class="colProblem">Total</td>
												<td><?php echo $div2->{"Level Total"}->submitted; ?></td>
												<td><?php echo $div2->{"Level Total"}->failedChallenge; ?></td>
												<td><?php echo $div2->{"Level Total"}->{'failedSys.Test'}; ?></td>
												<td><?php echo $div2->{"Level Total"}->{'success%'}; ?></td>
											</tr>

										</tbody>
									</table>
									<table class="ratingTable">
										<caption>Challenges</caption>
										<thead>
											<tr>
												<th class="colProblem">Problem</th>
												<th>#Failed Challenge</th>
												<th>#Challenges</th>
												<th>Success %</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td class="colProblem">Level One</td>
												<td><?php echo $divCh->{"Level One"}->failedChallenge; ?></td>
												<td><?php echo $divCh->{"Level One"}->challenges; ?></td>
												<td><?php echo $divCh->{"Level One"}->{'success%'}; ?></td>
											</tr>
											<tr class="alt">
												<td class="colProblem">Level Two</td>
												<td><?php echo $divCh->{"Level Two"}->failedChallenge; ?></td>
												<td><?php echo $divCh->{"Level Two"}->challenges; ?></td>
												<td><?php echo $divCh->{"Level Two"}->{'success%'}; ?></td>
											</tr>
											<tr>
												<td class="colProblem">Level Three</td>
												<td><?php echo $divCh->{"Level Three"}->failedChallenge; ?></td>
												<td><?php echo $divCh->{"Level Three"}->challenges; ?></td>
												<td><?php echo $divCh->{"Level Three"}->{'success%'}; ?></td>
											</tr>
											<tr class="alt">
												<td class="colProblem">Total</td>
												<td><?php echo $divCh->Total->failedChallenge; ?></td>
												<td><?php echo $divCh->Total->challenges; ?></td>
												<td><?php echo $divCh->Total->{'success%'}; ?></td>
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
			<h2>Member rating unavailable or member didn't participated in any Algorithm competition.</h2>
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
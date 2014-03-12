<?php
// coder info


$track= "data/srm";
if ($tab == "algo") {
	$track = "data/srm";
}else if ($tab == "develop") {
	$track = "develop";
}else if ($tab == "design") {
	$track = "design";
}
global $coder;
$coder = get_member_statistics ( $handle, $track );
$div1 =$coder->Divisions->{'Division I'};
$div2 = $coder->Divisions->{'Division II'};
$divCh =$coder->Challenges->Levels;
$srmD1 = (float)($div1->{"Level Total"}->{'success%'});
$srmD2 = (float)($div2->{"Level Total"}->{'success%'});
$srmClngeVal = (float)($divCh->Total->{'success%'});


// chart
include_once TEMPLATEPATH . '/chart/Highchart.php';


// line chart
$chart = new Highchart ();
$chart->chart = array (
		'renderTo' => 'algoChart',
		'type' => 'line',
		'marginRight' => 20,
		'marginBottom' => 10 
);

$chart->credits = array (
		'enabled' => false 
);

$chart->title = array (
		'text' => null 
);

$chart->yAxis = array (
		'title' => array (
				'text' => null 
		),
		'plotLines' => array (
				array (
						'value' => 0,
						'width' => 1,
						'color' => '#808080' 
				) 
		) 
);
$chart->xAxis = array (
		'labels' => array (
				'enabled' => false 
		) 
);
$chart->legend = array (
		'enabled' => false 
);

$chart->series [] = array (
		'name' => 'SRM 400',
		'data' => array (
				7.0,
				6.9,
				9.5,
				14.5,
				18.2,
				21.5,
				25.2,
				26.5,
				23.3,
				18.3,
				13.9,
				9.6 
		) 
);
$chart->series [] = array (
		'name' => 'SRM 401',
		'data' => array (
				- 0.2,
				0.8,
				5.7,
				11.3,
				17.0,
				22.0,
				24.8,
				24.1,
				20.1,
				14.1,
				8.6,
				2.5 
		) 
);
$chart->series [] = array (
		'name' => 'SRM 402',
		'data' => array (
				- 0.9,
				0.6,
				3.5,
				8.4,
				13.5,
				17.0,
				18.6,
				17.9,
				14.3,
				9.0,
				3.9,
				1.0 
		) 
);
$chart->series [] = array (
		'name' => 'SRM 405',
		'data' => array (
				3.9,
				4.2,
				5.7,
				8.5,
				11.9,
				15.2,
				17.0,
				16.6,
				14.2,
				10.3,
				6.6,
				4.8 
		) 
);

$chart->tooltip->formatter = new HighchartJsExpr ( "function() { return '<b>'+ this.series.name +'</b><br/>'+ this.x +': '+ this.y ;}" );

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
		'marginBottom' => 0, width => 236, height=> 164
);
$srmD1Chart->plotOptions->pie->dataLabels->enabled = false;
$srmD1Chart->plotOptions->pie->borderWidth = 0;
$srmD1Chart->title->text = null;
$srmD1Chart->yAxis->title->enabled=false;
$srmD1Chart->plotOptions->pie->shadow = false;
$srmD1Chart->plotOptions->pie->states->hover = false;
$srmD1Chart->tooltip->enabled = false;
$srmD1Chart->series [] = array (
		'type' => "pie",
		'innerSize' => "90%",
		'name' => "Rating",
		'data' => array (
				array (
						'name'=>"Division 1",
						'color'=>"#81bc01",
						'y' =>$srmD1						
				),
				array (
						'name'=>"null",
						'color'=>"#eeeeee",
						'y' =>(100-$srmD1)
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
		'marginBottom' => 0, width => 236, height=> 164
);
$srmD2Chart->plotOptions->pie->dataLabels->enabled = false;
$srmD2Chart->plotOptions->pie->borderWidth = 0;
$srmD2Chart->title->text = null;
$srmD2Chart->yAxis->title->enabled=false;
$srmD2Chart->plotOptions->pie->shadow = false;
$srmD2Chart->plotOptions->pie->states->hover = false;
$srmD2Chart->tooltip->enabled = false;
$srmD2Chart->series [] = array (
		'type' => "pie",
		'innerSize' => "90%",
		'name' => "Rating",
		'data' => array (
				array (
						'name'=>"Division 2",
						'color'=>"#81bc01",
						'y' =>$srmD2
				),
				array (
						'name'=>"null",
						'color'=>"#eeeeee",
						'y' =>(100-$srmD2)
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
		'marginBottom' => 0, width => 236, height=> 164
);
$srmClnge->plotOptions->pie->dataLabels->enabled = false;
$srmClnge->plotOptions->pie->borderWidth = 0;
$srmClnge->title->text = null;
$srmClnge->yAxis->title->enabled=false;
$srmClnge->plotOptions->pie->shadow = false;
$srmClnge->plotOptions->pie->states->hover = false;
$srmClnge->tooltip->enabled = false;
$srmClnge->series [] = array (
		'type' => "pie",
		'innerSize' => "90%",
		'name' => "Rating",
		'data' => array (
				array (
						'name'=>"Challenge",
						'color'=>"#ffae00",
						'y' =>$srmClngeVal
				),
				array (
						'name'=>"null",
						'color'=>"#eeeeee",
						'y' =>(100-$srmClngeVal)
				)
		)
);

?>



<div id="algorithm" class="tab algoLayout">
	<nav class="tabNav">
		<ul>
			<li><a href="javascript:;" class="isActive">Algorithm</a></li>
			<li><a href="javascript:;">Marathon Matches</a></li>
		</ul>
	</nav>
	<div class="ratingInfo">
		<header class="head">
			<div class="trackNRating">
				<h4 class="trackName">Data Science Competitions</h4>
				<div class="rating"><?php echo $coder->rating;?></div>
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
					<div class="val"><?php echo $coder->rank;?><!-- of <?php echo $coder->activeMembers;?>--></div>
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
							<div id="algoChart" class="chart"></div>
						<?php $chart->printScripts(); ?>   
						<script type="text/javascript">						
							<?php echo $chart->render("algoChart"); ?>
						</script>
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
										<div class="chartLbl"><em><?php echo $srmD1?>%</em>Division 1</div>
										<script type="text/javascript">
								            <?php echo $srmD1Chart->render("srmD1Chart"); ?>
								        </script>
									</div>
									<div class="chartWrap">
										<div id="srmD2Chart" class="chart"></div>
										<div class="chartLbl"><em><?php echo $srmD2?>%</em>Division 2</div>
										<script type="text/javascript">
								            <?php echo $srmD2Chart->render("srmD2Chart"); ?>
								        </script>
									</div>
									<div class="chartWrap chartYellow">
										<div id="srmChallenge" class="chart"></div>
										<div class="chartLbl"><em><?php echo $srmClngeVal?>%</em>Challenge</div>
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
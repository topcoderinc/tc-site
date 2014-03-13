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

$statsContestTypeMappings = array(
	"ui-prototype-competition" => "UI Prototype Competition",
	"assembly-competition" => "Assembly Competition",
	"development" => "Development",
	"specification" => "Specification",
	"conceptualization" => "Conceptualization",
	"design" => "Design",
	"test-suites" => "Test Suites",
	"test-scenarios" => "Test Scenarios",
	"ria-build" => "RIA Build Competition",
	"reporting" => "Reporting",
	"content-creation" => "Content Creation"
);

$chartContestTypeMappings = array(
	"ui-prototype-competition" => "ui_prototypes",
	"assembly-competition" => "assembly",
	"development" => "development",
	"specification" => "specification",
	"conceptualization" => "conceptualization",
	"design" => "design",
	"test-suites" => "test_suites",
	"test-scenarios" => "test_scenarios",
	"ria-build" => "ria_build",
	"reporting" => "reporting",
	"content-creation" => "content_creation"
);

global $coder;
$coder = get_member_statistics ( $handle, $track );
$contestType = get_query_var ( 'ct' );
if(!$contestType){
	$contestType = "development";
}
$apiContestType = $statsContestTypeMappings[$contestType];
$dev =$coder->Tracks->$apiContestType;

$chartRawData = get_member_chart_statistics( $handle, $track, $chartContestTypeMappings[$contestType] );

// chart
include_once TEMPLATEPATH . '/chart/Highchart.php';


// line chart
$chart = new Highchart ();
$chart->chart = array (
		'renderTo' => 'algoChart',
		'type' => 'line',
		'marginRight' => 20,
		'marginBottom' => 10,  
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
		),
		tickInterval => 100
);
$chart->xAxis = array (
		type => 'datetime',
		dateTimeLabelFormats => array (
       year => '%Y'
  ),
  tickInterval => 24 * 3600 * 1000 * 356 // one year interval
);
$chart->legend = array (
		'enabled' => false 
);

$chart->yAxis->plotBands[] = array(
 'from' => 0,
 'to' => 899,
 'color' => "rgba(153, 153, 153, 0.2)"
);
$chart->yAxis->plotBands[] = array(
 'from' => 900,
 'to' => 1199,
 'color' => "rgba(0, 169, 0, 0.2)"
);
$chart->yAxis->plotBands[] = array(
 'from' => 1200,
 'to' => 1499,
 'color' => "rgba(102, 102, 255, 0.2)"
);
$chart->yAxis->plotBands[] = array(
 'from' => 1500,
 'to' => 2199,
 'color' => "rgba(221, 204, 0, 0.2)"
);
$chart->yAxis->plotBands[] = array(
 'from' => 2200,
 'to' => 10000,
 'color' => "rgba(238, 0, 0, 0.2)"
);

$chartData = array();
for ($index=0; $index<=count($chartRawData->history); $index++)
{
	$rating = $chartRawData->history[$index]->rating;
	$pointColor = "#999999";
	if($rating >= 900 && $rating <= 1199){
		$pointColor = "#00A900";
	} else if($rating >= 1200 && $rating <= 1499){
		$pointColor = "#6666FF";
	} else if($rating >= 1500 && $rating <= 2199){
		$pointColor = "#DDCC00";
	} else if($rating >= 2200 && $rating <= 10000){
		$pointColor = "#EE0000";
	}
	list($year, $month, $day) = split('[/.-]', $chartRawData->history[$index]->date);
	if($month && $day && $year){
		$chartData[$index] = array(name => $chartRawData->history[$index]->challengeName, x => new HighchartJsExpr("Date.UTC(" . $year . ", " . $month .", " . $day . ")"), y => $rating, marker => array(fillColor => $pointColor));
	}
}

$chart->series [] = array (
		'data' => $chartData,
		'color' => '#888888',
		lineWidth => 1
);
$chart->tooltip->formatter = new HighchartJsExpr ( "function() { return '<b>'+ this.point.name +'</b><br/>'+ Highcharts.dateFormat('%m / %d / %Y', this.x) + '</b><br/>' + 'Rating: '+ this.y ;}" );

?>



<div id="develop" class="tab algoLayout">
	<nav class="tabNav">
		<ul>
		
			<li class="conceptualization"><a href="?tab=<?php echo $tab;?>&ct=conceptualization" class="<?php if($contestType=='conceptualization'){ echo 'isActive';}?>">Concept</a></li>
			<li class="specification"><a href="?tab=<?php echo $tab;?>&ct=specification" class="<?php if($contestType=='specification'){ echo 'isActive';}?>">Spec</a></li>			
			<li class="design"><a href="?tab=<?php echo $tab;?>&ct=design" class="<?php if($contestType=='design'){ echo 'isActive';}?>">Software Design</a></li>			
			<li class="development"><a href="?tab=<?php echo $tab;?>&ct=development" class="<?php if(!$contestType || $contestType=='development'){ echo 'isActive';}?>">Dev</a></li>
			<li class="assembly-competition"><a href="?tab=<?php echo $tab;?>&ct=assembly-competition" class="<?php if($contestType=='assembly-competition'){ echo 'isActive';}?>">Assembly</a></li>
			<li class="test-suites"><a href="?tab=<?php echo $tab;?>&ct=test-suites" class="<?php if($contestType=='test-suites'){ echo 'isActive';}?>">Test Suites</a></li>
			<li class="test-scenarios"><a href="?tab=<?php echo $tab;?>&ct=test-scenarios" class="<?php if($contestType=='test-scenarios'){ echo 'isActive';}?>">Test Scenarios</a></li>
			<li class="ui-prototype-competition"><a href="?tab=<?php echo $tab;?>&ct=ui-prototype-competition" class="<?php if($contestType=='ui-prototype-competition'){ echo 'isActive';}?>">UI Prototype</a></li>
			<li class="ria-build"><a href="?tab=<?php echo $tab;?>&ct=ria-build" class="<?php if($contestType=='ria-build'){ echo 'isActive';}?>">RIA Build</a></li>
			<li class="content-creation"><a href="?tab=<?php echo $tab;?>&ct=content-creation" class="<?php if($contestType=='content-creation'){ echo 'isActive';}?>">Content</a></li>
			<li class="reporting"><a href="?tab=<?php echo $tab;?>&ct=reporting" class="<?php if($contestType=='reporting'){ echo 'isActive';}?>">Reporting</a></li>
		</ul>
	</nav>
	<div class="ratingInfo">
		<header class="head">
			<div class="trackNRating">
				<h4 class="trackName">Development Competitions</h4>
				<div class="rating"><?php echo $dev->rating;?></div>
				<div class="lbl">Rating</div>
			</div>
			<div class="detailedRating">
				<div class="row">
					<label>Percentile:</label>
					<div class="val"><?php echo $dev->activePercentile;?></div>
				</div>
				<div class="row">
					<label>Rank:</label>
					<div class="val"><?php echo $dev->activeRank;?></div>
				</div>
				<div class="row">
					<label>Country Rank:</label>
					<div class="val"><?php echo $dev->overallCountryRank;?></div>
				</div>
				<div class="row">
					<label>School Rank:</label>
					<div class="val"><?php echo $dev->activeSchoolRank;?></div>
				</div>
				<div class="row">
					<label>Maximum Rating: </label>
					<div class="val"><?php echo $dev->maximumRating;?></div>
				</div>
				<div class="row">
					<label>Minimum Rating: </label>
					<div class="val"><?php echo $dev->minimumRating;?></div>
				</div>
				<div class="row">
					<label>Volatility:</label>
					<div class="val"><?php echo $dev->volatility;?></div>
				</div>
				<div class="row">
					<label>Competitions:</label>
					<div class="val"><a href="#"><?php echo $dev->competitions;?></a></div>
				</div>
				<div class="row">
					<label>Reviewer Rating: </label>
					<div class="val"><a href="#"><?php echo $dev->reviewerRating;?></a></div>
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
							algoChart.reflow();
						</script>
						</div>
					</div>
				</div>
				<!-- /.subTrackTabs -->
			</div>
			<!-- /#graphView -->
			<div id="tabularView" >
				<div class="subTrackTabs">
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
												<td class="colTotal"><?php echo $dev->inquiries; ?></td>
											</tr>
											<tr class="alt">
												<td class="colDetails">Submissions</td>
												<td class="colTotal"><?php echo $dev->submissions; ?></td>
											</tr>
											
											<tr>
												<td class="colDetails">Submission Rate</td>
												<td class="colTotal"><?php echo $dev->submissionRate; ?></td>
											</tr>
											<tr class="alt">
												<td class="colDetails">Passed Screening</td>
												<td class="colTotal"><?php echo $dev->passedScreening; ?></td>
											</tr>
											<tr>
												<td class="colDetails">Screening Success Rate</td>
												<td class="colTotal"><?php echo $dev->screeningSuccessRate; ?></td>
											</tr>
											<tr class="alt">
												<td class="colDetails">Passed Review</td>
												<td class="colTotal"><?php echo $dev->passedReview; ?></td>
											</tr>
											<tr>
												<td class="colDetails">Review Success Rate</td>
												<td class="colTotal"><?php echo $dev->reviewSuccessRate; ?></td>
											</tr>
											<tr class="alt">
												<td class="colDetails">Maximum Score</td>
												<td class="colTotal"><?php echo $dev->maximumScore; ?></td>
											</tr>
											<tr>
												<td class="colDetails">Minimum Score</td>
												<td class="colTotal"><?php echo $dev->minimumScore; ?></td>
											</tr>											
											<tr class="alt">
												<td class="colDetails">Appeals</td>
												<td class="colTotal"><?php echo $dev->appeals; ?></td>
											</tr>
											<tr>
												<td class="colDetails">Appeal Success Rate</td>
												<td class="colTotal"><?php echo $dev->appealSuccessRate; ?></td>
											</tr>
											<tr class="alt">
												<td class="colDetails">Average Score</td>
												<td class="colTotal"><?php echo $dev->averageScore; ?></td>
											</tr>
											<tr>
												<td class="colDetails">Average Placement</td>
												<td class="colTotal"><?php echo $dev->averagePlacement; ?></td>
											</tr>
											<tr class="alt">
												<td class="colDetails">Wins</td>
												<td class="colTotal"><?php echo $dev->wins; ?></td>
											</tr>
											<tr>
												<td class="colDetails">Win Percentage</td>
												<td class="colTotal"><?php echo $dev->winPercentage; ?></td>
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
			<h4>Badges Cabinet</h4>
		</header>
		<?php get_template_part('content', 'badges');?>		
	</aside>
 -->	<!-- /.badges -->
</div>
<!-- /.algoLayout -->
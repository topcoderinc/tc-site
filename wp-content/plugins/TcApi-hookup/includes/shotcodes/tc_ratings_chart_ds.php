<?php

/*
 * tc_ratings_chart_ds
 */
$chartWidth=768;
function tc_ratings_chart_ds_function($atts, $content = null) {
	extract ( shortcode_atts ( array (
			"contest" => "",
			"handle" => "" 
	), $atts ) );
	
	$url = "http://api.topcoder.com/v2/users/" . $handle . "/statistics/" . $contest;
	
	$args = array (
			'httpversion' => get_option ( 'httpversion' ),
			'timeout' => get_option ( 'request_timeout' ) 
	);
	
	$response = wp_remote_get ( $url, $args );
	if (is_wp_error ( $response ) || ! isset ( $response ['body'] )) {
		return "Error";
	}
	if ($response ['response'] ['code'] == 200) {
		$data = json_decode ( $response ['body'] );
	}
	
	/* rating history data */
	$history = $data->History;
	// date format is like: 2005.09.07
	$hseries = array ();
	foreach ( $history as &$score ) {
		$date = explode ( ".", $score->date );
		$dateExp = "Date.UTC(" . $date [0] . "," . $date [1] . "," . $date [2] . ")";
		
		$tTipName = "Contest Name:";
		$challengeName=$score->challengeName;
		if(empty($challengeName)){
			$tTipName = "Contest Id:";
			$challengeName = $score->challengeId;
		}
		
		array_push ( $hseries, array (
				'x' => new HighchartJsExpr ( $dateExp ),
				'y' => $score->rating,
				'name' => $challengeName,
				'marker' => array (
						'fillColor' => get_point_color ( $score->rating ),
						'radius' => 4,
						'lineWidth'=>0,
						'lineColor'=>'#666'
				)
		) );
	}
		
	$maxVal = -1;
	$hMax = "";
	$count = 0;
	foreach($hseries as &$hVal){
		if($hVal['y'] > $maxVal){
			$hMax = $count;
			$maxVal = $hVal['y'];
		}
		$count+=1;
	}	

	$hseries[$hMax]['marker']['lineWidth'] = "2";
	$hseries[$hMax]['marker']['radius'] = "8";
	
	foreach ( $hseries as $key => $row ) {
		$x [$key] = $row ['x'];
	}
	array_multisort ( $x, SORT_ASC, $hseries );
	
	/* Distribution data */
	$distribution = $data->Distribution;
	$dseries = array ();
	foreach ( $distribution as &$point ) {
		
		$range = explode ( "-", $point->range ); // explode($point->range);
		$mean = (( int ) $range [0] + ( int ) $range [1]) / 2;
		
		array_push ( $dseries, array (
				'x' => $mean,
				'y' => $point->number,
				'color' => get_point_color ( $mean ) 
		) );
	}
	
	$cdata = array (
			'hseries' => $hseries,
			'dseries' => $dseries 
	);
	
	$ratingsChart = new Highchart ();
	$ratingsChart->chart = array (
			'renderTo' => 'chart_' . $handle,
			'type' => 'line',
			'marginRight' => 20,
			'marginBottom' => 20,
			'width'=>768 
	);
	$ratingsChart->credits = array (
			'enabled' => false 
	);
	$ratingsChart->title = array (
			'text' => null 
	);
	
	$ratingsChart->yAxis = array (
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
			'plotBands' => array (
					array (
							"from" => 0,
							"to" => 899,
							"color" => "rgba(153, 153, 153, 0.2)" 
					),
					array (
							"from" => 900,
							"to" => 1199,
							"color" => "rgba(0, 169, 0, 0.2)" 
					),
					array (
							"from" => 1200,
							"to" => 1499,
							"color" => "rgba(102, 102, 255, 0.2)" 
					),
					array (
							"from" => 1500,
							"to" => 2199,
							"color" => "rgba(221, 204, 0, 0.2)" 
					),
					array (
							"from" => 2200,
							"to" => 10000,
							"color" => "rgba(238, 0, 0, 0.2)" 
					) 
			) 
	);
	$ratingsChart->xAxis = array (
			'type' => "datetime",
			'title' => array (
					'text' => null 
			),
			dateTimeLabelFormats => array (
					year => '%Y' 
			),
			tickInterval => 24 * 3600 * 1000 * 356 
	);
	
	$ratingsChart->legend = array (
			'enabled' => false 
	);
	$ratingsChart->tooltip = array (
			'formatter' => new HighchartJsExpr ( "function() {
		        return '".$tTipName." <b>'+ this.point.name +'</b><br/>Date: '+
		        Highcharts.dateFormat('%e %b %Y', this.x) +'<br/>Rating: '+ this.y ;
		    }" ) 
	);
	$ratingsChart->series [] = array (
			'name' => 'Rating',
			'color' => '#888888',
			"lineWidth" => 1,
			'data' => $hseries 
	);
	
	/* distribution chart */
	$distChart = new Highchart ();
	$distChart->chart = array (
			'renderTo' => 'chart_d_' . $handle,
			'type' => 'column',
			'marginRight' => 20,
			'marginBottom' => 70,
			'width'=>768  
	);
	$distChart->credits = array (
			'enabled' => false 
	);
	$distChart->title = array (
			'text' => null 
	);
	
	$distChart->yAxis = array (
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
	$distChart->xAxis = array (
			'title' => array (
					'text' => null 
			),
			'min' => 50,
			'labels' => array (
					'rotation' => 90,
					'step' => 1,
					'formatter' => new HighchartJsExpr ( "function() {
						var vm = parseInt(this.value)-50;
						var vMx =  parseInt(this.value)+49;
						return vm+'-'+vMx;
					}" ),
					'y' => 18,
					'x' => - 4
			),
			tickPositioner => new HighchartJsExpr ( "function () {
				var positions = [],
				tick = 50,
				increment = 100;
			
				for (; tick - increment <= this.dataMax; tick += increment) {
					positions.push(tick);
				}
				return positions;
			}" ) 
	);
	
	$distChart->legend = array (
			'enabled' => false 
	);
	$distChart->tooltip = array (
			'formatter' => new HighchartJsExpr ( "function() {
		        return this.y +' Coders';
		    }" ) 
	);
	$distChart->series [] = array (
			'name' => 'Rating',
			'color' => '#888888',
			"lineWidth" => 1,
			'data' => $dseries 
	);
	
	global $coder;
	$html = "
		<div class='ratingChart distributionType' id='chart_d_$handle'></div>
		<div class='ratingChart historyType' id='chart_$handle'></div>
	";
	$html .= "
			<script type='text/javascript'>	
				var chart_$handle;
				var chart_d_$handle;
				
						
				$(document).ready(function(){
				" . $ratingsChart->render ( 'chart_' . $handle ) . ";  var currentChart = chart_" . $handle . ";
				" . $distChart->render ( 'chart_d_' . $handle ) . ";  var currentDistChart = chart_d_" . $handle . ";
						
						currentDistChart.xAxis[0].addPlotLine({
		                value: " . $coder->rating . ",
		                color: '" . get_point_color ( $coder->rating ) . "',
		                width: 2,
                		label : {
							text: '" . $coder->rating . "',
							style:{'color': '" . get_point_color ( $coder->rating ) . "'}
						}
		            });
				})
			</script>
			";
	return $html;
}
add_shortcode ( "tc_ratings_chart_ds", "tc_ratings_chart_ds_function" );



?>
<?php

/*
 * tc_ratings_chart
 */
function tc_ratings_chart_dev_function($atts, $content = null) {
	extract ( shortcode_atts ( array (
			"challengetype" => "",
			"handle" => "" 
	), $atts ) );
	
	$url = "http://api.topcoder.com/v2/develop/statistics/" . $handle . "/" . $challengetype . "/";
	
	$cdata = get_data_dev ( $url );
	$hseries = $cdata['hseries'];
	$dseries = $cdata['dseries'];
	
	
	
	/* ratings chart */
	
	$ratingsChart = new Highchart ();
	$ratingsChart->chart = array (
			'renderTo' => 'chart_' . $handle,
			'type' => 'line',
			'marginRight' => 15,
			'marginLeft' => 30,
			'marginBottom' => 20,
			'marginTop' => 20
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
			dateTimeLabelFormats => array (
			       year => '%Y'
			  ),
			  tickInterval => 24 * 3600 * 1000 * 356 // one year interval
	);
	
	$ratingsChart->legend = array (
			'enabled' => false 
	);
	$ratingsChart->tooltip = array (
			'formatter' => new HighchartJsExpr ( "function() {
		        return 'Challenge: <b>'+ this.point.name +'</b><br/>Date: '+
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
			'marginBottom' => 70
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
			'name' => 'Distribution',
			'color' => '#888888',
			"lineWidth" => 1,
			'data' => $dseries
	);
	
	$html = "
		<div class='ratingChart distributionType' id='chart_d_$handle'></div>
		<div class='ratingChart historyType' id='chart_$handle'></div>
	";
	global $dev;
	$html .= "
			<script type='text/javascript'>
				var chart_$handle;
				var chart_d_$handle;
				var currentDistChart;
				var currentChart;
				
				" . $ratingsChart->render ( 'chart_' . $handle ) . ";  
				currentChart = chart_$handle;
						
				" . $distChart->render ( 'chart_d_' . $handle ) . ";  
				currentDistChart = chart_d_" . $handle . ";
				
						
		
				var ct = $.trim($('.tabNavHead .isActive').text());
						
				$(document).ready(function(){
						currentDistChart.xAxis[0].addPlotLine({
		                value: ".$dev->rating.",
		                color: '".get_point_color ( $dev->rating )."',
		                width: 2,
                		label : {
							text: '" . $dev->rating . "',
							style:{'color': '" . get_point_color ( $dev->rating ) . "'}
						}
		            });
				})
		                	
			</script>
			";
	
	return $html;
}
add_shortcode ( "tc_ratings_chart_dev", "tc_ratings_chart_dev_function" );
function get_all_contest() {
	$url = "http://api.topcoder.com/v2/develop/challengetypes";
	
	$args = array (
			'httpversion' => get_option ( 'httpversion' ),
			'timeout' => get_option ( 'request_timeout' ) 
	);
	
	$challengetypes = array ();
	
	$response = wp_remote_get ( $url, $args );
	if (is_wp_error ( $response ) || ! isset ( $response ['body'] )) {
		return "{Error:'Data not available'}";
	}
	if ($response ['response'] ['code'] == 200) {
		$contestList = json_decode ( $response ['body'] );
		foreach ( $contestList as &$contest ) {
			array_push ( $challengetypes, $contest->name );
		}
	}
	return $challengetypes;
}
function get_data_dev($url) {
	global $_POST;
	if (empty ( $url )) {
		$url = "http://api.topcoder.com/v2/develop/statistics/" . $_POST ['handle'] . "/" . $_POST ['challengetype'] . "/";
	}
	$args = array (
			'httpversion' => get_option ( 'httpversion' ),
			'timeout' => get_option ( 'request_timeout' ) 
	);
	
	$response = wp_remote_get ( $url, $args );
	if (is_wp_error ( $response ) || ! isset ( $response ['body'] )) {
		return "{Error:'Data not available'}";
	}
	if ($response ['response'] ['code'] == 200) {
		$data = json_decode ( $response ['body'] );
	}
	
	/* history data */
	$history = $data->history;
	// date format is like: 2005.09.07
	$hseries = array ();
	foreach ( $history as &$score ) {
		$date = explode ( ".", $score->date );
		$dateExp = "Date.UTC(" . $date [0] . "/" . $date [1] . "/" . $date [2] . ")";
		array_push ( $hseries, array (
				'x' => strtotime ( $date [0] . "/" . $date [1] . "/" . $date [2] ) * 1000,
				'y' => $score->rating,
				'name' => $score->challengeName,
				'marker' => array (
						'fillColor' => get_point_color ( $score->rating ),
						'radius' => 4 ,
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
	$hseries[$hMax]['marker']['radius'] = "7";
	
	foreach ( $hseries as $key => $row ) {
		$x [$key] = $row ['x'];
	}
	array_multisort ( $x, SORT_ASC, $hseries );
	
	/* Distribution data */
	$distribution = $data->distribution;
	$dseries = array ();
	foreach ( $distribution as &$point ) {

		$range = explode( "-", $point->range );//explode($point->range);
		$mean = ((int)$range[0] + (int)$range[1])/2;
		
		array_push($dseries, array(			
			'x'=>$mean,
			'y'=>$point->number,
				'color' => get_point_color ( $mean )
		));
	}
	
	$cdata = array(
		'hseries'=>$hseries,
		'dseries'=>$dseries
	);

	if (! empty ( $_POST ['handle'] )) {
		echo json_encode ( $cdata );
		die ();
	}
	return $cdata;
}
;

add_action ( 'wp_ajax_ratings_dev_chart_data', 'get_data_dev' );
add_action ( 'wp_ajax_nopriv_ratings_dev_chart_data', 'get_data_dev' );

?>
<?php

/*
 * tc_rating_color
 */
function tc_rating_color_function($atts, $content=null) {
	extract ( shortcode_atts ( array (
			"score" => ""
	), $atts ) );
	
	return get_color($score);
}

function get_color($score){
	switch ($score){
		case ($score < 900):
		return "coderTextGray";
		case ($score < 1200):
		return "coderTextGreen";
		case ($score < 1500):
		return "coderTextBlue";
		case ($score < 2200):
		return "coderTextYellow";
		case ($score >= 2200):
		return "coderTextRed";
		default:
		return "coderTextBlack";
	}
}

add_shortcode('tc_rating_color', 'tc_rating_color_function');

function get_point_color($rating){
	switch ($rating){
		case ($rating < 900):
			return "#999999";
		case ($rating < 1200):
			return "#00A900";
		case ($rating < 1500):
			return "#6666FF";
		case ($rating < 2200):
			return "#DDCC00";
		case ($rating >= 2200):
			return "#EE0000";
		default:
			return "#000";
	}
}
?>

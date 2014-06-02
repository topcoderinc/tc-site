<?php

/* member stastics */
function get_member_statistics($handle, $track) {
	$url = TC_API_URL . "/users/$handle/statistics/$track";
	$args = array (
			'httpversion' => get_option ( 'httpversion' ),
			'timeout' => get_option ( 'request_timeout' ) 
	);
	$response = wp_remote_get ( $url, $args );
	
	if (is_wp_error ( $response ) || ! isset ( $response ['body'] )) {
		return "Error in processing request";
	}
	if ($response ['response'] ['code'] == 200) {
		return json_decode ( $response ['body'] );
	}
	return "Error in processing request";
}

/* member achievements */
function get_member_achievements($handle = '') {
	$url = TC_API_URL . "/users/" . $handle . "?data=achievements";
	$args = array (
			'httpversion' => get_option ( 'httpversion' ),
			'timeout' => get_option ( 'request_timeout' ) 
	);
	$response = wp_remote_get ( $url, $args );
	
	if (is_wp_error ( $response ) || ! isset ( $response ['body'] )) {
		return "Error in processing request or Member dosen't exist";
	}
	if ($response ['response'] ['code'] == 200) {
		$coder_achievements = json_decode ( $response ['body'] );
		return $coder_achievements;
	}
	return "Error in processing request";
}

/* search coder */
function search_coder($handle = '') {
	$url = TC_API_URL . "/users/search/?handle=" . $handle;
	$args = array (
			'httpversion' => get_option ( 'httpversion' ),
			'timeout' => 30 
	);
	$response = wp_remote_get ( $url, $args );
	
	if (is_wp_error ( $response ) || ! isset ( $response ['body'] )) {
		return "Error in processing request";
	}
	if ($response ['response'] ['code'] == 200) {
		$users = json_decode ( $response ['body'] );
		return $users;
	}
	return "Error in processing request";
}

/* member achievements current */
function get_member_achievements_current($userId = '', $badgeId = '') {
	$url = "http://community.topcoder.com/tc?module=MemberAchievementCurrent&cr=" . $userId . "&ruleId=" . $badgeId;
	$args = array (
			'httpversion' => get_option ( 'httpversion' ),
			'timeout' => 30 
	);
	$response = wp_remote_get ( $url, $args );
	
	if (is_wp_error ( $response ) || ! isset ( $response ['body'] )) {
		return "Error in processing request";
	}
	if ($response ['response'] ['code'] == 200) {
		$coder_achievements_current = json_decode ( $response ['body'] );
		return $coder_achievements_current;
	}
	return "Error in processing request";
}

/* get member profile design recent Wins */
function get_stat_design_recentwins($handle = '') {
	$url      = TC_API_URL . "/users/$handle/statistics/design/recentWins";
	$args     = array(
			'httpversion' => get_option('httpversion'),
			'timeout'     => 30
	);
	$response = wp_remote_get($url, $args);

	if (is_wp_error($response) || !isset ( $response ['body'] )) {
		return "Error in processing request or Member dosen't exist";
	}
	if ($response ['response'] ['code'] == 200) {
		$coder_achievements = json_decode($response ['body']);
		return $coder_achievements;
	}

	return "Error in processing request";
}

// Ajax request
function get_template_part_by_ajax_ctrl() {
	$tab =  $_POST ["tab"];
	if(empty($ct)){
		$ct = $_POST ["ct"];
	}
	if ($tab == "design") {
		get_template_part ( 'content', 'member-design' );
	} else if ($tab == "develop") {
		get_template_part ( 'content', 'member-develop' );
	} else if ($ct == "marathon") {
		get_template_part ( 'content', 'member-marathon' );
	} else {
		get_template_part ( 'content', 'member-algo' );
	}
	die ();
}

add_action ( 'wp_ajax_get_template_part_by_ajax', 'get_template_part_by_ajax_ctrl' );
add_action ( 'wp_ajax_nopriv_get_template_part_by_ajax', 'get_template_part_by_ajax_ctrl' );

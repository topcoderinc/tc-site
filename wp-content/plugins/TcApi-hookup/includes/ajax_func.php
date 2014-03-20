<?php
function post_register_controller(){
	global $_POST;
	$url = "https://api.topcoder.com/v2/users";
	$response = wp_remote_post( $url, array(
	'method' => 'POST',
	'timeout' => 45,
	'redirection' => 5,
	'httpversion' => '1.0',
	'blocking' => true,
	'headers' => array(),
	'body' => array( 
	'firstName' => $_POST['firstName'], 
	'lastName' => $_POST['lastName'],
	'handle' => $_POST['handle'],
	'country' => $_POST['country'],
	'email' => $_POST['email'],
	'password' => $_POST['password']
	),
	'cookies' => array()
    )
);

	#print_r($response );
	$msg = json_decode($response['body']);
	$code = $response['response']['code'];
	#print_r($response['response']);
	$mm = "";
	if ( $msg->message )
	foreach ( $msg->message as $m ):
		$mm.= $m;
	endforeach;
	
	echo json_encode(array("code" => $code, "description" => $mm ));	
	exit;
	return "Error in processing request";

}
add_action ( 'wp_ajax_post_register', 'post_register_controller' );
add_action ( 'wp_ajax_nopriv_post_register', 'post_register_controller' );




function post_login_controller(){
	global $_POST;
	$url = "http://api.topcoder.com/v2/users/";
	#$url = "http://api.topcoder.com/v2/develop/challenges/30036134";
	$arg = array(
	'method' => 'POST',
	'headers' => array("Content-Type: application/json"),
	'body' => "{\n    \"firstname\" : \"".$_POST['name']."\",\n    \"lastname\" : \"Doe\",\n    \"handle\" : \"".$_POST['name']."\",\n    \"country\" : \"UK\",\n    \"email\" : \"".$_POST['password']."\",\n    \"password\" : \"HashedPassword\",\n    \"socialProvider\" : \"google\",\n    \"socialUserName\" : \"JohnsGoogleName\",\n    \"socialEmail\" : \"john@gmail.com\",\n    \"socialEmailVerified\" : \"true\"\n}"
	);
	$response = wp_remote_post ( $url, $args );
	
	// harcoded message 
	$description = 'We have sent you an email to<strong> '.$_POST['email'].'</strong> with a activation instructions.<br />If you do not receive that email within 1 hour, please email <a href="mailto:support@topcoder.com">support@topcoder.com</a>';
	echo json_encode(array('description' => $description ));
	exit;
	
	return "Error in processing request";




}
add_action ( 'wp_ajax_post_login', 'post_login_controller' );
add_action ( 'wp_ajax_nopriv_post_login', 'post_login_controller' );




function get_active_contest_ajax_controller() {
	$userkey = get_option ( 'api_user_key' );
	$contest_type = $_GET ['contest_type'];
	$page = get_query_var ( 'pages' );
	$post_per_page =  $_GET['pageSize'];
	$page = $_GET ['pageIndex'];
	$sortColumn = $_GET ['sortColumn'];
	$sortOrder = $_GET ['sortOrder'];
	
	$contest_list = get_active_contests_ajax ( $userkey, $contest_type, $page, $post_per_page, $sortColumn, $sortOrder );
	if ($contest_list->data != null) {
		echo json_encode ( $contest_list->data );
	}
	die ();
}
add_action ( 'wp_ajax_get_active_contest', 'get_active_contest_ajax_controller' );
add_action ( 'wp_ajax_nopriv_get_active_contest', 'get_active_contest_ajax_controller' );
function get_past_contest_ajax_controller() {
	$userkey = get_option ( 'api_user_key' );
	$contest_type = $_GET ['contest_type'];
	$page = get_query_var ( 'pages' );
	$post_per_page = $_GET ['pageSize'];
	$sortColumn = $_GET ['sortColumn'];
	$sortOrder = $_GET ['sortOrder'];
	
	$contest_list = get_past_contests_ajax ( $userkey, $contest_type, $page, $post_per_page, $sortColumn, $sortOrder );
	if ($contest_list->data != null) {
		echo json_encode ( $contest_list->data );
	}
	die ();
}

add_action ( 'wp_ajax_get_past_contest', 'get_past_contest_ajax_controller' );
add_action ( 'wp_ajax_nopriv_get_past_contest', 'get_past_contest_ajax_controller' );
function get_review_opportunities_ajax_controller() {
	$userkey = get_option ( 'api_user_key' );
	$contest_type = $_GET ['contest_type'];
	$page = get_query_var ( 'pages' );
	$post_per_page = get_option ( 'contest_per_page' );
	$sortColumn = $_GET ['sortColumn'];
	$sortOrder = $_GET ['sortOrder'];
	
	$contest_list = get_review_opportunities_ajax ( $userkey, $contest_type, $page, $post_per_page, $sortColumn, $sortOrder );
	if ($contest_list->data != null) {
		echo json_encode ( $contest_list->data );
	}
	die ();
}

add_action ( 'wp_ajax_get_review_opportunities', 'get_review_opportunities_ajax_controller' );
add_action ( 'wp_ajax_nopriv_get_review_opportunities', 'get_review_opportunities_ajax_controller' );
function get_member_profile_ajax_controller() {
	$userkey = get_option ( 'api_user_key' );
	$handle = $_GET ["handle"];
	
	$memberProfile = get_member_profile ( $userkey, $handle );
	if ($memberProfile != null) {
		echo json_encode ( $memberProfile );
	}
	die ();
}
add_action ( 'wp_ajax_get_member_profile', 'get_member_profile_ajax_controller' );
add_action ( 'wp_ajax_nopriv_get_member_profile', 'get_member_profile_ajax_controller' );
function get_user_achievements_ajax_controller() {
	$userkey = get_option ( 'api_user_key' );
	$handle = $_GET ["handle"];
	
	$userAchievements = get_user_achievements ( $userkey, $handle );
	if ($userAchievements != null) {
		echo json_encode ( $userAchievements );
	}
	die ();
}
add_action ( 'wp_ajax_get_user_achievement', 'get_user_achievements_ajax_controller' );
add_action ( 'wp_ajax_nopriv_get_user_achievement', 'get_user_achievements_ajax_controller' );
function get_copilot_stats_controller() {
	$userkey = get_option ( 'api_user_key' );
	$handle = $_GET ["handle"];
	
	$userAchievements = get_copilot_stats ( $userkey, $handle );
	if ($userAchievements != null) {
		echo json_encode ( $userAchievements );
	}
	die ();
}
add_action ( 'wp_ajax_get_copilot_stats', 'get_copilot_stats_controller' );
add_action ( 'wp_ajax_nopriv_get_copilot_stats', 'get_copilot_stats_controller' );


	/* challenge terms  */
	function get_challenge_terms_ajax_controller(){
			
	$challengeId = $_GET ["challengeId"];
    $role = $_GET ["role"];
    $jwtToken = $_GET ["jwtToken"];
	$challengeTerms = get_challenge_terms($challengeId, $role, $jwtToken);
    if (isset($challengeTerms)) {
        wp_send_json($challengeTerms);
    } else {
        wp_send_json_error();
    }
	}

add_action('wp_ajax_get_challenge_terms', 'get_challenge_terms_ajax_controller');
add_action('wp_ajax_nopriv_get_challenge_terms', 'get_challenge_terms_ajax_controller');



/**
 * End of ajax controller
 */

/**
 * Start of ajax functioning
 */
 
/* challenge terms  */
function get_challenge_terms($challengeId, $role, $jwtToken){
		$url = "https://api.topcoder.com/v2/terms/$challengeId?role=" . $role;
		$args = array (
				'headers' => array(
					'Authorization' => 'Bearer ' . $jwtToken
				),
				'httpversion' => get_option ( 'httpversion' ),
				'timeout' => 30
		);
		$response = wp_remote_get ( $url, $args );

		if (is_wp_error ( $response ) || ! isset ( $response ['body'] )) {
			return "Error in processing request";
		}

		if ($response ['response'] ['code'] == 200) {
			return json_decode( $response ['body']);
		}
		return "Error in processing request";
	}
 

// returns active contest list
function get_active_contests_ajax($userKey = '', $contestType = 'design', $page = 1, $post_per_page = 30, $sortColumn = '', $sortOrder = '') {
	$contestType = str_replace ( " ", "+", $contestType );
	$contestType = str_replace ( "-", "/", $contestType );
	$listType = ( $contestType == 'data/marathon' or  $contestType == 'data/srm' ) ? "active":"Open";
	// $url = "http://api.topcoder.com/rest/contests?user_key=" . $userKey . "&listType=ACTIVE&type=" . $contestType . "&pageSize=10000";	
	$url = "http://api.topcoder.com/v2/".$contestType."/challenges?listType=".$listType."&pageIndex=".$page."&pageSize=".$post_per_page;
	
	if ($contestType == "") {
		// $url = "http://api.topcoder.com/rest/contests?user_key=" . $userKey . "&listType=ACTIVE&pageSize=10000";
		//$url = "http://api.topcoder.com/v2/".$contestType."/challenges?listType=Active&pageIndex=1&pageSize=50&sortColumn=contestName&sortOrder=asc";
		$url = "http://api.topcoder.com/v2/".$contestType."/challenges?listType=".$listType."&pageIndex=".$page."&pageSize=".$post_per_page;
	}
	#echo $url;
	if ($sortOrder) {
		$url .= "&sortOrder=$sortOrder";
	}
	if ($sortColumn) {
		$url .= "&sortColumn=$sortColumn";
	}
	$args = array (
			'httpversion' => get_option ( 'httpversion' ),
			'timeout' => get_option ( 'request_timeout' )
	);
	$response = wp_remote_get ( $url, $args );
	if (is_wp_error ( $response ) || ! isset ( $response ['body'] )) {
		return "Error in processing request";
	}
	if ($response ['response'] ['code'] == 200) {

		//print $response ['body'];
		$active_contest_list = json_decode (str_replace('"items":','"data":',$response ['body'] ));
		return $active_contest_list;
	}
	
	return "Error in processing request";
}

// returns past contest list
function get_past_contests_ajax($userKey = '', $contestType = '', $page = 1, $post_per_page = 30, $sortColumn = '', $sortOrder = '') {
	$contestType = str_replace ( " ", "+", $contestType );
	$url = "http://api.topcoder.com/v2/develop/challenges?user_key=" . $userKey . "&listType=PAST&type=" . $contestType . "&pageSize=10000";
	$args = array (
			'httpversion' => get_option ( 'httpversion' ),
			'timeout' => get_option ( 'request_timeout' ) 
	);
	if ($contestType == "") {
		$url = "http://api.topcoder.com/v2/develop/challenges?user_key=" . $userKey . "&listType=PAST&pageSize=10000";
	}
	if ($sortOrder) {
		$url .= "&sortOrder=$sortOrder";
	}
	if ($sortColumn) {
		$url .= "&sortColumn=$sortColumn";
	}
	$response = wp_remote_get ( $url, $args );
	
	if (is_wp_error ( $response ) || ! isset ( $response ['body'] )) {
		return "Error in processing request";
	}
	if ($response ['response'] ['code'] == 200) {
		$active_contest_list = json_decode ( $response ['body'] );
		return $active_contest_list;
	}
	return "Error in processing request";
}

// returns review opportunities
function get_review_opportunities_ajax($userKey = '', $contestType = '', $page = 1, $post_per_page = 30, $sortColumn = '', $sortOrder = '') {
	$contestType = str_replace ( " ", "+", $contestType );
	$url = "http://api.topcoder.com/rest/reviewOpportunities?user_key=" . $userKey . "&type=" . $contestType . "&pageSize=10000";
	$args = array (
			'httpversion' => get_option ( 'httpversion' ),
			'timeout' => get_option ( 'request_timeout' ) 
	);
	if ($contestType == "") {
		$url = "http://api.topcoder.com/rest/reviewOpportunities?user_key=" . $userKey . "&pageSize=10000";
	}
	if ($sortOrder) {
		$url .= "&sortOrder=$sortOrder";
	}
	if ($sortColumn) {
		$url .= "&sortColumn=$sortColumn";
	}
	$response = wp_remote_get ( $url, $args );
	
	if (is_wp_error ( $response ) || ! isset ( $response ['body'] )) {
		return "Error in processing request";
	}
	if ($response ['response'] ['code'] == 200) {
		$active_contest_list = json_decode ( $response ['body'] );
		return $active_contest_list;
	}
	return "Error in processing request";
}

// returns member profile
function get_member_profile($userKey = '', $handle = '') {
	#echo $userKey;
	$url = "http://api.topcoder.com/rest/statistics/" . $handle . "?user_key=" . $userKey;
	$args = array (
			'httpversion' => get_option ( 'httpversion' ),
			'timeout' => get_option ( 'request_timeout' ) 
	);
	$response = wp_remote_get ( $url, $args );
	#print_r($response);
	if (is_wp_error ( $response ) || ! isset ( $response ['body'] )) {
		return "Error in processing request or Member dosen't exist";
	}
	if ($response ['response'] ['code'] == 200) {
		$coder_profile = json_decode ( $response ['body'] );
		return $coder_profile;
	}
	#print_r($response);
	return "Error in processing request";
}

// returns achievements data
function get_user_achievements($userKey = '', $handle = '') {
	$url = "http://api.topcoder.com/rest/statistics/$handle/achievements?user_key=" . $userKey;
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

// returns copilot stats
function get_copilot_stats($userKey = '', $handle = '') {
	$url = "http://api.topcoder.com/rest/statistics/copilots/$handle/contests?user_key=" . $userKey;
	$args = array (
			'httpversion' => get_option ( 'httpversion' ),
			'timeout' => get_option ( 'request_timeout' ) 
	);
	$response = wp_remote_get ( $url, $args );
	
	if (is_wp_error ( $response ) || ! isset ( $response ['body'] )) {
		return "Error in processing request or Member dosen't exist";
	}
	if ($response ['response'] ['code'] == 200) {
		$copilot_stats = json_decode ( $response ['body'] );
		return $copilot_stats;
	}
	return "Error in processing request";
}

// returns top rank
function get_top_rank($userKey = '', $contestType = 'Algorithm') {
	$contestType = str_replace ( " ", "+", $contestType );
	
	switch($contestType){
		case "develop":
			$url = "http://api.topcoder.com/v2/develop/statistics/tops/development?rankType=rank";
		break;
		case "data":
			$url = "http://api.topcoder.com/v2/data/srm/statistics/tops";
		break;	
	
	}
		
	$args = array (
			'httpversion' => get_option ( 'httpversion' ),
			'timeout' => get_option ( 'request_timeout' ) 
	);
	$response = wp_remote_get ( $url, $args );
	
	if (is_wp_error ( $response ) || ! isset ( $response ['body'] )) {
		return "Error in processing request";
	}
	if ($response ['response'] ['code'] == 200) {
		$arrTopRank = json_decode ( $response ['body'] );
		return $arrTopRank;
	}
	return "Error in processing request";
}

/**
 * End of ajax functioning
 */

/**
 * Start of load data functioning
 */
function get_contest_info($contestID = '') {
	$url = "http://api.topcoder.com/v2/software/contests/$contestID";
	$args = array (
			'httpversion' => get_option ( 'httpversion' ),
			'timeout' => get_option ( 'request_timeout' ) 
	);
	$response = wp_remote_get ( $url, $args );
	if (is_wp_error ( $response ) || ! isset ( $response ['body'] )) {
		return "Error in processing request";
	}
	if ($response ['response'] ['code'] == 200) {
		$contestObj = $response ['body'];
		return $contestObj;
	}
	return "Error in processing request";
}
 
/**
 * End of load data functioning 
 */ 

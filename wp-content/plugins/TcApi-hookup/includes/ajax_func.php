<?php

function post_register_controller() {
  global $_POST;
  $url = "https://api.topcoder.com/v2/users";
  $params = array(
    'method' => 'POST',
    'timeout' => 45,
    'redirection' => 5,
    'httpversion' => '1.0',
    'blocking' => true,
    'headers' => array(),
    'body' => array(
      'firstName' => filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING),
      'lastName' => filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRING),
      'handle' => filter_input(INPUT_POST, 'handle', FILTER_SANITIZE_STRING),
      'country' => filter_input(INPUT_POST, 'country', FILTER_SANITIZE_STRING),
      'regSource' => 'http://www.topcoder.com/',
      'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
      'utm_source' => filter_input(INPUT_POST, 'utmSource', FILTER_SANITIZE_STRING),
      'utm_medium' => filter_input(INPUT_POST, 'utmMedium', FILTER_SANITIZE_STRING),
      'utm_campaign' => filter_input(INPUT_POST, 'utmCampaign', FILTER_SANITIZE_STRING)
    ),
    'cookies' => array()
  );
  $socialProviderId = filter_input(INPUT_POST, 'socialProviderId');
  if (isset($socialProviderId)) {
    $params["body"]["socialProviderId"] = $socialProviderId;
    $params["body"]["socialProvider"] = filter_input(INPUT_POST, 'socialProvider', FILTER_SANITIZE_STRING);
    $params["body"]["socialUserName"] = filter_input(INPUT_POST, 'socialUserName', FILTER_SANITIZE_STRING);
    $params["body"]["socialUserId"] = filter_input(INPUT_POST, 'socialUserId', FILTER_SANITIZE_STRING);
    $params["body"]["socialEmail"] = filter_input(INPUT_POST, 'socialEmail', FILTER_SANITIZE_EMAIL);
    $params["body"]["socialEmailVerified"] = filter_input(INPUT_POST, 'socialEmailVerified', FILTER_SANITIZE_STRING);
  }
  else {
    $params["body"]["password"] = filter_input(INPUT_POST, 'password');
  }
  $response = wp_remote_post($url, $params);

  $msg = json_decode($response['body']);
  #browser()->log($params);
  #browser()->log($msg);
  $code = $response['response']['code'];
  #print_r($msg);
  $mm = "";
  if ($msg->error->details) {
    foreach ($msg->error->details as $m):
      $mm .= $m;
    endforeach;
  }

  wp_send_json(array("code" => $code, "description" => $mm));


}

add_action( 'wp_ajax_post_register', 'post_register_controller' );
add_action( 'wp_ajax_nopriv_post_register', 'post_register_controller' );


function post_login_controller()
{
    global $_POST;
    $url      = "https://api.topcoder.com/v2/users/";
    $arg      = array(
        'method'  => 'POST',
        'headers' => array( "Content-Type: application/json" ),
        'body'    => "{\n \"firstname\" : \"" . $_POST['name'] . "\",\n \"lastname\" : \"Doe\",\n \"handle\" : \"" . $_POST['name'] . "\",\n \"country\" : \"UK\",\n \"email\" : \"" . $_POST['password'] . "\",\n \"password\" : \"HashedPassword\",\n \"socialProvider\" : \"google\",\n \"socialUserName\" : \"JohnsGoogleName\",\n \"socialEmail\" : \"john@gmail.com\",\n \"socialEmailVerified\" : \"true\"\n}"
    );
    $response = wp_remote_post( $url, $args );

// harcoded message
    $description = 'We have sent you an email to<strong> ' . $_POST['email'] . '</strong> with a activation instructions.<br />If you do not receive that email within 1 hour, please email <a href="mailto:support@topcoder.com">support@topcoder.com</a>';
    wp_send_json( array( 'description' => $description ) );

}

add_action( 'wp_ajax_post_login', 'post_login_controller' );
add_action( 'wp_ajax_nopriv_post_login', 'post_login_controller' );

function get_member_profile_ajax_controller()
{
    $userkey = get_option( 'api_user_key' );
    $handle  = $_GET ["handle"];

    $memberProfile = get_member_profile( $handle );
    if (isset( $memberProfile )) {
        wp_send_json( $memberProfile );
    } else {
        wp_send_json_error();
    }
}

add_action( 'wp_ajax_get_member_profile', 'get_member_profile_ajax_controller' );
add_action( 'wp_ajax_nopriv_get_member_profile', 'get_member_profile_ajax_controller' );
function get_user_achievements_ajax_controller()
{
    $userkey = get_option( 'api_user_key' );
    $handle  = $_GET ["handle"];

    $userAchievements = get_user_achievements( $userkey, $handle );
    if (isset( $userAchievements )) {
        wp_send_json( $userAchievements );
    } else {
        wp_send_json_error();
    }
}

add_action( 'wp_ajax_get_user_achievement', 'get_user_achievements_ajax_controller' );
add_action( 'wp_ajax_nopriv_get_user_achievement', 'get_user_achievements_ajax_controller' );
function get_copilot_stats_controller()
{
    $userkey = get_option( 'api_user_key' );
    $handle  = $_GET ["handle"];

    $userAchievements = get_copilot_stats( $userkey, $handle );
    if (isset( $userAchievements )) {
        wp_send_json( $userAchievements );
    } else {
        wp_send_json_error();
    }
}

add_action( 'wp_ajax_get_copilot_stats', 'get_copilot_stats_controller' );
add_action( 'wp_ajax_nopriv_get_copilot_stats', 'get_copilot_stats_controller' );

/* challenge terms  */
function get_challenge_terms_ajax_controller()
{

    $challengeId = $_GET ["challengeId"];
    $role        = $_GET ["role"];
    $jwtToken    = $_GET ["jwtToken"];

    $challengeTerms = get_challenge_terms( $challengeId, $role, $jwtToken );
    if (isset( $challengeTerms )) {
        wp_send_json( $challengeTerms );
    } else {
        wp_send_json_error();
    }
}

add_action( 'wp_ajax_get_challenge_terms', 'get_challenge_terms_ajax_controller' );
add_action( 'wp_ajax_nopriv_get_challenge_terms', 'get_challenge_terms_ajax_controller' );

/* challenge term details  */
function get_challenge_term_details_ajax_controller()
{

    $termId   = $_GET ["termId"];
    $jwtToken = $_GET ["jwtToken"];

    $termDetails = get_challenge_term_details( $termId, $jwtToken );
    if (isset( $termDetails )) {
        wp_send_json( $termDetails );
    } else {
        wp_send_json_error();
    }
}

add_action( 'wp_ajax_get_challenge_term_details', 'get_challenge_term_details_ajax_controller' );
add_action( 'wp_ajax_nopriv_get_challenge_term_details', 'get_challenge_term_details_ajax_controller' );


/* challenge term details  */
function agree_challenge_terms_ajax_controller()
{

    $termId   = $_GET ["termId"];
    $jwtToken = $_GET ["jwtToken"];

    $termDetails = agree_challenge_terms( $termId, $jwtToken );
    if (isset( $termDetails )) {
        wp_send_json( $termDetails );
    } else {
        wp_send_json_error();
    }
}

add_action( 'wp_ajax_agree_challenge_terms', 'agree_challenge_terms_ajax_controller' );
add_action( 'wp_ajax_nopriv_agree_challenge_terms', 'agree_challenge_terms_ajax_controller' );

/* register to challenge */
function register_to_challenge_ajax_controller()
{

    $challengeId = filter_input( INPUT_GET, "challengeId", FILTER_SANITIZE_NUMBER_INT );
    $jwtToken    = filter_input( INPUT_GET, "jwtToken", FILTER_SANITIZE_STRING );

    $challengeReg = register_to_challenge( $challengeId, $jwtToken );
    if (isset( $challengeReg )) {
        wp_send_json( $challengeReg );
    } else {
        wp_send_json_error();
    }
}

add_action( 'wp_ajax_register_to_challenge', 'register_to_challenge_ajax_controller' );
add_action( 'wp_ajax_nopriv_register_to_challenge', 'register_to_challenge_ajax_controller' );

/* submit to development challenge */
function submit_to_dev_challenge_ajax_controller()
{

    $challengeId = filter_input( INPUT_POST, "challengeId", FILTER_SANITIZE_NUMBER_INT );
    $fileName    = filter_input( INPUT_POST, "fileName", FILTER_SANITIZE_STRING );
    $fileData    = filter_input( INPUT_POST, "fileData", FILTER_UNSAFE_RAW );
    $jwtToken    = filter_input( INPUT_POST, "jwtToken", FILTER_SANITIZE_STRING );

    $submitToDevChallengeResponse = submit_to_dev_challenge( $challengeId, $fileName, $fileData, $jwtToken );
    if (isset( $submitToDevChallengeResponse )) {
        wp_send_json( $submitToDevChallengeResponse );
    } else {
        wp_send_json_error();
    }
}

add_action( 'wp_ajax_submit_to_dev_challenge', 'submit_to_dev_challenge_ajax_controller' );
add_action( 'wp_ajax_nopriv_submit_to_dev_challenge', 'submit_to_dev_challenge_ajax_controller' );

/**
 * End of ajax controller
 */

/**
 * Start of ajax functioning
 */

// returns member profile
function get_member_profile( $handle = '' )
{
    $url      = "http://api.topcoder.com/v2/users/" . $handle;
    $args     = array(
        'httpversion' => get_option( 'httpversion' ),
        'timeout'     => get_option( 'request_timeout' )
    );
    $response = wp_remote_get( $url, $args );

    if (is_wp_error( $response ) || ! isset ( $response ['body'] )) {
        return "Error in processing request or Member dosen't exist";
    }
    if ($response ['response'] ['code'] == 200) {
        $coder_profile = json_decode( $response ['body'] );
        return $coder_profile;
    }

    return "Error in processing request";
}

// returns achievements data
function get_user_achievements( $userKey = '', $handle = '' )
{
    $url      = "https://api.topcoder.com/rest/statistics/$handle/achievements?user_key=" . $userKey;
    $args     = array(
        'httpversion' => get_option( 'httpversion' ),
        'timeout'     => get_option( 'request_timeout' )
    );
    $response = wp_remote_get( $url, $args );

    if (is_wp_error( $response ) || ! isset ( $response ['body'] )) {
        return "Error in processing request or Member dosen't exist";
    }
    if ($response ['response'] ['code'] == 200) {
        $coder_achievements = json_decode( $response ['body'] );
        return $coder_achievements;
    }
    return "Error in processing request";
}

// returns copilot stats
function get_copilot_stats( $userKey = '', $handle = '' )
{
    $url      = "https://api.topcoder.com/rest/statistics/copilots/$handle/contests?user_key=" . $userKey;
    $args     = array(
        'httpversion' => get_option( 'httpversion' ),
        'timeout'     => get_option( 'request_timeout' )
    );
    $response = wp_remote_get( $url, $args );

    if (is_wp_error( $response ) || ! isset ( $response ['body'] )) {
        return "Error in processing request or Member dosen't exist";
    }
    if ($response ['response'] ['code'] == 200) {
        $copilot_stats = json_decode( $response ['body'] );
        return $copilot_stats;
    }
    return "Error in processing request";
}

// returns top rank
function get_top_rank( $userKey = '', $contestType = 'Algorithm' )
{
    $contestType = str_replace( " ", "+", $contestType );

    switch ($contestType) {
        case "develop":
            $url = "https://api.topcoder.com/v2/develop/statistics/tops/development?rankType=rank";
            break;
        case "data":
            $url = "https://api.topcoder.com/v2/data/srm/statistics/tops";
            break;

    }

    $args     = array(
        'httpversion' => get_option( 'httpversion' ),
        'timeout'     => get_option( 'request_timeout' )
    );
    $response = wp_remote_get( $url, $args );

    if (is_wp_error( $response ) || ! isset ( $response ['body'] )) {
        return "Error in processing request";
    }
    if ($response ['response'] ['code'] == 200) {
        $arrTopRank = json_decode( $response ['body'] );
        return $arrTopRank;
    }
    return "Error in processing request";
}

/* challenge terms  */
function get_challenge_terms( $challengeId, $role, $jwtToken )
{
    $url      = "https://api.topcoder.com/v2/terms/$challengeId?role=" . $role;
    $args     = array(
        'headers'     => array(
            'Authorization' => 'Bearer ' . $jwtToken
        ),
        'httpversion' => get_option( 'httpversion' ),
        'timeout'     => 20
    );
    $response = wp_remote_get( $url, $args );

    if (is_wp_error( $response ) || ! isset ( $response ['body'] )) {
        return "Error in processing request";
    }
    return json_decode( $response ['body'] );
}

/* challenge term details  */
function get_challenge_term_details( $termId, $jwtToken )
{
    $url      = "https://api.topcoder.com/v2/terms/detail/" . $termId;
    $args     = array(
        'headers'     => array(
            'Authorization' => 'Bearer ' . $jwtToken
        ),
        'httpversion' => get_option( 'httpversion' ),
        'timeout'     => 20
    );
    $response = wp_remote_get( $url, $args );

    if (is_wp_error( $response ) || ! isset ( $response ['body'] )) {
        return "Error in processing request";
    }
    return json_decode( $response ['body'] );
}

/* register to challenge */
function register_to_challenge( $challengeId, $jwtToken )
{
    $url      = "https://api.topcoder.com/v2/challenges/$challengeId/register";
    $args     = array(
        'headers'     => array(
            'Authorization' => 'Bearer ' . $jwtToken
        ),
        'httpversion' => get_option( 'httpversion' ),
        'timeout'     => 20
    );
    $response = wp_remote_post( $url, $args );

    if (is_wp_error( $response ) || ! isset ( $response ['body'] )) {
        return "Error in processing request";
    }
    return json_decode( $response ['body'] );
}

/* submit to development challenge */
function submit_to_dev_challenge( $challengeId, $fileName, $fileData, $jwtToken )
{
    $url      = "https://api.topcoder.com/v2/develop/challenges/$challengeId/submit";
    $body     = array(
        'fileName' => $fileName,
        'fileData' => $fileData
    );
    $args     = array(
        'body'        => $body,
        'headers'     => array(
            'Authorization' => 'Bearer ' . $jwtToken
        ),
        'httpversion' => get_option( 'httpversion' ),
        'timeout'     => 600
    );
    $response = wp_remote_post( $url, $args );

    if (is_wp_error( $response ) || ! isset ( $response ['body'] )) {
        return "Error in processing request";
    }
    return json_decode( $response ['body'] );
}

/* agree challenge terms  */
function agree_challenge_terms( $termId, $jwtToken )
{
    $url      = "https://api.topcoder.com/v2/terms/" . $termId . "/agree";
    $args     = array(
        'headers'     => array(
            'Authorization' => 'Bearer ' . $jwtToken
        ),
        'httpversion' => get_option( 'httpversion' ),
        'timeout'     => 20
    );
    $response = wp_remote_post( $url, $args );

    if (is_wp_error( $response ) || ! isset ( $response ['body'] )) {
        return "Error in processing request";
    }
    return json_decode( $response ['body'] );
}


/**
 * End of ajax functioning
 */

/**
 * Start of load data functioning
 */
function get_contest_info( $contestID = '' )
{
    $url      = "https://api.topcoder.com/v2/software/contests/$contestID";
    $args     = array(
        'httpversion' => get_option( 'httpversion' ),
        'timeout'     => get_option( 'request_timeout' )
    );
    $response = wp_remote_get( $url, $args );
    if (is_wp_error( $response ) || ! isset ( $response ['body'] )) {
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

/**
 * For backwards compatility
 *
 * @param string $userKey
 * @param string $contestType
 * @param int $page
 * @param int $post_per_page
 * @param string $sortColumn
 * @param string $sortOrder
 */
function get_active_contests_ajax($userKey = '', $contestType = 'design', $page = 1, $post_per_page = 30, $sortColumn = '', $sortOrder = '') {
  return get_challenges_ajax('Active', $contestType, $page, $post_per_page, $sortOrder, $sortColumn);
}

/**
 * Challenges changes from "TopCoder Website - Challenges Pages - Wordpress Theme Build" Contest
 */
add_action('wp_ajax_get_challenges', 'get_challenges_ajax_controller');
add_action('wp_ajax_nopriv_get_challenges', 'get_challenges_ajax_controller');
function get_challenges_ajax_controller() {
  $contest_type = $_GET ['contest_type'];
  $page = $_GET['pageIndex'];
  $listType = $_GET['listType'];
  $post_per_page = $_GET ['pageSize'];
  $sortColumn = $_GET ['sortColumn'];
  $sortOrder = $_GET ['sortOrder'];
  $challengeType = urlencode($_GET ['challengeType']);
  $startDate = $_GET ['submissionEndFrom'];
  $endDate = $_GET ['submissionEndTo'];

    $contest_list = get_challenges_ajax(
        $listType,
        $contest_type,
        $page,
        $post_per_page,
        $sortColumn,
        $sortOrder,
        $challengeType,
        $startDate,
        $endDate
    );

    if (isset( $contest_list->data )) {
        wp_send_json( $contest_list );
    } else {
        wp_send_json_error();
    }
}

function get_challenges_ajax(
  $listType = 'Active',
  $contestType = 'design',
  $page = 1,
  $post_per_page = 30,
  $sortColumn = "",
  $sortOrder = '',
  $challengeType = '',
  $startDate = '',
  $endDate = ''
) {

  $url = "https://api.topcoder.com/v2/" . $contestType . "/challenges?listType=" . $listType . "&pageIndex=" . $page . "&pageSize=" . $post_per_page;

  // set default value since failed using params;
  // @TODO update to be a little better
  if ($contestType !== 'data/marathon') {
    $sortColumn = ($sortColumn == '') ? "submissionEndDate" : $sortColumn;
    $sortOrder = ($sortOrder == '') ? "desc" : $sortOrder;
  }

  if ($sortOrder) {
    $url .= "&sortOrder=$sortOrder";
  }
  if ($sortColumn) {
    $url .= "&sortColumn=$sortColumn";
  }
  if ($challengeType) {
    $url .= "&challengeType=$challengeType";
  }
  if ($startDate) {
    $url .= "&submissionEndFrom=$startDate";
  }
  if ($endDate) {
    $url .= "&submissionEndTo=$endDate";
  }

    $args     = array(
        'httpversion' => get_option( 'httpversion' ),
        'timeout'     => get_option( 'request_timeout' )
    );
    $response = wp_remote_get( $url, $args );

    if (is_wp_error( $response ) || ! isset ( $response ['body'] )) {
        return "Error in processing request";
    }
    if ($response ['response'] ['code'] == 200) {

        $active_contest_list = json_decode( $response['body'] );
        return $active_contest_list;
    }

    return "Error in processing request";
}


/**
 * Review opportunities changes from "TopCoder Website - Challenges Pages - Wordpress Theme Build" Contest
 */

add_action( 'wp_ajax_get_review_opportunities', 'get_review_opportunities_ajax_controller' );
add_action( 'wp_ajax_nopriv_get_review_opportunities', 'get_review_opportunities_ajax_controller' );
function get_review_opportunities_ajax_controller()
{
    $userkey       = get_option( 'api_user_key' );
    $contest_type  = $_GET ['contest_type'];
    $page          = $_GET['pageIndex'];
    $listType      = $_GET['listType'];
    $post_per_page = $_GET ['pageSize'];
    $sortColumn    = $_GET ['sortColumn'];
    $sortOrder     = $_GET ['sortOrder'];
    $challengeType = urlencode( $_GET ['challengeType'] );

    $contest_list = get_review_opportunities_ajax(
        $listType,
        $contest_type,
        $page,
        $post_per_page,
        $sortColumn,
        $sortOrder,
        $challengeType
    );
    if (isset( $contest_list->data )) {
        wp_send_json_success( $contest_list );
    } else {
        wp_send_json_error();
    }
}

function get_review_opportunities_ajax(
    $listType = 'Active',
    $contestType = 'design',
    $page = 1,
    $post_per_page = 30,
    $sortColumn = '',
    $sortOrder = '',
    $challengeType = ''
) {

    $url = "http://api.topcoder.com/v2/" . $contestType . "/reviewOpportunities?listType=" . $listType . "&pageIndex=" . $page . "&pageSize=" . $post_per_page;

    if ($contestType == "") {
        $url = "http://api.topcoder.com/v2/" . $contestType . "/reviewOpportunities?listType=" . $listType . "&pageIndex=" . $page . "&pageSize=" . $post_per_page;
    }
    //echo $url;
    if ($sortOrder) {
        $url .= "&sortOrder=$sortOrder";
    }
    if ($sortColumn) {
        $url .= "&sortColumn=$sortColumn";
    }
    if ($challengeType) {
        $url .= "&challengeType=$challengeType";
    }
    $args     = array(
        'httpversion' => get_option( 'httpversion' ),
        'timeout'     => get_option( 'request_timeout' )
    );
    $response = wp_remote_get( $url, $args );

    if (is_wp_error( $response ) || ! isset ( $response ['body'] )) {
        return "Error in processing request";
    }
    if ($response ['response'] ['code'] == 200) {

//print $response ['body'];
    $active_contest_list = json_decode($response['body']);
    return $active_contest_list;
  }

  return "Error in processing request";
}

/*
* Review Opportunities Detail API
*/
add_action( 'wp_ajax_get_review_detail', 'get_review_detail_controller' );
add_action( 'wp_ajax_nopriv_get_review_detail', 'get_review_detail_controller' );

function get_review_detail_controller()
{
    $jwtToken    = filter_input( INPUT_GET, "jwtToken", FILTER_SANITIZE_STRING );
    $challengeId = filter_input( INPUT_GET, "challengeId", FILTER_SANITIZE_STRING );
    $challengeType = filter_input( INPUT_GET, "challengeType", FILTER_SANITIZE_STRING );
    $userkey = get_option( 'api_user_key' );

    $review_detail = get_review_detail_ajax($userKey, $challengeId, $challengeType, FALSE, $jwtToken);

    if (isset( $review_detail->Phases ) || isset( $review_detail->phases )) {
        wp_send_json_success( $review_detail );
    } else {
        wp_send_json_error();
    }
}

function get_review_detail_ajax($userKey = '', $contestID = '', $contestType = '', $resetCache = FALSE, $jwtToken = '') {

    $url = "http://api.topcoder.com/v2/$contestType/reviewOpportunities/$contestID";

    if ($resetCache) {
        $url .= "?refresh=t";
    }
    $args     = array(
        'headers'     => array(
        'Authorization' => 'Bearer ' . $jwtToken
    ),
        'httpversion' => get_option( 'httpversion'  ),
        'timeout'     => get_option('request_timeout')
    );
    $response = wp_remote_get($url, $args);
    if (is_wp_error($response) || !isset ( $response ['body'] )) {
        return "Error in processing request";
    }
    if ($response ['response'] ['code'] == 200) {
        $review_result = json_decode($response ['body']);
        return $review_result;
    }
    return "Error in processing request";
}
/**
 * Get user id from handle
 */

add_action( 'wp_ajax_get_member_id', 'get_member_id_ajax_controller' );
add_action( 'wp_ajax_nopriv_get_member_id', 'get_member_id_ajax_controller' );
function get_member_id_ajax_controller()
{

    $handle  = $_GET ['handle'];
    $page          = $_GET['pageIndex'];
    $post_per_page = $_GET ['pageSize'];
    $case_sensitive    = $_GET ['case'];

    $id_list = get_member_id_ajax(
        $handle,
        $page,
        $post_per_page,
        $case_sensitive
    );
    if (isset( $id_list->users )) {
        wp_send_json_success( $id_list );
    } else {
        wp_send_json_error($id_list);
    }
}

function get_member_id_ajax(
    $handle,
    $page = '',
    $post_per_page = '',
    $case_sensitive = 'true'
) {

    $url = "http://api.topcoder.com/v2/users/search?handle=" . $handle;

    if (!empty($page)) {
        $url .= "&pageIndex=$page";
    }
    if (!empty($post_per_page)) {
        $url .= "&pageSize=$post_per_page";
    }
        $url .= "&caseSensitive=$case_sensitive";

    $args     = array(
        'httpversion' => get_option( 'httpversion' ),
        'timeout'     => get_option( 'request_timeout' )
    );
    $response = wp_remote_get( $url, $args );

    if (is_wp_error( $response ) || ! isset ( $response ['body'] )) {
        return "Error in processing request";
    }
    if ($response ['response'] ['code'] == 200) {
        $user_id_list = json_decode($response['body']);
        return $user_id_list;
  }
  return "Error in processing request";
}

/*
 * Check handle availability and validity
 */
add_action( 'wp_ajax_get_handle_validity', 'get_handle_validity_controller' );
add_action( 'wp_ajax_nopriv_get_handle_validity', 'get_handle_validity_controller' );

function get_handle_validity_controller()
{
    $userkey = get_option( 'api_user_key' );
    $handle  = $_GET ['handle'];

    $handle_validity = get_handle_validity_ajax( $handle );

    if (isset( $handle_validity->valid ) || isset( $handle_validity->error )) {
        wp_send_json( $handle_validity );
    } else {
        wp_send_json_error();
    }
}

function get_handle_validity_ajax(
    $handle = ''
) {

    $url = "http://api.topcoder.com/v2/users/validate/" . $handle;

    $args     = array(
        'httpversion' => get_option( 'httpversion' ),
        'timeout'     => get_option( 'request_timeout' )
    );
    $response = wp_remote_get( $url, $args );

    if (is_wp_error( $response ) || ! isset ( $response ['body'] )) {
        $handle_validity = json_decode( $response['body'] );
        return $handle_validity;
    }
    if ($response ['response'] ['code'] == 200) {

//print $response ['body'];
        $handle_validity = json_decode( $response['body'] );
        return $handle_validity;
    }

    $handle_validity = json_decode( $response['body'] );
    return $handle_validity;
}

/*
 * Check email availability and validity
 */
add_action( 'wp_ajax_get_email_validity', 'get_email_validity_controller' );
add_action( 'wp_ajax_nopriv_get_email_validity', 'get_email_validity_controller' );

function get_email_validity_controller()
{
    $userkey = get_option( 'api_user_key' );
    $email   = $_GET ['email'];

    $email_validity = get_email_validity_ajax( $email );

    if (isset( $email_validity->available ) || isset( $email_validity->error )) {
        wp_send_json( $email_validity );
    } else {
        wp_send_json_error();
    }
}

function get_email_validity_ajax(
    $email = ''
) {

    $url = "http://api.topcoder.com/v2/users/validateEmail?email=" . $email;

    $args     = array(
        'httpversion' => get_option( 'httpversion' ),
        'timeout'     => get_option( 'request_timeout' )
    );
    $response = wp_remote_get( $url, $args );

    if (is_wp_error( $response ) || ! isset ( $response ['body'] )) {
        $email_validity = json_decode( $response['body'] );
        return $email_validity;
    }
    if ($response ['response'] ['code'] == 200) {

//print $response ['body'];
        $email_validity = json_decode( $response['body'] );
        return $email_validity;
    }

    $email_validity = json_decode( $response['body'] );
    return $email_validity;
}

/*
 * Check social availability and validity
 */
add_action( 'wp_ajax_get_social_validity', 'get_social_validity_controller' );
add_action( 'wp_ajax_nopriv_get_social_validity', 'get_social_validity_controller' );

function get_social_validity_controller()
{
    $userkey  = get_option( 'api_user_key' );
    $provider = $_GET ['provider'];
    $user     = $_GET ['user'];

    $social_validity = get_social_validity_ajax( $provider, $user );

    if (isset( $social_validity->available ) || isset( $social_validity->error )) {
        wp_send_json( $social_validity );
    } else {
        wp_send_json_error();
    }
}

function get_social_validity_ajax(
    $provider = '',
    $user = ''
) {

    $url = "http://api.topcoder.com/v2/users/validateSocial?socialProviderId=" . $provider . "&socialUserId=" . $user;

    $args     = array(
        'httpversion' => get_option( 'httpversion' ),
        'timeout'     => get_option( 'request_timeout' )
    );
    $response = wp_remote_get( $url, $args );

    if (is_wp_error( $response ) || ! isset ( $response ['body'] )) {
        $social_validity = json_decode( $response['body'] );
        return $social_validity;
    }
    if ($response ['response'] ['code'] == 200) {

//print $response ['body'];
        $social_validity = json_decode( $response['body'] );
        return $social_validity;
    }

    $social_validity = json_decode( $response['body'] );
    return $social_validity;
}

/**
 * Get challenges to be used in rss
 *
 * @param $listType
 * @param $challengeType
 *
 * @return array|mixed|string
 */
function get_contests_rss($listType, $challengeType)
{
    $url = "http://api.topcoder.com/v2/challenges/rss?listType={$listType}&challengeType={$challengeType}";

    $args     = array(
        'httpversion' => get_option( 'httpversion' ),
        'timeout'     => get_option( 'request_timeout' )
    );

    $response = wp_remote_get( $url, $args );

    if (is_wp_error( $response ) || ! isset ( $response ['body'] )) {
        return "Error in processing request";
    }

    if ($response ['response'] ['code'] == 200) {
        $active_contest_list = json_decode( $response['body'] );
        return $active_contest_list;
    }

    return "Error in processing request";
}
// Forgot Password
function generateResetToken($handle = '') {
    $url = "http://api.topcoder.com/v2/users/resetToken/";

    if(filter_var($handle, FILTER_VALIDATE_EMAIL)) {
        //input handle is email
        $url .= "?email=".$handle;
    }
    else {
        //input handle is handle
        $url .= "?handle=".$handle;
    }
    $args = array(
        'httpversion' => get_option('httpversion'),
        'timeout' => get_option('request_timeout')
    );
    $response = wp_remote_get($url, $args);

    if (is_wp_error($response) || !isset ($response ['body'])) {
        return "error";
    }

    return $response;

}

function changePassword($handle = '', $password = '' , $unlockCode = '') {

    $url = "http://api.topcoder.com/v2/users/resetPassword/" . $handle;

    $arrParam = array('handle' => $handle, 'password' => $password, 'token' => $unlockCode );
    $args = array(
        'httpversion' => get_option('httpversion'),
        'timeout' => get_option('request_timeout'),
        'body'=>$arrParam
    );
    $response = wp_remote_post($url, $args);

    if (is_wp_error($response) || !isset ($response ['body'])) {
        return "error";
    }
    return $response;
}

add_action( 'wp_ajax_get_challenge_documents', 'get_challenge_documents_controller' );
add_action( 'wp_ajax_nopriv_get_challenge_documents', 'get_challenge_documents_controller' );

function get_challenge_documents_controller()
{
    $jwtToken    = filter_input( INPUT_GET, "jwtToken", FILTER_SANITIZE_STRING );
    $challengeId = filter_input( INPUT_GET, "challengeId", FILTER_SANITIZE_STRING );
    $challengeType = filter_input( INPUT_GET, "challengeType", FILTER_SANITIZE_STRING );
    $resetCache = filter_input(INPUT_GET, 'nocache', FILTER_SANITIZE_STRING);

    $docs = get_challenge_documents_ajax($challengeId, $challengeType, $resetCache, $jwtToken);

    if ($docs !== "Error in processing request") {
      wp_send_json( $docs );
    } else {
      wp_send_json_error();
    }
}

function get_challenge_documents_ajax($contestID = '', $contestType = '', $resetCache = FALSE, $jwtToken = '') {

  // This IF isn't working. It's not getting the contestType var. We need to call the design vs. develop api based on the contest type.
  #echo "	contest type ".$contestType;
  $url = "https://api.topcoder.com/v2/$contestType/challenges/$contestID";

  if ($resetCache) {
    $url .= "?refresh=t";
  }

  $args     = array(
    'headers'     => array(
      'Authorization' => 'Bearer ' . $jwtToken
    ),
    'httpversion' => get_option( 'httpversion'  ),
    'timeout'     => get_option('request_timeout')
  );
  $response = wp_remote_get($url, $args);
  if (is_wp_error($response) || !isset ( $response ['body'] )) {
    return "Error in processing request";
  }
  if ($response ['response'] ['code'] == 200) {
    $search_result = json_decode($response ['body']);
    return $search_result;
  }
  return "Error in processing request";
}


// get all supported platforms and technologies
function get_all_platforms_and_technologies_ajax_controller()
{
    $list = get_all_platforms_and_technologies_ajax();
    if ($list !== "Error in processing request") {
        wp_send_json( $list );
    } else {
        wp_send_json_error();
    }
}

add_action( 'wp_ajax_get_all_platforms_and_technologies', 'get_all_platforms_and_technologies_ajax_controller' );
add_action( 'wp_ajax_nopriv_get_all_platforms_and_technologies', 'get_all_platforms_and_technologies_ajax_controller' );

function get_all_platforms_and_technologies_ajax() {
    $pUrl = "http://api.topcoder.com/v2/data/platforms";
    $tUrl = "http://api.topcoder.com/v2/data/technologies";
    $args = array (
        'httpversion' => get_option ( 'httpversion' ),
        'timeout' => get_option ( 'request_timeout' )
    );

    // get all platforms and technologies
    $pResponse = wp_remote_get($pUrl, $args);
    $tResponse = wp_remote_get($tUrl, $args);

    if (is_wp_error ($pResponse ) || ! isset ($pResponse ['body'] ) || is_wp_error ($tResponse) || ! isset ($tResponse ['body'] )) {
        return "Error in processing request";
    }

    if ($pResponse ['response'] ['code'] == 200 && $tResponse ['response'] ['code'] == 200) {
        $pList = json_decode ( $pResponse ['body'], true);
        $tList = json_decode ( $tResponse ['body'], true);
        $all_list = array_merge_recursive($pList, $tList);
        return $all_list;
    }
    return "Error in processing request";
}

/**
 * Legacy code only for backward compatibility
 */


function get_active_contest_ajax_controller()
{
  $userkey       = get_option( 'api_user_key' );
  $contest_type  = $_GET ['contest_type'];
  $page          = get_query_var( 'pages' );
  $post_per_page = $_GET['pageSize'];
  $page          = $_GET ['pageIndex'];
  $sortColumn    = $_GET ['sortColumn'];
  $sortOrder     = $_GET ['sortOrder'];

  $contest_list = get_active_contests_ajax( $userkey, $contest_type, $page, $post_per_page, $sortColumn, $sortOrder );
  if (isset( $contest_list->data )) {
    wp_send_json( $contest_list->data );
  } else {
    wp_send_json_error();
  }
}
add_action('wp_ajax_get_upcoming_contest', 'get_upcoming_contest_ajax_controller');
add_action('wp_ajax_nopriv_get_upcoming_contest', 'get_upcoming_contest_ajax_controller');
function get_upcoming_contest_ajax_controller() {
  $userkey = get_option('api_user_key');
  $contest_type = $_GET ['contest_type'];
  $page = get_query_var('pages');
  $post_per_page = $_GET['pageSize'];
  $page = $_GET ['pageIndex'];
  $sortColumn = $_GET ['sortColumn'];
  $sortOrder = $_GET ['sortOrder'];

  $contest_list = get_upcoming_contests_ajax($userkey, $contest_type, $page, $post_per_page, $sortColumn, $sortOrder);
  if (isset($contest_list->data)) {
    wp_send_json($contest_list->data);
  }
  else {
    wp_send_json_error();
  }
}

add_action('wp_ajax_get_active_contest', 'get_active_contest_ajax_controller');
add_action('wp_ajax_nopriv_get_active_contest', 'get_active_contest_ajax_controller');
function get_past_contest_ajax_controller() {
  $userkey = get_option('api_user_key');
  $contest_type = $_GET ['contest_type'];
  $page = get_query_var('pages');
  $post_per_page = $_GET ['pageSize'];
  $sortColumn = $_GET ['sortColumn'];
  $sortOrder = $_GET ['sortOrder'];

  $contest_list = get_past_contests_ajax($userkey, $contest_type, $page, $post_per_page, $sortColumn, $sortOrder);
  if (isset($contest_list->data)) {
    wp_send_json($contest_list->data);
  }
  else {
    wp_send_json_error();
  }
}

add_action( 'wp_ajax_get_past_contest', 'get_past_contest_ajax_controller' );
add_action( 'wp_ajax_nopriv_get_past_contest', 'get_past_contest_ajax_controller' );

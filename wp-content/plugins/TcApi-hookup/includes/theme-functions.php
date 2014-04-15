<?php
/* TC API functions for TC theme*/

function get_contest_type($userKey = ''){
	global $TCHOOK_plugin;
	return $TCHOOK_plugin-> get_contest_type( $userKey );
}

function get_active_contests($userKey = '', $contestType = '', $page = 1, $post_per_page = 30){
	global $TCHOOK_plugin;
	return $TCHOOK_plugin-> get_active_contests($userKey, $contestType, $page, $post_per_page);
}

function get_past_contests($userKey = '', $contestType = '', $page = 1, $post_per_page = 30){
	global $TCHOOK_plugin;
	return $TCHOOK_plugin-> get_past_contests($userKey, $contestType, $page, $post_per_page);
}

function search_contest($userKey = '', $keyword = ''){
	global $TCHOOK_plugin;
	return $TCHOOK_plugin-> search_contest($userKey, $keyword);
}

function get_contest_detail($userKey = '', $contestID = '',$contestType='', $nocache = false){
	global $TCHOOK_plugin;
	return $TCHOOK_plugin-> get_contest_detail($userKey, $contestID, $contestType, $nocache);
}

function get_contest_results($contestID = '', $contestType=''){
	global $TCHOOK_plugin;
	return $TCHOOK_plugin-> get_contest_results($contestID, $contestType);
}

function get_contest_checkpoint_detail($contestID = '', $contestType=''){
	global $TCHOOK_plugin;
	return $TCHOOK_plugin-> get_contest_checkpoint_detail($contestID, $contestType);
}

function get_raw_coder($handle, $data){
	global $TCHOOK_plugin;
	return $TCHOOK_plugin->tcapi_get_raw_coder($handle, $data);
}

function get_handle($handle = ''){
	global $TCHOOK_plugin;
	return $TCHOOK_plugin-> tcapi_get_coder('',$handle);
}

function get_activity_summary($key=''){
	global $TCHOOK_plugin;
	return $TCHOOK_plugin-> tcapi_get_activitySummary('',$key);
}

function get_member_statistics($handle, $track){
	global $TCHOOK_plugin;
	return $TCHOOK_plugin->tcapi_get_member_stats($handle, $track);
}

function get_member_chart_statistics($handle, $track, $contestType){
	global $TCHOOK_plugin;
	return $TCHOOK_plugin->tcapi_get_member_chart_stats($handle, $track, $contestType);
}

function get_member_achievements($handle){
	global $TCHOOK_plugin;
	return $TCHOOK_plugin->tcapi_get_member_achievements($handle);
}

function get_forum_posts(){
	global $TCHOOK_plugin;
	return $TCHOOK_plugin->tcapi_get_forum_posts();
}

function get_json_from_url( $url ){
	global $TCHOOK_plugin;
	return $TCHOOK_plugin->get_json_from_url( $url );
}
function get_stat_design_recentwins ( $url ){
	global $TCHOOK_plugin;
	return $TCHOOK_plugin->tcapi_get_stat_design_recentwins( $url );
}

function get_checkpoint_details( $contestId, $challengeType ){
	global $TCHOOK_plugin;
	return $TCHOOK_plugin->get_checkpoint_details_fn( $contestId, $challengeType );
}



function get_member_achievements_current($userId, $badgeId){
	global $TCHOOK_plugin;
	return $TCHOOK_plugin->tcapi_get_member_achievements_current($userId, $badgeId);
}

function search_users($handle){
	global $TCHOOK_plugin;
	return $TCHOOK_plugin->tcapi_search_users($handle);
}

/**
 * Is a user registred for a challenge
 *
 * @param $handle
 * @param $challenge
 *
 * @return bool
 */
function is_user_register_for_challenge($handle, $challenge) {
  if ($handle && isset($challenge->registrants)) {
    foreach ($challenge->registrants as $registrant) {
      if ($handle == $registrant->handle) {
        return true;
      }
    }
  }

  return false;
}

<?php

function active_contests_function($atts, $content = null) {
	extract(shortcode_atts(array("type" => '',"id"=>'activeContest',"post_per_page" => 5), 
			$atts));
	$content = $content == null ? "Active Contest" : $content;		
	$contest_type = $type;
	$postPerPage = $post_per_page;
	
	$ret = '';
	$ret .=	'	<!-- tc_contest -->';
	$ret .=	'		<h2>'.$content.'</h2>';
	$ret .=	'		<script>';
	$ret .=	'			var siteurl = "'.get_bloginfo('siteurl').'";';
	$ret .=	'			var ajaxUrl = "'.get_bloginfo('wpurl').'/wp-admin/admin-ajax.php";';
	$ret .=	'			var activePastContest = "active";';
	$ret .=	'			$(document).ready(function() {';
	$ret .=	'				listActiveContest("'.$id.'","activeContest","'.$contest_type.'");';
	$ret .=	'			});';
	$ret .=	'		</script>';
	$ret .=	'		<div id="'.$id.'" class="tc_contest">';
	$ret .=	'			<input type="hidden" class="contestType" value="activeContest"></input>';
	$ret .=	'			<input type="hidden" class="postPerPage" value="'.$postPerPage.'"></input>';
	$ret .=	'			<div class="pagingWrapper">';
	$ret .=	'			</div>';
	$ret .=	'			<table class="contestTable">';
	$ret .=	'				<colgroup>';
	$ret .=	'					<col width="315">';
	$ret .=	'					<col width="140">';
	$ret .=	'					<col width="71">';
	$ret .=	'				</colgroup>';
	$ret .=	'				<thead>';
	$ret .=	'					<tr class="head">';
	$ret .=	'						<td height="17">Contest</td>';
	$ret .=	'						<td>Contest Type</td>';
	$ret .=	'						<td align="center">First Prize</td>';
	$ret .=	'						<td align="center">End</td>';
	$ret .=	'					</tr>';
	$ret .=	'				</thead>';
	$ret .=	'				<tbody>';
	$ret .=	'				</tbody>';
	$ret .=	'			</table>';
	$ret .=	'			<div class="overlayWrapper">
							<div class="loadingOverlay"><div class="loadingGif"></div></div>
						</div>';
	$ret .=	'		</div>';
	$ret .=	'		<!-- /.tc_contest -->';
	
	return $ret;
}

add_shortcode("active_contests", "active_contests_function"); 

function past_contests_function($atts, $content = null) {
	extract(shortcode_atts(array("type" => '',"id"=>'pastContest',"post_per_page" => 5), 
			$atts));
	$content = $content == null ? "Past Contest" : $content;				
	$contest_type = $type;
	$postPerPage = $post_per_page;
	
	$ret = '';
	$ret .=	'	<!-- tc_contest -->';
	$ret .=	'		<h2>'.$content.'</h2>';
	$ret .=	'		<script>';
	$ret .=	'			var siteurl = "'.get_bloginfo('siteurl').'";';
	$ret .=	'			var ajaxUrl = "'.get_bloginfo('wpurl').'/wp-admin/admin-ajax.php";';
	$ret .=	'			var activePastContest = "active";';
	$ret .=	'			$(document).ready(function() {';
	$ret .=	'				listActiveContest("'.$id.'","pastContest","'.$contest_type.'");';
	$ret .=	'			});';
	$ret .=	'		</script>';
	$ret .=	'		<div id="'.$id.'" class="tc_contest">';
	$ret .=	'			<input type="hidden" class="contestType" value="pastContest"></input>';
	$ret .=	'			<input type="hidden" class="postPerPage" value="'.$postPerPage.'"></input>';
	$ret .=	'			<div class="pagingWrapper">';
	$ret .=	'			</div>';
	$ret .=	'			<table class="contestTable">';
	$ret .=	'				<colgroup>';
	$ret .=	'					<col width="315">';
	$ret .=	'					<col width="140">';
	$ret .=	'					<col width="71">';
	$ret .=	'				</colgroup>';
	$ret .=	'				<thead>';
	$ret .=	'					<tr class="head">';
	$ret .=	'						<td height="17">Contest</td>';
	$ret .=	'						<td>Contest Type</td>';
	$ret .=	'						<td align="center">First Prize</td>';
	$ret .=	'						<td align="center">End</td>';
	$ret .=	'					</tr>';
	$ret .=	'				</thead>';
	$ret .=	'				<tbody>';
	$ret .=	'				</tbody>';
	$ret .=	'			</table>';
	$ret .=	'			<div class="overlayWrapper">
							<div class="loadingOverlay"><div class="loadingGif"></div></div>
						</div>';
	$ret .=	'		</div>';
	$ret .=	'		<!-- /.tc_contest -->';

	return $ret;
}

add_shortcode("past_contests", "past_contests_function"); 

function review_opportunities_function($atts, $content = null) {
	extract(shortcode_atts(array("type" => '',"id"=>'reviewOpportunities',"post_per_page" => 5), 
			$atts));
	
	$content = $content == null ? "Review Opportunities" : $content;			
	$contest_type = $type;
	$postPerPage = $post_per_page;
	
	$ret = '';
	$ret .=	'	<!-- tc_contest -->';
	$ret .=	'		<h2>'.$content.'</h2>';
	$ret .=	'		<script>';
	$ret .=	'			var siteurl = "'.get_bloginfo('siteurl').'";';
	$ret .=	'			var ajaxUrl = "'.get_bloginfo('wpurl').'/wp-admin/admin-ajax.php";';
	$ret .=	'			$(document).ready(function() {';
	$ret .=	'				listActiveContest("'.$id.'","reviewOpportunities","'.$contest_type.'");';
	$ret .=	'			});';
	$ret .=	'		</script>';
	$ret .=	'		<div id="'.$id.'" class="tc_contest">';
	$ret .=	'			<input type="hidden" class="contestType" value="reviewOpportunities"></input>';
	$ret .=	'			<input type="hidden" class="postPerPage" value="'.$postPerPage.'"></input>';
	$ret .=	'			<div class="pagingWrapper">';
	$ret .=	'			</div>';
	$ret .=	'			<table class="contestTable">';
	$ret .=	'				<colgroup>';
	$ret .=	'					<col width="315">';
	$ret .=	'					<col width="140">';
	$ret .=	'					<col width="71">';
	$ret .=	'				</colgroup>';
	$ret .=	'				<thead>';
	$ret .=	'					<tr class="head">';
	$ret .=	'						<td height="17">Contest</td>';
	$ret .=	'						<td align="right">Reviewer Payment *</td>';
	$ret .=	'						<td align="center">Submissions</td>';
	$ret .=	'						<td align="center">Review Start</td>';
	$ret .=	'						<td align="center">Open Positions</td>';
	$ret .=	'					</tr>';
	$ret .=	'				</thead>';
	$ret .=	'				<tbody>';
	$ret .=	'				</tbody>';
	$ret .=	'			</table>';
	$ret .=	'			<div class="overlayWrapper">
							<div class="loadingOverlay"><div class="loadingGif"></div></div>
						</div>';
	$ret .=	'		</div>';
	$ret .=	'		<!-- /.tc_contest -->';

	return $ret;
}

add_shortcode("review_opportunities", "review_opportunities_function"); 

function get_member_basic_data_shortcode($atts, $content = null) {
	extract(shortcode_atts(array("width" => ''), 
			$atts));
	if($width!="") {
		$mainDivWidth = $width!="" ? "width:".$width."px;" : "";
	}
	$userKey = get_option( 'api_user_key' );
	$data = get_member_profile($userKey,$content);
	$memberSince = substr($data->memberSince,0,10);
	$arrRating = $data->ratingsSummary;
	$ratingColor="";
	$rating=0;
	for($i=0;$i<count($arrRating);$i++) {
		if( $arrRating[$i]->rating > $rating ) {
			$ratingColor = $arrRating[$i]->colorStyle;
			$rating = $arrRating[$i]->rating;
		}
	}
	$ret = '';
	if($data!="Error in processing request")  {
		$ret .= '<div style="'.$mainDivWidth.'" class="shortcodeMainDiv">';
		$ret .= '	<div class="memberProfilePicture">';
		if(isset($data->photoLink))
			$ret .= '	<img src="http://www.topcoder.com'.$data->photoLink.'" alt="" width="141" height="140" />';
		else 
			$ret .= '	<img src="'.plugins_url("TcApi-hookup").'/includes/i/member-placeholder.png" alt="" width="141" height="140" />';
		$ret .= '	</div>';
		$ret .= '	<p class="memberProfile"><span class="handle" id="handle" style="'.$ratingColor.'" >'.$data->handle.'</span></p>';
		$ret .= '	<p id="memberSince" class="memberProfile"><label>Member Since</label><span class="alignRight">'.$memberSince.'</span></p>';
		$ret .= '	<p id="country" class="memberProfile"><label>Country</label><span class="alignCenter">'.$data->country.'</span></p>';
		$ret .= '	</div>';
	}
	return $ret;
}

add_shortcode("get_member_basic_data", "get_member_basic_data_shortcode"); 

function get_basic_achievements_shortcode($atts, $content = null) {
	extract(shortcode_atts(array("width" => ''), 
			$atts));
	if($width!="") {
		$mainDivWidth = $width!="" ? "width:".$width."px;" : "";
	}
	$userKey = get_option( 'api_user_key' );
	$arrUserAchievements = get_user_achievements($userKey,$content);
	$ret = '';
	if($arrUserAchievements!="Error in processing request") {
		$ret .= '<div style="'.$mainDivWidth.'" class="shortcodeMainDiv">';
		$ret .= '	<div class="coderAchievementTop">Coder Achievements</div>';
		$ret .= '	<table class="coderAchievementTable">';
		$ret .= '		<thead>';
		$ret .= '			<tr>';
		$ret .= '				<th width="30%">Date</th>';
		$ret .= '				<th width="70%">Description</th>';
		$ret .= '			</tr>';
		$ret .= '		</thead>';
		$ret .= '		<tbody>';
					if($arrUserAchievements!=null) 
					foreach($arrUserAchievements as $data) {
							$date = substr($data->date,0,10);
							$badgeLink = $data->badgeLink;
							$badgeImg = $badgeLink->url;
							$top = $badgeLink->topOffset;
							$left = $badgeLink->leftOffset;
							$desc = $data->description;
							$ret .= "<tr><td class=\"date\">".$date."</td><td><span class=\"icon\" style=\"background:url('".$badgeImg."') no-repeat ".$left."px ".$top."px;\"></span><span class=\"desc\">".$desc."</span></td></tr>";
					}
		$ret .= '		</tbody>';
		$ret .= '	</table>';
		$ret .= '</div>';
	}
	return $ret;
}

add_shortcode("get_basic_achievements", "get_basic_achievements_shortcode"); 

function get_copilot_stats_shortcode($atts, $content = null) {
	extract(shortcode_atts(array("width" => ''), 
			$atts));
	if($width!="") {
		$mainDivWidth = $width!="" ? "width:".$width."px;" : "";
	}
	$userKey = get_option( 'api_user_key' );
	$copilotStats = get_copilot_stats($userKey,$content);
	$ret = '';
	if($copilotStats!="Error in processing request"&&$copilotStats!="Error in processing request or Member dosen't exist") {
		$ret .= '<div id="copilotStatsShortcode" style="'.$mainDivWidth.'" class="mainRail">';

		$ret .= '	<div id="copilotStats">';
		$ret .= '		<h3 class="copilotAchievementsTitle copilotAchivementAjax">Copilot Achievements</h3>';
		$ret .= '		<div class="copilot-pool copilotAchivementAjax"><div class="charts">';
		$ret .= '			<div class="palisade">';
		$ret .= '				<div class="palisade-control">';
		$ret .= '					<div class="left-control">';
		$ret .= '						<div class="leftControlMask">';
									if($copilotStats!=null) 
									foreach($copilotStats as $key=>$obj) {
										$active = $key==0 ? " active" : "";
										$ret .= "<div class='controller$active' id='ctype$key' onclick=\"copilotAchievementsGoTo($key)\" >";
										$ret .= "	<div class='controllerWrapper'><span>".$obj->contestType."</span><span class='arrow'></span></div>";
										$ret .= "</div>";
									}							
		$ret .= '						</div>';
		$ret .= '					</div>';
		$ret .= '					<div class="right-area">';
									if($copilotStats!=null) 
									foreach($copilotStats as $key=>$obj) {
										$block = "table";
										$browser = $_SERVER['HTTP_USER_AGENT'];
										if( preg_match('/msie 7./i', $browser ) ) {
											$block = "block";
										}
										$display = $key==0 ? $block : "none";
										$ret .= "<table class='ctype".$key."' style='display: $display;'>";
										$ret .= "	<tbody><tr>";
										$ret .= "		<td>Number of Contests:</td>";
										$ret .= "		<td class='number b'>".$obj->numContests."</td>";
										$ret .= "	</tr>";
										$ret .= "	<tr>";
										$ret .= "		<td>Number of Reposts:</td>";
										$ret .= "		<td class='number b'>".$obj->numReposts."</td>";
										$ret .= "	</tr>";
										$ret .= "	<tr>";
										$ret .= "		<td>Number of Failures:</td>";
										$ret .= "		<td class='number b'>".$obj->numFailures."</td>";
										$ret .= "	</tr>";
										$ret .= "</tbody></table>";
									}													
		$ret .= '					</div>';
		$ret .= '				</div>';
		$ret .= '			</div>';
		$ret .= '		</div></div>';
		$ret .= '	</div>';
		$ret .= '	';
		$ret .= '</div>';
		$ret .= '<!-- End of .mainRail -->	';
	}
	return $ret;
}
add_shortcode("get_copilot_stats", "get_copilot_stats_shortcode"); 
?>
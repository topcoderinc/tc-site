<?php
/*
 * Template Name: Search Contests
 */
get_header ( 'contests' );

$values = get_post_custom ( $post->ID );

$userkey = get_option ( 'api_user_key' );
$siteURL = site_url ();
$keyword = $_GET['Contest_Name'];
?>

<div id="content" class="tcApi">
	<div class="rightLayout">
		<aside class="sidebar">
			<ul>
				<li><a href="<?php echo $siteURL .'/plugin-test-page/'?> ">Plugin Test Page</a></li>
				<li><p>&nbsp;</p></li>
				<li><a href="<?php echo $siteURL .'/active-contests/'?> ">Active Contests</a></li>
				<li><a href="<?php echo $siteURL .'/active-contests/UI Prototype Competition/1/'?> "> -- UI Prototype Competition</a></li>
				<li><a href="<?php echo $siteURL .'/active-contests/Assembly Competition/1/'?> "> -- Assembly Competition</a></li>
				<li><p>&nbsp;</p></li>
				<li><a href="<?php echo $siteURL .'/past-contests/'?> ">Past Contests</a></li>
				<li><a href="<?php echo $siteURL .'/past-contests/UI Prototype Competition/1/'?> "> -- UI Prototype Competition</a></li>
				<li><a href="<?php echo $siteURL .'/past-contests/Assembly Competition/1/'?> "> -- Assembly Competition</a></li>

			</ul>
		</aside>
		<!-- End of .rightRail -->
		<div class="mainRail">		
		
			
			<?php
			$post_per_page = get_option ( 'contest_per_page' );
			$result_list = search_contest ( $userkey, $keyword);

			?>
			<h2>Searched Result</h2>

			<?php
			if($result_list -> message !=null){
				echo $result_list -> message;
			}else if ($result_list->data == null) {
				echo $result_list;
			} else {
				$html = '<div class="tc_contest">
				<input type="hidden" class="page" value="' . $page . '" />
				<input type="hidden" class="postPerPage" value="' . $post_per_page . '" />
				<div class="contestPagination">
					<a href="javascript:;" class="pagePrev">&lt;&lt; Previous</a>
					<span>|</span>
					<a href="javascript:;" class="pageNext">Next &gt;&gt;</a>
				</div>
				<table class="contestTable">
					<colgroup>
						<col width="315">
						<col width="140">
						<col span="2" width="71">
					</colgroup>
					<thead>
						<tr class="head">
							<td height="17">Contest</td>
							<td>Contest Type</td>
							<td align="center">First Prize</td>
							<td align="center">End</td>
						</tr>
					</thead>
					<tbody>
					';
				$count = 0;
				foreach ( $result_list->data as $contest ) {
					$cls = '';
					if ($count % 2 == 0) {
						$cls = "odd";
					}
					$html .= '<tr class="' . $cls . '"><td><a href="http://community.topcoder.com/tc?module=ProjectDetail&pj=' . $contest->contestId . '">' . $contest->contestName . ' </a></td>
						<td>' . $contest->type . '</td>
						<td align="center"> $' . $contest->firstPrize . '</td>
						<td align="center">' . $contest->submissionEndDate . '</td></tr>';
					$count += 1;
				}
				$html .= '				
				</tbody>
				</table>
			</div>
			<!-- /.tc_contest -->';
				echo $html;
			}
			?>
			
		</div>
		<!-- End of .mainRail -->

		<div class="clear"></div>
	</div>
	<!-- End of .contentInner -->
</div>
<!-- End of #content -->

<?php get_footer(); ?>

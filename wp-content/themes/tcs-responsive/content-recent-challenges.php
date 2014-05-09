<div class="sideMostRecentChallenges">
	<h3>Most Recent Challenges</h3>
	<?php 
		$recentDesign = get_active_contests_ajax('','design',1,1);
		$recentDesign = $recentDesign->data[0];
		$recentDev= get_active_contests_ajax('','develop',1,1);
		$recentDev = $recentDev->data[0];
		$recentData= get_active_contests_ajax('','data/marathan');
		$recentData = $recentData->data[0];
		$chLink =  get_page_link_by_slug('challenge-details');
	?>
	<ul>									
		<li><a class="contestName contestType1" href="<?php echo $chLink.$recentDev->challengeId ?>">
				<i></i><?php echo $recentDev->challengeName ?>
			</a></li>
		<li class="alt"><a class="contestName contestType2" href="<?php echo $chLink.$recentDesign->challengeId ?>/?type=design">
				<i></i><?php echo $recentDesign->challengeName ?>
			</a></li>
	
	</ul>
</div>
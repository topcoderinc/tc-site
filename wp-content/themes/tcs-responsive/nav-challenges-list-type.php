<div class="lt challengeType">
	<?php
		$activeChallenges = get_bloginfo('siteurl') . "/active-challenges/" . $contest_type . "/";
		$pastChallenges = get_bloginfo('siteurl') . "/past-challenges/" . $contest_type . "/";
		$upcomingChallenges = get_bloginfo('siteurl') . "/upcoming-challenges/" . $contest_type . "/";
		$reviewOpportunities = get_bloginfo('siteurl') . "/review-opportunities/" . $contest_type . "/";
		$activeClass = '';
		$pastClass = '';
		$upcomingClass = '';
		$reviewClass = '';
		$currentUrl = get_permalink() . $contest_type  ."/";
		switch($currentUrl) {
                    case $activeChallenges:
                        $activeChallenges = 'javascript:;';
                        $activeClass = 'active ';
                        break;
                    case $pastChallenges:
                        $pastChallenges = 'javascript:;';
                        $pastClass = 'active ';
                        break;
                    case $upcomingChallenges:
                        $upcomingChallenges = 'javascript:;';
                        $upcomingClass = 'active ';
                        break;
                    case $reviewOpportunities:
                        $reviewOpportunities = 'javascript:;';
                        $reviewClass = 'active ';
                        break;
                    default:
                        $activeChallenges = 'javascript:;';
                        $activeClass = 'active ';
                        break;
		}
	?>
	<ul>
		<li><a href="<?php echo $activeChallenges;?>" class="<?php echo $activeClass;?>link">Open Challenges</a></li>
		<li><a href="<?php echo $pastChallenges;?>" class="<?php echo $pastClass;?>link">Past Challenges</a></li>
		<li><a href="<?php echo $upcomingChallenges;?>" class="<?php echo $upcomingClass;?>link">Upcoming Challenges</a></li>
		<!-- Coming soon!  <li><a href="<?php echo $reviewOpportunities;?>" class="<?php echo $reviewClass;?>link">Review Opportunities</a></li> -->
	</ul>
</div>
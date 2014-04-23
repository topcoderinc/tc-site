<div class="lt challengeType">
	<?php
		$activeChallenges = get_bloginfo('siteurl') . "/active-challenges/" . $contest_type . "/";
		$pastChallenges = get_bloginfo('siteurl') . "/past-challenges/" . $contest_type . "/";
		$upcomingChallenges = get_bloginfo('siteurl') . "/upcoming-challenges/" . $contest_type . "/";
		$activeClass = '';
		$pastClass = '';
		$upcomingClass = '';
		$currentUrl = get_permalink() . $contest_type  ."/";
		if ($currentUrl == $activeChallenges) {
			$activeChallenges = 'javascript:;';
			$activeClass = 'active ';
		} elseif ($currentUrl == $pastChallenges) {
			$pastChallenges = 'javascript:;';
			$pastClass = 'active ';
		} elseif ($currentUrl == $upcomingChallenges) {
			$upcomingChallenges = 'javascript:;';
			$upcomingClass = 'active ';
		}
	?>
	<ul>
		<li><a href="<?php echo $activeChallenges;?>" class="<?php echo $activeClass;?>link">Open Challenges</a></li>
		<li><a href="<?php echo $pastChallenges;?>" class="<?php echo $pastClass;?>link">Past Challenges</a></li>
		<li><a href="<?php echo $upcomingChallenges;?>" class="<?php echo $upcomingClass;?>link">Upcoming Challenges</a></li>
	</ul>
</div>
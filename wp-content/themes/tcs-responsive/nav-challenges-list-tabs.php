<div id="hero">
	<?php
		$designChallengesLink = get_permalink() . "design/";
		$developChallengesLink = get_permalink() . "develop/";
		$dataChallengesLink = get_permalink() . "data/";
	?>
	<div class="container grid grid-float">
		<div class="grid-3-1 track trackUX<?php if($contest_type=="design") echo " isActive"; ?>" >
			<a href="<?php echo $designChallengesLink;?>"><i></i>Graphic Design Challenges
			</a><span class="arrow"></span>
		</div>
		<div class="grid-3-1 track trackSD<?php if($contest_type=="develop") echo " isActive"; ?>" >
			<a href="<?php echo $developChallengesLink;?>"><i></i>Software Development Challenges
			</a><span class="arrow"></span>
		</div>
		<div class="grid-3-1 track trackAn<?php if($contest_type=="data") echo " isActive"; ?>" >
			<a href="<?php echo $dataChallengesLink;?>">
				<i></i>Data Science Challenges
			</a><span class="arrow"></span>
		</div>
	</div>
</div>
<!-- /#hero -->
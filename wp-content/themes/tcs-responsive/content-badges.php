<?php 
$achievements = array(
	"First Forum Post" => false,
	"One Hundred Forum Posts" => false,
	"Five Hundred Forum Posts" => false,
	"One Thousand Forum Posts" => false,
	"Five Thousand Forum Posts" => false,
	"First Rated Algorithm Competition" => false,
	"Five Rated Algorithm Competitions" => false,
	"Twenty Five Rated Algorithm Competitions" => false,
	"One Hundred Rated Algorithm Competitions" => false,
	"Three Hundred Rated Algorithm Competitions" => false,
	"First SRM Room Win (Any Division)" => false,
	"Five SRM Room Wins (Any Division)" => false,
	"Twenty SRM Room Wins (Any Division)" => false,
	"Fifty SRM Room Wins (Any Division)" => false,
	"One Hundred SRM Room Wins (Any Division)" => false,
	"First Solved Algorithm Problem" => false,
	"Ten Solved Algorithm Problems" => false,
	"Fifty Solved Algorithm Problems" => false,
	"Two Hundred Solved Algorithm Problems" => false,
	"Five Hundred Solved Algorithm Problems" => false,
	"First Successful Challenge" => false,
	"Five Successful Challenges" => false,
	"Twenty Five Successful Challenges" => false,
	"One Hundred Successful Challenges" => false,
	"Two Hundred Successful Challenges" => false,
	"First Marathon Competition" => false,
	"Three Marathon Competitions" => false,
	"Ten Marathon Competitions" => false,
	"Twenty Marathon Competitions" => false,
	"Fifty Marathon Competitions" => false,
	"First Marathon Top-5 Placement" => false,
	"Two Marathon Top-5 Placements" => false,
	"Four Marathon Top-5 Placements" => false,
	"Eight Marathon Top-5 Placements" => false,
	"Sixteen Marathon Top-5 Placements" => false,
	"First Passing Submission" => false,
	"Fifty Passing Submissions" => false,
	"One Hundred Passing Submissions" => false,
	"Two Hundred And Fifty Passing Submissions" => false,
	"Five Hundred Passing Submissions" => false,
	"First Milestone Prize" => false,
	"Fifty Milestone Prizes" => false,
	"One Hundred Milestone Prizes" => false,
	"Two Hundred And Fifty Milestone Prizes" => false,
	"Five Hundred Milestone Prizes" => false,
	"First Placement" => false,
	"Twenty Five Placements" => false,
	"Fifty Placements" => false,
	"One hundred Placements" => false,
	"Two Hundred And Fifty Placements" => false,
	"First Win" => false,
	"Twenty Five First Placement Win" => false,
	"Fifty First Placement Win" => false,
	"One Hundred First Placement Win" => false,
	"Two Hundred And Fifty First Placement Win" => false,
	"CoECI Client Badge" => false,
	"Solved Hard Div2 Problem in SRM" => false,
	"Solved Hard Div1 Problem in SRM" => false,
	"Marathon Match Winner" => false,
	"Algorithm Target" => false,
	"SRM Winner Div 1" => false,
	"SRM Winner Div 2" => false,
	"Solved Hard Div2 Problem in SRM" => false,
	"Solved Hard Div1 Problem in SRM" => false,
	"Digital Run Winner" => false,
	"Digital Run Top Five" => false,
	"Two Hundred Successful Challenges" => false,
	"First SRM Room Win (Any Division)" => false,
	"Five SRM Room Wins (Any Division)" => false,
	"Twenty SRM Room Wins (Any Division)" => false,
	"Fifty SRM Room Wins (Any Division)" => false,
	"One Hundred SRM Room Wins (Any Division)" => false
);
$coder_achievements = get_member_achievements ($handle)->Achievements;
$coder_achievements = is_array($coder_achievements) ? $coder_achievements : array();
foreach($coder_achievements as $achievement){
	$achievements[$achievement->description] = true;
}
?>
<div class="badgeGroups">
	<div class="groupBadge Forum-Posts <?php if($achievements['First Forum Post']==false){echo 'hide';} ?>">
		<span class="subBadge Forum-Posts-1 <?php if($achievements['First Forum Post']){echo 'selected';} ?>"></span><span class="subBadge Forum-Posts-100 <?php if($achievements['One Hundred Forum Posts']){echo 'selected';} ?>"></span><span class="subBadge Forum-Posts-500 <?php if($achievements['Five Hundred Forum Posts']){echo 'selected';} ?>"></span><span class="subBadge Forum-Posts-1000 <?php if($achievements['One Thousand Forum Posts']){echo 'selected';} ?>"></span><span class="subBadge Forum-Posts-5000 <?php if($achievements['Five Thousand Forum Posts']){echo 'selected';} ?>"></span>
	</div>
	<div class="groupBadge Rated-SRMs <?php if($achievements['First Rated Algorithm Competition']==false){echo 'hide';} ?>">
		<span class="subBadge Rated-SRMs-1 <?php if($achievements['First Rated Algorithm Competition']){echo 'selected';} ?>"></span><span class="subBadge Rated-SRMs-5 <?php if($achievements['Five Rated Algorithm Competitions']){echo 'selected';} ?>"></span><span class="subBadge Rated-SRMs-25 <?php if($achievements['Twenty Five Rated Algorithm Competitions']){echo 'selected';} ?>"></span><span class="subBadge Rated-SRMs-100 <?php if($achievements['One Hundred Rated Algorithm Competitions']){echo 'selected';} ?>"></span><span class="subBadge Rated-SRMs-300 <?php if($achievements['Three Hundred Rated Algorithm Competitions']){echo 'selected';} ?>"></span>
	</div>
	<div class="groupBadge SRM-Room-Wins" <?php if($achievements['First SRM Room Win (Any Division)']==false){echo 'hide';} ?>>
		<span class="subBadge SRM-Room-Wins-1 <?php if($achievements['First SRM Room Win (Any Division)']){echo 'selected';} ?>"></span><span class="subBadge SRM-Room-Wins-5 <?php if($achievements['Five SRM Room Wins (Any Division)']){echo 'selected';} ?>"></span><span class="subBadge SRM-Room-Wins-20 <?php if($achievements['Twenty SRM Room Wins (Any Division)']){echo 'selected';} ?>"></span><span class="subBadge SRM-Room-Wins-50 <?php if($achievements['Fifty SRM Room Wins (Any Division)']){echo 'selected';} ?>"></span><span class="subBadge SRM-Room-Wins-100 <?php if($achievements['One Hundred SRM Room Wins (Any Division)']){echo 'selected';} ?>"></span>
	</div>
	<div class="groupBadge Solved-SRM-Problems <?php if($achievements['First Solved Algorithm Problem']==false){echo 'hide';} ?>">
		<span class="subBadge Solved-SRM-Problems-1 <?php if($achievements['First Solved Algorithm Problem']){echo 'selected';} ?>"></span><span class="subBadge Solved-SRM-Problems-10 <?php if($achievements['Ten Solved Algorithm Problems']){echo 'selected';} ?>"></span><span class="subBadge Solved-SRM-Problems-50 <?php if($achievements['Fifty Solved Algorithm Problems']){echo 'selected';} ?>"></span><span class="subBadge Solved-SRM-Problems-200 <?php if($achievements['Two Hundred Solved Algorithm Problems']){echo 'selected';} ?>"></span><span class="subBadge Solved-SRM-Problems-500 <?php if($achievements['Five Hundred Solved Algorithm Problems']){echo 'selected';} ?>"></span>
	</div>
	<div class="groupBadge Successful-Challenges <?php if($achievements['First Successful Challenge']==false){echo 'hide';} ?>">
		<span class="subBadge Successful-Challenges-1 <?php if($achievements['First Successful Challenge']){echo 'selected';} ?>"></span><span class="subBadge Successful-Challenges-5 <?php if($achievements['Five Successful Challenges']){echo 'selected';} ?>"></span><span class="subBadge Successful-Challenges-25 <?php if($achievements['Twenty Five Successful Challenges']){echo 'selected';} ?>"></span><span class="subBadge Successful-Challenges-100 <?php if($achievements['One Hundred Successful Challenges']){echo 'selected';} ?>"></span><span class="subBadge Successful-Challenges-250 <?php if($achievements['Two Hundred Successful Challenges']){echo 'selected';} ?>"></span>
	</div>
	<div class="groupBadge Marathon-Matches <?php if($achievements['First Marathon Competition']==false){echo 'hide';} ?>">
		<span class="subBadge Marathon-Matches-1 <?php if($achievements['First Marathon Competition']){echo 'selected';} ?>"></span><span class="subBadge Marathon-Matches-3 <?php if($achievements['Three Marathon Competitions']){echo 'selected';} ?>"></span><span class="subBadge Marathon-Matches-10 <?php if($achievements['Ten Marathon Competitions']){echo 'selected';} ?>"></span><span class="subBadge Marathon-Matches-20 <?php if($achievements['Twenty Marathon Competitions']){echo 'selected';} ?>"></span><span class="subBadge Marathon-Matches-50 <?php if($achievements['Fifty Marathon Competitions']){echo 'selected';} ?>"></span>
	</div>
	<div class="groupBadge Marathon-Top-5-Placements <?php if($achievements['First Marathon Top-5 Placement']==false){echo 'hide';} ?>">
		<span class="subBadge Marathon-Top-5-Placements-1 <?php if($achievements['First Marathon Competition']){echo 'selected';} ?>"></span><span class="subBadge Marathon-Top-5-Placements-2 <?php if($achievements['Two Marathon Top-5 Placements']){echo 'selected';} ?>"></span><span class="subBadge Marathon-Top-5-Placements-4 <?php if($achievements['Four Marathon Top-5 Placements']){echo 'selected';} ?>"></span><span class="subBadge Marathon-Top-5-Placements-8 <?php if($achievements['Eight Marathon Top-5 Placements']){echo 'selected';} ?>"></span><span class="subBadge Marathon-Top-5-Placements-16 <?php if($achievements['Sixteen Marathon Top-5 Placements']){echo 'selected';} ?>"></span>
	</div>
	<div class="groupBadge Passing-Submissions <?php if($achievements['First Passing Submission']==false){echo 'hide';} ?>">
		<span class="subBadge Passing-Submissions-1 <?php if($achievements['First Passing Submission']){echo 'selected';} ?>"></span><span class="subBadge Passing-Submissions-50 <?php if($achievements['Fifty Passing Submissions']){echo 'selected';} ?>"></span><span class="subBadge Passing-Submissions-100 <?php if($achievements['One Hundred Passing Submissions']){echo 'selected';} ?>"></span><span class="subBadge Passing-Submissions-250 <?php if($achievements['Two Hundred And Fifty Passing Submissions']){echo 'selected';} ?>"></span><span class="subBadge Passing-Submissions-500 <?php if($achievements['Five Hundred Passing Submissions']){echo 'selected';} ?>"></span>
	</div>
	<div class="groupBadge Checkpoint-Prizes <?php if($achievements['First Milestone Prize']==false){echo 'hide';} ?>">
		<span class="subBadge Checkpoint-Prizes-1 <?php if($achievements['First Milestone Prize']){echo 'selected';} ?>"></span><span class="subBadge Checkpoint-Prizes-50 <?php if($achievements['Fifty Milestone Prizes']){echo 'selected';} ?>"></span><span class="subBadge Checkpoint-Prizes-100 <?php if($achievements['One Hundred Milestone Prizes']){echo 'selected';} ?>"></span><span class="subBadge Checkpoint-Prizes-250 <?php if($achievements['Two Hundred And Fifty Milestone Prizes']){echo 'selected';} ?>"></span><span class="subBadge Checkpoint-Prizes-500 <?php if($achievements['Five Hundred Milestone Prizes']){echo 'selected';} ?>"></span>
	</div>
	<div class="groupBadge Winning-Placements <?php if($achievements['First Placement']==false){echo 'hide';} ?>">
		<span class="subBadge Winning-Placements-1 <?php if($achievements['First Placement']){echo 'selected';} ?>"></span><span class="subBadge Winning-Placements-25 <?php if($achievements['Twenty Five Placements']){echo 'selected';} ?>"></span><span class="subBadge Winning-Placements-50 <?php if($achievements['Fifty Placements']){echo 'selected';} ?>"></span><span class="subBadge Winning-Placements-100 <?php if($achievements['One hundred Placements']){echo 'selected';} ?>"></span><span class="subBadge Winning-Placements-250 <?php if($achievements['Two Hundred And Fifty Placements']){echo 'selected';} ?>"></span>
	</div>
	<div class="groupBadge First-Place-Wins <?php if($achievements['First Win']==false){echo 'hide';} ?>">
		<span class="subBadge First-Place-Wins-1 <?php if($achievements['First Win']){echo 'selected';} ?>"></span><span class="subBadge First-Place-Wins-25 <?php if($achievements['Twenty Five First Placement Win']){echo 'selected';} ?>"></span><span class="subBadge First-Place-Wins-50 <?php if($achievements['Fifty First Placement Win']){echo 'selected';} ?>"></span><span class="subBadge First-Place-Wins-100 <?php if($achievements['One Hundred First Placement Win']){echo 'selected';} ?>"></span><span class="subBadge First-Place-Wins-250 <?php if($achievements['Two Hundred And Fifty First Placement Win']){echo 'selected';} ?>"></span>
	</div>
	<div class="clear-float"></div>
</div>
<!-- /.badgeGroups -->

<div class="footer-badges">
	<div class="singleBadge Successful-Challenges-200 <?php if($achievements['Two Hundred Successful Challenges']==false){echo 'hide';} ?>"></div>
	<div class="singleBadge Solved-Hard-Div2-Problem-in-SRM <?php if($achievements['Solved Hard Div2 Problem in SRM']==false){echo 'hide';} ?>"></div>
	<div class="singleBadge Solved-Hard-Div1-Problem-in-SRM <?php if($achievements['Solved Hard Div1 Problem in SRM']==false){echo 'hide';} ?>"></div>
	<div class="singleBadge SRM-Winner-Div-2 <?php if($achievements['SRM Winner Div 2']==false){echo 'hide';} ?>"></div>
	<div class="singleBadge SRM-Winner-Div-1 <?php if($achievements['SRM Winner Div 1']==false){echo 'hide';} ?>"></div>
	<div class="singleBadge Algorithm-Target <?php if($achievements['Algorithm Target']==false){echo 'hide';} ?>"></div>
	<div class="singleBadge Marathon-Match-Winner <?php if($achievements['Marathon Match Winner']==false){echo 'hide';} ?>"></div>
	<div class="singleBadge Digital-Run-Winner <?php if($achievements['Digital Run Winner']==false){echo 'hide';} ?>"></div>
	<div class="singleBadge Digital-Run-Top-5 <?php if($achievements['Digital Run Top Five']==false){echo 'hide';} ?>"></div>
	<div class="clear-float"></div>
</div>
<!-- /.footer-badges -->
<script type="text/javascript">
$(document).ready(function(){
  // coder.initMemberBadges();
});
</script>
<?php
$handle = $_POST['handle'];

$achievements_map = array(
	"First Forum Post" => array(id => 1, active => false, groupClass => "Forum-Posts", specificClass => "Forum-Posts-1"),
	"One Hundred Forum Posts" => array(id => 1, active => false, groupClass => "Forum-Posts", specificClass => "Forum-Posts-100"),
	"Five Hundred Forum Posts" => array(id => 1, active => false, groupClass => "Forum-Posts", specificClass => "Forum-Posts-500"),
	"One Thousand Forum Posts" => array(id => 1, active => false, groupClass => "Forum-Posts", specificClass => "Forum-Posts-1000"),
	"Five Thousand Forum Posts" => array(id => 1, active => false, groupClass => "Forum-Posts", specificClass => "Forum-Posts-5000"),
	"First Rated Algorithm Competition" => array(id => 89, active => false, groupClass => "Rated-SRMs", specificClass => "Rated-SRMs-1"),
	"Five Rated Algorithm Competitions" => array(id => 89, active => false, groupClass => "Rated-SRMs", specificClass => "Rated-SRMs-5"),
	"Twenty Five Rated Algorithm Competitions" => array(id => 89, active => false, groupClass => "Rated-SRMs", specificClass => "Rated-SRMs-25"),
	"One Hundred Rated Algorithm Competitions" => array(id => 89, active => false, groupClass => "Rated-SRMs", specificClass => "Rated-SRMs-100"),
	"Three Hundred Rated Algorithm Competitions" => array(id => 89, active => false, groupClass => "Rated-SRMs", specificClass => "Rated-SRMs-300"),
	"First SRM Room Win (Any Division)" => array(id => 94, active => false, groupClass => "SRM-Room-Wins", specificClass => "SRM-Room-Wins-1"),
	"Five SRM Room Wins (Any Division)" => array(id => 94, active => false, groupClass => "SRM-Room-Wins", specificClass => "SRM-Room-Wins-5"),
	"Twenty SRM Room Wins (Any Division)" => array(id => 94, active => false, groupClass => "SRM-Room-Wins", specificClass => "SRM-Room-Wins-20"),
	"Fifty SRM Room Wins (Any Division)" => array(id => 94, active => false, groupClass => "SRM-Room-Wins", specificClass => "SRM-Room-Wins-50"),
	"One Hundred SRM Room Wins (Any Division)" => array(id => 94, active => false, groupClass => "SRM-Room-Wins", specificClass => "SRM-Room-Wins-100"),
	"First Solved Algorithm Problem" => array(id => 99, active => false, groupClass => "Solved-SRM-Problems", specificClass => "Solved-SRM-Problems-1"),
	"Ten Solved Algorithm Problems" => array(id => 99, active => false, groupClass => "Solved-SRM-Problems", specificClass => "Solved-SRM-Problems-10"),
	"Fifty Solved Algorithm Problems" => array(id => 99, active => false, groupClass => "Solved-SRM-Problems", specificClass => "Solved-SRM-Problems-50"),
	"Two Hundred Solved Algorithm Problems" => array(id => 99, active => false, groupClass => "Solved-SRM-Problems", specificClass => "Solved-SRM-Problems-200"),
	"Five Hundred Solved Algorithm Problems" => array(id => 99, active => false, groupClass => "Solved-SRM-Problems", specificClass => "Solved-SRM-Problems-500"),
	"First Successful Challenge" => array(id => 104, active => false, groupClass => "Successful-Challenges", specificClass => "Successful-Challenges-1"),
	"Five Successful Challenges" => array(id => 104, active => false, groupClass => "Successful-Challenges", specificClass => "Successful-Challenges-5"),
	"Twenty Five Successful Challenges" => array(id => 104, active => false, groupClass => "Successful-Challenges", specificClass => "Successful-Challenges-25"),
	"One Hundred Successful Challenges" => array(id => 104, active => false, groupClass => "Successful-Challenges", specificClass => "Successful-Challenges-100"),
	"Two Hundred Successful Challenges" => array(id => 104, active => false, groupClass => "Successful-Challenges", specificClass => "Successful-Challenges-250"),
	"First Marathon Competition" => array(id => 113, active => false, groupClass => "Marathon-Matches", specificClass => "Marathon-Matches-1"),
	"Three Marathon Competitions" => array(id => 113, active => false, groupClass => "Marathon-Matches", specificClass => "Marathon-Matches-3"),
	"Ten Marathon Competitions" => array(id => 113, active => false, groupClass => "Marathon-Matches", specificClass => "Marathon-Matches-10"),
	"Twenty Marathon Competitions" => array(id => 113, active => false, groupClass => "Marathon-Matches", specificClass => "Marathon-Matches-20"),
	"Fifty Marathon Competitions" => array(id => 113, active => false, groupClass => "Marathon-Matches", specificClass => "Marathon-Matches-50"),
	"First Marathon Top-5 Placement" => array(id => 117, active => false, groupClass => "Marathon-Top-5-Placements", specificClass => "Marathon-Top-5-Placements-1"),
	"Two Marathon Top-5 Placements" => array(id => 117, active => false, groupClass => "Marathon-Top-5-Placements", specificClass => "Marathon-Top-5-Placements-2"),
	"Four Marathon Top-5 Placements" => array(id => 117, active => false, groupClass => "Marathon-Top-5-Placements", specificClass => "Marathon-Top-5-Placements-4"),
	"Eight Marathon Top-5 Placements" => array(id => 117, active => false, groupClass => "Marathon-Top-5-Placements", specificClass => "Marathon-Top-5-Placements-8"),
	"Sixteen Marathon Top-5 Placements" => array(id => 117, active => false, groupClass => "Marathon-Top-5-Placements", specificClass => "Marathon-Top-5-Placements-16"),
	"First Passing Submission" => array(id => 6, active => false, groupClass => "Passing-Submissions", specificClass => "Passing-Submissions-1"),
	"Fifty Passing Submissions" => array(id => 6, active => false, groupClass => "Passing-Submissions", specificClass => "Passing-Submissions-50"),
	"One Hundred Passing Submissions" => array(id => 6, active => false, groupClass => "Passing-Submissions", specificClass => "Passing-Submissions-100"),
	"Two Hundred And Fifty Passing Submissions" => array(id => 6, active => false, groupClass => "Passing-Submissions", specificClass => "Passing-Submissions-250"),
	"Five Hundred Passing Submissions" => array(id => 6, active => false, groupClass => "Passing-Submissions", specificClass => "Passing-Submissions-500"),
	"First Milestone Prize" => array(id => 11, active => false, groupClass => "Checkpoint-Prizes", specificClass => "Checkpoint-Prizes-1"),
	"Fifty Milestone Prizes" => array(id => 11, active => false, groupClass => "Checkpoint-Prizes", specificClass => "Checkpoint-Prizes-50"),
	"One Hundred Milestone Prizes" => array(id => 11, active => false, groupClass => "Checkpoint-Prizes", specificClass => "Checkpoint-Prizes-100"),
	"Two Hundred And Fifty Milestone Prizes" => array(id => 11, active => false, groupClass => "Checkpoint-Prizes", specificClass => "Checkpoint-Prizes-250"),
	"Five Hundred Milestone Prizes" => array(id => 11, active => false, groupClass => "Checkpoint-Prizes", specificClass => "Checkpoint-Prizes-500"),
	"First Placement" => array(id => 16, active => false, groupClass => "Winning-Placements", specificClass => "Winning-Placements-1"),
	"Twenty Five Placements" => array(id => 16, active => false, groupClass => "Winning-Placements", specificClass => "Winning-Placements-25"),
	"Fifty Placements" => array(id => 16, active => false, groupClass => "Winning-Placements", specificClass => "Winning-Placements-50"),
	"One hundred Placements" => array(id => 16, active => false, groupClass => "Winning-Placements", specificClass => "Winning-Placements-100"),
	"Two Hundred And Fifty Placements" => array(id => 16, active => false, groupClass => "Winning-Placements", specificClass => "Winning-Placements-250"),
	"First Win" => array(id => 21, active => false, groupClass => "First-Place-Wins", specificClass => "First-Place-Wins-1"),
	"Twenty Five First Placement Win" => array(id => 21, active => false, groupClass => "First-Place-Wins", specificClass => "First-Place-Wins-25"),
	"Fifty First Placement Win" => array(id => 21, active => false, groupClass => "First-Place-Wins", specificClass => "First-Place-Wins-50"),
	"One Hundred First Placement Win" => array(id => 21, active => false, groupClass => "First-Place-Wins", specificClass => "First-Place-Wins-100"),
	"Two Hundred And Fifty First Placement Win" => array(id => 21, active => false, groupClass => "First-Place-Wins", specificClass => "First-Place-Wins-250")
);
$single_achievements_map = array(
	"Marathon Match Winner" => array(id => 121, active => false, groupClass => "Marathon-Match-Winner"),
	"Algorithm Target" => array(id => 122, active => false, groupClass => "Algorithm-Target"),
	"SRM Winner Div 1" => array(id => 119, active => false, groupClass => "SRM-Winner-Div-1"),
	"SRM Winner Div 2" => array(id => 120, active => false, groupClass => "SRM-Winner-Div-2"),
	"Solved Hard Div2 Problem in SRM" => array(id => 127, active => false, groupClass => "Solved-Hard-Div2-Problem-in-SRM"),
	"Solved Hard Div1 Problem in SRM" => array(id => 126, active => false, groupClass => "Solved-Hard-Div1-Problem-in-SRM"),
	"Digital Run Winner" => array(id => 51, active => false, groupClass => "Digital-Run-Winner"),
	"Digital Run Top Five" => array(id => 52, active => false, groupClass => "Digital-Run-Top-5"),
	"Two Hundred Successful Challenges" => array(id => 1, active => false, groupClass => "Successful-Challenges-200"),
	"CoECI Client Badge" => array(id => 129, active => false, groupClass => "CoECI-Client-Badge")
);

$coder_achievements = get_member_achievements ($handle)->Achievements;
$coder_achievements = is_array($coder_achievements) ? $coder_achievements : array();
$searchResult = search_coder($handle);
foreach($coder_achievements as $achievement){
  if(isset($achievements_map[$achievement->description])){
    $achievements_map[$achievement->description]["active"] = true;
    $achievements_map[$achievement->description]["date"] = date("M d, Y H:i", strtotime("$achievement->date")) . " EST";
  } else if(isset($single_achievements_map[$achievement->description])){
    $single_achievements_map[$achievement->description]["active"] = true;
    $single_achievements_map[$achievement->description]["date"] = date("M d, Y H:i", strtotime("$achievement->date")) . " EST";
  }
}
?>
<div class="badgeGroups">
 <?php
    $index = 0;
    foreach($achievements_map as $key => $achievement):
      $active = $achievement['active'];
      $active = $achievement['active'];
      $achievements_current;
      if(isset($searchResult->users)){
        $achievements_current = get_member_achievements_current($searchResult->users[0]->userId, $achievement["id"]);
      }
      $achievement["currentlyEarned"] = isset($achievements_current->count) ? $achievements_current->count : "(retrieving...)" ;
 ?>
   <?php if($index % 5 == 0): ?>
      <?php if($index != 0): ?>
        </div>
      <?php endif; ?>
      <div class="groupBadge <?php echo $achievement['groupClass']; ?> <?php if($active==false){echo 'hide';} ?>">
   <?php endif; ?>
    <span data-current="<?php echo $achievement['currentlyEarned']; ?>" data-date="<?php if($active){echo $achievement['date'];} else {echo 'Not Earned Yet';} ?>" data-title="<?php echo $key; ?>" class="subBadge <?php echo $achievement['specificClass']; if($active){echo ' selected';} ?>"></span>
 <?php $index++; endforeach; ?>
</div>


<div class="footer-badges">
  <?php
    $index = 0;
    foreach($single_achievements_map as $key => $achievement):
      $active = $achievement['active'];
      $achievements_current;
      if(isset($searchResult->users)){
        $achievements_current = get_member_achievements_current($searchResult->users[0]->userId, $achievement["id"]);
      }
      $achievement["currentlyEarned"] = isset($achievements_current->count) ? $achievements_current->count : "(retrieving...)" ;
  ?>
    <div data-current="<?php echo $achievement['currentlyEarned']; ?>" data-date="<?php if($active){echo $achievement['date'];} ?>" data-title="<?php echo $key; ?>" class="singleBadge <?php echo $achievement['groupClass']; if($active){echo ' selected';} else {echo ' hide';} ?>"></div>
  <?php endforeach; ?>
  <div class="clear-float"></div>
</div>
<!-- /.footer-badges -->
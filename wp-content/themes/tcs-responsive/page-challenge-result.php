<?php
$registrants_map = array();
foreach ($registrants as &$registrant) {
  $registrants_map[$registrant->handle] = $registrant;
}

$submissions = array();
if ($contestType == 'design'){
    $submission_map = populateDesignSubmissionMap($contest->submissions);
    
    foreach ($submission_map as $submission) {
      $submissions[] = $submission;
    }
} else {
  $submissions = array_filter(
    $contest->submissions,
    function ($submission) {
      if ($submission->screeningScore) {
        return TRUE;
      }
      else {
        return FALSE;
      }
    }
  );
}

function populateDesignSubmissionMap($submissions) {
  $submission_map = array();
  foreach ($submissions as $submission) {
    if ($submission_map[$submission->submitter]) {
      $sub_date = new DateTime($submission->submissionTime);
      if ($cur_date->diff($sub_date) < 0) {
        $submission_map[$submission->submitter] = $submission;
        $cur_date = new DateTime($submission->submissionTime);
      }
    }
    else {
      $submission_map[$submission->submitter] = $submission;
      $cur_date = new DateTime($submission->submissionTime);
    }
  }

  return $submission_map;
}

function cmp($a, $b)
{
    if ($a->placement == "" && $b->placement != "") {
        return -1;
    } else if($b->placement == "" && $a->placement != ""){
        return 1;
    } else if($a->placement == $b->placement){
        return 0;
    } 
    return ($a->placement < $b->placement) ? -1 : 1;
}

$submissionCount = count($submissions);

if($submissionCount != 0){
  
}

usort($submissions, "cmp");

$nrOfPassingCheckpointSubmissions = 0;
$checkpointDetail = get_contest_checkpoint_detail($contestID, $contestType);

if(isset($checkpointDetail->checkpointResults)){
  $nrOfPassingCheckpointSubmissions = count($checkpointDetail->checkpointResults);
} else if(isset($checkpointDetail->error)){
  $nrOfPassingCheckpointSubmissions = -1;
}

if(count($submissions) > 0 && ($submissions[0]->placement == 1 || $contestType == 'design')){
  $firstPlacedSubmission = $submissions[0];
}
if(count($submissions) > 1 && ($submissions[1]->placement == 2 || $contestType == 'design')){
  $secondPlacedSubmission = $submissions[1];
}
?>

<?php if($submissionCount != 0): ?>

<?php
if ($contestType == 'design'):
  if(count($submissions) > 2 && ($submissions[2]->placement == 3 || $contestType == 'design')){
    $thirdPlacedSubmission = $submissions[2];
  }
  $nrOfPrizes = count($contest->prize);
?>
<article>
    <?php 
    if (isset($firstPlacedSubmission)):
      $registrationDate = $registrants_map[$firstPlacedSubmission->submitter]->registrationDate;
    ?>
    <div class="winnerRow">
        <div class="place first">1<span>st</span></div>
        <!-- #/end place-->
        <div class="image">
        
            <img src="http://studio.topcoder.com/studio.jpg?module=DownloadSubmission&sbmid=<?php echo $firstPlacedSubmission->submissionId; ?>&sbt=full" alt="winner"/>
        </div>
        
        <!-- #/end image-->
        <div class="details">
            <a href="<?php bloginfo('wpurl'); ?>/member-profile/<?php echo $firstPlacedSubmission->submitter; ?>" class="coderTextOrange"><?php echo $firstPlacedSubmission->submitter; ?></a>
            <div class="">
                <h3>$<?php echo $contest->prize[0]; ?></h3>
                <span class="title">PRIZE</span>
                <span class="date">Registration Date</span>
                <span class="time"><?php echo date("M d, Y H:i", strtotime("$registrationDate")) . " EST"; ?></span>
            </div>
            <div class="">
                <h3><?php echo $firstPlacedSubmission->points; ?></h3>
                <span class="title">Studio Cup Points</span>
                <span class="date">Submitted Date</span>
                <span class="time"><?php echo date("M d, Y H:i", strtotime("$firstPlacedSubmission->submissionTime")) . " EST"; ?></span>
            </div>
        </div>
        <!-- #/end details-->
        <div class="actions">
            <a href="http://studio.topcoder.com/studio.jpg?module=DownloadSubmission&sbmid=<?php echo $firstPlacedSubmission->submissionId; ?>&sbt=full&it=28&sfi=1" class="view">View</a>
            <a href="http://studio.topcoder.com/?module=DownloadSubmission&sbmid=<?php echo $firstPlacedSubmission->submissionId; ?>" class="download">Download</a>
        </div>
        <!-- #/end actions-->
        <div class="clear"></div>
    </div>
    <!--#/end winnerrow-->
    <?php endif; ?>
    <?php 
    if (isset($secondPlacedSubmission)):
      $registrationDate = $registrants_map[$secondPlacedSubmission->submitter]->registrationDate;
    ?>
    <div class="winnerRow">
        <div class="place second">2<span>nd</span></div>
        <!-- #/end place-->
        <div class="image">
            <img src="http://studio.topcoder.com/studio.jpg?module=DownloadSubmission&sbmid=<?php echo $secondPlacedSubmission->submissionId; ?>&sbt=full" alt="winner" alt="winner"/>
        </div>
        <!-- #/end image-->
        <div class="details">
            <a href="<?php bloginfo('wpurl'); ?>/member-profile/<?php echo $secondPlacedSubmission->submitter; ?>" class="coderTextOrange"><?php echo $secondPlacedSubmission->submitter; ?></a>
            <div class="">
                <h3>$<?php if($nrOfPrizes > 1) { echo $contest->prize[1]; } else { echo "0"; } ?></h3>
                <span class="title">PRIZE</span>
                <span class="date">Registration Date</span>
                <span class="time"><?php echo date("M d, Y H:i", strtotime("$registrationDate")) . " EST"; ?></span>
            </div>
            <div class="">
                <h3><?php echo $thirdPlacedSubmission->points; ?></h3>
                <span class="title">Studio Cup Points</span>
                <span class="date">Submitted Date</span>
                <span class="time"><?php echo date("M d, Y H:i", strtotime("$secondPlacedSubmission->submissionTime")) . " EST"; ?></span>
            </div>
        </div>
        <!-- #/end details-->
        <div class="actions">
            <a href="http://studio.topcoder.com/studio.jpg?module=DownloadSubmission&sbmid=<?php echo $secondPlacedSubmission->submissionId; ?>&sbt=full&it=28&sfi=1" class="view">View</a>
            <a href="http://studio.topcoder.com/?module=DownloadSubmission&sbmid=<?php echo $secondPlacedSubmission->submissionId; ?>" class="download">Download</a>
        </div>
        <!-- #/end actions-->
        <div class="clear"></div>
    </div>
    <!--#/end winnerrow-->
    <?php endif; ?>
    <?php 
    if (isset($thirdPlacedSubmission)):
      $registrationDate = $registrants_map[$thirdPlacedSubmission->submitter]->registrationDate;
    ?>
    <div class="winnerRow hideOnMobi">
        <div class="place third">3<span>rd</span></div>
        <!-- #/end place-->
        <div class="image">
            <img src="http://studio.topcoder.com/studio.jpg?module=DownloadSubmission&sbmid=<?php echo $thirdPlacedSubmission->submissionId; ?>&sbt=full" alt="winner" alt="winner"/>
        </div>
        <!-- #/end image-->
        <div class="details">
            <a href="<?php bloginfo('wpurl'); ?>/member-profile/<?php echo $thirdPlacedSubmission->submitter; ?>" class="coderTextOrange"><?php echo $thirdPlacedSubmission->submitter; ?></a>
            <div class="">
                <h3>$<?php if($nrOfPrizes > 2) { echo $contest->prize[2]; } else { echo "0"; } ?></h3>
                <span class="title">PRIZE</span>
                <span class="date">Registration Date</span>
                <span class="time"><?php echo date("M d, Y H:i", strtotime("$registrationDate")) . " EST"; ?></span>
            </div>
            <div class="">
                <h3><?php echo $thirdPlacedSubmission->points; ?></h3>
                <span class="title">Studio Cup Points</span>
                <span class="date">Submitted Date</span>
                <span class="time"><?php echo date("M d, Y H:i", strtotime("$thirdPlacedSubmission->submissionTime")) . " EST"; ?></span>
            </div>
        </div>
        <!-- #/end details-->
        <div class="actions">
            <a href="http://studio.topcoder.com/studio.jpg?module=DownloadSubmission&sbmid=<?php echo $thirdPlacedSubmission->submissionId; ?>&sbt=full&it=28&sfi=1" class="view">View</a>
            <a href="http://studio.topcoder.com/?module=DownloadSubmission&sbmid=<?php echo $thirdPlacedSubmission->submissionId; ?>" class="download">Download</a>
        </div>
        <!-- #/end actions-->
        <div class="clear"></div>
    </div>
    <!--#/end winnerrow-->
    <?php endif; ?>
    <?php 
    for ($i = 3; $i < count($submissions); $i++) :
      $registrationDate = $registrants_map[$submissions[$i]->submitter]->registrationDate;
      $submissionTime = $submissions[$i]->submissionTime;
    ?>
    <div class="winnerRow hideOnMobi">
        <div class="place other"><?php echo $i+1; ?><span>th</span></div>
        <!-- #/end place-->
        <div class="image">
            <img src="http://studio.topcoder.com/studio.jpg?module=DownloadSubmission&sbmid=<?php echo $submissions[$i]->submissionId; ?>&sbt=full" alt="winner" alt="winner"/>
        </div>
        <!-- #/end image-->
        <div class="details">
            <a href="<?php bloginfo('wpurl'); ?>/member-profile/<?php echo $submissions[$i]->submitter; ?>" class="coderTextOrange"><?php echo $submissions[$i]->submitter; ?></a>
            <div class="">
                <h3>$<?php if($nrOfPrizes > $i) { echo $contest->prize[$i]; } else { echo "0"; } ?></h3>
                <span class="title">PRIZE</span>
                <span class="date">Registration Date</span>
                <span class="time"><?php echo date("M d, Y H:i", strtotime("$registrationDate")) . " EST"; ?></span>
            </div>
            <div class="">
                <h3><?php echo $submissions[$i]->points; ?></h3>
                <span class="title">Studio Cup Points</span>
                <span class="date">Submitted Date</span>
                <span class="time"><?php echo date("M d, Y H:i", strtotime("$submissionTime")) . " EST"; ?></span>
            </div>
        </div>
        <!-- #/end details-->
        <div class="actions">
            <a href="http://studio.topcoder.com/studio.jpg?module=DownloadSubmission&sbmid=<?php echo $submissions[$i]->submissionId; ?>&sbt=full&it=28&sfi=1" class="view">View</a>
            <a href="http://studio.topcoder.com/?module=DownloadSubmission&sbmid=<?php echo $submissions[$i]->submissionId; ?>" class="download">Download</a>
        </div>
        <!-- #/end actions-->
        <div class="clear"></div>
    </div>
    <?php endfor; ?>
    <!--#/end winnerrow-->
    <div class="winnerRow hideOnMobi hide">
        <div class="place other client">CLIENT<span>SELECTION</span></div>
        <!-- #/end place-->
        <div class="image">
            <img src="http://studio.topcoder.com/studio.jpg?module=DownloadSubmission&sbmid=<?php echo $firstPlacedSubmission->submissionId; ?>&sbt=full" alt="winner" alt="winner"/>
        </div>
        <!-- #/end image-->
        <div class="details">
            <a href="javascript:" class="coderTextOrange">Handlegoeshere</a>
            <div class="">
                <h3>$200</h3>
                <span class="title">PRIZE</span>
                <span class="date">Registration Date</span>
                <span class="time">01.07.2014 09:37 AM EST</span>
            </div>
            <div class="">
                <h3>100</h3>
                <span class="title">Studio Cup Points</span>
                <span class="date">Submitted Date</span>
                <span class="time">01.07.2014 09:37 AM EST</span>
            </div>
        </div>
        <!-- #/end details-->
        <div class="actions">
            <a href="http://studio.topcoder.com/studio.jpg?module=DownloadSubmission&sbmid=<?php echo $secondPlacedSubmission->submissionId; ?>&sbt=full&it=28&sfi=1" class="view" class="view">View</a>
            <a href="http://studio.topcoder.com/?module=DownloadSubmission&sbmid=<?php echo $firstPlacedSubmission->submissionId; ?>" class="download">Download</a>
        </div>
        <!-- #/end actions-->
        <div class="clear"></div>
    </div>
    <!--#/end winnerrow-->
    <div class="showMore hideOnMobi hide">
        <a class="fiveMore" href="javascript:">5 more winners</a>
    </div>
    <!--#/end showMore-->
    <div class="competitionDetails">
        <div class="registrant">
            <h2>Registrants</h2>
            <div class="values">
                <span class="count"><?php echo $contest->numberOfRegistrants; ?></span>
            </div>
        </div>
        <!--#/end registrant-->
        <div class="round <?php if($nrOfPassingCheckpointSubmissions == -1) { echo 'hide'; } ?>">
            <h2>Round 1 (Checkpoint)</h2>
            <!--<div class="values">
                <span class="count"><?php echo $nrOfPassingCheckpointSubmissions; ?><span class="sup">&nbsp;</span></span>
                <span class="type">Submitter</span>
                <span class="type">&nbsp;</span>
            </div>-->
            <div class="values">
                <span class="count"><?php echo $nrOfPassingCheckpointSubmissions; ?></span>
                <span class="type">Submissions</span>
            </div>
            <!--<div class="values">
                <span class="count">N/A<span class="sup">(N/A%)</span></span>
                <span class="type">Passed Review</span>
            </div>-->
        </div>
        <!--#/end round-->
        <div class="round round2">
            <h2>Round 2 (Final)</h2>
            <!--<div class="values">
                <span class="count">N/A<span class="sup">&nbsp;</span></span>
                <span class="type">Submitter</span>
                <span class="type">&nbsp;</span>
            </div>-->
            <div class="values">
                <span class="count"><?php echo count($submissions); ?></span>
                <span class="type">Submissions</span>
            </div>
            <!--<div class="values">
                <span class="count">N/A<span class="sup">(N/A%)</span></span>
                <span class="type">Passed Review</span>
            </div>-->
        </div>
        <!--#/end round-->
        <div class="clear"></div>
    </div>
    <!--#/end competitionDetails-->
</article>

<?php else: ?>
<article>
    <?php if (isset($firstPlacedSubmission)): ?>
    <div class="winnerRow">
        <div class="place first">1<span>st</span></div>
        <!-- #/end place-->
        <div class="details">
            <a href="<?php bloginfo('wpurl'); ?>/member-profile/<?php echo $firstPlacedSubmission->handle; ?>" class="coderTextYellow"><?php echo $firstPlacedSubmission->handle; ?></a>
        </div>
        <!-- #/end details-->
        <div class="price">
            <span class="price">$<?php echo $contest->prize[0]; ?></span>
            <span>PRIZE</span>
        </div>
        <!-- #/end price-->
        <div class="point">
            <span class="point"><?php echo $firstPlacedSubmission->points; ?></span>
            <span>DR POINT</span>
        </div>
        <!-- #/end price-->
        <div class="actions">
            <a href="javascript:" class="download">Download</a>
        </div>
        <!-- #/end actions-->
        <div class="clear"></div>
    </div>
    <!--#/end winnerrow-->
    <?php endif; ?>
    <?php if (isset($secondPlacedSubmission)): ?>
    <div class="winnerRow">
        <div class="place second">2<span>nd</span></div>
        <!-- #/end place-->
        <div class="details">
            <a href="<?php bloginfo('wpurl'); ?>/member-profile/<?php echo $secondPlacedSubmission->handle; ?>" class="coderTextGray"><?php echo $secondPlacedSubmission->handle; ?></a>
        </div>
        <!-- #/end details-->
        <div class="price">
            <span class="price">$<?php echo $contest->prize[1]; ?></span>
            <span>PRIZE</span>
        </div>
        <!-- #/end price-->
        <div class="point">
            <span class="point"><?php echo $secondPlacedSubmission->points; ?></span>
            <span>DR POINT</span>
        </div>
        <!-- #/end price-->
        <div class="actions">
            <a href="javascript:" class="download">Download</a>
        </div>
        <!-- #/end actions-->
        <div class="clear"></div>
    </div>
    <!--#/end winnerrow-->
    <?php endif; ?>
    <table class="registrantsTable hideOnMobi">
        <thead>
        <tr>
            <th class="leftAlign">
                <a href="javascript:" class="">Handle</a>
            </th>
            <th>
                <a href="javascript:" class="">Registration Date</a>
            </th>
            <th>
                <a href="javascript:" class="">Submission Date</a>
            </th>
            <th>
                <a href="javascript:" class="">Screening Score</a>
            </th>
            <th>
                <a href="javascript:" class="">Initial/ Final Score</a>
            </th>
            <th>
            </th>
        </tr>
        </thead>
        <tbody>
        <?php 
        $initialScoreSum = 0;
        $finalScoreSum = 0;
        for ($i = 0; $i < $submissionCount; $i++): 
          $submission = $submissions[$i];
          $registrationDate = $registrants_map[$submission->handle]->registrationDate;
          $initialScoreSum += $submissions[$i]->initialScore;
          $finalScoreSum += $submissions[$i]->finalScore;
        ?>
        <tr class="<?php if ($i % 2 == 1) { echo 'alt'; } ?>">
            <td class="leftAlign"><a href="<?php bloginfo('wpurl'); ?>/member-profile/<?php echo $submission->handle; ?>" class="coderTextGray"><?php echo $submission->handle; ?></a></td>
            <td><?php echo date("M d, Y H:i", strtotime("$registrationDate")) . " EST"; ?></td>
            <td><?php echo date("M d, Y H:i", strtotime("$submission->submissionDate")) . " EST"; ?></td>
            <td><span class="pass"><?php echo $submission->screeningScore; ?></span></td>
            <td><span class="initialScore"><?php echo $submission->initialScore; ?></span>/<a href="javascript:" class="finalScore"><?php echo $submission->finalScore; ?></a> </td>
            <td><a href="javascript:">Download</a></td>
        </tr>
        <?php endfor; ?>
        </tbody>
    </table>
    <div class="registrantsTable onMobi">
        <?php 
        $initialScoreSum = 0;
        $finalScoreSum = 0;
        for ($i = 0; $i < count($submissions); $i++):
          $submission = $submissions[$i];
          $registrationDate = $registrants_map[$submission->handle]->registrationDate;
          $initialScoreSum += $submissions[$i]->initialScore;
          $finalScoreSum += $submissions[$i]->finalScore;        
        ?>
        
        <div class="registrantSection">
            <div class="registrantSectionRow registrantHandle"><a href="<?php bloginfo('wpurl'); ?>/member-profile/<?php echo $submissions[$i]->handle; ?>" class=" coder coderTextYellow"><?php echo $submissions[$i]->handle; ?></a></div>
            <div class="registrantSectionRow">
                <div class="registrantLabel">Registration Date:</div>
                <div class="registrantField"><?php echo date("M d, Y H:i", strtotime("$registrationDate")) . " EST"; ?></div>
                <div class="clear"></div>
            </div>
            <div class="registrantSectionRow">
                <div class="registrantLabel">Submission Date:</div>
                <div class="registrantField"><?php echo date("M d, Y H:i", strtotime("$submission->submissionDate")) . " EST"; ?></div>
                <div class="clear"></div>
            </div>
            <div class="registrantSectionRow">
                <div class="registrantLabel">Screening Score:</div>
                <div class="registrantField"><span class="pass"><?php echo $submissions[$i]->screeningScore; ?></span></div>
                <div class="clear"></div>
            </div>
            <div class="registrantSectionRow">
                <div class="registrantLabel">Initial/ Final Score:</div>
                <div class="registrantField"><a href="javascript:"><?php echo $submissions[$i]->initialScore; ?>/<?php echo $submissions[$i]->finalScore; ?></a></div>
                <div class="clear"></div>
            </div>
            <div class="registrantSectionRow">
                <div class="registrantLabel"><a href="javascript:" class="download">Download</a></div>
                <div class="clear"></div>
            </div>
        </div>
        <?php endfor; ?>
    </div>
    <div class="competitionDetails">
        <div class="registrant">
            <h2>Registrants</h2>
            <div class="values">
                <span class="count"><?php echo $contest->numberOfRegistrants; ?></span>
            </div>
        </div>
        <!--#/end registrant-->
        <div class="round <?php if($nrOfPassingCheckpointSubmissions == -1) { echo 'hide'; } ?>">
            <h2>Checkpoint</h2>
            <!--<div class="values">
                <span class="count"><?php echo $nrOfPassingCheckpointSubmissions; ?><span class="sup">&nbsp;</span></span>
                <span class="type">Submitter</span>
                <span class="type">&nbsp;</span>
            </div>-->
            <div class="values">
                <span class="count"><?php echo $nrOfPassingCheckpointSubmissions; ?></span>
                <span class="type">Submissions</span>
            </div>
            <!--<div class="values">
                <span class="count">N/A<span class="sup">(N/A%)</span></span>
                <span class="type">Passed Review</span>
            </div>-->
        </div>
        <!--#/end round-->
        <div class="round round2">
            <h2>Final</h2>
            <!--<div class="values">
                <span class="count"><?php echo count($submissions); ?><span class="sup">&nbsp;</span></span>
                <span class="type">Submitter</span>
                <span class="type">&nbsp;</span>
            </div>-->
            <div class="values">
                <span class="count"><?php echo count($submissions); ?></span></span>
                <span class="type">Submissions</span>
            </div>
            <!--<div class="values">
                <span class="count">N/A<span class="sup">(N/A%)</span></span>
                <span class="type">Passed Review</span>
            </div>-->
        </div>
        <!--#/end round-->
        <div class="average">
            <h2>AVERAGE SCORE</h2>
            <div class="values">
                <span class="count"><?php echo round($initialScoreSum/$submissionCount, 2); ?><span class="sup">&nbsp;</span></span>
                <span class="type">Average</span>
                <span class="type">Initial Score</span>
            </div>
            <div class="values">
                <span class="count"><?php echo round($finalScoreSum/$submissionCount, 2); ?><span class="sup">&nbsp;</span></span>
                <span class="type">Average</span>
                <span class="type">Final Score</span>
            </div>
        </div>
        <!--#/end round-->
        <div class="clear"></div>
    </div>

</article>

<?php endif; ?>
<?php else: ?>
The are no submissions for this contest.
<?php endif; ?>
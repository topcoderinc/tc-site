<?php
$contestResults = get_contest_results($contestID, $contestType);
$submissions = $contestResults->results;
$submission_map = array();
for ($i = 0; $i < count($submissions); $i++){
  $submission_map[$submissions[$i]->placement] = $submissions[$i];
}

$submissionCount = $contestResults->submissions;

$nrOfCheckpointSubmissions = 0;
$checkpointDetail = get_contest_checkpoint_detail($contestID, $contestType);

if(isset($checkpointDetail->numberOfSubmissions)){
  $nrOfCheckpointSubmissions = $checkpointDetail->numberOfSubmissions;
} else if(isset($checkpointDetail->error)){
  $nrOfCheckpointSubmissions = -1;
}
$nrOfPrizes = count($contest->prize);
if($nrOfPrizes > 0){
  $firstPlacedSubmission = $submission_map[1];
}
if($nrOfPrizes > 1){
  $secondPlacedSubmission = $submission_map[2];
}
?>

<?php if($submissionCount != 0): ?>

<?php
if ($contestType == 'design'):
  if($nrOfPrizes > 2){
    $thirdPlacedSubmission = $submission_map[3];
  }
?>
<article>
    <?php 
    if (isset($firstPlacedSubmission)):
      $registrationDate = $firstPlacedSubmission->registrationDate;
    ?>
    <div class="winnerRow">
        <div class="place first">1<span>st</span></div>
        <!-- #/end place-->
        <div class="image">
            <img src="<?php echo $firstPlacedSubmission->previewDownloadLink; ?>" alt="winner"/>
        </div>
        
        <!-- #/end image-->
        <div class="details">
            <a href="<?php bloginfo('wpurl'); ?>/member-profile/<?php echo $firstPlacedSubmission->handle; ?>" class="coderTextOrange"><?php echo $firstPlacedSubmission->handle; ?></a>
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
                <span class="time"><?php echo date("M d, Y H:i", strtotime("$firstPlacedSubmission->submissionDate")) . " EST"; ?></span>
            </div>
        </div>
        <!-- #/end details-->
        <div class="actions">
            <a href="<?php echo $firstPlacedSubmission->previewDownloadLink; ?>" class="view">View</a>
            <a href="<?php echo $firstPlacedSubmission->submissionDownloadLink; ?>" class="download">Download</a>
        </div>
        <!-- #/end actions-->
        <div class="clear"></div>
    </div>
    <!--#/end winnerrow-->
    <?php endif; ?>
    <?php 
    if (isset($secondPlacedSubmission)):
      $registrationDate = $secondPlacedSubmission->registrationDate;
    ?>
    <div class="winnerRow">
        <div class="place second">2<span>nd</span></div>
        <!-- #/end place-->
        <div class="image">
            <img src="<?php echo $secondPlacedSubmission->previewDownloadLink; ?>" alt="winner" alt="winner"/>
        </div>
        <!-- #/end image-->
        <div class="details">
            <a href="<?php bloginfo('wpurl'); ?>/member-profile/<?php echo $secondPlacedSubmission->handle; ?>" class="coderTextOrange"><?php echo $secondPlacedSubmission->handle; ?></a>
            <div class="">
                <h3>$<?php echo $contest->prize[1]; ?></h3>
                <span class="title">PRIZE</span>
                <span class="date">Registration Date</span>
                <span class="time"><?php echo date("M d, Y H:i", strtotime("$registrationDate")) . " EST"; ?></span>
            </div>
            <div class="">
                <h3><?php echo $secondPlacedSubmission->points; ?></h3>
                <span class="title">Studio Cup Points</span>
                <span class="date">Submitted Date</span>
                <span class="time"><?php echo date("M d, Y H:i", strtotime("$secondPlacedSubmission->submissionDate")) . " EST"; ?></span>
            </div>
        </div>
        <!-- #/end details-->
        <div class="actions">
            <a href="<?php echo $secondPlacedSubmission->previewDownloadLink; ?>" class="view">View</a>
            <a href="<?php echo $secondPlacedSubmission->submissionDownloadLink; ?>" class="download">Download</a>
        </div>
        <!-- #/end actions-->
        <div class="clear"></div>
    </div>
    <!--#/end winnerrow-->
    <?php endif; ?>
    <?php 
    if (isset($thirdPlacedSubmission)):
      $registrationDate = $thirdPlacedSubmission->registrationDate;
    ?>
    <div class="winnerRow hideOnMobi">
        <div class="place third">3<span>rd</span></div>
        <!-- #/end place-->
        <div class="image">
            <img src="<?php echo $thirdPlacedSubmission->previewDownloadLink; ?>" alt="winner" alt="winner"/>
        </div>
        <!-- #/end image-->
        <div class="details">
            <a href="<?php bloginfo('wpurl'); ?>/member-profile/<?php echo $thirdPlacedSubmission->handle; ?>" class="coderTextOrange"><?php echo $thirdPlacedSubmission->handle; ?></a>
            <div class="">
                <h3>$<?php echo $contest->prize[2]; ?></h3>
                <span class="title">PRIZE</span>
                <span class="date">Registration Date</span>
                <span class="time"><?php echo date("M d, Y H:i", strtotime("$registrationDate")) . " EST"; ?></span>
            </div>
            <div class="">
                <h3><?php echo $thirdPlacedSubmission->points; ?></h3>
                <span class="title">Studio Cup Points</span>
                <span class="date">Submitted Date</span>
                <span class="time"><?php echo date("M d, Y H:i", strtotime("$thirdPlacedSubmission->submissionDate")) . " EST"; ?></span>
            </div>
        </div>
        <!-- #/end details-->
        <div class="actions">
            <a href="<?php echo $thirdPlacedSubmission->previewDownloadLink; ?>" class="view">View</a>
            <a href="<?php echo $thirdPlacedSubmission->submissionDownloadLink; ?>" class="download">Download</a>
        </div>
        <!-- #/end actions-->
        <div class="clear"></div>
    </div>
    <!--#/end winnerrow-->
    <?php endif; ?>
    <?php 
    for ($i = 3; $i < $nrOfPrizes; $i++) :
      $submission = $submission_map[$i+1];
      if(isset($submission)):
        $registrationDate = $submission->registrationDate;
        $submissionTime = $submission->submissionDate;
    ?>
    <div class="winnerRow hideOnMobi">
        <div class="place other"><?php echo $i+1; ?><span>th</span></div>
        <!-- #/end place-->
        <div class="image">
            <img src="<?php echo $submission->previewDownloadLink; ?>" alt="winner" alt="winner"/>
        </div>
        <!-- #/end image-->
        <div class="details">
            <a href="<?php bloginfo('wpurl'); ?>/member-profile/<?php echo $submission->handle; ?>" class="coderTextOrange"><?php echo $submission->handle; ?></a>
            <div class="">
                <h3>$<?php if($nrOfPrizes > $i) { echo $contest->prize[$i]; } else { echo "0"; } ?></h3>
                <span class="title">PRIZE</span>
                <span class="date">Registration Date</span>
                <span class="time"><?php echo date("M d, Y H:i", strtotime("$registrationDate")) . " EST"; ?></span>
            </div>
            <div class="">
                <h3><?php echo $submission->points; ?></h3>
                <span class="title">Studio Cup Points</span>
                <span class="date">Submitted Date</span>
                <span class="time"><?php echo date("M d, Y H:i", strtotime("$submissionTime")) . " EST"; ?></span>
            </div>
        </div>
        <!-- #/end details-->
        <div class="actions">
            <a href="<?php echo $submission->previewDownloadLink; ?>" class="view">View</a>
            <a href="<?php echo $submission->submissionDownloadLink; ?>" class="download">Download</a>
        </div>
        <!-- #/end actions-->
        <div class="clear"></div>
    </div>
    <?php endif; ?>
    <?php endfor; ?>
    <!--#/end winnerrow-->
    <div class="winnerRow hideOnMobi hide">
        <div class="place other client">CLIENT<span>SELECTION</span></div>
        <!-- #/end place-->
        <div class="image">
            <img src="" alt="winner" alt="winner"/>
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
            <a href="" class="view" class="view">View</a>
            <a href="" class="download">Download</a>
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
        <div class="round <?php if($nrOfCheckpointSubmissions == -1) { echo 'hide'; } ?>">
            <h2>Round 1 (Checkpoint)</h2>
            <!--<div class="values">
                <span class="count"><?php echo $nrOfCheckpointSubmissions; ?><span class="sup">&nbsp;</span></span>
                <span class="type">Submitter</span>
                <span class="type">&nbsp;</span>
            </div>-->
            <div class="values">
                <span class="count"><?php echo $nrOfCheckpointSubmissions; ?></span>
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
            <a href="<?php echo $firstPlacedSubmission->submissionDownloadLink; ?>" class="download">Download</a>
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
            <a href="<?php echo $secondPlacedSubmission->submissionDownloadLink; ?>" class="download">Download</a>
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
          $registrationDate = $submission->registrationDate;
          $initialScoreSum += $submissions[$i]->initialScore;
          $finalScoreSum += $submissions[$i]->finalScore;
        ?>
        <tr class="<?php if ($i % 2 == 1) { echo 'alt'; } ?>">
            <td class="leftAlign"><a href="<?php bloginfo('wpurl'); ?>/member-profile/<?php echo $submission->handle; ?>" class="coderTextGray"><?php echo $submission->handle; ?></a></td>
            <td><?php echo date("M d, Y H:i", strtotime("$registrationDate")) . " EST"; ?></td>
            <td><?php echo date("M d, Y H:i", strtotime("$submission->submissionDate")) . " EST"; ?></td>
            <td><span class="pass"><?php echo $submission->screeningScore; ?></span></td>
            <td><span class="initialScore"><?php echo $submission->initialScore; ?></span>/<a href="javascript:" class="finalScore"><?php echo $submission->finalScore; ?></a> </td>
            <td><a href="<?php echo $submission->submissionDownloadLink; ?>">Download</a></td>
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
          $registrationDate = $submission->registrationDate;
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
        <div class="round <?php if($nrOfCheckpointSubmissions == -1) { echo 'hide'; } ?>">
            <h2>Checkpoint</h2>
            <!--<div class="values">
                <span class="count"><?php echo $nrOfCheckpointSubmissions; ?><span class="sup">&nbsp;</span></span>
                <span class="type">Submitter</span>
                <span class="type">&nbsp;</span>
            </div>-->
            <div class="values">
                <span class="count"><?php echo $nrOfCheckpointSubmissions; ?></span>
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
<?php

$chkResults = $checkpointData->checkpointResults;
?>

<?php if (!empty( $checkpointData ) && $checkpointData != "Error in processing request"): ?>
  <h1>checkpoint WINNERS</h1>
  <p class="info">The following submissions have received a checkpoint prize.</p>
  <ul class="winnerList">
    <?php
    foreach ($chkResults as $idx => $result) {
      if ($idx == count($chkResults) - 1) {
        $cssClass = "last";
      }
      if ($idx == 0) {
        $boxClass = "firstPrizeIcon";
      }
      if ($idx == 1) {
        $boxClass = "secondPrizeIcon";
      }

      echo '<li class="' . $cssClass . '"><span class="prizeIcon ' . $boxClass . '"></span><span class="box">#' . $result->submissionId . '</span></li>';

    }
    ?>
  </ul>
  <div class="clear"></div>
  <h1>Checkpoint General Feedback</h1>
  <div class="generalFeedback">
    <p>
      <?php echo $checkpointData->generalFeedback; ?></p>
  </div>
  <h1 class="noBorder">personal Feedback</h1>
  <ul class="expandCollaspeList">
    <?php foreach ($chkResults as $idx => $result): ?>
      <li>
        <div class="bar">
          <a href="javascript:;" class="collapseIcon"></a>
          <?php echo 'Feedback #' . $result->submissionId; ?>
        </div>
        <div class="feedBackContent hide">
          <p><?php echo !empty( $result->feedback ) ? $result->feedback : 'N/A'; ?></p>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>
<?php else: ?>
  <h1>Data is not available.</h1>
<?php endif; ?>
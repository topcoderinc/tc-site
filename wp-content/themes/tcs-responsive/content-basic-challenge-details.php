<div class="container">
<header class="pageHeading aboutPage">
  <h1><?php echo $contest->challengeName; ?></h1>

  <h2>CHALLENGE TYPE: <span><?php echo $contest->challengeType; ?></span></h2>
</header>

<div id="stepBox">
<div class="container">

<div class="leftColumn">
  <?php
  if ($contestType != 'design'):
    ?>
    <a class="btn btnAction challengeRegisterBtn disabled" href="javascript:;"><span>1</span>
      <strong>Register For This Challenge</strong></a>
    <a class="btn btnAction challengeSubmissionBtn disabled" target="_blank"
       href="<?php bloginfo("siteurl"); ?>/challenge-details/<?php echo $contestID; ?>/submit"><span>2</span>      <strong>Submit Your Entries</strong></a>
  <?php
  else:
    ?>
    <a class="btn btnAction challengeRegisterBtn disabled" href="javascript:;"><span>1</span> <strong>Register
        For This Challenge</strong></a>
    <a class="btn btnAction challengeSubmissionBtn disabled" target="_blank"
       href="http://studio.topcoder.com/?module=ViewRegistration&ct=<?php echo $contestID; ?>"><span>2</span> <strong>Submit
        Your Entries</strong></a>
    <a class="btn btnAction challengeSubmissionsBtn disabled" target="_blank"
       href="http://studio.topcoder.com/?module=ViewSubmission&ct=<?php echo $contestID; ?>"><span>3</span> <strong>View
        Your Submission</strong></a>
  <?php
  endif;
  ?>
</div>
<?php
if ($contestType != 'design'):
?>
<div class="middleColumn">
<?php
else:
?>
<div class="middleColumn studio">
<?php
endif;
?>
<table class="prizeTable">
<tbody>

<tr>
  <?php
  if ($contestType != 'design' && $contest->challengeType != "Code"):
    ?>
    <td class="fifty">
      <h2>1st PLACE</h2>

      <h3>
        <small>$</small><?php if (isset($contest->prize[0])) {
          echo number_format($contest->prize[0]);
        } ?></h3>
    </td>
    <td class="fifty">
      <h2>2nd PLACE</h2>
      <h3>
        <small>$</small><?php
        echo number_format(isset($contest->prize[1])? $contest->prize[1] : "0"); ?>
      </h3>
    </td>
  <?php
  else:
    ?>
    <?php
    if (isset($contest->prize[0])):
      ?>
      <td class="twenty">
        <h2>1st PLACE</h2>

        <h3>
          <small>$</small><?php echo number_format($contest->prize[0]); ?></h3>
      </td>
    <?php
    else:
      ?>
      <td class="twenty noPrize">
        <h2>1st PLACE</h2>

        <h3>
          <small>$</small><?php echo number_format(0) ?></h3>
      </td>
    <?php
    endif;
    ?>
    <?php
    if (isset($contest->prize[1])):
      ?>
      <td class="twenty">
        <h2>2nd PLACE</h2>

        <h3>
          <small>$</small><?php echo number_format($contest->prize[1]); ?></h3>
      </td>
    <?php
    else:
      ?>
      <td class="twenty noPrize">
        <h2>2nd PLACE</h2>

        <h3>
          <small>$</small><?php echo number_format(0) ?></h3>
      </td>
    <?php
    endif;
    ?>
    <?php
    if (isset($contest->prize[2])):
      ?>
      <td class="twenty">
        <h2>3rd PLACE</h2>

        <h3>
          <small>$</small><?php echo number_format($contest->prize[2]); ?></h3>
      </td>
    <?php
    else:
      ?>
      <td class="twenty noPrize">
        <h2>3rd PLACE</h2>

        <h3>
          <small>$</small><?php echo number_format(0) ?></h3>
      </td>
    <?php
    endif;
    ?>
    <?php
    if (isset($contest->prize[3])):
      ?>
      <td class="twenty">
        <h2>4th PLACE</h2>

        <h3>
          <small>$</small><?php echo number_format($contest->prize[3]); ?></h3>
      </td>
    <?php
    else:
      ?>
      <td class="twenty noPrize">
        <h2>4th PLACE</h2>

        <h3>
          <small>$</small><?php echo number_format(0) ?></h3>
      </td>
    <?php
    endif;
    ?>
    <?php
    if (isset($contest->prize[4])):
      ?>
      <td class="twenty">
        <h2>5th PLACE</h2>

        <h3>
          <small>$</small><?php echo number_format($contest->prize[4]); ?></h3>
      </td>
    <?php
    else:
      ?>
      <td class="twenty noPrize">
        <h2>5th PLACE</h2>

        <h3>
          <small>$</small><?php echo number_format(0) ?></h3>
      </td>
    <?php
    endif;
    ?>
    <?php
    if (sizeof($contest->prize) > 5):
      ?>
      <td class="morePayments active closed" rowspan="<?php echo 2 + (int) ((sizeof($contest->prize) - 5) / 5) ?>">
      </td>
    <?php
    else:
      ?>
      <td class="morePayments inactive" rowspan="<?php echo 2 + (int) ((sizeof($contest->prize) - 5) / 5) ?>">
      </td>
    <?php
    endif;
    ?>
  <?php
  endif;
  ?>
</tr>
<?php
if (sizeof($contest->prize) > 5) {
  for ($i = 0; $i < (sizeof($contest->prize) - 5) / 5; $i++) :
    ?>
    <tr class="additionalPrizes hide">
      <?php
      if (sizeof($contest->prize) > 5 + $i * 5):
        ?>
        <td class="twenty">
          <h2><?php echo 5 + $i * 5 + 1; ?>th PLACE</h2>

          <h3>
            <small>$</small><?php echo number_format($contest->prize[5 + $i * 5]); ?></h3>
        </td>
      <?php
      endif;
      ?>
      <?php
      if (sizeof($contest->prize) > 5 + $i * 5 + 1):
        ?>
        <td class="twenty">
          <h2><?php echo 5 + $i * 5 + 2; ?>th PLACE</h2>

          <h3>
            <small>$</small><?php echo number_format($contest->prize[5 + $i * 5 + 1]); ?></h3>
        </td>
      <?php
      endif;
      ?>
      <?php
      if (sizeof($contest->prize) > 5 + $i * 5 + 2):
        ?>
        <td class="twenty">
          <h2><?php echo 5 + $i * 5 + 3; ?>th PLACE</h2>

          <h3>
            <small>$</small><?php echo number_format($contest->prize[5 + $i * 5 + 2]); ?></h3>
        </td>
      <?php
      endif;
      ?>
      <?php
      if (sizeof($contest->prize) > 5 + $i * 5 + 3):
        ?>
        <td class="twenty">
          <h2><?php echo 5 + $i * 5 + 4; ?>th PLACE</h2>

          <h3>
            <small>$</small><?php echo number_format($contest->prize[5 + $i * 5 + 3]); ?></h3>
        </td>
      <?php
      endif;
      ?>
      <?php
      if (sizeof($contest->prize) > 5 + $i * 5 + 4):
        ?>
        <td class="twenty">
          <h2><?php echo 5 + $i * 5 + 5; ?>th PLACE</h2>

          <h3>
            <small>$</small><?php echo number_format($contest->prize[5 + $i * 5 + 4]); ?></h3>
        </td>
      <?php
      endif;
      ?>
    </tr>
  <?php
  endfor;
}
?>
<tr>
  <?php
  if ($contestType != 'design'):
    ?>
    <td
      <?php
      //Adjust the colspan for CODE challenge type since the 5 prizes add more columns
      if ($contest->challengeType == "Code") {
        echo 'colspan="2"';
      }
      ?>
      >
      <p class="realibilityPara">Reliability Bonus

        <?php
        if (empty($contest->reliabilityBonus)):
          ?>
          <span><?php echo "N/A" ?></span>
        <?php
        else:
          ?>
          <span>$<?php echo number_format($contest->reliabilityBonus); ?></span>
        <?php
        endif;
        ?>
      </p>
    </td>
    <td
      <?php
      //Adjust the colspan for CODE challenge type since the 5 prizes add more columns
      if ($contest->challengeType == "Code") {
        echo 'colspan="3"';
      }
      ?>
      >

      <p class="drPointsPara">DR Points <span><?php echo isset($contest->digitalRunPoints) ? $contest->digitalRunPoints : "N/A" ; ?></span></p>
    </td>
  <?php
  else:
    ?>
    <td colspan="2">
      <?php
      if (isset($contest->digitalRunPoints)):
        ?>
        <p class="scPoints"><span><?php echo $contest->digitalRunPoints; ?></span> STUDIO CUP POINTS</p>
      <?php
      else:
        ?>
        <p class="scPoints">NO STUDIO CUP POINTS</p>
      <?php
      endif;
      ?>
    </td>
    <td colspan="3">
      <p class="scPoints"><span><?php echo $contest->numberOfCheckpointsPrizes; ?></span> CHECKPOINT AWARDS WORTH <span>$<?php echo $contest->topCheckPointPrize; ?></span>
        EACH</p>
    </td>
  <?php
  endif;
  ?>
</tr>
</tbody>
</table>

<div class="prizeSlider hide">
  <ul>
    <li class="slide">
      <table>
        <tbody>
        <tr>
          <?php
          if (isset($contest->prize[0])):
            ?>
            <td class="twenty">
              <h2>1st PLACE</h2>

              <h3>
                <small>$</small><?php echo number_format($contest->prize[0]); ?></h3>
            </td>
          <?php
          else:
            ?>
            <td class="twenty noPrize">
              <h2>1st PLACE</h2>

              <h3>
                <small>$</small><?php echo number_format(0) ?></h3>
            </td>
          <?php
          endif;
          ?>
          <?php
          if (isset($contest->prize[1])):
            ?>
            <td class="twenty">
              <h2>2nd PLACE</h2>

              <h3>
                <small>$</small><?php echo number_format($contest->prize[1]); ?></h3>
            </td>
          <?php
          else:
            ?>
            <td class="twenty noPrize">
              <h2>2nd PLACE</h2>

              <h3>
                <small>$</small><?php echo number_format(0) ?></h3>
            </td>
          <?php
          endif;
          ?>
          <?php
          if (isset($contest->prize[2])):
            ?>
            <td class="twenty">
              <h2>3rd PLACE</h2>

              <h3>
                <small>$</small><?php echo number_format($contest->prize[2]); ?></h3>
            </td>
          <?php
          else:
            ?>
            <td class="twenty noPrize">
              <h2>3rd PLACE</h2>

              <h3>
                <small>$</small><?php echo number_format(0) ?></h3>
            </td>
          <?php
          endif;
          ?>
        </tr>
        </tbody>
      </table>
    </li>
    <?php
    if (sizeof($contest->prize) > 3) {
      for ($i = 0; $i < (sizeof($contest->prize) - 3) / 3; $i++) :
        ?>
        <li>
          <table>
            <tbody>
            <tr>
              <?php
              if (sizeof($contest->prize) > 3 + $i * 3):
                ?>
                <td class="twenty">
                  <h2><?php echo 3 + $i * 3 + 1; ?>th PLACE</h2>

                  <h3>
                    <small>$</small><?php echo number_format($contest->prize[3 + $i * 3]); ?></h3>
                </td>
              <?php
              endif;
              ?>
              <?php
              if (sizeof($contest->prize) > 3 + $i * 3 + 1):
                ?>
                <td class="twenty">
                  <h2><?php echo 3 + $i * 3 + 2; ?>th PLACE</h2>

                  <h3>
                    <small>$</small><?php echo number_format($contest->prize[3 + $i * 3 + 1]); ?></h3>
                </td>
              <?php
              endif;
              ?>
              <?php
              if (sizeof($contest->prize) > 3 + $i * 3 + 2):
                ?>
                <td class="twenty">
                  <h2><?php echo 3 + $i * 3 + 3; ?>th PLACE</h2>

                  <h3>
                    <small>$</small><?php echo number_format($contest->prize[3 + $i * 3 + 2]); ?></h3>
                </td>
              <?php
              endif;
              ?>
            </tr>
            </tbody>
          </table>
        </li>
      <?php
      endfor;
    }
    ?>
  </ul>
  <div>
    <table>
      <tbody>
      <tr>
        <td>
          <?php
          if (isset($contest->digitalRunPoints)):
            ?>
            <p class="scPoints"><span><?php echo $contest->digitalRunPoints; ?></span> STUDIO CUP POINTS</p>
          <?php
          else:
            ?>
            <p class="scPoints">NO STUDIO CUP POINTS</p>
          <?php
          endif;
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <p class="scPoints"><span><?php echo $contest->numberOfCheckpointsPrizes; ?></span> CHECKPOINT AWARDS WORTH
            <span>$100</span> EACH</p>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</div>
</div>

<div class="rightColumn">

  <div class="nextBox ">

    <div class="nextBoxContent nextDeadlineNextBoxContent">
      <div class="icoTime">
        <span class="nextDTitle">Current Phase</span>
        <span
          class="CEDate"><?php 
          //Bugfix I-106745: if current status of contest is not Active, output current contest status, else output current contest phase if active.
          echo (strpos($contest->currentStatus, 'Active') === FALSE) ? $contest->currentStatus : $contest->currentPhaseName; ?></span>
      </div>
      <span class="timeLeft">
      <?php
      //Bugfix I-106745: Added check for cancelled contest before display of current phase remaining time
      if ($contest->currentStatus !== 'Completed' && $contest->currentStatus !== 'Deleted' && strpos($contest->currentStatus, 'Cancelled') === FALSE && $contest->currentPhaseRemainingTime > 0) {
        $dtF = new DateTime("@0");
        $dtT = new DateTime("@{$contest->currentPhaseRemainingTime}");
        echo $dtF->diff($dtT)->format('%a <small>Days</small> %h <small>Hours</small> %i <small>Mins</small>');
      }
      ?>
      </span>
    </div>
    <!--End nextBoxContent-->
    <?php
    if ($contestType != 'design'):
      ?>
      <div class="nextBoxContent allDeadlineNextBoxContent hide">
        <p><label>Posted On:</label>
          <span><?php echo date(
              "M d, Y H:i T",
              strtotime("$contest->postingDate"));?>
          </span>
        </p>


        <p><label>Register By:</label>
         <span><?php echo date(
             "M d, Y H:i T",
             strtotime("$contest->registrationEndDate"));?>
         </span>
        </p>

        <!-- Issue ID: I-107591 - add Final Submission in all deadline if finalFixEndDate field is set -->
        <p <?php echo (isset($contest->finalFixEndDate)) ? '' : 'class="last"';?>><label>Submit By:</label>
          <span><?php echo date("M d, Y H:i T", strtotime("$contest->submissionEndDate"));?></span>
        </p>

		<?php if (isset($contest->finalFixEndDate)): ?>
		<p class="last"><label>Final Submission:</label>
          <span><?php echo date("M d, Y H:i T", strtotime("$contest->finalFixEndDate"));?></span>
        </p>
        <?php endif; ?>

      </div>
      <!--End nextBoxContent-->
    <?php
    else:
      ?>
      <div class="nextBoxContent allDeadlineNextBoxContent studio hide">
        <p><label>Start Date:</label>
          <span><?php echo date(
              "M d, Y H:i T",
              strtotime("$contest->postingDate"));?>
          </span>
        </p>
        <?php if ($contest->checkpointSubmissionEndDate != "") : ?>
          <p><label>Checkpoint:</label>
          <span><?php echo date(
              "M d, Y H:i T",
              strtotime("$contest->checkpointSubmissionEndDate"));?>
          </span>
          </p>
        <?php endif; ?>

        <p><label>End Date:</label>
          <span><?php echo date(
              "M d, Y H:i T",
              strtotime("$contest->submissionEndDate"));?>
          </span>
        </p>

        <p class="last"><label>Winners Announced:</label>
          <span><?php echo date(
              "M d, Y H:i T",
              strtotime("$contest->appealsEndDate"));?>
          </span>
        </p>
      </div>
      <!--End nextBoxContent-->
    <?php
    endif;
    ?>
  </div>

  <!--End nextBox-->
  <div class="deadlineBox">

    <div class="deadlineBoxContent nextDeadlinedeadlineBoxContent ">
      <a class="viewAllDeadLineBtn" href="javascript:">View all deadlines +</a>
    </div>
    <!--End deadlineBoxContent-->
    <div class="deadlineBoxContent allDeadlinedeadlineBoxContent hide">
      <a class="viewNextDeadLineBtn" href="javascript:">View next deadline +</a>
    </div>
    <!--End deadlineBoxContent-->
  </div>
  <!--End deadlineBox-->
</div>

</div>
</div>
<!-- /#hero -->

</div>
<!-- /.pageHeading -->

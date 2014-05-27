<?php

$activeTab = $tab;
add_action('wp_head', 'tc_challenge_details_js');
function tc_challenge_details_js() {
  global $contest, $contestType, $contestID, $activeTab;

  if (!isset($contest->registrationEndDate)) {
    $contest = get_contest_detail('', $contestID, $contestType);
  }

  $regEnd = strtotime($contest->registrationEndDate) | 1;
  $submissionEnd = strtotime($contest->submissionEndDate) | 1;

  ?>
  <script type="text/javascript">
    var activeTab = "<?php echo $activeTab;?>";
    var registrationUntil = new Date(<?php echo $regEnd ?> * 1000);
    var submissionUntil = new Date(<?php echo $submissionEnd ?> * 1000);
    var challengeId = "<?php echo $contestID;?>";
    var challengeType = "<?php echo $contestType;?>";
    var autoRegister = "<?php echo get_query_var('autoRegister');?>";
  </script>
<?php
}

/**
 * Template Name: Challenge details
 */

$isChallengeDetails = TRUE;

$values = get_post_custom($post->ID);

$userkey = get_option('api_user_key');
$siteURL = site_url();


$contestID = get_query_var('contestID');

$contestType    = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_STRING);
$contestType    = empty( $contestType ) ? "develop" : $contestType;
$noCache        = get_query_var('nocache');
$contest        = get_contest_detail('', $contestID, $contestType, $noCache);
$registrants    = empty( $contest->registrants ) ? array() : $contest->registrants;
$checkpointData = get_checkpoint_details($contestID, $contestType);

$registerDisable = FALSE;
$submitDisabled  = FALSE;


/**
 * Format Date Strings
 *
 * @todo Tom add this to angular
 */

if ($contest) {
  tc_remove_end_of_date($contest->postingDate);
  tc_remove_end_of_date($contest->registrationEndDate);
  tc_remove_end_of_date($contest->checkpointSubmissionEndDate);
  tc_remove_end_of_date($contest->submissionEndDate);
  tc_remove_end_of_date($contest->appealsEndDate);
  tc_remove_end_of_date($contest->currentPhaseEndDate);
}


function tc_remove_end_of_date(&$date) {
  $date = reset(explode('.', $date));
}


/*
$curDate = new DateTime();
$registerDisable = true;
if ($contest->registrationEndDate) {
  $regDate = new DateTime($contest->registrationEndDate);
  if ($regDate > $curDate) {
    $registerDisable = false;
  }

  if (is_user_register_for_challenge($handle, $contest)) {
    $registerDisable = TRUE;
  }

  return $registerDisable;
}


/**
 * Should the submit button be active
 *
 * Submit button should be disabled:
 *  - If submission date is not passed and challenge is not complete
 *  - If there is a user and the user is registered
 *
 * @param $contest
 *
 * @return bool
 */
function challenge_submit_disabled($contest) {
  global $handle;
  $submitDisabled = TRUE;

  if ($contest->submissionEndDate && $contest->currentStatus !== "Completed") {
    $curDate    = new DateTime();
    $submitDate = new DateTime($contest->submissionEndDate);
    if ($submitDate > $curDate) {
      $submitDisabled = FALSE;
    }
  }

  if (!is_user_register_for_challenge($handle, $contest)) {
    $submitDisabled = TRUE;
  }

  return $submitDisabled;
}


// Ad submission dates to registrants
// @TODO move this to a class
if (!empty( $contest->submissions )) {
  $submission_map = array();
  switch ($contestType) {
    case "develop":
      $submission_map = createDevelopSubmissionMap($contest);
      foreach ($registrants as &$registrant) {
        if ($submission_map[ $registrant->handle ]) {
          $registrant->lastSubmissionDate = $submission_map[ $registrant->handle ]->submissionDate;
          $registrant->submissionStatus = $submission_map[ $registrant->handle  ]->submissionStatus;
        }
      }
      break;
    case "design":
      $submission_map = createDesignSubmissionMap($contest);
      foreach ($registrants as &$registrant) {
        if ($submission_map[ $registrant->handle ]) {
          $registrant->lastSubmissionDate = $submission_map[ $registrant->handle ]->submissionTime;
          $registrant->submissionStatus = $submission_map[ $registrant->handle  ]->submissionStatus;
        }
      }
      break;
  }
}


function createDesignSubmissionMap($contest) {
  $submission_map = array();
  foreach ($contest->submissions as $submission) {
    if ($submission_map[ $submission->submitter ]) {
      $sub_date = new DateTime($submission->submissionDate);
      if ($cur_date->diff($sub_date) > 0) {
        $submission_map[ $submission->submitter ] = $submission;
        $cur_date                                 = new DateTime($submission->submissionDate);
      }
    }
    else {
      $submission_map[ $submission->submitter ] = $submission;
      $cur_date                                 = new DateTime($submission->submissionDate);
    }
  }

  return $submission_map;
}

function createDevelopSubmissionMap($contest) {
  $submissions = array_filter(
    $contest->submissions,
    function ($submission) {
      if ($submission->submissionStatus === "Active") {
        return TRUE;
      }
      else {
        return FALSE;
      }
    }
  );

  // 'user' => latest submissions
  $submission_map = array();
  foreach ($submissions as $submission) {
    if ($submission_map[ $submission->handle ]) {
      $sub_date = new DateTime($submission->submissionDate);
      if ($cur_date->diff($sub_date) > 0) {
        $submission_map[ $submission->handle ] = $submission;
        $cur_date                              = new DateTime($submission->submissionDate);
      }
    }
    else {
      $submission_map[ $submission->handle ] = $submission;
      $cur_date                              = new DateTime($submission->submissionDate);
    }
  }

  return $submission_map;
}

$documents = isset($contest->Documents) ? $contest->Documents : array();

// need for header file
$contest_type = $contestType;

include locate_template('header-challenge-landing.php');

?>

<div class="content challenge-detail <?php if ($contestType != 'design') {
  echo 'develop';
} ?>">
<div id="main">

<?php include( locate_template('content-basic-challenge-details.php') ); ?>


<article id="mainContent" class="splitLayout <?php if (!empty( $activeTab )) {echo 'currentTab-' . $activeTab;} ?>">
<div class="container">
<div class="rightSplit  grid-3-3">
<div class="mainStream partialList">

<section class="tabsWrap">
<nav class="tabNav">
  <div class="topRightTitle topRightTitleAlt">
    <?php if ($contestType != 'design'): ?>
      <a href="http://apps.topcoder.com/forums/?module=Category&categoryID=<?php echo $contest->forumId; ?>"
         class="contestForumIcon" target="_blank">Challenge Discussion</a>
    <?php else: ?>
      <a href="http://studio.topcoder.com/forums?module=ThreadList&forumID=<?php echo $contest->forumId; ?>"
         class="contestForumIcon" target="_blank">Challenge Discussion</a>
    <?php endif; ?>
  </div>
  <ul>
    <?php if ($contestType != 'design'): ?>
      <li><a href="#contest-overview" class="<?php if ($tab !== "checkpoints") { echo "active"; } ?> link">Details</a></li>
      <li><a href="#viewRegistrant" class="link">Registrants</a></li>
      <?php if (( !empty( $checkpointData ) && $checkpointData != "Error in processing request" ) || ( $tab === "checkpoints" )): ?>
        <li><a href="#checkpoints" class="link <?php if ($tab === "checkpoints") { echo "active"; } ?>">Checkpoints</a></li>
      <?php endif; ?>
      <li><a href="#winner" class="link">Results</a></li>

    <?php else: ?>
      <li><a href="#contest-overview" class="<?php if ($tab !== "checkpoints") { echo "active"; } ?> link">Details</a></li>
      <li><a href="#viewRegistrant" class="link">Registrants</a></li>
      <?php if (( !empty( $checkpointData ) && $checkpointData != "Error in processing request" ) || ( $tab === "checkpoints" )): ?>
        <li><a href="#checkpoints" class="link <?php if ($tab === "checkpoints") { echo "active"; } ?>">Checkpoints</a></li>
      <?php endif; ?>
      <?php if (strpos($contest->currentPhaseName, 'Submission') !== FALSE): ?>
        <li><span class="inactive">Submissions</span></li>
      <?php else: ?>
        <li><a href="#submissions" class="link">Submissions</a></li>
      <?php endif; ?>
      <?php if (strpos($contest->currentPhaseName, 'Submission') !== FALSE ||
          strpos($contest->currentPhaseName, 'Screening') !== FALSE ||
          strpos($contest->currentPhaseName, 'Review') !== FALSE): ?>
        <li><span class="inactive">Results</span></li>
      <?php else: ?>
        <li><a href="#winner" class="link">Results</a></li>
      <?php endif; ?>
    <?php endif; ?>
  </ul>
</nav>
<nav class="tabNav firstTabNav designFirstTabNav mobile hide">
  <ul>
    <li><a href="#contest-overview" class="active link">Details</a></li>
    <li><a href="#viewRegistrant" class="link">Registrants</a></li>
  </ul>
</nav>
<nav class="tabNav firstTabNav designSecondTabNav mobile hide">
  <ul>
    <?php if (strpos($contest->currentPhaseName, 'Submission') !== FALSE): ?>
      <li><span class="inactive">Checkpoints</span></li>
    <?php else: ?>
      <?php if (!empty( $checkpointData ) && empty( $checkpointData->error )): ?>
      <li><a href="<?php echo CURRENT_FULL_URL; ?>&tab=checkpoints" class="link">Checkpoints</a></li>
    <?php endif; ?>
    <?php endif; ?>
    <?php if (strpos($contest->currentPhaseName, 'Submission') !== FALSE): ?>
      <li><span class="inactive">Submissions</span></li>
    <?php else: ?>
      <li><a href="#submissions" class="link">Submissions</a></li>
    <?php endif; ?>
    </li>
    <li>
      <?php if (strpos($contest->currentPhaseName, 'Submission') !== FALSE ||
                strpos($contest->currentPhaseName, 'Screening') !== FALSE ||
                strpos($contest->currentPhaseName, 'Review') !== FALSE): ?>
    <li><span class="inactive">Results</span></li>
  <?php else: ?>
    <li><a href="#winner" class="link">Results</a></li>
  <?php endif; ?>
    </li>
  </ul>
</nav>

<div id="contest-overview" class="tableWrap <?php echo ( $activeTab == 'checkpoints' ) ? 'hide' : ''; ?> tab">
  <?php if ($contestType != 'design'): ?>
  <article id="contestOverview">
    <h1>Challenge Overview</h1>

    <p><?php echo $contest->detailedRequirements; ?></p>

    <article id="platforms">
      <h1>Platforms</h1>
      <?php

      echo '<ul>';
      if (!empty( $contest->platforms )) {
        foreach ($contest->platforms as $value) {
          echo '<li><strong>' . $value . '</li></strong>';
        }
      }
      else {
        echo '<li><strong>Not Specified</li></strong>';
      }
      echo '</ul>';
      ?>
    </article>

    <article id="technologies">
      <h1>Technologies</h1>
      <div class="technologyTags">
      <?php

      echo '<ul>';
      if (!empty( $contest->technology )) {
        foreach ($contest->technology as $value) {
          echo '<li><span>' . $value . '</span></li>';
        }
      }
      else {
        echo '<li><strong>Not Specified</li></strong>';
      }
      echo '</ul>';
      ?>
      <div class="clear"></div>
      </div>
    </article>

    <h3>Final Submission Guidelines</h3>
    <?php echo $contest->finalSubmissionGuidelines; ?>



    <article id="payments">
      <h1>Payments</h1>

      <p>TopCoder will compensate members in accordance with the payment structure of this challenge.
        Initial payment for the winning member will be distributed in two installments. The first payment
        will be made at the closure of the approval phase. The second payment will be made at the
        completion of the support period.</p>

      <h2>Reliability Rating and Bonus</h2>

      <p>For challenges that have a reliability bonus, the bonus depends on the reliability rating at
        the moment of registration for that project. A participant with no previous projects is
        considered to have no reliability rating, and therefore gets no bonus.
        Reliability bonus does not apply to Digital Run winnings. Since reliability rating is
        based on the past 15 projects, it can only have 15 discrete values.<br>
        <a href="http://help.topcoder.com/development/understanding-reliability-and-ratings/">Read more.</a></p>
    </article>


    <article id="eligibility">
      <h1>Eligibility</h1>

      <p>You must be a TopCoder member, at least 18 years of age, meeting all of the membership requirements. In
        addition, you must fit into one of the following categories.</p>

      <p>If you reside in the United States, you must be either:</p>

      <p>
      <ul>A US Citizen
        <li>A Lawful Permanent Resident of the US</li>
        <li>A temporary resident, asylee, refugee of the U.S., or have a lawfully issued work authorization card
          permitting unrestricted employment in the U.S.
        </li>
      </ul>
      </p>
      <p>If you do not reside in the United States:</p>
      <ul>
        <li>You must be authorized to perform services as an independent contractor.
          (Note: In most cases you will not need to do anything to become authorized)
        </li>
      </ul>

    </article>


  </article>

</div>
<?php else: ?>
<article id="contestOverview">

  <article id="contestSummary">
    <h1>CONTEST SUMMARY</h1>

    <p class="paragraph"></p>

    <p><?php echo $contest->introduction; ?></p>

    <p class="paragraph1"><b>Please read the contest specification carefully and watch the forums for any
        questions or feedback concerning this contest. It is important that you monitor any updates
        provided by the client or Studio Admins in the forums. Please post any questions you might have for
        the client in the forums.</b></p>
  </article>

  <article id="studioTournamentFormat">
    <h1>CHALLENGE FORMAT</h1>

    <p class="paragraph">This competition will be run as a two-round challenge.</p>

    <span class="subTitle">Round One (1)</span>

    <p class="paragraph"></p>

    <p style="margin: 0px 0px 0px 15px; padding: 0px; color: rgb(64, 64, 64);"><span
        style="line-height: 1.6em;"><?php echo $contest->round1Introduction; ?></span></p>

    <span class="subTitle">Round Two (2)</span>

    <p class="paragraph"></p>

    <p><span style="color: rgb(64, 64, 64); font-size: 13px;"><?php echo $contest->round2Introduction; ?></span></p>

    <p></p>


    <h6 class="smallTitle red">Regarding the Rounds:</h6>

    <p></p>

    <ul class="red">
      <li>To be eligible for Round 1 prizes and design feedback, you must submit before the Checkpoint
        deadline.
      </li>
      <li>A day or two after the Checkpoint deadline, the contest holder will announce Round 1 winners and
        provide design feedback to those winners in the "Checkpoints" tab above.
      </li>
      <li>You must submit to Round 1 to be eligible to compete in Round 2. If your submission fails
        screening for a small mistake in Round 1, you may still be eligible to submit to Round 2.
      </li>
      <li>Every competitor with a passing Round 1 submission can submit to Round 2, even if they didn't
        win a Checkpoint prize.
      </li>
      <li><a
          href="http://help.topcoder.com/design/submitting-to-a-design-challenge/multi-round-checkpoint-design-challenges/">Learn
          more here</a>.
      </li>
    </ul>
  </article>

  <article id="fullDescription">
    <h1>FULL DESCRIPTION &amp; PROJECT GUIDE</h1>

    <p><?php echo $contest->detailedRequirements; ?></p>
  </article>

  <article id="stockPhotography">
    <h1>STOCK PHOTOGRAPHY</h1>

      <?php
      if ($contest->allowStockArt != "false") {
          echo '<p> Stock photography is allowed in this challenge.<br>
                      <a href="http://help.topcoder.com/design/design-copyright-and-font-policies/policy-for-stock-photos-in-design-submissions/">See this page for more details.</a></p>';
      } else {
          echo '<p>Stock photography is not allowed in this challenge. All submitted elements must be designed solely by you.<br>
                      <a href="http://help.topcoder.com/design/design-copyright-and-font-policies/policy-for-stock-photos-in-design-submissions/">See
                        this page for more details.</a></p>';
      }
      ?>

  </article>

  <article id="howtosubmit">
    <h1>How to Submit</h1>

    <p>
    <ul class="howToSubmit">
      <li>New to Studio? <a
          href="http://help.topcoder.com/design/submitting-to-a-design-challenge/getting-started-in-design-challenges/"
          target="_blank">Learn how to compete
          here</a>.
      </li>
      <li>Upload your submission in three parts (<a
          href="http://help.topcoder.com/design/submitting-to-a-design-challenge/formatting-your-submission-for-design-challenges/"
          target="_blank">Learn more here</a>). Your design should be finalized and should contain only a single design
        concept (do not include multiple designs in a single submission).
      </li>
      <li>If your submission wins, your source files must be correct and
        "<a href="http://help.topcoder.com/design/submitting-to-a-design-challenge/design-final-fixes-policies/"
            target="_blank">Final Fixes</a>" (if
        applicable) must be completed before payment can be released.
      </li>
      <li>You may submit as many times as you'd like during the submission phase, but only the number of files
        listed above in the Submission Limit that you rank the highest will be considered. You can change
        the order of your submissions at any time during the submission phase. If you make revisions to your
        design, please delete submissions you are replacing.
      </li>
    </ul>
    </p>
  </article>

  <article id="winnerselection">
    <h1>Winner Selection</h1>

    <p>
      Submissions are viewable to the client as they are entered into the challenge. Winners are selected by the
      client and are chosen solely at the Client's discretion.
    </p>
  </article>

  <article id="payments">
    <h1>Payments</h1>

    <p>TopCoder will compensate members in accordance with the payment structure of this challenge.
      Initial payment for the winning member will be distributed in two installments. The first payment
      will be made at the closure of the approval phase. The second payment will be made at the
      completion of the support period.</p>

    <h2>Reliability Rating and Bonus</h2>

    <p>For challenges that have a reliability bonus, the bonus depends on the reliability rating at
      the moment of registration for that project. A participant with no previous projects is
      considered to have no reliability rating, and therefore gets no bonus.
      Reliability bonus does not apply to Digital Run winnings. Since reliability rating is
      based on the past 15 projects, it can only have 15 discrete values.<br>
      <a href="http://help.topcoder.com/development/understanding-reliability-and-ratings/">Read more.</a></p>
  </article>


  <article id="eligibility">
    <h1>Eligibility</h1>

    <p>You must be a TopCoder member, at least 18 years of age, meeting all of the membership requirements. In addition,
      you must fit into one of the following categories.</p>

    <p>If you reside in the United States, you must be either:</p>

    <p>
    <ul>A US Citizen
      <li>A Lawful Permanent Resident of the US</li>
      <li>A temporary resident, asylee, refugee of the U.S., or have a lawfully issued work authorization card
        permitting unrestricted employment in the U.S.
      </li>
    </ul>
    </p>
    <p>If you do not reside in the United States:</p>
    <ul>
      <li>You must be authorized to perform services as an independent contractor.
        (Note: In most cases you will not need to do anything to become authorized)
      </li>
    </ul>

  </article>

</article>

</div>
<?php endif; ?>
<div id="viewRegistrant" class="tableWrap hide tab">


  <article>
    <h1>REGISTRANTS</h1>
    <table class="registrantsTable">
      <thead>
      <tr>
        <th class="handleColumn">
          <div>Handle</div>
        </th>
        <?php if ($contestType != 'design'): ?>
          <th class="ratingColumn">
            <div>Rating</div>
          </th>

          <th class="reliabilityColumn">
            <div>Reliability</div>
          </th>
        <?php endif; ?>
        <th class="regDateColumn">
          <div>Registration Date</div>
        </th>
        <th class="subDateColumn">
          <div>Submission Date</div>
        </th>
        <th class="successColumn">
          <div></div>
        </th>
      </tr>
      </thead>
      <tbody>
      <?php foreach ($registrants as $key => $value) {
        $handleLink = get_bloginfo("siteurl") . "/member-profile/" . $value->handle;
        echo '<tr >';
        echo '<td class="handleColumn">';
        echo '<span>' . '<a href="' . $handleLink . '" style="' . $value->color . '">' . $value->handle . '</a></span>';
        echo '</td>';
        if ($contestType != 'design') {
          echo '<td class="ratingColumn">';
          echo '<span style="' . $value->colorStyle . '">';
          echo isset( $value->rating ) ? $value->rating : 0;
          echo '</span>';
          echo '</td>';

          echo '<td class="reliabilityColumn">';
          echo $value->reliability;
          echo '</td>';
        }

        echo '<td class="regDateColumn">';
        echo date("M d, Y H:i T", strtotime($value->registrationDate));
        echo '</td>';
        echo '<td class="subDateColumn">';
        if ($value->lastSubmissionDate) {
          echo date("M d, Y H:i T", strtotime($value->lastSubmissionDate));
        }
        else {
          echo "--";
        }
        echo '</td>';
        echo '<td>';
        if ($value->submissionStatus == "Active") {
          echo '<i class="successIcon"></i>';
        }
        else if ($value->submissionStatus != "") {
          echo '<i class="failureIcon"></i>';
        }
        echo '</td>';
        echo '</tr>';

      }  ?>
      </tbody>
    </table>

    <div class="registrantsTable mobile hide">
      <?php foreach ($registrants as $key => $value) {
        $handleLink = get_bloginfo("siteurl") . "/member-profile/" . $value->handle;
        echo '<div class="registrantSection">';
        echo '<div class="registrantSectionRow registrantHandle">' . '<a href="' . $handleLink . '" style="' . $value->color . '">' . $value->handle . '</a></div>';
        if ($contestType != 'design') {
          echo '<div class="registrantSectionRow">';
          echo '<div class="registrantLabel">Rating:</div>';
          echo '<div class="registrantField">';
          echo '<span style="' . $value->ratings_color . '">';
          echo $value->max_rating;
          echo '</span>';
          echo '</div>';
          echo '<div class="clear"></div>';
          echo '</div>';
          echo '<div class="registrantSectionRow">';
          echo '<div class="registrantLabel">Reliability:</div>';
          echo '<div class="registrantField">' . $value->reliability . '</div>';
          echo '<div class="clear"></div>';
          echo '</div>';
        }
        echo '<div class="registrantSectionRow">';
        echo '<div class="registrantLabel">Registration Date:</div>';
        echo '<div class="registrantField">';
        echo date(
               "M d, Y H:i T",
               strtotime($value->registrationDate)
             ) . '</div>';
        echo '<div class="clear"></div>';
        echo '</div>';
        echo '<div class="registrantSectionRow">';
        echo '<div class="registrantLabel">Submission Date:</div>';
        echo '<div class="registrantField">';
        if ($value->lastSubmissionDate) {
          echo date("M d, Y H:i T", strtotime($value->lastSubmissionDate));
        }
        else {
          echo "--";
        }
        echo '</div>';
        echo '<div class="clear"></div>';
        echo '</div>';
        echo '</div>';
      }  ?>
    </div>

  </article>


</div>
<div id="winner" class="tableWrap hide tab">

  <?php include( locate_template('page-challenge-result.php') ); ?>

</div>
<div id="checkpoints" class="tableWrap <?php echo ( $activeTab == 'checkpoints' ) ? '' : 'hide'; ?> tab">


  <article>
    <?php include( locate_template('content-checkpoint.php') ); ?>
  </article>

</div>
<div id="submissions" class="tableWrap hide tab">


  <article>
    Coming Soon...
  </article>

</div>
</section>
</div>


</div>
<!-- /.mainStream -->
<aside class="sideStream  grid-1-3">

<div class="topRightTitle">

  <?php if ($contestType != 'design'): ?>
    <a href="http://apps.topcoder.com/forums/?module=Category&categoryID=<?php echo $contest->forumId; ?>"
       class="contestForumIcon" target="_blank">Challenge Discussion</a>
  <?php else: ?>
    <a href="http://studio.topcoder.com/forums?module=ThreadList&forumID=<?php echo $contest->forumId; ?>"
       class="contestForumIcon" target="_blank">Challenge Discussion</a>
  <?php endif; ?>

</div>

<div class="columnSideBar">

<div class="slider">
<ul>
<?php if ($contestType != 'design'): ?>
  <div class="slideBox">
    <?php include locate_template('content-challenge-downloads.php'); ?>
  </div>
  <li class="slide">

    <div class="reviewStyle slideBox">
      <h3>Review Style:</h3>

      <div class="inner">
        <p><strong>Final Review: </strong><span>Community Review Board</span>
          <a onmouseout="hideTooltip('FinalReview');" onmouseover="showTooltip(this, 'FinalReview');" class="tooltip"
             href="javascript:;"> </a></p>

        <p><strong>Approval: </strong><span>User Sign-Off</span>
          <a onmouseout="hideTooltip('Approval');" onmouseover="showTooltip(this, 'Approval');" class="tooltip"
             href="javascript:;"> </a>
        </p>
      </div>

    </div>
    <!-- End review style section -->

  </li>
  <?php if (isset( $contest->screeningScorecardId ) && isset( $contest->reviewScorecardId )) : ?>
  <li class="slide">

    <div class="contestLinks slideBox">
      <h3>Contest Links:</h3>

      <div class="inner">
        <p><a
            href="https://software.topcoder.com/review/actions/ViewScorecard.do?method=viewScorecard&scid=<?php echo $contest->screeningScorecardId; ?>">Screening
            Scorecard</a></p>

        <p><a
            href="http://software.topcoder.com/review/actions/ViewScorecard.do?method=viewScorecard&scid=<?php echo $contest->reviewScorecardId; ?>">Review
            Scorecard</a></p>
      </div>

    </div>

  </li>
<?php endif; ?>

  <li class="slide">
    <div class="forumFeed slideBox">&nbsp;<br/>
    </div>
  </li>
<?php else: ?>
  <li class="slide">
    <?php include locate_template('content-challenge-downloads.php'); ?>
  </li>
  <li class="slide">
    <div class="slideBox">
      <h3>How to Format Your Submission:</h3>

      <div class="inner">
        <ul>
          <b>Your Design Files:</b><br>
          <li>1. Look for instructions in this challenge regarding what files to provide.
          </li>
          <li>2. Place your submission files into a "Submission.zip" file.</li>
          <li>3. Place all of your source files into a "Source.zip" file.</li>
          <li>4. Create a JPG preview file.</li>
        </ul>

        <p>Trouble formatting your submission or want to learn more?
          <a href="http://topcoder.com/home/studio/the-process/how-to-submit-to-a-contest/">Read this FAQs</a>.</p>

        <p><strong>Fonts:</strong><br> All fonts within your design must be declared when you submit. DO NOT <a
            style="white-space:nowrap;">include any font files in your submission</a><a style="white-space:nowrap;">
            <br>or source files. </a><a href="http://topcoder.com/home/studio/the-process/font-policy/"
                                        style="white-space:nowrap;">Read the font policy here</a>.
        </p>

        <p><strong>Screening:</strong><br>All submissions are screened for eligibility before the challenge holder picks
          winners. Don't let your hard work go to waste.<br> <a
            href="http://community.topcoder.com/studio/the-process/screening/">Learn more about how to pass screening
            here</a>.
        </p>

        <p>Questions? <a href="http://studio.topcoder.com/forums?module=ThreadList&amp;forumID=6">Ask in the Forums</a>.
        </p>

      </div>
    </div>
  </li>
  <li class="slide">
    <div class="slideBox">
      <!-- <h3>Forums Feed:</h3> -->
      <div class="inner"></div>
    </div>
  </li>
  <li class="slide">
    <div class="slideBox">
      <h3>Source Files:</h3>

      <div class="inner">
        <ul>
          <?php if (empty($contest->filetypes)) : ?>
            <li><strong>Text or Word Document containing all of your ideas and supporting information.</strong></li>
          <?php else:
            foreach ($contest->filetypes as $filetype) {
              echo '<li><strong>' . $filetype .'</strong></li>';
            }
          endif; ?>
        </ul>

        <p>You must include all source files with your submission. </p>
      </div>
    </div>
  </li>
  <li class="slide">
    <div class="slideBox">
      <h3>Submission Limit:</h3>

      <div class="inner">
        <p><strong><?php
        //Bugfix I-107615: Added check if SubmissionLimit is empty, if so, display "Unlimited" instead of empty value
        if (!empty($contest->submissionLimit)) {
            echo $contest->submissionLimit;
        } else {
            echo "Unlimited";
        }
        ?></strong></p>
      </div>
    </div>
  </li>
<?php endif; ?>
<li class="slide">
  <div class="slideBox">
    <h3>Share:</h3>

    <div class="inner">
      <!-- AddThis Button BEGIN -->
      <div class="addthis_toolbox addthis_default_style addthis_32x32_style">
        <a class="addthis_button_preferred_1"></a>
        <a class="addthis_button_preferred_2"></a>
        <a class="addthis_button_preferred_3"></a>
        <a class="addthis_button_preferred_4"></a>
        <a class="addthis_button_compact"></a>
        <a class="addthis_counter addthis_bubble_style"></a>
      </div>
      <script type="text/javascript">var addthis_config = {"data_track_addressbar": true};
        var addthis_share = { url: location.href, title: "<?php echo $contest->challengeName;?>" }</script>
      <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-52f22306211cecfc"></script>
      <!-- AddThis Button END -->
    </div>
  </div>
</li>
<li class="slide">
  <div class="slideBox">
    &nbsp;
    <br/>
  </div>
</li>
</ul>
</div>

</div>

</aside>
<!-- /.sideStream -->
<div class="clear"></div>
</div>
<!-- /.rightSplit -->
</article>
<!-- /#mainContent -->


<div onmouseout="hideTooltip('FinalReview')" onmouseover="enterTooltip('FinalReview')"
     class="tip reviewStyleTip tipFinalReview" style="display: none;">
  <div class="inner">
    <div class="tipHeader">
      <h2>Final Review</h2>
    </div>
    <div class="tipBody">
      <a href="javascript:;">Community Review Board</a> performs a thorough review based on scorecards.
    </div>
    <div class="corner tl"></div>
    <div class="corner tr"></div>
    <div class="corner bl"></div>
    <div class="corner br"></div>
  </div>
  <div class="shadow"></div>
</div>

<div onmouseout="hideTooltip('Approval')" onmouseover="enterTooltip('Approval')" class="tip reviewStyleTip tipApproval"
     style="display: none;">
  <div class="inner">
    <div class="tipHeader">
      <h2>Approval</h2>
    </div>
    <div class="tipBody">
      Customer has final opportunity to sign-off on the delivered assets.
    </div>
    <div class="corner tl"></div>
    <div class="corner tr"></div>
    <div class="corner bl"></div>
    <div class="corner br"></div>
  </div>
  <div class="shadow"></div>
</div>
<?php get_footer('challenge-detail-tooltipx'); ?>

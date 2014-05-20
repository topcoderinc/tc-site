<?php
/**
 * Template Name: Angular Challenge Details
 */

/**
 * @file
 * This template displays the details of a particular challenge.
 */

tc_setup_angular();

$activeTab = $tab;
add_action('wp_head', 'tc_challenge_details_js');
function tc_challenge_details_js() {
  global $contest, $contestType, $contestID, $registrants, $activeTab;

  $regEnd = strtotime("$contest->registrationEndDate") || 1;
  $submissionEnd = strtotime("$contest->submissionEndDate") || 1

  ?>
  <script type="text/javascript">
    var activeTab = "<?php echo $activeTab;?>";
    var registrationUntil = new Date(<?php echo $regEnd ?> * 1000);
    var submissionUntil = new Date(<?php echo $submissionEnd ?> * 1000);
    var challengeId = "<?php echo $contestID;?>";
    var challengeType = "<?php echo $contestType;?>";
    var autoRegister = "<?php echo get_query_var('autoRegister');?>";
    var handle = ""; // fix me
  </script>
<?php
}

/**
 * Template Name: Challenge details
 */

$isChallengeDetails = TRUE;

$contestID = get_query_var('contestID');
$contestType    = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_STRING);
$contestType    = empty( $contestType ) ? "develop" : $contestType;
$noCache        = get_query_var('nocache');

$registerDisable = FALSE;
$submitDisabled  = FALSE;


// @TODO need to fix loading of hanlde before these will work
//$registerDisable = challenge_register_disabled($contest);
//$submitDisabled = challenge_submit_disabled($contest);

/**
 * Should the registration button active
 *
 * Registration button should be disabled:
 *  - When the date is after the registration end date
 *  - If the user is already registered
 *
 * @param $contest
 *
 * @return bool
function challenge_register_disabled($contest) {
  global $handle;

  $registerDisable = TRUE;

  if ($contest->registrationEndDate) {
    $curDate = new DateTime();
    $regDate = new DateTime($contest->registrationEndDate);
    if ($regDate > $curDate) {
      $registerDisable = FALSE;
    }
  }

  if (is_user_register_for_challenge($handle, $contest)) {
    $registerDisable = TRUE;
  }

  return $registerDisable;
}

 */

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

 */

/*

$documents = isset($contest->Documents) ? $contest->Documents : array();

*/

// need for header file
$contest_type = $contestType;

include locate_template('header-challenge-landing.php');

?>

<div id="cdNgMain" ng-app="challengeDetails" ng-controller="CDCtrl" class="hide content challenge-detail view-challenge-result {{challengeType != 'design' ? 'develop' : ''}}">
<div id="main">

<?php include( locate_template('ng-content-basic-challenge-details.php') ); ?>


<article id="mainContent" class="splitLayout <?php if (!empty( $activeTab )) {echo 'currentTab-' . $activeTab;} ?>">
<div class="container">
<div class="rightSplit  grid-3-3">
<div class="mainStream partialList">

<section class="tabsWrap">
<nav class="tabNav">
  <div class="topRightTitle topRightTitleAlt" style="position: relative;">
    <a ng-if="challengeType != 'design'" ng-href="http://apps.topcoder.com/forums/?module=Category&categoryID={{challenge.forumId}}"
        class="contestForumIcon" target="_blank">Challenge Discussion</a>
    <a ng-if="challengeType == 'design'" ng-href="http://studio.topcoder.com/forums?module=ThreadList&forumID={{challenge.forumId}}"
       class="contestForumIcon" target="_blank">Challenge Discussion</a>
  </div>
  <ul>
    <li ng-if="!isDesign"><a href="#contest-overview" class="{{activeTab == 'active' ? 'active' : ''}} link">Details</a></li>
    <li ng-if="!isDesign"><a href="#viewRegistrant" class="link">Registrants</a></li>
    <!-- FIXME: took out checkpoint stuff here
    < ?php if (( !empty( $checkpointData ) && $checkpointData != "Error in processing request" ) || ( $tab === "checkpoints" )): ?>
      <li><a href="#checkpoints" class="link {{activeTab == 'checkpoints' ? 'active' : ''}}">Checkpoints</a></li>
    < ?php endif; ?>
    -->
    <li ng-if="!isDesign"><a href="#winner" class="link">Results</a></li>

    <li ng-if="isDesign"><a href="#contest-overview" class="{{activeTab != 'checkpoints' ? 'active' : ''}} link">Details</a></li>
    <li ng-if="isDesign"><a href="#viewRegistrant" class="link">Registrants</a></li>
    <!-- FIXME: more checkpoint stuff commented out
    < ?php if (( !empty( $checkpointData ) && $checkpointData != "Error in processing request" ) || ( $tab === "checkpoints" )): ?>
      <li><a href="#checkpoints" class="link < ?php if ($tab === "checkpoints") { echo "active"; } ?>">Checkpoints</a></li>
    < ?php endif; ?>
    -->
    <li ng-if="isDesign && inSubmission"><span class="inactive">Submissions</span></li>
    <li ng-if="isDesign && !inSubmission"><a href="#submissions" class="link">Submissions</a></li>
    <li ng-if="isDesign && (inSubmission || inScreening || inReview)"><span class="inactive">Results</span></li>
    <li ng-if="isDesign && !(inSubmission || inScreening || inReview)"><a href="#winner" class="link">Results</a></li>
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
    <li ng-if="inSubmission"><span class="inactive">Checkpoints</span></li>
    <!-- FIXME: took out checkpoint data stuff here. It didn't seem like it was being used.
      <li ng-if="!inSubmission"><a href="<?php echo CURRENT_FULL_URL; ?>&tab=checkpoints" class="link">Checkpoints</a></li>
    -->
    <li ng-if="inSubmission"><span class="inactive">Submissions</span></li>
    <li ng-if="!inSubmission"><a href="#submissions" class="link">Submissions</a></li>
    </li>
    <li>
      <li ng-if="inSubmission || inScreening || inReview"><span class="inactive">Results</span></li>
      <li ng-if="!(inSubmission || inScreening || inReview)"><a href="#winner" class="link">Results</a></li>
    </li>
  </ul>
</nav>

<div id="contest-overview" class="tableWrap {{activeTab == 'checkpoints' ? 'hide' : ''}} tab">
  <article ng-if="!isDesign" id="contestOverview">
    <h1>Challenge Overview</h1>

    <p ng-bind-html="trust(challenge.detailedRequirements)"></p>

    <article id="platforms">
      <h1>Platforms</h1>
      <ul>
        <li ng-if="hasPlatforms = challenge.platforms && challenge.platforms.length > 0" ng-repeat="platform in challenge.platforms" >
          <strong ng-bind="platform"></strong>
        </li>
        <li ng-if="!hasPlatforms">
          <strong>Not Specified</strong>
        </li>
      </ul>
    </article>

    <article id="technologies">
      <h1>Technologies</h1>
      <div class="technologyTags">
        <li ng-if="hasTechnology = challenge.technology && challenge.technology.length > 0" ng-repeat="tech in technology">
          <span ng-bind="tech"></span>
        </li>
        <li ng-if="!hasTechnology">
          <strong>Not Specified</strong>
        </li>

      <div class="clear"></div>
      </div>
    </article>

    <h3>Final Submission Guidelines</h3>
    <div ng-bind-html="trust(challenge.finalSubmissionGuidelines)"></div>



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
<article ng-if="isDesign" id="contestOverview">

  <article id="contestSummary">
    <h1>CONTEST SUMMARY</h1>

    <p class="paragraph"></p>

    <p></p>

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
        style="line-height: 1.6em;" ng-bind-html="trust(challenge.round1Introduction)"></span></p>

    <span class="subTitle">Round Two (2)</span>

    <p class="paragraph"></p>

    <p><span style="color: rgb(64, 64, 64); font-size: 13px;" ng-bind-html="trust(challenge.round2Introduction)"></span></p>

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

    <p ng-bind-html="trust(challenge.detailedRequirements)"></p>
  </article>

  <article id="stockPhotography">
    <h1>STOCK PHOTOGRAPHY</h1>

    <p ng-if="challenge.allowStockArt"> Stock photography is allowed in this challenge.<br>
      <a href="http://help.topcoder.com/design/design-copyright-and-font-policies/policy-for-stock-photos-in-design-submissions/">See this page for more details.</a></p>

    <p ng-if="!challenge.allowStockArt">Stock photography is not allowed in this challenge. All submitted elements must be designed solely by you.<br>
      <a href="http://help.topcoder.com/design/design-copyright-and-font-policies/policy-for-stock-photos-in-design-submissions/">See
         this page for more details.</a></p>

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
<div id="viewRegistrant" class="tableWrap hide tab" style="">


  <article>
    <h1>
        REGISTRANTS
    </h1>
    <table class="registrantsTable">
      <thead>
      <tr>
        <th class="handleColumn">
          <div>Handle</div>
        </th>
        <th ng-if="challengeType != 'design'" class="ratingColumn">
          <div>Rating</div>
        </th>
        <th ng-if="challengeType != 'design'" class="reliabilityColumn">
          <div>Reliability</div>
        </th>
        <th class="regDateColumn">
          <div>Registration Date</div>
        </th>
        <th class="subDateColumn">
          <div>Submission Date</div>
        </th>
      </tr>
      </thead>
      <tbody>
      <tr ng-repeat="registrant in challenge.registrants">
        <td class="handleColumn">
            <span>
                <a ng-href="{{siteURL + '/member-profile' + registrant.handle}}" ng-bind="registrant.handle"></a>
            </span>
        </td>
        <td ng-if="challengeType != 'design'" class="ratingColumn">
            <span style="{{registrant.colorStyle}}" ng-bind="registrant.rating || 0" ng-bind="registrant.rating || 0"></span>
        </td>
        <td ng-if="challengeType != 'design'" class="reliabilityColumn">
            <span ng-bind="registrant.reliability"></span>
        </td>
        <td class="regDateColumn" ng-bind="formatDate(registrant.registrationDate)"></td>
        <td class="subDateColumn" ng-bind="formatDate(registrant.lastSubmissionDate)"></td>
      </tr>
      </tbody>
    </table>


  </article>


</div>
<div id="winner" class="tableWrap hide tab">

  <?php include( locate_template('ng-page-challenge-result.php') ); ?>

</div>
<div id="checkpoints" class="tableWrap {{activeTab == 'checkpoints' ? '' : 'hide'}} tab">


  <article>
    <?php include( locate_template('ng-content-checkpoint.php') ); ?>
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
<aside class="sideStream  grid-1-3" style="float: left;">

<div class="topRightTitle" style="position: relative;">

    <a ng-if="challengeType != 'design'" ng-href="http://apps.topcoder.com/forums/?module=Category&categoryID={{challenge.forumId}}"
       class="contestForumIcon" target="_blank">Challenge Discussion</a>
    <a ng-if="challengeType == 'design'" ng-href="http://studio.topcoder.com/forums?module=ThreadList&forumID={{challenge.forumId}}"
       class="contestForumIcon" target="_blank">Challenge Discussion</a>

</div>

<div class="columnSideBar">

<div class="slider">
<ul>
  <div ng-hide="isDesign = challengeType == 'design'" class="slideBox">
    <?php include locate_template('ng-content-challenge-downloads.php'); ?>
  </div>
  <li ng-hide="isDesign" class="slide">

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
  <li ng-hide="isDesign && challenge.screeningScorecardId && challenge.reviewScorecardId" class="slide">

    <div class="contestLinks slideBox">
      <h3>Contest Links:</h3>

      <div class="inner">
        <p><a
            href="https://software.topcoder.com/review/actions/ViewScorecard.do?method=viewScorecard&scid={{challenge.screeningScorecardId}}">Screening
            Scorecard</a></p>

        <p><a
            href="http://software.topcoder.com/review/actions/ViewScorecard.do?method=viewScorecard&scid={{challenge.reviewScorecardId}}">Review
            Scorecard</a></p>
      </div>

    </div>

  </li>

  <li ng-if="isDesign" class="slide">
    <div class="forumFeed slideBox">&nbsp;<br/>
    </div>
  </li>
  <li ng-if="isDesign" class="slide">
    <?php include locate_template('ng-content-challenge-downloads.php'); ?>
  </li>
  <li ng-if="isDesign" class="slide">
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
  <li ng-if="isDesign" class="slide">
    <div class="slideBox">
      <!-- <h3>Forums Feed:</h3> -->
      <div class="inner"></div>
    </div>
  </li>
  <li ng-if="isDesign" class="slide">
    <div class="slideBox">
      <h3>Source Files:</h3>

      <div class="inner">
        <ul>
            <li ng-if="!hasFiletypes"><strong>Text or Word Document containing all of your ideas and supporting information.</strong></li>
            <li ng-if="hasFiletypes" ng-repeat="filetype in challenge.filetypes">
              <strong ng-bind="filetype"></strong>
            </li>
        </ul>

        <p>You must include all source files with your submission. </p>
      </div>
    </div>
  </li>
  <li ng-if="isDesign" class="slide">
    <div class="slideBox">
      <h3>Submission Limit:</h3>

      <div class="inner">
        <p><strong ng-bind="challenge.submissionLimit"></strong></p>
      </div>
    </div>
  </li>
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
        var addthis_share = { url: location.href, title: "{{challenge.challengeName}}" }</script>
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

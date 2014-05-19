<div class="container">
<header class="pageHeading aboutPage">
  <h1 ng-bind="challenge.challengeName"></h1>

  <h2>CHALLENGE TYPE: <span ng-bind="challenge.challengeType"></span></h2>
</header>

<div id="stepBox">
<div class="container">

<div class="leftColumn">
    <a ng-if="!isDesign" class="btn btnAction challengeRegisterBtn {{challenge.registerDisabled ? 'disabled' : ''}}" href="javascript:;"><span>1</span>
      <strong>Register For This Challenge</strong></a>
    <a ng-if="!isDesign" class="btn btnAction {{challenge.submitDisabled ? 'disabled' : ''}}" target="_blank"
       href="<?php bloginfo("siteurl"); ?>/challenge-details/{{challenge.challengeId}}/submit"><span>2</span>      <strong>Submit Your Entries</strong></a>
    <a ng-if="isDesign" class="btn btnAction challengeRegisterBtn {{challenge.registerDisabled ? 'disabled' : ''}}" href="javascript:;"><span>1</span> <strong>Register
        For This Challenge</strong></a>
    <a ng-if="isDesign" class="btn btnAction {{challenge.submitDisabled ? 'disabled' : ''}}" target="_blank"
       href="http://studio.topcoder.com/?module=ViewRegistration&ct={{challenge.challengeId}}"><span>2</span> <strong>Submit
        Your Entries</strong></a>
    <a ng-if="isDesign" class="btn btnAction" target="_blank"
       href="http://studio.topcoder.com/?module=ViewSubmission&ct={{challenge.challengeId}}"><span>3</span> <strong>View
        Your Submission</strong></a>
</div>
<div class="middleColumn {{isDesign ? 'studio' : ''}}">
<table class="prizeTable">
<tbody>

<tr>
    <td ng-if="!isDesign && challenge.challengeType != 'Code'" class="fifty">
      <h2>1st PLACE</h2>

      <h3>
        <small>$</small><span ng-bind="challenge.prize ? (challenge.prize[0] ? challenge.prize[0] : '') : ''"></span>
      </h3>
    </td>
    <td ng-if="!isDesign && challenge.challengeType != 'Code'" class="fifty">
      <h2>2nd PLACE</h2>
      <h3>
        <small>$</small>
        <span ng-bind="challenge.prize ? (challenge.prize[1] ? challenge.prize[1] : '0') : ''">
        </span>
      </h3>
    </td>
      <td ng-if="(designOrCode = isDesign || challenge.challengeType == 'Code') && (challenge.prize && challenge.prize[0])" class="twenty">
        <h2>1st PLACE</h2>

        <h3>
          <small>$</small><span ng-bind="challenge.prize[0]"></span></h3>
      </td>
      <td ng-if="designOrCode && !(challenge.prize && challenge.prize[0])" class="twenty noPrize">
        <h2>1st PLACE</h2>

        <h3>
          <small>$</small>0</h3>
      </td>
      <td ng-if="designOrCode && (challenge.prize && challenge.prize[1])" class="twenty">
        <h2>2nd PLACE</h2>

        <h3>
          <small>$</small><span ng-bind="challenge.prize[1]"></span></h3>
      </td>
      <td ng-if="designOrCode && !(challenge.prize && challenge.prize[1])" class="twenty noPrize">
        <h2>2nd PLACE</h2>

        <h3>
          <small>$</small>0</h3>
      </td>
      <td ng-if="designOrCode && (challenge.prize && challenge.prize[2])" class="twenty">
        <h2>3rd PLACE</h2>

        <h3>
          <small>$</small><span ng-bind="challenge.prize[2]"></span></h3>
      </td>
      <td ng-if="designOrCode && !(challenge.prize && challenge.prize[2])" class="twenty noPrize">
        <h2>3rd PLACE</h2>

        <h3>
          <small>$</small>0</h3>
      </td>
      <td ng-if="designOrCode && (challenge.prize && challenge.prize[3])" class="twenty">
        <h2>4th PLACE</h2>

        <h3>
          <small>$</small><span ng-bind="challenge.prize[3]"></span></h3>
      </td>
      <td ng-if="designOrCode && !(challenge.prize && challenge.prize[3])" class="twenty noPrize">
        <h2>4th PLACE</h2>

        <h3>
          <small>$</small><span ng-bind="challenge.prize[3]"></span></h3>
      </td>
      <td ng-if="designOrCode && (challenge.prize && challenge.prize[4])" class="twenty">
        <h2>5th PLACE</h2>

        <h3>
          <small>$</small><span ng-bind="challenge.prize[4]"></span></h3>
      </td>
      <td ng-if="designOrCode && (challenge.prize && challenge.prize[5])" class="twenty noPrize">
        <h2>5th PLACE</h2>

        <h3>
          <small>$</small>0</h3>
      </td>
      <td ng-if="designOrCode && (challenge.prize && challenge.prize.length > 5)" class="morePayments active closed" rowspan="{{2 + ((challenge.prize.length - 5) / 5)}}">
      </td>
      <td ng-if="designOrCode && (challenge.prize && challenge.prize.length <= 5)" class="morePayments inactive" rowspan="{{2 + ((challenge.prize.length - 5) / 5)}}">
      </td>
</tr>
<tr ng-if="challenge.prize  && challenge.prize.length > 5 && (currentPlace = 6)" class="additionalPrizes hide" ng-repeat="i in range(0, (challenge.prize.length - 5) / 5)">
  <td class="twenty" ng-if="challenge.prize.length > 5 + i * 5" ng-repeat="j in range(i * 5, max((i + 1) * 5), challenge.prize.length)">
    <h2 ng-bind-template="{{j + 1}}'th PLACE'"></h2>
    <h3><small>$</small><span ng-bind="challenge.prize[j]"></span></h3>
  </td>
</tr>
<tr>
    <td ng-if="!isDesign" colspan="{{challenge.challengeType == 'Code' ? '2' : ''">
      <p class="realibilityPara">
        Reliability Bonus
        <span ng-if="reliabilityBonus && reliabilityBonus.length > 0" ng-bind="challenge.reliabilityBonus">
        </span>
        <span ng-if="!(reliabilityBonus && reliabilityBonus.length > 0)">
          N/A
        </span>
      </p>
    </td>
    <td ng-if="!isDesign" colspan="challenge.challengeType = 'Code' ? '3' : ''">
      <p class="drPointsPara">DR Points <span ng-bind="challenge.digitalRunPoints ? challenge.digitalRunPoints : 'N/A'"></span></p>
    </td>
    </td>
    <td ng-if="isDesign" colspan="2">
        <p class="scPoints"><span ng-bind="challenge.digitalRunPoints ? challenge.digitalRunPoints : 'NO'"></span> STUDIO CUP POINTS</p>
    </td>
    <td ng-if="isDesign" colspan="3">
      <p class="scPoints"><span ng-bind="challenge.numberOfCheckpointsPrizes"></span> CHECKPOINT AWARDS WORTH $<span ng-bind="challenge.topCheckPointPrize"></span>
        EACH</p>
    </td>
</tr>
</tbody>
</table>
<!-- TODO: not sure why this code is repeated -- probably get rid of it (or make sure my fix of it is correct) -->
<div class="prizeSlider hide">
  <ul>
    <li class="slide">
      <table>
        <tbody>

<tr>
    <td ng-if="!isDesign && challenge.challengeType != 'Code'" class="fifty">
      <h2>1st PLACE</h2>

      <h3>
        <small>$</small><span ng-bind="challenge.prize ? (challenge.prize[0] ? challenge.prize[0] : '') : ''"></span>
      </h3>
    </td>
    <td ng-if="!isDesign && challenge.challengeType != 'Code'" class="fifty">
      <h2>2nd PLACE</h2>
      <h3>
        <small>$</small><span ng-bind="challenge.prize ? (challenge.prize[1] ? challenge.prize[1] : '0') : ''"></span>
      </h3>
    </td>
      <td ng-if="(designOrCode = isDesign || challenge.challengeType == 'Code') && (challenge.prize && challenge.prize[0])" class="twenty">
        <h2>1st PLACE</h2>

        <h3>
          <small>$</small><span ng-bind="challenge.prize[0]"></span></h3>
      </td>
      <td ng-if="designOrCode && !(challenge.prize && challenge.prize[0])" class="twenty noPrize">
        <h2>1st PLACE</h2>

        <h3>
          <small>$</small>0</h3>
      </td>
      <td ng-if="designOrCode && (challenge.prize && challenge.prize[1])" class="twenty">
        <h2>2nd PLACE</h2>

        <h3>
          <small>$</small><span ng-bind="challenge.prize[1]"></span></h3>
      </td>
      <td ng-if="designOrCode && !(challenge.prize && challenge.prize[1])" class="twenty noPrize">
        <h2>2nd PLACE</h2>

        <h3>
          <small>$</small>0</h3>
      </td>
      <td ng-if="designOrCode && (challenge.prize && challenge.prize[2])" class="twenty">
        <h2>3rd PLACE</h2>

        <h3>
          <small>$</small><span ng-bind="challenge.prize[2]"></span></h3>
      </td>
      <td ng-if="designOrCode && !(challenge.prize && challenge.prize[2])" class="twenty noPrize">
        <h2>3rd PLACE</h2>

        <h3>
          <small>$</small>0</h3>
      </td>
      <td ng-if="designOrCode && (challenge.prize && challenge.prize[3])" class="twenty">
        <h2>4th PLACE</h2>

        <h3>
          <small>$</small><span ng-bind="challenge.prize[3]"></span></h3>
      </td>
      <td ng-if="designOrCode && !(challenge.prize && challenge.prize[3])" class="twenty noPrize">
        <h2>4th PLACE</h2>

        <h3>
          <small>$</small><span ng-bind="challenge.prize[3]"></span></h3>
      </td>
      <td ng-if="designOrCode && (challenge.prize && challenge.prize[4])" class="twenty">
        <h2>5th PLACE</h2>

        <h3>
          <small>$</small><span ng-bind="challenge.prize[4]"></span></h3>
      </td>
      <td ng-if="designOrCode && (challenge.prize && challenge.prize[5])" class="twenty noPrize">
        <h2>5th PLACE</h2>

        <h3>
          <small>$</small>0</h3>
      </td>
      <td ng-if="designOrCode && (challenge.prize && challenge.prize.length > 5)" class="morePayments active closed" rowspan="{{2 + ((challenge.prize.length - 5) / 5)}}">
      </td>
      <td ng-if="designOrCode && (challenge.prize && challenge.prize.length <= 5)" class="morePayments inactive" rowspan="{{2 + ((challenge.prize.length - 5) / 5)}}">
      </td>
</tr>
<tr ng-if="challenge.prize  && challenge.prize.length > 5 && (currentPlace = 6)" class="additionalPrizes hide" ng-repeat="i in range(0, (challenge.prize.length - 5) / 5)">
  <td class="twenty" ng-if="challenge.prize.length > 5 + i * 5" ng-repeat="j in range(i * 5, max((i + 1) * 5), challenge.prize.length)">
    <h2 ng-bind-template="{{j + 1}}'th PLACE'"></h2>
    <h3><small>$</small><span ng-bind="challenge.prize[j]"></span></h3>
  </td>
</tr>
<tr>
    <td ng-if="!isDesign" colspan="{{challenge.challengeType == 'Code' ? '2' : ''">
      <p class="realibilityPara">
        Reliability Bonus
        <span ng-if="reliabilityBonus && reliabilityBonus.length > 0">
          {{challenge.reliabilityBonus}}
        </span>
        <span ng-if="!(reliabilityBonus && reliabilityBonus.length > 0)">
          N/A
        </span>
      </p>
    </td>
    <td ng-if="!isDesign" colspan="challenge.challengeType = 'Code' ? '3' : ''">
      <p class="drPointsPara">DR Points <span ng-bind="challenge.digitalRunPoints ? challenge.digitalRunPoints : 'N/A'"></span></p>
    </td>
    </td>
    <td ng-if="isDesign" colspan="2">
        <p class="scPoints"><span ng-bind="challenge.digitalRunPoints ? challenge.digitalRunPoints : 'NO'"></span> STUDIO CUP POINTS</p>
    </td>
    <td ng-if="isDesign" colspan="3">
      <p class="scPoints"><span ng-bind="challenge.numberOfCheckpointsPrizes"></span> CHECKPOINT AWARDS WORTH $<span ng-bind="challenge.topCheckPointPrize"></span>
        EACH</p>
    </td>
</tr>        </tbody>
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
          class="CEDate" ng-bind="challenge.currentStatus == 'Completed' ? 'Completed' : challenge.currentPhaseName"></span>
      </div>
      <span ng-if="challenge.currentStatus != 'Completed' && challenge.currentStatus != 'Deleted' && challenge.currentPhaseRemainingTime > 0" class="timeLeft">
        <span ng-bind="daysLeft(challenge.currentPhaseRemainingTime)"></span> <small>Days</small>
        <span ng-bind="hoursLeft(challenge.currentPhaseRemainingTime)"></span> <small>Hours</small>
        <span ng-bind="minsLeft(challenge.currentPhaseRemainingTime)"></span> <small>Mins</small>
      </span>
    </div>
    <!--End nextBoxContent-->
      <div ng-if="!isDesign" class="nextBoxContent allDeadlineNextBoxContent hide">
        <p><label>Posted On:</label>
          <span>
            {{formatDate(challenge.postingDate)}}
          </span>
        </p>


        <p><label>Register By:</label>
         <span>
           {{formatDate(challenge.registrationEndDate)}}
         </span>
        </p>

        <p class="last"><label>Submit By:</label>
          <span>
            {{formatDate(challenge.submissionEndDate)}}
          </span>
        </p>

      </div>
      <!--End nextBoxContent-->
      <div ng-if="isDesign" class="nextBoxContent allDeadlineNextBoxContent studio hide">
        <p><label>Start Date:</label>
          <span>
            {{formatDate(challenge.postingDate)}}
          </span>
        </p>
          <p ng-if="challenge.checkpointSubmissionEndDate != ''"><label>Checkpoint:</label>
          <span>
            {{formatDate(challenge.checkpointSubmissionEndDate)}}
          </span>
          </p>

        <p><label>End Date:</label>
          <span>
            {{formatDate(challenge.submissionEndDate)}}
          </span>
        </p>

        <p class="last"><label>Winners Announced:</label>
          <span>
            {{formatDate(challenge.appealsEndDate)}}
          </span>
        </p>
      </div>
      <!--End nextBoxContent-->
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
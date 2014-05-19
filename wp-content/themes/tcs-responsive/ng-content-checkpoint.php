<div ng-if="checkpointData">
  <h1>checkpoint WINNERS</h1>
  <p class="info">The following submissions have received a checkpoint prize.</p>
  <ul class="winnerList">
    <li ng-repeat="result in checkpointResults" class="{{$index == checkpointResults.length - 1 ? last : ''">
      <span class="{{$index == 0 ? 'firstPrizeIcon' : ($index == 1 ? 'secondPrizeIcon' : '')">#{{result.submissionId}}</span>
    </li>
  </ul>
  <div class="clear"></div>
  <h1>Checkpoint General Feedback</h1>
  <div class="generalFeedback">
    <p>
      {{checkpointData.generalFeedback}}
    </p>
  </div>
  <h1 class="noBorder">personal Feedback</h1>
  <ul class="expandCollaspeList">
    <li ng-repeat="result in checkpointResults">
      <div class="bar">
        <a href="javascript:;" class="collapseIcon"></a>
        Feedback #{{result.submissionId}}
      </div>
      <div class="feedBackContent hide">
        <p>
          {{result.feedback && result.feedback.length > 0 ? result.feedback : 'N/A'}}
        </p>
      </div>
    </li>
  </ul>
</div>
<h1 ng-if="!checkpointData">Data is not available.</h1>

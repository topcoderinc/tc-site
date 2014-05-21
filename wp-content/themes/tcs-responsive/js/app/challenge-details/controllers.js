// @TODO Split out the different parts of the page into different contorllers
cdapp.controller('CDCtrl', ['$scope', 'ChallengeService', '$sce', function($scope, ChallengeService, $sce) {
  $scope.trust = function(x) {
    return $sce.trustAsHtml(x);
  };

  $scope.range = function(from, to) {
    var ans = [];
    for (var i = from; i < to; i++) {
      ans.push[i];
    }
    return ans;
  };

  // @TODO Move to filter
  $scope.daysLeft = function(seconds) {
    return Math.floor(seconds / (3600 * 24));
  };

  // @TODO Move to filter
  $scope.hoursLeft = function(seconds) {
    return Math.floor(Math.floor(seconds % (3600 * 24)) / 3600);
  };

  // @TODO Move to filter
  $scope.minsLeft = function(seconds) {
    return Math.floor(Math.floor(seconds % 3600) / 60);
  };

  // @TODO Move to filter
  $scope.max = function(x, y) { return x > y ? x : y; };

  // @TODO Move to filter
  $scope.formatDate = function(date) {
    function pad0(x) {
      return (x+'').length == 1 ? '0' + x : x;
    }
    if (!date) return '--';
    if (typeof date == 'string') date = new Date(date);
    var month = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'][date.getMonth()];
    var day = date.getDate();
    var year = date.getFullYear();
    var time = pad0((date.getUTCHours() + 20) % 24) + ':' + pad0(date.getUTCMinutes());
    return month + ' ' + day + ', ' + year + ' ' + time + ' EDT';
  };

  challengeId = location.href.match(/s\/(\d+)/)[1];
  $scope.round = Math.round;
  $scope.currentTab = 'details';

  ChallengeService.getChallenge(challengeId).then(function(challenge) {
    $('#cdNgMain').removeClass('hide');
    if (challenge.checkpointSubmissionEndDate && challenge.checkpointSubmissionEndDate != '') {
      ChallengeService.getCheckpointData(challengeId).then(function(data) {
          if (!data || data.error) {
            $scope.checkpointData = false;
            $scope.checkpointResults = false;
            $scope.numCheckpointSubmissions = -1;
          } else {
            $scope.checkpointData = data;
            $scope.checkpointResults = data.checkpointResults;
            $scope.numCheckpointSubmissions = data.numberOfSubmissions;
          }
        });
    }

    chglo = challenge;
    $scope.challenge = challenge;
    $scope.activeTab = 'overview';
    $scope.siteURL = siteURL;
    $scope.challengeType = getParameterByName('type');
    $scope.isDesign = $scope.challengeType == 'design';
    $scope.inSubmission = inSubmission = challenge.currentPhaseName.indexOf('Submission') >= 0;
    $scope.inScreening = inScreening = challenge.currentPhaseName.indexOf('Screening') >= 0;
    $scope.inReview = inReview = challenge.currentPhaseName.indexOf('Review') >= 0;
    $scope.hasFiletypes = (challenge.filetypes != undefined) && challenge.filetypes.length > 0;
    globby = $scope;

    if (challenge.currentPhaseEndDate == '') {
      ChallengeService.getResults(challengeId).then(function(results) {
        $scope.results = results;
        $scope.firstPlaceSubmission = results.firstPlaceSubmission;
        $scope.secondPlaceSubmission = results.secondPlaceSubmission;
        $scope.submissions = results.submissions;
      });
    } else {
      $scope.submissions = false;
    }
  });
}]);



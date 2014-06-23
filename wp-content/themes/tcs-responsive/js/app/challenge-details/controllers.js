var challengeName;
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
  $scope.formatDate = function(date, opt) {
    function pad0(x) {
      return (x+'').length == 1 ? '0' + x : x;
    }
    if (!date) return '--';
    if (typeof date == 'string') date = moment(date).toDate();
    var month = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'][date.getMonth()];
    if (opt == 2) month = month.substring(0, 3);
    var day = date.getDate();
    var year = date.getFullYear();
    var time = pad0((date.getUTCHours() + 20) % 24) + ':' + pad0(date.getUTCMinutes());
    return month + ' ' + day + ', ' + year + ' ' + time + ' EDT';
  };

  challengeId = location.href.match(/s\/(\d+)/)[1];
  $scope.round = Math.round;
  $scope.activeTab = 'details';
  if (window.location.hash == '#viewRegistrant') $scope.activeTab = 'registrants';
  else if (window.location.hash == '#winner') $scope.activeTab = 'winners';
  else if (window.location.hash == '#submissions') $scope.activeTab = 'submissions';

  $scope.numCheckpointSubmissions = -1;
  $scope.checkpointData = false;
  $scope.checkpointResults = false;
  ChallengeService.getChallenge(challengeId).then(function(challenge) {
    challengeName = challenge.challengeName;
    $('#cdNgMain').removeClass('hide');
    if (challenge.checkpointSubmissionEndDate && challenge.checkpointSubmissionEndDate != '') {
      ChallengeService.getCheckpointData(challengeId).then(function(data) {
          if (data && !data.error) {
            $scope.checkpointData = data;
            $scope.checkpointResults = data.checkpointResults;
            $scope.numCheckpointSubmissions = data.numberOfSubmissions;

            // @TODO: This is so hacky. Do it the Angular way instead
            setTimeout(function() {
              $('.expandCollaspeList li a').each(function () {
                var _this = $(this).parents('li')
                if (!$(this).hasClass('collapseIcon')) {
                  _this.children('.bar').css('border-bottom', '1px solid #e7e7e7');
                } else {
                  _this.children('.bar').css('border-bottom', 'none');
                }
              });
              $('.expandCollaspeList li .bar').on(ev, function () {
                var _this = $(this).closest('li');
                if (!$('a', _this).hasClass('collapseIcon')) {
                  $('a', _this).addClass('collapseIcon');
                  _this.children('.feedBackContent').hide();
                  _this.children('.bar').css('border-bottom', 'none');
                } else {
                  $('a', _this).removeClass('collapseIcon')
                  _this.children('.feedBackContent').show();
                  _this.children('.bar').css('border-bottom', '1px solid #e7e7e7');
                }
              });

              // checkpoint box click
              $('.winnerList .box').on('click', function () {
                var idx = $(this).closest('li').index();
                $('a', $('.expandCollaspeList li').eq(idx)).trigger('click');
                var top = $('a', $('.expandCollaspeList li').eq(idx)).offset().top - 20;
                var body = $("html, body");
                body.animate({scrollTop: top}, '500', 'swing');
              });



            }, 500);
           
          }
        });
    }

    // @TODO: put this in a service
    var reglist = challenge.registrants.map(function(x) { return x.handle; });
    app.getHandle(function(handle) {
      if (((moment(challenge.registrationEndDate)) >moment()) && reglist.indexOf(handle) == -1) {
        challenge.registrationDisabled = false;
      } else {
        challenge.registrationDisabled = true;
      }
      if (((moment(challenge.submissionEndDate)) > moment()) && reglist.indexOf(handle) > -1) {
        challenge.submissionDisabled = false;
      } else {
        challenge.submissionDisabled = true;
      }
    });

    chglo = challenge;
    //Bugfix refactored-challenge-details-40: format currency values with comma delimiters
    if (typeof challenge.reliabilityBonus === 'number') {
      challenge.reliabilityBonus = challenge.reliabilityBonus.format();
    }
    //loop over prizes and format number values
    for (var i = 0; i < challenge.prize.length; i++) {
      challenge.prize[i] = challenge.prize[i].format();
    }
    
    $scope.challenge = challenge;
    $scope.reliabilityBonus = challenge.reliabilityBonus;
    $scope.siteURL = siteURL;
    $scope.challengeType = getParameterByName('type');
    $scope.isDesign = $scope.challengeType == 'design';
    $scope.inSubmission = inSubmission = challenge.currentPhaseName.indexOf('Submission') >= 0;
    $scope.inScreening = inScreening = challenge.currentPhaseName.indexOf('Screening') >= 0;
    $scope.inReview = inReview = challenge.currentPhaseName.indexOf('Review') >= 0;
    $scope.hasFiletypes = (challenge.filetypes != undefined) && challenge.filetypes.length > 0;
    globby = $scope;

    if (challenge.currentStatus == 'Completed' || challenge.currentPhaseEndDate == '') {
      ChallengeService.getResults(challengeId).then(function(results) {
        $scope.results = results;
        $scope.firstPlaceSubmission = results.firstPlaceSubmission;
        $scope.secondPlaceSubmission = results.secondPlaceSubmission;
        $scope.submissions = results.submissions;
        $scope.initialScoreSum = 0;
        $scope.finalScoreSum = 0;
        $scope.submissions.map(function(x) {
          $scope.initialScoreSum += x.initialScore;
          $scope.finalScoreSum += x.finalScore;
        });
        //console.log('init and fina');
        //console.log($scope.initialScoreSum);
        //console.log($scope.finalScoreSum);
        $scope.winningSubmissions = [];
        for (var i = 0; i < $scope.submissions.length; i++) {
          if (challenge.prize[i]) $scope.winningSubmissions.push($scope.submissions[i]);
          else break;
        }
      });
    } else {
      $scope.submissions = false;
    }
  });
}]);



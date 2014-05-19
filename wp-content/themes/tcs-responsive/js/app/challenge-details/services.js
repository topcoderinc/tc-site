cdapp.factory('ChallengeService', ['Restangular', 'API_URL', '$q', '$cookies', function(Restangular, API_URL, $q, $cookies) {
  var service = Restangular.withConfig(function(RestangularConfigurer) {
    RestangularConfigurer.setBaseUrl(API_URL);
  });
  service.getResults = function(id) {
    var defer = $q.defer();
    service.one(challengeType).one('challenges').one('result', id).getList().then(function(results) {
      results = results[0];
      var submissionMap = {};
      results.results.map(function(x) {
        submissionMap[x.placement] = x;
      });
      results.firstPlaceSubmission = submissionMap[1];
      results.secondPlaceSubmission = submissionMap[2];
      results.submissions = [];
      var i = 1;
      while (submissionMap[i]) {
        results.submissions.push(submissionMap[i]);
        i++;
      }
      results.initialScoreSum = 0;
      results.finalScoreSum = 0;
      results.submissions.map(function(x) {
        results.initialScoreSum += x.initialScore;
        results.finalScoreSum += x.finalScore;
      });
      defer.resolve(results);
    });
    return defer.promise;
  }
  service.getCheckpointData = function(id) {
    var defer = $q.defer();
    service.one(challengeType).one('challenges').one('checkpoint', id).getList().then(function(data) {
      data = data[0];
      if (data.error) defer.resolve(false);
      defer.resolve(data);
    });
    return defer.promise;
  }
  service.getChallenge = function(id) {
    var defer = $q.defer();
    service.one(challengeType).one('challenges').getList(id).then(function(challenge) {
      challenge = challenge[0];
      var submissionMap = {};
      challenge.submissions.map(function(submission) {
        if (submissions[submission.handle]) {
          var neu = new Date(submission.submissionDate);
          var alt = new Date(submissionMap[submission.handle]);
          if (neu > alt) {
            submissionMap[submission.handle] = submission.submissionDate;
          }
        } else {
          submissionMap[submission.handle] = submission.submissionDate;
        }
      });
      challenge.registrants.map(function(x) {
        x.lastSubmissionDate = submissionMap[x.handle];
      });
      if (challenge.allowStockArt) {
        challenge.allowStockArt = challenge.allowStockArt == 'true';
      }
      if ((new Date(challenge.registrationEndDate)) > new Date()) {
        challenge.registrationDisable = false;
      } else {
        challenge.registrationDisable = true;
      }
      challenge.submitDisabled = true;
      if (challenge.submissionEndDate && challenge.currentStatus != 'Completed') {
        if ((new Date(challenge.submissionEndDate)) > new Date()) {
          challenge.submitDisabled = false;
        }
        var handleMap = {};
        challenge.registrants.map(function(x) {
          handleMap[x.handle] = true;
        });
      }
      defer.resolve(challenge);
    });
    return defer.promise;
  }
  return service;
}]);

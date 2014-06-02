/*global angular: true */
(function (angular) {
  'use strict';
  var challengesModule = angular.module('tc.challenges');
  challengesModule.controller('ChallengeListingCtrl', ['$scope', 'ChallengesService', 'DataService', '$http', '$window', 'TemplateService', 'GridService', '$routeParams',
    function ($scope, ChallengesService, DataService, $http, $window, TemplateService, GridService, $routeParam) {
      //console.log('routes', $routeParam);
      $scope.allChallenges = [];
      $scope.challenges = [];
      $scope.filteredChallenges = [];
      $scope.contest = {
        contestType: '',
        listType: 'active'
      };
      $scope.titles = {
        '': 'All Open Challenges',
        design: 'Graphic Design Challenges',
        develop: 'Software Development Challenges',
        data: 'Data Science Challenges'
      };
      $scope.view = 'table';
      //$scope.wordpressConfig = $window.wordpressConfig;
      $scope.getTrackSymbol = TemplateService.getTrackSymbol;
      $scope.formatTimeLeft = TemplateService.formatTimeLeft;
      $scope.getContestDuration = TemplateService.getContestDuration;
      $scope.getPhaseName = TemplateService.getPhaseName;
      $scope.dateFormat = 'dd MMM yyyy hh:mm EDT';
      $scope.images = $window.wordpressConfig.stylesheetDirectoryUri + '/i/';
      $scope.definitions = GridService.definitions($scope.contest);
      $scope.gridOptions = GridService.gridOptions('definitions');
      $scope.search = {
        radioFilterChallenge: 'all',
        show: false,
        allPlatforms: [],
        allTechnologies: []
      };
      $scope.pageSize = 10;
      $scope.page = 1;

tc.controller('ChallengeListingCtrl', ['$scope', 'Challenge',
  function($scope, Challenge) {

  $scope.challenges = [];

  // @TODO this should be dynamic per type
  $scope.gridOptions = {
    data: 'challenges',
    columnDefs: [ //@TODO replace with row template
      {
        field: 'challengeName',
        displayName: 'Challenges'
      },
      {
        field: 'challengeType',
        displayName: 'Type'
        // @TODO add template
      },
      { // @TODO replace with "Timeline"
        field: 'postingDate',
        displayName: 'Timeline'
      },
      { // @TODO format of "timeleft"
        field: 'currentPhaseRemainingTime',
        displayName: 'Time Left'
      },
      {
        field: 'currentPhaseName',
        displayName: 'Current Phase'
      },
      {
        field: 'numRegistrants',
        displayName: 'Registrants'
      },
      {
        field: 'numSubmissions',
        displayName: 'Submissions'
      }
    ]
  };

      $scope.submit = function () {
        $scope.filteredChallenges = $scope.allChallenges.filter(function (contest) {
          if ($scope.search.radioFilterChallenge !== 'all' && $scope.search.radioFilterChallenge !== contest.challengeType) {
            return false;
          }
          if ($scope.search.fSDate && contest.submissionEndDate < $scope.search.fSDate) {
            return false;
          }
          if ($scope.search.fEDate && contest.submissionEndDate > $scope.search.fEDate) {
            return false;
          }
          if($scope.search.technologies && $scope.search.technologies.length > 0) {
            for(var tech in $scope.search.technologies) {
              if(contest.technologies && contest.technologies.length > 0 && contest.technologies[0] != '' && contest.technologies.indexOf(tech) == -1) {
                return false;
              }
            }
          } 
          return true;
        });

        $scope.challenges = $scope.setPagingData($scope.filteredChallenges, $scope.page, $scope.pageSize);
      };

      DataService.one('technologies').get().then(function(data) {
        if(data) {
          $scope.search.allTechnologies = data.technologies;
        }
      });
      
      DataService.one('platforms').get().then(function(data) {
        if(data) {
          $scope.search.allPlatforms = data.platforms;
        }
      });



      $scope.$watch('page', function () {
        if($scope.page < 0) {
          $scope.page = 0;
        }
        $scope.challenges = $scope.setPagingData($scope.filteredChallenges, $scope.page, $scope.pageSize);
      });

      $scope.$watchCollection('contest', function (contest) {
        $scope.definitions = GridService.definitions(contest);
        if (contest.listType === 'past') {
          $scope.view = 'table';
        }
        getChallenges(contest);
      });
      getChallenges($scope.contest);

    }]);
}(angular));

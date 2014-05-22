/*global angular: true */
(function (angular) {
  'use strict';
  var challengesModule = angular.module('tc.challenges');
  challengesModule.controller('ChallengeListingCtrl', ['$scope', 'ChallengesService', '$http', '$window', 'TemplateService', 'GridService', '$routeParams',
      function ($scope, ChallengesService, $http, $window, TemplateService, GridService, $routeParam) {
      //console.log('routes', $routeParam);
      $scope.allChallenges = [];
      $scope.challenges = [];
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
        show: false
      };
      
      $scope.setPagingData = function (data, page, pageSize) {
        var pagedData = data.slice((page - 1) * pageSize, page * pageSize);
        $scope.totalServerItems = data.length;
        if (!$scope.$$phase) {
          $scope.$apply();
        }
        return pagedData;
      };
      
      //$http.get($window.wordpressConfig.stylesheetDirectoryUri + '/js/app/challenges/data/design.json').success(function (data) {
      //  $scope.challenges = data.data;
      //});
      // @TODO make type dynamic by using $routeparams
      function getChallenges(contest) {
        var params = {};
        if (contest.contestType && contest.contestType !== '') {
          params = {
            type: contest.contestType
          };
        }
        ChallengesService.all(contest.listType).getList(params).then(function (challenges) {
          $scope.allChallenges = challenges;
          $scope.challenges = $scope.setPagingData($scope.allChallenges, $scope.page, $scope.pageSize);
        },
          function () {
            $scope.challenges = [];
          });
      }

      $scope.submit = function () {
        console.log('submit');
        $scope.challenges = $scope.allChallenges = $scope.allChallenges.filter(function (contest) {
          if ($scope.search.radioFilterChallenge !== 'all' && $scope.search.radioFilterChallenge !== contest.challengeType) {
            return false;
          }
          if ($scope.search.fSDate && contest.submissionEndDate < $scope.search.fSDate) {
            return false;
          }
          if ($scope.search.fEDate && contest.submissionEndDate > $scope.search.fEDate) {
            return false;
          }
          return true;
        });
      };

      $scope.pageSize = 10;
      $scope.page = 1;

      

      $scope.$watch('page', function () {
        if($scope.page < 0) {
          $scope.page = 0;
        }
        $scope.challenges = $scope.setPagingData($scope.allChallenges, $scope.page, $scope.pageSize);
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
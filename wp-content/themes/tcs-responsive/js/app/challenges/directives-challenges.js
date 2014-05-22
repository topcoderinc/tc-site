/*global angular: true*/
(function () {
  'use strict';
  var directivesModule = angular.module('tc.challenges.directives', []);
  directivesModule.directive('tcChallengesTabs', ['$location', 'TemplateService',
      function ($location, TemplateService) {
      return {
        restrict: 'A',
        scope: {
          contest: '='
        },
        templateUrl: TemplateService.challengesBase + '/partials/ng-nav-challenges-list-tabs.html',
        controller: function ($scope) {
          var ctrl = this;
          $scope.setContestType = function (type) {
            $scope.contest.contestType = type;
          };

          $scope.isActive = function (type) {
            return $scope.contest.contestType === type;
          };

        }
      };
    }]);
  directivesModule.directive('tcChallengesActions', ['$location', 'TemplateService',
      function ($location, TemplateService) {
      return {
        restrict: 'A',
        scope: {
          contest: '=',
          search: '='
        },
        templateUrl: TemplateService.challengesBase + '/partials/actions.html',
        controller: function ($scope) {
          var ctrl = this;

          $scope.setListType = function (type) {
            $scope.contest.listType = type;
          };
          $scope.isActive = function (type) {
            return $scope.contest.listType === type;
          };

        }
      };
    }]);
  directivesModule.directive('tcContestGrid', ['TemplateService',
                                               function (TemplateService) {
      return {
        restrict: 'A',
        replace: true,
        /*scope: {
          challenge: '=',
          contest: '='
        },*/
        link: function (scope, element, attrs) {
          scope.getContentUrl = function () {
            if (!scope.contest.contestType || scope.contest.contestType === '') {
              return TemplateService.challengesBase + '/partials/gridView/all.html';
            } else {
              return TemplateService.challengesBase + '/partials/gridView/' + scope.contest.contestType + '-' + scope.contest.listType + '.html';
            }
          };

        },
        template: '<div ng-include="getContentUrl()"></div>',
        //templateUrl: TemplateService.challengesBase + '/partials/gridView/all.html',
        controller: function ($scope) {
          $scope.formatTimeLeft = TemplateService.formatTimeLeft;
          $scope.images = TemplateService.image('');
          $scope.getContestDuration = TemplateService.getContestDuration;
        }
      };
    }]);
  directivesModule.directive('tcContestGrid', ['TemplateService',
                                               function (TemplateService) {
      return {
        restrict: 'A',
        replace: true,
        scope: {
          search: '=',
          submit: '&'
        },
        link: function (scope, element, attrs) {
          scope.getContentUrl = function () {
            if (!scope.contest.contestType || scope.contest.contestType === '') {
              return TemplateService.challengesBase + '/partials/gridView/all.html';
            } else {
              return TemplateService.challengesBase + '/partials/gridView/' + scope.contest.contestType + '-' + scope.contest.listType + '.html';
            }
          };

        },
        template: '<div ng-include="getContentUrl()"></div>',
        //templateUrl: TemplateService.challengesBase + '/partials/gridView/all.html',
        controller: function ($scope) {
          $scope.formatTimeLeft = TemplateService.formatTimeLeft;
          $scope.images = TemplateService.image('');
          $scope.getContestDuration = TemplateService.getContestDuration;
        }
      };
    }]);
  directivesModule.directive('tcChallengesSearch', ['TemplateService',
                                               function (TemplateService) {
      return {
        restrict: 'A',
        replace: false,
        scope: {
          search: '=',
          submit: '&onSubmit',
          contest: '='
        },
        link: function (scope, element, attrs) {
          scope.getContentUrl = function () {
            if (scope.contest.contestType && scope.contest.contestType === 'design') {
              return TemplateService.challengesBase + '/partials/search-design.html';
            } else {
              return TemplateService.challengesBase + '/partials/search-develop.html';
            }
          };
          scope.images = TemplateService.image('');
          scope.submit = scope.$parent.submit;

        },
        template: '<div ng-include="getContentUrl()"></div>'
        //templateUrl: TemplateService.challengesBase + '/partials/gridView/all.html',
        /*controller: function ($scope) {
          $scope.images = TemplateService.image('');
          $scope.submit = function() {
            console.log('submit2');
          }
        }*/
      };
    }]);
}(angular));
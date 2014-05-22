/*global angular: true*/
(function (angular) {
    
  'use strict';
  var challengesModule = angular.module('tc.challenges', ['ngRoute']);
  challengesModule.config(['$httpProvider', '$routeProvider', '$locationProvider', function ($httpProvider, $routeProvider, $locationProvider) {
    $locationProvider.html5Mode(true);
    $httpProvider.defaults.useXDomain = true;
    delete $httpProvider.defaults.headers.common['X-Requested-With'];
    
    $routeProvider.
      when('/challenges/', {

      }).
      when('/challenges/:contestType/:listType', {

      });
  }
    ]);
}(angular));
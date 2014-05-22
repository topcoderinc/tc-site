/*global angular: true */
(function (angular) {
  'use strict';
  angular.module('tc.challenges.services', [
    'restangular'
  ])

  .factory('ChallengesService', ['Restangular', 'API_URL',
    function (Restangular, API_URL) {

      return Restangular.withConfig(function (RestangularConfigurer) {
        RestangularConfigurer.setBaseUrl(API_URL + '/challenges');
      });
  }]);
}(angular));
'use strict';

angular.module('tc.challengeService', [
  'restangular'
])

.factory('Challenge', ['Restangular', 'API_URL', function(Restangular, API_URL) {
  return Restangular.withConfig(function(RestangularConfigurer) {
    RestangularConfigurer.setBaseUrl(API_URL + '/challenges');
  });
}]);
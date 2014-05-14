'use strict';

// There's an error in while initializing content-member-algo.php. It creates a 'not defined' error on the coder object. Problem exists in other pages as well.
// Solving this is out of scope. So, just stopping that error instead
var coder = {
  initMemberBadges : function(){}
}

// App module:
var tc = angular.module('tc.memberProfile', [
  'ngGrid',
  'tc.memberProfileServices'
])

.constant("API_URL", "https://api.topcoder.com/v2")

.config(['$httpProvider', 'RestangularProvider', 'API_URL', function($httpProvider, RestangularProvider, API_URL) {
  /*
   * Enable CORS
   * http://stackoverflow.com/questions/17289195/angularjs-post-data-to-external-rest-api
   */
  $httpProvider.defaults.useXDomain = true;
  delete $httpProvider.defaults.headers.common['X-Requested-With'];

  // Base API url
  RestangularProvider.setBaseUrl(API_URL);

}]);

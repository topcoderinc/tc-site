'use strict';

window.tc = angular.module('tc', [
  'restangular',
  'tc.challengeService',
  'tc.memberProfileService',
  'ngGrid'
])

.constant("API_URL", "https://api.topcoder.com/v2")
.constant("PHOTO_LINK_LOCATION", "http://community.topcoder.com")
.constant("MEMBER_PROFILE_TEMPLATE_URL", "/js/app/member-profile/partials/memberProfile.tpl.html")

.config(['$httpProvider', 'RestangularProvider', 'API_URL', function($httpProvider, RestangularProvider, API_URL) {
  /*
   * Enable CORS
   * http://stackoverflow.com/questions/17289195/angularjs-post-data-to-external-rest-api
   */
  $httpProvider.defaults.useXDomain = true;
  delete $httpProvider.defaults.headers.common['X-Requested-With'];

  // Base API url
  RestangularProvider.setBaseUrl(API_URL);

  // Format restangular response

  // add a response intereceptor
  RestangularProvider.addResponseInterceptor(function(data, operation, what, url, response, deferred) {
    var extractedData;
    // .. to look for getList operations
    if (operation === "getList") {
      // .. and handle the data and meta data
      extractedData = data.data;
      extractedData.pagination = {
        total: data.total,
        pageIndex: data.pageIndex,
        pageSize: data.pageSize
      };
    } else {
      extractedData = data;
    }
    return extractedData;
  });
}]);

'use strict';

window.tc = angular.module('tc', [
  'restangular',
  'tc.challenges',
  'tc.challenges.services',
  'tc.challenges.directives',
  'angular-loading-bar',
  'ngGrid'
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
  
  RestangularProvider.addRequestInterceptor(function(element, operation, what, url) {
    //loadingBar.start();
  });

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
      extractedData = data.data;
    }
    //loadingBar.complete();
    return extractedData;
  });
}]);

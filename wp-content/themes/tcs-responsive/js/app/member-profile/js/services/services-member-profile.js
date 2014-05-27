'use strict';

angular.module('tc.memberProfileService', [
  'restangular'
])

.factory('MemberProfileService', ['Restangular', function(Restangular){
  return {
    'getUser' : function(handle){
      // Returns fetched restangular object of the fetched user
      return Restangular.all('users').one(handle).get();
    }
  }
}]);

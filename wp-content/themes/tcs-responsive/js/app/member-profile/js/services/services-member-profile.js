'use strict';

angular.module('tc.memberProfileServices', [
  'restangular'
])

// UserService could be better?
.factory('MemberService', ['Restangular', 'API_URL', function(Restangular, API_URL){
  return {
    'getUser' : function(handle){
      // Returns fetched restangular object of the fetched user
      return Restangular.all('users').one(handle).get();
    }
  }
}]);

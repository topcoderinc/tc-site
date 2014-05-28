'use strict';

tc.controller('MemberProfileCtrl', ['$scope', 'MemberProfileService', "PHOTO_LINK_LOCATION", "MEMBER_PROFILE_TEMPLATE_URL",
              function($scope, MemberProfileService, PHOTO_LINK_LOCATION, MEMBER_PROFILE_TEMPLATE_URL){
  // This template will be loaded by ng-include in the page.
  // Using this as the complete app is not angular based, routing will create dead links with HTML5 mode.
  // THEME_URL is defined in ng-page-member-profile.php
  $scope.templateUrl = THEME_URL + MEMBER_PROFILE_TEMPLATE_URL;

  // Get the user's profile. 'user' is defined in ng-page-member-profile.php
  MemberProfileService.getUser(user).then(function(user){
    $scope.coder = user;
  });

  // Returns a url for coder's profile. Returns default photo when coder has no pic.
  $scope.getPhotoLink = function(coder){
    if(coder && coder.photoLink !== ''){
      return PHOTO_LINK_LOCATION + coder.photoLink;
    }
    return THEME_URL + '/i/default-photo.png';
  }
}]);

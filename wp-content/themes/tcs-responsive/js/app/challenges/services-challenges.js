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
  }])
    .factory('TemplateService', ['$window', '$sce',
      function ($window, $sce) {
        var partialUrl = $window.wordpressConfig.stylesheetDirectoryUri + '/js/app/challenges/partials/';

        function partial(partial) {
          return partialUrl + partial;
        }

        function getTrackSymbol(type) {
          var trackName = "w";
          switch (type) {
          case "Web Design":
            trackName = "w";
            break;
          case "Widget or Mobile Screen Design":
            trackName = "wi";
            break;
          case "Wireframes":
            trackName = "wf";
            break;
          case "Idea Generation":
            trackName = "ig";
            break;
          case "Other":
            trackName = "o";
            break;
          case "UI Prototype Competition":
            trackName = "p";
            break;
          case "Content Creation":
            trackName = "cc";
            break;
          case "Assembly Competition":
            trackName = "ac";
            break;
          case "Print\/Presentation":
            trackName = "pr";
            break;
          case "Banners\/Icons":
            trackName = "bi";
            break;
          case "Code":
            trackName = "c";
            break;
          case "Architecture":
            trackName = "a";
            break;
          case "Bug Hunt":
            trackName = "bh";
            break;
          case "Specification":
            trackName = "spc";
            break;
          case "Test Suites":
            trackName = "ts";
            break;
          case "Copilot Posting":
            trackName = "cp";
            break;
          case "Conceptualization":
            trackName = "c";
            break;
          case "First2Finish":
            trackName = "ff";
            break;
          case "Design First2Finish":
            trackName = "df2f";
            break;
          case "Application Front-End Design":
            trackName = "af";
            break;
          default:
            trackName = "o";
            break;

          }
          return trackName;
        }

        function formatTimeLeft(seconds, grid) {
          var sep = (grid) ? '' : ' ';
          if (seconds < 0) {
            return $sce.trustAsHtml('<span style="font-size:14px;">0' + sep + '<span style="font-size:10px;">Days</span> 0' + sep + '<span style="font-size:10px;">Hrs</span>');
          }

          var numdays = Math.floor(seconds / 86400);
          var numhours = Math.floor((seconds % 86400) / 3600);
          var numminutes = Math.floor(((seconds % 86400) % 3600) / 60);
          var numseconds = ((seconds % 86400) % 3600) % 60;
          var result = "";
          var style = "";
          if (numdays == 0 && numhours <= 2) {
            style = "color:red";
          }
          if (isNaN(numhours)) {
            result =  "<em style='font-size:14px;'>not available</em>";
          }
          else {
            result = "<span style='font-size:14px;" + style + "'>" + (numdays > 0 ? numdays + sep + "<span style='font-size:10px;'>Day" + ((numdays > 1) ? "s" : "") + "</span> " : "") + "" + numhours + sep + "<span style='font-size:10px;'>Hrs</span> " + (numdays == 0 ? numminutes + sep + "<span style='font-size:10px;'>Min</span> " : "") + "</span>";
          }
          return $sce.trustAsHtml(result);
        }
        return {
          partial: partial,
          formatTimeLeft: formatTimeLeft,
          getTrackSymbol: getTrackSymbol
          
        }
  }]);
}(angular));
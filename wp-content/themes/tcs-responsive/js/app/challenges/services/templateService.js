/*global angular: true, moment: true */
(function (angular) {
  'use strict';
  var challengesService = angular.module('tc.challenges.services');

  challengesService.factory('TemplateService', ['$window', '$sce',
                               function ($window, $sce) {
      var partialUrl = $window.wordpressConfig.stylesheetDirectoryUri + '/js/app/challenges/partials/';

      function getPartial(partial) {
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

      function getContestDuration(dateStart, dateEnd) {
        var start = moment(dateStart.slice(0, -5)),
          end = moment(dateEnd.slice(0, -5)),
          days = end.diff(start, 'days');
        return days;
      }

      function formatTimeLeft(seconds, grid) {
        var sep = (grid) ? '' : ' ',
          numdays = Math.floor(seconds / 86400),
          numhours = Math.floor((seconds % 86400) / 3600),
          numminutes = Math.floor(((seconds % 86400) % 3600) / 60),
          numseconds = ((seconds % 86400) % 3600) % 60,
          result = "",
          style = "";
        if (seconds < 0) {
          return $sce.trustAsHtml('<span style="font-size:14px;">0' + sep + '<span style="font-size:10px;">Days</span> 0' + sep + '<span style="font-size:10px;">Hrs</span>');
        }

        if (numdays === 0 && numhours <= 2) {
          style = "color:red";
        }
        if (isNaN(numhours)) {
          result = "<em style='font-size:14px;'>not available</em>";
        } else {
          result = "<span style='font-size:14px;" + style + "'>" + (numdays > 0 ? numdays + sep + "<span style='font-size:10px;'>Day" + ((numdays > 1) ? "s" : "") + "</span> " : "") + numhours + sep + "<span style='font-size:10px;'>Hrs</span> " + (numdays === 0 ? numminutes + sep + "<span style='font-size:10px;'>Min</span> " : "") + "</span>";
        }
        return $sce.trustAsHtml(result);
      }

      function registrationOpen(registration) {
        if (typeof registration === 'string' && registration.toLowerCase().indexOf('no') !== -1) {
          return false;
        }
        return registrationOpen;
      }

      function getPhaseName(contest, registration) {
        if (contest.listType === 'past') {
          return 'Completed';
        }
        if (registrationOpen(registration)) {
          return 'Open to All';
        }
        return 'Open to Challenge Registrants';
      }
      function image(img) {
        return $window.wordpressConfig.stylesheetDirectoryUri + '/i/' + img;
      }
      return {
        partial: getPartial,
        formatTimeLeft: formatTimeLeft,
        getTrackSymbol: getTrackSymbol,
        getContestDuration: getContestDuration,
        getPhaseName: getPhaseName,
        image: image,
        templateBase: $window.wordpressConfig.stylesheetDirectoryUri,
        challengesBase: $window.wordpressConfig.stylesheetDirectoryUri + '/js/app/challenges'
      };
    }]);
}(angular));
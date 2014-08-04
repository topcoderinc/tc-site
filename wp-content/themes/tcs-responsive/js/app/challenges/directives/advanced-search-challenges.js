/*jslint nomen: true*/
/*global angular: true, _: true */
(function (angular) {
  'use strict';
  angular.module('tc.AdvancedSearch', ['ui.bootstrap']).directive('advancedSearch', ['$compile', '$timeout', 'ChallengesService', function ($compile, $timeout, ChallengesService) {
    return {
      replace: true,
      //transclude: false,
      templateUrl: 'advanced-search.html',
      //templateUrl: '/wp-content/themes/tcs-responsive/js/app/challenges/partials/advanced-search.html',
      scope: {
        applyFilterHandler: '=applyFilter',
        challengeCommunity: '=challengeCommunity',
        challengeStatus: '=challengeStatus',
        searchBarVisible: '=showOn',
        technologies: '=technologies',
        platforms: '=platforms',
        actualFilter: '=filter'
      },

      controller: ['$scope', function ($scope) {
        $scope.chbFrom = false;
        $scope.chbTo = false;
        var initOptions = {
          challengeTypes: [],
          startDate: undefined,
          endDate: undefined,
          technologies: [],
          platforms: [],
          keywords: []
        };
        
        $scope.tempOptions = {
          challengeType: undefined,
          technology: undefined,
          platform: undefined,
          text: undefined
        };
        
        $scope.contestTypes = [];
        
        this.datePicker = undefined;
        this.selects = [];
        this.closeDropdowns = function (element) {
          _.each(this.selects, function (s) {
            if (s !== element) {
              angular.element(s).select2('close');
            }
          });
          if (this.datePicker !== element) {
            angular.element(this.datePicker).hide();
          }
        };

        function getChallengeTypes() {
          ChallengesService.getChallengeTypes($scope.challengeCommunity).then(function (data) {
            var contestTypes = {};
            _.each(data, function (type) {
              contestTypes[type.description] = type.name;
            });
            $scope.contestTypes = contestTypes;
          });
        }

        $scope.resetFilterOptions = function () {
          $scope.filterOptions = angular.extend({}, initOptions);
        };
        
        $scope.formatDate = function (date) {
          if (!date) {
            return '';
          }
          return window.moment(date).format("DD MMM YYYY");
        };
        
        $scope.clearDates = function () {
          $scope.filterOptions.startDate = null;
          $scope.filterOptions.endDate = null;
          $scope.applyFilter();
        };
        $scope.addTechnology = function (tech) {
          if(!tech || tech === '') {
            return; 
          }
          $scope.filterOptions.technologies.push(tech);
          $timeout(function () {
            $scope.tempOptions.technology = undefined;
            $scope.applyFilter();
          }); // Timeout to let time to select2 to handle selection
          
        };
        $scope.addPlatform = function (plat) {
          if(!plat || plat === '') {
            return; 
          }
          $scope.filterOptions.platforms.push(plat);
          $timeout(function () {
            $scope.tempOptions.platform = undefined;
            $scope.applyFilter();
          });
          
        };
        $scope.addChallengeType = function (ch) {
          if(!ch || ch === '') {
            return; 
          }
          //Past api does not handle multiple challenge types
          if ($scope.challengeStatus === 'past') {
            $scope.filterOptions.challengeTypes = [ch];
          } else {
            $scope.filterOptions.challengeTypes.push(ch);
          }
          $timeout(function () {
            $scope.tempOptions.challengeType = undefined;
            $scope.applyFilter();
          });
        };
        $scope.addKeywords = function (text) {
          if (!text || text.match(/^\s*$/)) {
            return;
          }
          /*var split = text.split(/,|\s/);
          $scope.filterOptions.keywords = $scope.filterOptions.keywords.concat(split.filter(function(elem, pos, self) {
              return self.indexOf(elem) === pos && $scope.filterOptions.keywords.indexOf(elem) === -1;
          }));*/
          $scope.tempOptions.text = undefined;
          if ($scope.filterOptions.keywords.indexOf(text) === -1) {
            //Past api does not handle multiple keywords
            if ($scope.challengeStatus === 'past') {
              $scope.filterOptions.keywords = [text];
            } else {
              $scope.filterOptions.keywords.push(text);
            }
            $scope.applyFilter();
          }
        };
        $scope.removeTechnology = function (tech) {
          $scope.filterOptions.technologies.splice($scope.filterOptions.technologies.indexOf(tech), 1);
          $scope.applyFilter();
        };
        $scope.removePlatform = function (plat) {
          $scope.filterOptions.platforms.splice($scope.filterOptions.platforms.indexOf(plat), 1);
          $scope.applyFilter();
        };
        $scope.removeChallengeType = function (ch) {
          $scope.filterOptions.challengeTypes.splice($scope.filterOptions.challengeTypes.indexOf(ch), 1);
          $scope.applyFilter();
        };
        $scope.removeKeyword = function (keyword) {
          $scope.filterOptions.keywords.splice($scope.filterOptions.keywords.indexOf(keyword), 1);
          $scope.applyFilter();
        };
        
        $scope.reset = function () {
          $scope.filterOptions = angular.extend({}, initOptions);
          $scope.applyFilter();
        };
        
        $scope.hasFilters = function () {
          var f = $scope.filterOptions;
          return f.startDate || f.endDate || f.technologies.length > 0 || f.platforms.length > 0 || f.challengeTypes.length > 0 || f.keywords.length > 0;
        };

        $scope.resetFilterOptions();

        if ($scope.actualFilter) {
          $scope.filterOptions = angular.extend($scope.filterOptions, $scope.actualFilter);
        }
        
        getChallengeTypes();

      }],
      compile: function (tElement, tAttrs, transclude) {

        return function ($scope, $element, attr) {

          $scope.closeForm = function () {
            $element.hide(200);
            $scope.searchBarVisible = false;
          };

          $scope.applyFilter = function () {
            var filterOptions = _.clone($scope.filterOptions);
            $scope.applyFilterHandler(filterOptions);
          };
          
          $scope.$on('$locationChangeSuccess', function (event) {
            $timeout(function () {
              if ($scope.actualFilter) {
                $scope.filterOptions = angular.extend({}, $scope.actualFilter);
              }
            });
          });

        };
      }

    };
  }])
  
  .directive('tcDatePicker', function () {
    var moment = window.moment;
    return {
      restrict: 'A',
      require: '^advancedSearch',
      controller: function($scope) {
        var dateCtrl = this;
        dateCtrl.today = function() {
          $scope.filterOptions.startDate = new Date();
          $scope.filterOptions.endDate = $scope.filterOptions.startDate;
          $scope.applyFilterHandler($scope.filterOptions);
        };
        dateCtrl.yesterday = function() {
          $scope.filterOptions.startDate = moment().subtract('days', 1).toDate();
          $scope.filterOptions.endDate = $scope.filterOptions.startDate;
          $scope.applyFilterHandler($scope.filterOptions);
        };
        dateCtrl.last7Days = function() {
          $scope.filterOptions.endDate = new Date();
          $scope.filterOptions.startDate = moment().subtract('days', 7).toDate();
          $scope.applyFilterHandler($scope.filterOptions);
        };
        dateCtrl.thisMonth = function() {
          $scope.filterOptions.startDate = moment().startOf('month').toDate();
          $scope.filterOptions.endDate = new Date();
          $scope.applyFilterHandler($scope.filterOptions);
        };
        dateCtrl.lastMonth = function() {
          $scope.filterOptions.startDate = moment().subtract('months', 1).startOf('month').toDate();
          $scope.filterOptions.endDate = moment($scope.filterOptions.startDate).endOf('month').toDate();
          $scope.applyFilterHandler($scope.filterOptions);
        };
      },
      controllerAs: 'dateCtrl',
      link: function(scope, element, attrs, advancedSearchCtrl) {
         var from = element.find('.from-datepicker'),
             to = element.find('.to-datepicker');
          from.datepicker({
            onSelect: function (selectedDate) {
              to.datepicker("option", "minDate", selectedDate);
              scope.$apply(function () {
                scope.filterOptions.startDate = from.datepicker('getDate');
                scope.applyFilterHandler(scope.filterOptions);
              });
            },
            defaultDate: scope.filterOptions.startDate,
            maxDate: scope.filterOptions.endDate
          });
        
        to.datepicker({
            onSelect: function (selectedDate) {
              from.datepicker("option", "maxDate", selectedDate);
              scope.$apply(function () {
                scope.filterOptions.endDate = to.datepicker('getDate');
                scope.applyFilterHandler(scope.filterOptions);
              });
            },
            defaultDate: scope.filterOptions.endDate,
            minDate: scope.filterOptions.startDate
        });
        var pickers = element.find('.pickers');
        advancedSearchCtrl.datePicker = pickers[0];
        element.hover(function (event) {
          advancedSearchCtrl.closeDropdowns(pickers[0]);
          pickers.show();
        });
        element.mouseleave(function () {
          element.find('.pickers').hide();
        });
        scope.$on('$destroy', function() {
          element.off('hover');
          element.off('mouseleave')
        });
      }
    }
  })
  .directive('tcSelect2Hover', ['$timeout', function ($timeout) {
    var selects = [];
    return {
      restrict: 'A',
      scope: true,
      require: '^advancedSearch',
      link: function(scope, element, attrs, advancedSearchCtrl) {
        var select = element.find('select'),
            entered = false;
        advancedSearchCtrl.selects.push(select[0]);
        element.hover(function() {
          advancedSearchCtrl.closeDropdowns(select[0]);
          select.select2('open');
          angular.element('#select2-drop .select2-search input').blur();
          angular.element('#select2-drop').hover(function() {
            entered = true;
          });
          angular.element('#select2-drop').off('mouseleave').mouseleave(function () {
            advancedSearchCtrl.closeDropdowns(null);
            entered = false;
          });
        });
        element.mouseleave(function() {
          $timeout(function() {
            if(!entered) {
              entered = false;
              select.select2('close');
            }
          }, 100);
        });
       
        scope.$on('$destroy', function() {
          element.off('hover');
          angular.element('#select2-drop').off('mouseleave')
        });
      }
    };
  }]);
}(angular));

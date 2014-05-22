/*global angular: true */
(function (angular) {
  'use strict';
  var challengesService = angular.module('tc.challenges.services');

  challengesService.factory('GridService', ['TemplateService',
    function (TemplateService) {

      function colums(contest) {
        return [
          {
            field: 'challengeName',
            fieldName: 'Challenges',
            width: 340
          },
          {
            field: 'challengeType',
            fieldName: 'Type',
            width: 45
          },
          {
            field: 'registrationStartDate',
            fieldName: 'Timeline',
            minWidth: 194,
            width: 210,
            maxWidth: 210,
            tplName: 'timeline'
          },
          {
            field: 'registrationStartDate',
            fieldName: 'Duration (days)',
            width: 120,
            tplName: 'duration',
            visible: contest.listType === 'upcoming'
          },
          {
            field: 'currentPhaseRemainingTime',
            fieldName: 'Time Left',
            width: 80,
            visible: contest.listType === 'active'
          },
          {
            field: 'totalPrize',
            fieldName: 'Prizes',
            width: 73,
            tplName: 'prizes',
            visible: contest.listType !== 'upcoming'
          },
          {
            field: 'firstPlacePrize',
            fieldName: 'First Prize',
            width: 80,
            tplName: 'prizes',
            visible: contest.listType === 'upcoming'
          },
          {
            field: 'currentPhaseName',
            fieldName: 'Current Phase',
            minWidth: 67,
            width: 110,
            maxWidth: 120,
            visible: contest.listType !== 'upcoming'
          },
          {
            field: 'technologies',
            fieldName: 'Technologies',
            width: 130,
            visible: contest.listType === 'upcoming'
          },
          {
            field: 'numRegistrants',
            fieldName: 'Registrants',
            width: 74,
            minWidth: 74,
            maxWidth: 74,
            visible: contest.listType !== 'upcoming'
          },
          {
            field: 'numSubmissions',
            fieldName: 'Submissions',
            width: 74,
            minWidth: 74,
            maxWidth: 74,
            visible: contest.listType === 'active'
          },
          {
            field: 'numSubmissions',
            fieldName: 'Eligible Submissions',
            width: 74,
            minWidth: 74,
            visible: contest.listType === 'past'
          },

          {
            field: 'chalengeId',
            fieldName: 'Winners',
            width: '*',
            minWidth: 74,
            tplName: 'winners',
            visible: contest.listType === 'past'
          },
          {
            field: 'isPrivate',
            fieldName: 'Public/Private',
            minWidth: 80,
            width: 80,
            visible: contest.listType === 'past'
          },
          {
            field: 'status',
            fieldName: 'Status',
            minWidth: 74,
            visible: contest.listType === 'upcoming'
          }
        ];
      }

      function fieldTpl(fieldName) {
        return TemplateService.partial('tableView/' + fieldName + '.html');
      }

      function toDef(fieldDef) {
        var tplName = fieldDef.tplName || fieldDef.field,
          visible = typeof fieldDef.visible === 'undefined' ? true : fieldDef.visible,
          tplUrl = fieldTpl(tplName);
        return {
          field: fieldDef.field,
          displayName: fieldDef.fieldName,
          sortable: true,
          resizable: false,
          enableCellEdit: false,
          pinnable: false,
          visible: visible,
          width: fieldDef.width,
          minWidth: fieldDef.minWidth,
          cellTemplate: tplUrl
        };
      }

      function getDefinitions(contest) {
        return colums(contest).map(function (col) {
          return toDef(col);
        });
      }

      function getGridOptions(definitionsName) {
        return {
          data: 'challenges',
          headerRowTemplate: TemplateService.partial('tableView/header.html'),
          rowTemplate: TemplateService.partial('tableView/row.html'),
          headerRowHeight: 43,
          enableHighlighting: true,
          rowHeight: 140,
          columnDefs: definitionsName,
          enableRowSelection: false

        };
      }

      return {
        definitions: getDefinitions,
        gridOptions: getGridOptions
      };
    }]);
}(angular));
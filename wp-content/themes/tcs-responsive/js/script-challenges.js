var pageSize = 8;
var sortColumn = "";
var sortOrder = "";
var ApiData = {};

// I-104467 I-107029: default view for challenges
var default_view = "#tableView";
var isSearch = false;
/**
 * Challenges function
challenge
 */
appChallenges = {
    init: function() {
        if (navigator.userAgent.indexOf('MSIE 7.0') >= 0) {
            $('body').addClass('ie7');
            ie7 = true;
        }


        app.setPlaceholder($('.connected .email'));
        $('body').on(ev, function() {
            $('.btnMyAcc').removeClass('isActive');
            $('.userWidget:visible').hide();
        });
        $('.userWidget').on(ev, function(e) {
            e.stopPropagation();
        });

        if ($('.tooltip').length > 0) {
            app.tooltip();
        }


        app.initAjaxData();
        app.calendar();
        app.bindEvents();

        // I-104467 I-107029: check if there is already stored view
        if ($.cookie('viewMode') == null) {
            // I-104467 I-107029: if not, save the the default view to the cookie
            $.cookie('viewMode', default_view, { expires: 7, path: '/' });
        }

        // I-104467 I-107029: update the view mode (grid or table) according to the cookie value
        var viewHref = $.cookie('viewMode');
        var switchViewLink = $('.views a[href="' + viewHref + '"]');

        if (typeof(listType) != "undefined" && listType !== "Past" && !switchViewLink.hasClass('isActive')) {
            $('.viewTab').hide();
            $(viewHref).fadeIn('fast');
            $('.isActive', switchViewLink.parent()).removeClass('isActive');
            switchViewLink.addClass('isActive');
            app.ie7Fix();
        }
    },
    initAjaxData: function() {
        if ($('.dataChanges .viewAll').length <= 0 || !$('.dataChanges .viewAll').is(':visible')) {
            pageSize = 10000;
        }

        if (typeof(reviewType) != "undefined") {
            if (reviewType == "contest") {
                if (contest_type == 'data') {
                    app.getDataChallenges($('.tcoTable'), currentPage);
                } else {
                    app.getDesignContests($('.tcoTable'), currentPage);
                }
            } else if (reviewType == "review") {
                if (contest_type == "design" || contest_type == "develop") {
                    app.getReview($('.tcoTable'), currentPage);
                }
            } else if (reviewType == "data") {
                app.getDataChallenges($('.tcoTable'), currentPage);
            }
        }
    },
    bindEvents: function() {

        // filter check/ uncheck

        $('.filterOpts input:checkbox').on('change', function() {
            if (!$(this).is(':checked') && !$(this).hasClass('all')) {
                $('.filterOpts .all').prop("checked", false);
            }
        });

        // view siwtching
        $('.views a').off().on(ev, function(e) {
            if ($(this).hasClass('isActive')) {
                return false;
            }

            // app.initAjaxData(); no need to loading, when change state

            $('.viewTab').hide();
            id = $(this).attr('href');

            // I-104467 I-107029: store the view to the cookie
            $.cookie('viewMode', id, { expires: 7, path: '/' });

            $(id).fadeIn('fast');
            $('.isActive', $(this).parent()).removeClass('isActive');
            $(this).addClass('isActive');
            app.ie7Fix();

            return false;
        });

        // advanced search
        $('.advSearch').on(ev, function() {
            if ($(this).hasClass('isActive')) {
                $(this).removeClass('isActive');
                $('.searchFilter').fadeOut('fast');
            } else {
                $(this).addClass('isActive');
                $('.searchFilter').fadeIn();
				
				// Issue ID: I-111128 - enable/disable datepicker based on the status of the related checkbox
				$('.otherOpts .row').each(function() {
                    var cbDate = $(this).find('input:checkbox');
					$('.datepicker', $(this)).datepicker(cbDate.is(':checked') ? 'enable' : 'disable');
				});

        // populate technology tags
        app.getTechnologyTags($('.chosen-select'));
            }
        });

        $('.tcoTable').on(ev, '.action .btn', function() {
            $(this).replaceWith('<a href="javascript:;" class="btn">Submit</a>');
        });

        $('.otherOpts input:checkbox').on('change', function() {
            var row = $(this).closest('.row');
            if (row.hasClass('subRow1')) {
                row = row.parent();
            }
            if ($(this).is(':checked')) {
                // Issue ID: I-111128 - enable the datepicker
                $('.datepicker', row).datepicker("enable");
                $('img', row).css('opacity', 1).css('filter', 'alpha(opacity=100)');
                $('img', row).closest('.row').removeClass('isDisabled');
                $('input:text, select', row).removeAttr('disabled');
            } else {
                // Issue ID: I-111128 - enable the datepicker
                $('.datepicker', row).datepicker("disable");
                $('img', row).css('opacity', 1).css('opacity', 0.5).css('filter', 'alpha(opacity=50)');
                $('img', row).closest('.row').addClass('isDisabled');
                $('input:text, select', row).attr('disabled', 'disabled');
            }
        });

        //searchFilter
        $('.searchFilter .btnClose').on(ev, function() {
            $(this).closest('.searchFilter').fadeOut('fast');
            $('.advSearch').removeClass('isActive');
        });
        $('.searchFilter .btnApply').on(ev, function() {
            isSearch = true;
            app.initAjaxData();
            $(this).closest('.searchFilter').fadeOut('fast');
            $('.advSearch').removeClass('isActive');
        });

        /* select all filters */
        $('.filterOpts .types .all').on('change', function() {
            var cr = $(this).closest('.types');
            if ($(this).is(':checked')) {
                $('input:checkbox', cr).not($(this)).prop("checked", true);
            } else {
                $('input:checkbox', cr).not($(this)).prop("checked", false);
            }
        });

        /* view all records */
        $('.dataChanges .viewAll').off().on(ev, function() {
            postPerPage = 1000;

            if (reviewType == "contest") {
                if (contest_type == "design" || contest_type == "develop") {
                    app.getDesignContests($('.tcoTable'), 1);
                } else if (listType == "AllActive") {
                    app.getDesignContests($('.tcoTable'), 1);
                }
            } else if (reviewType == "review") {
                app.getReview($('.tcoTable'), 1);
            }

            $(this).closest('.dataChanges').hide();

        });

        /* view next */
        $('.dataChanges').off().on(ev, '.nextLink', function(e) {
            var nextPage = currentPage + 1;

            if (reviewType == "contest") {
                if (contest_type == "design" || contest_type == "develop") {
                    app.getDesignContests($('.tcoTable'), nextPage);
                } else if (listType == "AllActive") {
                    app.getDesignContests($('.tcoTable'), nextPage);
                }
            } else if (reviewType == "review") {
                app.getReview($('.tcoTable'), nextPage);
            } else if (reviewType == "data") {
                app.getDataChallenges($('.tcoTable'), nextPage);
            }
            e.preventDefault();
        });
        $('.dataChanges').on(ev, '.prevLink', function(e) {
            var prevPage = currentPage - 1;

            if (reviewType == "contest") {
                if (contest_type == "design" || contest_type == "develop") {
                    app.getDesignContests($('.tcoTable'), prevPage);
                } else if (listType == "AllActive") {
                    app.getDesignContests($('.tcoTable'), prevPage);
                }
            } else if (reviewType == "review") {
                app.getReview($('.tcoTable'), prevPage);
            } else if (reviewType == "data") {
                app.getDataChallenges($('.tcoTable'), prevPage);
            }
            e.preventDefault();
        });

        // challengeType
        $('.challengeType a').off().on(ev, function() {
            if ($(this).hasClass('active')) {
                return false;
            }
        });

        //table sorting function
        $('.tcoTable th').on(ev, function() {
            var getSortColumn = $(this).attr("data-placeholder");
            var getSortOrder = $(this).hasClass("asc") ? "desc" : "asc";

            if (!$(this).hasClass("noSort")) {
                sortColumn = getSortColumn;
                sortOrder = getSortOrder;
                if (reviewType == "contest") {
                    if (contest_type == "design" || contest_type == "develop") {
                        if (postPerPage >= apiData.total) {
                            apiData = app.apiDataSort(apiData, sortColumn, sortOrder);
                            app.apiDataView(apiData, $('.tcoTable'), app.callbackAfterSort($(this)));
                        } else {
                            app.getDesignContests($('.tcoTable'), currentPage, app.callbackAfterSort($(this)));
                        }
                    } else if (listType == "AllActive") {
                        app.getDesignContests($('.tcoTable'), currentPage, app.callbackAfterSort($(this)));
                    }
                } else if (reviewType == "review") {
                    app.getReview($('.tcoTable'), currentPage, app.callbackAfterSort($(this)));
                }

            }

        });
    },
    //sort data from previous api call so we dont have to make a new one, useful for 'view all' datasets
    apiDataSort: function(data, sortColumn, sortOrder) {
        switch(sortColumn) {
            case 'challengeType':
            case 'challengeName':
            case 'currentStatus':
                data.data.sort(function(a, b) {
                    if (sortOrder === 'asc') {
                        if (a[sortColumn] > b[sortColumn]) {
                            return 1;
                        }
                        if (a[sortColumn] < b[sortColumn]) {
                            return -1;
                        }
                        // a must be equal to b
                        return 0;
                    }
                    if (a[sortColumn] < b[sortColumn]) {
                        return 1;
                    }
                    if (a[sortColumn] > b[sortColumn]) {
                        return -1;
                    }
                    // a must be equal to b
                    return 0;
                });
                break;
            case 'postingDate':
                data.data.sort(function(a, b) {
                    a = new Date(a[sortColumn]);
                    b = new Date(b[sortColumn]);
                    if (sortOrder === 'asc') {
                        return a<b ? -1 : a>b ? 1 : 0;
                    }
                    return a>b ? -1 : a<b ? 1 : 0;
                });
                break;
            case 'challengeId':
            case 'projectId':
            case 'forumId':
            case 'numSubmissions':
            case 'numRegistrants':
            case 'numberOfCheckpointsPrizes':
            case 'digitalRunPoints':
                if (sortOrder === 'asc') {
                    data.data.sort(function(a, b) {
                        return (a[sortColumn] - b[sortColumn]);
                    });
                }
                data.data.sort(function(a, b) {
                    return (b[sortColumn] - a[sortColumn]);
                });
                break;
            default:
                break;
        }
        return data;
    },
    apiDataView: function(data, table, callback) {
         // If contest type
        if (!isBugRace) {
            if (typeof listType !== "undefined" && listType == "Past") {
                app.getDesignPastContestTable(table, data, null);
            } else if (typeof listType !== "undefined" && listType == "AllActive") {
                app.getAllContestTable(table, data, null);
                app.getAllContestGrid($('#gridView .contestGrid'), data, (null + 1));
                app.getDataLandingContests($('.tcoTable'), 1);
            } else if (typeof listType !== "undefined" && listType == "Upcoming") {
                app.getDesignUpcomingContestTable(table, data, null);
                app.getDesignUpcomingContestGrid($('#gridView .contestGrid'), data, (null + 1));
            } else {
                app.getDesignContestTable(table, data, null);
                app.getDesignContestGrid($('#gridView .contestGrid'), data, (null + 1));
            }
        } else { // If bug race type
            app.getBugraceTable(table, data, null);
        }
        /* call back */
        if (callback != null && callback != "") {
            callback();
        }
    },
    callbackAfterSort: function(ev) {
        var getSortColumn = ev.attr("data-placeholder");
        var getSortOrder = ev.hasClass("asc") ? "desc" : "asc";
        if (getSortOrder == "asc" && !ev.hasClass("asc")) {
            ev.addClass("asc");
        } else {
            ev.removeClass("asc");
        }
    },
    // tooltip functions
    tooltip: function() {
        $('.dataTable, .contestGrid').on('mouseenter', '.colType .ico, .coleSub .subs, .ico.trackType, a .itco', function() {
            var tt = $('#typeTooltip');
            tt.addClass('isShowing');

            // I-107026: Add class devTooltip if the contest is not design contest.
            var contestType = $('.tipC', $(this)).html();
            if (!app.isDesignContest(contestType)) {
                tt.addClass('devTooltip');
            } else if (tt.hasClass('devTooltip')) {
                tt.removeClass('devTooltip');
            }

            $(this).addClass('activeLink');
            $('header', tt).html($('.tipT', $(this)).html());
            var $contestType = $('.tipC', $(this));
            $('.contestTy', tt).html($contestType.html());
            if ($contestType.data('contest_type') == 'develop') {
                tt.addClass('devTooltip');
            } else if (tt.hasClass('devTooltip')) {
                tt.removeClass('devTooltip');
            }

            if ($(this).hasClass('itco')) {
                var tempTcoTooltipTitle = typeof tcoTooltipTitle !== "undefined" ? tcoTooltipTitle : "TCO-14";
                var tempTcoTooltipMessage = typeof tcoTooltipMessage !== "undefined" ? tcoTooltipMessage : "Egalible for TCO14";
                $('header', tt).html(tempTcoTooltipTitle);
                $('.contestTy', tt).html(tempTcoTooltipMessage);
            }

            var ht = tt.height();
            var wt = tt.width() - $('.activeLink').width();
            var activeLinkOffset = $('.activeLink').offset();

            var isFirefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
            var addedHeight = (isFirefox) ? 40 : 10;

            tt.css('z-index', '-1').stop().fadeIn();

            window.setTimeout(function() {
                var ttNew = $('.tooltip.isShowing');
                var ht = tt.height();
                var wt = tt.width() - $('.activeLink').width();

                var activeLinkTop = $('.activeLink').offset().top;
                var top = $('.activeLink').offset().top - ht - 10;
                var lt = $('.activeLink').offset().left - wt / 2;
                ttNew.css('left', lt).css('top', top);
                ttNew.css('z-index', '2000').css('opacity', '1');
                $('.isShowing').removeClass('isShowing');
                $('.activeLink').removeClass('activeLink');
            }, 10);
        });
        $('.dataTable, .contestGrid').on('mouseleave', '.colType .ico, .coleSub .subs, .ico.trackType, a .itco', function() {
            var tt = $('#typeTooltip');
            tt.css('top', -20000);
        });

        //winner tooltip
        $('.dataTable').on('mouseenter', '.colStandings .winner', function() {
            var tt = $('#winnerTooltip');
            tt.addClass('isShowing');
            tt.removeClass('isHiding');
            $(this).addClass('activeLink');
            $('header', tt).html($('.tipT', $(this)).html());
            $('.winnerInfo', tt).html($('.tipC', $(this)).html());

            tt.css('z-index', '-1').stop().fadeIn('fast');

            window.setTimeout(function() {
                var ttNew = $('.tooltip.isShowing');
                var ht = tt.height();
                var wt = tt.width();
                var top = $('.activeLink').offset().top - ht - 10;
                var lt = $('.activeLink').offset().left - wt / 2 + 10;
                ttNew.css('left', lt).css('top', top);
                ttNew.css('z-index', '2000').css('opacity', '1');
                $('.isShowing').removeClass('isShowing');
                $('.activeLink').removeClass('activeLink');
            }, 2);
        });
        $('.dataTable').on('mouseleave', '.colStandings .winner', function() {
            var tt = $('#winnerTooltip');
            tt.addClass('isHiding');
            window.setTimeout(function() {
                var tt = $('#winnerTooltip');
                if (tt.hasClass('isHiding')) {
                    tt.hide();
                    tt.removeClass('isHiding');
                }
            }, 400);
        });
        $('#winnerTooltip').on('mouseenter', function() {
            var tt = $('#winnerTooltip');
            tt.removeClass('isHiding');
        });
        $('#winnerTooltip').on('mouseleave', function() {
            var tt = $('#winnerTooltip');
            tt.removeClass('isHiding');
            tt.hide();
        });
    },

    //Calander
    calendar: function() {
        var datePickerTo = $(".datepicker.to");

        datePickerTo.datepicker({
            showOn: 'both',
            buttonImage: stylesheet_dir + '/i/ico-cal.png',
            buttonImageOnly: true,
            dateFormat: 'yy-mm-dd',
            buttonText: "",
            onSelect: function(selectedDate) {
                $(".datepicker.from").datepicker("option", "maxDate", selectedDate);
            }
        });

        var row = datePickerTo.closest('.row');
        $('.datepicker', row).datepicker("option", "disabled", true);
        $('img', row).css('opacity', 1).css('opacity', 0.5).css('filter', 'alpha(opacity=50)');
        $('img', row).closest('.row').addClass('isDisabled');
        $('input:text, select', row).attr('disabled', 'disabled');

        var datePickerFrom = $(".datepicker.from");

        datePickerFrom.datepicker({
            showOn: 'both',
            buttonImage: stylesheet_dir + '/i/ico-cal.png',
            buttonImageOnly: true,
            dateFormat: 'yy-mm-dd',
            onSelect: function(selectedDate) {
                datePickerFrom.datepicker("option", "minDate", selectedDate);
            }
        });
        datePickerFrom.closest('.row').addClass('isDisabled');

        row = datePickerFrom.closest('.row');
        $('.datepicker', row).datepicker("option", "disabled", true);
        $('img', row).css('opacity', 1).css('opacity', 0.5).css('filter', 'alpha(opacity=50)');
        $('img', row).closest('.row').addClass('isDisabled');
        $('input:text, select', row).attr('disabled', 'disabled');
    },

    getTrackSymbol: function(type) {
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
            case ("First2Finish" || "Design First2Finish"):
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
    },

  /* populates technology tags drop down */
  getTechnologyTags: function(list, callback) {
      var param = {};
        param.action = 'get_all_platforms_and_technologies';

        $.ajax({
            url: ajaxUrl,
            data: param,
            type: "GET",
            dataType: "json",
            success: function(data) {
              if (typeof data['platforms'] !== 'undefined' && data['platforms'].length > 0) {
                var $pOptGroup = $('<optgroup label="Platforms">');
                $.each(data['platforms'], function(key, val) {
                  $pOptGroup.append('<option value="' + val + '">' + val + '</option>');
                });
                $(list).append($pOptGroup);
              }

              if (typeof data['technologies'] !== 'undefined' && data['technologies'].length > 0) {
                var $tOptGroup = $('<optgroup label="Technologies">');
                $.each(data['technologies'], function(key, val) {
                  $tOptGroup.append('<option value="' + val + '">' + val + '</option>');
                });
                $(list).append($tOptGroup);
              }

              $(list).trigger("chosen:updated");

                /* call back */
              if (callback != null && callback != "") {
                callback();
              }
            },

            fail: function(data) {
              $('.tags').hide();
            }
        });
    },

    getDataChallenges: function(table, pageIndex, callback) {

        app.setLoading();
        var param = {};
        param.action = ajaxAction;
        param.pageIndex = pageIndex;
        param.pageSize = postPerPage;
        param.contest_type = "data/marathon";
        param.listType = listType;

        var challengesUrlEndFrom = app.getParameterByName('submission_end_date_from');
        if (challengesUrlEndFrom  != null){
            param.submissionEndFrom = challengesUrlEndFrom;
            if (!isSearch){
                $('#fSDate').prop('checked',true);
                $("#startDate").val(param.submissionEndFrom);
            }
        }
        console.log(param.submissionEndFrom+"$");
        var challengesUrlEndTo = app.getParameterByName('submission_end_date_to');
        if (challengesUrlEndTo  != null){
            param.submissionEndTo = challengesUrlEndTo;
            if (!isSearch){
                $('#fEDate').prop('checked',true);
                $("#endDate").val(param.submissionEndTo);
            }
        }
        var challengesUrlType = app.getParameterByName('type');
        if (challengesUrlType  != null){
            param.challengeType = challengesUrlType;
            if (!isSearch) $("input[name='radioFilterChallenge'][value='"+param.challengeType+"']").attr("checked",true);
        }

        $.ajax({
            url: ajaxUrl,
            data: param,
            type: "GET",
            dataType: "json",
            success: function(data) {
                currentPage = pageIndex;

                var latestRecords = currentPage * postPerPage; // Latest record read by user

                $("#challengeNav a").hide();
                if (latestRecords < data.total) {
                    $("#challengeNav .nextLink").show();
                }
                if (currentPage > 1) {
                    $("#challengeNav .prevLink").show();
                }

                app.getDataChallengesTable(table, data, null);

                /* call back */
                if (callback != null && callback != "") {
                    callback();
                }
            },
            fail: function(data) {
                $('.loading').hide();
                //$('tbody', table).html(null);
                alert(" Data not found!");
            }
        });
    },

    /* table draw function */
    getDataChallengesTable: function(table, data, records2Disp, isAppend) {

        if (isAppend != true) {
            $('tbody', table).html(null);
        }
								$('thead', table).show();
        var count = 0;
        //JS uncaught typeError when no data available, so adding defined check
        if (typeof data.data !== 'undefined' && data.data.length > 0) {
            $.each(data.data, function(key, rec) {

                var row = $(challengesBP.tabData).clone();
                /*
                 * generate table row for design past contest type
                 */
                if (typeof rec.numberOfRegistrants !== "undefined") {
                    $('.contestName', row).html('<img alt="" class="allContestIco" src="' + stylesheet_dir + '/i/ico-track-data.png" />' + '<a href="http://community.topcoder.com/tc?module=MatchDetails&rd=' + rec.roundId + '">' + rec.fullName + '</a>');
                    $('.colType', row).html("Marathon");
                    $('.vStartDate', row).html(app.formatDate2(rec.startDate));
                    $('.vEndDate', row).html(app.formatDate2(rec.endDate));
                    $('.colTLeft', row).html(app.formatTimeLeft(rec.timeRemaining));
                    $('.colReg', row).html('<a href=" http://community.topcoder.com/longcontest/?module=ViewStandings&rd=' + rec.roundId + '">' + rec.numberOfRegistrants + '</a>');
                    $('.colSub', row).html(rec.numberOfSubmissions);
                } else {
                    //$('.contestName', row).html(rec.fullName);
                    $('.contestName', row).html('<img alt="" class="allContestIco" src="' + stylesheet_dir + '/i/ico-track-data.png" />' + '<a href="http://community.topcoder.com/tc?module=MatchDetails&rd=' + rec.roundId + '">' + rec.fullName + '</a>');
                    $('.colType', row).html("Marathon");
                    $('.vStartDate', row).html(app.formatDate2(rec.startDate));
                    $('.vEndDate', row).html(app.formatDate2(rec.endDate));
                    $('.colTLeft', row).html(app.formatTimeLeft(rec.timeRemaining));
                    $('.colReg', row).html(rec.numberOfRegistrants);
                    $('.colSub', row).html(rec.numberOfSubmissions);
                }

                $('tbody', table).append(row);
            });
            app.initZebra(table);
        } else {
            app.addEmptyResult(table, 'active');
        }

        $('.loading').hide();
    },

    getReview: function(table, pageIndex, callback) {

        app.setLoading();
        var param = {};
        param.action = ajaxAction;
        param.contest_type = contest_type;
        param.pageIndex = pageIndex;

        if (postPerPage != -1) {
            param.pageSize = postPerPage;
        }
        if (sortColumn != "") {
            param.sortColumn = sortColumn;
            param.sortOrder = sortOrder;
        } else {
            param.sortColumn = 'registrationOpen';
            param.sortOrder = 'desc';
        }

        $.ajax({
            url: ajaxUrl,
            data: param,
            type: "GET",
            dataType: "json",
            success: function(data) {
                /* show hide navigation */
                currentPage = pageIndex;
                if (postPerPage != -1) {
                    var latestRecords = currentPage * postPerPage; // Latest record read by user

                    $("#challengeNav a").hide();
                    if (latestRecords < data.total) {
                        $("#challengeNav .nextLink").show();
                    }
                    if (currentPage > 1) {
                        $("#challengeNav .prevLink").show();
                    }
                    if (data.total <= postPerPage) {
                        $(".viewAll").hide();
                    }
                } else {
                    $("#challengeNav a").hide();
                }

                if (contest_type == "develop") {
                    app.getDevReviewTable(table, data, null);
                } else {
                    app.getDesignReviewTable(table, data, null);
                }

                /* call back */
                if (callback != null && callback != "") {
                    callback();
                }
            },
            fail: function(data) {
                $('.loading').hide();
                //$('tbody', table).html(null);
                alert("Data not found!");
            }
        });
    },

    /* table draw function */
    getDesignReviewTable: function(table, data, records2Disp, isAppend) {

        if (isAppend != true) {
            $('tbody', table).html(null);
        }
        var count = 0;
        //JS uncaught typeError when no data available, so adding defined check
        if (typeof data.data !== 'undefined' && data.data.length > 0) {
            $.each(data.data, function(key, rec) {

                var row = $(challengesBP.tabReivew).clone();

                var trackName = app.getTrackSymbol(rec.challengeType);
                var round1ScheduledStartDate = app.formatDate2(rec.round1ScheduledStartDate);
                var round2ScheduledStartDate = app.formatDate2(rec.round2ScheduledStartDate);
                var contestLinkUrl = siteurl + "/review-opportunity/design/" + "30036202";

                row.addClass('track-' + trackName);
                /*
                 * generate table row for design past contest type
                 */

                $('.colCh a', row).attr("href", contestLinkUrl);
                $('.contestName', row).html(rec.challengeName);
                $('.contestName', row).closest('td').addClass('nonTCO');
                $('.colType', row).html(rec.type);
                $('.colR1start', row).html(round1ScheduledStartDate);
                $('.colR2start', row).html(round2ScheduledStartDate);
                $('.colPay', row).html("$" + app.formatCur(rec.reviewerPayment));
                $('.colStatus', row).html('<a href="' + contestLinkUrl + '">' + rec.type + '</a>');

                $('tbody', table).append(row);
            });
            app.initZebra(table);
        }
        $('.loading').hide();
    },

    /* table draw function */
    getDevReviewTable: function(table, data, records2Disp, isAppend) {
        if (isAppend != true) {
            $('tbody', table).html(null);
        }

        var count = 0;
        //JS uncaught typeError when no data available, so adding defined check
        if (typeof data.data !== 'undefined' && data.data.length > 0) {
            $.each(data.data, function(key, rec) {

                var row = $(challengesBP.tabDevReivew).clone();

                var trackName = app.getTrackSymbol(rec.challengeType);
                var reviewStart = rec.reviewStart;
                var contestLinkUrl = siteurl + "/review-opportunity/develop/" + "30039083";

                row.addClass('track-' + trackName);
                /*
                 * generate table row for design contest type
                 */
                $('.colCh a', row).attr("href", contestLinkUrl);
                $('.contestName', row).html(rec.contestName);
                //$('.contestName', row).closest('td').addClass('nonTCO');
                $('.colRPay', row).html("$" + app.formatCur(rec.primaryReviewerPayment));
                $('.colRstart', row).html(rec.reviewStart.replace(/ /g, '&nbsp;'));
                $('.colSub', row).html(rec.submissionsNumber);
                $('.colOPos', row).html(rec.numberOfReviewPositionsAvailable);
                $('.colStatus', row).html('<a href="' + contestLinkUrl + '">details</a>');

                $('tbody', table).append(row);
            });
            app.initZebra(table);
        }

        $('.loading').hide();
    },

    getDesignContests: function(table, pageIndex, callback) {
        app.setLoading();
        var param = {};
        param.action = ajaxAction;
        if (listType != "AllActive") {
            param.contest_type = contest_type;
            param.listType = listType;
        }
        param.pageIndex = pageIndex;
        if (postPerPage != -1) {
            param.pageSize = postPerPage;
        }
        if (sortColumn != "") {
            param.sortColumn = sortColumn;
            param.sortOrder = sortOrder;
        } else {
            param.sortColumn = 'submissionEndDate';
            param.sortOrder = 'desc';
        }

        var challengesUrlEndFrom = app.getParameterByName('submission_end_date_from');
        if (challengesUrlEndFrom  != null){
            param.submissionEndFrom = challengesUrlEndFrom;
            if (!isSearch){
                $('#fSDate').prop('checked',true);
                $("#startDate").val(param.submissionEndFrom);
            }
        }
        var challengesUrlEndTo = app.getParameterByName('submission_end_date_to');
        if (challengesUrlEndTo  != null){
            param.submissionEndTo = challengesUrlEndTo;
            if (!isSearch){
                $('#fEDate').prop('checked',true);
                $("#endDate").val(param.submissionEndTo);
            }
        }

        var startDate = $("#startDate").val();
        var endDate = $("#endDate").val();
        if ($.trim(startDate) != "" && $('#fSDate').prop('checked')) {
            param.submissionEndFrom = startDate;
        }
        if ($.trim(endDate) != "" && $('#fEDate').prop('checked')) {
            param.submissionEndTo = endDate;
        }

        // if submission from date is blank form to date isn't
        if (!param.submissionEndFrom && param.submissionEndTo) {
            param.submissionEndFrom = app.formatDateApi(new Date(1));
        }

        if (!param.submissionEndTo && param.submissionEndFrom) {
            // 60 days from today
            var futureDate = 60 * 24 * 60 * 60 * 1000;
            var curDate = Date.now();
            param.submissionEndTo = app.formatDateApi(new Date(curDate + futureDate));
        }

        var challengesUrlType = app.getParameterByName('type');
        if (challengesUrlType  != null){
            param.challengeType = challengesUrlType;
            if (!isSearch) $("input[name='radioFilterChallenge'][value='"+param.challengeType+"']").attr("checked",true);
        }

        var challengesRadio = $("input:radio[name ='radioFilterChallenge']:checked").val();
        if (challengesRadio != null && challengesRadio != "all") {
            param.challengeType = challengesRadio;
        }

        // get all chosen technology tags if any
        if (contest_type == 'develop') {
          var platforms = [];
          var technologies = [];

          $('.chosen-select :selected').each(function (i, selected) {
              // categorize each selected value into platforms or technologies
            var categoryLabel = $(selected).closest('optgroup').prop('label').toLowerCase();
            var selectedVal = $(selected).val();
            if (categoryLabel === 'platforms') {
                platforms.push(selectedVal);
            } else if (categoryLabel === 'technologies') {
                technologies.push(selectedVal);
            }
          });

          if (platforms.length > 0) {
              param.platforms = platforms.join();
          }

          if (technologies.length > 0) {
              param.technologies = technologies.join();
          }
        }

        $.ajax({
            url: ajaxUrl,
            data: param,
            type: "GET",
            dataType: "json",
            success: function(data) {
                /* show hide navigation */
                currentPage = pageIndex;
                apiData = data;
                if (postPerPage != -1) {
                    var latestRecords = currentPage * postPerPage; // Latest record read by user

                    $("#challengeNav a").hide();
                    if (latestRecords < data.total) {
                        $("#challengeNav .nextLink").show();
                    }
                    if (currentPage > 1) {
                        $("#challengeNav .prevLink").show();
                    }

                    if (typeof data.total === 'undefined' || data.total <= postPerPage) {
                        $(".viewAll").hide();
                    } else {
                        $(".viewAll").show();
                    }
                } else {
                    $("#challengeNav a").hide();
                }

                // If contest type
                if (!isBugRace) {
                    if (typeof(listType) != "undefined" && listType == "Past") {
                        app.getDesignPastContestTable(table, data, null);
                    } else if (typeof(listType) != "undefined" && listType == "AllActive") {
                        app.getAllContestTable(table, data, null);
                        app.getAllContestGrid($('#gridView .contestGrid'), data, (null + 1));
                        app.getDataLandingContests($('.tcoTable'), 1);
                    } else if (typeof listType !== "undefined" && listType == "Upcoming") {
                        app.getDesignUpcomingContestTable(table, data, null);
                        app.getDesignUpcomingContestGrid($('#gridView .contestGrid'), data, (null + 1));
                    } else {
                        app.getDesignContestTable(table, data, null);
                        app.getDesignContestGrid($('#gridView .contestGrid'), data, (null + 1));
                    }
                } else { // If bug race type
                    app.getBugraceTable(table, data, null);
                }

                /* call back */
                if (callback != null && callback != "") {
                    callback();
                }
            },
            fail: function(data) {
                $('.loading').hide();
                //    $('tbody', table).html(null);
                $(".viewAll").hide();
                alert("Data not found!");
            }
        });

    },

    getDataLandingContests: function(table, pageIndex, callback) {
        app.setLoading();
        var param = {};
        param.action = "get_active_data_challenges";
        param.pageIndex = 1;
        param.pageSize = 2;

        $.ajax({
            url: ajaxUrl,
            data: param,
            type: "GET",
            dataType: "json",
            success: function(data) {
                app.getAllContestTable(table, data, null, true, true);
                app.getAllContestGrid($('#gridView .contestGrid'), data, (null + 1), true, true);

                /* call back */
                if (callback != null && callback != "") {
                    callback();
                }
            },
            fail: function(data) {
                $('.loading').hide();
                //$('tbody', table).html(null);
                alert("Data not found!");
            }
        });
    },

    /* table draw function */
    getAllContestTable: function(table, data, records2Disp, isAppend, isDataScience) {
        isAppend = typeof isAppend === 'undefined' ? false : isAppend;
        isDataScience = typeof isDataScience === 'undefined' ? false : isDataScience;

        if (isAppend != true) {
            $('tbody', table).html(null);
        }
        var count = 0;
        //JS uncaught typeError when no data available, so adding defined check
        if (typeof data.data !== 'undefined' && data.data.length > 0) {
            $.each(data.data, function(key, rec) {
                if (isDataScience) {
                    var row = $(challengesBP.tabAllData).clone();

                    var startDate = app.formatDate2(rec.startDate);
                    var totalCompetitors = rec.totalCompetitors;
                    var numSubmissions = rec.divIITotalSolutionsSubmitted;

                    $('.contestName', row).html('<img alt="" class="allContestIco" src="' + stylesheet_dir + '/i/ico-track-data.png" />' + rec.fullName + '</a>');
                    $('.contestName', row).parents(".inTCO").addClass("hasTCOIco");
                    $('.contestName', row).attr('href', 'http://community.topcoder.com/tc?module=MatchDetails&rd=' + rec.roundId);

                    //$('.contestName', row).html('<i></i>' + '<a href="http://community.topcoder.com/tc?module=MatchDetails&rd=' + rec.roundId + '">' + rec.fullName + '</a>');
                    //$('.colReg', row).html(rec.numberOfRegistrants);


                    $('.vEndRound', row).html(startDate);
                    $('.colReg', row).html('<a href=" http://community.topcoder.com/longcontest/?module=ViewStandings&rd=' + rec.roundId + '">' + rec.numberOfRegistrants + '</a>');
                    $('.colSub', row).html(numSubmissions);

                    $('tbody', table).append(row);
                } else {
                    var row = $(challengesBP.tabAll).clone();

                    var trackName = app.getTrackSymbol(rec.challengeType);
                    var startDate = app.formatDate2(rec.postingDate);
                    var checkPointDate;
                    if (rec.checkpointSubmissionEndDate) {
                        checkPointDate = app.formatDate2(rec.checkpointSubmissionEndDate);
                    }

                    var endDate = app.formatDate2(rec.submissionEndDate);
                    var remainingTime = app.formatTimeLeft(rec.currentPhaseRemainingTime);
                    var purse = 0;
                    for (var i = 0; i < rec.prize.length; i++)
                        purse += rec.prize[i];

                    if (rec.challengeCommunity == "design") {
                      var icoTrack = "ico-track-design.png";
                      var tcoFlag = "tco-flag-design.png";
                    } else {
                        var icoTrack = "ico-track-develop.png";
                        var tcoFlag = "tco-flag-develop.png";
                        row = $(challengesBP.tabAllDev).clone();
                        if (rec.registrationEndDate) {
                            checkPointDate = app.formatDate2(rec.registrationEndDate);
                        }
                    }
                    var contestLinkUrl = app.getContestLinkUrl(rec.challengeId, rec.challengeCommunity);

                    row.addClass('track-' + trackName);
                    /*
                     * generate table row for design contest type
                     */
                    $('.contestName', row).html('<img alt="" class="allContestIco" src="' + stylesheet_dir + '/i/' + icoTrack + '" />' + rec.challengeName + '<img alt="" class="allContestTCOIco" src="' + stylesheet_dir + '/i/' + tcoFlag + '" />');
                    $('.contestName', row).parents(".inTCO").addClass("hasTCOIco");
                    $('.colCh a, .cgCh a', row).attr("href", contestLinkUrl);

                    $('.tipC', row).html(rec.challengeType);
					$('.tipC', row).data('contest_type', rec.challengeCommunity);

                    $('.vStartDate', row).html(startDate);

                    if (checkPointDate) {
                        $('.vEndRound', row).html(checkPointDate);
                    } else {
                        $('.vEndRound', row).parent().empty();
                    }

                    $('.vEndDate', row).html(endDate);

                    $('.colTLeft', row).html(remainingTime);

                    if (rec.isEnding === "true") {
                        $('.colTLeft', row).addClass('imp');
                    }

                    $('.colPur', row).html("$" + app.formatCur(purse));

                    $('.colReg', row).html('<a href="' + contestLinkUrl + '#viewRegistrant">' + rec.numRegistrants + '</a>');

                    $('.colSub', row).html(rec.numSubmissions);

                    $('tbody', table).append(row);
                }
            });
            app.initZebra(table);
        }
    },

    addEmptyResult: function(table) {        
								$('thead', table).hide();
        var toUpdate = $('tbody', table).length > 0 ? $('tbody', table) : $(table);
        toUpdate.html('<tr><td style="font-size:20px;">There are no active challenges under this category. Please check back later</td></tr>');
    },

    // getGridview Blocks
    getAllContestGrid: function(gridEl, data, records2Disp, isAppend, isDataScience) {
        isAppend = typeof isAppend === 'undefined' ? false : isAppend;
        isDataScience = typeof isDataScience === 'undefined' ? false : isDataScience;

        if (isAppend != true) {
            gridEl.html(null);
        }

        var count = 0;
        //JS uncaught typeError when no data available, so adding defined check
        if (typeof data.data !== 'undefined' && data.data.length > 0) {
            $.each(data.data, function(key, rec) {
                if (isDataScience) {
                    var con = $(challengesBP.grDataAll).clone();

                    var startDate = app.formatDate2(rec.startDate);
                    var totalCompetitors = rec.totalCompetitors;
                    var numSubmissions = rec.divIITotalSolutionsSubmitted;

                    var trackName = "trackAn";
                    con.addClass(trackName);
                    var contestName = rec.name.length > 60 ? rec.name.substr(0, 61) + '...' : rec.name;
                    $('.contestName', con).html('<img alt="" class="allContestIco" src="' + stylesheet_dir + '/i/ico-track-data.png" />' + contestName);
                    $('.contestName', con).parents(".inTCO").addClass("hasTCOIco");
                    $('.vStartDate', con).html(startDate);
                    $('.cgReg', con).html('<i></i>' + totalCompetitors);
                    $('.cgSub', con).html('<i></i>' + numSubmissions);
                    gridEl.append(con);
                } else {
                    var con = $(challengesBP.grDOpen).clone();

                    var trackName = app.getTrackSymbol(rec.challengeType);
                    trackName += " trackSD";

                    var startDate = app.formatDate2(rec.postingDate);
                    var checkPointDate;
                    if (rec.checkpointSubmissionEndDate) {
                        checkPointDate = app.formatDate2(rec.checkpointSubmissionEndDate);
                    }

                    var endDate = app.formatDate2(rec.submissionEndDate);
                    var remainingTime = app.formatTimeLeft(rec.currentPhaseRemainingTime, true);
                    var purse = 0;
                    for (var i = 0; i < rec.prize.length; i++)
                        purse += rec.prize[i];

                    var icoTrack;
                    var tcoFlag;

                    if (rec.challengeCommunity == "design") {
                      icoTrack = "ico-track-design.png";
                      tcoFlag = "tco-flag-design.png";
                    } else {
                      icoTrack = "ico-track-develop.png";
                      tcoFlag = "tco-flag-develop.png";
                      row = $(challengesBP.tabAllDev).clone();
                      if (rec.registrationEndDate) {
                          checkPointDate = app.formatDate2(rec.registrationEndDate);
                      }
                    }

                    var contestLinkUrl = app.getContestLinkUrl(rec.challengeId, rec.challengeCommunity);
                    var contestName = rec.challengeName.length > 60 ? rec.challengeName.substr(0, 61) + '...' : rec.challengeName;


                    con.addClass('track-' + trackName);
                    con.addClass('type-' + rec.challengeCommunity);

                    $('.contestName', con).html('<img alt="" class="allContestIco" src="' + stylesheet_dir + '/i/' + icoTrack + '" />' + contestName + '<img alt="" class="allContestTCOIco" src="' + stylesheet_dir + '/i/' + tcoFlag + '" />');
                    $('.contestName', con).parents(".inTCO").addClass("hasTCOIco");

                    $('.colCh a, .cgCh a', con).attr("href", contestLinkUrl);

                    $('.type', con).html(rec.challengeType);
                    $('.tipC', con).html(rec.challengeType);
					$('.tipC', con).data('contest_type', rec.challengeCommunity);
                    $('.vStartDate', con).html(startDate);
                    if (checkPointDate) {
                        $('.vEndRound', con).html(checkPointDate);
                    } else {
                        $('.vEndRound', con).parent().empty();
                    }

                    $('.vEndDate', con).html(endDate);
                    $('.vPhase', con).html(rec.currentPhaseName);

                    $('.cgTLeft', con).html('<i></i>' + remainingTime);
                    if (rec.isEnding === "true") {
                        $('.cgTLeft', con).addClass('imp');
                    }
                    $('.cgPur', con).html('<i></i> $' + purse);
                    $('.cgReg', con).html('<i></i>' + '<a href="' + contestLinkUrl + '#viewRegistrant">' + rec.numRegistrants + '</a>');
                    $('.cgSub', con).html('<i></i>' + rec.numSubmissions);

                    $('.cgTLeft', con).qtip({
                        content: {
                            text: remainingTime,
                            title: 'Time Left'
                        },
                        style: {
                            classes: 'qtip-' + rec.challengeCommunity + ' qtip-rounded qtip-shadow'
                        },
                        position: {
                            my: 'bottom center',
                            at: 'top center '
                        }
                    });
                    $('.cgPur', con).qtip({
                        content: {
                            text: '$' + purse,
                            title: 'Prize Purse'
                        },
                        style: {
                            classes: 'qtip-' + rec.challengeCommunity + ' qtip-rounded qtip-shadow'
                        },
                        position: {
                            my: 'bottom center',
                            at: 'top center '
                        }
                    });
                    $('.cgReg', con).qtip({
                        content: {
                            text: rec.numRegistrants || '0',
                            title: 'Registrants'
                        },
                        style: {
                            classes: 'qtip-' + rec.challengeCommunity + ' qtip-rounded qtip-shadow'
                        },
                        position: {
                            my: 'bottom center',
                            at: 'top center '
                        }
                    });
                    $('.cgSub', con).qtip({
                        content: {
                            text: rec.numSubmissions || '0',
                            title: 'Submissions'
                        },
                        style: {
                            classes: 'qtip-' + rec.challengeCommunity + ' qtip-rounded qtip-shadow'
                        },
                        position: {
                            my: 'bottom center',
                            at: 'top center '
                        }
                    });

                    gridEl.append(con);
                }
                window.setTimeout(function() {
                    window.setTimeout(function() {
                        $('.loading').hide();
                    }, 2000);
                }, 5);
            });
        }
    },

    /* table draw function */
    getDesignContestTable: function(table, data, records2Disp, isAppend) {
        if (isAppend != true) {
            $('tbody', table).html(null);
        }
								$('thead', table).show();
        var count = 0;
        //JS uncaught typeError when no data available, so adding defined check
        if (typeof data.data !== 'undefined' && data.data.length > 0) {
            $.each(data.data, function(key, rec) {

                var row = $(challengesBP.gdOpen).clone();

                var trackName = app.getTrackSymbol(rec.challengeType);
                var startDate = app.formatDate2(rec.postingDate);
                var checkPointDate;
                if (rec.checkpointSubmissionEndDate) {
                    checkPointDate = app.formatDate2(rec.checkpointSubmissionEndDate);
                }

                var endDate;
                if (rec.submissionEndDate) {
                  endDate = app.formatDate2(rec.submissionEndDate);
                }

                var remainingTime = app.formatTimeLeft(rec.currentPhaseRemainingTime);
                var contestLinkUrl = app.getContestLinkUrl(rec.challengeId, rec.challengeCommunity);

                var purse = 0;
                for (var i = 0; i < rec.prize.length; i++)
                    purse += rec.prize[i];

                if (contest_type == "develop") {
                    row = $(challengesBP.gdDevOpen).clone();
                    if (rec.registrationEndDate) {
                        checkPointDate = app.formatDate2(rec.registrationEndDate);
                    }
                }

                row.addClass('track-' + trackName);
                /*
                 * generate table row for design contest type
                 */
                var icoTrack = "ico-track-design.png";
                var tcoFlag = "tco-flag-design.png";
                if (rec.challengeCommunity == "develop") {
                  icoTrack = "ico-track-develop.png";
                  tcoFlag = "tco-flag-develop.png";
                }

                $('.contestName', row).html('<img alt="" class="allContestIco" src="' + stylesheet_dir + '/i/' + icoTrack + '" />' + rec.challengeName + '<img alt="" class="allContestTCOIco" src="' + stylesheet_dir + '/i/' + tcoFlag + '" />');
                $('.contestName', row).parents(".inTCO").addClass("hasTCOIco");
                $('.colCh a, .cgCh a', row).attr("href", contestLinkUrl);

                if (contest_type == "develop" && !app.isEmptyArray(rec.technologies)) {
                  var $div = $('<div>');
                  $div.prop("id", rec.challengeId).addClass("technologyTags");
                  var $ul =$('<ul>')
                  $.each(rec.technologies, function(_, sp){
                    $ul.append('<li><span>' + sp + '</span></li>');
                  });

                  $div.append($ul);
                  $('.colCh', row).append($div);
                }

                $('.tipC', row).html(rec.challengeType);
                $('.tipC', row).data('contest_type', rec.challengeCommunity);
				
                $('.vStartDate', row).html(startDate);

                if (checkPointDate) {
                    $('.vEndRound', row).html(checkPointDate);
                } else {
                    $('.vEndRound', row).parent().empty();
                }

                $('.vEndDate', row).html(endDate);

                $('.colTLeft', row).html(remainingTime);

                if (rec.isEnding === "true") {
                    $('.colTLeft', row).addClass('imp');
                }

                $('.colPur', row).html("$" + app.formatCur(purse));

                $('.colPhase', row).html(rec.registrationOpen == 'Yes' ? 'Open to All' : 'Open to Challenge Registrants');

                $('.colReg', row).html('<a href="' + contestLinkUrl + '#viewRegistrant">' + rec.numRegistrants + '</a>');

                $('.colSub', row).html(rec.numSubmissions);

                $('tbody', table).append(row);
            });
            app.initZebra(table);
        } else {
            app.addEmptyResult(table, 'active');
            $('.loading').hide();
        }
    },

    // getGridview Blocks
    getDesignContestGrid: function(gridEl, data, records2Disp) {
        gridEl.html(null);

        var count = 0;
        //JS uncaught typeError when no data available, so adding defined check
        if (typeof data.data !== 'undefined' && data.data.length > 0) {
            $.each(data.data, function(key, rec) {

                var con = $(challengesBP.grDOpen).clone();

                var trackName = app.getTrackSymbol(rec.challengeType);
                trackName += " trackSD";

                var startDate = app.formatDate2(rec.postingDate);
                var checkPointDate = app.formatDate2(rec.checkpointSubmissionEndDate);
                if (rec.checkpointSubmissionEndDate) {
                    checkPointDate = app.formatDate2(rec.checkpointSubmissionEndDate);
                }
                var endDate = app.formatDate2(rec.submissionEndDate);
                var remainingTime = app.formatTimeLeft(rec.currentPhaseRemainingTime, true);
                var contestLinkUrl = app.getContestLinkUrl(rec.challengeId, rec.challengeCommunity);

                var purse = 0;
                for (var i = 0; i < rec.prize.length; i++)
                    purse += rec.prize[i];

                /* for develop type contest */
                var icoTrack = "ico-track-design.png";
                var tcoFlag = "tco-flag-design.png";
                if (rec.challengeCommunity == "develop") {
                    con = $(challengesBP.grDevOpen).clone();
                    if (rec.registrationEndDate) {
                        checkPointDate = app.formatDate2(rec.registrationEndDate);
                    }
                    icoTrack = "ico-track-develop.png";
                    tcoFlag = "tco-flag-develop.png";
                }

                con.addClass('track-' + trackName);
                con.addClass('type-' + rec.challengeCommunity);

                if (rec.challengeName.length < 61) {
                    $('.contestName', con).html('<img alt="" class="allContestIco" src="' + stylesheet_dir + '/i/' + icoTrack + '" />' + rec.challengeName + '<img alt="" class="allContestTCOIco" src="' + stylesheet_dir + '/i/' + tcoFlag + '" />');
                } else {
                    $('.contestName', con).html('<img alt="" class="allContestIco" src="' + stylesheet_dir + '/i/' + icoTrack + '" />' + rec.challengeName.substr(0, 61) + '...' + '<img alt="" class="allContestTCOIco" src="' + stylesheet_dir + '/i/' + tcoFlag + '" />');
                }
                $('.contestName', con).parents(".inTCO").addClass("hasTCOIco");
                $('.colCh a, .cgCh a', con).attr("href", contestLinkUrl);

                $('.tipC', con).html(rec.challengeType);
				$('.tipC', con).data('contest_type', rec.challengeCommunity);

                $('.vStartDate', con).html(startDate);

                if (checkPointDate) {
                    $('.vEndRound', con).html(checkPointDate);
                } else {
                    $('.vEndRound', con).parent().empty();
                }

                if (endDate) {
                  $('.vEndDate', con).html(endDate);
                } else {
                  $('.vEndDate', con).parent().empty();
                }

                $('.vPhase', con).html(rec.currentPhaseName);

                if (contest_type == "develop" && !app.isEmptyArray(rec.technologies)) {
                  var $div = $('<div>');
                  $div.prop("id", rec.challengeId).addClass("technologyTags");
                  var $ul =$('<ul>');
                  $.each(rec.technologies, function(_, sp){
                    $ul.append('<li><span>' + sp + '</span></li>');
                  });

                  $div.append($ul);
                  $div.append('<div class="clear"></div>');
                  $('.cgTime', con).after($div);
                }

                $('.cgTLeft', con).html('<i></i>' + remainingTime);
                if (rec.isEnding === "true") {
                    $('.cgTLeft', con).addClass('imp');
                }
                $('.cgPur', con).html('<i></i> $' + purse);
                $('.cgReg', con).html('<i></i>' + '<a href="' + contestLinkUrl + '#viewRegistrant">' + rec.numRegistrants + '</a>');
                $('.cgSub', con).html('<i></i>' + rec.numSubmissions);

                $('.cgTLeft', con).qtip({
                    content: {
                        text: remainingTime,
                        title: 'Time Left'
                    },
                    style: {
                        classes: 'qtip-' + rec.challengeCommunity + ' qtip-rounded qtip-shadow'
                    },
                    position: {
                        my: 'bottom center',
                        at: 'top center '
                    }
                });
                $('.cgPur', con).qtip({
                    content: {
                        text: '$' + purse,
                        title: 'Prize Purse'
                    },
                    style: {
                        classes: 'qtip-' + rec.challengeCommunity + ' qtip-rounded qtip-shadow'
                    },
                    position: {
                        my: 'bottom center',
                        at: 'top center '
                    }
                });
                $('.cgReg', con).qtip({
                    content: {
                        text: rec.numRegistrants || '0',
                        title: 'Registrants'
                    },
                    style: {
                        classes: 'qtip-' + rec.challengeCommunity + ' qtip-rounded qtip-shadow'
                    },
                    position: {
                        my: 'bottom center',
                        at: 'top center '
                    }
                });
                $('.cgSub', con).qtip({
                    content: {
                        text: rec.numSubmissions || '0',
                        title: 'Submissions'
                    },
                    style: {
                        classes: 'qtip-' + rec.challengeCommunity + ' qtip-rounded qtip-shadow'
                    },
                    position: {
                        my: 'bottom center',
                        at: 'top center '
                    }
                });


                gridEl.append(con);
                window.setTimeout(function() {
                    window.setTimeout(function() {
                        $('.loading').hide();
                    }, 2000);
                }, 5);
            });
        } else {
            app.addEmptyResult(gridEl, 'active');
        }
    },

    /* table draw function */
    getDesignPastContestTable: function(table, data, records2Disp, isAppend) {
        if (isAppend != true) {
            $('tbody', table).html(null);
        }
								$('thead', table).show();
        var count = 0;
        //JS uncaught typeError when no data available, so adding defined check
        if (typeof data.data !== 'undefined' && data.data.length > 0) {
            $.each(data.data, function(key, rec) {

                var row = $(challengesBP.tabPC).clone();

                var trackName = app.getTrackSymbol(rec.challengeType);
                var startDate = app.formatDate2(rec.postingDate);

                var checkPointDate;
                if (rec.checkpointSubmissionEndDate) {
                checkPointDate = app.formatDate2(rec.checkpointSubmissionEndDate);
                }

                var endDate;
                if (rec.submissionEndDate) {
                  endDate = app.formatDate2(rec.submissionEndDate);
                }

                var contestLinkUrl = app.getContestLinkUrl(rec.challengeId, rec.challengeCommunity);
                var purse = 0;
                for (var i = 0; i < rec.prize.length; i++)
                    purse += rec.prize[i];


                row.addClass('track-' + trackName);

                var icoTrack = "ico-track-design.png";
                var tcoFlag = "tco-flag-design.png";
                if (rec.challengeCommunity == "develop") {
                    icoTrack = "ico-track-develop.png";
                    tcoFlag = "tco-flag-develop.png";
                }

                $('.contestName', row).html('<img alt="" class="allContestIco" src="' + stylesheet_dir + '/i/' + icoTrack + '" />' + rec.challengeName + '<img alt="" class="allContestTCOIco" src="' + stylesheet_dir + '/i/' + tcoFlag + '" />');
                $('.contestName', row).parents(".inTCO").addClass("hasTCOIco");
                $('.colCh a, .cgCh a', row).attr("href", contestLinkUrl);

                if (contest_type == "develop" && !app.isEmptyArray(rec.technologies)) {
                  var $div = $('<div>');
                  $div.prop("id", rec.challengeId).addClass("technologyTags");
                  var $ul =$('<ul>')
                  $.each(rec.technologies, function(_, sp){
                    $ul.append('<li><span>' + sp + '</span></li>');
                  });

                  $div.append($ul);
                  $('.colCh', row).append($div);
                }

                $('.colType .tipC', row).html(rec.challengeType);
				$('.colType .tipC', row).data('contest_type', rec.challengeCommunity);

                $('.vStartDate', row).html(startDate);

                if (checkPointDate) {
                  $('.vEndRound', row).html(checkPointDate);
                } else {
                  $('.vEndRound', row).parent().empty();
                  $('.colTime', row).append('<div class="row">&nbsp;</div>');
                }

                if (endDate) {
                  $('.vEndDate', row).html(endDate);
                } else {
                  $('.vEndDate', row).parent().empty();
                }

                $('.colPur', row).html("$" + purse);

                $('.colPhase', row).html('Completed');

                $('.winBages', row).html('<a href="' + siteurl+ '/challenge-details/' +rec.challengeId+'?type='+ rec.challengeCommunity +'#winner">View Winners</a>');

                $('.moreWin', row).hide();

                $('.colReg', row).html('<a href="' + contestLinkUrl + '#viewRegistrant">' + rec.numRegistrants + '</a>');

                $('.coleSub .subs', row).prepend(rec.numSubmissions);
                $('.coleSub .tipC', row).html(rec.numSubmissions + ' submissions<br/>');

                if (rec.isPrivate == "true") {
                    $('.colAccessLevel', row).removeClass('public').addClass('private');
                }

                $('tbody', table).append(row);
            });
            app.initZebra(table);
        } else {
            app.addEmptyResult(table, 'past');
        }

        $('.loading').hide();
    },
    getDesignUpcomingContestTable: function(table, data, records2Disp, isAppend) {
        if (isAppend != true) {
            $('tbody', table).html(null);
        }
        var count = 0;
        //JS uncaught typeError when no data available, so adding defined check
        if (typeof data.data !== 'undefined' && data.data.length > 0) {
            $.each(data.data, function(key, rec) {

                var row = $(challengesBP.gdUpcoming).clone();

                var trackName = app.getTrackSymbol(rec.challengeType);
                var startDate = app.formatDate2(rec.postingDate);
                var checkPointDate;
                if (rec.checkpointSubmissionEndDate) {
                    checkPointDate = app.formatDate2(rec.checkpointSubmissionEndDate);
                }

                var endDate;
                if (rec.submissionEndDate) {
                  endDate = app.formatDate2(rec.submissionEndDate);
                }

                var contestDuration = app.getContestDuration(rec.postingDate, rec.submissionEndDate);
                var contestTechnologies = rec.technologies.join(', ');
                if (!contestTechnologies) {
                    contestTechnologies = "N/A";
                }
                var contestLinkUrl = app.getContestLinkUrl(rec.challengeId, rec.challengeCommunity);

                var purse = 0;
                for (var i = 0; i < rec.prize.length; i++)
                    purse += rec.prize[i];

                var icoTrack = "ico-track-design.png";
                var tcoFlag = "tco-flag-design.png";
                if (rec.challengeCommunity == "develop") {
                    row = $(challengesBP.gdDevUpcoming).clone();
                    if (rec.registrationEndDate) {
                        checkPointDate = app.formatDate2(rec.registrationEndDate);
                    }
                    icoTrack = "ico-track-develop.png";
                    tcoFlag = "tco-flag-develop.png";
                }

                row.addClass('track-' + trackName);

                $('.contestName', row).html('<img alt="" class="allContestIco" src="' + stylesheet_dir + '/i/' + icoTrack + '" />' + rec.challengeName + '<img alt="" class="allContestTCOIco" src="' + stylesheet_dir + '/i/' + tcoFlag + '" />');
                $('.contestName', row).parents(".inTCO").addClass("hasTCOIco");
                $('.colCh a, .cgCh a', row).attr("href", contestLinkUrl);

                $('.tipC', row).html(rec.challengeType);
				$('.tipC', row).data('contest_type', rec.challengeCommunity);

                $('.vStartDate', row).html(startDate);

                if (checkPointDate) {
                    $('.vEndRound', row).html(checkPointDate);
                } else {
                    $('.vEndRound', row).parent().empty();
                }

                if (endDate) {
                  $('.vEndDate', row).html(endDate);
                } else {
                  $('.vEndDate', row).parent().empty();
                }

                $('.colDur', row).html(contestDuration);

                $('.colTech', row).html(contestTechnologies);

                if (rec.isEnding === "true") {
                    $('.colTLeft', row).addClass('imp');
                }

                $('.colPur', row).html("$" + app.formatCur(purse));

                $('.colStat', row).html(rec.currentStatus);


                $('tbody', table).append(row);
            });
            app.initZebra(table);
        } else {
            app.addEmptyResult(table, 'upcoming');
            $('.loading').hide();
        }
    },
    getDesignUpcomingContestGrid: function(gridEl, data, records2Disp) {
        gridEl.html(null);

        var count = 0;
        //JS uncaught typeError when no data available, so adding defined check
        if (typeof data.data !== 'undefined' && data.data.length > 0) {
            $.each(data.data, function(key, rec) {

                var con = $(challengesBP.grDUpcoming).clone();

                var trackName = app.getTrackSymbol(rec.challengeType);
                trackName += " trackSD";

                var startDate = app.formatDate2(rec.postingDate);
                var checkPointDate = app.formatDate2(rec.checkpointSubmissionEndDate);
                if (rec.checkpointSubmissionEndDate) {
                    checkPointDate = app.formatDate2(rec.checkpointSubmissionEndDate);
                }

                var endDate;
                if (rec.submissionEndDate) {
                  endDate = app.formatDate2(rec.submissionEndDate);
                }

                var contestDuration = app.getContestDuration(rec.postingDate, rec.submissionEndDate);
                var contestTechnologies = rec.technologies.join(', ');
                var contestLinkUrl = app.getContestLinkUrl(rec.challengeId, rec.challengeCommunity);

                var purse = 0;
                for (var i = 0; i < rec.prize.length; i++)
                    purse += rec.prize[i];

                con.addClass('track-' + trackName);
                con.addClass('type-' + rec.challengeCommunity);
                var icoTrack = "ico-track-design.png";
                var tcoFlag = "tco-flag-design.png";
                if (rec.challengeCommunity == "develop") {
                    con = $(challengesBP.grDevUpcoming).clone();
                    if (rec.registrationEndDate) {
                        checkPointDate = app.formatDate2(rec.registrationEndDate);
                    }
                    icoTrack = "ico-track-develop.png";
                    tcoFlag = "tco-flag-develop.png";
                }

                if (rec.challengeName.length < 61) {
                    $('.contestName', con).html('<img alt="" class="allContestIco" src="' + stylesheet_dir + '/i/' + icoTrack + '" />' + rec.challengeName + '<img alt="" class="allContestTCOIco" src="' + stylesheet_dir + '/i/' + tcoFlag + '" />');
                } else {
                    $('.contestName', con).html('<img alt="" class="allContestIco" src="' + stylesheet_dir + '/i/' + icoTrack + '" />' + rec.challengeName.substr(0, 61) + '...' + '<img alt="" class="allContestTCOIco" src="' + stylesheet_dir + '/i/' + tcoFlag + '" />');
                }
                $('.contestName', con).parents(".inTCO").addClass("hasTCOIco");
                $('.colCh a, .cgCh a', con).attr("href", contestLinkUrl);

                $('.tipC', con).html(rec.challengeType);
				$('.tipC', con).data('contest_type', rec.challengeCommunity);

                $('.vStartDate', con).html(startDate);

                if (checkPointDate) {
                    $('.vEndRound', con).html(checkPointDate);
                } else {
                    $('.vEndRound', con).parent().empty();
                }

                $('.vEndDate', con).html(endDate);
                $('.vStatus', con).html(rec.currentStatus);

                if (!contestTechnologies) {
                    contestTechnologies = "N/A";
                }
                $('.vTech', con).html(contestTechnologies);

                $('.cgTLeft', con).html('<i></i>' + contestDuration);
                if (rec.isEnding === "true") {
                    $('.cgTLeft', con).addClass('imp');
                }
                $('.cgPur', con).html('<i></i> $' + purse);

                $('.cgTLeft', con).qtip({
                    content: {
                        text: contestDuration + " days",
                        title: 'Duration'
                    },
                    style: {
                        classes: 'qtip-' + rec.challengeCommunity + ' qtip-rounded qtip-shadow'
                    },
                    position: {
                        my: 'bottom center',
                        at: 'top center '
                    }
                });
                $('.cgPur', con).qtip({
                    content: {
                        text: '$' + purse,
                        title: 'Prize Purse'
                    },
                    style: {
                        classes: 'qtip-' + rec.challengeCommunity + ' qtip-rounded qtip-shadow'
                    },
                    position: {
                        my: 'bottom center',
                        at: 'top center '
                    }
                });


                gridEl.append(con);
                window.setTimeout(function() {
                    window.setTimeout(function() {
                        $('.loading').hide();
                    }, 2000);
                }, 5);
            });
        } else {
            app.addEmptyResult(gridEl, 'upcoming');
        }
    },
    /* table draw function */
    getBugraceTable: function(table, data, records2Disp, isAppend) {

        if (isAppend != true) {
            $('tbody', table).html(null);
        }
								$('thead', table).show();
        var count = 0;
        //JS uncaught typeError when no data available, so adding defined check
        if (typeof data.data !== 'undefined' && data.data.length > 0) {
            $.each(data.data, function(key, rec) {

                var row = $(challengesBP.tabF2F).clone();
                var startDate = app.formatDate2(rec.postingDate);
                var trackName = app.getTrackSymbol(rec.challengeType);
                var purse = 0;
                for (var i = 0; i < rec.prize.length; i++)
                    purse += rec.prize[i];
                row.addClass('track-' + trackName);
                /*
                 * generate table row for design past contest type
                 */
                $('.contestName', row).html('<img alt="" class="allContestIco" src="' + stylesheet_dir + '/i/ico-track-develop.png" />' + rec.challengeName + '<img alt="" class="allContestTCOIco" src="' + stylesheet_dir + '/i/tco-flag-develop.png" />');
                $('.contestName', row).parents(".inTCO").addClass("hasTCOIco");
                $('.tipC', row).html(rec.challengeType);
				$('.tipC', row).data('contest_type', rec.challengeCommunity);
                $('.colPay', row).html("$" + app.formatCur(purse));
                $('.colTP', row).html(20);
                $('.colReg', row).html('<a href="javascript:;">' + rec.numRegistrants + '</a>');
                $('.colAS', row).html(startDate);

                $('tbody', table).append(row);
            });
            app.initZebra(table);
        } else {
            app.addEmptyResult(table, 'active');
        }

        $('.loading').hide();
    },

    // check if array is empty
    isEmptyArray: function(arr) {
      if (typeof arr !== 'undefined' && arr != null && arr.length > 0) {
        if (arr.length == 1 && $.trim(arr[0]).length == 0) {
            return true;
        }
        return false;
      }
      return true;
    },

    //format currency
    formatCur: function(cu) {
        return cu.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    },

    //format date
    formatDate: function(date) {
        return date.replace(/ /g, '&nbsp;').replace(/[.]/g, '/');
    },

    formatDate2: function(date) {
        //some function is passing in undefined timezone_string variable causing js errors, so check if undefined and set default:
        if (typeof timezone_string === 'undefined') {
        var timezone_string = "America/Toronto"; // lets set to TC timezone
        }
        return moment(date).tz(timezone_string).format("D MMM YYYY HH:mm z");
        // var d = new Date(date);
        // var utcd = Date.UTC(d.getFullYear(), d.getMonth(), d.getDate(), d.getHours(), d.getMinutes(), d.getSeconds(), d.getMilliseconds());

        // // obtain local UTC offset and convert to msec
        // localOffset = d.getTimezoneOffset() * 60000;
        // var newdate = new Date(utcd + localOffset);

        // return newdate.toDateString() + ' ' + ((newdate.getUTCHours() < 10 ? '0' : '') + newdate.getUTCHours()) + ':' + ((newdate.getUTCMinutes() < 10 ? '0' : '') + newdate.getUTCMinutes());
    },

    getContestDuration: function(dateStart, dateEnd) {
      var start = moment(dateStart.slice(0, -5));
      var end = moment(dateEnd.slice(0, -5));
      var days = end.diff(start, 'days');
      return days;
    },

    //format date review
    formatDateReview: function(date) {
        if (date == "") return "";
        var timezone = "EST";
        var temp = new Date(date);
        var month = temp.getMonth() + 1;
        month = month < 10 ? "0" + month : month;
        var day = temp.getDate() < 10 ? "0" + temp.getDate() : temp.getDate();
        var year = temp.getFullYear();
        var hour = temp.getHours() < 10 ? "0" + temp.getHours() : temp.getHours();
        var minutes = temp.getMinutes() < 10 ? "0" + temp.getMinutes() : temp.getMinutes();
        return month + "." + day + "." + year + " " + hour + ":" + minutes + " " + timezone;
    },

    /**
     * Format a date for the API
     *
     * @param date Date
     * @return string
     */
    formatDateApi: function(date) {
        return date.getFullYear() + "-" +
            date.getMonth() + "-" +
            date.getDate();
    },

    //format time left
    formatTimeLeft: function(seconds, grid) {
        var sep = (grid) ? '' : ' ';
        if (seconds < 0) {
          return '<span style="font-size:14px;">0' + sep + '<span style="font-size:10px;">Days</span> 0' + sep + '<span style="font-size:10px;">Hrs</span>';
        }

        var numdays = Math.floor(seconds / 86400);
        var numhours = Math.floor((seconds % 86400) / 3600);
        var numminutes = Math.floor(((seconds % 86400) % 3600) / 60);
        var numseconds = ((seconds % 86400) % 3600) % 60;
        var style = "";
        if (numdays == 0 && numhours <= 2) {
            style = "color:red";
        }
        if (isNaN(numhours)) {
            return "<em style='font-size:14px;'>not available</em>";
        }
        return "<span style='font-size:14px;" + style + "'>" + (numdays > 0 ? numdays + sep + "<span style='font-size:10px;'>Day" + ((numdays > 1) ? "s" : "") + "</span> " : "") + "" + numhours + sep + "<span style='font-size:10px;'>Hrs</span> " + (numdays == 0 ? numminutes + sep + "<span style='font-size:10px;'>Min</span> " : "") + "</span>";

    },

    //get contest link url
    getContestLinkUrl: function(projectId, contestType) {
        return siteurl + "/challenge-details/" + projectId + "/?type=" + contestType;
    },

    //get contest param from url
    getParameterByName: function(name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
        return results == null ? null : decodeURIComponent(results[1].replace(/\+/g, " "));
    }
};

/**
 */
var challengesBP = {
    tabAll: '<tr class="inTCO">\
                <td class="colCh"><div>\
                        <a href="javascript:;" class="contestName"></a>\
                    </div></td>\
                <td class="colType"><i class="ico"> <span class="tooltipData"> \
                <span class="tipT">Contest Type</span> \
                <span class="tipC">Web Design</span>\
                    </span>\
                </i></td>\
                <td class="colTime"><div>\
                        <div class="row">\
                            <label class="lbl">Start Date</label>\
                            <div class="val vStartDate"></div>\
                        </div>\
                        <div class="row">\
                            <label class="lbl vEndRoundLabel">Round 1 End</label>\
                            <div class="val vEndRound"></div>\
                        </div>\
                        <div class="row">\
                            <label class="lbl">End Date</label>\
                            <div class="val vEndDate"></div>\
                        </div>\
                    </div></td>\
                <td class="colTLeft"></td>\
                <td class="colPur"></td>\
                <td class="colReg"></td>\
                <td class="colSub"></td>\
            </tr>',
    tabAllDev: '<tr class="inTCO">\
                <td class="colCh"><div>\
                        <a href="javascript:;" class="contestName"></a>\
                    </div></td>\
                <td class="colType"><i class="ico"> <span class="tooltipData"> \
                <span class="tipT">Contest Type</span> \
                <span class="tipC">Web Design</span>\
                    </span>\
                </i></td>\
                <td class="colTime"><div>\
                        <div class="row">\
                            <label class="lbl">Start Date</label>\
                            <div class="val vStartDate"></div>\
                        </div>\
                        <div class="row">\
                            <label class="lbl vEndRoundLabel">Register by</label>\
                            <div class="val vEndRound"></div>\
                        </div>\
                        <div class="row">\
                            <label class="lbl">End Date</label>\
                            <div class="val vEndDate"></div>\
                        </div>\
                    </div></td>\
                <td class="colTLeft"></td>\
                <td class="colPur"></td>\
                <td class="colReg"></td>\
                <td class="colSub"></td>\
            </tr>',
    tabAllData: '<tr class="inTCO">\
                <td class="colCh"><div>\
                        <a href="javascript:;" class="contestName"></a>\
                    </div></td>\
                <td class="colType">&nbsp;</td>\
                <td class="colTime">N/A</td>\
                <td class="colTLeft"></td>\
                <td class="colPur">N/A</td>\
                <td class="colReg"></td>\
                <td class="colSub"></td>\
            </tr>',
    gdOpen: '<tr class="inTCO">\
                <td class="colCh"><div>\
                        <a href="javascript:;" class="contestName"></a>\
                    </div></td>\
                <td class="colType"><i class="ico"> <span class="tooltipData"> \
                <span class="tipT">Contest Type</span> \
                <span class="tipC">Web Design</span>\
                    </span>\
                </i></td>\
                <td class="colTime"><div>\
                        <div class="row">\
                            <label class="lbl">Start Date</label>\
                            <div class="val vStartDate"></div>\
                        </div>\
                        <div class="row">\
                            <label class="lbl ">Round 1 End</label>\
                            <div class="val vEndRound"></div>\
                        </div>\
                        <div class="row">\
                            <label class="lbl">End Date</label>\
                            <div class="val vEndDate"></div>\
                        </div>\
                    </div></td>\
                <td class="colTLeft"></td>\
                <td class="colPur"></td>\
                <td class="colPhase"></td>\
                <td class="colReg"></td>\
                <td class="colSub"></td>\
            </tr>',
    gdDevOpen: '<tr class="inTCO">\
                <td class="colCh"><div>\
                        <a href="javascript:;" class="contestName"></a>\
                    </div></td>\
                <td class="colType"><i class="ico"> <span class="tooltipData"> \
                <span class="tipT">Contest Type</span> \
                <span class="tipC">Web Design</span>\
                    </span>\
                </i></td>\
                <td class="colTime"><div>\
                        <div class="row">\
                            <label class="lbl">Start Date</label>\
                            <div class="val vStartDate"></div>\
                        </div>\
                        <div class="row">\
                            <label class="lbl ">Register by</label>\
                            <div class="val vEndRound"></div>\
                        </div>\
                        <div class="row">\
                            <label class="lbl">Submit by</label>\
                            <div class="val vEndDate"></div>\
                        </div>\
                    </div></td>\
                <td class="colTLeft"></td>\
                <td class="colPur"></td>\
                <td class="colPhase"></td>\
                <td class="colReg"></td>\
                <td class="colSub"></td>\
            </tr>',
     gdUpcoming: '<tr class="inTCO">\
                <td class="colCh"><div>\
                        <a href="javascript:;" class="contestName"></a>\
                    </div></td>\
                <td class="colType"><i class="ico"> <span class="tooltipData"> \
                <span class="tipT">Contest Type</span> \
                <span class="tipC">Web Design</span>\
                    </span>\
                </i></td>\
                <td class="colTime"><div>\
                        <div class="row">\
                            <label class="lbl">Start Date</label>\
                            <div class="val vStartDate"></div>\
                        </div>\
                        <div class="row">\
                            <label class="lbl ">Round 1 End</label>\
                            <div class="val vEndRound"></div>\
                        </div>\
                        <div class="row">\
                            <label class="lbl">End Date</label>\
                            <div class="val vEndDate"></div>\
                        </div>\
                    </div></td>\
                <td class="colDur"></td>\
                <td class="colPur"></td>\
                <td class="colTech"></td>\
                <td class="colStat"></td>\
            </tr>',
    gdDevUpcoming: '<tr class="inTCO">\
                <td class="colCh"><div>\
                        <a href="javascript:;" class="contestName"></a>\
                    </div></td>\
                <td class="colType"><i class="ico"> <span class="tooltipData"> \
                <span class="tipT">Contest Type</span> \
                <span class="tipC">Web Design</span>\
                    </span>\
                </i></td>\
                <td class="colTime"><div>\
                        <div class="row">\
                            <label class="lbl">Start Date</label>\
                            <div class="val vStartDate"></div>\
                        </div>\
                        <div class="row">\
                            <label class="lbl ">Register by</label>\
                            <div class="val vEndRound"></div>\
                        </div>\
                        <div class="row">\
                            <label class="lbl">Submit by</label>\
                            <div class="val vEndDate"></div>\
                        </div>\
                    </div></td>\
                <td class="colDur"></td>\
                <td class="colPur"></td>\
                <td class="colTech"></td>\
                <td class="colStat"></td>\
            </tr>',
    grDOpen: '<div class="contest">\
                <div class="cgCh"><a href="javascript:;" class="contestName"></a></div>\
                <div class="cgTime">\
                    <div>\
                        <div class="row">\
                            <label class="lbl">Start Date</label>\
                            <div class="val vStartDate"></div>\
                        </div>\
                        <div class="row">\
                            <label class="lbl">Round 1 End</label>\
                            <div class="val vEndRound"></div>\
                        </div>\
                        <div class="row">\
                            <label class="lbl">End Date</label>\
                            <div class="val vEndDate"></div>\
                        </div>\
                        <div class="row">\
                            <label class="lbl">Current Phase</label>\
                            <div class="val vPhase"></div>\
                        </div>\
                    </div>\
                </div>\
                <div class="genInfo">\
                    <p class="cgTLeft">\
                        <i></i>\
                    </p>\
                    <p class="cgPur">\
                        <i></i>\
                    </p>\
                    <p class="cgReg">\
                        <i></i>\
                    </p>\
                    <p class="cgSub">\
                        <i></i>\
                    </p>\
                </div>\
            <i class="ico trackType"> <span class="tooltipData"><span class="tipT">Contest Type</span><span class="tipC">Web Design</span></span></i></div>',
    grDUpcoming: '<div class="contest">\
                <div class="cgCh"><a href="javascript:;" class="contestName"></a></div>\
                <div class="cgTime">\
                    <div>\
                        <div class="row">\
                            <label class="lbl">Start Date</label>\
                            <div class="val vStartDate"></div>\
                        </div>\
                        <div class="row">\
                            <label class="lbl">Round 1 End</label>\
                            <div class="val vEndRound"></div>\
                        </div>\
                        <div class="row">\
                            <label class="lbl">End Date</label>\
                            <div class="val vEndDate"></div>\
                        </div>\
                        <div class="row">\
                            <label class="lbl">Current Status</label>\
                            <div class="val vStatus"></div>\
                        </div>\
                        <div class="row">\
                            <label class="lbl">Technologies</label>\
                            <div class="val vTech"></div>\
                        </div>\
                    </div>\
                </div>\
                <div class="genInfo gdUpcoming">\
                    <p class="cgTLeft">\
                        <i></i>\
                    </p>\
                    <p class="cgPur">\
                        <i></i>\
                    </p>\
                </div>\
            <i class="ico trackType"> <span class="tooltipData"><span class="tipT">Contest Type</span><span class="tipC">Web Design</span></span></i></div>',
    // grid design open
    grDataAll: '<div class="contest">\
                <div class="cgCh"><a href="javascript:;" class="contestName"></a></div>\
                <div class="cgTime">\
                    <div>\
                        <div class="row">\
                            <label class="lbl">Start Date</label>\
                            <div class="val vStartDate"></div>\
                        </div>\
                        <div class="row">\
                            <label class="lbl">&nbsp;</label>\
                            <div class="val vEndRound"></div>\
                        </div>\
                        <div class="row">\
                            <label class="lbl">&nbsp;</label>\
                            <div class="val vEndDate"></div>\
                        </div>\
                        <div class="row">\
                            <label class="lbl">&nbsp;</label>\
                            <div class="val vPhase"></div>\
                        </div>\
                    </div>\
                </div>\
                <div class="genInfo">\
                    <p class="cgTLeft"></p>\
                    <p class="cgPur"></p>\
                    <p class="cgReg">\
                        <i></i>\
                    </p>\
                    <p class="cgSub">\
                        <i></i>\
                    </p>\
                </div>',
    // grid develop open
    grDevOpen: '<div class="contest">\
                <div class="cgCh"><a href="javascript:;" class="contestName"></a></div>\
                <div class="cgTime">\
                    <div>\
                        <div class="row">\
                            <label class="lbl">Start Date</label>\
                            <div class="val vStartDate"></div>\
                        </div>\
                        <div class="row">\
                            <label class="lbl">Register by</label>\
                            <div class="val vEndRound"></div>\
                        </div>\
                        <div class="row">\
                            <label class="lbl">Submit by</label>\
                            <div class="val vEndDate"></div>\
                        </div>\
                        <div class="row">\
                            <label class="lbl">Current Phase</label>\
                            <div class="val vPhase"></div>\
                        </div>\
                    </div>\
                </div>\
                <div class="genInfo">\
                    <p class="cgTLeft">\
                        <i></i>\
                    </p>\
                    <p class="cgPur">\
                        <i></i>\
                    </p>\
                    <p class="cgReg">\
                        <i></i>\
                    </p>\
                    <p class="cgSub">\
                        <i></i>\
                    </p>\
                </div>\
            <i class="ico trackType"> <span class="tooltipData"><span class="tipT">Contest Type</span><span class="tipC">Web Design</span></span></i></div>',
    //upcoming challenges grid
    grDevUpcoming: '<div class="contest">\
                <div class="cgCh"><a href="javascript:;" class="contestName"></a></div>\
                <div class="cgTime">\
                    <div>\
                        <div class="row">\
                            <label class="lbl">Start Date</label>\
                            <div class="val vStartDate"></div>\
                        </div>\
                        <div class="row">\
                            <label class="lbl">Register by</label>\
                            <div class="val vEndRound"></div>\
                        </div>\
                        <div class="row">\
                            <label class="lbl">Submit by</label>\
                            <div class="val vEndDate"></div>\
                        </div>\
                        <div class="row">\
                            <label class="lbl">Current Status</label>\
                            <div class="val vStatus"></div>\
                        </div>\
                        <div class="row">\
                            <label class="lbl">Technologies</label>\
                            <div class="val vTech"></div>\
                        </div>\
                    </div>\
                </div>\
                <div class="genInfo gdUpcoming">\
                    <p class="cgTLeft">\
                        <i></i>\
                    </p>\
                    <p class="cgPur">\
                        <i></i>\
                    </p>\
                </div>\
            <i class="ico trackType"> <span class="tooltipData"><span class="tipT">Contest Type</span><span class="tipC">Web Design</span></span></i></div>',
    /* table past challenges */
    tabPC: '<tr class="inTCO">\
                <td class="colCh"><div>\
                        <a href="javascript:;" class="contestName"></a>\
                    </div></td>\
                <td class="colType w"><i class="ico"> <span class="tooltipData"> <span class="tipT">Contest Type</span> <span class="tipC">Web Design</span>\
                    </span></i></td>\
                <td class="colTime"><div>\
                        <div class="row">\
                            <label class="lbl">Start Date</label>\
                            <div class="val vStartDate"></div>\
                        </div>\
                        <div class="row">\
                            <label class="lbl">End Date</label>\
                            <div class="val vEndDate"></div>\
                        </div>\
                        <div class="row">\
                            <label class="lbl ">Review End</label>\
                            <div class="val vEndRound"></div>\
                        </div>\
                    </div></td>\
                <td class="colPur"></td>\
                <td class="colPhase"></td>\
                <td class="colReg"></td>\
                <td class="coleSub"><span class="uline subs"> <span class="tooltipData"> <span class="tipT">Submissions</span> <span class="tipC">5 out of 5 submissions<br/> passed screening</span>\
                    </span></span></td>\
                <td class="colStandings">\
                    <div class="winBages">\
                        <div class="winner place-1">\
                            <div class="tooltipData">\
                                <span class="tipT">1st Place</span>\
                                <div class="tipC">\
                                    <img alt="Storyboard" class="imgSB" src="i/storyboard.png" />\
                                    <div class="handleWrap">\
                                        <a href="#">Handlename</a>\
                                    </div>\
                                    <footer>\
                                        <a href="#">View Details</a>\
                                    </footer>\
                                </div>\
                            </div>\
                        </div>\
                        <div class="winner place-2">\
                            <div class="tooltipData">\
                                <span class="tipT">2nd Place</span>\
                                <div class="tipC">\
                                    <img alt="Storyboard" class="imgSB" src="i/storyboard.png" />\
                                    <div class="handleWrap">\
                                        <a href="#">Handlename</a>\
                                    </div>\
                                    <footer>\
                                        <a href="#">View Details</a>\
                                    </footer>\
                                </div>\
                            </div>\
                        </div>\
                        <div class="winner place-3">\
                            <div class="tooltipData">\
                                <span class="tipT">3rd Place</span>\
                                <div class="tipC">\
                                    <img alt="Storyboard" class="imgSB" src="i/storyboard.png" />\
                                    <div class="handleWrap">\
                                        <a href="#">Handlename</a>\
                                    </div>\
                                    <footer>\
                                        <a href="#">View Details</a>\
                                    </footer>\
                                </div>\
                            </div>\
                        </div>\
                    </div> <!-- /.winBages -->\
                    <p class="moreWin">\
                        <a href="#">More Winners</a>\
                    </p>\
                </td>\
                <td class="colAccessLevel public"><i></i></td>\
            </tr>',
    /* reivew table */
    tabReivew: '<tr class="inTCO">\
            <td class="colCh"><div>\
                    <a href="javascript:;" class="contestName"></a>\
                </div></td>\
            <td class="colType"></td>\
            <td class="colR1start"></td>\
            <td class="colR2start"></td>\
            <td class="colPay"></td>\
            <td class="colStatus"></td>\
        </tr>',
    /* reivew table */
    tabDevReivew: '<tr class="inTCO">\
            <td class="colCh nonTCO"><div>\
                    <a href="javascript:;" class="contestName"></a>\
                </div></td>\
            <td class="colRPay"></td>\
            <td class="colSub"></td>\
            <td class="colRstart"></td>\
            <td class="colOPos"></td>\
            <td class="colStatus"></td>\
        </tr>',
    /* first 2 finish table */
    tabF2F: '<tr class="inTCO">\
            <td class="colCh"><div>\
                    <a href="javascript:;" class="contestName"></a>\
                </div></td>\
            <td class="colType w"><i class="ico"> <span class="tooltipData"> <span class="tipT">Contest Type</span> <span class="tipC">Web Design</span>\
                    </span></i></td>\
            <td class="colPay"></td>\
            <td class="colTP"></td>\
            <td class="colReg"></td>\
            <td class="colAS"></td>\
        </tr>',
    /* data table */
    tabData: '<tr class="inTCO">\
                <td class="colCh"><div>\
                        <a href="javascript:;" class="contestName"></a>\
                    </div></td>\
                <td class="colType"></td>\
                <td class="colTime"><div>\
                        <div class="row">\
                            <label class="lbl">Start Date</label>\
                            <div class="val vStartDate"></div>\
                        </div>\
                        <div class="row">\
                            <label class="lbl">End Date</label>\
                            <div class="val vEndDate"></div>\
                        </div>\
                    </div></td>\
                <td class="colTLeft"></td>\
                <td class="colPur">N/A</td>\
                <td class="colPhase">N/A</td>\
                <td class="colReg"></td>\
                <td class="colSub"></td>\
            </tr>'
};


//string insertion at idx index
String.prototype.splice = function(idx, rem, s) {
    return (this.slice(0, idx) + s + this.slice(idx + Math.abs(rem)));
};

// extending base prototype
$.extend(app, appChallenges);


$.getJSON = function(url, success) {

    return $.ajax({
        dataType: "json",
        url: url,
        success: success,
        cache: false
    });
};

// everythings begins from here
$(document).ready(function() {
    $("#challengeNav a").hide();

});


/* fancy drop down platform on advanced search form */
$(document).ready(function() {

    /*multiple select configurations
    var config = {
        '.chosen-select': {},
        '.chosen-select-deselect': { allow_single_deselect: true },
        '.chosen-select-no-single': { disable_search_threshold: 10 },
        '.chosen-select-no-results': { no_results_text: 'Oops, nothing found!' },
        '.chosen-select-width': { width: "95%" }
    };
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }*/

    //set equal height to row contestGrid boxes
    var index = 0, minWidth = 1019, cols = $(window).width() > minWidth ? 3 : 1, rows = 0;
    $(".contestGrid .contest").each(function() {
        rows = Math.floor(index / cols) + 1;
        $(this).addClass("row" + Math.floor(index / cols));
        index++;
    });
    $('.tabviews a').off().on(ev, function(e) {
        if ($(this).hasClass('isActive')) {
            return false;
        }
        $('.viewTab').hide();
        id = $(this).attr('href');
        $(id).fadeIn('fast');
        $('.isActive', $(this).parent()).removeClass('isActive');
        $(this).addClass('isActive');

        if ($(this).hasClass('gridView') && $(window).width() > minWidth) {
            for (var i = 0; i < rows; i++) {
                var maxHeight = Math.max.apply(null, $(".contestGrid .contest.row" + i).map(function() {
                    return $(this).height();
                }).get());
                $(".contestGrid .contest.row" + i).height(maxHeight);
            }
        }
    });
	
	// Issue ID: I-111387 - Add date input masking to both startDate and endDate inputs
	$('input[type=text].datepicker', '.otherOpts').inputmask('yyyy-mm-dd');
});



var ev = 'click';
if ($.support.touch) {
  ev = 'tap'
}
var ie7 = false;

var ajax = {
  data: '',
  postPerPage: '100' // temp setting until we have paging on the challenges page.
};

var xhr = "";

var bannerSlider;
var bannerSliderClone;

function createBannerSlider() {
  bannerSliderClone = $('#banner .slider').clone();
  bannerSlider = $('#banner .slider').bxSlider({
    minSlides: 1,
    maxSlides: 1,
    controls: false,
    auto: true,
    pause: 5000,
    infiniteLoop: true,
    useCSS: false

  });
}


// application functions
var app = {
  init: function() {
    if (navigator.userAgent.indexOf('MSIE 7.0') >= 0) {
      $('body').addClass('ie7');
      ie7 = true;
    }

    createBannerSlider();

    $(window).resize(function() {
      if (bannerSlider.destroySlider) {
        bannerSlider.destroySlider();
      }
      $('#banner .slider').replaceWith(bannerSliderClone);
      createBannerSlider();
    });

    // new promo module banner slider
    $('#promo-banner .slider').bxSlider({
      minSlides: 1,
      maxSlides: 1,
      controls: false,
      responsive: true,
      auto: true,
      pause: 5000,
      slideMargin: 2,
      adaptiveHeight: true
    });


    $('.dataTable.challenges tbody, .layChallenges .dataTable tbody').html(null);
    $('#gridView .contestGrid').html(null);
    // challenges list init
    if ($('.layChallenges').length > 0) {
      //app.challenges.init();
    }
    if ($('.community').length > 0) {
      //app.community.init();
    }
    //Overview page init
    if ($('.overviewPage').length > 0) {
      app.overview.init();
    }

    //Case page init
    if ($('.casePage').length > 0) {
      app.casestudy.init();
    }

    //Resource init
    if ($('.resourceList').length > 0) {
      app.resource.init();
    }

    //Story init
    if ($('.storyPage').length > 0) {
      app.story.init();
    }

    if ($('#whatsHappening .slider').length > 0) {

      $('#whatsHappening .slider').each(function() {
        $('ul', $(this)).bxSlider({
          minSlides: 1,
          maxSlides: 1,
          responsive: !ie7,
          controls: false
        });
      })
    }

    app.setPlaceholder($('.connected .email'));


    $('body').on(ev, function() {
      $('.btnMyAcc').removeClass('isActive');
      $('.userWidget:visible').hide();
    });

    $('.userWidget').on(ev, function(e) {
      e.stopPropagation();
    });								
  },
  // event bindings
  initEvents: function() {

    /* post email data */
    $('#footer #emailForm .btn').on(ev, function() {
      var emailAddress = $('#footer #emailForm input[name=EMAIL]').val();
      var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
      //alert(emailAddress);
      if (pattern.test(emailAddress)) {
        $('#footer #emailForm').submit();
      }

    });

    $('.btnMyAcc').on(ev, function() {
      var widg = $('.userWidget', $(this).closest('.container'));
      if ($(this).hasClass('isActive')) {
        widg.stop().slideUp();
        $(this).removeClass('isActive');
      } else {
        widg.stop().slideDown();
        $(this).addClass('isActive');
      }

      return false;

    });

    // register demo
    $('.dataTable').on(ev, '.btnAlt', function() {
      $(this).replaceWith('<a href="javascript:;" class="btn">Submit</a>');
    });

    // login
    $('.actionLogout').on(ev, function() {
      $('#navigation, .sidebarNav').addClass('newUser')
      $('#navigation .userWidget').hide();
      $('#navigation .isActive').removeClass('isActive');
      $('.btnRegWrap').show();
      $('.btnAccWrap').hide();
    });
    $('.actionLogin_Del').on(ev, function() {
      $('#navigation, .sidebarNav').removeClass('newUser');
      $('.btnRegWrap').hide();
      $('.btnAccWrap').show();
    });


    $('.sidebarNav a i').on(ev, function() {
      var root = $(this).closest('.root');
      if ($(this).closest('li').hasClass('isActive')) {
        $(this).closest('li').removeClass('isActive');
      } else {
        $(this).closest('li').addClass('isActive');
      }
      return false;
    });

    // main Nav
    $('#mainNav').on(ev, function() {
      $('.sidebarNav').css('opacity', 1);
      $('.content, #navigation').toggleClass('moving');
      window.setTimeout(function() {
        $('body').toggleClass('stop-scrolling');
      }, 1);
    });
    $('#mainNav .root').on(ev, function(e) {
      e.stopPropagation();
    });

    $('#mainNav .root > li').mouseenter(function() {
      $('.child', $(this)).stop().slideDown('fast');
    });
    $('#mainNav .root > li').mouseleave(function() {
      $('.child', $(this)).stop().slideUp('fast');
    });

    // footer navigation
    $(' #footer .rootNode > a').on(ev, function() {
      if (!($('.onMobi.linkLogout').is(':visible') || $('.onMobi.linkLogin').is(':visible'))) {
        return false;
      }
      var ul = $('ul', $(this).closest('.rootNode'));
      ul.toggleClass('show');
      return false;
    });

    // tab navs
    $('.tabNav a').on(ev, function() {
      var id = $(this).attr('href');
      $('.tab', $(this).closest('.tabsWrap')).hide();
      $(id).fadeIn();
      $('.active', $(this).closest('nav')).removeClass('active');
      $(this).addClass('active');
      return false;
    });
  },

  setLoading: function() {
    if ($('.loading').length <= 0) {
      $('body').append('<div class="loading">Loading...</div>');
    } else {
      $('.loading').show();
    }
  },
  buildRequestData: function(actionType, contestType, contest_track, sortColumn, sortOrder, pageIndex, pageSize) {
    var action = "";
    //	switch contest type
    switch (actionType) {
      case "activeContest":
        action = "get_active_contest";
        break;
      case "pastContest":
        action = "get_past_contest";
        break;
      case "reviewOpportunities":
        action = "get_review_opportunities";
        break;
    }
    if (pageIndex == null || pageIndex == "") {
      pageIndex = 1;
    }
    ajax.data = {
      "action": action,
      "contest_type": contestType,
      "contest_track": contest_track,
      "sortColumn": sortColumn,
      "sortOrder": sortOrder,
      "pageIndex": pageIndex,
      "pageSize": pageSize
    };
  },
  /*
   * community page functions
   * --------------------------------------------------------------
   */
  community: {
    init: function() {
      // list partial challenges table data
      app.community.getAllPartialContests(ajax.postPerPage);

      $('.dataChanges .viewAll').on(ev, function() {
        ajax.data["pageIndex"] = 1;
        app.community.getAllPartialContests(1000);

        $('.rt', $(this).closest('.dataChanges')).hide();
        $(this).parent().hide();
        app.ie7Fix2();
      });

      /* table short */
      $('.dataTable.challenges thead th').click(function() {
        if ($(this).hasClass('disabled')) {
          return false;
        }
        var shortCol = $(this).text().toLowerCase();
        var sortColumn = "";
        shortCol = shortCol.replace(' ', '');
        if (shortCol == "") {
          return false;
        }

        switch (shortCol) {
          case "challenges":
            sortColumn = "challengeName";
            break;
          case "timeline":
            sortColumn = "submissionEndDate";
            break;
          default:
            sortColumn = "challengeName";
        }

        ajax.data["sortColumn"] = sortColumn;
        if ($(this).hasClass('asc')) {
          ajax.data["sortOrder"] = 'desc';
          $(this).removeClass('asc');
        } else {
          ajax.data["sortOrder"] = 'asc';
          $(this).addClass('asc');
        }
        /* build url and requtest data using ajax */
        //if(conType==null || conType==""){
        app.community.getAllPartialContests(ajax.postPerPage);

      });
    },

    getAllPartialContests: function(nRecords) {
      /*
       * get all contests data
       */
      app.getPartialContests(ajaxUrl, $('.challenges'), 2, 'design', false, function() {
        app.getPartialContests(ajaxUrl, $('.challenges'), 2, 'develop', true, function() {
          app.getPartialContests(ajaxUrl, $('.challenges'), 1, 'data-marathon', true, function() {
            app.getPartialContests(ajaxUrl, $('.challenges'), 1, 'data-srm', true);
          });
        });
      });
    }

  },
  /*
   * community design,development,data-marathon page functions
   * --------------------------------------------------------------
   */
  communityLanding: {
    init: function() {
      // list partial challenges table data
      app.communityLanding.getAllPartialContests(ajax.postPerPage);

      $('.dataChanges .viewAll').on(ev, function() {
        ajax.data["pageIndex"] = 1;
        app.communityLanding.getAllPartialContests(100);

        $('.rt', $(this).closest('.dataChanges')).hide();
        $(this).parent().hide();
        app.ie7Fix2();
      });

      /* table short */
      $('.dataTable.challenges thead th').click(function() {
        if ($(this).hasClass('disabled')) {
          return false;
        }
        var shortCol = $(this).text().toLowerCase();
        var sortColumn = "";
        shortCol = shortCol.replace(' ', '');
        if (shortCol == "") {
          return false;
        }

        switch (shortCol) {
          case "challenges":
            sortColumn = "challengeName";
            break;
          case "timeline":
            sortColumn = "submissionEndDate";
            break;
          default:
            sortColumn = "challengeName";
        }

        ajax.data["sortColumn"] = sortColumn;
        if ($(this).hasClass('asc')) {
          ajax.data["sortOrder"] = 'desc';
          $(this).removeClass('asc');
        } else {
          ajax.data["sortOrder"] = 'asc';
          $(this).addClass('asc');
        }
        /* build url and requtest data using ajax */
        //if(conType==null || conType==""){
        app.community.getAllPartialContests(ajax.postPerPage);

      });
    },

    getAllPartialContests: function(nRecords) {
      /*
       * get all contests data
       */
      if (contest_track == "algorithm") {
        app.getPartialContests(ajaxUrl, $('.challenges'), nRecords, 'data-marathon', false, function() {
          app.getPartialContests(ajaxUrl, $('.challenges'), nRecords, 'data-srm', false);
        });
      } else {
        app.getPartialContests(ajaxUrl, $('.challenges'), nRecords, contest_track, false, function() {});
      }
    }

  },
  // get contests tableView & gridView data
  getPartialContests: function(url, table, pageSize, challenge_type, isAppend, callback) {
    if (url == null || url == "") {
      return false;
    }
    ajax.data["contest_type"] = challenge_type;
    ajax.data["pageSize"] = pageSize;
    app.setLoading();
    if (xhr != "") {
      xhr.abort();
    }


    xhr = $.getJSON(url, ajax.data, function(data) {
      app.getPartialContestTable(table, data, pageSize, isAppend);
      if (callback != null && callback != "") {
        callback();
      }
    }).fail(function() { /* add failure handler */
      $('.loading').hide();
      //$('body').append('<div class="errorLoading">Oops... we had trouble loading ' +challenge_type+ ' Challenges.</div>');
      // setTimeout( "jQuery('.errorLoading').fadeOut();",5000 );
    });
  },

  /*
   * challenges page functions
   * --------------------------------------------------------------
   */
  challenges: {
    init: function() {
      // add table and gird data
      var conType = ajax.data["contest_type"];
      if (conType == null || conType == "") {
        app.challenges.getAllContests();
      } else {
        $('.challengeType .active').removeClass('active');
        $('.challengeType .' + conType).addClass('active');

        if (conType == "data") {
          app.getContests(ajaxUrl, $('.dataTable'), 100, 'data-marathon', false, function() {
            app.getContests(ajaxUrl, $('.dataTable'), 100, 'data-srm', true);
          });
        } else {
          app.getContests(ajaxUrl, $('.dataTable'), 100, conType, false);
        }

      }

      /* view all records */
      $('.dataChanges .viewAll').on(ev, function() {
        ajax.data["pageIndex"] = 1;
        app.getContests(ajaxUrl, $('.dataTable'), 1000, ajax.data["contest_type"]);
        $('.rt', $(this).closest('.dataChanges')).hide();
        $(this).parent().hide();
      });

      /* view next */
      $('.dataChanges').on(ev, '.nextLink', function(e) {
        $('.prevLink', $(this).parent()).show();
        var _this = $(e.currentTarget);

        ajax.data["pageIndex"] = ajax.data["pageIndex"] + 1;
        var pageCount = 3; //dummy data as current api do not return number of pages in current track
        if (ajax.data["pageIndex"] >= pageCount) {
          _this.hide();
        }
        var conType = ajax.data.contest_type;
        if (conType == "data" || conType == "data-srm" || conType == "data-marathon") {
          app.getContests(ajaxUrl, $('.dataTable'), ajax.postPerPage, 'data-marathon', false, function() {
            app.getContests(ajaxUrl, $('.dataTable'), ajax.postPerPage, 'data-srm', true);
          });
        } else {
          app.getContests(ajaxUrl, $('.dataTable'), ajax.postPerPage, conType, false);
        }

        return false;
      });
      $('.dataChanges').on(ev, '.prevLink', function(e) {
        $('.nextLink', $(this).parent()).show();
        var _this = $(e.currentTarget);

        ajax.data["pageIndex"] = ajax.data["pageIndex"] - 1;
        if (ajax.data["pageIndex"] <= 1) {
          _this.hide();
          ajax.data["pageIndex"] = 1;
        }
        var conType = ajax.data.contest_type;
        if (conType == "data" || conType == "data-srm" || conType == "data-marathon") {
          app.getContests(ajaxUrl, $('.dataTable'), ajax.postPerPage, 'data-marathon', false, function() {
            app.getContests(ajaxUrl, $('.dataTable'), ajax.postPerPage, 'data-srm', true);
          });
        } else {
          app.getContests(ajaxUrl, $('.dataTable'), ajax.postPerPage, conType, false);
        }

        return false;
      });

      $('.views a').on(ev, function(e) {
        if ($(this).hasClass('isActive')) {
          return false;
        }
        $('.viewTab').hide();
        id = $(this).attr('href');
        $(id).fadeIn('fast');
        $('.isActive', $(this).parent()).removeClass('isActive');
        $(this).addClass('isActive');
        app.ie7Fix();
        return false;
      });

      $('.ddWrap').on(ev, '.val', function() {
        $(this).closest('.ddWrap').toggleClass('on');
      });
      $('.ddWrap').mouseleave(function() {
        $(this).closest('.ddWrap').removeClass('on');
      });
      $('.ddWrap .list li').on(ev, function() {
        var dd = $(this).closest('.ddWrap');
        $('.active', dd).removeClass('active');
        $(this, dd).addClass('active');
        $('.val', dd).html($(this).text() + '<i></i>');
        dd.removeClass('on');
        // app.getContests('data/challenges-2.json', $('.dataTable'), ajax.postPerPage);
      });

      // challengeType
      $('.challengeType a').on(ev, function() {
        if ($(this).hasClass('active'))
          return false;

        var href = $(this).attr('href');
        var url = $('#navigation .logo a').attr('href') + '/' + href + '/challenges';
        if (href == "all") {
          url = $('#navigation .logo a').attr('href') + '/challenges';
        }
        window.location = url;
        return false;
      });

      /* table short */
      $('.layChallenges .dataTable thead th').click(function() {
        if ($(this).hasClass('disabled')) {
          return false;
        }
        var shortCol = $(this).text().toLowerCase();
        shortCol = shortCol.replace(' ', '');
        if (shortCol == "") {
          return false;
        }

        if ($('.challengeType .active').text() == "All") {
          ajax.data["contest_type"] = "";
        }

        //if($('.challengeType .active').length > 0){
        //ajax.data["contest_type"] = $('.challengeType active').text().toLowerCase();
        //}
        ajax.data["sortColumn"] = shortCol;
        if ($(this).hasClass('asc')) {
          ajax.data["sortOrder"] = 'desc';
          $(this).removeClass('asc');
        } else {
          ajax.data["sortOrder"] = 'asc';
          $(this).addClass('asc');
        }
        var conType = ajax.data.contest_type;
        /* build url and requtest data using ajax */
        if (conType == null || conType == "") {
          app.challenges.getAllContests();
        } else {
          $('.challengeType .active').removeClass('active');
          $('.challengeType .' + conType).addClass('active');

          if (conType == "data" || conType == "data-srm" || conType == "data-marathon") {
            app.getContests(ajaxUrl, $('.dataTable'), ajax.postPerPage, 'data-marathon', false, function() {
              app.getContests(ajaxUrl, $('.dataTable'), ajax.postPerPage, 'data-srm', true);
            });
          } else {
            app.getContests(ajaxUrl, $('.dataTable'), ajax.postPerPage, conType, false);
          }
        }
      });
    },

    initTableNGrid: function(callback) {
      var conType = ajax.data["contest_type"];
      if (conType == null || conType == "") {
        app.challenges.getAllContests(callback);
      } else {
        $('.challengeType .active').removeClass('active');
        $('.challengeType .' + conType).addClass('active');

        if (conType == "data") {
          app.getContests(ajaxUrl, $('.dataTable'), ajax.postPerPage, 'data-marathon', false, function() {
            app.getContests(ajaxUrl, $('.dataTable'), ajax.postPerPage, 'data-srm', true, callback);
          });
        } else {
          app.getContests(ajaxUrl, $('.dataTable'), ajax.postPerPage, conType, false, callback);
        }

      }
    },

    getAllContests: function(callback) {
      /*
       * get all contests data
       */
      app.getContests(ajaxUrl, $('.dataTable'), ajax.postPerPage, 'design', false,
        function() {
          app.getContests(ajaxUrl, $('.dataTable'), ajax.postPerPage, 'develop', true, function() {
            app.getContests(ajaxUrl, $('.dataTable'), ajax.postPerPage, 'data-marathon', true, function() {
              app.getContests(ajaxUrl, $('.dataTable'), ajax.postPerPage, 'data-srm', true);
            });
          });
          callback();
        });
    },

    getActiveContestsList: function(callback) {
      /*
       * get all contests data
       */
      app.getContests(ajaxUrl, $('.dataTable'), ajax.postPerPage, 'design', false,
        function() {
          app.getContests(ajaxUrl, $('.dataTable'), ajax.postPerPage, 'develop', true, function() {
            app.getContests(ajaxUrl, $('.dataTable'), ajax.postPerPage, 'data-marathon', true, function() {
              app.getContests(ajaxUrl, $('.dataTable'), ajax.postPerPage, 'data-srm', true);
            });
          });
          callback();
        });
    }


  },
  getTrackSymbol: function(type) {
    var trackName = "o";
    switch (type) {
      case "Web Design":
        trackName = "w";
        break;
      case "Widget or Mobile Screen Design":
        trackName = "wi";
        break;
      case "Logo Design":
        trackName = "l";
        break;
      case "Banners/Icons":
        trackName = "bi";
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
        trackName = "as";
        break;
      case "Conceptualization":
        trackName = "c";
        break;
      case "Marathon":
        trackName = "mm";
        break;

    }
    return trackName;
  },

  /*
   * 0verview page functions
   * --------------------------------------------------------------
   */
  overview: {
    init: function() {

      //Equalize height
      $(".csRealResults .realResult").height("");
      var fw = parseInt($(".csRealResults").width() / 2);
      var w = parseInt($(".realResult").eq(0).width());
      if ((w < fw) && ($(".realResult").length > 0)) {
        //desktop view, need equalize
        $(".csRealResults .grid-3-1:nth-child(2n)").each(function() {
          var prevObj = $(this).prev(".grid-3-1");
          var h1 = parseInt(prevObj.height()) - 42;
          var h2 = parseInt($(this).height()) - 42;
          var newH = Math.max(h1, h2, 0);
          $(".realResult", $(this)).height(newH);
          $(".realResult", prevObj).height(newH);
        });
      }
      $(window).resize(function() {
        $(".csRealResults .realResult").height("");
        var fw = parseInt($(".csRealResults").width() / 2);
        var w = parseInt($(".realResult").eq(0).width());
        if ((w < fw) && ($(".realResult").length > 0)) {
          //desktop view, need equalize
          $(".csRealResults .grid-3-1:nth-child(2n)").each(function() {
            var prevObj = $(this).prev(".grid-3-1");
            var h1 = parseInt(prevObj.height()) - 42;
            var h2 = parseInt($(this).height()) - 42;
            var newH = Math.max(h1, h2, 0);
            $(".realResult", $(this)).height(newH);
            $(".realResult", prevObj).height(newH);
          });
        }
      });
    }

  },
  /*
   * Resources page functions
   * --------------------------------------------------------------
   */
  resource: {
    init: function() {
      $(".jsshowMoreResource").click(function() {
        app.setLoading();
        window.setTimeout(function() {
          $('.loading').hide();
        }, 1000);
        $(this).hide();
        $(".resourceList > ul > li").removeClass("hide");
      });
    }

  },
  /*
   * Story page functions
   * --------------------------------------------------------------
   */
  story: {
    init: function() {
      $(".jsShowMoreArchiveStories").click(function() {
        app.setLoading();
        window.setTimeout(function() {
          $('.loading').hide();
        }, 1000);
        $(this).hide();
        $(".archiveStoriesList > li").removeClass("hide");
      });
    }

  },
  /*
   * Case page functions
   * --------------------------------------------------------------
   */
  casestudy: {
    init: function() {

      $(".jsCloseCaseDetails").click(function() {
        var caseItem = $(this).parents(".caseDetailItem").eq(0);
        if (ie7) {
          $('.btn', caseItem).css({
            "visibility": "hidden"
          });
          $('.container', caseItem).css({
            "position": "static"
          });
        }
        caseItem.hide(0, function() {
          caseItem.hide()
          $(".jsShowCaseDetails").removeClass("isShow");
          $(".caseDetailItem").hide();
          if (ie7) {
            $('.container', caseItem).css({
              "position": "relative"
            });
            $('.btn', caseItem).css({
              "visibility": ""
            });
          }
          var scrollTopValue = $("html").data("scrollTop");
          $('html, body').animate({
            scrollTop: scrollTopValue + "px"
          });
        });
      });

      $(".caseDetailItem").each(function(index) {
        var newObj = $(this).clone("true");
        var caseGrids = $(".casesView .caseGrid");
        caseGrids.eq(index).append(newObj);
      });

      $(".jsShowCaseDetails").click(function() {
        var linkCase = $(this);
        var sameParent = $(this).parents(".group").find(".isShow").length > 0;
        if ($(this).hasClass("isShow")) {
          $(".jsCloseCaseDetails:visible").trigger("click");
        } else {
          if ($(".isShow").length > 0) {
            $(".jsShowCaseDetails").removeClass("isShow");
            $(".caseDetailItem").hide();
          }
          $(".jsShowCaseDetails").removeClass("isShow");
          $(this).addClass("isShow");
          var gridItem = $(this).parent();
          var groupItem = $(this).parents(".group").eq(0);
          var idx = $(".caseGrid", groupItem).index(gridItem);
          var detailsWrapper = groupItem.next(".caseDetailsWrapper");
          var detailItem = $(".caseDetailItem", detailsWrapper).eq(idx);
          if (ie7) {
            $('.btn', detailItem).css({
              "visibility": "hidden"
            });
            $('.container', detailItem).css({
              "position": "relative"
            });
          }
          if (detailsWrapper.is(":visible")) {
            if (sameParent) {
              detailItem.stop().fadeIn(800, function() {
                closeCaseItem(detailItem, linkCase);
              });
            } else {
              detailItem.stop().slideDown(800, function() {
                closeCaseItem(detailItem, linkCase);
              });
            }
          } else {
            if (sameParent) {
              $(".caseDetailItem", gridItem).eq(0).stop().fadeIn(800, function() {
                scrollCaseItem(linkCase);
              });
            } else {
              $(".caseDetailItem", gridItem).eq(0).stop().slideDown(800, function() {
                scrollCaseItem(linkCase);
              });
            }
          }
        }
      });

      function closeCaseItem(detailItem, linkCase) {
        if (ie7) {
          $('.btn', detailItem).css({
            "visibility": ""
          });
        }
        var offset = linkCase.offset();
        var scrollTopValue = $(document).scrollTop();
        $("html").data("scrollTop", scrollTopValue);
        var totalScrollTopValue = offset.top + linkCase.outerHeight() + 14;
        //alert(totalScrollTopValue)
        $('html, body').animate({
          scrollTop: totalScrollTopValue + "px"
        }, 500);
      }

      function scrollCaseItem(linkCase) {
        var offset = linkCase.offset();
        var scrollTopValue = $(document).scrollTop();
        $("html").data("scrollTop", scrollTopValue);
        var totalScrollTopValue = offset.top + linkCase.outerHeight();
        $('html, body').animate({
          scrollTop: totalScrollTopValue + "px"
        }, 500);
      }


      /*	$(".showAllBtn").click(function(){
       app.setLoading();
       window.setTimeout(function(){$('.loading').hide();},1000);
       var btnWrapper =$(this).parents(".dataChanges").eq(0);
       $("> div", btnWrapper ).hide();
       $(".caseGrid, .casesView, .caseDetailsWrapper ").removeClass("hide");
       });
       $('.dataChanges').on(ev, '.nextLink', function(e) {
       $('.prevLink', $(this).parent()).show();
       var _this = $(e.currentTarget);
       var pageNo = _this.attr('href').replace(/#/g, '');
       pageNo = parseInt(pageNo);
       if (pageNo >= 3) {
       _this.hide();
       }
       _this.attr('href', '#' + (pageNo + 1));
       $('.prevLink', $(this).parent()).attr('href', '#' + (pageNo - 1));
       app.setLoading();
       window.setTimeout(function(){$('.loading').hide();},1000);
       return false;
       });*/
      $('.dataChanges').on(ev, '.prevLink', function(e) {
        $('.nextLink', $(this).parent()).show();
        var _this = $(e.currentTarget);
        var pageNo = _this.attr('href').replace(/#/g, '');
        pageNo = parseInt(pageNo);
        if (pageNo <= 1) {
          _this.hide();
        }
        _this.attr('href', '#' + (pageNo - 1));
        $('.nextLink', $(this).parent()).attr('href', '#' + (pageNo + 1));
        app.setLoading();
        window.setTimeout(function() {
          $('.loading').hide();
        }, 1000);
        return false;
      });
    }

  },

  // ie fix
  ie7Fix: function() {
    if (ie7) {
      $('#aboutContent, #footer').hide();
      window.setTimeout(function() {
        $('#aboutContent, #footer').show()
      }, 10);
    }
  },

  // ie fix
  ie7Fix2: function() {
    if (ie7) {
      $('body').hide();
      window.setTimeout(function() {
        $('body').show();
      }, 100);
    }
  },

  formatDate: function(date) {
    return date.replace(/ /g, '&nbsp;').replace(/[.]/g, '/');
  },

  formatDate2: function(date) {
    //some function is passing in undefined timezone_string variable causing js errors, so check if undefined and set default:
    if (typeof timezone_string === 'undefined') {
      var timezone_string = "America/New_York"; // lets set to TC timezone
    }
    return moment(date).tz(timezone_string).format("D MMM YYYY HH:mm z");
  },


  // get contests tableView & gridView data
  getContests: function(url, table, pageSize, challenge_type, isAppend, callback) {
    $('.errorLoading').remove();
    if (url == null || url == "") {
      return false;
    }
    ajax.data["contest_type"] = challenge_type;
    ajax.data["pageSize"] = pageSize;
    app.setLoading();

    if (xhr != "") {
      xhr.abort();
    }

    xhr = $.getJSON(url, ajax.data, function(data) {
      app.getContestTable(table, data, pageSize, isAppend);
      app.getContestGrid($('#gridView .contestGrid'), data, (pageSize), isAppend);
      if (callback != null && callback != "") {
        callback();
      }
    }).fail(function() { /* add failure handler */
      $('.loading').hide();
      //$('body').append('<div class="errorLoading">Oops... we had trouble loading ' +challenge_type+ ' Challenges.</div>');
      // setTimeout( "jQuery('.errorLoading').fadeOut();",5000 );
    });
  },
  // generate contest view table
  getContestTable: function(table, data, records2Disp, isAppend) {
    if (isAppend != true) {
      $('tbody', table).html(null);
    }
    var count = 0;
    $.each(data, function(key, rec) {

      if (count >= records2Disp) {
        count = 0;
        $('.dataChanges').show();
        return false;
      } else {
        $('.dataChanges').hide();
        count += 1;
      }
      var row = $(blueprints.challengeRow).clone();
      var trackName = ajax.data["contest_type"].split('-')[0];
      row.addClass('track-' + trackName);
      if (rec && ajax.data["contest_type"] == "data-srm") {
        /*
         * generate table row for contest type SRM
         */
        $('.contestName', row).html('<i></i>' + rec.name);
        $('.contestName', row).attr('href', challengeDetailsUrl + rec.roundId);

        if (rec.startDate == null || rec.startDate == "") {
          rec.startDate = "10.31.2013 10:10 EDT"; //dummy data
        }
        //$('.vStartDate', row).html(app.formatDate(rec.startDate));
        $('.vStartDate', row).html(app.formatDate2(new Date(rec.startDate)));

        if (rec.round1EndDate == null || rec.round1EndDate == "") {
          rec.round1EndDate = "10.31.2013 10:10 EDT"; //dummy data
        }
        //$('.vEndRound', row).html(app.formatDate(rec.round1EndDate));
        $('.vEndRound', row).html(app.formatDate2(new Date(rec.endDate)));

        if (rec.endDate == null || rec.endDate == "") {
          rec.endDate = "10.31.2013 10:10 EDT"; //dummy data
        }
        //$('.vEndDate', row).html(app.formatDate(rec.endDate));
        $('.vEndDate', row).html(app.formatDate2(new Date(rec.endDate)));

        if (rec.timeLeft == null || rec.timeLeft == "") {
          rec.timeLeft = "3 days"; //dummy data
        }
        $('.colTLeft', row).html(rec.timeLeft);

        if (rec.purse == null || rec.purse == "") {
          rec.purse = "1500"; //dummy data
        }
        $('.colPur', row).html("$" + numberWithCommas(rec.purse));

        if (rec.registrants == null || rec.registrants == "") {
          rec.registrants = "10"; //dummy data
        }
        $('.colReg', row).html(rec.registrants);

        if (rec.submissions == null || rec.submissions == "") {
          rec.submissions = "10"; //dummy data
        }
        $('.colSub', row).html(rec.submissions);

      } else if (rec && ajax.data["contest_type"] == "data-marathon") {
        /*
         * generate table row for contest type Marathon
         */
        //            	$('.contestName', row).html('<i></i>' + rec.fullName);

        $('.contestName', row).html('<i></i>' + '<a href="http://community.topcoder.com/tc?module=MatchDetails&rd=' + rec.roundId + '">' + rec.fullName + '</a>');

        $trackName = 'mm';
        row.addClass('track-' + trackName);

        if (rec.startDate == null || rec.startDate == "") {
          rec.startDate = "10.31.2013 10:10 EDT"; //dummy data
        }
        $('.vStartDate', row).html(app.formatDate2(rec.startDate));

        if (rec.round1EndDate == null || rec.round1EndDate == "") {
          rec.round1EndDate = "10.31.2013 10:10 EDT"; //dummy data
        }
        //				$('.vEndRound', row).html(app.formatDate2(rec.round1EndDate));
        $('.lEndRound', row).html("");
        $('.vEndRound', row).html("");


        if (rec.endDate == null || rec.endDate == "") {
          rec.endDate = "10.31.2013 10:10 EDT"; //dummy data
        }
        $('.vEndDate', row).html(app.formatDate2(rec.endDate));

        if (rec.timeLeft == null || rec.timeLeft == "") {
          rec.timeLeft = "NA"; //dummy data
        }
        $('.colTLeft', row).html(secondsToString(rec.timeRemaining));


        if (rec.purse == null || rec.purse == "") {
          rec.purse = "NA"; //dummy data
        }
        $('.colPur', row).html("$" + numberWithCommas(rec.purse));

        if (rec.registrants == null || rec.registrants == "") {
          rec.registrants = "NA"; //dummy data
        }
        $('.colReg', row).html(rec.numberOfRegistrants);

        if (rec.submissions == null || rec.submissions == "") {
          rec.submissions = "NA"; //dummy data
        }
        $('.colSub', row).html(rec.numberOfSubmissions);

      } else if (rec && ajax.data["contest_type"] == "design") {

        /*
         * generate table row for design contest type
         */

        var trackName = app.getTrackSymbol(rec.challengeType);

        $('.contestName', row).html('<i></i>' + '<a href="/challenge-details/' + rec.challengeId + '?type=design">' + rec.challengeName + '</a>');
        row.addClass('track-' + trackName);

        if (rec.startDate == null || rec.startDate == "") {
          rec.startDate = "10.31.2013 10:10 EDT"; //dummy data
        }
        $('.vStartDate', row).html(app.formatDate2(new Date(rec.postingDate)));

        if (rec.checkpointSubmissionEndDate == null || rec.checkpointSubmissionEndDate == "") {
          rec.checkpointSubmissionEndDate = ""; // no checkpoint
          $('.lEndRound', row).html("");
          $('.vEndRound', row).html("");
        } else {
          $('.vEndRound', row).html(app.formatDate2(new Date(rec.checkpointSubmissionEndDate)));
        }


        if (rec.endDate == null || rec.endDate == "") {
          rec.endDate = "10.31.2013 10:10 EDT"; //dummy data
        }
        $('.vEndDate', row).html(app.formatDate2(new Date(rec.submissionEndDate)));

        if (rec.timeLeft == null || rec.timeLeft == "") {
          rec.timeLeft = "3 days"; //dummy data
        }
        $('.colTLeft', row).html(secondsToString(rec.currentPhaseRemainingTime));

        if (rec.isEnding === "true") {
          $('.colTLeft', row).addClass('imp');
        }

        if (rec.purse == null || rec.purse == "") {
          rec.purse = "1500"; //dummy data
        }
        $('.colPur', row).html("$" + numberWithCommas(rec.prize.sum()));

        if (rec.registrants == null || rec.registrants == "") {
          rec.registrants = "10"; //dummy data
        }
        $('.colReg', row).html('<a href="/challenge-details/' + rec.challengeId + '?type=design/#viewRegistrant">' + rec.numRegistrants + '</a>');

        if (rec.submissions == null || rec.submissions == "") {
          rec.submissions = "10"; //dummy data
        }
        $('.colSub', row).html(rec.numSubmissions);

      } else if (rec && ajax.data["contest_type"] == "develop") {

        /*
         * generate table row for other contest type
         */
        var trackName = app.getTrackSymbol(rec.challengeType);

        $('.contestName', row).html('<i></i>' + '<a href="/challenge-details/' + rec.challengeId + '">' + rec.challengeName + '</a>');
        row.addClass('track-' + trackName);

        if (rec.startDate == null || rec.startDate == "") {
          rec.startDate = "10.31.2013 10:10 EDT"; //dummy data
        }
        $('.vStartDate', row).html(app.formatDate2(new Date(rec.postingDate)));

        if (rec.checkpointSubmissionEndDate == null || rec.checkpointSubmissionEndDate == "") {
          rec.checkpointSubmissionEndDate = ""; // no checkpoint
          $('.lEndRound', row).html("");
          $('.vEndRound', row).html("");
        } else {
          $('.vEndRound', row).html(app.formatDate2(new Date(rec.checkpointSubmissionEndDate)));
        }

        if (rec.endDate == null || rec.endDate == "") {
          rec.endDate = "10.31.2013 10:10 EDT"; //dummy data
        }
        $('.vEndDate', row).html(app.formatDate2(new Date(rec.submissionEndDate)));

        if (rec.timeLeft == null || rec.timeLeft == "") {
          rec.timeLeft = "3 days"; //dummy data
        }
        $('.colTLeft', row).html(secondsToString(rec.currentPhaseRemainingTime));

        if (rec.isEnding === "true") {
          $('.colTLeft', row).addClass('imp');
        }

        if (rec.purse == null || rec.purse == "") {
          rec.purse = "1500"; //dummy data
        }
        $('.colPur', row).html("$" + numberWithCommas(rec.prize.sum()));

        if (rec.registrants == null || rec.registrants == "") {
          rec.registrants = "10"; //dummy data
        }
        $('.colReg', row).html('<a href="/challenge-details/' + rec.challengeId + '/#viewRegistrant">' + rec.numRegistrants + '</a>');

        if (rec.submissions == null || rec.submissions == "") {
          rec.submissions = "10"; //dummy data
        }
        $('.colSub', row).html(rec.numSubmissions);

      } else {

        if (rec) {
          /*
           * generate table row for other contest type
           */
          $('.contestName', row).html('<i></i>' + '<a href="/challenge-details/' + rec.challengeId + '">' + rec.challengeName + '</a>');
          if (rec.startDate == null || rec.startDate == "") {
            rec.startDate = "10.31.2013 10:10 EDT"; //dummy data
          }
          $('.vStartDate', row).html(app.formatDate2(new Date(rec.postingDate)));

          if (rec.checkpointSubmissionEndDate == null || rec.checkpointSubmissionEndDate == "") {
            rec.checkpointSubmissionEndDate = ""; // no checkpoint
            $('.lEndRound', row).html("");
            $('.vEndRound', row).html("");
          } else {
            $('.vEndRound', row).html(app.formatDate2(new Date(rec.checkpointSubmissionEndDate)));
          }

          if (rec.endDate == null || rec.endDate == "") {
            rec.endDate = "10.31.2013 10:10 EDT"; //dummy data
          }
          $('.vEndDate', row).html(app.formatDate2(new Date(rec.submissionEndDate)));

          if (rec.timeLeft == null || rec.timeLeft == "") {
            rec.timeLeft = "3 days"; //dummy data
          }
          $('.colTLeft', row).html(secondsToString(rec.currentPhaseRemainingTime));

          if (rec.isEnding === "true") {
            $('.colTLeft', row).addClass('imp');
          }

          if (rec.purse == null || rec.purse == "") {
            rec.purse = "1500"; //dummy data
          }
          $('.colPur', row).html("$" + numberWithCommas(rec.prize.sum()));

          if (rec.registrants == null || rec.registrants == "") {
            rec.registrants = "10"; //dummy data
          }
          $('.colReg', row).html('<a href="/challenge-details/' + rec.challengeId + '/#viewRegistrant">' + rec.numRegistrants + '</a>');

          if (rec.submissions == null || rec.submissions == "") {
            rec.submissions = "10"; //dummy data
          }
          $('.colSub', row).html(rec.numSubmissions);

        }
      }
      if (rec) {
        $('tbody', table).append(row);
      }
    });
    app.initZebra(table);
    $('.loading').hide();
  },

  // getGridview Blocks
  getContestGrid: function(gridEl, data, records2Disp, isAppend) {
    if (isAppend != true) {
      gridEl.html(null);
    }
    var count = 0;
    $.each(data, function(key, rec) {
      if (count >= records2Disp) {
        count = 0;
        $('.dataChanges').show();
        return false;
      } else {
        $('.dataChanges').hide();
        count += 1;
      }

      var con = $(blueprints.challengeGridBlock).clone();
      var trackName = ajax.data["contest_type"].split('-')[0];
      con.addClass('track-' + trackName);


      if (ajax.data["contest_type"] == "data-srm") {

        /*
         * generate table row for contest type SRM
         */
        $('.contestName', con).html('<i></i>' + rec.name);

        if (rec.startDate == null || rec.startDate == "") {
          rec.startDate = "10.31.2013 10:10 EDT"; //dummy data
        }
        $('.vStartDate', con).html(app.formatDate2(rec.startDate));

        if (rec.round1EndDate == null || rec.round1EndDate == "") {
          rec.round1EndDate = "10.31.2013 10:10 EDT"; //dummy data
        }
        $('.vEndRound', con).html(app.formatDate2(rec.round1EndDate));

        if (con.endDate == null || con.endDate == "") {
          con.endDate = "10.31.2013 10:10 EDT"; //dummy data
        }
        $('.vEndDate', con).html(app.formatDate2(rec.endDate));

        if (rec.timeLeft == null || rec.timeLeft == "") {
          rec.timeLeft = "3 days"; //dummy data
        }
        $('.cgTLeft', con).html('<i></i>' + rec.timeLeft.replace(/ days/g, 'd').replace(/ Hours/g, 'hr').replace(/ Minutes/g, 'min'));
        if (rec.isEnding === "true") {
          $('.cgTLeft', con).addClass('imp');
        }

        if (rec.purse == null || rec.purse == "") {
          rec.purse = "--"; //dummy data
        }
        $('.cgPur', con).html('<i></i> $' + numberWithCommas(rec.purse));

        if (rec.registrants == null || rec.registrants == "") {
          rec.registrants = "--"; //dummy data
        }
        $('.cgReg', con).html('<i></i>' + rec.registrants);

        if (rec.submissions == null || rec.submissions == "") {
          rec.submissions = "--"; //dummy data
        }
        $('.cgSub', con).html('<i></i>' + rec.submissions);
      } else if (ajax.data["contest_type"] == "data-marathon") {
        /*
         * generate table row for contest type Marathon
         */
        //$('.contestName', con).html('<i></i>' + rec.fullName);
        $('.contestName', con).html('<i></i>' + '<a href="http://community.topcoder.com/tc?module=MatchDetails&rd=' + rec.roundId + '">' + rec.fullName + '</a>');

        if (rec.startDate == null || rec.startDate == "") {
          rec.startDate = "10.31.2013 10:10 EDT"; //dummy data
        }
        $('.vStartDate', con).html(app.formatDate2(new Date(rec.startDate)));

        if (rec.round1EndDate == null || rec.round1EndDate == "") {
          rec.round1EndDate = "10.31.2013 10:10 EDT"; //dummy data
        }
        $('.vEndRound', con).html(app.formatDate2(new Date(rec.endDate)));
        $('.vEndRound', con).html(""); //Hide his for now

        if (con.endDate == null || con.endDate == "") {
          con.endDate = "10.31.2013 10:10 EDT"; //dummy data
        }
        $('.vEndDate', con).html(app.formatDate2(new Date(rec.endDate)));

        if (rec.timeLeft == null || rec.timeLeft == "") {
          rec.timeLeft = "NA"; //dummy data
        }
        $('.cgTLeft', con).html('<i></i>' + ((new Number(rec.timeRemaining)) / 60 / 60 / 24).toPrecision(1).toString() + 'd');
        if (rec.isEnding === "true") {
          $('.cgTLeft', con).addClass('imp');
        }

        if (rec.purse == null || rec.purse == "") {
          rec.purse = "NA"; //dummy data
        }
        $('.cgPur', con).html('<i></i> $' + numberWithCommas(0));

        if (rec.numRegistrants == null || rec.numRegistrants == "") {
          rec.numRegistrants = "NA"; //dummy data
        }
        $('.cgReg', con).html('<i></i>' + rec.numberOfRegistrants);

        if (rec.numSubmissions == null || rec.numSubmissions == "") {
          rec.numSubmissions = "NA"; //dummy data
        }
        $('.cgSub', con).html('<i></i>' + rec.numberOfSubmissions);

      } else if (ajax.data["contest_type"] == "design") {

        $('.contestName', con).html('<i></i>' + '<a href="/challenge-details/' + rec.challengeId + '?type=design">' + rec.challengeName + '</a>');

        if (rec.startDate == null || rec.startDate == "") {
          rec.startDate = "10.31.2013 10:10 EDT"; //dummy data
        }
        $('.vStartDate', con).html(app.formatDate2(new Date(rec.postingDate)));

        if (rec.checkpointSubmissionEndDate == null || rec.checkpointSubmissionEndDate == "") {
          rec.checkpointSubmissionEndDate = ""; // no checkpoint
          $('.vEndRound', con).html("");
        } else {
          $('.lEndRound').show();
          $('.vEndRound', con).html(app.formatDate2(new Date(rec.checkpointSubmissionEndDate)));
        }

        if (con.endDate == null || con.endDate == "") {
          con.endDate = "10.31.2013 10:10 EDT"; //dummy data
        }
        $('.vEndDate', con).html(app.formatDate2(rec.endDate));
        $('.vEndDate', con).html(app.formatDate2(new Date(rec.submissionEndDate)));

        if (rec.timeLeft == null || rec.timeLeft == "") {
          rec.timeLeft = "3 days"; //dummy data
        }

        $('.cgTLeft', con).html('<i></i>' + ((new Number(rec.currentPhaseRemainingTime)) / 60 / 60 / 24).toPrecision(1).toString() + 'd');
        if (rec.isEnding === "true") {
          $('.cgTLeft', con).addClass('imp');
        }

        if (rec.purse == null || rec.purse == "") {
          rec.purse = "1500"; //dummy data
        }
        $('.cgPur', con).html('<i></i> $' + numberWithCommas(rec.prize.sum()));

        if (rec.registrants == null || rec.registrants == "") {
          rec.registrants = "10"; //dummy data
        }
        $('.cgReg', con).html('<i></i>' + rec.numRegistrants);

        if (rec.submissions == null || rec.submissions == "") {
          rec.submissions = "10"; //dummy data
        }
        $('.cgSub', con).html('<i></i>' + rec.numSubmissions);
      } else {

        $('.contestName', con).html('<i></i>' + '<a href="/challenge-details/' + rec.challengeId + '">' + rec.challengeName + '</a>');

        if (rec.startDate == null || rec.startDate == "") {
          rec.startDate = "10.31.2013 10:10 EDT"; //dummy data
        }
        $('.vStartDate', con).html(app.formatDate2(new Date(rec.postingDate)));

        if (rec.checkpointSubmissionEndDate == null || rec.checkpointSubmissionEndDate == "") {
          rec.checkpointSubmissionEndDate = ""; // no checkpoint
          $('.vEndRound', con).html("");
        } else {
          $('.lEndRound').show();
          $('.vEndRound', con).html(app.formatDate2(new Date(rec.checkpointSubmissionEndDate)));
        }

        if (con.endDate == null || con.endDate == "") {
          con.endDate = "10.31.2013 10:10 EDT"; //dummy data
        }
        $('.vEndDate', con).html(app.formatDate2(rec.endDate));
        $('.vEndDate', con).html(app.formatDate2(new Date(rec.submissionEndDate)));

        if (rec.timeLeft == null || rec.timeLeft == "") {
          rec.timeLeft = "3 days"; //dummy data
        }

        $('.cgTLeft', con).html('<i></i>' + ((new Number(rec.currentPhaseRemainingTime)) / 60 / 60 / 24).toPrecision(1).toString() + 'd');
        if (rec.isEnding === "true") {
          $('.cgTLeft', con).addClass('imp');
        }

        if (rec.purse == null || rec.purse == "") {
          rec.purse = "1500"; //dummy data
        }
        $('.cgPur', con).html('<i></i> $' + numberWithCommas(rec.prize.sum()));

        if (rec.registrants == null || rec.registrants == "") {
          rec.registrants = "10"; //dummy data
        }
        $('.cgReg', con).html('<i></i>' + rec.numRegistrants);

        if (rec.submissions == null || rec.submissions == "") {
          rec.submissions = "10"; //dummy data
        }
        $('.cgSub', con).html('<i></i>' + rec.numSubmissions);
      }

      gridEl.append(con);
      $('.loading').hide();
    });
  },

  // generate contest view table
  getPartialContestTable: function(table, data, records2Disp, isAppend) {
    if (isAppend != true) {
      $('tbody', table).html(null);
    }
    var count = 0;

    $.each(data, function(key, rec) {
      if (count >= records2Disp) {
        count = 0;
        return false;
      } else {
        count += 1;
      }
      var row = $(blueprints.partialChallengeRow).clone();
      var trackName = ajax.data["contest_type"].split('-')[0];
      row.addClass('track-' + trackName);
      if (rec && ajax.data["contest_type"] == "data-srm") {
        /*
         * generate table row for contest type SRM
         */
        $('.contestName', row).html('<i></i>' + rec.name);

        if (rec.startDate == null || rec.startDate == "") {
          rec.startDate = "10.31.2013 10:10 EDT"; //dummy data
        }
        $('.vStartDate', row).html(app.formatDate2(new Date(rec.startDate)));

        if (rec.round1EndDate == null || rec.round1EndDate == "") {
          rec.round1EndDate = "10.31.2013 10:10 EDT"; //dummy data
        }
        $('.vEndRound', row).html(app.formatDate2(new Date(rec.round1EndDate)));

        if (rec.endDate == null || rec.endDate == "") {
          rec.endDate = "10.31.2013 10:10 EDT"; //dummy data
        }
        $('.vEndDate', row).html(app.formatDate2(new Date(rec.endDate)));

        if (rec.timeLeft == null || rec.timeLeft == "") {
          rec.timeLeft = "3 days"; //dummy data
        }
        $('.colTLeft', row).html(rec.timeLeft);

        if (rec.purse == null || rec.purse == "") {
          rec.purse = "1500"; //dummy data
        }
        $('.colPur', row).html("$" + numberWithCommas(rec.purse));


      } else if (rec && ajax.data["contest_type"] == "data-marathon") {
        /*
         * generate table row for contest type Marathon
         */
        $('.contestName', row).html('<i></i>' + '<a href=http://community.topcoder.com/tc?module=MatchDetails&rd=' + rec.roundId + '>' + rec.fullName + '</a>');

        if (rec.startDate == null || rec.startDate == "") {
          rec.startDate = "10.31.2013 10:10 EDT"; //dummy data
        }
        $('.vStartDate', row).html(app.formatDate2(new Date(rec.startDate)));

        if (rec.round1EndDate == null || rec.round1EndDate == "") {
          rec.round1EndDate = "10.31.2013 10:10 EDT"; //dummy data
        }
        $('.vEndRound', row).html('--');

        if (rec.endDate == null || rec.endDate == "") {
          rec.endDate = "10.31.2013 10:10 EDT"; //dummy data
        }
        $('.vEndDate', row).html(app.formatDate2(new Date(rec.endDate)));

        if (rec.timeLeft == null || rec.timeLeft == "") {
          rec.timeLeft = "NA days"; //dummy data
        }
        $('.colTLeft', row).html(((new Number(rec.timeRemaining)) / 60 / 60 / 24).toPrecision(1).toString() + ' Days');

        if (rec.purse == null || rec.purse == "") {
          rec.purse = "NA"; //dummy data
        }
        $('.colPur', row).html("$" + numberWithCommas(rec.purse));

      } else if (rec && ajax.data["contest_type"] == "design") {
        /*
         * generate table row for contest type
         */

        $('.contestName', row).html('<i></i>' + '<a href="/challenge-details/' + rec.challengeId + '?type=design">' + rec.challengeName + '</a>');

        if (rec.startDate == null || rec.startDate == "") {
          rec.startDate = "10.31.2013 10:10 EDT"; //dummy data
        }
        $('.vStartDate', row).html(app.formatDate2(new Date(rec.postingDate)));

        if (rec.checkpointSubmissionEndDate == null || rec.checkpointSubmissionEndDate == "") {
          rec.checkpointSubmissionEndDate = ""; // no checkpoint
          $('.lEndRound', row).html("");
          $('.vEndRound', row).html("");
        } else {
          //$('.lEndRound').show();
          $('.vEndRound', row).html(app.formatDate2(new Date(rec.checkpointSubmissionEndDate)));
        }

        if (rec.endDate == null || rec.endDate == "") {
          rec.endDate = "10.31.2013 10:10 EDT"; //dummy data
        }
        $('.vEndDate', row).html(app.formatDate2(new Date(rec.submissionEndDate)));

        if (rec.timeLeft == null || rec.timeLeft == "") {
          rec.timeLeft = "3 days"; //dummy data
        }
        $('.colTLeft', row).html(((new Number(rec.currentPhaseRemainingTime)) / 60 / 60 / 24).toPrecision(1).toString() + ' Days');

        if (rec.purse == null || rec.purse == "") {
          rec.purse = "1500"; //dummy data
        }
        $('.colPur', row).html("$" + numberWithCommas(rec.prize.sum()));


      } else {
        if (rec) {
          /*
           * generate table row for contest type
           */
          $('.contestName', row).html('<i></i>' + '<a href="/challenge-details/' + rec.challengeId + '">' + rec.challengeName + '</a>');

          if (rec.startDate == null || rec.startDate == "") {
            rec.startDate = "10.31.2013 10:10 EDT"; //dummy data
          }
          $('.vStartDate', row).html(app.formatDate2(new Date(rec.postingDate)));

          if (rec.checkpointSubmissionEndDate == null || rec.checkpointSubmissionEndDate == "") {
            rec.checkpointSubmissionEndDate = ""; // no checkpoint
            $('.lEndRound', row).html("");
            $('.vEndRound', row).html("");
          } else {
            //$('.lEndRound').show();
            $('.vEndRound', row).html(app.formatDate2(new Date(rec.checkpointSubmissionEndDate)));
          }

          if (rec.endDate == null || rec.endDate == "") {
            rec.endDate = "10.31.2013 10:10 EDT"; //dummy data
          }
          $('.vEndDate', row).html(app.formatDate2(new Date(rec.submissionEndDate)));

          if (rec.timeLeft == null || rec.timeLeft == "") {
            rec.timeLeft = "3 days"; //dummy data
          }
          $('.colTLeft', row).html(((new Number(rec.currentPhaseRemainingTime)) / 60 / 60 / 24).toPrecision(1).toString() + ' Days');

          if (rec.purse == null || rec.purse == "") {
            rec.purse = "1500"; //dummy data
          }
          $('.colPur', row).html("$" + numberWithCommas(rec.prize.sum()));

        }

      }
      if (rec) {
        $('tbody', table).append(row);
      }
    });
    app.initZebra(table);
    $('.loading').hide();

  },

  // table zebra
  initZebra: function(table) {
    $('tbody tr.alt', table).removeClass('alt');
    $('tbody tr:odd', table).addClass('alt');
  },

  // palceholder
  setPlaceholder: function(selector) {
    $(selector).each(function() {
      _this = $(this);
      var text = _this.attr('placeholder');
      _this.val(text).addClass('isBlured');
      _this.on('focus', function() {
        $(this).on('blur', function() {
          $(this).unbind('blur', arguments.callee);
          if ($.trim($(this).val()) === '') {
            $(this).val(text).addClass("isBlured");
          }
        });
        if ($(this).val() === text) {
          $(this).val('').removeClass("isBlured");
        }
      });
    });
  },

  isLoggedIn: function() {
    var tcjwt = $.cookie('tcjwt');

    if (typeof tcjwt == "undefined") {
      return false;
    }

    var decoded = jwt_decode(tcjwt);
    var expDate = moment.unix(decoded.exp);
    var today = new Date();
    var dateDiff = expDate.diff(today, 'hours');
    if (dateDiff < 0) {
      return false;
    }

    return decoded;
  },

  getHandle: function(callback) {
    var tcsso = $.cookie('tcsso');

    var handle = '';
    if (typeof tcsso === 'undefined') {
      callback(handle);
    } else {
      var uid = tcsso.split('|')[0];
      if (uid) {
        $.getJSON("http://community.topcoder.com/tc?module=BasicData&c=get_handle_by_id&dsid=30&uid=" + uid + "&json=true", function(data) {
          callback(data['data'][0]['handle']);
        });
      }
    }
  }

};
var blueprints = {
  challengeRow: '<tr> \
						<td class="colCh"><div>\
								<a href="javascript:;" class="contestName"></a>\
							</div></td>\
							<td class="colType"><i class="ico"></i></td>\
						<td class="colTime"><div>\
								<div class="row">\
									<label class="lbl">Start Date</label>\
									<div class="val vStartDate"> </div>\
								</div>\
								<div class="row">\
									<label class="lbl lEndRound">Round End</label>\
									<div class="val vEndRound"> </div>\
								</div>\
								<div class="row">\
									<label class="lbl">End Date</label>\
									<div class="val  vEndDate"> </div>\
								</div>\
							</div></td>\
						<td class="colTLeft"></td>\
						<td class="colPur"></td>\
						<td class="colReg"></td>\
						<td class="colSub"></td>\
						<td class="action"><!--<a href="javascript:;" class="btn">Submit</a>--></td>\
					</tr>',
  partialChallengeRow: '<tr> \
						<td class="colCh"><div>\
								<a href="javascript:;" class="contestName"></a>\
							</div></td>\
						<td class="colTime"><div>\
								<div class="row">\
									<label class="lbl">Start Date</label>\
									<div class="val vStartDate"> </div>\
								</div>\
								<div class="row">\
									<label class="lbl lEndRound">Round End</label>\
									<div class="val vEndRound"> </div>\
								</div>\
								<div class="row">\
									<label class="lbl">End Date</label>\
									<div class="val  vEndDate"> </div>\
								</div>\
							</div></td>\
						<td class="colPur"></td>\
					</tr>',
  challengeGridBlock: '<div class="contest">\
									<div class="cgCh"><a href="javascript:;" class="contestName"></a></div>\
									<div class="cgTime">\
										<div>\
											<div class="row">\
												<label class="lbl">Start Date</label>\
												<div class="val vStartDate"></div>\
											</div>\
											<div class="row">\
												<label class="lbl lEndRound">Round End</label>\
												<div class="val vEndRound"></div>\
											</div>\
											<div class="row">\
												<label class="lbl">End Date</label>\
												<div class="val vEndDate"></div>\
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
								</div>'
};

// everythings begins from here
$(document).ready(function() {
  app.init();
  app.initEvents();
});

function secondsToString(seconds) {
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
  return "<span style='font-size:14px;" + style + "'>" + (numdays > 0 ? numdays + " Day(s) " : "") + "" + numhours + " Hrs " + (numdays == 0 ? numminutes + " Min " : "") + "</span>";
}

function numberWithCommas(x) {
  return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Calculate the sum of the values of an array.
Array.prototype.sum = function() {
  for (var i = 0, sum = 0, max = this.length; i < max; sum += this[i++]);
  return sum;
};

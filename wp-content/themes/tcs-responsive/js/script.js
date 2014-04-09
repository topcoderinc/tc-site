var ev = 'click';
if ($.support.touch) {
  ev = 'tap'
}
var ie7 = false;
// application functions
var app = {
  init: function () {

    // init slider
    $('#banner .slider').bxSlider({
      minSlides: 1,
      maxSlides: 1,
      controls: false,
      responsive: !ie7,
      auto: true,
      pause: 5000,
      slideMargin: 2,
	  adaptiveHeight: true
    });


    $('.dataTable.challenges tbody, .layChallenges .dataTable tbody').html(null);
    $('#gridView .contestGrid').html(null);
    // challenges list init
    if ($('.layChallenges').length > 0) {
      app.challenges.init();
    }
    if ($('.community').length > 0) {
      app.community.init();
    }

    if ($('#whatsHappening .slider').length > 0) {
      $('#whatsHappening .slider').each(function () {
        $('ul', $(this)).bxSlider({
          minSlides: 1,
          maxSlides: 1,
          responsive: !ie7,
          controls: false
        });
      })
    }

    app.setPlaceholder($('.connected .email'));

    if (navigator.userAgent.indexOf('MSIE 7.0') >= 0) {
      $('body').addClass('ie7');
      ie7 = true;
    }

    $('body').on(ev, function () {
      $('.btnMyAcc').removeClass('isActive');
      $('.userWidget:visible').hide();
    });

    $('.userWidget').on(ev, function (e) {
      e.stopPropagation();
    })


  },
  // event bindings
  initEvents: function () {
    $('.btnMyAcc').on(ev, function () {
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

    // login
    $('.actionLogout').on(ev, function () {
      $('#navigation, .sidebarNav').addClass('newUser')
      $('#navigation .userWidget').hide();
      $('#navigation .isActive').removeClass('isActive');
      $('.btnRegWrap').show();
      $('.btnAccWrap').hide();
    });
    $('.actionLogin').on(ev, function () {
      $('#navigation, .sidebarNav').removeClass('newUser');
      $('.btnRegWrap').hide();
      $('.btnAccWrap').show();
    });

    // sidebar navigation
    $('.sidebarNav').on(ev, function (e) {
      e.stopPorpagation();
    });
    $('.sidebarNav a').on(ev, function () {
      var root = $(this).closest('.root');
      if ($(this).closest('li').hasClass('isActive')) {
        $(this).closest('li').removeClass('isActive');
      } else {
        $(this).closest('li').addClass('isActive');
      }
    })

    //main Nav
    $('#mainNav').on(ev, function () {
      $('.sidebarNav').css('opacity', 1);


      $('.content, #navigation').toggleClass('moving');

    });
    $('#mainNav .root').on(ev, function (e) {
      e.stopPropagation();
    });
    // footer navigation
    $('#footer nav h4').on(ev, function () {
      var ul = $('ul', $(this).closest('nav'));
      if (ul.is(':visible')) {
        ul.slideUp();
      } else {
        ul.slideDown();
      }
    });

    // tab navs
    $('.tabNav a').on(ev, function () {
      var id = $(this).attr('href');
      $('.tab', $(this).closest('.tabsWrap')).hide();
      $(id).fadeIn();
      $('.active', $(this).closest('nav')).removeClass('active');
      $(this).addClass('active');
      return false;
    });
  },

  setLoading: function () {
    if ($('.loading').length <= 0) {
      $('body').append('<div class="loading">Loading...</div>');
    } else {
      $('.loading').show();
    }
  },
  /* community page functions
   --------------------------------------------------------------*/
  community: {
    init: function () {
      url = 'data/challenges-1.json';
      app.setLoading();
      $.getJSON(url, function (data) {
        app.getPartialContestTable($('.challenges'), data, 6);
      });

      /*$('.dataChanges .viewAll').on(ev, function () {
       app.setLoading();
       $.getJSON(url, function (data) {
       app.getPartialContestTable($('.challenges'), data, 1000);
       });
       $('.rt', $(this).closest('.dataChanges')).hide();
       $(this).parent().hide();
       });*/
    }

  },

  /* challenges page functions
   --------------------------------------------------------------*/
  challenges: {
    init: function () {
      // add table data
      app.getContests('data/challenges-1.json', $('.dataTable'), 8);

      $('.dataChanges .viewAll').on(ev, function () {
        app.getContests('data/challenges-1.json', $('.dataTable'), 1000);
        $('.rt', $(this).closest('.dataChanges')).hide();
        $(this).parent().hide();
      });

      $('.dataChanges').on(ev, '.nextLink', function (e) {
        $('.prevLink', $(this).parent()).show();
        var _this = $(e.currentTarget);
        var pageNo = _this.attr('href').replace(/#/g, '');
        pageNo = parseInt(pageNo);
        if (pageNo >= 3) {
          _this.hide();
        }
        var url = 'data/challenges-' + pageNo + '.json';
        app.getContests(url, $('.dataTable'), 8);
        _this.attr('href', '#' + (pageNo + 1));
        $('.prevLink', $(this).parent()).attr('href', '#' + (pageNo - 1));
        return false;
      });
      $('.dataChanges').on(ev, '.prevLink', function (e) {
        $('.nextLink', $(this).parent()).show();
        var _this = $(e.currentTarget);
        var pageNo = _this.attr('href').replace(/#/g, '');
        pageNo = parseInt(pageNo);
        if (pageNo <= 1) {
          _this.hide();
        }
        var url = 'data/challenges-' + pageNo + '.json';
        app.getContests(url, $('.dataTable'), 8);
        _this.attr('href', '#' + (pageNo - 1));
        $('.nextLink', $(this).parent()).attr('href', '#' + (pageNo + 1));
        return false;
      });

      $('.views a').on(ev, function (e) {
        if ($(this).hasClass('isActive')) {
          return false;
        }
        $('.viewTab').hide();
        id = $(this).attr('href');
        $(id).fadeIn('fast');
        $('.isActive', $(this).parent()).removeClass('isActive');
        $(this).addClass('isActive');
        return false;
      });

      $('.actions .upDown').on(ev, function () {
        if ($(this).hasClass('alt')) {
          $(this).removeClass('alt');
        } else {
          $(this).addClass('alt');
        }
      });

      // challengeType
      $('.challengeType a').on(ev, function () {
        $('.active', $(this).closest('.challengeType')).removeClass('active');
        $(this).addClass('active');
      });
    }
  },

  // get contests tableView & gridView data
  getContests: function (url, table, records2Disp) {
    if (url == null || url == "") {
      return false;
    }
    app.setLoading();
    $.getJSON(url, function (data) {
      app.getContestTable(table, data, records2Disp);
      app.getContestGrid($('#gridView .contestGrid'), data, (records2Disp + 1));
    });
  },
  // generate contest view table
  getContestTable: function (table, data, records2Disp) {
    $('tbody', table).html(null);
    var count = 0;
    $.each(data.records, function (key, rec) {
      if (count >= records2Disp) {
        count = 0;
        return false;
      } else {
        count += 1;
      }
      var row = $(blueprints.challengeRow).clone();
      row.addClass('track' + rec.track)
      $('.contestName', row).html('<i></i>' + rec.title);
      $('.vStartDate', row).html(rec.time.startDate.replace(/ /g, '&nbsp;'));
      $('.vEndRound', row).html(rec.time.endRound.replace(/ /g, '&nbsp;'));
      $('.vEndDate', row).html(rec.time.endDate.replace(/ /g, '&nbsp;'));
      $('.colTLeft', row).html(rec.timeLeft);
      if (rec.isEnding === "true") {
        $('.colTLeft', row).addClass('imp');
      }
      $('.colPur', row).html(rec.purse);
      $('.colReg', row).html(rec.registrants);
      $('.colSub', row).html(rec.Submissions);
      if (rec.isRegistered === "false") {
        $('.action', row).html('<a href="javascript:;" class="btn btnAlt">Register</a>');
      } else {
        $('.action', row).html('<a href="javascript:;" class="btn">Submit</a>');
      }
      $('tbody', table).append(row);
    });
    app.initZebra(table);
    $('.loading').hide();
  },

  //getGridview Blocks
  getContestGrid: function (gridEl, data, records2Disp) {
    gridEl.html(null);
    var count = 0;
    $.each(data.records, function (key, rec) {
      if (count >= records2Disp) {
        count = 0;
        return false;
      } else {
        count += 1;
      }

      var con = $(blueprints.challengeGridBlock).clone();
      con.addClass('track' + rec.track);
      $('.contestName', con).html('<i></i>' + rec.title);
      $('.vStartDate', con).html(rec.time.startDate.replace(/ /g, '&nbsp;'));
      $('.vEndRound', con).html(rec.time.endRound.replace(/ /g, '&nbsp;'));
      $('.vEndDate', con).html(rec.time.endDate.replace(/ /g, '&nbsp;'));
      $('.cgTLeft', con).html('<i></i>' + rec.timeLeft.replace(/ Days/g, 'd').replace(/ Hours/g, 'hr').replace(/ Minutes/g, 'min'));
      if (rec.isEnding === "true") {
        $('.cgTLeft', con).addClass('imp');
      }
      $('.cgPur', con).html('<i></i>' + rec.purse);
      $('.cgReg', con).html('<i></i>' + rec.registrants);
      $('.cgSub', con).html('<i></i>' + rec.Submissions);
      gridEl.append(con);
      $('.loading').hide();
    });
  },


  // generate contest view table
  getPartialContestTable: function (table, data, records2Disp) {
    $('tbody', table).html(null);
    var count = 0;
    $.each(data.records, function (key, rec) {
      if (count >= records2Disp) {
        count = 0;
        return false;
      } else {
        count += 1;
      }
      var row = $(blueprints.partialChallengeRow).clone();
      row.addClass('track' + rec.track)
      $('.contestName', row).html('<i></i>' + rec.title);
      $('.vStartDate', row).html(rec.time.startDate.replace(/ /g, '&nbsp;'));
      $('.vEndRound', row).html(rec.time.endRound.replace(/ /g, '&nbsp;'));
      $('.vEndDate', row).html(rec.time.endDate.replace(/ /g, '&nbsp;'));
      $('.colPur', row).html(rec.purse);
      $('tbody', table).append(row);
    });
    app.initZebra(table);
    $('.loading').hide();
  },

  // table zebra
  initZebra: function (table) {
    $('tbody tr.alt', table).removeClass('alt');
    $('tbody tr:odd', table).addClass('alt');
  },

  // palceholder
  setPlaceholder: function (selector) {
    $(selector).each(function () {
      _this = $(this);
      var text = _this.attr('placeholder');
      _this.val(text).addClass('isBlured');
      _this.on('focus', function () {
        $(this).on('blur', function () {
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
  }

};
var blueprints = {
  challengeRow: '<tr> \
						<td class="colCh"><div>\
								<a href="javascript:;" class="contestName"></a>\
							</div></td>\
						<td class="colTime"><div>\
								<div class="row">\
									<label class="lbl">Start Date</label>\
									<div class="val vStartDate"> </div>\
								</div>\
								<div class="row">\
									<label class="lbl">Round End</label>\
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
						<td class="action"><a href="javascript:;" class="btn">Submit</a></td>\
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
									<label class="lbl">Round End</label>\
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
												<label class="lbl">Round End</label>\
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
											<i></i>3d\
										</p>\
										<p class="cgPur">\
											<i></i>$1500\
										</p>\
										<p class="cgReg">\
											<i></i>10\
										</p>\
										<p class="cgSub">\
											<i></i>10\
										</p>\
									</div>\
								</div>'
};

// everythings begins from here
$(document).ready(function () {
  app.init();
  app.initEvents();
});

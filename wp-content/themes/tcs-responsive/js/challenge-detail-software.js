var sliderActive = false;
var prizeSliderActive = false;
var submissionSliderActive = false;
var submissionSingleSliderActive = false;
var informationViewSliderActive = false;
var slider;
var prizeSlider;
var submissionSlider;
var submissionSingleSlider;
var informationViewSlider;
var sliderClone;
var submissionCurrPage=1;

var tcjwt = getCookie('tcjwt');


function createSlider() {
  sliderClone = $('.columnSideBar .slider > ul:first-child').clone();
  slider = jQuery('.columnSideBar .slider > ul:first-child').bxSlider({
    minSlides: 1,
    maxSlides: 1,
    responsive: !ie7,
    adaptiveHeight: false,
    swipeThreshold: 40,
    controls: false,
    infiniteLoop: false
  });
  return true;
}

function createPrizeSlider() {
  prizeSlider = $('.prizeSlider > ul:first-child').bxSlider({
    minSlides: 1,
    maxSlides: 1,
    responsive: !ie7,
    adaptiveHeight: false,
    swipeThreshold: 40,
    controls: false
  });
  return true;
}

function createSubmissionSlider(){

    submissionSlider = $('.submissionSlider > ul:first-child').bxSlider({
        minSlides: 1,
        maxSlides: 1,
        responsive: !ie7,
        adaptiveHeight: false,
        swipeThreshold: 40,
        controls: false ,
        infiniteLoop: false 
      });
      return true;
}

function createSubmissionSlider(){

    submissionSlider = $('.submissionSlider > ul:first-child').bxSlider({
        minSlides: 1,
        maxSlides: 1,
        responsive: !ie7,
        adaptiveHeight: false,
        swipeThreshold: 40,
        controls: false ,
        infiniteLoop: false 
      });
      return true;
}
function createSubmissionSingleSlider(){
    submissionSingleSlider = $('.submissionSingleSlider > ul:first-child').bxSlider({
        minSlides: 1,
        maxSlides: 1,
        responsive: !ie7,
        adaptiveHeight: false,
        swipeThreshold: 40,
        controls: false ,
        infiniteLoop: false 
      });
      return true;
}
function createInformationViewSlider(){
    var informationViewSlider = $('.informationViewSlider ul').bxSlider({
        adaptiveHeight: false,
        controls: false,
        infiniteLoop: false 
      });
      return true;
}


function getAnchor(url) {
  var index = url.lastIndexOf('#');
  if (index != -1)
    return url.substring(index);
}

//create slider if page is wide
$(document).ready(function () {
  if (window.innerWidth < 1019) {

    $(".rightColumn").insertAfter('.leftColumn');
    $('.grid-1-3').insertBefore('#contest-overview');
    $('.scroll-pane').jScrollPane({ autoReinitialise: true });

    sliderActive = createSlider();

    $('#stepBox .rightColumn .nextBox .allDeadlineNextBoxContent p:nth-child(3)').addClass('moveRight');
    if ($('.studio').length > 0) {
      updateDesignContestMobile();
    }
    // Hide deadline boxes on mobile view
    updateDeadlineBoxMobile();

    $('.registrantsTable').not('.mobile').addClass('hide');
    $('.registrantsTable.mobile').removeClass('hide');
  } else {
    if ($('.studio').length > 0) {
      updateDesignContest();
    }
    // Show deadline boxes
    updateDeadlineBox();

    $('.registrantsTable').not('.mobile').removeClass('hide');
    $('.registrantsTable.mobile').addClass('hide');
  }

  $('a[href="' + getAnchor(location.href) + '"]').click();

  var loggedIn = app.isLoggedIn();

  // init tab nav
  app.tabNavinit();

  if (typeof challengeId != 'undefined') {
    if ($('.loading').length <= 0) {
      $('body').append('<div class="loading">Loading...</div>');
    } else {
      $('.loading').show();
    }

    if (loggedIn) {
      getChallenge($.cookie('tcjwt'), function(challenge) {
        updateRegSubButtons(challenge);
        addDocuments(challenge);
        $('.loading').hide();
      });
    } else {
        //Bugfix I-114581:
        //if auth cookie is not set, we cannot list Documents or know if any exist, since API requires Auth header to return Documents
        $('.downloadDocumentList').html('<li><strong>Log In and Register for Challenge to Download Files (if available)</strong></li>');
        var now = new Date();
        if (registrationUntil && now.getTime() < registrationUntil.getTime()) {
          $('.challengeRegisterBtn').removeClass('disabled');
        }
        $('.loading').hide();
    }
  }


  function updateRegSubButtons(challenge) {
    // if there was an error getting the challenge then enable the buttons
	if (challenge.status == false) {
      $('.challengeRegisterBtn').removeClass('disabled');
      $('.challengeSubmissionBtn').removeClass('disabled');
      $('.challengeSubmissionsBtn').removeClass('disabled');
    } else {
      if (loggedIn) {
        app.getHandle(function(handle) {
          var registrants = [];
          var now = new Date();
          $.each(challenge.registrants, function(x, registrant) {
            registrants.push(registrant.handle)
          });

          if (registrationUntil && now.getTime() < registrationUntil.getTime() && registrants && registrants.indexOf(handle) == -1) {
            $('.challengeRegisterBtn').removeClass('disabled');
          }
          if (submissionUntil && now.getTime() < submissionUntil.getTime() && registrants && registrants.indexOf(handle) > -1) {
            $('.challengeSubmissionBtn').removeClass('disabled');
            $('.challengeSubmissionsBtn').removeClass('disabled');
          }
        });
      }
    }
  }

  function addDocuments(challenge) {
    //Bugfix I-114581 fixed document download messages
    if (typeof challenge.Documents !== 'undefined' && $('.downloadDocumentList')) {
      $('.downloadDocumentList').children().remove();
      //only display "none" if there really are no document downloads available
      if (challenge.Documents.length === 0) {
          $('.downloadDocumentList').html('<li><strong>None</strong></li>');
      } else {
        //output list of downloads
        challenge.Documents.map(function(x) {
            $('.downloadDocumentList').append($(
                '<li><a href="'+x.url+'">'+x.documentName+'</a></li>'
            ));
        });
      }
    } else {
        //Bugfix I-114581:
        //if auth cookie is set, but user is not registered for challenge they will get this message.
        //API does not tell us if any downloads exist if not registered, so cannot tell if any will be available
        $('.downloadDocumentList').html('<li><strong>Register to Download Files (if available)</strong></li>');
    }
  }

  function getChallenge(tcjwt, callback) {
    if (tcjwt && (typeof challengeId != 'undefined')) {
      $.getJSON(ajaxUrl, {
        "action": "get_challenge_documents",
        "challengeId": challengeId,
        "challengeType": challengeType,
        "nocache": true,
        "jwtToken": tcjwt.replace(/["]/g, "")
      }, function (data) {
        callback(data);
      });
    } else {
      $('.loading').hide();
    }
  }
});

//create/destroy slider based on width
$(window).resize(function () {

  if (window.innerWidth < 1019) {
    if (sliderActive == false) {
      $(".rightColumn").insertAfter('.leftColumn');
      $('.grid-1-3').insertBefore('#contest-overview');
      $('.scroll-pane').jScrollPane({ autoReinitialise: true });
      sliderActive = createSlider();
    }
    if ($('.studio').length > 0) {
      updateDesignContestMobile();
    }
    // Hide deadline boxes on mobile view
    updateDeadlineBoxMobile();

    $('.registrantsTable').not('.mobile').addClass('hide');
    $('.registrantsTable.mobile').removeClass('hide');
	
	
	 $('.submissionShowcase').css('display','none');
            $('.submissionSingleSlider').css('display','block');
            $('.submissionSingleSlider .bx-viewport').height(493);
            $('.submissionSingleSlider .bx-viewport').width(300).css('margin','0 auto');
            $('.submissionSingleSlider .bx-viewport li').css('width','300px');
            $('.submissionSingleSlider > ul:first-child').bxSlider({
                minSlides: 1,
                maxSlides: 1,
                responsive: !ie7,
                adaptiveHeight: false,
                swipeThreshold: 40,
                controls: false ,
                infiniteLoop: false 
              });



            $('.submissionList').css('display','none');
            $('.submissionSlider').css('display','block');
            $('.submissionSlider .bx-viewport').height(340);
            $('.submissionSlider .bx-viewport').width("300").css('margin','0 auto');
            $('.submissionSlider .bx-viewport li').css('width','300px');
            $('.submissionSlider > ul:first-child').bxSlider({
                minSlides: 1,
                maxSlides: 1,
                responsive: !ie7,
                adaptiveHeight: false,
                swipeThreshold: 40,
                controls: false ,
                infiniteLoop: false 
              });

            

            $('.informationView').css('display','none');
            $('.informationViewSlider').css('display','block');
            //createInformationViewSlider();

	
  }

  if (window.innerWidth > 1019) {
    if (sliderActive == true) {
      $(".rightColumn").insertAfter('.middleColumn');
      $('.grid-1-3').insertAfter('.rightSplit');
      $('.scroll-pane').jScrollPane({ autoReinitialise: true });

      slider.destroySlider();
      sliderActive = false;
      // Replace the destroyed slider with a previously cloned one
      // Hack for a known bxslider bug: http://stackoverflow.com/questions/16283955/window-resize-with-bxslider-destroyed-breaks-style
      $('.slider > ul:first-child').replaceWith(sliderClone);
    }
    if ($('.studio').length > 0) {
      updateDesignContest();
    }
    // Show deadline boxes
    updateDeadlineBox();

    $('.registrantsTable').not('.mobile').removeClass('hide');
    $('.registrantsTable.mobile').addClass('hide');
  }
});

$(window).bind('orientationchange', function (event) {
  //alert('new orientation:' + event.orientation);
  $('.scroll-pane').jScrollPane({ autoReinitialise: true });
});

//getClassName
var getElementsByClassName = function (searchClass, node, tag) {
  if (document.getElementsByClassName) {
    return  document.getElementsByClassName(searchClass)
  } else {
    node = node || document;
    tag = tag || '*';
    var returnElements = []
    var els = (tag === "*" && node.all) ? node.all : node.getElementsByTagName(tag);
    var i = els.length;
    searchClass = searchClass.replace(/\-/g, "\\-");
    var pattern = new RegExp("(^|\\s)" + searchClass + "(\\s|$)");
    while (--i >= 0) {
      if (pattern.test(els[i].className)) {
        returnElements.push(els[i]);
      }
    }
    return returnElements;
  }
};

function hasClass(obj, cls) {
  return obj.className.match(new RegExp('(\\s|^)' + cls + '(\\s|$)'));
}

function addClass(obj, cls) {
  if (!this.hasClass(obj, cls)) obj.className += " " + cls;
}

function removeClass(obj, cls) {
  if (hasClass(obj, cls)) {
    var reg = new RegExp('(\\s|^)' + cls + '(\\s|$)');
    obj.className = obj.className.replace(reg, ' ');
  }
}

var tooltipTimeout;

function showTooltip(source, num) {
  getElementsByClassName('tip' + num)[0].style.display = 'block';
  getElementsByClassName('tip' + num)[0].style.top = source.getBoundingClientRect().top + (document.documentElement.scrollTop || document.body.scrollTop) + 2 + 'px';
  if (hasClass(getElementsByClassName('tip' + num)[0], 'reviewStyleTip')) {
    getElementsByClassName('tip' + num)[0].style.left = source.getBoundingClientRect().left + (document.documentElement.scrollLeft || document.body.scrollLeft) - 210 + 'px';
  } else {
    getElementsByClassName('tip' + num)[0].style.left = source.getBoundingClientRect().left + (document.documentElement.scrollLeft || document.body.scrollLeft) + 32 + 'px';
  }
}

function hideTooltip(num) {
  tooltipTimeout = setTimeout(function () {
    getElementsByClassName('tip' + num)[0].style.display = 'none';
  }, 200);
}

function enterTooltip(num) {
  clearTimeout(tooltipTimeout);
  getElementsByClassName('tip' + num)[0].style.display = 'block';
}

function ieHack() {
  var browser = navigator.appName;
  var b_version = navigator.appVersion;
  var version = b_version.split(";");
  if (version[1]) {
    var trim_Version = version[1].replace(/[ ]/g, "");
  }
  if (browser == "Microsoft Internet Explorer" && trim_Version == "MSIE7.0") {
    for (i = 0; i < getElementsByClassName('shadow').length; i++) {
      getElementsByClassName('shadow')[i].style.marginTop = '-1px';
    }
  }
}

function updateDeadlineBoxMobile() {
  $('.deadlineBoxContent').addClass("hide");
  $('.allDeadlineNextBoxContent').addClass('hide');
  $('.nextDeadlineNextBoxContent').removeClass('hide');
}

function updateDeadlineBox() {
  if ($('.nextDeadlineNextBoxContent').hasClass('hide')) {
    $('.allDeadlinedeadlineBoxContent').removeClass("hide");
  } else {
    $('.nextDeadlinedeadlineBoxContent').removeClass("hide");
  }
}

function updateDesignContestMobile() {
  if (prizeSliderActive == false) {
    $('.prizeTable').addClass("hide");
    $('.prizeSlider').removeClass("hide");
    prizeSliderActive = createPrizeSlider();
  }
  $('.tabsWrap .tabNav').not('.mobile').addClass('hide');
  $('.tabsWrap .tabNav.mobile').removeClass('hide');
}

function updateDesignContest() {
  if (prizeSliderActive == true) {
    $('.prizeTable').removeClass("hide");
    $('.prizeSlider').addClass("hide");
    prizeSlider.destroySlider();
    prizeSliderActive = false;
  }
  $('.tabsWrap .tabNav').not('.mobile').removeClass('hide');
  $('.tabsWrap .tabNav.mobile').addClass('hide');
}


$(function () {
  $('.scroll-pane').jScrollPane();
  //switch the view all deadline and view next deadline
  $(".viewAllDeadLineBtn").click(function () {
    $(".nextDeadlinedeadlineBoxContent").addClass("hide");
    $(".allDeadlinedeadlineBoxContent").removeClass("hide");
    $(".nextDeadlineNextBoxContent").addClass("hide");
    $(".allDeadlineNextBoxContent").removeClass("hide");
    $(".contestEndedBox").addClass("hide");

  });
  //switch the view all deadline and view next deadline
  $(".viewNextDeadLineBtn").click(function () {
    $(".contestEndedBox").addClass("hide");
    $(".allDeadlinedeadlineBoxContent").addClass("hide");
    $(".nextDeadlinedeadlineBoxContent").removeClass("hide");
    $(".allDeadlineNextBoxContent").addClass("hide");
    $(".nextDeadlineNextBoxContent").removeClass("hide");
  });

  $(".morePayments.active").click(function () {
    if ($(this).hasClass("closed")) {
      $(".morePayments.active").removeClass("closed");
      $(".morePayments.active").addClass("open");
      $(".additionalPrizes").removeClass("hide");
    } else {
      $(".morePayments.active").removeClass("open");
      $(".morePayments.active").addClass("closed");
      $(".additionalPrizes").addClass("hide");
    }
  });

  $(".challengeRegisterBtn").click(function (event) {
    if ($(this).hasClass("disabled")) { return false; }

    var loggedInCookie = app.isLoggedIn();
    if (loggedInCookie) {
      if ($('.loading').length <= 0) {
        $('body').append('<div class="loading">Loading...</div>');
      } else {
        $('.loading').show();
      }
      $.getJSON(ajaxUrl, {
        "action": "register_to_challenge",
        "challengeId": challengeId,
        "jwtToken": $.cookie('tcjwt').replace(/["]/g, "")
      }, function (data) {
        $('.loading').hide();
        if (data["message"] === "ok") {
          showModal("#registerSuccess");
        } else if (data["error"]["details"] === "You should agree with all terms of use.") {
          window.location = siteURL + "/challenge-details/terms/" + challengeId + "?challenge-type=" + challengeType;
        } else if (data["error"]["details"]) {
          $("#registerFailed .failedMessage").text(data["error"]["details"]);
          showModal("#registerFailed");
        }
      });
    } else {
      $('.actionLogin').click();
    }
  });

  if (autoRegister) {
    $(".challengeRegisterBtn").click();
  }

  $("#registerSuccess .closeModalReg").click(function (event) {
    $('.modal,#bgModal').hide();
    window.location.href = siteURL + "/challenge-details/" + challengeId + "?type=" + challengeType + "&nocache=true";
  });

    var QueryString = function () {
        // This function is anonymous, is executed immediately and
        // the return value is assigned to QueryString!
        var query_string = {};
        var query = window.location.search.substring(1);
        var vars = query.split("&");
        for (var i=0;i<vars.length;i++) {
            var pair = vars[i].split("=");
            // If first entry with this name
            if (typeof query_string[pair[0]] === "undefined") {
                query_string[pair[0]] = pair[1];
                // If second entry with this name
            } else if (typeof query_string[pair[0]] === "string") {
                var arr = [ query_string[pair[0]], pair[1] ];
                query_string[pair[0]] = arr;
                // If third or later entry with this name
            } else {
                query_string[pair[0]].push(pair[1]);
            }

        }
        return query_string;
    } ();

    function showModal(selector) {
        var modal = $(selector);
        $('#bgOverlapModal').show();
        modal.show();
        centerModal();
    }
    function centerModal(selector){
        var modal = $('.modal:visible');
        if($(window).width() >= 1003 || $('html').is('.ie6, .ie7, .ie8'))
            modal.css('margin', -modal.height() / 2 + 'px 0 0 ' + (-modal.width() / 2) + 'px');
        else {
            modal.css('margin', '0');
        }
    }
	$('.closePopupModal,#bgOverlapModal').on('click', function () {
		closePopupModal();
	});
    function closePopupModal() {
        $('.modal,#bgOverlapModal').hide();
    }
    if(QueryString.registered === 'success'){
        showModal('#registerSuccess');
        setTimeout(function(){
            $('#Registrants').click();
            $('#Registrants1').click();
        },200)
    }

    $('.jsFullScreenBtn').on(ev, function() {
		var loading = $('#bgLoadingModal span');
		loading.css({
            'margin-top'  : '-' + Math.round(loading.height() / 2) + 'px',   
            'margin-left' : '-' + Math.round(loading.width() / 2) + 'px',       
        });
		$('#bgLoadingModal').show();
		window.setTimeout(
			function(){
				if($(window).width() >= 1003 || $('html').is('.ie6, .ie7, .ie8')){
					showModal('#showSubmission');
					var showSubmission = $('#showSubmission:visible');
					showSubmission.css({
						'width' : '940px'
					});
					showSubmission.css('margin', -showSubmission.height() / 2 + 'px 0 0 ' +  '-485px');
				}else {
					
					showModal('#showSubmission');
					
					var modalHeightC = $('#showSubmission .content').height();
					var showSubmission = $('#showSubmission:visible');
					//showSubmission.css('margin', '0').css('top',0).css('left',0).css('width','97%').css('height',"auto");
				    showSubmission.css({
						'width' : ($(window).width()-20)+'px'
					});
					showSubmission.css({
						'left' : 'auto',
						'margin-top'  : '-' + Math.round(showSubmission.height() / 2) + 'px',   
						'margin-left' : '0px',       
					});
				}
				$('#bgLoadingModal').hide();
			},
			3000
		);
    });
	
	/**
	 * Paging Navigation 
	 */
	$(".nextLink").on(ev, function() {
		var parentDiv = $(this).parent().parent().parent();
		var submissionPageCount = parentDiv.find(".submissionPageCount").val();
		if(submissionCurrPage<submissionPageCount) {
			submissionCurrPage++;
			parentDiv.find(".submissionPage").hide();
			parentDiv.find(".page"+submissionCurrPage).show();
		}
		if(submissionCurrPage<=submissionPageCount) {
			parentDiv.find(".nextLink").hide();
		}
	});

	$(".prevLink").on(ev, function() {
		var parentDiv = $(this).parent().parent().parent();
		var submissionPageCount = parentDiv.find(".submissionPageCount").val();
		if(submissionCurrPage>1) {
			submissionCurrPage--;
			parentDiv.find(".submissionPage").hide();
			parentDiv.find(".page"+submissionCurrPage).show();
		}
		if(submissionCurrPage==1) {
			parentDiv.find(".prevLink").hide();
		}
	});	
	
	$(".viewAll").on(ev, function() {
		var parentDiv = $(this).parent().parent().parent();
		parentDiv.find(".nextLink").hide();
		parentDiv.find(".prevLink").hide();
		parentDiv.find(".submissionPage").show();
		parentDiv.find(".viewAll").hide();
	});
	
}); 


/* checkpoint contest css*/
$(function () {
  // checkpoint box click
  $('.winnerList .box').on('click', function () {
    var idx = $(this).closest('li').index();
    $('a', $('.expandCollaspeList li').eq(idx)).trigger('click');
    var top = $('a', $('.expandCollaspeList li').eq(idx)).offset().top - 20;
    var body = $("html, body");
    body.animate({scrollTop: top}, '500', 'swing');
  });

  $('.expandCollaspeList li a').each(function () {
    var _this = $(this).parents('li');
    if (!$(this).hasClass('collapseIcon')) {
      _this.children('.bar').css('border-bottom', '1px solid #e7e7e7');
    } else {
      _this.children('.bar').css('border-bottom', 'none');
    }
  });
  $('.expandCollaspeList li .bar').on(ev, function () {
    var _this = $(this).closest('li');
    if (!$('a', _this).hasClass('collapseIcon')) {
      $('a', _this).addClass('collapseIcon');
      _this.children('.feedBackContent').hide();
      _this.children('.bar').css('border-bottom', 'none');
    } else {
      $('a', _this).removeClass('collapseIcon')
      _this.children('.feedBackContent').show();
      _this.children('.bar').css('border-bottom', '1px solid #e7e7e7');
    }
  });

  $('#submissions .jsViewSubmission').on(ev, function () {
    $('.submissionAllView').hide();
    $('.submissionSingleView').show();
  });


  /* view next */
  $('#submissions .pager').on(ev, '.nextLink', function (e) {
    $('#submissions .prevLink').show();
    return false;
  });
  $('#submissions .pager').on(ev, '.prevLink', function (e) {
    $('#submissions .nextLink').show();

    return false;
  });

  $('.jsSeeMore').on(ev, function () {
    $(this).hide();
    $(this).parents('.stockArtInfo').find('.seeMoreInfo').show();
  });
  if ($('.scrollPane').length > 0) {
    $('.scrollPane').jScrollPane({autoReinitialise: true});
  }

  $('.challenge-detail #submissions .submissionShowcaseList li a').click(function () {
    $('.submissionBig').hide();
    $('.submissionBigMock').hide();
    if (!$(this).hasClass('mock')) {
      $('.submissionBig').show();
      $('.submissionBigMock').hide();
    } else {

      $('.submissionBig').hide();
      $('.submissionBigMock').show();
    }
    $('.challenge-detail #submissions .submissionShowcaseList li a').removeClass('active');
    $(this).addClass('active');
  });

  var userAgent = navigator.userAgent.toLowerCase();
  jQuery.browser = {
    version: (userAgent.match(/.+(?:rv|it|ra|ie)[\/: ]([\d.]+)/) || [])[1],
    safari: /webkit/.test(userAgent),
    opera: /opera/.test(userAgent),
    msie: /msie/.test(userAgent) && !/opera/.test(userAgent),
    mozilla: /mozilla/.test(userAgent) && !/(compatible|webkit)/.test(userAgent)
  };
  if ($.browser.msie && $.browser.version == 10) {
    if (window.innerWidth < 1019) {
      $('.winnerList li .boxName,.winnerListAlt li .boxName').css('top', '0');
      $('.challenge-detail #checkpoints .winnerListEndAlt').css('height', 'auto');
    } else {
      $('.winnerList li .boxName,.winnerListAlt li .boxName').css('top', '-6px');
      $('.challenge-detail #checkpoints .winnerListEndAlt').css('height', '73px');
    }
    $(window).resize(function () {
      if (window.innerWidth < 1019) {
        $('.winnerList li .boxName,.winnerListAlt li .boxName').css('top', '0');
        $('.challenge-detail #checkpoints .winnerListEndAlt').css('height', 'auto');
      } else {
        $('.winnerList li .boxName,.winnerListAlt li .boxName').css('top', '-6px');
        $('.challenge-detail #checkpoints .winnerListEndAlt').css('height', '73px');
      }
    });
  }
  if ($.browser.mozilla) {

    if (window.innerWidth < 1019) {
      $('.winnerList li .boxName,.winnerListAlt li .boxName').css('top', '0');
      $('.challenge-detail #checkpoints .winnerListEndAlt').css('height', 'auto');
    } else {
      $('.winnerList li .boxName,.winnerListAlt li .boxName').css('top', '-6px');
      $('.challenge-detail #checkpoints .winnerListEndAlt').css('height', '75px');
    }

    $(window).resize(function () {
      if (window.innerWidth < 1019) {
        $('.winnerList li .boxName,.winnerListAlt li .boxName').css('top', '0');
        $('.challenge-detail #checkpoints .winnerListEndAlt').css('height', 'auto');
      } else {
        $('.winnerList li .boxName,.winnerListAlt li .boxName').css('top', '-6px');
        $('.challenge-detail #checkpoints .winnerListEndAlt').css('height', '75px');
      }
    });
  }
  if ($.browser.safari) {
    if (window.innerWidth < 1019) {
      $('.winnerList li .boxName,.winnerListAlt li .boxName').css('top', '0');
      $('.challenge-detail #checkpoints .winnerListEndAlt').css('height', 'auto');
    } else {
      $('.challenge-detail #checkpoints .winnerListEndAlt').css('height', '74px');
      $('.winnerList li .boxName,.winnerListAlt li .boxName').css('top', '-5px');
    }
    $(window).resize(function () {

      if (window.innerWidth < 1019) {
        $('.winnerList li .boxName,.winnerListAlt li .boxName').css('top', '0');
        $('.challenge-detail #checkpoints .winnerListEndAlt').css('height', 'auto');
      }
      if (window.innerWidth > 1019) {
        $('.challenge-detail #checkpoints .winnerListEndAlt').css('height', '74px');
        $('.winnerList li .boxName,.winnerListAlt li .boxName').css('top', '-5px');
      }
    });
  }

  var Sys = {};
  var ua = navigator.userAgent.toLowerCase();
  if (ua.match(/version\/([\d.]+).*safari/) != null && ua.match(/version\/([\d.]+).*safari/)[1].split('.')[0] > 3) {
    if (window.innerWidth < 1019) {
      $('.winnerList li .boxName,.winnerListAlt li .boxName').css('top', '0');
      $('.challenge-detail #checkpoints .winnerListEndAlt').css('height', 'auto');
      //$('.winnerListAlt li .boxName').css('width','50%');
    } else {
      $('.challenge-detail #checkpoints .winnerListEndAlt').css('height', '74px');
      $('.winnerList li .boxName,.winnerListAlt li .boxName').css('top', '0px');
    }

    $(window).resize(function () {
      if (window.innerWidth < 1019) {
        $('.winnerList li .boxName,.winnerListAlt li .boxName').css('top', '0');
        $('.challenge-detail #checkpoints .winnerListEndAlt').css('height', 'auto');
        //$('.winnerListAlt li .boxName').css('width','50%');
      } else {
        $('.challenge-detail #checkpoints .winnerListEndAlt').css('height', '74px');
        $('.winnerList li .boxName,.winnerListAlt li .boxName').css('top', '0px');
      }
    });

  }

});


app.tabNavinit = function () {

  // tab navs
if($('#main.coderProfile').lenght<=0){
  $('.tabNav a').off().on(ev, function () {
    var id = $(this).attr('href');
    var tabIdx = id.lastIndexOf('tab=');
    if (tabIdx > 0) {
      id = "#" + id.substr(tabIdx + 4);
    }
    $('.tab', $(this).closest('.tabsWrap')).hide();
    $(id).fadeIn();
    $('.active', $(this).closest('nav')).removeClass('active');
    $(this).addClass('active');

    id = id.replace('#', '');
    $('#mainContent').attr('class', '').addClass('splitLayout').addClass('currentTab-' + id);
    return false;
  });
}

  $('.challenge-detail .tabsWrap .tabNav a').each(function () {
    var value = $.trim($(this).text()).toLocaleLowerCase();
    if (activeTab === value) {
      $('.active', $(this).closest('ul')).removeClass('active');
      $(this).addClass('active');
    }
  })
};

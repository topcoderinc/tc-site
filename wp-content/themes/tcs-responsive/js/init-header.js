//Opera hack to run .ready() on each page load including on history navigation
history.navigationMode = 'compatible';
$(document).ready(function() {

  function showModal(selector) {
    var modal = $(selector);
    $('#bgModal').show();
    modal.show();
    centerModal();
  };

  function centerModal(selector) {
    var modal = $('.modal:visible');
    if ($(window).width() >= 1003 || $('html').is('.ie6, .ie7, .ie8'))
      modal.css('margin', -modal.height() / 2 + 'px 0 0 ' + (-modal.width() / 2) + 'px');
    else {
      modal.css('margin', '0');
    }
  }

  // Initialize member details
    /* 
          Bugfix I-108496: Unable to login using IE10 browser
          pageshow does not work in IE, and will always trigger in Chrome with event.persisted=false, pageshow & event.persisted is a Firefox only feature
          So we must check the browser userAgent for firefox to avoid inconsistent behaviour between browsers
    */
if (navigator.userAgent.toLowerCase().indexOf('firefox') > -1) {
    $(window).bind('pageshow', function(event) {
    //originalEvent.persisted is always FALSE in Chrome, so we need extra variable check
        if (!event.originalEvent.persisted) {
            initMemberDetails(false);
        } else {
            initMemberDetails(true);
        }
    });
} else {
    //if not Firefox, we check for value of a hidden input, if there is none page is not cached, we then set a value and on history navigation that value is retained and we know page is cached
    //this part also has to be hidden from firefox, because once you set value on input with js that value is retained even with page refresh, hence the reason we still need to use pageshow method above for firefox
    if ($('#cache-persist').val()) {
        initMemberDetails(true);
    } else {
        initMemberDetails(false);
    //set hidden input value, when page is re-navigated via history buttons, this value will be saved and we can know page is cached
        $('#cache-persist').val('1');
    }
}
              
$('#login input').keyup(function(e) {
if (e.keyCode == 13) {
    $('#login a.btnSubmit').click();
}
});
});

function initMemberDetails(pagePersisted){
    if ($('.tcssoUsingJS').length > 0) {
      var regCookie = app.isLoggedIn();
        if (regCookie && pagePersisted === false) {
        $('.actionLogout').attr('href', 'javascript:;');
        $('.loginLink, .linkLogin, .btnRegister').addClass('hide').hide();
        $('.logoutLink, .linkLogout, .userDetailsWrapper').removeClass('hide').show();
        $('.headerTopRightMenuLink.logIn a').unbind('click');
        $('.headerTopRightMenuLink.logIn a').text("Log Out").removeClass("actionLogin").addClass("actionLogout");
        app.getHandle(function(handle) {
          $('.userDetails .coder').text(handle);
          $.get(ajaxUrl, {
            "action": "get_member_profile",
            "handle": handle
          }, function(data) {
            if (data['photoLink']) {
              $('.userPic img').attr('src', 'http://community.topcoder.com' + data['photoLink']);
            } else {
              $('.userPic img').attr('src', 'http://community.topcoder.com/i/m/nophoto_login.gif');
            }
            $('.userDetails .coder').attr('href', $('.userDetails .coder').attr('href') + handle);
            $('.userDetails .link').attr('href', $('.userDetails .link').attr('href') + handle);
            $('.action .profileLink').attr('href', $('.action .profileLink').attr('href') + handle);
            var color = '';
            if (data['ratingsSummary']) {
              var maxRating = 0;
              for (var i = 0; i < data['ratingsSummary'].length; i++) {
                if (maxRating < data['ratingsSummary'][i]['rating']) {
                  maxRating = data['ratingsSummary'][i]['rating'];
                  color = data['ratingsSummary'][i]['colorStyle'].split(": ")[1];
                }
              }
            } else if (data['isPM'] == true) {
              color = '#FF9900';
            }
            $('.userDetails .coder').attr('style', 'color: ' + color);
            var userPofileUrl = wpUrl + '/member-profile/' + handle;
            $('.userDetails').prepend('<a class="tc_coder coder" href="' + userPofileUrl + '" style="color:' + color + '">' + handle + '</a>');
            $('.myProfileLink, .profileLink').attr('href', userPofileUrl);
            $('.userDetails .country').text(data['country']);
            $('.userDetails .memberSince').text(dateformat(data['memberSince'].substring(0, 10)));

            if (data['overallEarning'])
              $('.userDetails .memberEarning').text("$" + data['overallEarning']);
            else
              $('.userDetails .memberEarning').text("");
          }, 'json');
          $('#navigation, .sidebarNav').removeClass('newUser');
        });

        // Clear local tcsso cookie on logout
        $('.logoutLink, .actionLogout').click(function() {
          document.cookie = 'tcsso=; path=/; domain=.topcoder.com; expires=' + new Date(0).toUTCString();
          document.cookie = 'tcjwt=; path=/; domain=.topcoder.com; expires=' + new Date(0).toUTCString();
          // check if we have the weird facebook hash
          // if so, redirect to root
          if (window.location.hash == '#_=_') {
            location.href = '';
          } else {
            location.reload();
          }
        });
      } else if (!app.isLoggedIn() && $('.actionLogout').length > 1) {
        $('.headerTopRightMenuLink.logIn a').unbind('click');
        $('.headerTopRightMenuLink.logIn a').text("Log In").removeClass("actionLogout").addClass("actionLogin");
        $('.actionLogin').on('click', function() {
          document.getElementById("loginForm").reset();
          $('#loginForm .btnSubmit').html('Login');
          $(".pwd, .confirm, .strength").parents(".row").show();
          $("#register a.btnSubmit").removeClass("socialRegister");
          showModal('#login');
        });
        $('.loginLink, .linkLogin, .btnRegister').addClass('show').show();
        $('.logoutLink, .linkLogout, .userDetailsWrapper').removeClass('show').hide();

      } else {
        $('.headerTopRightMenu .actionLogin').show();
      }

    } else {
      $('.headerTopRightMenu .actionLogin').show();
    }
}

function getCookie(cname) {
  var name = cname + "=";
  var ca = document.cookie.split(';');
  for (var i = 0; i < ca.length; i++) {
    var c = $.trim(ca[i]);
    if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
  }
  return "";
}

function dateformat(dt) {
  var myDate = new Date(dt);
  if (isNaN(myDate)) {
    return "";
  }

  var month = new Array();
  month[0] = "Jan";
  month[1] = "Feb";
  month[2] = "Mar";
  month[3] = "Apr";
  month[4] = "May";
  month[5] = "Jun";
  month[6] = "Jul";
  month[7] = "Aug";
  month[8] = "Sep";
  month[9] = "Oct";
  month[10] = "Nov";
  month[11] = "Dec";
  var hours = myDate.getHours();
  var minutes = myDate.getMinutes();
  var ampm = hours >= 12 ? 'pm' : 'am';
  hours = hours % 12;
  hours = hours ? hours : 12;
  minutes = minutes < 10 ? '0' + minutes : minutes;
  var strTime = hours + ':' + minutes + ampm;
  //"13 Jan 2012 11:00am";
  return month[myDate.getMonth()] + " " + myDate.getDate() + ", " + myDate.getFullYear();

}

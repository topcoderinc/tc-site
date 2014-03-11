$(function () {

  // modal
  /**
   * show modal
   * selector - the jQuery selector of the popup
   */
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

  function closeModal() {
    $('.modal,#bgModal').hide();
  };

  $(window).on('resize', function () {
    centerModal();
  });

  $('.btnRegister').on('click', function () {
    //document.getElementById("registerForm").reset();
    showModal('#register');

  });

  $('.actionLogin').on('click', function () {
    document.getElementById("loginForm").reset();
    $('#loginForm .btnSubmit').html('Login');
    $(".pwd, .confirm, .strength").parents(".row").show();
    $("#register a.btnSubmit").removeClass("socialRegister");
    showModal('#login');
  });

  $('.closeModal,#bgModal').on('click', function () {
    window.location.replace('/');
    // closeModal();
  });


  /* validation */

  function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp("(^[\\+_A-Za-z0-9-]+(\\.[\\+_A-Za-z0-9-]+)*@([A-Za-z0-9-])+((\\.com)"
    + "|(\\.net)|(\\.org)|(\\.info)|(\\.edu)|(\\.mil)|(\\.gov)|(\\.biz)|(\\.ws)|(\\.us)|(\\.tv)|(\\.cc)"
    + "|(\\.aero)|(\\.arpa)|(\\.coop)|(\\.int)|(\\.jobs)|(\\.museum)|(\\.name)|(\\.pro)|(\\.travel)|(\\.nato)"
    + "|(\\..{2,3})|(\\.([A-Za-z0-9-])+\\..{2,3}))$)");
    return pattern.test(emailAddress);
  };


  function pwdStrength(pwd) {

    var result = 0;
    if (pwd.trim()=='') return 0;
    if (pwd[pwd.length-1].match(/\d/)) return -1;
    if (pwd.length < 7) return -2;
    if (pwd.length > 30) return -3;

    if (pwd.match(/[a-z]/)) result++;
    if (pwd.match(/[A-Z]/)) result++;
    if (pwd.match(/\d/)) result++;
    if (pwd.match(/[\]\[\!\"\#\$\%\&\'\(\)\*\+\,\.\/\:\;\<\=\>\?\@\\\^\_\`\{\|\}\~\-]/)) result++;

    return result;

  }

  $('input.pwd:password').on('keyup', function () {
    var strength = pwdStrength($(this).val());

    $(".strength .field").removeClass("red").removeClass("green");
    var classname = "red";
    $(this).closest('.row').find('span.err3').hide();
    $(this).closest('.row').find('span.err4').hide();
    if (strength >= 3) {
      classname = "green";
      $(this).parents(".row").find("span.valid").css("display", "inline-block");
      $(this).closest('.row').find('input:text').removeClass('invalid');
      $(this).closest('.row').find('span.err1').hide();
      $(this).closest('.row').find('span.err2').hide();
    } else {
      $(this).parents(".row").find("span.valid").hide();
    }

    $(".strength .field").each(function (i, e) {
      if (i < strength) {
        $(e).addClass(classname);
      }
    });
  });

  $('#register form.register input.email:text').on('keyup', function () {
    if (isValidEmailAddress($(this).val())) {
      $(this).parents(".row").find("span.valid").css("display", "inline-block");
      $(this).closest('.row').find('input:text').removeClass('invalid');
      $(this).closest('.row').find('span.err1').hide();
      $(this).closest('.row').find('span.err2').hide();
    } else {
      $(this).parents(".row").find("span.valid").hide();
    }
  });

  $('#register form.register input.name.lastName:text').on('keyup', function () {
    if ($(this).val() != "") {
      $(this).parents(".row").find("span.valid").css("display", "inline-block");
      $(this).closest('.row').find('input:text').removeClass('invalid');
      $(this).closest('.row').find('span.err1').hide();
      $(this).closest('.row').find('span.err2').hide();
    } else {
      $(this).parents(".row").find("span.valid").hide();
    }
  });

  $('#register form.register input.name.firstName:text').on('keyup', function () {
    if ($(this).val() != "") {
      $(this).parents(".row").find("span.valid").css("display", "inline-block");
      $(this).closest('.row').find('input:text').removeClass('invalid');
      $(this).closest('.row').find('span.err1').hide();
      $(this).closest('.row').find('span.err2').hide();
    } else {
      $(this).parents(".row").find("span.valid").hide();
    }
  });


  $('#register form.register input:checkbox').on('change', function () {
    if ($(this).prop('checked')) {
      $(this).parents(".row").find("span.valid").css("display", "inline-block");
      $(this).closest('.row').find('input:text').removeClass('invalid');
      $(this).closest('.row').find('span.err1').hide();
      $(this).closest('.row').find('span.err2').hide();
    } else {
      $(this).parents(".row").find("span.valid").hide();
    }
  });

  $('#register input:password').on('keyup', function () {
    var pwd = $('#register form.register input.pwd:password');
    var confirm = $('#register form.register input.confirm:password');
    if ($(this).hasClass('pwd')) {
      if ($(this).val() != "") {
        $(this).closest('.row').find('span.err1').hide();
        $(this).closest('.row').find('span.err2').hide();
        $(this).closest('.row').find('input:password').removeClass('invalid');
      } else {
        $(this).parents(".row").find("span.valid").hide();
      }
    }
    if (pwd.val() == confirm.val()) {
      confirm.parents(".row").find("span.valid").css("display", "inline-block");
      confirm.parents(".row").find('input:text').removeClass('invalid');
      confirm.parents(".row").find('span.err1').hide();
      confirm.parents(".row").find('span.err2').hide();
    } else {
      confirm.parents(".row").find("span.valid").hide();
    }
  });


  $('select').on('change', function () {
    if ($(this).val() != "") {
      $(this).parents(".row").find("span.valid").css("display", "inline-block");
      $(this).closest('.row').find('.err1').hide();
      $(this).closest('.row').find('.err2').hide();
      $(this).closest('.row').find('.customSelect').removeClass('invalid');
    } else {
      $(this).parents(".row").find("span.valid").hide();
    }
  });

  var handleIsFree = true;
  var handleDeferred = $.Deferred();
  function validateHandle() {
    var handle = $('#register form.register input.name.handle:text').val();
    $.ajax({
      type: 'GET',
      data: {
        handle: handle,
        action: 'get_handle_validity'
      },
      dataType: 'json',
      url: ajaxUrl,
      success: function(data) {
        if (data.error) {
          handleIsFree = false;
          var node = $('#register form.register input.name.handle:text');
          $('input.handle').closest('.row').find('.err2').show();
          $('input.handle').closest('.row').find('input:text').addClass('invalid');
          $('input.handle').closest('.row').find('span.valid').hide();
          handleDeferred.resolve();
        } else {
          handleIsFree = true;
          var node = $('#register form.register input.name.handle:text');
          node.parents(".row").find("span.valid").css("display", "inline-block");
          node.closest('.row').find('input:text').removeClass('invalid');
          node.closest('.row').find('span.err1').hide();
          node.closest('.row').find('span.err2').hide();
          handleDeferred.resolve();
        }
      }
    }).fail(function() {
      console.log('fail with '+handleState.handle);
    });
  }
  $('#register form.register input.name.handle:text').keyup(function() {
    $(this).closest('.row').find('input:text').removeClass('invalid');
    $(this).closest('.row').find('span.err1').hide();
    $(this).closest('.row').find('span.err2').hide();
    $(this).parents(".row").find("span.valid").hide();
  });
  $('#register form.register input.name.handle:text').blur(function() {
    if ($(this).val()=='') return;
    validateHandle();
    handleDeferred = $.Deferred();
  });

  $('select').customSelect();

  $('#register a.btnSubmit').on('click', function () {
    var isValid = true;

    var frm = $('#register form.register');
    $('.invalid', frm).removeClass('invalid');
    $('.err1,.err2', frm).hide();
    $('input:text', frm).each(function () {
      if ($.trim($(this).val()) == "") {
        $(this).closest('.row').find('.err1').show();
        $(this).closest('.row').find('input:text').addClass('invalid');
        isValid = false;
      }
    });

    $('select', frm).each(function () {
      if ($.trim($(this).val()) == "") {
        $(this).closest('.row').find('.err1').show();
        $(this).closest('.row').find('.customSelect').addClass('invalid');
        isValid = false;
      }
    });

    $('input.email:text', frm).each(function () {
      if ($.trim($(this).val()) == "") {
        $(this).closest('.row').find('.err1').show();
        $(this).closest('.row').find('input.email:text').addClass('invalid');
        isValid = false;
      }
      else if (!isValidEmailAddress($(this).val())) {
        $(this).closest('.row').find('.err2').show();
        $(this).closest('.row').find('input.email:text').addClass('invalid');
        isValid = false;
      }

    });
    if (!$(this).hasClass("socialRegister")) {
      $('input.pwd:password', frm).each(function () {
        if ($(this).val() == "") {
          $(this).closest('.row').find('.err1').show();
          $(this).closest('.row').find('input:password').addClass('invalid');
          isValid = false;
        } else if ($(".strength .field.red", frm).length > 0) {
          frm.find(".err2.red").show();
          $(this).closest('.row').find('.err2').show();
          $(this).closest('.row').find('input:password').addClass('invalid');
          isValid = false;
        } else if (pwdStrength($('input.pwd:password').val()) == -1) {
          frm.find(".err3.red").show();
          $(this).closest('.row').find('.err3').show();
          $(this).closest('.row').find('input:password').addClass('invalid');
          isValid = false;
        } else if (pwdStrength($('input.pwd:password').val()) < -1) {
          frm.find(".err4.red").show();
          $(this).closest('.row').find('.err4').show();
          $(this).closest('.row').find('input:password').addClass('invalid');
          isValid = false;
        }
        if ($('input.pwd:password', frm).val() != $('input.confirm:password', frm).val()) {
          $('input.confirm:password').closest('.row').find('.err2').show();
          $('input.confirm:password').closest('.row').find('input:password').addClass('invalid');
          isValid = false;
        }
        else if ($('input.confirm:password', frm).val() == "") {
          $('input.confirm:password').closest('.row').find('.err1').show();
          $('input.confirm:password').closest('.row').find('input:password').addClass('invalid');
          isValid = false;
        }
      });
    }
    $('.lSpace input:checkbox', frm).each(function () {
      if (!$(this).is(':checked')) {
        $(this).closest('.row').find('.err1').show();
        isValid = false;
      }
    });

    var handle = $('#register form.register input.name.handle:text').val();
    if (handle=='') {
      $('input.handle').closest('.row').find('.err1').show();
      $('input.handle').closest('.row').find('input:text').addClass('invalid');
      $('input.handle').closest('.row').find('span.valid').hide();
      isValid = false;
    }
    if (!isValid) return;

    handleDeferred.done(function() {

      if (!handleIsFree) {
        $('input.handle').closest('.row').find('.err2').show();
        $('input.handle').closest('.row').find('input:text').addClass('invalid');
        $('input.handle').closest('.row').find('span.valid').hide();
        isValid = false;
      }
      if (isValid && $('#register a.btnSubmit').html() == 'Sign Up') {
        $('#register a.btnSubmit').html('Please Wait');
        var fields = {
          firstName: $('#registerForm input.firstName').val(),
          lastName: $('#registerForm input.lastName').val(),
          handle: $('#registerForm input.handle').val(),
          country: $('#registerForm select#selCountry').val(),
          email: $('#registerForm input.email').val()
        }
        if ((typeof socialProviderId != 'undefined') && socialProviderId !== "") {
          fields.socialProviderId = socialProviderId;
          fields.socialUserId = socialUserId;
          fields.socialProvider = socialProvider,
            fields.socialUserName = socialUserName;
          fields.socialEmail = socialEmail;
          fields.socialEmailVerified = "t";
        } else {
          fields.password = $('#registerForm  input.pwd').val();
        }

        $.post(ajaxUrl + '?action=post_register', fields, function (data) {
          if (data.code == "200") {
            $('.modal').hide();
            $("#thanks h2").html('Thanks for Registering');
            $("#thanks p").html('We have sent you an email with activation instructions.<br>If you do not receive that email within 1 hour, please email <a href="mailto:support@topcoder.com">support@topcoder.com</a>');
            showModal('#thanks');
            $('#registerForm .invalid').removeClass('invalid');
            $('#registerForm .valid').removeClass('valid');
            $('.err1,.err2', frm).hide();
            resetRegisterFields();
          }
          else {
            //$('.modal').hide();
            //$("#thanks h2").html('Error');
            //$("#thanks p").html(data.description);
            //showModal('#thanks');
            alert(data.description);

          }
          $('#register .btnSubmit').html('Sign Up');
        }, "json");
      }
    });
  });

  $('#login a.btnSubmit').on('click', function () {
    var frm = $(this).closest('form.login');
    $('.invalid', frm).removeClass('invalid');
    $('.err1,.err2', frm).hide();
    var isValid = true;
    $('input:password', frm).each(function () {
      if ($(this).val() == "") {
        $(this).closest('.row').parent().find('.err2').show();
        $(this).closest('.row').find('input:password').addClass('invalid');
        isValid = false;
      }
    });

    if (isValid) {
      $('#loginForm .btnSubmit').html('Please wait');
    }

    /*if(isValid)
     $('input:text',frm).each(function(){
     if($(this).val() != "OK"){
     $(this).closest('.row').find('.err1').show();
     $(this).closest('.row').find('input:text').addClass('invalid');
     $(this).closest('.row').parent().find('input:password').addClass('invalid');
     isValid = false;
     }
     });



     if(isValid){
     $('#loginForm .btnSubmit').html('Please Wait');
     $.post( ajaxUrl+'?action=post_login', { name: $('input.name').val(), password : $('input.pwd').val() },function( data ) {
     if ( data.name == 'OK' ){
     $('#navigation, .sidebarNav').removeClass('newUser');
     $('.btnRegWrap').hide();
     $('.btnAccWrap').show();
     $('.modal').hide();
     }
     else{
     $('#loginForm .err1').show();
     $('#loginForm .err2').hide();
     $('#loginForm .btnSubmit').html('Login');
     }

     }, "json");


     }
     */
  });

  /* hover style icons */
  $('.animeButton').each(function () {
    var $span = $(this).find('.animeButtonHover', this).css('opacity', 0);
    $(this).hover(function () {
      if (!$('html').is('.ie6, .ie7, .ie8'))$span.stop().fadeTo(500, 1);
      else $span.css('opacity', 1);
    }, function () {
      if (!$('html').is('.ie6, .ie7, .ie8'))$span.stop().fadeTo(500, 0);
      else $span.css('opacity', 0);
    });
  });

  $('.person label').each(function () {
    var $span = $(this).find('.animeManHover', this).css('opacity', 0);
    $(this).hover(function () {
      if (!$('html').is('.ie6, .ie7, .ie8'))$span.stop().fadeTo(500, 1);
      else $span.css('opacity', 1);
    }, function () {
      if (!$('html').is('.ie6, .ie7, .ie8'))$span.stop().fadeTo(500, 0);
      else $span.css('opacity', 0);
    });
  });
});

// Resets the registration popup fields
function resetRegisterFields() {
  $("#registerForm input[type='text'], #registerForm input[type='password']").val("");
  $("#registerForm select").val($("#registerForm select option:first").val());
  $("#registerForm .customSelectInner").text($("#registerForm select option:first").text());
  $("#registerForm input[type='checkbox']").attr('checked', false);
  $(".pwd, .confirm, .strength").parents(".row").show();
  $("#register a.btnSubmit").removeClass("socialRegister");
}

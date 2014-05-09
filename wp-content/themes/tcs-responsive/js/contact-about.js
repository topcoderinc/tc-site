/* Issue ID: I-104523 - validate email address */
function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp("(^[\\+_A-Za-z0-9-]+(\\.[\\+_A-Za-z0-9-]+)*@.+(\\.[A-Za-z]{2,}$))");
    return pattern.test(emailAddress);
}

/* Issue ID: I-104523 - validate all text field in contact form */
function validateContactField(field, isEmailField) {
	var text = $.trim(field.val());
		
	if (text.length == 0) {
	  field.addClass('invalid');
	  field.nextUntil('br', 'span.err2').first().hide();
	  field.nextUntil('br', 'span.valid').first().hide();
	  if (field.nextUntil('br', 'span.err1').length == 0) {
	    field.after('<span class="err1">Required field.</span>');	    
	  }
	  field.nextUntil('br', 'span.err1').first().show();
	} else if (isEmailField && !isValidEmailAddress(text)) {
	  field.addClass('invalid');
	  field.nextUntil('br', 'span.err1').first().hide();
	  field.nextUntil('br', 'span.valid').first().hide();
	  if (field.nextUntil('br', 'span.err2').length == 0) {
	    field.after('<span class="err2">Invalid email address.</span>');	    
	  }
	  field.nextUntil('br', 'span.err2').first().show();
	} else {	  
	  field.removeClass('invalid');
      field.nextUntil('br', 'span.err1').first().hide();
	  field.nextUntil('br', 'span.err2').first().hide();
	  if (field.nextUntil('br', 'span.valid').length == 0) {
	    field.after('<span class="valid"></span>').css("display", "inline-block");
	  }
	  field.nextUntil('br', 'span.valid').first().show();
	}
}

$(document).ready(function () {
  /* About Us page*/
  $('.teamView .showMore').on('click', function () {
    $('.members').css('height', 'auto');
    $(this).closest('.actions').hide();
  });
  
  /* Issue ID: I-104523 - validate all text fields on the contact form */
  $('#first_name[name="first_name"]').on('keyup', function () {
    validateContactField($(this), false);
  });
  
  $('#last_name[name="last_name"]').on('keyup', function () {
    validateContactField($(this), false);
  });
  
  $('#email[name="email"]').on('keyup', function () {
    validateContactField($(this), true);
  });
  
  $('textarea[name="description"]').on('keyup', function () {
    validateContactField($(this), false);
  });
  
  /* Issue ID: I-104523 - validate before submitting form */
  $('form').submit(function () {
	if ($(this).find('#first_name[name="first_name"]').length > 0) {
      $(this).find('input:text, textarea').each(function () {
	    var isEmail = $(this).attr('name') == 'email' ? true : false;
        validateContactField($(this), isEmail);
      });
    
	  if ($(this).find('.invalid').length > 0) {
	    return false;
      }
	}
	return true;
  });

  /*Contact Us page*/
  $('.contact .btnSubmit').click(function () {
    var frm = $(this).closest('.contactForm');
    $('.error', frm).removeClass('error');
    $('.errormsg', frm).hide();
    var isValid = true;
    $('input:text, .textarea', frm).each(function () {
      if ($.trim($(this).val()) == "") {
        $(this).closest('.row').addClass('error');
        isValid = false;
      }
    });

    $('#ea', frm).each(function () {
      if (($.trim($(this).val()) == "") || ($.trim($(this).val()).match(/^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/g) === null)) {
        $(this).closest('.row').addClass('error');
        isValid = false;
      }
    });

    if (!isValid) {
      $('.errormsg', frm).fadeIn();
    } else {
      $('#contactForm').submit();
    }
  });

  $('.contact .contactForm input, .contact .contactForm textarea').focus(function () {
    $(this).closest('.row').removeClass('error');
    var frm = $(this).closest('.contactForm');
    if ($('.error', frm).length <= 0) {
      $('.errormsg', frm).hide();
    }
  });


  var $window = $(document);

  function checkWidth() {
    var windowsize = $window.width();

    if (windowsize < 1019) {
      $("#hero .point").animate({ "left": "50%" }, 200);

      $(".contact .trackInfo").click(function () {
        var thisId = $(this).attr('id');
        $(".contactInfo > .container > .descBox").each(function (index) {
          $(this).attr('class', 'descBox');
          $("#" + thisId + 'Box').addClass("activeBox");
        });

        $('html, body').animate({
          scrollTop: $("#hero").offset().top
        }, 500);
      });

    } else {

      $(".contact #register").hover(function () {
        if ($(window).width() > 1019) {
          $("#hero .point").animate({ "left": "165.5px" }, 200);
        }
      }, function () {
        if ($(window).width() > 1019) {
          $("#hero .point").animate({ "left": "505.5px" }, 200);
        }
      });

      $(".contact #support").hover(function () {
        if ($(window).width() > 1019) {
          $("#hero .point").animate({ "left": "845.5px" }, 200);
        }
      }, function () {
        if ($(window).width() > 1019) {
          $("#hero .point").animate({ "left": "505.5px" }, 200);
        }
      });

      $(".contact .trackInfo").hover(function () {
        var thisId = $(this).attr('id');
        $(".contactInfo > .container > .descBox").each(function (index) {
          $(this).attr('class', 'descBox');
          $("#" + thisId + 'Box').addClass("activeBox");
        });

      });

    }
  }

  checkWidth();
  $(window).resize(checkWidth);
})


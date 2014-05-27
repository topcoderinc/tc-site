$(document).ready(function () {

	$('#formResetPassword .btnSubmit').click(function () {
		var handle = $("#formResetPassword .handleOrEmail").val();
		
		$("#formResetPassword .handleOrEmail").parent().find('.err1').hide();
                if($.trim(handle)!="") {
			$("#formResetPassword").submit();
		}
		else {
			$("#formResetPassword .handleOrEmail").parent().find('.err1').show();
		}
	});
	
	$('#formChangePassword .btnSubmit').click(function () {
		var handle = $("#formChangePassword .handle").val();
		var password = $("#formChangePassword .password").val();
		var confirm = $("#formChangePassword .confirm").val();
		var unlockCode = $("#formChangePassword .unlockCode").val();
		var isValid = true;

		$("#formChangePassword").find(".error").hide();
		//validate password
		if ($("#formChangePassword .password").val() == "") {
			$("#formChangePassword .password").parent().find('.err1').show();
			isValid = false;
        } else if ($(".strength .field.red").length > 0) {
			$("#formChangePassword .password").parent().find('.err2').show();
			isValid = false;
        } else if (pwdStrength($('#formChangePassword .password').val()) < -1) {
			$("#formChangePassword .password").parent().find('.err4').show();
			isValid = false;
        }
		
		//validate confirm
		if ($("#formChangePassword .confirm").val() == "") {
			$("#formChangePassword .confirm").parent().find('.err1').show();
			isValid = false;
        } else if ($("#formChangePassword .password").val() != $("#formChangePassword .confirm").val()) {
			$("#formChangePassword .confirm").parent().find('.err2').show();
			isValid = false;
        }
				
		if ($("#formChangePassword .handle").val() == "") {
			$("#formChangePassword .handle").parent().find('.err1').show();
			isValid = false;
        } 		
		
		//validate unlock code 
		if ($("#formChangePassword .unlockCode").val() == "") {
			$("#formChangePassword .unlockCode").parent().find('.err1').show();
			isValid = false;
        } 
		
		if(isValid) {
			$("#formChangePassword").submit();
		}
	});
	
	function pwdStrength(pwd) {

		var result = 0;
                if ($.trim(pwd)=='') return 0;
		if (pwd.length < 7) return -2;
		if (pwd.length > 30) return -3;

		if (pwd.match(/[a-z]/)) result++;
		if (pwd.match(/[A-Z]/)) result++;
		if (pwd.match(/\d/)) result++;
		if (pwd.match(/[\]\[\!\"\#\$\%\&\'\(\)\*\+\,\.\/\:\;\<\=\>\?\@\\\^\_\`\{\|\}\~\-]/)) result++;

		return result;

	}
	
	$('.password').on('keyup', function () {
		$("#formChangePassword .password").parent().find('.error').hide();
		$("#formChangePassword .confirm").parent().find('.error').hide();
		
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
	
});




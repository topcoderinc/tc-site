$(function(){

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
	
	function centerModal(selector){
		var modal = $('.modal:visible');
		if($(window).width() >= 1003 || $('html').is('.ie6, .ie7, .ie8'))
			modal.css('margin', -modal.height() / 2 + 'px 0 0 ' + (-modal.width() / 2) + 'px');
		else {
			modal.css('margin', '0');
		}
	}
	
	function closeModal() {
		$('.modal,#bgModal').hide();
	};
	
	$(window).on('resize', function(){
		centerModal();
	});
	
	$('.btnRegister').on('click', function () {
		//document.getElementById("registerForm").reset();
		showModal('#register');
		
	});
	
	$('.actionLogin').on('click', function() {
		document.getElementById("loginForm").reset();
		$('#loginForm .btnSubmit').html('Login'); 
		showModal('#login');
    });
	
	$('.closeModal,#bgModal').on('click', function () {
		closeModal();
	});	
	
	
	/* validation */
	
	function isValidEmailAddress(emailAddress) {
		var emailPattern = new RegExp("\\b(^[_A-Za-z0-9-]+(\\.[_A-Za-z0-9-]+)*@([A-Za-z0-9-])+((\\.com)"
                                    + "|(\\.net)|(\\.org)|(\\.info)|(\\.edu)|(\\.mil)|(\\.gov)|(\\.biz)|(\\.ws)|(\\.us)|(\\.tv)|(\\.cc)"
                                    + "|(\\.aero)|(\\.arpa)|(\\.coop)|(\\.int)|(\\.jobs)|(\\.museum)|(\\.name)|(\\.pro)|(\\.travel)|(\\.nato)"
                                    + "|(\\..{2,3})|(\\.([A-Za-z0-9-])+\\..{2,3}))$)\\b");
	    return emailPattern.test(emailAddress);
	};
	

	function pwdStrength(pwd){
		var n = 0;
		var c = 0;
		for(i = 0; i < pwd.length; i++){
			if(pwd[i] >= '0' && pwd[i] <= '9') {
				n++;
			}else {
				c++;
			}
		}

		if(n > 0 && c > 0 && pwd.length >= 8)
			return 100.0;
		
		if(n > 8 || c > 8) return 80.0;
		if(n > 7 || c > 7) return 80.0;
		if(n > 6 || c > 6) return 70.0;
		if(n > 5 || c > 5) return 60.0;
		if(n > 4 || c > 4) return 50.0;
		if(n > 3 || c > 3) return 40.0;
		if(n > 2 || c > 2) return 30.0;
		if(n > 1 || c > 1) return 20.0;
		if(n > 0 || c > 0) return 10.0;
		if(n==0 && c == 0) return 0;
	}
	$('input.pwd:password').on('keyup', function(){
		var strength = pwdStrength($(this).val());
		var strengthId = strength*6/100;
		
		$(".strength .field").removeClass("red").removeClass("green");
		var classname = "red";
		if (strengthId > 3)
		{
			classname = "green";
			$(this).parents(".row").find("span.valid").css("display", "inline-block");
			$(this).closest('.row').find('input:text').removeClass('invalid');
			$(this).closest('.row').find('span.err1').hide();
			$(this).closest('.row').find('span.err2').hide();
		}else
		{
			$(this).parents(".row").find("span.valid").hide();
		}
		
		$(".strength .field").each( function(i,e){
			if(i<strengthId){
				$(e).addClass(classname);
			}
		});
	});
	
	$('#register form.register input.email:text').on('keyup', function(){
		if(isValidEmailAddress($(this).val()))
		{
			$(this).parents(".row").find("span.valid").css("display", "inline-block");
			$(this).closest('.row').find('input:text').removeClass('invalid');
			$(this).closest('.row').find('span.err1').hide();
			$(this).closest('.row').find('span.err2').hide();
		} else
		{
			$(this).parents(".row").find("span.valid").hide();
		}
	});
	
	$('#register form.register input.name:text').on('keyup', function(){
		if($(this).val() != "")
		{
			$(this).parents(".row").find("span.valid").css("display", "inline-block");
			$(this).closest('.row').find('input:text').removeClass('invalid');
			$(this).closest('.row').find('span.err1').hide();
			$(this).closest('.row').find('span.err2').hide();
		} else
		{
			$(this).parents(".row").find("span.valid").hide();
		}
	});
	
	$('#register form.register input:checkbox').on('change', function(){
		if($(this).prop('checked'))
		{
			$(this).parents(".row").find("span.valid").css("display", "inline-block");
			$(this).closest('.row').find('input:text').removeClass('invalid');
			$(this).closest('.row').find('span.err1').hide();
			$(this).closest('.row').find('span.err2').hide();
		} else
		{
			$(this).parents(".row").find("span.valid").hide();
		}
	});
	
	$('#register input:password').on('keyup', function(){
		var pwd = $('#register form.register input.pwd:password');
		var confirm = $('#register form.register input.confirm:password');
		if($(this).hasClass('pwd'))
		{
			if($(this).val() != "")
			{
				$(this).closest('.row').find('span.err1').hide();
				$(this).closest('.row').find('span.err2').hide();
				$(this).closest('.row').find('input:password').removeClass('invalid');
			} else
			{
				$(this).parents(".row").find("span.valid").hide();
			}
		}
		if(pwd.val() == confirm.val())
		{
			confirm.parents(".row").find("span.valid").css("display", "inline-block");
			confirm.parents(".row").find('input:text').removeClass('invalid');
			confirm.parents(".row").find('span.err1').hide();
			confirm.parents(".row").find('span.err2').hide();
		} else
		{
			confirm.parents(".row").find("span.valid").hide();
		}
	});
	
	
	$('select').on('change', function() {
		if($(this).val()!="")
		{
			$(this).parents(".row").find("span.valid").css("display", "inline-block");
			$(this).closest('.row').find('.err1').hide();
			$(this).closest('.row').find('.err2').hide();
			$(this).closest('.row').find('.customSelect').removeClass('invalid');
		} else
		{
			$(this).parents(".row").find("span.valid").hide();
		}
	});
	
	$('select').customSelect();
	
	$('#register a.btnSubmit').on('click', function(){
		var frm = $('#register form.register');
		$('.invalid', frm).removeClass('invalid');
		$('.err1,.err2',frm).hide();
		var isValid = true;
		$('input:text',frm).each(function(){
			if($.trim($(this).val()) == ""){
				$(this).closest('.row').find('.err1').show();
				$(this).closest('.row').find('input:text').addClass('invalid');
				isValid = false;
			}else if ($(this).hasClass("handle") && $.trim($(this).val()) == "user01") {
				$(this).closest('.row').find('.err2').show();
				$(this).closest('.row').find('input:text').addClass('invalid');
				$(this).closest('.row').find('span.valid').hide();
				isValid = false;
			}
		});
		$('select',frm).each(function(){
			if($.trim($(this).val()) == ""){
				$(this).closest('.row').find('.err1').show();
				$(this).closest('.row').find('.customSelect').addClass('invalid');
				isValid = false;
			}
		});
		
		$('input.email:text',frm).each(function(){
			if($.trim($(this).val()) == "")
			{
				$(this).closest('.row').find('.err1').show();
				$(this).closest('.row').find('input.email:text').addClass('invalid');
				isValid = false;
			}
			else if(!isValidEmailAddress($(this).val())){
				$(this).closest('.row').find('.err2').show();
				$(this).closest('.row').find('input.email:text').addClass('invalid');
				isValid = false;
			}
			
		});
		
		$('input:checkbox',frm).each(function(){
			if(!$(this).is(':checked')) {
				$(this).closest('.row').find('.err1').show();
				isValid = false;
			}
		});
		
		
		$('input.pwd:password',frm).each(function(){
			if($(this).val() == ""){
				$(this).closest('.row').find('.err1').show();
				$(this).closest('.row').find('input:password').addClass('invalid');
				isValid = false;
			}else if($(".strength .field.red", frm).length > 0){
				frm.find(".err2.red").show();
				$(this).closest('.row').find('.err2').show();
				$(this).closest('.row').find('input:password').addClass('invalid');
				isValid = false;
			}
			if($('input.pwd:password',frm).val() != $('input.confirm:password',frm).val()) {
				$('input.confirm:password').closest('.row').find('.err2').show();
				$('input.confirm:password').closest('.row').find('input:password').addClass('invalid');
				isValid = false;
			}
			else if($('input.confirm:password',frm).val() == "" ) {
				$('input.confirm:password').closest('.row').find('.err1').show();
				$('input.confirm:password').closest('.row').find('input:password').addClass('invalid');
				isValid = false;
			}
			
		});
		
		
		
		if(isValid && $('#register a.btnSubmit').html() == 'Sign Up' ){
			$('#register a.btnSubmit').html('Please Wait');
			
			$.post( ajaxUrl+'?action=post_register', { 
			firstName: $('#registerForm input.firstName').val(),
			lastName: $('#registerForm input.lastName').val(), 
			handle: $('#registerForm input.handle').val(),
			country: $('#registerForm select#selCountry').val(),			
			email: $('#registerForm input.email').val() ,
			password : $('#registerForm  input.pwd').val() 
			},function( data ) {
				if ( data.code == "200" ) {
					$('.modal').hide();
					$("#thanks h2").html('Thanks for Registering');
					$("#thanks p").html('We have sent you an email with a activation instructions.<br>If you do not receive that email within 1 hour, please email <a href="mailto:support@topcoder.com">support@topcoder.com</a>');
					showModal('#thanks'); 
					$('#registerForm .invalid').removeClass('invalid');
					$('#registerForm .valid').removeClass('valid');
					$('.err1,.err2',frm).hide();
					
				}
				else{
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
	
	 $('#login a.btnSubmit').on('click', function(){
		var frm = $(this).closest('form.login');
		$('.invalid', frm).removeClass('invalid');
		$('.err1,.err2',frm).hide();
		var isValid = true;
		$('input:password',frm).each(function(){
			if($(this).val() == ""){
				$(this).closest('.row').parent().find('.err2').show();
				$(this).closest('.row').find('input:password').addClass('invalid');
				isValid = false;
			}
		});
		
		if (isValid){
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

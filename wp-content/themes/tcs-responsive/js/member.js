$(document).ready(function(){
/* About Us page*/
        $('.teamView .showMore').on('click',function(){
                $('.members').css('height','auto');
                $(this).closest('.actions').hide();
        });
/*Contact Us page*/
	$('.contact .btnSubmit').click(function(){
		var frm = $(this).closest('.contactForm');
		$('.error', frm).removeClass('error');
		$('.errormsg',frm).hide();
		var isValid = true;
		$('input:text, .textarea',frm).each(function(){
			if($.trim($(this).val()) == ""){
				$(this).closest('.row').addClass('error');
				isValid = false;
			}
		});
		
		$('#ea',frm).each(function(){
			if(($.trim($(this).val()) == "") ||  ($.trim($(this).val()).match(/^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/g) === null)){
				$(this).closest('.row').addClass('error');
				isValid = false;
			}
		});
		
		if(!isValid){
			$('.errormsg',frm).fadeIn();
		}else{
			$('#contactForm').submit();
		}
	});
	
	$('.contact .contactForm input, .contact .contactForm textarea').focus(function(){
		$(this).closest('.row').removeClass('error');
		var frm = $(this).closest('.contactForm');
		if($('.error',frm).length <=0){
			$('.errormsg',frm).hide();
		}
	});
	
	
	
	var $window = $(window); 
	 function checkWidth() {
        var windowsize = $window.width();
		 
        if (windowsize < 1019) {
           	
			$(".contact .trackInfo").click(function(){
			  var thisId = $(this).attr('id');
		  	  $( ".contactInfo > .container > .descBox" ).each(function( index ) { 
				  $(this).attr('class', 'descBox');
				  $("#"+thisId+'Box').addClass("activeBox");
				});
			   
			  $('html, body').animate({
					scrollTop: $("#hero").offset().top
				}, 500);
		  });
			
		}else{
			  
			$(".contact #register").hover(function(){
				  $("#hero .point").animate({ "left": "-=340px" }, 200);
			  },function(){
				  $("#hero .point").animate({ "left": "+=340px" }, 200);
			  });
		  
		  $(".contact #support").hover(function(){
			  $("#hero .point").animate({ "left": "+=340px" }, 200);
		  },function(){
			  $("#hero .point").animate({ "left": "-=340px" }, 200);
		  });
		  
		  $(".contact .trackInfo").hover(function(){
			  var thisId = $(this).attr('id');
		  	  $( ".contactInfo > .container > .descBox" ).each(function( index ) { 
				  $(this).attr('class', 'descBox');
				  $("#"+thisId+'Box').addClass("activeBox");
				}); 
			  
		  });
			
		}
    } 
    checkWidth(); 
    $(window).resize(checkWidth);
})


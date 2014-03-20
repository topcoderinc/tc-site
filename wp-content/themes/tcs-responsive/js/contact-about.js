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
    
   
    
    var $window = $(document); 
     function checkWidth() {
        var windowsize = $window.width();
         
        if (windowsize < 1019) {
            $("#hero .point").animate({ "left": "50%" }, 200);
            
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
                if($(window).width() > 1019){
                    $("#hero .point").animate({ "left": "165.5px" }, 200);
                }
              },function(){
                if($(window).width() > 1019){
                  $("#hero .point").animate({ "left": "505.5px" }, 200);
                }
              });
          
            $(".contact #support").hover(function(){
                if($(window).width() > 1019){
                    $("#hero .point").animate({ "left": "845.5px" }, 200);
                }
            },function(){
                if($(window).width() > 1019){
                    $("#hero .point").animate({ "left": "505.5px" }, 200);
                }
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


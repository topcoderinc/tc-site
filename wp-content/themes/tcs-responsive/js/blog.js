$(document).ready(function() {	
	/* swipe slider for category widget */
	window.mySwipe = $('#mySwipe').Swipe(
		{
			callback: function(pos) {
				var n = $( "#mySwipe .swipeNavWrapper a" ).length;
				var index = pos % n;
				$("#mySwipe .swipeNavWrapper a").removeClass("on");
				$("#swipeNav"+index).addClass("on");
			}
		}
	).data('Swipe');
	
	/* swipe nav button */
	$("#mySwipe .swipeNavWrapper a").click(function(){
		var id = $(this).attr("id");
		var index = parseInt(id.replace("swipeNav",""));
		mySwipe.slide(index);
	});

	/* show more blog post function on blog page */
	$("#showMoreBlogPost").click(function(){
		var action = "get_blog_ajax";
		var page = $(this).parent().parent().find(".pageNo").val();
		var catId = $(this).parent().parent().find(".catId").val();
		var searchKey = $(this).parent().parent().find(".searchKey").val();
		page++;
		
		$(this).hide();
		$(this).parent().find(".morePostLoading").css("display","inline-block");
		$.ajax({
			type: "GET",
			context: this,
			url: ajaxUrl,
			data: {"action":action,"page":page,"catId":catId,"searchKey":searchKey},
			dataType: 'html',
			success: function(data) {
				if($.trim(data)!="") {
					$(this).parent().parent().find(".blogsWrapper").append(data);
					$(this).parent().parent().find(".pageNo").val(page);
					$(this).parent().find(".morePostLoading").hide();
					$(this).show();
				}
				else {
					$(this).parent().find(".morePostLoading").hide();
					$(this).hide();
					$(this).parent().find(".noMorePostExist").css("display","inline-block");
				}
			},
			error: function() {
				alert('error');
			}
		});
	});
	
	
	/* show more popular post function on popular post widget */
	$("#popularShowMore").click(function(){
		var action = "get_popular_ajax";
		var page = $(this).parent().parent().find(".pageNo").val();
		var postPerPage = $(this).parent().parent().find(".popularPostPage").val();
		page++;
		
		$(this).hide();
		$(this).parent().find(".morePostLoading").css("display","inline-block");
		$.ajax({
			type: "GET",
			context: this,
			url: ajaxUrl,
			data: {"action":action,"page":page,"posts_per_page":postPerPage},
			dataType: 'html',
			success: function(data) {
				if($.trim(data)!="") {
					$(this).parent().parent().find(".relatedContentList").append(data);
					$(this).parent().parent().find(".pageNo").val(page);
					$(this).parent().find(".morePostLoading").hide();
					$(this).show();
				}
				else {
					$(this).parent().find(".morePostLoading").hide();
					$(this).hide();
					$(this).parent().find(".noMorePostExist").css("display","inline-block");
				}
			},
			error: function() {
				alert('error');
			}
		});
	});
	
	/* subscribe function on subscribe widget */
	$(".subscribeButton").click(function(){
		var email = $(this).parent().parent().find(".subscribeInput").val();
		$(this).parent().parent().find(".errorInput").html("");
		email = $.trim(email);
		
		if(email=="") {
			$(".subscribeBox .errorInput").html("Please fill the email!");
		}
		else if(!validateEmail(email)) {
			$(".subscribeBox .errorInput").html("Email is invalid email format!");
		}
		else {
			$(".subscribeBox form").submit();
			
			/* $.ajax({
				type: "POST",
				context: this,
				url: ajaxUrl,
				data: {action:"subscribe_ajax","email":email},
				dataType: 'json',
				success: function(data) {
					$(this).parent().parent().find(".subscribeInput").hide();
					$(this).parent().parent().find(".errorInput").hide();
					$(this).parent().parent().find(".subscribeSuccess").show();
					$(this).parent().hide();
				},
				error: function() {
					alert('error');
				}
			});
			*/
		}
	});
})

/* validate email */
function validateEmail(email)
{
	var reg = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/
	if (reg.test(email)){
		return true; 
	}
	else{
		return false;
	}
} 

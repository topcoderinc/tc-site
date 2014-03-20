$(document).ready(function(){
	// Initialize member details
	if($('.tcssoUsingJS').length > 0){
		var tcsso = getCookie('tcsso');
		if(tcsso){
 			$('.actionLogout').attr('href', 'javascript:;');
 			$('.loginLink, .linkLogin, .btnRegister').addClass('hide').hide();
			$('.logoutLink, .linkLogout, .userDetailsWrapper').removeClass('hide').show();
			$('.headerTopRightMenuLink.logIn a').unbind('click');
			$('.headerTopRightMenuLink.logIn a').text("Log Out").removeClass("actionLogin").addClass("actionLogout");
 			var tcssoValues = tcsso.split("|");
 			$.getJSON("http://community.topcoder.com/tc?module=BasicData&c=get_handle_by_id&dsid=30&uid=" + tcssoValues[0] + "&json=true", function(data) {			
				var handle = data['data'][0]['handle'];
				$('.userDetails .coder').text(handle);
				$.getJSON(ajaxUrl, {"action": "get_member_profile", "handle": handle}, function(data) {				
					if(data['photoLink']){
						$('.userPic img').attr('src', 'http://community.topcoder.com' + data['photoLink']);
					} else {
						$('.userPic img').attr('src', 'http://community.topcoder.com/i/m/nophoto_login.gif');
					}
					$('.userDetails .coder').attr('href', $('.userDetails .coder').attr('href') + handle);
					$('.userDetails .link').attr('href', $('.userDetails .link').attr('href') + handle);
					$('.action .profileLink').attr('href', $('.action .profileLink').attr('href') + handle);
					var color = '';
					if(data['ratingsSummary']){
						var maxRating = 0;
						for(var i=0; i<data['ratingsSummary'].length; i++){
							if(maxRating<data['ratingsSummary'][i]['rating']){
								maxRating = data['ratingsSummary'][i]['rating'];
								color = data['ratingsSummary'][i]['colorStyle'].split(": ")[1];
							}
						}
					} else if (data['isPM']==true){
						color = '#FF9900';
					}
					$('.userDetails .coder').attr('style', 'color: '+color);
					var userPofileUrl = wpUrl+'/member-profile/'+handle;
					$('.userDetails').prepend('<a class="tc_coder coder" href="'+userPofileUrl+'" style="color:'+color+'">'+handle+'</a>');
					$('.myProfileLink, .profileLink').attr('href', userPofileUrl);
					$('.userDetails .country').text(data['country']);
					$('.userDetails .memberSince').text(data['memberSince'].split(" ")[0].split(".")[2]);
					$('.userDetails .memberEarning').text("$"+data['overallEarning']);
				});
			});
			
			// Clear local tcsso cookie on logout
			$('.logoutLink, .actionLogout').click(function() {
        document.cookie = 'tcsso=; path=/; domain=.topcoder.com; expires=' + new Date(0).toUTCString();
        location.href = location.href;
    	});
 		}
	}
	$('#login input').keyup(function (e) {
	  if(e.keyCode == 13){
	    $('#login a.btnSubmit').click();
	  }
	});
});

function getCookie(cname)
{
	var name = cname + "=";
	var ca = document.cookie.split(';');
	for(var i=0; i<ca.length; i++) {
		var c = $.trim(ca[i]);
		if (c.indexOf(name)==0) return c.substring(name.length,c.length);
	}
	return "";
}
$(document).ready(function() {
    var tcjwt = getCookie('tcjwt');
    if(tcjwt){
      if ($('.loading').length <= 0) {
         $('body').append('<div class="loading">Loading...</div>');
      } else {
         $('.loading').show();
      }
      $.getJSON(ajaxUrl, {
	        "action": "get_challenge_terms",
	        "challengeId": challengeId,
	        "role": "Submitter",
	        "jwtToken": tcjwt.replace(/["]/g, "")
	    }, function(data) {
	    	   if(data["terms"]){
	    	   	$(".formContent .terms, .formContent .termTable").show();
	    	   	$(".formContent .warning").hide();
	         var terms = data["terms"];
	         var allAgreed = true;
	         for(var i=0; i< terms.length; i++){
	             var agreed = terms[i]["agreed"]
	             if(agreed === false){
	                 allAgreed = false;
	             }
	             var $tr = $("<tr>", {class: i % 2 == 1 ? "alt" : ""});
	             var $td1 = $("<td>").text(terms[i]["title"]).append(" (").append($("<a>", {target: "_blank", href: terms[i]["url"] ? terms[i]["url"] : "javascript:;", }).text(agreed ? "view" : "view and agree")).append(")");
	             var $td2 = $("<td>").append($("<span>", {class: "status "+(agreed === true? "complete" : "required")}).text(agreed === true? "Completed" : "Required"));       
	             $tr.append($td1).append($td2);           
	             $(".termTable tbody").append($tr);
	         }
	         if(allAgreed === true){
	             $(".termsBtnRegister").removeClass("hide");    
	         } else {
	             $(".termsBtnRegister").addClass("hide");    	
	        	}
         } else {
         	$(".formContent .terms, .formContent .termTable").hide();
         	$(".formContent .warning").text(data["error"]["details"]);
         	$(".formContent .warning").show();
         }
         $('.loading').hide();
	    });
    }
});
/**
 * Challenge result functions
 */
$(document).ready(function () {
  $(".link").click(function(){
      if($(this).attr("href") === "#winner"){
        updateTabForResults();
      } else {
        updateTabForNonResults();
      }
    });   
    $('a[href="' + getAnchor(location.href) + '"]').click();
    
    var tcjwt = getCookie('tcjwt');

    if (challengeType) {
      switch (challengeType) {
        case "develop":
          initDevelopResult(tcjwt);
        case "design":
          initDesignResult(tcjwt);
      }
    }
});

function initDevelopResult(tcjwt) {

}

function initDesignResult(tcjwt) {
  $(".challenge-detail").addClass("design");
}
  
function updateTabForResults(){
  $(".challenge-detail").addClass("view-challenge-result");
  $(".container .rightSplit.grid-3-3").removeClass("grid-3-3");
  $(".columnSideBar").hide();
  $(".topRightTitle").insertAfter(".designSecondTabNav");
}
  
function updateTabForNonResults(){
  $(".challenge-detail").removeClass("view-challenge-result");
  $(".container .rightSplit").addClass("grid-3-3");
  $(".columnSideBar").show();
  $(".topRightTitle").insertBefore(".columnSideBar");
}
/**
 * Terms functions
 */
appChallengeTerms = {
  init: function () {
    var tcjwt = getCookie('tcjwt');

    if (termType) {
      switch (termType) {
        case "list":
          app.initList(tcjwt);
        case "detail":
          app.initDetail(tcjwt);
      }
    }

  },

  initDetail: function (tcjwt) {
    if (app.isLoggedIn()) {
      app.setLoading();
      $.getJSON(ajaxUrl, {
        "action": "get_challenge_term_details",
        "termId": termsOfUseID,
        "jwtToken": tcjwt.replace(/["]/g, "")
      }, function (data) {
        if (data.title != null && data.title != 'undefined') {
          $(".formContent .terms").show();
          $(".formContent .warning").hide();
          $(".overviewPageTitle").text(data["title"]);
          $(".termsText").html(data["text"]);
        } else {
          $(".formContent .terms").hide();
          $(".formContent .warning").text(data["error"]);
          $(".formContent .warning").show();
        }
        $('#agree').change(function () {
          if ($(this).prop('checked')) {
            $(this).closest('section.agreement').removeClass('notAgreed');
          } else {
            $(this).closest('section.agreement').addClass('notAgreed');
          }
        });
        $("#termSubmit").click(function () {
          if ($(this).parents(".notAgreed").length === 0) {
            app.setLoading();
            $.getJSON(ajaxUrl, {
              "action": "agree_challenge_terms",
              "termId": termsOfUseID,
              "jwtToken": tcjwt.replace(/["]/g, "")
            }, function (data) {
              window.location = siteURL + "/challenge-details/terms/" + challengeId + "?challenge-type=" + challengeType;
              $('.loading').hide();
            });
          }
        });

        // This is absolutly a terrible thing to do but the only way to achieve what tony wants
        // When the term api is updated to tell if we are using docusign this will go away
        // and die a very misserable life
        if (data.title == "Appirio NDA v1") {
          $('.agree-label').hide();
          $('.agreement').removeClass('notAgreed');
        }

        $('.loading').hide();
      });
    } else {
      $('.actionLogin').click();
    }
  },

  initList: function (tcjwt) {
    if (app.isLoggedIn()) {
      app.setLoading();
      $.getJSON(ajaxUrl, {
        "action": "get_challenge_terms",
        "challengeId": challengeId,
        "role": "Submitter",
        "jwtToken": tcjwt.replace(/["]/g, "")
      }, function (data) {
        if (data["terms"]) {
          $(".formContent .terms, .formContent .termTable").show();
          $(".formContent .warning").hide();
          var terms = data["terms"];
          var allAgreed = true;
          for (var i = 0; i < terms.length; i++) {
            var agreed = terms[i]["agreed"]
            if (agreed === false) {
              allAgreed = false;
            }
            var $tr = $("<tr>", {class: i % 2 == 1 ? "alt" : ""});
            var $td1 = $("<td>").text(terms[i]["title"]).append(" (").append($("<a>", {target: "_blank", href: siteURL + "/challenge-details/terms/detail/" + terms[i]["termsOfUseId"] + "?contestID=" + challengeId + "&challenge-type=" + challengeType}).text(agreed ? "view" : "view and agree")).append(")");
            var $td2 = $("<td>").append($("<span>", {class: "status " + (agreed === true ? "complete" : "required")}).text(agreed === true ? "Completed" : "Required"));
            $tr.append($td1).append($td2);
            $(".termTable tbody").append($tr);
          }
          if (allAgreed === true) {
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

        $(".termsBtnRegister").click(function () {
          window.location = siteURL + "/challenge-details/register/" + challengeId  + "?type=" + challengeType + "&nocache=true";
        });
      });
    } else {
      $('.actionLogin').click();
    }
  }
};

$.extend(app, appChallengeTerms);

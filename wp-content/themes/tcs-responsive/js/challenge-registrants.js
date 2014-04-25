var loaded = false;

/* format challenge date */
formatChallengeDate = function(date) {
    return moment(date).format('MMM D, YYYY HH:mm Z');
}

/* create section row for mobile view */
createSectionRow = function(label, value) {
    var sectionRow = $('<div>', {class : "registrantSectionRow"});
	sectionRow.append('<div class="registrantLabel">' + label + '</div>');
	sectionRow.append('<div class="registrantField">' + value + '</div>');
	return sectionRow;
}

// update the rating in page challenge-details to the most uptodate rating of registrant in the specified track
$(document).ready(function() {
    $(".link").click(function(){
        if($(this).attr("href") === "#viewRegistrant") {
		    if (loaded) {
			    return false;
			}
			var challenge_name = challengeName.replace(/ /g, '_');
			challenge_name = challenge_name.toLowerCase();
		
            var table = $("table.registrantsTable");
	        var mobileDiv = $('div.registrantsTable');
	        var clearDiv = $('<div>', {class : "clear"});
	
	        $.each(registrant_data, function(i, val) {	    
                var handleLink = siteurl + '/member-profile/' + val['handle'] + '?tab=' + challengeType + '&challengeType=' + challenge_name;
		        var handleText = '<span><a href="' + handleLink + '" style="' + val['colorStyle'] + '">' + val['handle'] + '</a></span>';

		        var $tr = $('<tr>');
		        var handleColumn = $('<td>', {class : "handleColumn"});

		        var $div = $('<div>', {class: "registrantSection"});
		        var handleSectionRow = $('<div>', {class : "registrantSectionRow registrantHandle"});

	            // table
		        handleColumn.html(handleText);
		        $tr.append(handleColumn);

                // mobile section
                handleSectionRow.html(handleText);
                $div.append(handleSectionRow);

                if (challengeType != 'design') {
                    var ratingText = $('<span>');
                    var ratingSectionText = ratingText.clone();
					val.rating = val.rating ? 'fetching...' : 'not rated';
                    ratingText.html(val.rating);
                    ratingSectionText.html(val.rating);

                    var param = {};
                    param.action = 'get_member_profile';
                    param.handle = val['handle'];
			
                    $.ajax({
                        url: ajaxUrl,
                        data: param,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
				            $.each(data['ratingSummary'], function(k, v) {
					            if (challengeName.indexOf(v['name']) == 0) {
								    ratingText.attr('style', val['colorStyle']);
									ratingSectionText.attr('style', val['colorStyle']);
						            ratingText.html(v['rating']);
						            ratingSectionText.html(v['rating']);
						            return false;
						        }
					        });
                        }
                    });
			
			        var ratingColumn = $('<td>', {class : "ratingColumn"});
			        ratingColumn.html(ratingText);
			        $tr.append(ratingColumn);
			
			        var reliabilityColumn = $('<td>', {class : "reliabilityColumn"});
			        reliabilityColumn.html(val.reliability);
			        $tr.append(reliabilityColumn);

			        var ratingSection = $('<div>', {class : "registrantSectionRow"});
	                ratingSection.append('<div class="registrantLabel">Rating:</div>');
			        var ratingField = $('<div>', {class : "registrantField"});
			        ratingField.html(ratingSectionText);
			        ratingSection.append(ratingField);
			        $div.append(ratingSection);
			        $div.append(clearDiv);
			
			        var reliabilitySection = createSectionRow("Reliability:", val.reliability);
			        $div.append(reliabilitySection);
			        $div.append(clearDiv);
                }

		        var regDate = formatChallengeDate(val.registrationDate);
                var regDateColumn = $('<td>', {class: "regDateColumn"});		
                regDateColumn.html(regDate);
                $tr.append(regDateColumn);

		        var regDateSection = createSectionRow("Registration Date:", regDate);
		        $div.append(regDateSection);
		        $div.append(clearDiv);

                var subDateColumn =  $('<td>', {class: "subDateColumn"});
		        var subDate;
                if (val.lastSubmissionDate) {
		            subDate = formatChallengeDate(val.lastSubmissionDate);            
                } else {
		            subDate = '--';
                }
		        subDateColumn.html(subDate);
		        $tr.append(subDateColumn);

		        var subDateSection = createSectionRow("Submission Date:", subDate);
		        $div.append(subDateSection);
		        $div.append(clearDiv);

		        $('tbody', table).append($tr);
		        mobileDiv.append($div);
	        });
			loaded = true;
        }
    });   
    $('a[href="' + getAnchor(location.href) + '"]').click();
});

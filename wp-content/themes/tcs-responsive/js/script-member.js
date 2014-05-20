var tc_rating_dev_chart = [];

/* chart update using data via ajax */
tc_rating_dev_chart.drawChart = function(challengetype) {
    var handle = $('.coderProfile .handle a').text();
    $('.chartWrap').addClass('loadingChart');
    if (xhr != "") {
        xhr.abort();
    }
    xhr = $.ajax({
        type: "POST",
        url: ajaxUrl + "?action=ratings_dev_chart_data",
        data: {
            'handle': handle,
            'challengetype': challengetype
        },
        success: function(data) {
            if (data.indexOf('<') > -1) {
                return false;
            }
            var cdata = JSON.parse(data);
            currentChart.series[0].update({
                data: cdata.hseries
            });
            currentDistChart.series[0].update({
                data: cdata.dseries
            });
        },
        complete: function() {
            $('.chartWrap').removeClass('loadingChart');
        }
    });
}


var coder = {
    // binding member module events
    initMemberEvents: function() {
    	
        // chart switch
    	$('#profileInfo').on('click', '.chartTypeSwitcher .btnHistory', function(e) {
            e.preventDefault();
            $('.isActive', $(this).closest('.chartTypeSwitcher')).removeClass('isActive');
            $(this).addClass('isActive');
            $('.ratingChart.distributionType').hide();
            $('.ratingChart.historyType').show();

            if (typeof(currentChart) != 'undefined') {
                currentChart.reflow();
            }
            if (typeof(currentDistChart) != 'undefined') {
                currentDistChart.reflow();
            }
        })
        $('#profileInfo').on('click', '.chartTypeSwitcher .btnDist', function(e) {
            e.preventDefault();
            $('.isActive', $(this).closest('.chartTypeSwitcher')).removeClass('isActive');
            $(this).addClass('isActive');
            $('.ratingChart.distributionType').show();
            $('.ratingChart.historyType').hide();

            if (typeof(currentChart) != 'undefined') {
                currentChart.reflow();
            }
            if (typeof(currentDistChart) != 'undefined') {
                currentDistChart.reflow();
            }
        })

        // switch tabs 
        $('#profileInfo').on('click','.subTrackTabs .tabNav a', function(e) {
            if($(this).closest('.tabNav').hasClass('alt')){
            	return;
            }
            if ($(this).hasClass('link')) {
                window.location = $(this).attr('href');
                return;
            }
            tc_rating_dev_chart.drawChart($(this).attr('href'));

            var currentTrack = $.trim($(this).text());
            var currentInfo = coderData.Tracks[currentTrack];
            var subtab = $(this).closest('.subTrackTabs');
            if ($('.detailedRating .row').length > 0) {
                $('.detailedRating .row').each(function() {
                    var fieldId = $('.fieldId', $(this)).val();
                    if (typeof(currentInfo[fieldId]) == 'undefined') {
                        if ($('.val a', $(this)).length > 0) {
                            $('.val a', $(this)).text("N/A");
                        } else {
                            $('.val', $(this)).text("N/A");
                        }
                    } else {
                        var cval = currentInfo[fieldId];
                        if (fieldId == "reviewerRating" && parseFloat(currentInfo[fieldId]) >= 0) {
                            cval = parseFloat(currentInfo[fieldId]).toFixed(2);
                        }
                        if ($('.val a', $(this)).length > 0) {
                            $('.val a', $(this)).text(cval);
                        } else {
                            $('.val', $(this)).text(cval);
                        }

                    }

                })


            }

            $('.trackName', subtab).text(currentTrack);

            $('.rating', subtab).text(currentInfo.rating);
            $('.rating', subtab).attr('class', '').addClass('rating').addClass(app.getColor(currentInfo.rating));


            if ($('.ratingTable', subtab).length > 0) {
                $('.ratingTable .valId', subtab).each(function() {
                    var valId = $(this).val();
                    $('span', $(this).closest('td')).text(currentInfo[valId]);
                })
            }
            
            //style active tab
            $('.isActive',$(this).closest('ul')).removeClass('isActive');
            $(this).addClass('isActive');
            
            e.preventDefault();
        });

        // tab navs


       

        //pager
        $('.pager .nextLink').on(ev, function() {
            var pager = $(this).closest('.pager');
            var nextLink = $('.isActive', pager).next('.pageLink');
            if (nextLink.length > 0) {
                nextLink.trigger('click');
            }
        });

        $('.pager .prevLink').on(ev, function() {
            var pager = $(this).closest('.pager');
            var prevLink = $('.isActive', pager).prev('.pageLink');
            if (prevLink.length > 0) {
                prevLink.trigger('click');
            }
        });

        $('.pager .pageLink').on(ev, function(e) {
            var pager = $(this).closest('.pager');
            $('.prevLink', pager).show();
            $('.nextLink', pager).show();
            var page = $(this).attr('href').replace(/#/g, '');
            $('.isActive', $(this).closest('.pager')).removeClass('isActive');
            $(this).addClass('isActive');
            $('.page').hide();
            $('.page' + parseInt($(this).text().trim())).show();
            if ($('.pageLink:last', pager).hasClass('isActive')) {
                $('.nextLink', pager).hide();
            }
            if ($('.pageLink:first', pager).hasClass('isActive')) {
                $('.prevLink', pager).hide();
            }
            e.preventDefault();
        });

        // view switch
        $('.viewSwitch a').on(ev, function(e) {
            var id = $(this).attr('href');
            $('.ratingViews #graphView').hide();
            $('.ratingViews #tabularView').hide();
            $('.ratingViews ' + id).fadeIn();
            e.preventDefault();
            $('.isActive', $(this).closest('.viewSwitch')).removeClass('isActive');
            $(this).addClass('isActive');
            if (typeof(currentChart) != 'undefined') {
                currentChart.reflow();
            }
            if (typeof(currentDistChart) != 'undefined') {
                currentDistChart.reflow();
            }
        });

        
    	// track swith using ajax
    	$('.trackSwitch a').on('click',function(e){    		
    		if($(this).hasClass('isActive') || $(this).hasClass('disabled')){
    			return false;
    		}
    		var href = $(this).attr('href');
    		updateRequestData(href);
    		
    		if(reqProfileData.tab==="design"){
    			$(this).closest('.actions').addClass('trackdesign');
    		}else{
    			$(this).closest('.actions').removeClass('trackdesign');
    		}

			$('.viewSwitch  .isActive').removeClass('isActive');
			$('.viewSwitch  #tableButton').addClass('isActive');
    		
    		$('.isActive',$(this).closest('.trackSwitch')).removeClass('isActive');
    		$(this).addClass('isActive');
    		e.preventDefault();
    	});
    	$('#profileInfo').on('click','#algorithm .subTrackTabs a, #marathon .subTrackTabs a',function(e){
    		if($(this).hasClass('isActive')){
    			return false;
    		}
    		var href = $(this).attr('href');
    		updateRequestData(href);
    		
    		
    		$('.isActive',$(this).closest('.tabNav')).removeClass('isActive');
    		$(this).addClass('isActive');
    		e.preventDefault();
    	});
    },
    // forum function
    forum: {
        // adding records to forum list
        populate: function(dataUrl) {
            if (xhr != "") {
                xhr.abort();
            }
            app.setLoading();
            xhr = $.getJSON(dataUrl, '', function(data) {
                var count = 0;
                $('.forumPosts .forumList').html(null);
                $.each(data, function(key, rec) {
                    var post = $(memBluprints.forum).clone();
                    post.addClass('post' + rec.type);
                    $('.postTitle', post).html(rec.title);
                    $('.postAuthor', post).html(rec.postedBy);
                    $('.postBody', post).html(rec.postContent);
                    $('.postCat', post).html(rec.postCategory);
                    $('.postedAt', post).html(rec.postedOn);
                    $('.nThread em', post).html(rec.threads);
                    $('.nMsg em', post).html(rec.messages);

                    $('.forumPosts .forumList').append(post);
                    count += 1;
                    if (count >= postPerPage) {
                        $('.loading').hide();
                        return false;
                    }
                });
                $('.loading').hide();
            });
        }
    }
};

var carouseEl = false;
var carouselDelay = 2000;
//design carousel
app.initDesignCarousel = function(){
	if(carouseEl.defs != null){
		carouseEl.clearTimer();
		this.init();
	}
	
	
	if ($('.submissionCarousel').length > 0) {
	    var len = $('.submissionCarousel .slider .slide').length;
	    $('.submissionCarousel .slider').iCarousel({
	        slides: 5,
	        dir: 'rtl',
	        easing: 'ease-in-out',
	        slidesSpace: 190,
	        mouseWheel: false,
	        onAfterChange: function() {
	        	if($(this.el).is(':visible')){
		        	carouseEl = this;
	        	
		            var aIdx = this.defs.slide;
		            $('.ratingInfo .slider-pager .isActive').removeClass('isActive');
		            $('.ratingInfo .slider-pager li:eq(' + aIdx + ') a').addClass('isActive');
		            $('.submissonInfo .submissionThumb img').attr('src', $('img', this.defs.currentSlide).attr('src') + '?sbt=full');
	
		            var desc = $('.comptetionData', this.defs.currentSlide);
		            $('.winInfo .contestTitle').html('<i></i>' + $('.name', desc).val());
		            $('.winInfo .contestTitle').attr('href', $('.challengeLink', desc).val());
		            $('.winInfo .prizeAmount .val').html('<i></i>' + $('.prize', desc).val());
		            $('.winInfo .submittedOn .time').html($('.submiissionDate', desc).val());

	        	}
	        }
	    });
	    window.setTimeout(function() {
	        $('.submissionCarousel .slider').trigger('iCarousel:pause');
	        $('.ratingInfo .slider-pager li:eq(0) a').addClass('isActive');
	    }, carouselDelay);
	    var pagination = $("<div class='slider-ctrl'><ul class='slider-pager'></ul></div>");
	    for (var i = 0; i < len; i++) {
	        $('.slider-pager', pagination).append('<li class="slider-pager-item"><a href="javascript:;" class="navDot"></a></li>');
	    }
	    $('.ratingInfo').append($(pagination));
	}
	
	//slider nav 
    $('.ratingInfo').off().on(ev, '.slider-ctrl a', function() {
        var idx = $(this).parent().index();
        var ctrl = $(this).closest('.slider-ctrl');
        $('.isActive', ctrl).removeClass('isActive');
        $(this).addClass('isActive');
        $('.submissionCarousel .slider').trigger('iCarousel:goSlide', [idx]);
        return false;
    });
}


// htmldata
var memBluprints = {
    forum: "<div class='post'>\
                <a href='#' class='thumb'></a>\
                <div class='head'>\
                    <a href='#' class='postTitle'></a>\
                    <span class='postedBy'>Last Post by: <a href='#' class='postAuthor'></a></span>\
                </div>\
                <div class='postBody'></div>\
                <div class='postInfo'>\
                    <div class='row'>\
                        <a href='#' class='postCat'></a>\
                        <span class='sep'></span><span class='postedAt'></span>\
                    </div>\
                    <div class='row'>\
                        <span class='info nThread'><em>8</em> Threads</span><span class='sep'></span><span class='info nMsg'><em>24</em> Messages</span>\
                    </div>\
                </div>\
            </div>"
};

app.getColor = function(score) {
    if (score < 900) return "coderTextGray";
    else if (score < 1200) return "coderTextGreen";
    else if (score < 1500) return "coderTextBlue";
    else if (score < 2200) return "coderTextYellow";
    else if (score >= 2200) return "coderTextRed";
}
var updateRequestData = function(url){
	var seachStr = url.substr(url.indexOf('?')+1);
	seachStr =  seachStr.split('&');
	
	var searchList = {};
	for(i=0;i<seachStr.length;i++){
		var en = seachStr[i];
		en=en.split('=');
		searchList[en[0]]=en[1];
	}
	
	var currTrack = "data/srm";

	if (searchList.tab == "algo") {
		currTrack = "data/srm";
		if(searchList.ct !== 'undefined' && searchList.ct === "marathon"){
			currTrack = "data/marathon";
		}
	} else if (searchList.tab == "develop") {
		currTrack = "develop";
	} else if (searchList.tab == "design") {
		currTrack = "design";
	}
	
	reqProfileData.track = currTrack;
	reqProfileData.activeTrack = searchList.activeTrack
	reqProfileData.tab = searchList.tab;
	reqProfileData.ct = searchList.ct;
	reqProfileData.renderBadges = "false";
	reqProfileData.href=url;
	app.ajaxProfileRequest();
}

// ajax profile request options
var reqProfileData = {
        "action": "get_template_part_by_ajax",
		"dataRequest":"false",
		"handle": basicCoderData.handle,
		"track": activeTrack,
		"tab": tab,
		"ct": currTab,
		"renderBadges": "true",
		"href":""
}

app.ajaxProfileRequest =function(){
	
	if(reqProfileData.href === ""){
		reqProfileData.href = window.location.href;
	}
	if (xhr != "") {
	    xhr.abort();
	}
	if(reqProfileData.renderBadges!=="true"){
		app.setLoading();
	}
	
	if (app.populateProfileTab()){
		return false;
	}
	
	xhr = $.ajax({
        type: "POST",
        url: ajaxUrl,
        data: reqProfileData,
        success: function(data){
        		$('.loading').hide();
        		if(reqProfileData.renderBadges==="true"){
        			$('#profileInfo').html(unescape(data));
        		}else{
        			$('#profileInfo .ratingInfo').html($('.ratingInfo',unescape(data)).html());
        		}
        		//store data to memory
        		if(reqProfileData.tab==="design"){
        			profileTabs.design = unescape(data);
        		}else if(reqProfileData.tab==="develop"){
        			profileTabs.develop = unescape(data);
        		}else if(reqProfileData.tab==="algo" && reqProfileData.ct === "marathon"){
        			profileTabs.marathon = unescape(data);
        		}else{
        			profileTabs.algo = unescape(data);
        		}
        		
        		// update style
        		$('.submissionCarousel .slider').trigger('iCarousel:pause');
        		if(reqProfileData.tab==="design"){
        			$('.dataTabs').addClass('designLayout');
        			app.initDesignCarousel();
        			
        		}else{
        			$('.dataTabs').removeClass('designLayout');
        		}
        		
        		$('.viewSwitch  .isActive').removeClass('isActive');
    			$('.viewSwitch  #tableButton').addClass('isActive');
        		
    			$('.trackSwitch .disabled').removeClass('disabled');
        		//window.location.href = reqProfileData.href;
        	},
    	complete: function(){
    		$('.loading').hide();
    	}
    });
        
}

app.populateProfileTab=function(){
	
	
	//store data to memory
	var tabData = "";
	if(reqProfileData.tab==="design"){
		tabData = profileTabs.design;
	}else if(reqProfileData.tab==="develop"){
		tabData = profileTabs.develop; 
	}else if(reqProfileData.tab==="algo" && reqProfileData.ct === "marathon"){
		tabData = profileTabs.marathon;
	}else if(reqProfileData.tab==="algo"){
		tabData = profileTabs.algo; 
	}
	if(tabData !== ""){
		$('#profileInfo .ratingInfo').html($('.ratingInfo',tabData).html());
		$('.loading').hide();
		

		// update style
		if(reqProfileData.tab==="design"){
			$('.dataTabs').addClass('designLayout');
			app.initDesignCarousel();
		}else{
			$('.dataTabs').removeClass('designLayout');
		}
		carouselDelay = 500;
		return true;
	}else{
		$('.trackSwitch a').addClass('disabled');
	}
	
	
	return false;
	
}

var profileTabs = {};
profileTabs.design = "";
profileTabs.develop = "";
profileTabs.algo = "";
profileTabs.marathon = "";

// extending base prototype
$.extend(app, coder);
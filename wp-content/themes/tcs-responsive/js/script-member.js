var tc_rating_dev_chart = [];

/* chart update using data via ajax */
tc_rating_dev_chart.drawChart = function(challengetype){
	var handle = $('.coderProfile .handle a').text();
	$('.chartWrap').addClass('loadingChart');
	if(xhr != ""){
		xhr.abort();
	}
	xhr = $.ajax({
	  type: "POST",
	  url: ajaxUrl+"?action=ratings_dev_chart_data",
	  data: {'handle':handle,'challengetype':challengetype},
	  success: function(data){
		if(data.indexOf('<')>-1){
			return false;
		}  
		var cdata = JSON.parse(data);
	  	currentChart.series[0].update({data:cdata.hseries});
		currentDistChart.series[0].update({data:cdata.dseries});		
	  },
	  complete: function(){
		  $('.chartWrap').removeClass('loadingChart');
	  }
	});
}

$(document).ready(function () {
  if($(".badgeGroups").length > 0){
    coder.initMemberBadges();
  }
});
 
var coder = {
	// binding member module events
    initMemberEvents: function() {
      
	
		// chart switch
		$('.chartTypeSwitcher .btnHistory').on('click',function(e){
			e.preventDefault();
			$('.isActive',$(this).closest('.chartTypeSwitcher')).removeClass('isActive');
			$(this).addClass('isActive');
			$('.ratingChart.distributionType').hide();
			$('.ratingChart.historyType').show();
			
			if(typeof(currentChart)!='undefined'){
				currentChart.reflow();
			}
			if(typeof(currentDistChart)!='undefined'){
				currentDistChart.reflow();
			}
		})
		$('.chartTypeSwitcher .btnDist').on('click',function(e){
			e.preventDefault();
			$('.isActive',$(this).closest('.chartTypeSwitcher')).removeClass('isActive');
			$(this).addClass('isActive');
			$('.ratingChart.distributionType').show();
			$('.ratingChart.historyType').hide();
			
			if(typeof(currentChart)!='undefined'){
				currentChart.reflow();
			}
			if(typeof(currentDistChart)!='undefined'){
				currentDistChart.reflow();
			}
		})
	
     	// switch tabs 
		$('.subTrackTabs .tabNav a').off().on(ev,function(e){
			//if($(this).hasClass('isActive')){
		//		return false;
			//}
			if($(this).hasClass('link')){
				window.location = $(this).attr('href');
				return;
			}
			tc_rating_dev_chart.drawChart($(this).attr('href'));
			
			var currentTrack = $.trim($(this).text());
			var currentInfo = coderData.Tracks[currentTrack];
			var subtab = $(this).closest('.subTrackTabs');
			if($('.detailedRating .row').length>0){
				$('.detailedRating .row').each(function(){
					var fieldId= $('.fieldId',$(this)).val();
					if(typeof(currentInfo[fieldId])=='undefined'){
						if($('.val a',$(this)).length>0){
							$('.val a',$(this)).text("N/A");
						}else{
							$('.val',$(this)).text("N/A");
						}
					}else{
						var cval = currentInfo[fieldId];
						if(fieldId=="reviewerRating" && parseFloat(currentInfo[fieldId])>=0){
							cval = parseFloat(currentInfo[fieldId]).toFixed(2);
						}
						if($('.val a',$(this)).length>0){
							$('.val a',$(this)).text(cval);
						}else{
							$('.val',$(this)).text(cval);
						}
						
					}
					
				})
				
				
			}
			
			$('.trackName',subtab).text(currentTrack);
			
			$('.rating',subtab).text(currentInfo.rating);
			$('.rating',subtab).attr('class','').addClass('rating').addClass(app.getColor(currentInfo.rating));
			
			
			if($('.ratingTable',subtab).length>0){
				$('.ratingTable .valId',subtab).each(function(){
					var valId = $(this).val();
					$('span',$(this).closest('td')).text(currentInfo[valId]);
				})				
			}
			/*activeTrack = $(this).attr('title');
			var trackData = buildRequestChartData(activeTrack);
			var alias = $('.alias',$(this).parent()).val();
			var trackDetailsData = statData.Tracks[alias];
			var rows = "";
			$.each(trackDetailsData, function(key, rec){
				rows += '<tr><td class="colDetails">'+key+'</td><td class="colTotal">'+rec+'</td></tr>';
			});
			$('.mainTabStream .ratingTable tbody').html($(rows).fadeIn());
			app.initZebra($('.mainTabStream .ratingTable'));
			
			$('.isActive',$(this).closest('nav')).removeClass('isActive');
			$(this).addClass('isActive');
			
			$('.subTrackTabs .head .trackName').html(alias);
			$('.subTrackTabs .head .rating').html(trackDetailsData.rating);
			
			$('.subTrackTabs .detailedRating .val').html(trackDetailsData.percentile);
			$('.subTrackTabs .fieldVolatility .val').html(trackDetailsData.volatility);
			$('.subTrackTabs .fieldRank .val').html(trackDetailsData.rank);
			$('.subTrackTabs .fieldCtryRank .val').html(trackDetailsData.countryRank);
			$('.subTrackTabs .fieldScRank .val').html(trackDetailsData.schoolRank);
			$('.subTrackTabs .fieldCompetitions .val').html(trackDetailsData.competitions);
			$('.subTrackTabs .fieldMaxRating .val').html(trackDetailsData.maximumRating);
			$('.subTrackTabs .fieldMinRating .val').html(trackDetailsData.minimumRating);
			$('.subTrackTabs .fieldRevRating .val').html(trackDetailsData.reviewerRating);
			*/
			e.preventDefault();
		});
		
		// tab navs
		
		
        $('.subTrackTabs .tabNav a').on(ev, function() {
            $('.isActive', $(this).closest('.tabNav')).removeClass('isActive');
            $(this).addClass('isActive');
        });       
		
		//pager
		$('.pager .nextLink').on(ev,function(){
			var pager = $(this).closest('.pager');
			var nextLink = $('.isActive',pager).next('.pageLink');
			if(nextLink.length> 0){
				nextLink.trigger('click');
			}
		});
		
		$('.pager .prevLink').on(ev,function(){
			var pager = $(this).closest('.pager');
			var prevLink = $('.isActive',pager).prev('.pageLink');
			if(prevLink.length> 0){
				prevLink.trigger('click');
			}
		});
		/*$('.pager .pageLink').on(ev,function(e){
			var pager = $(this).closest('.pager');
			$('.prevLink',pager).show();
			$('.nextLink',pager).show();
			var page = $(this).attr('href').replace(/#/g,'');
			var newUrl = url+ '?pageSize='+postPerPage+'&page='+page;
			app.forum.populate(newUrl);
			$('.isActive',$(this).closest('.pager')).removeClass('isActive');			
			$(this).addClass('isActive');
			if($('.pageLink:last',pager).hasClass('isActive')){
				$('.nextLink',pager).hide();
			}
			if($('.pageLink:first',pager).hasClass('isActive')){
				$('.prevLink',pager).hide();
			}
			e.preventDefault();
		});*/
		$('.pager .pageLink').on(ev,function(e){
			var pager = $(this).closest('.pager');
			$('.prevLink',pager).show();
			$('.nextLink',pager).show();
			var page = $(this).attr('href').replace(/#/g,'');
			$('.isActive',$(this).closest('.pager')).removeClass('isActive');			
			$(this).addClass('isActive');
			$('.page').hide();
			$('.page'+parseInt($(this).text().trim())).show();
			if($('.pageLink:last',pager).hasClass('isActive')){
				$('.nextLink',pager).hide();
			}
			if($('.pageLink:first',pager).hasClass('isActive')){
				$('.prevLink',pager).hide();
			}
			e.preventDefault();
		});
		
		// view switch
		$('.viewSwitch a').on(ev,function(e){
			var id = $(this).attr('href');
			$('.ratingViews #graphView').hide();
			$('.ratingViews #tabularView').hide();
			$('.ratingViews '+id).fadeIn();
			e.preventDefault();
			$('.isActive',$(this).closest('.viewSwitch')).removeClass('isActive');
			$(this).addClass('isActive');
			if(typeof(currentChart)!='undefined'){
				currentChart.reflow();
			}
			if(typeof(currentDistChart)!='undefined'){
				currentDistChart.reflow();
			}
		});
		
		//design carousel
		if($('.submissionCarousel').length>0){
			var len = $('.submissionCarousel .slider .slide').length;
			$('.submissionCarousel .slider').iCarousel({
				slides: 5,
				dir: 'rtl',
				easing: 'ease-in-out',
				slidesSpace: 190,
				mouseWheel: false,
				onAfterChange: function(){
									var aIdx = this.defs.slide;
									$('.ratingInfo .slider-pager .isActive').removeClass('isActive');
									$('.ratingInfo .slider-pager li:eq('+aIdx+') a').addClass('isActive');
									$('.submissonInfo .submissionThumb img').attr('src',$('img',this.defs.currentSlide).attr('src')+'?sbt=full');
									
									var desc = $('.comptetionData',this.defs.currentSlide);
									$('.winInfo .contestTitle').html('<i></i>'+$('.name',desc).val());
									$('.winInfo .prizeAmount .val').html('<i></i>'+$('.prize',desc).val());
									$('.winInfo .submittedOn .time').html($('.submiissionDate',desc).val());
								}
			});
			window.setTimeout(function(){
				$('.submissionCarousel .slider').trigger('iCarousel:pause');
				$('.ratingInfo .slider-pager li:eq(0) a').addClass('isActive');				
				},2000);
			var pagination = $("<div class='slider-ctrl'><ul class='slider-pager'></ul></div>");
			for(var i=0;i<len;i++){
				$('.slider-pager',pagination).append('<li class="slider-pager-item"><a href="javascript:;" class="navDot"></a></li>');
			}
			$('.ratingInfo').append($(pagination));
			
		}
		
		//slider nav 
		$('.ratingInfo').on(ev,'.slider-ctrl a',function(){
			var idx = $(this).parent().index();
			var ctrl = $(this).closest('.slider-ctrl');
			$('.isActive',ctrl).removeClass('isActive'); 
			$(this).addClass('isActive');
			$('.submissionCarousel .slider').trigger('iCarousel:goSlide', [idx]); return false;
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
					post.addClass('post'+rec.type);
                    $('.postTitle', post).html(rec.title);
					$('.postAuthor', post).html(rec.postedBy);
					$('.postBody', post).html(rec.postContent);
					$('.postCat', post).html(rec.postCategory);
					$('.postedAt', post).html(rec.postedOn);
					$('.nThread em', post).html(rec.threads);
					$('.nMsg em', post).html(rec.messages);
					
                	$('.forumPosts .forumList').append(post);
					count+=1;
					if(count>=postPerPage){
						$('.loading').hide();
						return false;
					}
                });				
        		$('.loading').hide();
            });
        }
    },
    initMemberBadges: function() {
      $(".subBadge, .singleBadge").on('mouseenter', function(){
        var tt = $('#badgeTooltip');
        $(this).addClass("activeBadge");
        tt.addClass('isShowing');
        $('header', tt).html($(this).data("title"));
        var date = $(this).data("date");
        if(date === "Not Earned Yet"){
          $('.eranedOn', tt).html(date);
        } else {
          $('.eranedOn', tt).html("Earned on " + date);
        }
        $('.currentlyEearned span', tt).html($(this).data("current"));
        
        tt.css('z-index', '-1').stop().fadeIn();
                
        window.setTimeout(function() {
            var ttNew = $('.tooltip.isShowing');
            var ht = tt.height();
            var wt = tt.width() - $('.activeBadge').width();
            var activeLinkTop = $('.activeBadge').offset().top;
            var top = activeLinkTop - ht - 10;
            var lt = $('.activeBadge').offset().left - wt / 2;
            ttNew.css('left', lt).css('top', top);
            ttNew.css('z-index', '2000').css('opacity', '1');
            $('.isShowing').removeClass('isShowing');
            $('.activeBadge').removeClass('activeBadge');
        }, 10);
      });
      $('.groupBadge, .footer-badges').on('mouseleave', function() {
          $('#badgeTooltip').css('top', -20000);
      });
    }
};

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

app.getColor = function(score){
	if(score < 900) return "coderTextGray";		
	else if(score < 1200) return "coderTextGreen"; 
	else if(score < 1500) return "coderTextBlue";
	else if(score < 2200) return "coderTextYellow";		
	else if(score >= 2200) return "coderTextRed";
}
// extending base prototype
$.extend(app, coder);
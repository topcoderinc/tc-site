var ev = 'click';
if ($.support.touch) {
    ev = 'tap'
}
var ie7 = false;

var ajax = {
    data: ''
}


// application functions
var app = {
    init: function() {
        if (navigator.userAgent.indexOf('MSIE 7.0') >= 0) {
            $('body').addClass('ie7');
            ie7 = true;
        }

        // init slider
        var bannerSlider = $('#banner .slider').bxSlider({
            minSlides: 1,
            maxSlides: 1,
            controls: false,
            auto: true,
            pause: 5000,
            onSlideAfter: function() {
                bannerSlider.startAuto()
            }
        
        });
        
        $('.dataTable.challenges tbody, .layChallenges .dataTable tbody').html(null);
        $('#gridView .contestGrid').html(null);
        // challenges list init
        if ($('.layChallenges').length > 0) {
        //app.challenges.init();
        }
        if ($('.community').length > 0) {
        //app.community.init();
        }
        
        if ($('#whatsHappening .slider').length > 0) {
            
            $('#whatsHappening .slider').each(function() {
                $('ul', $(this)).bxSlider({
                    minSlides: 1,
                    maxSlides: 1,
                    responsive: !ie7,
                    controls: false
                });
            })
        }
        
        app.setPlaceholder($('.connected .email'));
        
        
        
        $('body').on(ev, function() {
            $('.btnMyAcc').removeClass('isActive');
            $('.userWidget:visible').hide();
        });
        
        $('.userWidget').on(ev, function(e) {
            e.stopPropagation();
        })
    
    },
    // event bindings
    initEvents: function() {
        $('.btnMyAcc').on(ev, function() {
            var widg = $('.userWidget', $(this).closest('.container'));
            if ($(this).hasClass('isActive')) {
                widg.stop().slideUp();
                $(this).removeClass('isActive');
            } else {
                widg.stop().slideDown();
                $(this).addClass('isActive');
            }
            
            return false;
        
        });

        // login
        $('.actionLogout').on(ev, function() {
            $('#navigation, .sidebarNav').addClass('newUser')
            $('#navigation .userWidget').hide();
            $('#navigation .isActive').removeClass('isActive');
            $('.btnRegWrap').show();
            $('.btnAccWrap').hide();
        });
        $('.actionLogin').on(ev, function() {
            $('#navigation, .sidebarNav').removeClass('newUser');
            $('.btnRegWrap').hide();
            $('.btnAccWrap').show();
        });
        
        
        $('.sidebarNav a i').on(ev, function() {
            var root = $(this).closest('.root');
            if ($(this).closest('li').hasClass('isActive')) {
                $(this).closest('li').removeClass('isActive');
            } else {
                $(this).closest('li').addClass('isActive');
            }
            return false;
        })

        // main Nav
        $('#mainNav').on(ev, function() {
            $('.sidebarNav').css('opacity', 1);
            $('.content, #navigation').toggleClass('moving');
            $('body').toggleClass('stop-scrolling');
        });
        $('#mainNav .root').on(ev, function(e) {
            e.stopPropagation();
        });
        
        $('#mainNav .root > li').mouseenter(function() {
            $(this).addClass('hover');
        });
        $('#mainNav .root > li').mouseleave(function() {
            $(this).removeClass('hover');
        });

        // footer navigation
        $(' #footer .rootNode > a').on(ev, function() {
            if (!($('.onMobi.linkLogout').is(':visible') || $('.onMobi.linkLogin').is(':visible'))) {
                return false;
            }
            var ul = $('ul', $(this).closest('.rootNode'));
            ul.toggleClass('show');
            return false;
        });

        // tab navs
        $('.tabNav a').on(ev, function() {
            var id = $(this).attr('href');
            $('.tab', $(this).closest('.tabsWrap')).hide();
            $(id).fadeIn();
            $('.active', $(this).closest('nav')).removeClass('active');
            $(this).addClass('active');
            return false;
        });
    },
    
    setLoading: function() {
        if ($('.loading').length <= 0) {
            $('body').append('<div class="loading">Loading...</div>');
        } else {
            $('.loading').show();
        }
    },
    buildRequestData: function(contestType, contest_track, sortColumn, sortOrder) {
        var action = "";
        //	switch contest type 
        switch (contestType) 
        {
            case "activeContest":
                action = "get_active_contest";
                break;
            case "pastContest":
                action = "get_past_contest";
                break;
            case "reviewOpportunities":
                action = "get_review_opportunities";
                break;
        }
        ajax.data = {"action": action,"challenge_type": "","contest_type": contest_track,"sortColumn": sortColumn,"sortOrder": sortOrder};
    },
    /*
	 * community page functions
	 * --------------------------------------------------------------
	 */
    community: {
        init: function() { 
            // list partial challenges table data
            app.community.getAllPartialContests();
			
            $('.dataChanges .viewAll').on(ev, function() {
                console.log('hsi');
                app.setLoading();
                $.getJSON(url, function(data) {
                    app.getPartialContestTable($('.challenges'), data, 1000);
                });
                $('.rt', $(this).closest('.dataChanges')).hide();
                $(this).parent().hide();
                app.ie7Fix2();
            });
        },			
		
		getAllPartialContests: function(){
			/*
			* get all contests data
			*/	
			app.getPartialContests(ajaxUrl,$('.challenges'), 8, 'design',false, function(){
				app.getPartialContests(ajaxUrl,$('.challenges'), 8, 'develop',true, function(){
					app.getPartialContests(ajaxUrl,$('.challenges'), 8, 'data-marathon',true, function(){
						app.getPartialContests(ajaxUrl,$('.challenges'), 8, 'data-srm',true);
					});
				});
			});
		}
    
    },
	// get contests tableView & gridView data
	getPartialContests: function(url, table, records2Disp, challenge_type, isAppend, callback) {
	if (url == null || url == "") {
		return false;
	}
	ajax.data["challenge_type"] = challenge_type;
	app.setLoading();
	$.getJSON(url, ajax.data, function(data) {
			app.getPartialContestTable(table, data, records2Disp, isAppend);
			if(callback != null && callback !=""){
				callback();
			}
		});
	},
	
    /*
	 * challenges page functions
	 * --------------------------------------------------------------
	 */
    challenges: {
        init: function() {
            // add table and gird data
            app.challenges.getAllContests();       
            
            $('.dataChanges .viewAll').on(ev, function() {
                app.getContests(ajaxUrl, $('.dataTable'), 1000);
                $('.rt', $(this).closest('.dataChanges')).hide();
                $(this).parent().hide();
            });
            
            $('.dataChanges').on(ev, '.nextLink', function(e) {
                $('.prevLink', $(this).parent()).show();
                var _this = $(e.currentTarget);
                var pageNo = _this.attr('href').replace(/#/g, '');
                pageNo = parseInt(pageNo);
                if (pageNo >= 3) {
                    _this.hide();
                }
                var url = 'data/challenges-' + pageNo + '.json';
                app.getContests(url, $('.dataTable'), 8);
                _this.attr('href', '#' + (pageNo + 1));
                $('.prevLink', $(this).parent()).attr('href', '#' + (pageNo - 1));
                return false;
            });
            $('.dataChanges').on(ev, '.prevLink', function(e) {
                $('.nextLink', $(this).parent()).show();
                var _this = $(e.currentTarget);
                var pageNo = _this.attr('href').replace(/#/g, '');
                pageNo = parseInt(pageNo);
                if (pageNo <= 1) {
                    _this.hide();
                }
                var url = 'data/challenges-' + pageNo + '.json';
                app.getContests(url, $('.dataTable'), 8);
                _this.attr('href', '#' + (pageNo - 1));
                $('.nextLink', $(this).parent()).attr('href', '#' + (pageNo + 1));
                return false;
            });
            
            $('.views a').on(ev, function(e) {
                if ($(this).hasClass('isActive')) {
                    return false;
                }
                $('.viewTab').hide();
                id = $(this).attr('href');
                $(id).fadeIn('fast');
                $('.isActive', $(this).parent()).removeClass('isActive');
                $(this).addClass('isActive');
                app.ie7Fix();
                return false;
            });
            
            $('.ddWrap').on(ev, '.val', function() {
                $(this).closest('.ddWrap').toggleClass('on');
            });
            $('.ddWrap').mouseleave(function() {
                $(this).closest('.ddWrap').removeClass('on');
            });
            $('.ddWrap .list li').on(ev, function() {
                var dd = $(this).closest('.ddWrap');
                $('.active', dd).removeClass('active');
                $(this, dd).addClass('active');
                $('.val', dd).html($(this).text() + '<i></i>');
                dd.removeClass('on');
                app.getContests('data/challenges-2.json', $('.dataTable'), 8);
            });

            // challengeType
            $('.challengeType a').on(ev, function() {
                if ($(this).hasClass('active'))
                    return false;
                
                $('.active', $(this).closest('.challengeType')).removeClass('active');
                $(this).addClass('active');
                
                var href = $(this).attr('href');
                // add table data
				if(href=="all"){
					app.challenges.getAllContests();
				}else if(href=="data"){
					app.getContests(ajaxUrl, $('.dataTable'), 8, 'data-marathon',false,function(){
						app.getContests(ajaxUrl, $('.dataTable'), 8, 'data-srm',true);						
					});
				}else{
					app.getContests(ajaxUrl, $('.dataTable'), 8, href,false);
					//app.getContests('data/' + href + '.json', $('.dataTable'), 8);
				}
                
                return false;
            });
        },
		
		getAllContests: function(){
			/*
			* get all contests data
			*/
			app.getContests(ajaxUrl, $('.dataTable'), 8, 'design',false,
			function(){
				app.getContests(ajaxUrl, $('.dataTable'), 8, 'develop', true,function(){
					app.getContests(ajaxUrl, $('.dataTable'), 8, 'data-marathon',true,function(){
						app.getContests(ajaxUrl, $('.dataTable'), 8, 'data-srm',true);
					});
				});				
			});
		}
    },

    // ie fix
    ie7Fix: function() {
        if (ie7) {
            $('#aboutContent, #footer').hide();
            window.setTimeout(function() {
                $('#aboutContent, #footer').show()
            }, 10);
        }
    },

    // ie fix
    ie7Fix2: function() {
        if (ie7) {
            $('body').hide();
            window.setTimeout(function() {
                $('body').show();
            }, 100);
        }
    },    
    
	formatDate: function(date){
		return date.replace(/ /g, '&nbsp;').replace(/[.]/g, '/');
	},
	
    // get contests tableView & gridView data
    getContests: function(url, table, records2Disp, challenge_type, isAppend, callback) {
        if (url == null || url == "") {
            return false;
        }
        ajax.data["challenge_type"] = challenge_type;
        app.setLoading();
        $.getJSON(url, ajax.data, function(data) {
            app.getContestTable(table, data, records2Disp, isAppend);
            app.getContestGrid($('#gridView .contestGrid'), data, (records2Disp + 1), isAppend);
			
			if(callback != null && callback !=""){
				callback();
			}
        });
    },
    // generate contest view table
    getContestTable: function(table, data, records2Disp, isAppend) {
        if (isAppend != true) {
            $('tbody', table).html(null);
        }
        var count = 0;
        $.each(data, function(key, rec) {
            if (count >= records2Disp) {
                count = 0;
                return false;
            } else {
                count += 1;
            }
            var row = $(blueprints.challengeRow).clone();
			var trackName = ajax.data["challenge_type"].split('-')[0];
            row.addClass('track-'+trackName);
			if(ajax.data["challenge_type"]=="data-srm" ){	
			/*
			* generate table row for contest type SRM
			*/			
            	$('.contestName', row).html('<i></i>' + rec.name);
				
				if (rec.startDate == null || rec.startDate == "") {
                rec.startDate = "10.31.2013 10:10 EDT"; //dummy data
				}
				$('.vStartDate', row).html(app.formatDate(rec.startDate));
				
				if (rec.round1EndDate == null || rec.round1EndDate == "") {
                rec.round1EndDate = "10.31.2013 10:10 EDT"; //dummy data
				}
				$('.vEndRound', row).html(app.formatDate(rec.round1EndDate));
				
				if (rec.endDate == null || rec.endDate == "") {
                rec.endDate = "10.31.2013 10:10 EDT"; //dummy data
				}
				$('.vEndDate', row).html(app.formatDate(rec.endDate));
				
				if (rec.timeLeft == null || rec.timeLeft == "") {
					rec.timeLeft = "3 days"; //dummy data
				}
				$('.colTLeft', row).html(rec.timeLeft);
				
				if (rec.purse == null || rec.purse == "") {
					rec.purse = "1500"; //dummy data
				}
				$('.colPur', row).html("$" + rec.purse);
				
				if (rec.registrants == null || rec.registrants == "") {
					rec.registrants = "10"; //dummy data
				}
				$('.colReg', row).html(rec.registrants);
				
				if (rec.submissions == null || rec.submissions == "") {
					rec.submissions = "10"; //dummy data
				}
				$('.colSub', row).html(rec.submissions);
				
				if (rec.isRegistered === "true") {
					$('.action', row).html('<a href="javascript:;" class="btn">Submit</a>');
				} else {
					$('.action', row).html('<a href="javascript:;" class="btn btnAlt">Register</a>');
				}
				
			}else if(ajax.data["challenge_type"]=="data-marathon"){
				/*
				* generate table row for contest type Marathon
				*/			
            	$('.contestName', row).html('<i></i>' + rec.fullName);
				
				if (rec.startDate == null || rec.startDate == "") {
                rec.startDate = "10.31.2013 10:10 EDT"; //dummy data
				}
				$('.vStartDate', row).html(app.formatDate(rec.startDate));
				
				if (rec.round1EndDate == null || rec.round1EndDate == "") {
                rec.round1EndDate = "10.31.2013 10:10 EDT"; //dummy data
				}
				$('.vEndRound', row).html(app.formatDate(rec.round1EndDate));
				
				if (rec.endDate == null || rec.endDate == "") {
                rec.endDate = "10.31.2013 10:10 EDT"; //dummy data
				}
				$('.vEndDate', row).html(app.formatDate(rec.endDate));
				
				if (rec.timeLeft == null || rec.timeLeft == "") {
					rec.timeLeft = "3 days"; //dummy data
				}
				$('.colTLeft', row).html(rec.timeLeft);
				
				if (rec.purse == null || rec.purse == "") {
					rec.purse = "1500"; //dummy data
				}
				$('.colPur', row).html("$" + rec.purse);
				
				if (rec.registrants == null || rec.registrants == "") {
					rec.registrants = "10"; //dummy data
				}
				$('.colReg', row).html(rec.registrants);
				
				if (rec.submissions == null || rec.submissions == "") {
					rec.submissions = "10"; //dummy data
				}
				$('.colSub', row).html(rec.submissions);
				
				if (rec.isRegistered === "true") {
					$('.action', row).html('<a href="javascript:;" class="btn">Submit</a>');
				} else {
					$('.action', row).html('<a href="javascript:;" class="btn btnAlt">Register</a>');
				}
			}else{
				/*
				* generate table row for other contest type
				*/	
           		$('.contestName', row).html('<i></i>' + rec.contestName);
				if (rec.startDate == null || rec.startDate == "") {
                rec.startDate = "10.31.2013 10:10 EDT"; //dummy data
				}
				$('.vStartDate', row).html(rec.startDate.replace(/ /g, '&nbsp;').replace(/[.]/g, '/'));
				
				if (rec.round1EndDate == null || rec.round1EndDate == "") {
					rec.round1EndDate = "10.31.2013 10:10 EDT"; //dummy data
				}
				$('.vEndRound', row).html(rec.round1EndDate.replace(/ /g, '&nbsp;').replace(/[.]/g, '/'));
				
				if (rec.endDate == null || rec.endDate == "") {
					rec.endDate = "10.31.2013 10:10 EDT"; //dummy data
				}
				$('.vEndDate', row).html(rec.endDate.replace(/ /g, '&nbsp;').replace(/[.]/g, '/'));
				
				if (rec.timeLeft == null || rec.timeLeft == "") {
					rec.timeLeft = "3 days"; //dummy data
				}
				$('.colTLeft', row).html(rec.timeLeft);
				
				if (rec.isEnding === "true") {
					$('.colTLeft', row).addClass('imp');
				}
				
				if (rec.purse == null || rec.purse == "") {
					rec.purse = "1500"; //dummy data
				}
				$('.colPur', row).html("$" + rec.purse);
				
				if (rec.registrants == null || rec.registrants == "") {
					rec.registrants = "10"; //dummy data
				}
				$('.colReg', row).html(rec.registrants);
				
				if (rec.submissions == null || rec.submissions == "") {
					rec.submissions = "10"; //dummy data
				}
				$('.colSub', row).html(rec.submissions);
				
				if (rec.isRegistered === "true") {
					$('.action', row).html('<a href="javascript:;" class="btn">Submit</a>');
				} else {
					$('.action', row).html('<a href="javascript:;" class="btn btnAlt">Register</a>');
				}
			}
			
            
            $('tbody', table).append(row);
        });
        app.initZebra(table);
        $('.loading').hide();
    },

    // getGridview Blocks
    getContestGrid: function(gridEl, data, records2Disp, isAppend) {
		if(isAppend != true){
        	gridEl.html(null);			
		}
        var count = 0;
        $.each(data, function(key, rec) {
            if (count >= records2Disp) {
                count = 0;
                return false;
            } else {
                count += 1;
            }
            
            var con = $(blueprints.challengeGridBlock).clone();
			var trackName = ajax.data["challenge_type"].split('-')[0];
			con.addClass('track-'+trackName);
		if(ajax.data["challenge_type"]=="data-srm" ){	
			/*
			* generate table row for contest type SRM
			*/	
			$('.contestName', con).html('<i></i>' + rec.name);
			
			if (rec.startDate == null || rec.startDate == "") {
                rec.startDate = "10.31.2013 10:10 EDT"; //dummy data
				}
			$('.vStartDate', con).html(app.formatDate(rec.startDate));
				
            if (rec.round1EndDate == null || rec.round1EndDate == "") {
                rec.round1EndDate = "10.31.2013 10:10 EDT"; //dummy data
				}
            $('.vEndRound', con).html(app.formatDate(rec.round1EndDate));
			
			if (con.endDate == null || con.endDate == "") {
                con.endDate = "10.31.2013 10:10 EDT"; //dummy data
				}
            $('.vEndDate', con).html(app.formatDate(rec.endDate));
			
			if (rec.timeLeft == null || rec.timeLeft == "") {
					rec.timeLeft = "3 days"; //dummy data
				}
            $('.cgTLeft', con).html('<i></i>' + rec.timeLeft.replace(/ days/g, 'd').replace(/ Hours/g, 'hr').replace(/ Minutes/g, 'min'));
            if (rec.isEnding === "true") {
                $('.cgTLeft', con).addClass('imp');
            }
			
			if (rec.purse == null || rec.purse == "") {
					rec.purse = "1500"; //dummy data
				}
            $('.cgPur', con).html('<i></i> $' + rec.purse);
			
			if (rec.registrants == null || rec.registrants == "") {
					rec.registrants = "10"; //dummy data
				}
            $('.cgReg', con).html('<i></i>' + rec.registrants);
			
			if (rec.submissions == null || rec.submissions == "") {
					rec.submissions = "10"; //dummy data
				}
            $('.cgSub', con).html('<i></i>' + rec.submissions);	
		}else if(ajax.data["challenge_type"]=="data-marathon" ){	
			/*
			* generate table row for contest type Marathon
			*/	
			$('.contestName', con).html('<i></i>' + rec.fullName);
			
			if (rec.startDate == null || rec.startDate == "") {
                rec.startDate = "10.31.2013 10:10 EDT"; //dummy data
				}
			$('.vStartDate', con).html(app.formatDate(rec.startDate));
				
            if (rec.round1EndDate == null || rec.round1EndDate == "") {
                rec.round1EndDate = "10.31.2013 10:10 EDT"; //dummy data
				}
            $('.vEndRound', con).html(app.formatDate(rec.round1EndDate));
			
			if (con.endDate == null || con.endDate == "") {
                con.endDate = "10.31.2013 10:10 EDT"; //dummy data
				}
            $('.vEndDate', con).html(app.formatDate(rec.endDate));
			
			if (rec.timeLeft == null || rec.timeLeft == "") {
					rec.timeLeft = "3 days"; //dummy data
				}
            $('.cgTLeft', con).html('<i></i>' + rec.timeLeft.replace(/ days/g, 'd').replace(/ Hours/g, 'hr').replace(/ Minutes/g, 'min'));
            if (rec.isEnding === "true") {
                $('.cgTLeft', con).addClass('imp');
            }
			
			if (rec.purse == null || rec.purse == "") {
					rec.purse = "1500"; //dummy data
				}
            $('.cgPur', con).html('<i></i> $' + rec.purse);
			
			if (rec.registrants == null || rec.registrants == "") {
					rec.registrants = "10"; //dummy data
				}
            $('.cgReg', con).html('<i></i>' + rec.registrants);
			
			if (rec.submissions == null || rec.submissions == "") {
					rec.submissions = "10"; //dummy data
				}
            $('.cgSub', con).html('<i></i>' + rec.submissions);		
		}
		else{	
            $('.contestName', con).html('<i></i>' + rec.contestName);
			
			if (rec.startDate == null || rec.startDate == "") {
                rec.startDate = "10.31.2013 10:10 EDT"; //dummy data
				}
			$('.vStartDate', con).html(app.formatDate(rec.startDate));
				
            if (rec.round1EndDate == null || rec.round1EndDate == "") {
                rec.round1EndDate = "10.31.2013 10:10 EDT"; //dummy data
				}
            $('.vEndRound', con).html(app.formatDate(rec.round1EndDate));
			
			if (con.endDate == null || con.endDate == "") {
                con.endDate = "10.31.2013 10:10 EDT"; //dummy data
				}
            $('.vEndDate', con).html(app.formatDate(rec.endDate));
			
			if (rec.timeLeft == null || rec.timeLeft == "") {
					rec.timeLeft = "3 days"; //dummy data
				}
            $('.cgTLeft', con).html('<i></i>' + rec.timeLeft.replace(/ days/g, 'd').replace(/ Hours/g, 'hr').replace(/ Minutes/g, 'min'));
            if (rec.isEnding === "true") {
                $('.cgTLeft', con).addClass('imp');
            }
			
			if (rec.purse == null || rec.purse == "") {
					rec.purse = "1500"; //dummy data
				}
            $('.cgPur', con).html('<i></i> $' + rec.purse);
			
			if (rec.registrants == null || rec.registrants == "") {
					rec.registrants = "10"; //dummy data
				}
            $('.cgReg', con).html('<i></i>' + rec.registrants);
			
			if (rec.submissions == null || rec.submissions == "") {
					rec.submissions = "10"; //dummy data
				}
            $('.cgSub', con).html('<i></i>' + rec.submissions);		
        }
				
            gridEl.append(con);			
			$('.loading').hide();
        });
    },

    // generate contest view table
    getPartialContestTable: function(table, data, records2Disp, isAppend) {
		if(isAppend != true){
			$('tbody', table).html(null);	
		}        
        var count = 0;
		
		$.each(data, function(key, rec) {
            if (count >= records2Disp) {
                count = 0;
                return false;
            } else {
                count += 1;
            }
            var row = $(blueprints.partialChallengeRow).clone();
			var trackName = ajax.data["challenge_type"].split('-')[0];
            row.addClass('track-'+trackName);
			if(ajax.data["challenge_type"]=="data-srm" ){	
			/*
			* generate table row for contest type SRM
			*/			
            	$('.contestName', row).html('<i></i>' + rec.name);
				
				if (rec.startDate == null || rec.startDate == "") {
                rec.startDate = "10.31.2013 10:10 EDT"; //dummy data
				}
				$('.vStartDate', row).html(app.formatDate(rec.startDate));
				
				if (rec.round1EndDate == null || rec.round1EndDate == "") {
                rec.round1EndDate = "10.31.2013 10:10 EDT"; //dummy data
				}
				$('.vEndRound', row).html(app.formatDate(rec.round1EndDate));
				
				if (rec.endDate == null || rec.endDate == "") {
                rec.endDate = "10.31.2013 10:10 EDT"; //dummy data
				}
				$('.vEndDate', row).html(app.formatDate(rec.endDate));
				
				if (rec.timeLeft == null || rec.timeLeft == "") {
					rec.timeLeft = "3 days"; //dummy data
				}
				$('.colTLeft', row).html(rec.timeLeft);
				
				if (rec.purse == null || rec.purse == "") {
					rec.purse = "1500"; //dummy data
				}
				$('.colPur', row).html("$" + rec.purse);				
				
				
			}else if(ajax.data["challenge_type"]=="data-marathon"){
				/*
				* generate table row for contest type Marathon
				*/			
            	$('.contestName', row).html('<i></i>' + rec.fullName);
				
				if (rec.startDate == null || rec.startDate == "") {
                rec.startDate = "10.31.2013 10:10 EDT"; //dummy data
				}
				$('.vStartDate', row).html(app.formatDate(rec.startDate));
				
				if (rec.round1EndDate == null || rec.round1EndDate == "") {
                rec.round1EndDate = "10.31.2013 10:10 EDT"; //dummy data
				}
				$('.vEndRound', row).html(app.formatDate(rec.round1EndDate));
				
				if (rec.endDate == null || rec.endDate == "") {
                rec.endDate = "10.31.2013 10:10 EDT"; //dummy data
				}
				$('.vEndDate', row).html(app.formatDate(rec.endDate));
				
				if (rec.timeLeft == null || rec.timeLeft == "") {
					rec.timeLeft = "3 days"; //dummy data
				}
				$('.colTLeft', row).html(rec.timeLeft);
				
				if (rec.purse == null || rec.purse == "") {
					rec.purse = "1500"; //dummy data
				}
				$('.colPur', row).html("$" + rec.purse);
				
				
			}else{
				/*
				* generate table row for other contest type
				*/	
           		$('.contestName', row).html('<i></i>' + rec.contestName);
				if (rec.startDate == null || rec.startDate == "") {
                rec.startDate = "10.31.2013 10:10 EDT"; //dummy data
				}
				$('.vStartDate', row).html(rec.startDate.replace(/ /g, '&nbsp;').replace(/[.]/g, '/'));
				
				if (rec.round1EndDate == null || rec.round1EndDate == "") {
					rec.round1EndDate = "10.31.2013 10:10 EDT"; //dummy data
				}
				$('.vEndRound', row).html(rec.round1EndDate.replace(/ /g, '&nbsp;').replace(/[.]/g, '/'));
				
				if (rec.endDate == null || rec.endDate == "") {
					rec.endDate = "10.31.2013 10:10 EDT"; //dummy data
				}
				$('.vEndDate', row).html(rec.endDate.replace(/ /g, '&nbsp;').replace(/[.]/g, '/'));
				
				if (rec.timeLeft == null || rec.timeLeft == "") {
					rec.timeLeft = "3 days"; //dummy data
				}
				$('.colTLeft', row).html(rec.timeLeft);
				
				if (rec.isEnding === "true") {
					$('.colTLeft', row).addClass('imp');
				}
				
				if (rec.purse == null || rec.purse == "") {
					rec.purse = "1500"; //dummy data
				}
				$('.colPur', row).html("$" + rec.purse);
				
			}		
		
        
            $('tbody', table).append(row);
        });
        app.initZebra(table);
        $('.loading').hide();
		
    },

    // table zebra
    initZebra: function(table) {
        $('tbody tr.alt', table).removeClass('alt');
        $('tbody tr:odd', table).addClass('alt');
    },

    // palceholder
    setPlaceholder: function(selector) {
        $(selector).each(function() {
            _this = $(this);
            var text = _this.attr('placeholder');
            _this.val(text).addClass('isBlured');
            _this.on('focus', function() {
                $(this).on('blur', function() {
                    $(this).unbind('blur', arguments.callee);
                    if ($.trim($(this).val()) === '') {
                        $(this).val(text).addClass("isBlured");
                    }
                });
                if ($(this).val() === text) {
                    $(this).val('').removeClass("isBlured");
                }
            });
        });
    }

}
var blueprints = {
    challengeRow: '<tr> \
						<td class="colCh"><div>\
								<a href="#" class="contestName"></a>\
							</div></td>\
						<td class="colTime"><div>\
								<div class="row">\
									<label class="lbl">Start Date</label>\
									<div class="val vStartDate"> </div>\
								</div>\
								<div class="row">\
									<label class="lbl">Round End</label>\
									<div class="val vEndRound"> </div>\
								</div>\
								<div class="row">\
									<label class="lbl">End Date</label>\
									<div class="val  vEndDate"> </div>\
								</div>\
							</div></td>\
						<td class="colTLeft"></td>\
						<td class="colPur"></td>\
						<td class="colReg"></td>\
						<td class="colSub"></td>\
						<td class="action"><a href="javascript:;" class="btn">Submit</a></td>\
					</tr>',
    partialChallengeRow: '<tr> \
						<td class="colCh"><div>\
								<a href="#" class="contestName"></a>\
							</div></td>\
						<td class="colTime"><div>\
								<div class="row">\
									<label class="lbl">Start Date</label>\
									<div class="val vStartDate"> </div>\
								</div>\
								<div class="row">\
									<label class="lbl">Round End</label>\
									<div class="val vEndRound"> </div>\
								</div>\
								<div class="row">\
									<label class="lbl">End Date</label>\
									<div class="val  vEndDate"> </div>\
								</div>\
							</div></td>\
						<td class="colPur"></td>\
					</tr>',
    challengeGridBlock: '<div class="contest">\
									<div class="cgCh"><a href="#" class="contestName"></a></div>\
									<div class="cgTime">\
										<div>\
											<div class="row">\
												<label class="lbl">Start Date</label>\
												<div class="val vStartDate"></div>\
											</div>\
											<div class="row">\
												<label class="lbl">Round End</label>\
												<div class="val vEndRound"></div>\
											</div>\
											<div class="row">\
												<label class="lbl">End Date</label>\
												<div class="val vEndDate"></div>\
											</div>\
										</div>\
									</div>\
									<div class="genInfo">\
										<p class="cgTLeft">\
											<i></i>\
										</p>\
										<p class="cgPur">\
											<i></i>\
										</p>\
										<p class="cgReg">\
											<i></i>\
										</p>\
										<p class="cgSub">\
											<i></i>\
										</p>\
									</div>\
								</div>'
}

// everythings begins from here
$(document).ready(function() {
    app.init();
    app.initEvents();
})


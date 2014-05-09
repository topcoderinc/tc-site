jQuery(document).ready(function($){
	var tabs = $('.wpt-settings .wptab').length;
	$('.wpt-settings .tabs a[href="#'+firstItem+'"]').addClass('active');
	if ( tabs > 1 ) {
	$('.wpt-settings .wptab').not('#'+firstItem).hide();
	$('.wpt-settings .tabs a').on('click',function(e) {
		e.preventDefault();
		$('.wpt-settings .tabs a').removeClass('active');
		$(this).addClass('active');
		var target = $(this).attr('href');
		$('.wpt-settings .wptab').not(target).hide();
		$(target).show();
	});
	}
});
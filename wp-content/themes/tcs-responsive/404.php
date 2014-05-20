<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php wp_title(' - ', TRUE, 'right'); ?></title>

	<meta name="description" content="">
	<meta name="author" content="" >

	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0" />
	<meta name="apple-mobile-web-app-capable" content="yes" />

	<!-- Favicons -->
	<link rel="shortcut icon" href="http://local.topcoder.com/wp-content/themes/tcs-responsive/favicon.ico" />


	<?php
		delete_transient("tsc_get_asset_map");

		wp_head();

		$urlLogout = add_query_arg('auth', 'logout', get_bloginfo('wpurl'));

		fixIERoundedCorder();
	?>
	<!-- External JS -->
	<!--[if lt IE 9]>
	<script src="<?php bloginfo( 'stylesheet_directory' ); ?>/js/html5shiv.js" type="text/javascript"></script>
	<script src="<?php bloginfo( 'stylesheet_directory' ); ?>/js/respond.js" type="text/javascript"></script>
	<script src="<?php bloginfo( 'stylesheet_directory' ); ?>/js/modernizr.js" type="text/javascript"></script>
	<link rel = "stylesheet" href = "<?php bloginfo( 'stylesheet_directory' ); ?>/css/ie.css" / >
	<style type="text/css">
		body{
			min-width: 360px;
		}
	</style>
	<![endif]-->
</head>

<body>
	<div id="wrapper" class="tcssoUsingJS">
		<header id="navigation">
	    <div class="container">
		<div class="clear"></div>
			<h1 class="logo">
				<a href="http://local.topcoder.com" title="topcoder"></a>
			</h1>
			</div>
		</header>
		<article id="mainContent" class="page404">
			<div class="container">
				<header>
					<h1>
				ERROR 404 : Page not Found
	                </h1>
	            </header>
	            <p class="text404">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris malesuada tristique lacus, vel auctor arcu aliquam sit amet. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec placerat, nulla eget suscipit pulvinar, lacus leo consectetur lorem, a lacinia eros tellus eu libero.</p>
		</div>
	    </article>
	    <article class="step404">
		<div id="step-img"></div>
		<div id="print_btn">
			<a class="btn btnFooter" href="javascript:window.print();">PRINT</a>
		</div>
	    </article>
	    <footer>
		<div class="copyright">
			<section>
		        <br>
		        &#169; 2014 topcoder. All Rights reserved.
		        <br>
		        <a href="http://www.topcoder.com/community/how-it-works/privacy-policy/" class="privacyStmtLink">Privacy Policy</a> | <a href="http://www.topcoder.com/community/how-it-works/terms/" class="legalDisclaimerLink">Terms</a>
			</section>
		    </div>
	    </footer>
	</div>
	<div id="print_area">
		<div class="one-page page1"><img src="<?php bloginfo( 'stylesheet_directory' ); ?>/i/404-print-page-1.png"/></div>
		<div class="one-page "><h1>Back of Page1. Please Print on Both Sides.</h1></div>
		<div class="one-page page2-front"><img src="<?php bloginfo( 'stylesheet_directory' ); ?>/i/404-print-page-2-back.png"/></div>
		<div class="page2-back"><img src="<?php bloginfo( 'stylesheet_directory' ); ?>/i/404-print-page-2-front.png"/></div>
	</div>
</body>
</html>
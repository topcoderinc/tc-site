<?php $ver = (get_option('jsCssVersioning') == 1); $v = get_option('jsCssCurrentVersion'); ?>
<!-- meta -->
<meta name="viewport"
	content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0" />
<meta name="apple-mobile-web-app-capable" content="yes" />

<!-- Favicons -->
<link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/favicon.ico" />

<!-- Main CSS -->
<!--<link href="//fonts.googleapis.com/css?family=Lato:400,300,700,i,300i" rel="stylesheet" type="text/css" /> -->
<link href='//fonts.googleapis.com/css?family=Source+Sans+Pro:700,400' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_directory' ); ?>/css/blog.css<?php if ($ver) { echo "?v=$v"; } ?>" />
<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_directory' ); ?>/css/blog-responsive.css<?php if ($ver) { echo "?v=$v"; } ?>" />
<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_directory' ); ?>/css/base.css<?php if ($ver) { echo "?v=$v"; } ?>" />
<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_directory' ); ?>/css/style.css<?php if ($ver) { echo "?v=$v"; } ?>" />
<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_directory' ); ?>/css/style-profile.css<?php if ($ver) { echo "?v=$v"; } ?>" />
<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_directory' ); ?>/css/base-responsive.css<?php if ($ver) { echo "?v=$v"; } ?>" />
<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_directory' ); ?>/css/style-responsive.css<?php if ($ver) { echo "?v=$v"; } ?>" />
<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_directory' ); ?>/css/coder.css<?php if ($ver) { echo "?v=$v"; } ?>" />
<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_directory' ); ?>/css/contact-about.css<?php if ($ver) { echo "?v=$v"; } ?>" />
<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_directory' ); ?>/css/profileBadges.css<?php if ($ver) { echo "?v=$v"; } ?>" />
<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_directory' ); ?>/css/register-login.css<?php if ($ver) { echo "?v=$v"; } ?>" />
<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_directory' ); ?>/css/blog-base.css<?php if ($ver) { echo "?v=$v"; } ?>" />
<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_directory' ); ?>/css/community-landing.css<?php if ($ver) { echo "?v=$v"; } ?>" />



<!-- External JS -->
<!--[if lt IE 9]>
  <script src="<?php bloginfo( 'stylesheet_directory' ); ?>/js/html5shiv.js<?php if ($ver) { echo "?v=$v"; } ?>" type="text/javascript"></script>
  <link rel="stylesheet" href="<?php bloginfo( 'stylesheet_directory' ); ?>/css/ie.css<?php if ($ver) { echo "?v=$v"; } ?>" />
<![endif]-->

<!--[if IE 7]>
  <link rel="stylesheet" href="<?php bloginfo( 'stylesheet_directory' ); ?>/css/ie7.css<?php if ($ver) { echo "?v=$v"; } ?>" />
<![endif]-->

<!--[if IE]>
  <link rel="stylesheet" href="<?php bloginfo( 'stylesheet_directory' ); ?>/css/ie_all.css<?php if ($ver) { echo "?v=$v"; } ?>" />
<![endif]-->

<!--
<script src="<?php bloginfo( 'stylesheet_directory' ); ?>/js/jQuery.js" type="text/javascript"></script>
 -->
<script>
	var base_url = '<?php echo bloginfo( 'stylesheet_directory' ); ?>';
	var siteURL = '<?php echo get_bloginfo('siteurl');?>';
	var ajaxUrl = "<?php bloginfo('wpurl') ?>/wp-admin/admin-ajax.php";
</script>

<?php fixIERoundedCorder(); ?>
<script src="<?php bloginfo( 'stylesheet_directory' ); ?>/js/jquery.bxslider.js<?php if ($ver) { echo "?v=$v"; } ?>" type="text/javascript"></script>

<script src="<?php bloginfo( 'stylesheet_directory' ); ?>/js/jquery.easing.1.3.js<?php if ($ver) { echo "?v=$v"; } ?>" type="text/javascript"></script>
<script src="<?php bloginfo( 'stylesheet_directory' ); ?>/js/jquery.mousewheel.js<?php if ($ver) { echo "?v=$v"; } ?>" type="text/javascript"></script>
<script src="<?php bloginfo( 'stylesheet_directory' ); ?>/js/raphael-min.js<?php if ($ver) { echo "?v=$v"; } ?>" type="text/javascript"></script>
<script src="<?php bloginfo( 'stylesheet_directory' ); ?>/js/jquery.carousel.js<?php if ($ver) { echo "?v=$v"; } ?>" type="text/javascript"></script>
<script src="<?php bloginfo( 'stylesheet_directory' ); ?>/js/jquery.customSelect.min.js<?php if ($ver) { echo "?v=$v"; } ?>" type="text/javascript"></script>
<script src="<?php bloginfo( 'stylesheet_directory' ); ?>/js/swipe.js<?php if ($ver) { echo "?v=$v"; } ?>" type="text/javascript"></script>
<script src="<?php bloginfo( 'stylesheet_directory' ); ?>/js/jquery.inputhints.js<?php if ($ver) { echo "?v=$v"; } ?>" type="text/javascript"></script>

<script src="<?php bloginfo( 'stylesheet_directory' ); ?>/js/scripts.js<?php if ($ver) { echo "?v=$v"; } ?>" type="text/javascript"></script>
<script src="<?php bloginfo( 'stylesheet_directory' ); ?>/js/script-member.js<?php if ($ver) { echo "?v=$v"; } ?>" type="text/javascript"></script>

<script src="<?php bloginfo( 'stylesheet_directory' ); ?>/js/register-login.js<?php if ($ver) { echo "?v=$v"; } ?>" type="text/javascript"></script>
<script src="<?php bloginfo( 'stylesheet_directory' ); ?>/js/blog.js<?php if ($ver) { echo "?v=$v"; } ?>" type="text/javascript"></script>
<script src="<?php bloginfo( 'stylesheet_directory' ); ?>/js/contact-about.js<?php if ($ver) { echo "?v=$v"; } ?>" type="text/javascript"></script>
<script src="<?php bloginfo( 'stylesheet_directory' ); ?>/js/init-header.js<?php if ($ver) { echo "?v=$v"; } ?>" type="text/javascript"></script>

<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_directory' ); ?>/css/challenge-detail-software.css<?php if ($ver) { echo "?v=$v"; } ?>" />
<script src="<?php bloginfo( 'stylesheet_directory' ); ?>/js/challenge-detail-software.js<?php if ($ver) { echo "?v=$v"; } ?>" type="text/javascript"></script>
<script src="<?php bloginfo( 'stylesheet_directory' ); ?>/js/jquery.jscrollpane.min.js<?php if ($ver) { echo "?v=$v"; } ?>" type="text/javascript"></script>
<script src="<?php bloginfo( 'stylesheet_directory' ); ?>/js/jquery.mousewheel.js<?php if ($ver) { echo "?v=$v"; } ?>" type="text/javascript"></script>
<script src="<?php bloginfo( 'stylesheet_directory' ); ?>/js/jquery.column-1.0.js<?php if ($ver) { echo "?v=$v"; } ?>" type="text/javascript"></script>
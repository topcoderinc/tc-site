<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?php wp_title(' - ', true, 'right'); ?></title>
<meta name="description" content="">
<?php $ver = (get_option('jsCssVersioning') == 1); $v = get_option('jsCssCurrentVersion'); ?>
<!-- meta -->
<meta name="viewport"
	content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<!-- Favicons -->
<link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/favicon.ico" />
<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:700,400' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_directory' ); ?>/css/style.css<?php if ($ver) { echo "?v=$v"; } ?>" />
    <script src="<?php bloginfo( 'stylesheet_directory' ); ?>/js/jQuery.js" type="text/javascript"></script>
  </head>

<body>
<?php wp_head(); ?>	
	<script type="text/javascript">
	$(function(){
	  $('.notFoundErrorInner').height($(window).innerHeight());
		});
	</script>

<?php
/*
Template Name: 404
*/

get_header('error'); 
?>

<div class="notFoundError">
	<div class="notFoundErrorInner">
		<div class="content">
			<a href="<?php echo bloginfo('url');?>"><img src="<?php bloginfo( 'stylesheet_directory' ); ?>/i/logo2.png"  alt="" /></a>
			<h1>404 Error</h1>
			<p>your requested page <br>is still on vacation</p>
			<p class="contact">Please contact <br><a href="mailtoLsupport@topcoder.com">support@topcoder.com</a></p>
		</div>
		<span class="clound"></span>
		<span class="ball"></span>
		<span class="decrorat"></span>
		<span class="back"><a href="javascript:history.back(1)"></a></span>
	</div>
</div>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?php bloginfo('name'); ?><?php wp_title(' - ', true, 'left'); ?></title>
<meta name="description" content="">
<meta name="author" content="">

	<?php wp_head(); ?>
	<?php get_template_part('header.assets'); ?>
	
  </head>

<body>
<?php
$nav = array (
		'menu' => 'Main Navigation',
		'menu_class' => '',
		'container'       => '',		
		'menu_class'      => 'root',
		'items_wrap'      => '%3$s',
		'walker' => new nav_menu_walker () 
);

$cookie = get_cookie();
	$handle = "hi4sandy";
if ( $cookie->handle_name == '' || $cookie->handle_id == '' )
{
	$user = "";
	$welcome = "hide";
	$reg = "";
}
else
{
	$user = "newUser";
	$welcome = "";
	$reg = "hide";
}

global $coder;
$coder = get_raw_coder($handle);
$memberSince = explode(" ",$coder->memberSince);
$memberSince = explode(".",$memberSince[0]);
$memberEarning = '$'.$coder->overallEarning;
$photoLink = 'http://community.topcoder.com'.$coder->photoLink;
?>
<div id="wrapper">
		<nav class="sidebarNav mainNav onMobi <?php echo $user; ?>">
		 <ul class="root"><?php wp_nav_menu ( $nav );	?>
			 <li class="notLogged"><a href="javascript:;" class="actionLogin"><i></i>Log In</a></li>
			 <li class="notLogged"><a href="javascript:;"><i></i>REGISTER</a></li>
			 <li class="userLi isLogged">
				<div class="userInfo">
					<div class="userPic">
						<img src="i/userpic-alt.png" alt="usrname">
					</div>
					<div class="userDetails">
						<a href="#" class="coder">MyHandle</a>
						<p class="country">United States</p>
						<a href="#" class="link">My Profile</a>
						<a href="#" class="link">My Dashboard </a>
						<a href="javascript:;" class="link actionLogout">Log Out </a>	
					</div>
				</div>
			</li>
		 </ul>
		</nav>
		<!-- /.sidebarNav -->
		<header id="navigation" class="<?php echo $user; ?>">
			<div class="container">
				<h1 class="logo">
					<a href="<?php bloginfo('wpurl');?>" title="<?php bloginfo('name'); ?>"></a>
				</h1>
				<nav id="mainNav" class="mainNav">
					
					<ul class="root">
						<?php wp_nav_menu ( $nav );	?>
						<li class="onReg"><a href="javascript:;" class="actionLogout">Log Out</a></li>
						<li class="noReg"><a href="javascript:;" class="actionLogin">Log In</a></li>
					</ul>
				</nav>
				<a href="javascript:;" class="onMobi onReg linkLogout actionLogout">Log Out</a>
				<a href="javascript:;" class="onMobi noReg linkLogin actionLogin">Log In</a>
				<span class="btnRegWrap noReg"><a href="javascript:;" class="btn btnRegister">Register</a> </span> <span class="btnAccWrap onReg"><a href="javascript:;" class="btn btnAlt btnMyAcc">
						My Account<i></i>
					</a></span>
				<div class="userWidget">
					<div class="details">
						<div class="userPic">
							<img alt="<?php $coder->handle; ?>" src="<?php echo $photoLink;?>">
						</div>
						<div class="userDetails">
							<?php echo get_handle($coder->handle); ?>
							<p class="country"><?php echo $coder->country; ?></p>
							<p class="lbl">Member Since:</p>
							<p class="val"><?php echo $memberSince[2] ?></p>
							<p class="lbl">Total Earnings :</p>
							<p class="val"><?php echo $memberEarning?></p>
						</div>
					</div>
					<div class="action">
						<a href="#">My Profile</a>
						<a href="#">My Dashboard </a>
						<a href="javascript:;" class="linkAlt actionLogout">Log Out</a>
					</div>
				</div>
				<!-- /.userWidget -->
			</div>
		</header>
		<!-- /#header -->
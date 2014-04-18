
	<?php get_template_part('header-main'); ?>
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
	$handle = "lunarkid";
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
if (isset($handle)) {
    // only get profile if we have a valid handle
    $coder = get_member_profile($handle);
} else {
    // no user - just create an empty object
    $coder = new stdClass();
}
$memberSince = explode(" ",$coder->memberSince);
$memberSince = explode(".",$memberSince[0]);
$memberEarning = '$'.$coder->overallEarning;
$photoLink = 'http://community.topcoder.com'.$coder->photoLink;


function tc_header_challenge_submit_js() {
  global $challengeType;
  ?>
  <script type="text/javascript">
    var challengeId = "<?php echo get_query_var('contestID');?>";
    var challengeType = "<?php echo $challengeType; ?>";
  </script>
<?php
}

add_action("wp_head", "tc_header_challenge_submit_js");
get_header();
?>

<div id="wrapper" class="challenge-detail">
		<nav class="sidebarNav mainNav onMobi <?php echo $user; ?>">
		 <ul class="root"><?php wp_nav_menu ( $nav );	?>
			 <li class="notLogged"><a href="javascript:;" class="actionLogin"><i></i>Log In</a></li>
			 <li class="notLogged"><a href="javascript:;"><i></i>REGISTER</a></li>
			 <li class="userLi isLogged">
				<div class="userInfo">
					<div class="userPic">
						<img src="<?php echo $photoLink;?>" alt="<?php echo $coder->handle; ?>">
					</div>
					<div class="userDetails">
						<a href="<?php bloginfo('wpurl');?>/member-profile/<?php echo $coder->handle;?>" style="color:<?php echo $coder->colorStyle->color;?>" class="coder"><?php echo $handle ;?></a>
						<p class="country"><?php echo $coder->country; ?></p>
						<a href="<?php bloginfo('wpurl');?>/member-profile/<?php echo $coder->handle;?>" class="link">My Profile</a>
						<a href="http://community.topcoder.com/tc?module=MyHome" class="link">My Dashboard </a>
						<a href="#" class="link actionLogout">Log Out </a>
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

						<?php if ( $user_id != '' ) : ?>
						<li class="onReg"><a href="#" class="actionLogout">Log Out</a></li>
						<?php else: ?>
						<li class="noReg"><a href="javascript:;" class="actionLogin">Log In</a></li>
						<?php endif; ?>
					</ul>
				</nav>
				<?php if ( $user_id != '' ) : ?>
						<a href="javascript:;" class="onMobi onReg linkLogout actionLogout">Log Out</a>
				<?php else: ?>
				<a href="javascript:;" class="onMobi onReg linkLogin actionLogin">Log In</a>
				<?php endif; ?>
				<?php if ( $user_id == '' ) : ?>
				<span class="btnRegWrap noReg"><a href="javascript:;" class="btn btnRegister">Register</a> </span>
				<?php else: ?>
				<span class="btnAccWrap noReg"><a href="javascript:;" class="btn btnAlt btnMyAcc">
						My Account<i></i>
					</a></span>
				<div class="userWidget">
					<div class="details">
						<div class="userPic">
							<img alt="<?php echo $coder->handle; ?>" src="<?php echo $photoLink;?>">
						</div>
						<div class="userDetails">
							<?php echo get_handle($coder->handle); ?>
							<p class="country"><?php echo $coder->country; ?></p>
							<p class="val memberSince"><?php
									$memSince = $coder->memberSince;
									echo date("M d, Y", strtotime($memSince)) ;
									?></p>
							<?php if (isset($coder->overallEarning)) { ?>
								<p class="lbl">Total Earnings :</p>
								<p class="val memberEarning"><?php echo '$'.$coder->overallEarning;?></p>
							<?php } ?>
						</div>
					</div>
					<div class="action">
						<a href="<?php bloginfo('wpurl');?>/member-profile/<?php echo $coder->handle;?>">My Profile</a>
						<a href="http://community.topcoder.com/tc?module=MyHome">My Dashboard </a>
						<a href="#" class="linkAlt actionLogout">Log Out</a>
					</div>
				</div>
				<?php endif; ?>
				<!-- /.userWidget -->
			</div>
		</header>
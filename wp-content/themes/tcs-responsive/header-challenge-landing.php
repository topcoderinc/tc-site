<?php
session_start();
/***
 since staging or localhost could not use tcsso cookie, 
 then create dummy "tcsso" cookie at first time, only ON localhost OR staging server.
 please remove/disable line below on Prod 
*/
//setcookie("tcsso", "22760600|22554c24d30b15fd79289dd053a9a98e5ff385535dd6cc9b45e645fbabb0a4" );

/*** 
if receive ?auth=logout, then kill cookie and any other sessions 
*/ 
if( $_GET['auth'] == 'logout' ){
	unset($_COOKIE['tcsso']);
	setcookie('tcsso', '', time()-3600, '/', '.topcoder.com');
	
	/***
	kill any other sessions or cookie here 
	*/
	unset($coder);
	session_destroy();
	/***
	then send back user to where they came 	
	*/
	if ( $_SERVER['HTTP_REFERER'] )
	 echo "redirecting ... <script>location.href = '".$_SERVER['HTTP_REFERER']."';</script>";
	exit;
	
}

$urlLogout = add_query_arg( 'auth', 'logout', get_bloginfo('wpurl'));
require_once 'auth0/vendor/autoload.php';
require_once 'auth0/src/Auth0.php';
require_once 'auth0/vendor/adoy/oauth2/vendor/autoload.php';
require_once 'auth0/client/config.php';
use Auth0SDK\Auth0;

$auth0 = new Auth0(array(
    'domain'        => auth0_domain,
    'client_id'     => auth0_client_id,
    'client_secret' => auth0_client_secret,
    'redirect_uri'  => auth0_redirect_uri
));

#$token = $auth0->getAccessToken();
#$user_info = $auth0->getUserInfo();
#$_SESSION['token'] = $token ;
#echo $token;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?php bloginfo('name'); ?><?php wp_title(' - ', true, 'left'); ?></title>
<meta name="description" content="">
<meta name="author" content="" >
	
	<?php //wp_head(); ?>	
	<script type="text/javascript">
		var wpUrl = "<?php bloginfo('wpurl')?>";
		var ajaxUrl = wpUrl+"/wp-admin/admin-ajax.php";		
	</script>

   	<script id="auth0" src="https://sdk.auth0.com/auth0.js#client=<?php echo auth0_client_id;?>"></script>

	<script src="https://d19p4zemcycm7a.cloudfront.net/w2/auth0-1.2.2.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

<?php get_template_part('header.assets.challenge.landing'); ?>
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

//Get the TopCoder SSO Cookie
$cookie = $_COOKIE["tcsso"];
#$cookie = "22760600|22554c24d30b15fd79289dd053a9a98e5ff385535dd6cc9b45e645fbabb0a4"; // Please  disable (#) this line on prod
$cookie_parts = explode( "|", $cookie);
$user_id = $cookie_parts[0];
$tc_token = $cookie_parts[1];

#$url = "http://community.topcoder.com/tc?module=BasicData&c=get_handle_by_id&dsid=30&uid=".$user_id."&json=true";
#$response = get_json_from_url ( $url );
#$data = json_decode ( $response )->data;

#$handle = $data[0]->handle;

if ( isset($_COOKIE["user"]) )
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
/*$coder = get_raw_coder($handle);
$memberSince = explode(" ",$coder->memberSince);
$memberSince = explode(".",$memberSince[0]);
$memberEarning = '$'.$coder->overallEarning;
if ( $coder->photoLink != '')
$photoLink = 'http://community.topcoder.com'.$coder->photoLink;
else
$photoLink = 'http://community.topcoder.com/i/m/nophoto_login.gif';
*/
?>

<div id="wrapper" class="tcssoUsingJS">
		<nav class="sidebarNav mainNav onMobi <?php echo $user; ?>">
		 <ul class="root"><?php wp_nav_menu ( $nav );	?>
<!--			 <li class="notLogged"><a href="javascript:;" class="actionLogin"><i></i>Log In</a></li> -->
			 <li class="notLogged"><a href="javascript:;"><i></i>REGISTER</a></li>
			 <li class="userLi isLogged">
				<div class="userInfo">
					<div class="userPic">
						<img src="<?php echo $photoLink;?>" alt="<?php echo $coder->handle; ?>">
					</div>
					<div class="userDetails">
						<a href="<?php bloginfo('wpurl');?>/member-profile/?ha=<?php echo $coder->handle;?>" style="color:<?php echo $coder->colorStyle->color;?>" class="coder"><?php echo $handle ;?></a>
						<p class="country"><?php echo $coder->country; ?></p>
						<a href="<?php bloginfo('wpurl');?>/member-profile/<?php echo $coder->handle;?>" class="link myProfileLink">My Profile</a>
						<a href="http://community.topcoder.com/tc?module=MyHome" class="link">My TopCoder </a>
						<a href="http://community.topcoder.com/tc?module=MyHome" class="link">Account Settings </a>	
					</div>
				</div>
			</li>
		 </ul>
		</nav>
		<!-- /.sidebarNav -->
		<header id="navigation" class="<?php echo $user; ?>">
			<div class="container">
				<div class="headerTopRightMenu">
					<div class="headerTopRightMenuLink logIn">
						<div class="text"><a href="javascript:;" class="<?php if ( $user_id == '' ) { echo 'actionLogin'; } else { echo 'actionLogout'; }?>"><?php if ( $user_id == '' ) { echo 'Log In'; } else { echo 'Log Out'; }?></a></div>
						<div class="icon"></div>
						<div class="clear"></div>
					</div>
					<div class="separator"></div>
					<div class="headerTopRightMenuLink contact">
						<div class="text"><a href="/contact-us">Contact</a></div>
						<div class="clear"></div>
					</div>
					<div class="separator"></div>
					<div class="headerTopRightMenuLink help">
						<div class="text"><a href="http://help.topcoder.com">Help Center</a></div>
						<div class="clear"></div>
					</div>
					<div class="separator beforeSearch"></div>
					<div class="headerTopRightMenuLink search last">
						<div class="icon"></div>
						<div class="text"><a href="/search">Search</a></div>
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
				<h1 class="logo">
					<a href="<?php bloginfo('wpurl');?>" title="<?php bloginfo('name'); ?>"></a>
				</h1>
				<nav id="mainNav" class="mainNav">
				
				
					<ul class="root">
						<?php wp_nav_menu ( $nav );	?>
<!--						
						<li class="noReg logoutLink 	<?php if ( $user_id == '' ) { echo 'hide';} ?>"><a href="<?php echo $urlLogout;?>" class="actionLogout">Log Out</a></li>
						<?php if ( $user_id == '' ) : ?>
						<li class="noReg loginLink"><a href="javascript:;" class="actionLogin">Log In</a></li>
						<?php endif; ?>
-->
					</ul>
				</nav>
<!--
				<a href="<?php echo $urlLogout;?>" class="onMobi noReg linkLogout actionLogout <?php if ( $user_id == '' ) { echo 'hide';} ?>">Log Out</a>
				<?php if ( $user_id == '' ) : ?>
						<a href="javascript:;" class="onMobi noReg linkLogin actionLogin">Log In</a>
				<?php endif; ?>
-->				
				<div class="userDetailsWrapper <?php if ( $user_id == '' ) { echo 'hide';} ?>">
				<span class="btnAccWrap noReg"><a href="javascript:;" class="btn btnAlt btnMyAcc">
						My Account<i></i>
					</a></span>
				<div class="userWidget">
					<div class="details">
						<div class="userPic">
							<img src="">
						</div>
						<div class="userDetails">
							<?php //echo get_handle($coder->handle); ?>
							<p class="country"><?php //echo $coder->country; ?></p>
							<p class="lbl">Member Since:</p>
							<p class="val memberSince"><?php //echo $memberSince[2] ?></p>
							<p class="lbl">Total Earnings :</p>
							<p class="val memberEarning"><?php //echo $memberEarning?></p>
						</div>
					</div>
					<div class="action">
						<a class="profileLink" href="<?php bloginfo('wpurl');?>/member-profile/<?php echo $coder->handle;?>">My Profile</a>
						<a href="http://community.topcoder.com/tc?module=MyHome">My TopCoder </a>
						<a href="http://community.topcoder.com/tc?module=MyHome" class="linkAlt">Account Settings</a>
					</div>
				</div>
				</div>
				<?php if ( $user_id == '' ) : ?>
				<span class="btnRegWrap noReg"><a href="javascript:;" class="btn btnRegister">Register</a> </span>
				<?php endif; ?>
				<!-- /.userWidget -->	
			</div>
		</header>
		<!-- /#header -->
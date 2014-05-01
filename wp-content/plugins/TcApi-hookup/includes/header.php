<?php


require_once 'auth0/vendor/autoload.php';
require_once 'auth0/src/Auth0.php';
require_once 'auth0/vendor/adoy/oauth2/vendor/autoload.php';
require_once 'auth0/client/config.php';

use Auth0SDK\Auth0;

$auth0 = new Auth0(array(
    'domain'        => $auth0_cfg['domain'],
    'client_id'     => $auth0_cfg['client_id'],
    'client_secret' => $auth0_cfg['client_secret'],
    'redirect_uri'  => $auth0_cfg['redirect_uri']
));

$token = $auth0->getAccessToken();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?php wp_title(' - ', true, 'right'); ?></title>
<meta name="description" content="">
<meta name="author" content="">

	<?php wp_head(); ?>
	<script type="text/javascript">
		var ajaxUrl = "<?php  bloginfo('wpurl')?>/wp-admin/admin-ajax.php";
	</script>
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

// Print a cookie
//echo $_COOKIE["tcsso"];

//Get the TopCoder SSO Cookie
$cookie = $_COOKIE["tcsso"];
$cookie_parts = explode( "|", $cookie);
$user_id = $cookie_parts[0];
$tc_token = $cookie_parts[1];
#$user_id = "22760600";

// PEMULA - update this to correctly parts the "handle" from the json and set it to $handle
		$url = "http://community.topcoder.com/tc?module=BasicData&c=get_handle_by_id&dsid=30&uid=".$user_id."&json=true";
		$response = get_json_from_url ( $url );
		#print_r($response);
	//	print_r( json_decode ( $response )->data[0]->handle );

//		if (is_wp_error ( $response ) || ! isset ( $response ['data'] )) {
//			return "Error in processing";
//		}
			//$handle_obj = json_decode ( $response ['data'], true);
			//echo print_r ($handle_obj);
			//print_r($handle_obj->{'handle'});

$handle = json_decode ( $response )->data[0]->handle;  //Replace this with a call to http://community.topcoder.com/tc?module=BasicData&c=get_handle_by_id&dsid=30&uid=8547899&json=true and parse the handle from the result.

if ( isset($_COOKIE["user"]) )
{
	$user = $handle;
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
if ( $coder->photoLink != '')
$photoLink = 'http://community.topcoder.com'.$coder->photoLink;
else
$photoLink = 'http://community.topcoder.com/i/m/nophoto_login.gif';

?>

<div id="wrapper">
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
						<a href="<?php bloginfo('wpurl');?>/member-profile/<?php echo $coder->handle;?>" style="color:<?php echo $coder->colorStyle->color;?>" class="coder"><?php echo $coder->handle;?></a>
						<p class="country"><?php echo $coder->country; ?></p>
						<a href="#" class="link">My Profile</a>
						<a href="#" class="link">My Dashboard </a>
						<a href="https://topcoder.auth0.com/logout?returnTo=http://beta.topcoder.com" class="link actionLogout">Log Out </a>
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
						<li class="noReg"><a href="https://topcoder.auth0.com/logout?returnTo=http://somewhere" class="actionLogout">Log Out</a></li>
						<li class="onReg"><a href="javascript:;" class="actionLogin">Log In</a></li>
					</ul>
				</nav>
				<a href="javascript:;" class="onMobi noReg linkLogout actionLogout">Log Out</a>
				<a href="javascript:;" class="onMobi onReg linkLogin actionLogin">Log In</a>
				<span class="btnRegWrap onReg"><a href="javascript:;" class="btn btnRegister">Register</a> </span>
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
							<p class="lbl">Member Since:</p>
							<p class="val"><?php echo $memberSince[2] ?></p>
							<p class="lbl">Total Earnings :</p>
							<p class="val"><?php echo $memberEarning?></p>
						</div>
					</div>
					<div class="action">
						<a href="#">My Profile</a>
						<a href="#">My Dashboard </a>
						<a href="https://topcoder.auth0.com/logout?returnTo=http://somewhere" class="linkAlt actionLogout">Log Out</a>
					</div>
				</div>
				<!-- /.userWidget -->
			</div>
		</header>
		<!-- /#header -->
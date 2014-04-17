<?php get_template_part('header-main'); ?>

</head>

<body>

<?php

$nav = array(
  'menu' => 'Main Navigation',
  'container' => '',
  'menu_class' => 'root',
  'items_wrap' => '%3$s',
  'walker' => new nav_menu_walker ()
);

//Get the TopCoder SSO Cookie
$cookie = $_COOKIE["tcsso"];
#$cookie = "22760600|22554c24d30b15fd79289dd053a9a98e5ff385535dd6cc9b45e645fbabb0a4"; // Please  disable (#) this line on prod
$cookie_parts = explode("|", $cookie);
$user_id = $cookie_parts[0];
$tc_token = $cookie_parts[1];
$user_id = ""; // force empty to ensure not using PHP to read Cookie

#$url = "http://community.topcoder.com/tc?module=BasicData&c=get_handle_by_id&dsid=30&uid=".$user_id."&json=true";
#$response = get_json_from_url ( $url );
#$data = json_decode ( $response )->data;

#$handle = $data[0]->handle;

if (isset($_COOKIE["user"])) {
  $user = "";
  $welcome = "hide";
  $reg = "";
}
else {
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
$memberSince = explode(" ", $coder->memberSince);
$memberSince = explode(".", $memberSince[0]);
$memberEarning = '$' . $coder->overallEarning;
if ($coder->photoLink != '') {
  $photoLink = 'http://community.topcoder.com' . $coder->photoLink;
}
else {
  $photoLink = 'http://community.topcoder.com/i/m/nophoto_login.gif';
}

?>

<div id="wrapper" class="tcssoUsingJS">
  <nav class="sidebarNav mainNav onMobi <?php echo $user; ?>">
    <ul class="root"><?php wp_nav_menu($nav); ?>
      <!--			 <li class="notLogged"><a href="javascript:;" class="actionLogin"><i></i>Log In</a></li> -->
      <li class="notLogged"><a href="javascript:;" class="btnRegister"><i></i>REGISTER</a></li>
      <li class="userLi isLogged">
        <div class="userInfo">
          <div class="userPic">
            <img src="<?php echo $photoLink; ?>" alt="<?php echo $coder->handle; ?>">
          </div>
          <div class="userDetails">
            <a href="<?php bloginfo('wpurl'); ?>/member-profile/?ha=<?php echo $coder->handle; ?>"
               style="color:<?php echo $coder->colorStyle->color; ?>" class="coder"><?php echo $handle; ?></a>

            <p class="country"><?php echo $coder->country; ?></p>
            <a href="<?php bloginfo('wpurl'); ?>/member-profile/<?php echo $coder->handle; ?>"
               class="link myProfileLink">My Profile</a>
            <a href="http://community.topcoder.com/tc?module=MyHome" class="link">My TopCoder </a>
            <a href="http://community.topcoder.com/tc?module=MyHome" class="link">Account Settings </a>
            <a href="javascript:;" class="actionLogout">Log Out</a>
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
          <div class="text"><a href="javascript:;" class="<?php if ($user_id == '') {
              echo 'actionLogin';
            }
            else {
              echo 'actionLogout';
            } ?>"><?php if ($user_id == '') {
                echo 'Log In';
              }
              else {
                echo 'Log Out';
              } ?></a></div>
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
        <a href="<?php bloginfo('wpurl'); ?>" title="<?php bloginfo('name'); ?>"></a>
      </h1>
      <nav id="mainNav" class="mainNav">


        <ul class="root">
          <?php wp_nav_menu($nav); ?>
          <!--
						<li class="noReg logoutLink 	<?php if ($user_id == '') {
            echo 'hide';
          } ?>"><a href="<?php echo $urlLogout; ?>" class="actionLogout">Log Out</a></li>
						<?php if ($user_id == '') : ?>
						<li class="noReg loginLink"><a href="javascript:;" class="actionLogin">Log In</a></li>
						<?php endif; ?>
-->
        </ul>
      </nav>
      <!--
				<a href="<?php echo $urlLogout; ?>" class="onMobi noReg linkLogout actionLogout <?php if ($user_id == '') {
        echo 'hide';
      } ?>">Log Out</a>
				<?php if ($user_id == '') : ?>
						<a href="javascript:;" class="onMobi noReg linkLogin actionLogin">Log In</a>
				<?php endif; ?>
-->
      <div class="userDetailsWrapper hide">
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

              <p class="val memberSince"><?php
                $memSince = $coder->memberSince;
                echo date("M d, Y", strtotime($memSince));
                ?></p>
              <?php if (isset($coder->overallEarning)) { ?>
                <p class="lbl">Total Earnings :</p>
                <p class="val memberEarning"><?php echo '$' . $coder->overallEarning; ?></p>
              <?php } ?>
            </div>
          </div>
          <div class="action">
            <a class="profileLink" href="<?php bloginfo('wpurl'); ?>/member-profile/<?php echo $coder->handle; ?>">My
              Profile</a>
            <a href="http://community.topcoder.com/tc?module=MyHome">My TopCoder </a>
            <a href="http://community.topcoder.com/tc?module=MyHome" class="linkAlt">Account Settings</a>
          </div>
        </div>
      </div>
      <a class="onMobi noReg linkLogin actionLogin" href="javascript:;">Log In</a>
      <span class="btnRegWrap noReg"><a href="javascript:;" class="btn btnRegister">Register</a> </span>


      <!-- /.userWidget -->
    </div>
  </header>
  <!-- /#header -->

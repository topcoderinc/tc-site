<?php get_template_part('header-main'); ?>

</head>

<body>

<?php

$nav = array(
  'menu'       => 'Main Navigation',
  'container'  => '',
  'menu_class' => 'root',
  'items_wrap' => '%3$s',
  'walker'     => new nav_menu_walker ()
);

?>

<div id="wrapper" class="tcssoUsingJS">
  <nav class="sidebarNav mainNav onMobi">
    <ul class="root"><?php wp_nav_menu($nav); ?>
      <li class="notLogged"><a href="javascript:;" class="btnRegister"><i></i>REGISTER</a></li>
      <li class="userLi isLogged">
        <div class="userInfo">
          <div class="userPic">
            <img src="" alt="">
          </div>
          <div class="userDetails">
            <a href="" class="coder"></a>
            <p class="country"></p>
            <a href="" class="link myProfileLink">My Profile</a>
            <a href="http://community.topcoder.com/tc?module=MyHome" class="link">My TopCoder </a>
            <a href="http://community.topcoder.com/tc?module=MyHome" class="link">Account Settings </a>
            <a href="javascript:;" class="actionLogout">Log Out</a>
          </div>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.sidebarNav -->
  <header id="navigation">
    <div class="container">
      <div class="headerTopRightMenu">
        <div class="headerTopRightMenuLink logIn">
          <div class="text"><a href="javascript:;" class="actionLogin" style="display: inline;">Log In</a></div>
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
        </ul>
      </nav>
      <div class="userDetailsWrapper hide">
        <span class="btnAccWrap noReg">
          <a href="javascript:;" class="btn btnAlt btnMyAcc">
            My Account<i></i>
          </a>
        </span>

        <div class="userWidget">
          <div class="details">
            <div class="userPic">
              <img src="">
            </div>
            <div class="userDetails">
              <p class="country"></p>

              <p class="val memberSince"></p>

              <p class="lbl">Total Earnings :</p>

              <p class="val memberEarning"></p>
            </div>
          </div>
          <div class="action">
            <a class="profileLink" href="">My
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

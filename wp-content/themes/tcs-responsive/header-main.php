<?php

session_start();
/***
 * since staging or localhost could not use tcsso cookie,
 * then create dummy "tcsso" cookie at first time, only ON localhost OR staging server.
 * please remove/disable line below on Prod
 */
//setcookie("tcsso", "22760600|22554c24d30b15fd79289dd053a9a98e5ff385535dd6cc9b45e645fbabb0a4" );

/***
 * if receive ?auth=logout, then kill cookie and any other sessions
 */
if (isset($_GET['auth']) && $_GET['auth'] == 'logout') {
  unset($_COOKIE['tcsso']);
  setcookie('tcsso', '', time() - 3600, '/', '.topcoder.com');
  unset($_COOKIE['tcjwt']);
  setcookie('tcjwt', '', time() - 3600, '/', '.topcoder.com');

  /***
   * kill any other sessions or cookie here
   */
  unset($coder);
  session_destroy();
  /***
   * then send back user to where they came
   */
  if ($_SERVER['HTTP_REFERER']) {
    echo "redirecting ... <script>location.href = '" . $_SERVER['HTTP_REFERER'] . "';</script>";
  }
  exit;

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?php bloginfo('name'); ?><?php wp_title(' - ', TRUE, 'left'); ?></title>
  <meta name="description" content="">
  <meta name="author" content="" >

  <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0" />
  <meta name="apple-mobile-web-app-capable" content="yes" />

  <!-- Favicons -->
  <link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/favicon.ico" />

  <?php $ver = (get_option('jsCssVersioning') == 1); $v = get_option('jsCssCurrentVersion'); ?>
  <!-- External JS -->
  <!--[if lt IE 9]>
  <script src="<?php THEME_URL ?>/js/html5shiv.js" type="text/javascript"></script>
  <script src="<?php THEME_URL ?>/js/respond.min.js" type="text/javascript"></script>
  <script src="<?php THEME_URL ?>/js/modernizr.js" type="text/javascript"></script>
  <link rel = "stylesheet" href = "<?php THEME_URL ?>/css/ie.css<?php if ($ver) { echo " ? v = $v"; } ?>" / >
  <![endif]-->
  <script type="text/javascript">
    var wpUrl = "<?php bloginfo('wpurl')?>";
    var ajaxUrl = wpUrl.replace('http://', 'https://') + "/wp-admin/admin-ajax.php";
    var siteURL = '<?php bloginfo('siteurl');?>';
    var base_url = '<?php bloginfo( 'stylesheet_directory' ); ?>';
    var stylesheet_dir = '<?php echo THEME_URL . '/css'; ?>';
    var autoRegister = '<?php echo get_query_var('autoRegister'); ?>';
    var timezone_string = "<?php echo get_option('timezone_string');?>";
    var challengeType;
  </script>

<?php

wp_head();


$urlLogout = add_query_arg('auth', 'logout', get_bloginfo('wpurl'));
use Auth0SDK\Auth0;

$auth0 = new Auth0(array(
  'domain' => auth0_domain,
  'client_id' => auth0_client_id,
  'client_secret' => auth0_client_secret,
  'redirect_uri' => auth0_redirect_uri
));

fixIERoundedCorder();

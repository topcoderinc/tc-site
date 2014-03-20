<?php
require_once 'vendor/autoload.php';
require_once '../src/Auth0.php';
require_once '../vendor/adoy/oauth2/vendor/autoload.php';
require_once 'config.php';

use Auth0SDK\Auth0;

$auth0 = new Auth0(array(
    'domain'        => $auth0_cfg['domain'],
    'client_id'     => $auth0_cfg['client_id'],
    'client_secret' => $auth0_cfg['client_secret'],
    'redirect_uri'  => $auth0_cfg['redirect_uri']
));

$token = $auth0->getAccessToken();

?>
<!doctype html>
<?php if(!$token): ?>
    <!-- PUT YOUR Auth0 HTML/JS CODE HERE -->
	<script id="auth0" src="https://sdk.auth0.com/auth0.js#client=6ZwZEUo2ZK4c50aLPpgupeg5v2Ffxp9P&amp;state=http://cloudspokes.wpengine.com/&amp;redirect_uri=http://beta.topcoder.com"></script>

   <script>
     $(function () {
           $('.actionLogin').click( function () {
                  ({ onestep: true, 
                 						title: "TopCoder", 
                 						icon: 'http://www.topcoder.com/i/24x24_brackets.png', 
                 						showIcon: true,
                 						showForgot: true,
    									forgotText: "Forgot Password?",
    									forgotLink: "https://www.topcoder.com/..."
                 					});
           });
       });
                          
   </script>


<script src="https://d19p4zemcycm7a.cloudfront.net/w2/auth0-1.2.2.min.js"></script>
<script src="http://code.jquery.com/jquery.js"></script>
<a href="javascript:;" lass="actionLogin">login</a>
<?php else: ?>
    <?php var_dump($auth0->getUserInfo()) ?>
<?php endif ?>

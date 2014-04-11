<?php
require_once 'TwitterAPIExchange.php';

$settings = array(
  'oauth_access_token' => get_option('twAccessToken'),
  'oauth_access_token_secret' => get_option('twAccessTokenSecret'),
  'consumer_key' => get_option('twConsumerKey'),
  'consumer_secret' => get_option('twConsumerSecret')
);

$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
$getfield = '?screen_name=topcoder&count=3';
$requestMethod = 'GET';
$twitter = new TwitterAPIExchange($settings);
$tweets = json_decode($twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest());

$blog_posts_args = array(
  'posts_per_page' => 2,
  'offset' => 0,
  'orderby' => 'post_date',
  'order' => 'DESC',
  'post_type' => 'blog',
  'suppress_filters' => true
);

$blog_posts = get_posts($blog_posts_args);
?>
<footer id="footer">
  <div class="container">
    <div class="footerContentSection twitter">
      <div class="title">Twitter</div>
      <div class="footerContent">
        <?php
        foreach ($tweets as $tweet) :
          ?>
          <div class="footerTwEntry">
            <?php
            if ($tweet->retweeted_status):
              ?>
              <a href="http://www.twitter.com/<?php echo $tweet->retweeted_status->user->screen_name; ?>">
                @<?php echo $tweet->retweeted_status->user->screen_name; ?>
              </a>
            <?php else: ?>
              <a href="<?php echo get_option('twitterURL'); ?>">
                @<?php echo $tweet->user->screen_name; ?>
              </a>
            <?php endif; ?>
            <?php echo $tweet->text; ?>
          </div>
        <?php endforeach; ?>
        <a class="btn btnFooter" href="<?php echo get_option('twitterURL'); ?>"><span class="twFollowIcon"></span><span
            class="twFollowBtnText">Follow</span></a>
      </div>
    </div>
    <div class="footerContentSection recentBlogPosts">
      <div class="title">Recent Blog Posts</div>
      <div class="footerContent">
        <?php
        foreach ($blog_posts as $post) :
          setup_postdata($post);
          ?>
          <div class="footerBlogEntry">
            <a href="<?php the_permalink(); ?>"><?php the_date('F j'); ?></a> <?php echo wrap_content_strip_html(
              wpautop($post->post_content),
              150,
              true,
              '\n\r',
              ''
            ); ?>
            <a href="<?php the_permalink(); ?>">...read more &gt;</a>
          </div>
        <?php endforeach; ?>
        <a class="btn btnFooter" href="<?php bloginfo('wpurl') ?>/blog">View More</a>
      </div>
    </div>
    <div class="footerContentSection aboutTopCoder">
      <div class="title">About topcoder</div>
      <div class="footerContent">
        The topcoder community gathers the world’s experts in design, development and data science to work on
        interesting and challenging problems for fun and reward. We want to help topcoder members improve their skills,
        demonstrate and gain reward for their expertise, and provide the industry with objective insight on new and
        emerging technologies.
      </div>
      <a class="btn btnFooter" href="<?php bloginfo('wpurl') ?>/mission">About Us</a>
    </div>
    <div class="clear"></div>
  </div>
  <div class="container">
    <div class="connected">
      <section class="social">
        <h4>Get Connected</h4>
        <ul>
          <li><a class="fb" href="<?php echo get_option('facebookURL'); ?>">FB</a></li>
          <li><a class="tw" href="<?php echo get_option('twitterURL'); ?>">TW</a></li>
          <li><a class="gp" href="<?php echo get_option('gPlusURL'); ?>">GP</a></li>
          <li><a class="in" href="<?php echo get_option('linkedInURL'); ?>">IN</a></li>
        </ul>
        <div class="clear"></div>
      </section>
      <section class="updates">
        <div class="row">
          <form id="emailForm" onsubmit="return newsletter_check(this)"
                name="FeedBlitz_9feab01d431311e39e69002590771423" style="display:block" method="POST"
                action="http://www.feedblitz.com/f/f.fbz?AddNewUserDirect">
            <input type="email" class="email" name="EMAIL" placeholder="Your email address" maxlength="64"/>
            <input name="FEEDID" type="hidden" value="926643"/>
            <input name="PUBLISHER" type="hidden" value="34610190"/>
            <!-- <a onclick="FeedBlitz_9feab01d431311e39e69002590771423s(this.form);" class="btn">Submit</a> -->
            <input onclick="FeedBlitz_9feab01d431311e39e69002590771423s(this.form);" type="button"
                   class="btn btnSubmitFooter" value="Submit"/>
            <input type="hidden" name="na" value="s"/>
            <input type="hidden" name="nr" value="widget"/>
          </form>
          <script language="Javascript">function FeedBlitz_9feab01d431311e39e69002590771423i() {
              var x = document.getElementsByName('FeedBlitz_9feab01d431311e39e69002590771423');
              for (i = 0; i < x.length; i++) {
                x[i].EMAIL.style.display = 'block';
                x[i].action = 'http://www.feedblitz.com/f/f.fbz?AddNewUserDirect';
              }
            }
            function FeedBlitz_9feab01d431311e39e69002590771423s(v) {
              v.submit();
            }
            FeedBlitz_9feab01d431311e39e69002590771423i();</script>

        </div>
      </section>
    </div>
    <div class="copyright">
      <section>
        </br>
        © 2014 topcoder. All Rights reserved.
        </br>
        <a href="/community/how-it-works/privacy-policy/" class="privacyStmtLink">Privacy Policy</a> | <a
          href="/community/how-it-works/terms/" class="legalDisclaimerLink">Terms</a>
      </section>
    </div>
    <div class="clear"></div>
  </div>
</footer>
<!-- /#footer -->
</div>
<!-- /.content -->
</div>
<!-- /#wrapper -->


<div id="bgModal"></div><!-- background modal -->
<div id="thanks" class="modal">
  <a href="javascript:;" class="closeBtn closeModal"></a>

  <div class="content">
    <h2>Thanks for joining!</h2>

    <p>We have sent you an email with activation instructions.<br/>If you do not receive that email within 1 hour,
      please email <a href="mailto:support@topcoder.com">support@topcoder.com</a></p>

    <div>
      <a href="/" class="btn closeModal">Close</a>
    </div>
  </div>
</div><!-- END #thanks -->
<div id="registerSuccess" class="modal">
  <a href="javascript:;" class="btnClose closeModal"></a>
  <div class="content">
    <h2>Registered!</h2>
    <p class="success">Thank you for registering. You may now download the challenge files and participate in the challenge forums.</p>
    <p class="submitBtn">
      <a class="btn closeModal" href="javascript:;">Ok</a>
    </p>
  </div>
</div><!-- END #registerSuccess -->
<div id="registerFailed" class="modal">
  <a href="javascript:;" class="btnClose closeModal"></a>
  <div class="content">
    <h2>Info</h2>
    <p class="failedMessage"></p>
    <p class="submitBtn">
      <a class="btn closeModal" href="javascript:;">Ok</a>
    </p>
  </div>
</div><!-- END #registerFailed -->
<div id="register" class="modal">
<a href="javascript:;" class="btnClose closeModal"></a>

<div class="content">
<h2>Register Using An Existing Account</h2>

<div id="socials">
  <a class="register-facebook" href="javascript:;"><span class="animeButton shareFacebook"><span
        class="shareFacebookHover animeButtonHover"></span></span></a>
  <a class="register-google" href="javascript:;"><span class="animeButton shareGoogle"><span
        class="shareGoogleHover animeButtonHover"></span></span></a>
  <a class="register-twitter" href="javascript:;"><span class="animeButton shareTwitter"><span
        class="shareTwitterHover animeButtonHover"></span></span></a>
  <a class="register-github" href="javascript:;"><span class="animeButton shareGithub"><span
        class="shareGithubHover animeButtonHover"></span></span></a>

  <p>Using an existing account is quick and easy.<br/>Select the account you would like to use and we'll do the rest for
    you</p>

  <div class="clear"></div>
</div>
<!-- END .socials -->
<h2>Or Register Using Your Email</h2>

<form class="register" id="registerForm">
<p class="row">
  <label>First Name</label>
  <input type="text" class="name firstName" placeholder="First Name"/>
  <span class="err1">Required field</span>
  <span class="err2">Maximum length is 64 characters</span>
  <span class="valid"></span>
</p>

<p class="row">
  <label>Last Name</label>
  <input type="text" class="name lastName" placeholder="Last Name"/>
  <span class="err1">Required field</span>
  <span class="err2">Maximum length is 64 characters</span>
  <span class="valid"></span>
</p>

<p class="row">
  <label>Handle</label>
  <input type="text" class="handle name" placeholder="Handle"/>
  <span class="err1">Required field</span>
  <span class="err2">Handle already exists or is invalid</span>
  <span class="err3">Handle cannot contain a space</span>
  <span class="err4">Handle cannot consist solely of punctuation</span>
  <span class="err5">Handle contains invalid characters</span>
  <span class="err6">Handle cannot start with "admin"</span>
  <span class="err7">Handle must be between 2 and 15 characters long</span>
  <span class="valid"></span>
</p>

<p class="row">
<label>Country</label>
<select name="user.country" id="selCountry">
<option value="">Please Select</option>
<option value="Afganistan">Afghanistan</option>
<option value="Albania">Albania</option>
<option value="Algeria">Algeria</option>
<option value="American Samoa">American Samoa</option>
<option value="Andorra">Andorra</option>
<option value="Angola">Angola</option>
<option value="Anguilla">Anguilla</option>
<option value="Antigua &amp; Barbuda">Antigua &amp; Barbuda</option>
<option value="Argentina">Argentina</option>
<option value="Armenia">Armenia</option>
<option value="Aruba">Aruba</option>
<option value="Australia">Australia</option>
<option value="Austria">Austria</option>
<option value="Azerbaijan">Azerbaijan</option>
<option value="Bahamas">Bahamas</option>
<option value="Bahrain">Bahrain</option>
<option value="Bangladesh">Bangladesh</option>
<option value="Barbados">Barbados</option>
<option value="Belarus">Belarus</option>
<option value="Belgium">Belgium</option>
<option value="Belize">Belize</option>
<option value="Benin">Benin</option>
<option value="Bermuda">Bermuda</option>
<option value="Bhutan">Bhutan</option>
<option value="Bolivia">Bolivia</option>
<option value="Bonaire">Bonaire</option>
<option value="Bosnia &amp; Herzegovina">Bosnia &amp; Herzegovina</option>
<option value="Botswana">Botswana</option>
<option value="Brazil">Brazil</option>
<option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
<option value="Brunei">Brunei</option>
<option value="Bulgaria">Bulgaria</option>
<option value="Burkina Faso">Burkina Faso</option>
<option value="Burundi">Burundi</option>
<option value="Cambodia">Cambodia</option>
<option value="Cameroon">Cameroon</option>
<option value="Canada">Canada</option>
<option value="Canary Islands">Canary Islands</option>
<option value="Cape Verde">Cape Verde</option>
<option value="Cayman Islands">Cayman Islands</option>
<option value="Central African Republic">Central African Republic</option>
<option value="Chad">Chad</option>
<option value="Channel Islands">Channel Islands</option>
<option value="Chile">Chile</option>
<option value="China">China</option>
<option value="Christmas Island">Christmas Island</option>
<option value="Cocos Island">Cocos Island</option>
<option value="Colombia">Colombia</option>
<option value="Comoros">Comoros</option>
<option value="Congo">Congo</option>
<option value="Cook Islands">Cook Islands</option>
<option value="Costa Rica">Costa Rica</option>
<option value="Cote DIvoire">Cote D'Ivoire</option>
<option value="Croatia">Croatia</option>
<option value="Cuba">Cuba</option>
<option value="Curaco">Curacao</option>
<option value="Cyprus">Cyprus</option>
<option value="Czech Republic">Czech Republic</option>
<option value="Denmark">Denmark</option>
<option value="Djibouti">Djibouti</option>
<option value="Dominica">Dominica</option>
<option value="Dominican Republic">Dominican Republic</option>
<option value="East Timor">East Timor</option>
<option value="Ecuador">Ecuador</option>
<option value="Egypt">Egypt</option>
<option value="El Salvador">El Salvador</option>
<option value="Equatorial Guinea">Equatorial Guinea</option>
<option value="Eritrea">Eritrea</option>
<option value="Estonia">Estonia</option>
<option value="Ethiopia">Ethiopia</option>
<option value="Falkland Islands">Falkland Islands</option>
<option value="Faroe Islands">Faroe Islands</option>
<option value="Fiji">Fiji</option>
<option value="Finland">Finland</option>
<option value="France">France</option>
<option value="French Guiana">French Guiana</option>
<option value="French Polynesia">French Polynesia</option>
<option value="French Southern Ter">French Southern Ter</option>
<option value="Gabon">Gabon</option>
<option value="Gambia">Gambia</option>
<option value="Georgia">Georgia</option>
<option value="Germany">Germany</option>
<option value="Ghana">Ghana</option>
<option value="Gibraltar">Gibraltar</option>
<option value="Great Britain">Great Britain</option>
<option value="Greece">Greece</option>
<option value="Greenland">Greenland</option>
<option value="Grenada">Grenada</option>
<option value="Guadeloupe">Guadeloupe</option>
<option value="Guam">Guam</option>
<option value="Guatemala">Guatemala</option>
<option value="Guinea">Guinea</option>
<option value="Guyana">Guyana</option>
<option value="Haiti">Haiti</option>
<option value="Hawaii">Hawaii</option>
<option value="Honduras">Honduras</option>
<option value="Hong Kong">Hong Kong</option>
<option value="Hungary">Hungary</option>
<option value="Iceland">Iceland</option>
<option value="India">India</option>
<option value="Indonesia">Indonesia</option>
<option value="Iran">Iran</option>
<option value="Iraq">Iraq</option>
<option value="Ireland">Ireland</option>
<option value="Isle of Man">Isle of Man</option>
<option value="Israel">Israel</option>
<option value="Italy">Italy</option>
<option value="Jamaica">Jamaica</option>
<option value="Japan">Japan</option>
<option value="Jordan">Jordan</option>
<option value="Kazakhstan">Kazakhstan</option>
<option value="Kenya">Kenya</option>
<option value="Kiribati">Kiribati</option>
<option value="Korea North">Korea North</option>
<option value="Korea Sout">Korea South</option>
<option value="Kuwait">Kuwait</option>
<option value="Kyrgyzstan">Kyrgyzstan</option>
<option value="Laos">Laos</option>
<option value="Latvia">Latvia</option>
<option value="Lebanon">Lebanon</option>
<option value="Lesotho">Lesotho</option>
<option value="Liberia">Liberia</option>
<option value="Libya">Libya</option>
<option value="Liechtenstein">Liechtenstein</option>
<option value="Lithuania">Lithuania</option>
<option value="Luxembourg">Luxembourg</option>
<option value="Macau">Macau</option>
<option value="Macedonia">Macedonia</option>
<option value="Madagascar">Madagascar</option>
<option value="Malaysia">Malaysia</option>
<option value="Malawi">Malawi</option>
<option value="Maldives">Maldives</option>
<option value="Mali">Mali</option>
<option value="Malta">Malta</option>
<option value="Marshall Islands">Marshall Islands</option>
<option value="Martinique">Martinique</option>
<option value="Mauritania">Mauritania</option>
<option value="Mauritius">Mauritius</option>
<option value="Mayotte">Mayotte</option>
<option value="Mexico">Mexico</option>
<option value="Midway Islands">Midway Islands</option>
<option value="Moldova">Moldova</option>
<option value="Monaco">Monaco</option>
<option value="Mongolia">Mongolia</option>
<option value="Montserrat">Montserrat</option>
<option value="Morocco">Morocco</option>
<option value="Mozambique">Mozambique</option>
<option value="Myanmar">Myanmar</option>
<option value="Nambia">Nambia</option>
<option value="Nauru">Nauru</option>
<option value="Nepal">Nepal</option>
<option value="Netherland Antilles">Netherland Antilles</option>
<option value="Netherlands">Netherlands (Holland, Europe)</option>
<option value="Nevis">Nevis</option>
<option value="New Caledonia">New Caledonia</option>
<option value="New Zealand">New Zealand</option>
<option value="Nicaragua">Nicaragua</option>
<option value="Niger">Niger</option>
<option value="Nigeria">Nigeria</option>
<option value="Niue">Niue</option>
<option value="Norfolk Island">Norfolk Island</option>
<option value="Norway">Norway</option>
<option value="Oman">Oman</option>
<option value="Pakistan">Pakistan</option>
<option value="Palau Island">Palau Island</option>
<option value="Palestine">Palestine</option>
<option value="Panama">Panama</option>
<option value="Papua New Guinea">Papua New Guinea</option>
<option value="Paraguay">Paraguay</option>
<option value="Peru">Peru</option>
<option value="Phillipines">Philippines</option>
<option value="Pitcairn Island">Pitcairn Island</option>
<option value="Poland">Poland</option>
<option value="Portugal">Portugal</option>
<option value="Puerto Rico">Puerto Rico</option>
<option value="Qatar">Qatar</option>
<option value="Republic of Montenegro">Republic of Montenegro</option>
<option value="Republic of Serbia">Republic of Serbia</option>
<option value="Reunion">Reunion</option>
<option value="Romania">Romania</option>
<option value="Russia">Russia</option>
<option value="Rwanda">Rwanda</option>
<option value="St Barthelemy">St Barthelemy</option>
<option value="St Eustatius">St Eustatius</option>
<option value="St Helena">St Helena</option>
<option value="St Kitts-Nevis">St Kitts-Nevis</option>
<option value="St Lucia">St Lucia</option>
<option value="St Maarten">St Maarten</option>
<option value="St Pierre &amp; Miquelon">St Pierre &amp; Miquelon</option>
<option value="St Vincent &amp; Grenadines">St Vincent &amp; Grenadines</option>
<option value="Saipan">Saipan</option>
<option value="Samoa">Samoa</option>
<option value="Samoa American">Samoa American</option>
<option value="San Marino">San Marino</option>
<option value="Sao Tome &amp; Principe">Sao Tome &amp; Principe</option>
<option value="Saudi Arabia">Saudi Arabia</option>
<option value="Senegal">Senegal</option>
<option value="Serbia">Serbia</option>
<option value="Seychelles">Seychelles</option>
<option value="Sierra Leone">Sierra Leone</option>
<option value="Singapore">Singapore</option>
<option value="Slovakia">Slovakia</option>
<option value="Slovenia">Slovenia</option>
<option value="Solomon Islands">Solomon Islands</option>
<option value="Somalia">Somalia</option>
<option value="South Africa">South Africa</option>
<option value="Spain">Spain</option>
<option value="Sri Lanka">Sri Lanka</option>
<option value="Sudan">Sudan</option>
<option value="Suriname">Suriname</option>
<option value="Swaziland">Swaziland</option>
<option value="Sweden">Sweden</option>
<option value="Switzerland">Switzerland</option>
<option value="Syria">Syria</option>
<option value="Tahiti">Tahiti</option>
<option value="Taiwan">Taiwan</option>
<option value="Tajikistan">Tajikistan</option>
<option value="Tanzania">Tanzania</option>
<option value="Thailand">Thailand</option>
<option value="Togo">Togo</option>
<option value="Tokelau">Tokelau</option>
<option value="Tonga">Tonga</option>
<option value="Trinidad &amp; Tobago">Trinidad &amp; Tobago</option>
<option value="Tunisia">Tunisia</option>
<option value="Turkey">Turkey</option>
<option value="Turkmenistan">Turkmenistan</option>
<option value="Turks &amp; Caicos Is">Turks &amp; Caicos Is</option>
<option value="Tuvalu">Tuvalu</option>
<option value="Uganda">Uganda</option>
<option value="Ukraine">Ukraine</option>
<option value="United Arab Erimates">United Arab Emirates</option>
<option value="United Kingdom">United Kingdom</option>
<option value="United States">United States of America</option>
<option value="Uraguay">Uruguay</option>
<option value="Uzbekistan">Uzbekistan</option>
<option value="Vanuatu">Vanuatu</option>
<option value="Vatican City State">Vatican City State</option>
<option value="Venezuela">Venezuela</option>
<option value="Vietnam">Vietnam</option>
<option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
<option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
<option value="Wake Island">Wake Island</option>
<option value="Wallis &amp; Futana Is">Wallis &amp; Futana Is</option>
<option value="Yemen">Yemen</option>
<option value="Zaire">Zaire</option>
<option value="Zambia">Zambia</option>
<option value="Zimbabwe">Zimbabwe</option>
</select>
<span class="err1">Required field</span>
<span class="valid"></span>
</p>
<p class="row">
  <label>Email</label>
  <input type="text" class="email" placeholder="Email"/>
  <span class="err1">Required field</span>
  <span class="err2">Invalid email address</span>
  <span class="valid"></span>
</p>

<p class="row">
  <label>Password</label>
  <input type="password" class="pwd" placeholder="Password"/>
  <span class="err1">Required field</span>
  <span class="err2">Password strength is weak</span>
  <span class="err4">Password must be between 7 and 30 characters</span>
  <span class="valid">Strong</span>
</p>

<p class="row info lSpace">
					<span class="strength">
						<span class="field"></span>
						<span class="field"></span>
						<span class="field"></span>
						<span class="field"></span>
					</span>
  7 characters with letters, numbers, &amp; symbols
</p>

<p class="row">
  <label>Password Confirmation</label>
  <input type="password" class="confirm" placeholder="Password Confirmation"/>
  <span class="err1">Required field</span>
  <span class="err2">Password confirmation different from above field</span>
  <span class="valid"></span>
</p>

<p class="row lSpace">
  <label><input type="checkbox">I agree to the <a target="_blank" href="/community/how-it-works/terms/">terms of
      service</a> and <a target="_blank" href="/community/how-it-works/privacy-policy/">privacy policy</a></label>
  <span class="err1">You must agree to the terms</span>
  <span class="err2">You must agree to the terms</span>
</p>

</form>
<!-- END .form register -->
<div class="clear"></div>
<p class="submitBtn">
  <a href="javascript:;" class="btn btnSubmit">Sign Up</a>
</p>
</div>
</div><!-- END #register -->
<div id="login" class="modal">
  <a href="javascript:;" class="btnClose closeModal"></a>

  <div class="content">
    <h2>Login Using An Existing Account</h2>

    <div id="socials">
      <a class="signin-facebook" href="javascript:;"><span class="animeButton shareFacebook"><span
            class="shareFacebookHover animeButtonHover"></span></span></a>
      <a class="signin-google" href="javascript:;"><span class="animeButton shareGoogle"><span
            class="shareGoogleHover animeButtonHover"></span></span></a>
      <a class="signin-twitter" href="javascript:;"><span class="animeButton shareTwitter"><span
            class="shareTwitterHover animeButtonHover"></span></span></a>
      <a class="signin-github" href="javascript:;"><span class="animeButton shareGithub"><span
            class="shareGithubHover animeButtonHover"></span></span></a>

      <p>Using an existing account is quick and easy.<br/>Select the account you would like to use and we'll do the rest
        for you</p>

      <div class="clear"></div>
    </div>
    <!-- END .socials -->
    <h2>Login With a TopCoder Account</h2>

    <form class="login" id="loginForm">
      <p class="row">
        <label>Username</label>
        <input id="username" type="text" class="name" placeholder="Username"/>
        <span class="err1">Your username or password are incorrect</span>
        <span class="err3">Please input your username</span>
      </p>

      <p class="row">
        <label>Password</label>
        <input id="password" type="password" class="pwd" placeholder="Password"/>
        <span class="err4">Please input your password</span>
      </p>

      <p class="row lSpace">
        <label><input type="checkbox"/>Remember me</label>
      </p>

      <p class="row lSpace btns">
        <a href="javascript:;" class="signin-db btn btnSubmit">Login</a>
		<?php
			$fp_page = get_page_by_path( 'password-recovery' );
			$fp_link = get_permalink($fp_page->ID);
		?>
        <a href="<?php echo $fp_link; //http://community.topcoder.com/tc?module=FindUser ?>" target="_blank" class="forgotPwd">Forgot
          password?</a>
      </p>

      <div class="clear"></div>
    </form>
    <!-- END .form login -->
  </div>
</div><!-- END #login -->

<div id="typeTooltip" class="tooltip hide">
  <div class="inner">
    <header></header>
    <div class="data">
      <p class="contestTy"></p>
    </div>
    <div class="arrow"></div>
  </div>
</div><!-- /.tooltip -->
<div id="winnerTooltip" class="tooltip hide">
  <div class="inner">
    <header></header>
    <div class="data">
      <div class="winnerInfo"></div>
    </div>
    <div class="arrow alt"></div>
  </div>
</div><!-- /.tooltip -->

<?php wp_footer(); ?>
<script>
  var socialProviderId = "", socialUserName = "", socialEmail = "", socialProvider = "";
  $(function () {
    var googleProvider = "google-oauth2";
    var facebookProvider = "facebook";
    var twitterProvider = "twitter";
    var githubProvider = "github";
    <?php
    $urlFromDiscourse = $_REQUEST["url"];
	$stateLogin = "none";
	if ($_REQUEST['sso'] != ''  and $_REQUEST['sig'] != '' ) {
		// if ( preg_match('/sso=(.*)&sig=(.*)/', $urlFromDiscourse, $matches) ){
		//$sso = str_replace("=","%3D%0A",$matches[1]);
		//$sig = $matches[2];
		$sso = urlencode($_REQUEST['sso']);
		$sig = $_REQUEST['sig'];
		$stateLogin = "http://talk.topcoder.com/session/sso_login?sso=$sso&sig=$sig";
	}

    ?>

    var loginState = "<?php echo $stateLogin; ?>";

    if (loginState == 'none') {
      loginState = window.location.href;
    }
    var auth0Login = new Auth0({
      domain: 'topcoder.auth0.com',
      clientID: '6ZwZEUo2ZK4c50aLPpgupeg5v2Ffxp9P',
      callbackURL: 'https://www.topcoder.com/reg2/callback.action',
      state: loginState,
      redirect_uri: window.location.href
    });

    var auth0Register = new Auth0({
      domain: 'topcoder.auth0.com',
      clientID: '6ZwZEUo2ZK4c50aLPpgupeg5v2Ffxp9P',
      callbackURL: 'http://www.topcoder.com/?action=callback',
      state: loginState,
      redirect_uri: window.location.href
    });

    auth0Register.getProfile(window.location.hash, function (err, profile, id_token, access_token, state) {
      socialProvider = profile.identities[0].connection;
      var firstName = "" , lastName = "", handle = "", email = "";
      if(socialProvider === googleProvider) {
        firstName = profile.given_name;
        lastName = profile.family_name;
        handle = profile.nickname;
        email = profile.email;
        socialProviderId = 2;
      } else if (socialProvider === facebookProvider) {
        firstName = profile.given_name;
        lastName = profile.family_name;
        handle = firstName + '.' + lastName;
        email = profile.email;
        socialProviderId = 1;
      } else if (socialProvider === twitterProvider) {
        var splitName = profile.name.split(" ");
        firstName = splitName[0];
        if (splitName.length > 1) {
          lastName = splitName[1];
        }
        handle = profile.screen_name;
        socialProviderId = 3;
      } else if (socialProvider === githubProvider) {
        firstName = lastName = '';
        handle = profile.nickname;
        email = profile.email;
        socialProviderId = 4;
      }
      socialUserName = handle;
      socialUserId = profile.user_id.split('|')[1];
      socialEmail = profile.email;
      $("#registerForm .firstName").val(firstName);
      $("#registerForm .lastName").val(lastName);
      $("#registerForm .handle").val(handle);
      $("#registerForm .email").val(email);

      // trigger validation
      $('input.pwd:password').trigger('keyup');
      $('#register form.register input.email:text').trigger('keyup');
      $('#register form.register input.name:text').trigger('keyup');
      $('#register form.register input.handle:text').trigger('keyup');
      $('#register form.register input.handle:text').trigger('blur');
      $('#register form.register input:checkbox').trigger('change');
      $('#register input:password').on('keyup');
      $('select').on('change');

  }, function(err) {
    console.log('error');
    console.log(err);
  });

    $('.register-google').on('click', function () {
      auth0Register.login({
        connection: googleProvider,
        state: loginState,
        response_type: 'token'}); // this tells Auth0 to send the user back to the main site after login. Please replace the var for current page URL.
    });

    $('.register-facebook').on('click', function () {
      auth0Register.login({connection: facebookProvider,
        state: loginState,
        response_type: 'token'}); // this tells Auth0 to send the user back to the main site after login. Please replace the var for current page URL.
    });

    $('.register-twitter').on('click', function () {
      auth0Register.login({connection: twitterProvider,
        state: loginState,
        response_type: 'token'}); // this tells Auth0 to send the user back to the main site after login. Please replace the var for current page URL.
    });

    $('.register-github').on('click', function () {
      auth0Register.login({connection: githubProvider,
        state: loginState,
        response_type: 'token'});  // this tells Auth0 to send the user back to the main site after login. Please replace the var for current page URL.
    });

    $('.signin-google').on('click', function () {
      auth0Login.login({
        connection: 'google-oauth2',
        state: loginState}); // this tells Auth0 to send the user back to the main site after login. Please replace the var for current page URL.
    });

    $('.signin-facebook').on('click', function () {
      auth0Login.login({connection: 'facebook',
        state: loginState}); // this tells Auth0 to send the user back to the main site after login. Please replace the var for current page URL.
    });

    $('.signin-twitter').on('click', function () {
      auth0Login.login({connection: 'twitter',
        state: loginState}); // this tells Auth0 to send the user back to the main site after login. Please replace the var for current page URL.
    });

    $('.signin-github').on('click', function () {
      auth0Login.login({connection: 'github',
        state: loginState});  // this tells Auth0 to send the user back to the main site after login. Please replace the var for current page URL.
    });

    $('.signin-etc').on('click', function () {
      auth0Login.login({connection: 'connection-name',
        state: loginState}); // this tells Auth0 to send the user back to the main site after login. Please replace the var for current page URL.
    });

    $('.signin-db').on('click', function () {
      var empty = false;
      if ($('#username').val().trim() == '') {
        empty = true;
        $('#loginForm span.err3').show();
        $('#username').addClass('invalid');
      }
      if ($('#password').val().trim() == '') {
        empty = true;
        $('#loginForm span.err4').show();
        $('#password').addClass('invalid');
      }
      if (empty) return;
      auth0Login.login({
          connection: 'LDAP',
          state: loginState, // this tells Auth0 to send the user back to the main site after login. Please replace the var for current page URL.
          username: document.getElementById('username').value,
          password: document.getElementById('password').value
        },
        function (err) {
          // invalid user/password
          //alert(err);
          $('#loginForm .btnSubmit').html('Login');
          $('#loginForm .err1').show().html('Incorrect Username or Password')
            .addClass('invalid');
        });
    });
  });
</script>

</div>

</body>

</html>

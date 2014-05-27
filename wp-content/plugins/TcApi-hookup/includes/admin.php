<?php
class TCHOOK_Admin extends TCHOOK_Plugin {
	protected function init() {
		/* plugin activation */
		register_activation_hook ( $this->_config->plugin_file, array (
				$this,
				'activate' 
		) );
		
		/* plugin deactivation */
		register_deactivation_hook ( $this->_config->plugin_file, array (
				$this,
				'deactivate' 
		) );
		
		if ($_POST ['submit'] == 'Update Options') {
			tcapi_settings_update ();
		} // check options update
	}
	
	// plugin activate code
	public function activate() {
		/* rewrite rule */
		function tcapi_rewrite_rules() {
			// Active Contest
			add_rewrite_rule ( '^active-contests/([^/]*)/?$', 'index.php?pagename=active-contests&contest_type=$matches[1]', 'top' );
			add_rewrite_rule ( '^active-contests/([^/]*)/([0-9]*)/?$', 'index.php?pagename=active-contests&contest_type=$matches[1]&pages=$matches[2]', 'top' );
			
			// Past Contest
			add_rewrite_rule ( '^past-contests/([^/]*)/?$', 'index.php?pagename=past-contests&contest_type=$matches[1]', 'top' );
			add_rewrite_rule ( '^past-contests/([^/]*)/([0-9]*)/?$', 'index.php?pagename=past-contests&contest_type=$matches[1]&pages=$matches[2]', 'top' );
			
			// Member Profile
			add_rewrite_rule ( '^member-profile/([^/]*)/?$', 'index.php?pagename=member-profile&handle=$matches[1]', 'top' );
		}
		tcapi_rewrite_rules ();
		/* flush */
		flush_rewrite_rules ();
	}
	
	// plugin deactivate code
	public function deactivate() {
		/* flush */
		flush_rewrite_rules ();
	}
}
// init variables
function initvars() {
	add_option ( 'contest_per_page', "30" );
	add_option ( 'httpversion', "1.1" );
	add_option ( 'request_timeout', "30" );
	add_option ( 'som', "July 2013" );
        add_option ( 'tc_api_url', 'https://api.topcoder.com/v2' );
}
initvars ();
add_action ( 'admin_menu', 'tcapi_settings' );
// Plugin setting
function tcapi_settings() {
	add_menu_page ( 'TopCoder API', 'TopCoder API', 'administrator', 'tcapi_settings', 'tcapi_display_settings' );
}
function tcapi_display_settings() {
	$html = '<h2>TopCoder API settings</h2>
						<form method="POST" action="">							
						<h3>General Option</h3>
	            <p>
	                <label for="contest_per_page"><strong>Contest per page :</strong>  </label><br />
	                <input type="text" name="contest_per_page" id="contest_per_page" size="80" value="' . get_option ( 'contest_per_page' ) . '"/>   
	            </p>
              <p>
	                <label for="httpversion"><strong>Httpversion :</strong> (Enter httpversion of TopCoder API\'s response)  </label><br />
	                <input type="text" name="httpversion" id="httpversion" size="80" value="' . get_option ( 'httpversion' ) . '"/>   
	            </p>
              <p>
	                <label for="request_timeout"><strong>Request Timeout :</strong> (Specify how long to wait for server response)  </label><br />
	                <input type="text" name="request_timeout" id="request_timeout" size="80" value="' . get_option ( 'request_timeout' ) . '"/>   
	            </p>
	                		 <p>
	                <label for="tc_api_url"><strong>API Server URL :</strong> (Enter base URL for API server - e.g., https://api.topcoder.com)  </label><br />
	                <input type="text" name="tc_api_url" id="tc_api_url" size="80" value="' . get_option ( 'tc_api_url' ) . '"/>   
	            </p>
	                <br/> 		
						<h3>Other options</h3>
	                		 <p>
	                <label for="som"><strong>Time for star of month :</strong>  </label><br />
	                <input type="text" name="som" id="som" size="80" value="' . get_option ( 'som' ) . '"/>   
	            </p>
	            <br/>
	            <p><input type="submit" name="submit" value="Update Options" class="button button-primary" /></p>   		
           </form>
			';
	echo $html;
}

// Update settings
function tcapi_settings_update() {
	update_option ( 'contest_per_page', $_POST ['contest_per_page'] );
	update_option ( 'request_timeout', $_POST ['request_timeout'] );
	update_option ( 'httpversion', $_POST ['httpversion'] );
	update_option ( 'som', $_POST ['som'] );
	update_option ( 'tc_api_url', $_POST ['tc_api_url'] );
}



/* Register widgets */
include_once 'search_contests_widget.php';

add_action ( 'widgets_init', 'load_widgets' );
function load_widgets() {
	return register_widget ( "Search_contests_widget" );
}

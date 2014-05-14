<?php

/**
 * @file
 * Enqueue scripts and stylesheets
 *
 * CDN will also be used for jquery.  We will have a local fallback
 * The theme option jsCssUseCDN will be used to say if local files will be on the cdn.
 *  Grunt script is what pushes the script.
 * Theme option jsCssUseMin will be used to only enqueue the min version.
 *  Grunt will create a style.css and script.js file that will contain all of the local code
 */

function tcs_responsive_scripts() {
  $auth0_client_id = auth0_client_id;

  // register scripts that are used everywhere
  $assets = array(
    'jquery' => '//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js',
    'jquery_ui' => '//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js',
    'fonts' => '//fonts.googleapis.com/css?family=Source+Sans+Pro:700,400',
    'respond'   =>  '/js/vendor/respond.min.js',
    'modernizr' => '/js/vendor/modernizr.js',
    'html5shiv' => '/js/vendor/html5shiv.js',
    'auth0' => '//cdn.auth0.com/w2/auth0-1.6.4.js'
  );

  tsc_register_master($assets);

  $jsCssUseMin = get_option("jsCssUseMin", false);
  $template_map = tsc_get_asset_map();
  $page_template = get_page_template_slug(get_queried_object_id());
  $ver = get_option('jsCssVersioning') == 1 ? get_option('jsCssCurrentVersion') : '1';
  $jsCssUseCDN = get_option("jsCssUseCDN", false);

  if (isset($template_map[$page_template])) {
    $asset_map = $template_map[$page_template];
  } else {
    $asset_map = $template_map['default'];
  }

  if ($jsCssUseMin && $jsCssUseCDN) {
    // version number is in url when using cdn
    wp_register_script($asset_map['name'],
      tsc_build_asset_path($asset_map['name'], 'js', true, true),
      array('jquery', 'jquery_ui', 'auth0'), null, true);

    wp_register_style($asset_map['name'], tsc_build_asset_path($asset_map['name'], 'css', true, true));

    wp_enqueue_script($asset_map['name']);
    wp_enqueue_style($asset_map['name']);
  } elseif ($jsCssUseMin) {
    // version number is in url when using cdn
    wp_register_script($asset_map['name'],
      tsc_build_asset_path($asset_map['name'], 'js', true),
      array('jquery', 'jquery_ui', 'auth0'), $ver, true);

    wp_register_style($asset_map['name'], tsc_build_asset_path($asset_map['name'], 'css', true), array(), $ver);

    wp_enqueue_script($asset_map['name']);
    wp_enqueue_style($asset_map['name']);
  } else {
    $i = 0;
    foreach ($asset_map['js'] as $js_script) {
      if ($i == 0) {
        wp_register_script("custom-{$i}", tsc_build_asset_path($js_script, 'js'), array("jquery", "auth0"), $ver, true);
      } else {
        $j = $i -1;
        wp_register_script("custom-{$i}", tsc_build_asset_path($js_script, 'js'), array("custom-{$j}"), $ver, true);
      }
      wp_enqueue_script("custom-{$i}");
      $i++;
    }

    $i = 0;
    foreach ($asset_map['css'] as $css_script) {
      if ($i == 0) {
        wp_enqueue_style("custom-{$i}", tsc_build_asset_path($css_script, 'css'), array(), $ver);
      } else {
        $j = $i -1;
        wp_enqueue_style("custom-{$i}", tsc_build_asset_path($css_script, 'css'), array("custom-{$j}"), $ver);
      }
      $i++;
    }
  }
}

/**
 * Build the path to the assets
 *
 * @param $asset_name
 * @param string $type
 *  pass in "js" or "css" to help build the path if the file is concatinated
 * @param boolean $min
 * @return string
 */
function tsc_build_asset_path($asset_name, $type, $min = false, $useCDN = false) {
  static $base_path, $ext;

  if (!isset($base_path)) {
    $base_path = tsc_get_script_base_url();
  }

  if (!isset($ext)) {
    $ext = tsc_get_script_ext();
  }
  // if min and cdn use /ver/type/file
  if ($min && $useCDN) {
    $path =  "{$base_path}/{$type}/{$asset_name}.min.{$type}";
  } elseif ($min) {
    // min and not cdn then use add do not sort by type
    $path = "{$base_path}/{$asset_name}.min.{$type}";
  } else {
    $path = "{$base_path}/{$type}/{$asset_name}";
  }

  if ($ext) {
    $path .=  ".{$ext}";
  }

  return $path;
}

/**
 * Get the assets from json file
 *
 * @return array
 *  template => array('js' => array(), 'css' => array())
 */
function tsc_get_asset_map() {
  $template_map = get_transient(__FUNCTION__);

  if (!$template_map) {
    $json = file_get_contents(get_stylesheet_directory() .  '/config/script-register.json');
    $json = json_decode($json, true);

    // type should be either "packages" or "templates
    $template_map = array();

    foreach ($json['templates'] as $template => $package) {
      $template_map[$template] = $json['packages'][$package];
    }

    set_transient(__FUNCTION__, $template_map);
  }

  return $template_map;
}


/**
 * Get the base url to use to include
 *
 * @return string
 */
function tsc_get_script_base_url() {

  $jsCssUseCDN = get_option("jsCssUseCDN", false);
  $jsCssCDNBase = get_option("jsCssCDNBase", THEME_URL);
  $jsCssVersioning = get_option("jsCssVersioning", false);
  $jsCssCurrentVersion = get_option("jsCssCurrentVersion", false);
  $jsCssUseMin = get_option("jsCssUseMin", false);

  // Setup up base url if using cdn.  safe fallback to theme url
  $base_url = $jsCssUseCDN ? $jsCssCDNBase : THEME_URL;

  // if we are using cdn and version then add the version to the path
  if ($jsCssUseCDN && $jsCssVersioning) {
    $base_url .= '/' . $jsCssCurrentVersion;
  } elseif ($jsCssUseMin) {
    $base_url .= '/dist';
  }

  return $base_url;
}

/**
 * Get the extension to use for the scripts
 *
 * @return string
 */
function tsc_get_script_ext() {
  $jsCssUseCDN = get_option("jsCssUseCDN", false);

  // check if the browser supports gzip so we can specify the gzip version of resources
  $ext = '';
  if ($jsCssUseCDN && strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false) {
    $ext = 'gz';
  }

  return $ext;
}

/**
 * Register assets uses that are outside of local cat/min/cdn
 *
 * @param $assets array
 */
function tsc_register_master($assets) {
  // Add fonts
  wp_register_style('fonts', $assets['fonts']);
  wp_enqueue_style('fonts');

  // Setup jquery cdn and put in the header
  wp_deregister_script('jquery');
  wp_register_script('jquery', $assets['jquery'], array());
  wp_enqueue_script('jquery');

  wp_register_script('jquery_ui', $assets['jquery_ui'], array('jquery'), null, true);
  wp_enqueue_script('jquery_ui');
  add_filter('script_loader_src', 'tsc_jquery_local_fallback', 10, 2);

  // Always include auth0
  wp_register_script("auth0", $assets['auth0'], array(), null, true);
  wp_enqueue_script("auth0");
}



add_action('wp_enqueue_scripts', 'tcs_responsive_scripts', 100);

// http://wordpress.stackexchange.com/a/12450
function tsc_jquery_local_fallback($src, $handle = null) {
  static $add_jquery_fallback = false;

  if ($add_jquery_fallback) {
    echo '<script>window.jQuery || document.write(\'<script src="' .  THEME_URL . '/js/vendor/jquery/jquery.js"><\/script>\')</script>' . "\n";
    $add_jquery_fallback = false;
  }

  if ($handle === 'jquery') {
    $add_jquery_fallback = true;
  }

  return $src;
}

add_action('wp_head', 'tsc_jquery_local_fallback');

function tc_setup_angular() {
  // Core Angular
  wp_register_script('angularjs', '//cdnjs.cloudflare.com/ajax/libs/angular.js/1.2.15/angular.min.js', array('jquery'), null, true);
  wp_enqueue_script('angularjs');

  // Angular Route
  wp_register_script('angularjs-route', '//cdnjs.cloudflare.com/ajax/libs/angular.js/1.2.15/angular-route.min.js', array('angularjs'), null, true);
  wp_enqueue_script('angularjs-route');

  // Add underscore, requirement for restangular
  wp_register_script('underscore', '//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.6.0/underscore-min.js', array('angularjs'), null, true);
  wp_enqueue_script('underscore');

  // restangular
  wp_register_script('angularjs-restangular', '//cdnjs.cloudflare.com/ajax/libs/restangular/1.4.0/restangular.min.js', array('angularjs-route', 'underscore'), null, true);
  wp_enqueue_script('angularjs-restangular');

  // ng-grid
  wp_register_script('ng-grid', '//cdnjs.cloudflare.com/ajax/libs/ng-grid/2.0.10/ng-grid.min.js', array('angularjs'), null, true);
  wp_enqueue_script('ng-grid');

  wp_register_style('ng-grid', '//cdnjs.cloudflare.com/ajax/libs/ng-grid/2.0.10/ng-grid.min.css');
  wp_enqueue_style('ng-grid');
}
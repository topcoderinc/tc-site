<?php

function find_property( $metatags, $propertyName, $type='property' ) {
  for ( $i=0; $i<count( $metatags ); $i++ ) {
    $metatag = $metatags[$i];
    if ( strpos( $metatag, $type . '="' . $propertyName . '"' ) !== false) {
      return $i;
    }
  }

  return -1;
}

function replace_metatag( $metatags, $propertyName, $value ) {
  $idx = find_property($metatags, $propertyName);
  if ($idx == -1)
    $metatags[] = '<meta property="' . $propertyName . '" content="' . $value . '" />';
  else
    $metatags[$idx] = '<meta property="' . $propertyName . '" content="' . $value . '" />';

  return $metatags;
}

function customize_amt_get_the_excerpt( $excerpt ) {
  $contestID = get_query_var('contestID');
  $contestType = $_GET['type'];
  if (isset($contestID) && isset($contestType)) {
    $contest = get_contest_detail('',$contestID, $contestType);
    if (isset($contest)) {
      $excerpt = substr(strip_tags($contest->detailedRequirements), 0, 200);
    }
  }

  $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
  $excerpt = $excerpt . ' ( More at ' . $protocol . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] . ')';

  return $excerpt;
}
add_filter( 'amt_get_the_excerpt', 'customize_amt_get_the_excerpt', 10, 1 );

function customize_amt_metadata_head( $metatags ) {
  $contestID = get_query_var('contestID');
  $contestType = $_GET['type'];
  if (isset($contestID) && isset($contestType)) {
    $contest = get_contest_detail('',$contestID, $contestType);
    if (isset($contest)) {
      $metatags = replace_metatag($metatags, 'og:title', $contest->challengeName);
      $metatags = replace_metatag($metatags, 'twitter:title', $contest->challengeName);
    }
  }

  $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
  $metatags = replace_metatag($metatags, 'og:url', $protocol . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] );

  return $metatags;
}
add_filter( 'amt_metadata_head', 'customize_amt_metadata_head', 10, 1 );


function customize_amt_schemaorg_metadata_content( $metatags ) {
  $idx = -1;
  $mts = array_slice($metatags, 0);

  while (($i = find_property($mts, 'name', 'itemprop')) > -1 ) {
    $idx = ($idx == -1) ? $i : $idx + $i + 1;
    $mts = array_slice($metatags, $idx+1);
  }

  $contestID = get_query_var('contestID');
  $contestType = $_GET['type'];
  if (isset($contestID) && isset($contestType)) {
    $contest = get_contest_detail('',$contestID, $contestType);
    if (isset($contest)) {
      if ($idx == -1)
        $metatags[] = '<meta itemprop="name" content="' . $contest->challengeName . '" />';
      else
        $metatags[$idx] = '<meta itemprop="name" content="' . $contest->challengeName . '" />';
    }
  }

  return $metatags;
}
add_filter( 'amt_schemaorg_metadata_content', 'customize_amt_schemaorg_metadata_content', 10, 1 );

function tc_wp_title( $title ) {
  $contestID = get_query_var('contestID');
  $contestType = $_GET['type'];
  if (isset($contestID) && isset($contestType)) {
    $contest = get_contest_detail('',$contestID, $contestType);
    if (isset($contest)) {
      $title = $contest->challengeName;
    }
  }

  return $title . get_bloginfo('name');
}
add_filter( 'wp_title', 'tc_wp_title', 10, 2 );

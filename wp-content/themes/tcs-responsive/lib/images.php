<?php

// add featured image
add_theme_support ( 'post-thumbnails' );
if (function_exists('add_theme_support')) {
  add_theme_support('post-thumbnails');
  set_post_thumbnail_size(55, 55); // default Post Thumbnail dimensions
}

if (function_exists('add_image_size')) {
  add_image_size('blog-thumb', 158, 154, TRUE);
  add_image_size('blog-thumb-mobile', 300, 165);
}

class fixImageMargins {
  public $xs = 0; //change this to change the amount of extra spacing

  public function __construct() {
    add_filter('img_caption_shortcode', array(&$this, 'fixme'), 10, 3);
  }

  public function fixme($x = NULL, $attr, $content) {

    extract(
      shortcode_atts(
        array(
          'id' => '',
          'align' => 'alignnone',
          'width' => '',
          'caption' => ''
        ),
        $attr
      )
    );

    if (1 > (int) $width || empty($caption)) {
      return $content;
    }

    if ($id) {
      $id = 'id="' . $id . '" ';
    }

    return '<div ' . $id . 'class="wp-caption ' . $align . '" style="width: ' . ((int) $width + $this->xs) . 'px">'
           . $content . '<p class="wp-caption-text">' . $caption . '</p></div>';
  }
}

global $fixImageMargins;
$fixImageMargins = new fixImageMargins();
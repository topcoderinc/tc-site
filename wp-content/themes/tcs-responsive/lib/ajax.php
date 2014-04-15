<?php

add_action('wp_ajax_get_blog_ajax', 'get_blog_ajax');
add_action('wp_ajax_nopriv_get_blog_ajax', 'get_blog_ajax');
function get_blog_ajax() {
  $postPerPage = get_option("posts_per_page") == "" ? 5 : get_option("posts_per_page");

  $catId = $_GET["catId"];
  $page = $_GET["page"];
  $searchKey = $_GET["searchKey"];
  $authorId = $_GET["authorId"];

  //wp_reset_query();
  $args = "post_type=" . BLOG;
  $args .= "&order=DESC";
  $args .= "&posts_per_page=" . $postPerPage;
  $args .= "&paged=$page";

  if ($catId != "") {
    $args .= "&cat=$catId";
  }
  else {
    if ($searchKey != "") {
      $args .= "&s=$searchKey";
    }
  }

  //$arrPost = query_posts($args);
  $postQuery = new WP_Query($args);
  $arrPost = $postQuery->get_posts();
  if ($arrPost != NULL) :
    foreach ($arrPost as $post) :

      $postId = $post->ID;
      $image = wp_get_attachment_image_src(get_post_thumbnail_id($postId), 'single-post-thumbnail');
      if ($image != NULL) {
        $imageUrl = $image[0];
      }
      else {
        $imageUrl = get_bloginfo('stylesheet_directory') . "/i/story-side-pic.png";
      }

      $dateObj = DateTime::createFromFormat('Y-m-d H:i:s', $post->post_date);
      $dateStr = $dateObj->format('M j, Y');

      $twitterText = urlencode(wrap_content_strip_html(wpautop($subject."\nUrl: ".get_permalink()), 130, true,'\n\r',''));      $title = htmlspecialchars($post->post_title);
      $subject = htmlspecialchars(get_bloginfo('name')) . ' : ' . $title;
      $body = htmlspecialchars($post->post_content);
      $email_article = 'mailto:?subject='.rawurlencode($subject).'&body='.get_permalink();
      $twitterShare = "http://twitter.com/home?status=" . $twitterText;
      $fbShare = "http://www.facebook.com/sharer/sharer.php?s=100&p[url]=" . get_permalink(
        ) . "&p[images][0]=" . $imageUrl . "&p[title]=" . get_the_title() . "&p[summary]=" . $twitterText;
      $gplusShare = "https://plus.google.com/share?url=" . get_permalink();

      $authorObj = get_user_by("id", $post->post_author);
      $authorName = $authorObj->display_name;
      $authorLink = get_bloginfo("wpurl") . "/author/" . $authorObj->user_nicename;
      ?>
      <!-- Blog Item -->
      <div class="blogItem">
        <?php if ($searchKey == "") : ?>
          <!-- Thumb place holder -->
          <div class="mobiThumbPlaceholder">
            <a href="<?php the_permalink(); ?>"><img src="<?php echo $imageUrl; ?>" width="300" height="160"/></a>
          </div>
          <!-- Thumb place holder end -->
        <?php endif; ?>

        <a href="<?php the_permalink(); ?>" class="blogTitle blueLink"><?php echo $post->post_title; ?><a>

            <!-- Blog Desc -->
            <div class="blogDescBox">
              <div class="postDate"><?php echo $dateStr; ?> &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; By:&nbsp;&nbsp;</div>
              <div class="postAuthor"><a href="<?php echo $authorLink; ?>"
                                         class="author blueLink"><?php echo $authorName; ?></a></div>
              <div class="postCategory">In :
                <?php
                $categories = get_the_category($postId);
                $separator = ', ';
                $output = '';
                if ($categories) {
                  foreach ($categories as $key => $category) {
                    if (strtolower($category->name) != BLOG) {
                      $output .= '<a href="' . get_category_link($category->term_id) . '" title="' . esc_attr(
                          sprintf(__("View all posts in %s"), $category->name)
                        ) . '">' . $category->cat_name . '</a>' . $separator;
                    }
                  }
                }
                echo trim($output, $separator);
                ?>
              </div>
            </div>
            <!-- Blog Desc End -->

            <?php if ($searchKey != "") : ?>
              <!-- content wrapper -->
              <div class="contentWrapper">
                <?php
                $excerpt = wrap_content_strip_html(wpautop($post->post_content), 400, TRUE, '\n\r', '');
                echo $excerpt;
                ?>
              </div>
              <!-- content wrapper end -->
              <a href="<?php the_permalink(); ?>" class="continueReading">Continue Reading</a>
            <?php else: ?>
              <!-- Blog Right Section -->
              <div class="blogRightSection">
                <!-- Imageplacehoder -->
                <div class="imagePlaceholder">
                  <a href="<?php the_permalink(); ?>"><img src="<?php echo $imageUrl; ?>" width="158" height="158"/></a>
                </div>
                <!-- Imageplacehoder End -->

                <!-- Content Right -->
                <div class="contentRight">
                  <div class="excerpt">
                    <?php
                    $excerpt = wrap_content_strip_html(wpautop($post->post_content), 400, TRUE, '\n\r', '');
                    echo $excerpt;
                    ?>
                  </div>
                  <div class="shareVia">
                    <span>Share via : </span>
                    <a href="<?php echo $email_article; ?>" class="shareButton"><img
                        src="<?php bloginfo('stylesheet_directory'); ?>/i/shares-mail.png" width="21" height="21"/></a>
                    <a href="<?php echo $fbShare; ?>" class="shareButton"><img
                        src="<?php bloginfo('stylesheet_directory'); ?>/i/shares-fb.png" width="21" height="21"/></a>
                    <a href="<?php echo $twitterShare; ?>" class="shareButton"><img
                        src="<?php bloginfo('stylesheet_directory'); ?>/i/shares-twitter.png" width="21"
                        height="21"/></a>
                    <a href="<?php echo $gplusShare; ?>" class="shareButton"><img
                        src="<?php bloginfo('stylesheet_directory'); ?>/i/shares-gplus.png" width="21" height="21"/></a>
                  </div>
                  <a href="<?php the_permalink(); ?>" class="continueReading">Continue Reading</a>
                </div>
                <!-- Content Right End -->

              </div>
              <!-- Blog Right Section End -->
            <?php endif; ?>

      </div>
      <!-- Blog Item End -->
    <?php
    endforeach;
  endif;
  die();
}

add_action('wp_ajax_get_popular_ajax', 'get_popular_ajax');
add_action('wp_ajax_nopriv_get_popular_ajax', 'get_popular_ajax');
function get_popular_ajax() {
  $page = $_GET["page"];
  $postPerPage = $_GET["posts_per_page"] == "" ? 4 : $_GET["posts_per_page"];

  //wp_reset_query();
  $args = array(
    'post_type' => 'blog',
    'paged' => $page,
    'posts_per_page' => $postPerPage,
    'meta_key' => 'wpb_post_views_count',
    'orderby' => 'meta_value_num',
    'order' => 'DESC'
  );

  //$arrPost = query_posts($args);
  $postQuery = new WP_Query($args);
  $arrPost = $postQuery->get_posts();
  if ($arrPost != NULL) :
    foreach ($arrPost as $post) :
      $postId = $post->ID;
      ?>
      <li>
        <!-- Bug# I-104876 href comes as empty on "show more" -->
        <a class="contentLink" href="<?php echo get_permalink($postId); ?>">
          <img class="contentThumb" src="<?php bloginfo('stylesheet_directory'); ?>/i/content-thumb.png"
               alt="<?php echo $post->post_title; ?>">
          <?php echo $post->post_title; ?>
        </a> <span class="contentBrief"><?php echo wrap_content_strip_html(
            wpautop($post->post_content),
            70,
            TRUE,
            '\n\r',
            '...'
          ); ?></span>
      </li>
    <?php
    endforeach;
  endif;
  die();
  /*
  $arrPost = query_posts($args);
  if ($arrPost != NULL) :
    foreach ($arrPost as $post) :
      $postId = $post->ID;
      ?>
      <li>
        <!-- Bug# I-104876 href comes as empty on "show more" - -->
        <!-- Bug# I-104876 fix href to use rel urls - -->
        <a class="contentLink" href="<?php echo get_rel_url(get_permalink($postId), TRUE); ?>">
          <img class="contentThumb" src="<?php bloginfo('stylesheet_directory'); ?>/i/content-thumb.png"
               alt="<?php echo $post->post_title; ?>">
          <?php echo $post->post_title; ?>
        </a> <span class="contentBrief"><?php echo wrap_content_strip_html(
            wpautop($post->post_content),
            70,
            TRUE,
            '\n\r',
            '...'
          ); ?></span>
      </li>
    <?php
    endforeach;
  endif;
  die();
  */
}

add_action('wp_ajax_subscribe_ajax', 'subscribe_ajax');
add_action('wp_ajax_nopriv_subscribe_ajax', 'subscribe_ajax');
function subscribe_ajax() {
  echo json_encode($_POST);
  die();
}
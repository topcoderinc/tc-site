<?php

$values = get_post_custom ( $post->ID );

$listType = isset($listType) ? $listType : '';

$siteURL = site_url ();
$postId = $post->ID;

$tcoTooltipTitle = get_option("tcoTooltipTitle");
$tcoTooltipMessage = get_option("tcoTooltipMessage");

if (empty($contest_type)) {
  $contest_type = get_query_var("contest_type") == "" ? "design" : get_query_var("contest_type");
}

if (empty($postPerPage)) {
  $postPerPage = get_post_meta($postId,"Contest Per Page",true) == "" ? 10 : get_post_meta($postId,"Contest Per Page",true);
}

$page_title = '';

switch ($contest_type) {
  case "design":
    $page_title = "Graphic Design Challenges";
    break;
  case "data":
    $page_title = "Data Science Challenges";
    break;
  case "develop":
  default:
    $page_title = "Software Development Challenges";
}

function tc_header_challenge_landing_js() {
  global $tcoTooltipTitle, $tcoTooltipMessage, $postPerPage, $contest_type, $listType, $contestID;
  if ($listType === 'Review') {
    $ajaxAction = 'get_review_opportunities';
    $reviewType = 'review';
  } elseif ($listType === 'Review Details') {
    $ajaxAction = 'get_review_detail';
    $reviewType = 'review detail';
  } else {
    $ajaxAction = 'get_challenges';
    $reviewType = 'contest';
  }
  ?>
  <script type="text/javascript" >
    var siteurl = "<?php bloginfo('siteurl');?>";
    <?php
        if (!empty($contestID)) {
    ?>
    var challenge_id = "<?php echo $contestID; ?>";
    <?php
        }
    ?>
    var reviewType = "<?php echo $reviewType; ?>";
    var isBugRace = false;
    var ajaxAction = "<?php echo $ajaxAction; ?>";
    var stylesheet_dir = "<?php bloginfo('stylesheet_directory');?>";
    var currentPage = 1;
    var postPerPage = <?php echo $postPerPage;?>;
    var contest_type = "<?php echo $contest_type;?>";
    var listType = "<?php echo $listType;?>";
    <?php
        if($tcoTooltipTitle) echo "var tcoTooltipTitle= '$tcoTooltipTitle';";
        if($tcoTooltipMessage) echo "var tcoTooltipMessage= '$tcoTooltipMessage';";
    ?>
  </script>
<?php
}

add_action("wp_head", "tc_header_challenge_landing_js");

get_header();

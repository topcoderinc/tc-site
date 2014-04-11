<?php

function tc_header_challenge_landing_js() {
  global $tcoTooltipTitle, $tcoTooltipMessage, $postPerPage, $contest_type, $listType;
  ?>
  <script type="text/javascript" >
    var siteurl = "<?php bloginfo('siteurl');?>";

    var reviewType = "contest";
    var isBugRace = false;
    var ajaxAction = "get_challenges";
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

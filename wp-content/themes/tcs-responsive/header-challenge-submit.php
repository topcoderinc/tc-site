<?php

function tc_header_challenge_submit_js() {
  global $challengeType;
  ?>
  <script type="text/javascript">
    var challengeId = "<?php echo get_query_var('contestID');?>";
    var challengeType = "<?php echo $challengeType; ?>";
  </script>
<?php
}

add_action("wp_head", "tc_header_challenge_submit_js");

get_header();

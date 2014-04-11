<?php

function tc_header_terms_js() {
  global $termType;

  ?>
  <script type="text/javascript">
    var challengeId = "<?php echo get_query_var('contestID');?>";
    var role = "<?php echo get_query_var('role');?>";
    var termType = "<?php echo $termType; ?>";
    var termsOfUseID = "<?php echo get_query_var('termsOfUseID');?>";
    var challengeType = "<?php echo get_query_var('challenge-type'); ?>";
  </script>
<?php
}

add_action("wp_head", "tc_header_terms_js");

get_header();

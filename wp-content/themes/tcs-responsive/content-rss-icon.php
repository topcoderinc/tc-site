
<?php
if (empty($contest_type)) {
  $contest_type = 'all';
}

$FeedURL = get_bloginfo('wpurl') . "/challenges/feed?list=active&contestType=" . $contest_type;
?>

<span class="subscribeTopWrapper">
  <a class="feedBtn" href="<?php echo $FeedURL;?>" title="Subscribe to challenges"></a>
</span>
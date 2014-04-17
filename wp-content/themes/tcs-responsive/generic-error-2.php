<?php
/*
Template Name: Generic error 2
*/

get_header('error'); 
?>
<div class="notFoundError genericNotFoundError2">
	<div class="notFoundErrorInner">
		 <?php if(have_posts()) : the_post();?>
	                        <?php the_content();?>
	                <?php endif; wp_reset_query();?>
		<span class="snow"></span>
		<span class="decrorat"></span>
		<span class="back"><a href="javascript:history.back(1)"></a></span>
	</div>
</div>

<?php
/*
Template Name: Generic error 1
*/

get_header('error'); 
?>
<div class="notFoundError genericNotFoundError">
	<div class="notFoundErrorInner">
		 <?php if(have_posts()) : the_post();?>
	                        <?php the_content();?>
	                <?php endif; wp_reset_query();?>
		<span class="clound"></span>
		<span class="ball"></span>
		<span class="decrorat"></span>
		<span class="back"><a href="javascript:history.back(1)"></a></span>
	</div>
</div>

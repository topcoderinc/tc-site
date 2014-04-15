<?php
/*
 * index page
 */
?>
<?php get_header(); ?>

<div id="main">
	<div class="container">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<br />
		<br />
		<h3>
			<?php the_title();?>
		</h3>
		<div class="article">
			<div class="post">
				<?php echo the_content();?>
			</div>
			<br />
			<div class="clear"></div>
		</div>
		<?php endwhile; endif;?>
	</div>
</div>

<?php get_footer(); ?>
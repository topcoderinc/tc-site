<?php
/**
 * Template Name: New Home
 */
?>
<?php 
if ( strpos($_SERVER["REQUEST_URI"],'blog') !== false ):
	include ('page-blog.php');
?>
<?php 
elseif ( strpos($_SERVER["REQUEST_URI"],'search') !== false ):
	$pageNumber = 1;
	if(preg_match("/\/page\/(\d+)\/?(\?s=.*)?$/", $_SERVER["REQUEST_URI"])){
		$pageNumber = preg_replace('/(.*?)\/page\/[1-9]+\/?/', '\2', $_SERVER["REQUEST_URI"]);
	}
	$searchKey = $_GET["s"];
	include ('search-results.php');
?>
<?php 
else:
?>
<?php get_header(); ?>

<?php
 if(preg_match("/.*?action=callback.*$/", $_SERVER["REQUEST_URI"])){
 		echo '<script type="text/javascript">$(window).load(function(){$(".btnRegister").click();$(".pwd, .confirm, .strength").parents(".row").hide();$("#register a.btnSubmit").addClass("socialRegister");});</script>';
 }
 if(preg_match("/.*?action=showlogin.*$/", $_SERVER["REQUEST_URI"])){
 		echo '<script type="text/javascript">$(window).load(function(){$(".actionLogin").click();});</script>';
 }
?>

<?php 
global $activity;
//$activity = get_activity_summary();
?>
<div class="content">
	<div id="promo-banner">
		<div class="container">
			<ul class="slider">
				<?php 
					$args = array (
						'post_type' 	=> 'promo',
						'category_name' => 'Promo Banners',
						'orderby'		=> 'menu_order',
						'order'			=> 'asc'
					);

					$promos = new WP_Query ( $args );							
					if ($promos->have_posts ()) :
						while ( $promos->have_posts () ) :
							$promos->the_post ();
							
							// image attributes
							$alt = trim(strip_tags( $post->post_title ));
							
				?>
				<li class="<?php echo strtolower ($post->post_title);?>">
					<a href="<?php the_permalink(); ?>" class="hideOnMobile"><img src="<?php echo get_post_meta( $post->ID, '_pm_leaderboard', true ); ?>" alt="<?php echo $alt; ?>" /></a>
					<a href="<?php the_permalink(); ?>" class="onMobi"><img src="<?php echo get_post_meta( $post->ID, '_pm_rectangle', true ); ?>" alt="<?php echo $alt; ?>" /></a>
				</li>
				<?php endwhile; endif; wp_reset_query();?>
			</ul>
		</div>
	</div>		
	<!-- /#promo-banner -->

	<div class="article">
		
		<?php					
			if (have_posts ()) :
				while ( have_posts () ) : 
					the_post ();
					the_content();
				endwhile;
			endif;
			wp_reset_query();
		?>
		
	</div>
	<!-- /.article -->

<?php get_footer(); ?>

<?php 
endif;
?>
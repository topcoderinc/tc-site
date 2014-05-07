<?php
/**
 * Template Name: Home
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
 		echo '<script type="text/javascript">$(window).load(function(){$(".btnRegister").click();if(window.location.hash!="")$(".pwd, .confirm, .strength").parents(".row").hide();$("#register a.btnSubmit").addClass("socialRegister");});</script>';
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
	<div id="main">
		<div id="banner">
			<div class="inner">
				<div class="container">
					<ul class="slider">
						<?php 
						$args = array (

								'post_type' => 'promo',
								'category_name' => 'Promo home'

						);

						$promos = new WP_Query ( $args );
							
						if ($promos->have_posts ()) :

						while ( $promos->have_posts () ) :

						$promos->the_post ();
						?>
						<li class="<?php echo strtolower ($post->post_title);?>"><?php the_content(); ?>
						</li>
						<?php endwhile; endif; wp_reset_query();?>
					</ul>
				</div>
			</div>
		</div>
		<div id="stats">
			<div class="container">
				<p>
					<em class="members"><?php echo get_activity_summary("memberCount"); ?></em> of the world's best minds competing

				</p>
				<a class="btn btnAlt" href="<?php bloginfo('wpurl') ?>/challenges">View Challenges</a>
			</div>
		</div>
		<!-- /#stats -->




		<article id="mainContent">
			<div class="container">
				<?php					
				if (have_posts ()) :
				while ( have_posts () ) : the_post ();
				$pid= $post->ID;
				?>
				<?php the_content();?>
			</div>
		</article>
		<!-- /#mainContent -->
		<article id="featuredContent">
			<div class="container">
				<?php echo do_shortcode(get_post_meta($pid,'Featured content',true)); ?>
			</div>
		</article>
		<!-- /#featuredContent -->

		<?php endwhile;  endif; wp_reset_query(); ?>

<?php get_footer(); ?>

<?php 
endif;
?>

<?php
/**
 * Template Name: Challenges Review Details Page
 */
get_header('challenge-landing'); 


$values = get_post_custom ( $post->ID );

$siteURL = site_url ();
$postId = $post->ID;
$contestID = get_query_var("contestID");

$url = "http://api.topcoder.com/v2/develop/reviewOpportunities/".$contestID;

$args = array (
		'httpversion' => get_option ( 'httpversion' ),
		'timeout' => get_option ( 'request_timeout' )
);
$response = wp_remote_get ( $url, $args );

if (is_wp_error ( $response ) || ! isset ( $response ['body'] )) {
	return "Error in processing request";
}
if ($response ['response'] ['code'] == 200) {

	//print $response ['body'];
	$active_contest_list = json_decode($response['body']);
}
?>

<?php
?>

<script type="text/javascript" >
	var siteurl = "<?php bloginfo('siteurl');?>";
	var stylesheet_dir = "<?php bloginfo('stylesheet_directory');?>";
</script>
<div class="content">
	<div id="main">
	
	<?php if(have_posts()) : the_post();?>
		<?php the_content();?>
	<?php endif; wp_reset_query();?>

		<div id="hero">
			<?php 
				$activeDesignChallengesLink = get_bloginfo('siteurl')."/active-challenges/design/";
				$activeDevlopChallengesLink = get_bloginfo('siteurl')."/active-challenges/develop/";
				$activeDataChallengesLink = get_bloginfo('siteurl')."/active-challenges/data/";
			?>
			<div class="container grid grid-float">
				<div class="grid-3-1 track trackUX<?php if($contest_type=="design") echo " isActive"; ?>" >
					<a href="<?php echo $activeDesignChallengesLink;?>"><i></i>Graphic Design Challenges
					</a><span class="arrow"></span>
				</div>
				<div class="grid-3-1 track trackSD<?php if($contest_type=="develop") echo " isActive"; ?>" >
					<a href="<?php echo $activeDevlopChallengesLink;?>"><i></i>Software Development Challenges
					</a><span class="arrow"></span>
				</div>
				<div class="grid-3-1 track trackAn<?php if($contest_type=="data") echo " isActive"; ?>" >
					<a href="<?php echo $activeDataChallengesLink;?>">
						<i></i>Data Science Challenges
					</a><span class="arrow"></span>
				</div>
			</div>
		</div>
		<!-- /#hero -->
				
		<article id="mainContent" class="layChallenges">
			<div class="container">
				<?php print_r($active_contest_list); ?>
			</div>
		</article>
		<!-- /#mainContent -->
<?php get_footer(); ?>
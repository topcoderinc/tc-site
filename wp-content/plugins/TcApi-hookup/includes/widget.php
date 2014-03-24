<?php
/* Register the widget */
function apiwidget_load_widgets() {
	register_widget ( 'Tops_Rank_Widget' );
	register_widget ( 'Stars_Of_Month_Widget' );
}

/* Begin Widget Class */
class Tops_Rank_Widget extends WP_Widget {
	
	/* Widget setup */
	function Tops_Rank_Widget() {
		/* Widget settings. */
		$widget_ops = array (
				'classname' => 'Tops_Rank_Widget',
				'description' => __ ( 'Tops Rank Widget', 'inm' ) 
		);
		
		/* Widget control settings. */
		$control_ops = array (
				'id_base' => 'top-rank-widget' 
		);
		
		/* Create the widget. */
		$this->WP_Widget ( 'top-rank-widget', __ ( 'Tops Rank Widget', 'inm' ), $widget_ops, $control_ops );
	}
	
	/* Display the widget */
	function widget($args, $instance) {
		extract ( $args );
		
		/* Before widget (defined by themes). */
		echo $before_widget;
		
		/* Display the widget title if one was input (before and after defined by themes). */
		if ($title)
			echo $before_title . $title . $after_title;
		?>
<section class="tops tabsWrap">
	<h1>Top 10</h1>
	<nav class="tabNav">
		<ul>
			<li><a href="#design" class="link">Design</a></li>
			<li><a href="#software" class="active link">Develop </a></li>
			<li><a href="#algorithum" class="link">Data</a></li>
		</ul>
	</nav>
<?php
		$topRankContestType = "data";
		$userkey = get_option ( 'api_user_key' );
		$arrTopRank = get_top_rank($userkey,$topRankContestType);
		
		/*$arrTopRank = json_decode ( '{
								"total": 30,
								"pageIndex": 1,
								"pageSize": 3,
								"data": 
								[
									{
									"Rank": 1,
									"Handle": "Petr",
									"userId": 123457899,
									"Color": "coderTextRed",
									"Rating": 3674
									},
									{
										"Rank": 2,
										"Handle": "ACRush",
										"userId": 123457892,
										"Color": "coderTextRed",
										"Rating": 3664
									},
									{
										"Rank": 3,
										"Handle": "Tourist",
										"userId": 123457891,
										"Color": "coderTextRed",
										"Rating": 3654
									},
									{
										"Rank": 4,
										"Handle": "tomek",
										"userId": 123457891,
										"Color": "coderTextRed",
										"Rating": 3654
									},
									{
										"Rank": 5,
										"Handle": "Burunduk1",
										"userId": 123457891,
										"Color": "coderTextRed",
										"Rating": 3644
									},
									{
										"Rank": 6,
										"Handle": "Egor",
										"userId": 123457891,
										"Color": "coderTextRed",
										"Rating": 3634
									},
									{
										"Rank": 7,
										"Handle": "lympanda",
										"userId": 123457891,
										"Color": "coderTextRed",
										"Rating": 2000
									},
									{
										"Rank": 8,
										"Handle": "lympanda",
										"userId": 123457891,
										"Color": "coderTextYellow",
										"Rating": 2000
									},
									{
										"Rank": 9,
										"Handle": "iwi",
										"userId": 123457891,
										"Color": "coderTextGreen",
										"Rating": 3614
									},
									{
										"Rank": 10,
										"Handle": "meret",
										"userId": 123457891,
										"Color": "coderTextGray",
										"Rating": 2000
									}
								]
							}' );
						
		*/				
		$arrRank = $arrTopRank->data;
		
		?>
	<div id="design" class="tableWrap hide tab">
		<table class="dataTable topsTable">
			<thead>
				<tr>
					<th class="colRank">#</th>
					<th class="colHandle">Handle</th>
					<th>Rating</th>
				</tr>
			</thead>
			<tbody>
			<tr>
					<td colspan="3">Coming Soon</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div id="software" class="tableWrap tab">
		<table class="dataTable topsTable">
			<thead>
				<tr>
					<th class="colRank">#</th>
					<th class="colHandle">Handle</th>
					<th>Rating</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$arrTopRank = get_top_rank($userkey,"develop");
			$arrRank = $arrTopRank->data;
			if ($arrRank != null)
			foreach ( $arrRank as $row ) :
				$handleLink = get_bloginfo ( "siteurl" ) . "/member-profile/" . $row->handle;
				?>
				<tr>
					<td><?php echo $row->rank;?></td>
					<td class="colHandle"><a href="<?php echo $handleLink;?>" class="coderText<?php echo $row->color;?>"><?php echo $row->handle;?></a></td>
					<td><?php echo $row->rating;?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<!-- /#software -->
	<div id="algorithum" class="tableWrap hide tab">
		<table class="dataTable topsTable">
			<thead>
				<tr>
					<th class="colRank">#</th>
					<th class="colHandle">Handle</th>
					<th>Rating</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$arrTopRank = get_top_rank($userkey,"data");
			$arrRank = $arrTopRank->data;	
			if ($arrRank != null)
			foreach ( $arrRank as $row ) :
				$handleLink = get_bloginfo ( "siteurl" ) . "/member-profile/" . $row->handle;
				?>
				<tr>
					<td><?php echo $row->rank;?></td>
<!--					<td class="colHandle"><span class="coderTextRed"><?php echo $row->handle;?></span></td> -->
					<td class="colHandle"><a href="<?php echo $handleLink;?>" class="coderText<?php echo $row->color;?>"><?php echo $row->handle;?></a></td>
					<td><?php echo $row->rating;?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<div class="views">
	<!--	<a href="http://community.topcoder.com/tc?module=AlgoRank">View All</a> -->
	</div>
</section>
<!-- /.tops -->
<?php
		echo $after_widget;
	}
}
 class Stars_Of_Month_Widget extends WP_Widget {
	
	/* Widget setup */
	function Stars_Of_Month_Widget() {
		/* Widget settings. */
		$widget_ops = array (
				'classname' => 'Stars_Of_Month_Widget',
				'description' => __ ( 'Stars Of Month Widget', 'inm' ) 
		);
		
		/* Widget control settings. */
		$control_ops = array (
				'id_base' => 'stars-of-month-widget' 
		);
		
		/* Create the widget. */
		$this->WP_Widget ( 'stars-of-month-widget', __ ( 'Stars Of Month Widget', 'inm' ), $widget_ops, $control_ops );
	}
	
	/* Display the widget */
	function widget($args, $instance) {
		extract ( $args );
		
		/* Before widget (defined by themes). */
		echo $before_widget;
		
		/* Display the widget title if one was input (before and after defined by themes). */
		if ($title)
			echo $before_title . $title . $after_title;
		?>
<section class="starsOfMonth">
	<div class="somInner">
		<h2>
			topcoders of the Month <small class="month"><?php echo get_option ( 'som' );?></small>
		</h2>
		<div class="starProfiles grid">
		<?php
		$query = array (
				'post_type' => 'stars-of-month' 
		);
		//query_posts ( $query );
		global $post;
		$postQuery = new WP_Query($query);
		$i=0;
		if ($postQuery->have_posts ()) :
			while ( $postQuery->have_posts () ) :
				$postQuery->the_post ();
				$postId = $post->ID;
				$handle = get_post_meta ( $postId, "Handle", true );
				$ratingColor = "";				
				$ratingColor = "color:".get_post_meta ( $postId, "Rating Color", true );
				$handleLink = get_post_meta ( $postId, "Handle Link", true );
				$handleLink = $handleLink == "" ? "javascript:;" : $handleLink;
				$contestLink = get_post_meta ( $postId, "Contest Link", true );
				$contestLink = $contestLink == "" ? "javascript:;" : $contestLink;
				$userkey = get_option ( 'api_user_key' );
			#	$data = get_member_profile ( $userkey, $handle );
			#	$arrRating = $data->ratingsSummary;
				$rating = 0;
				for($i = 0; $i < count ( $arrRating ); $i ++) {
					if ($arrRating [$i]->rating > $rating) {
						$ratingColor = $arrRating [$i]->colorStyle;
						$rating = $arrRating [$i]->rating;
					}
				}
				$image = wp_get_attachment_image_src ( get_post_thumbnail_id ( $postId ), 'single-post-thumbnail' );
				$image = $image != null ? $image [0] : plugins_url ( "TcApi-hookup" ) . "/includes/i/no_pic.png";
				$title = get_the_title ();
				$profile = "";
				/*if ($title == "Software Developers") {
					$profile = "profileSD";
				} elseif ($title == "Analytics") {
					$profile = "profileAn";
				}
				*/
				if ( $i % 3 == 0 ){
					$profile = "profileUX";
				}
				$i++;
				
				?>

			<div class="grid-3-1">
				<section class="profile <?php echo $profile;?>">
					<h3><?php echo $title;?></h3>
					<div class="details">
						<div class="memberPic">
							<img style="width:137px;height:138px" src="<?php echo $image;?>" alt="<?php echo $handle;?>">
						</div>
						<div class="row">
							<label>Congratulations,</label>
							<a href="<?php echo $handleLink;?>" style="<?php echo $ratingColor;?>" class="coderTextYellow"><?php echo $handle;?></a>
						</div>
						<div class="row wonPurse">
							<?php echo $post->post_content;?>							
						</div>
					</div>
				</section>
			</div>
			<?php endwhile; endif;
				wp_reset_postdata();
			?>
		</div>
		<!-- /.starProfiles -->
	</div>
</section>
<!-- /.starsOfMonth -->

<?php
		echo $after_widget;
	}
}

add_action ( 'widgets_init', 'apiwidget_load_widgets' );
?>
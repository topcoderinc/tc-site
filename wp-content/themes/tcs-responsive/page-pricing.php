<?php

/*
 * Template Name: Pricing
*/
?>
<?php 
$pid = $post->ID;
get_header('');
//query_posts(array(
$postQuery = new WP_Query(array(
		'post_type' => 'page',
		'post__in' => array($pid),
		'posts_per_page' => 1
));

?>
<?php
if ($postQuery->have_posts()) : while ($postQuery->have_posts()) : $postQuery->the_post();
$priceSigle = get_post_meta( $pid, "Price Pro. Single", true  );
$priceMulti = get_post_meta( $pid, "Price Pro. Multi", true  );
$priceEnt = get_post_meta( $pid, "Price Enterprise", true  );
$lnkSalesTalk = get_post_meta( $pid, "URL talk sales", true  );
$post = get_post($pid);
$content_post = $post->post_content;
?>
<div id="main" class="mainPricing">
	<div class="packages">
		<div class="mask">
			<article>
				<header>
					<h1>Open Innovation on-demand for every size business.</h1>
					<p class="headerDesc">Access to amazing technology talent. Increased speed to market. Greater innovation, less risk. &nbsp;&nbsp;</p>
				</header>
				<div class="packageList">
					<section class="proSingle">
						<h2>Professional - Single</h2>
						<p class="seat">1 Seat</p>
						<div class="price">
							<span class="currency">$</span><span class="amount"><?php echo $priceSigle;?> </span> <span class="recurrence">/MO</span>
						</div>
						<p class="packDesc">
							The innovation platform for<br /> focused digital creation.
						</p>
					</section>
					<section class="proMulti  active">
						<h2>Professional - Multi</h2>
						<p class="seat">3 - 6 Seats</p>
						<div class="price">
							<span class="currency">$</span><span class="amount"><?php echo $priceMulti;?> </span> <span class="recurrence">/MO</span> <br /> <span class="ps">per seat</span>
						</div>
						<p class="packDesc">
							Turn-key innovation platform for<br /> businesses looking to scale.
						</p>
					</section>
					<section class="enterprise">
						<h2>Enterprise</h2>
						<p class="seat">&nbsp;</p>
						<div class="price">
							<span class="amount"><?php echo $priceEnt;?> </span>
						</div>
						<p class="packDesc">
							Open Innovation to<br /> transform the enterprise.
						</p>
					</section>
				</div>
				<!-- /.packageList -->
			</article>
			<footer>
				<p>Don't know which package is the best for you?</p>
				<p class="contact">
					<a href="javascript:;" class="btnCompare">Compare Packages</a> <span class="or">or</span> 
					<a target="_blank" href="<?php echo $lnkSalesTalk;?>" class="btnTalk">Talk with Sales</a>
				</p>
			</footer>
		</div>
	</div>
	<!-- /.packages -->
	<div class="jumper"></div>
	<div class="comparePlans">
		<div class="mask">
			<article>
				<header>
					<h1>Compare Plans</h1>
					<p class="headerDesc">Packages for every size business. Discover which plan is best for you.</p>
				</header>
				<div class="tableWrap">
					<table class="compareTable">
						<thead>
							<tr>
								<th class="thFea"><div class="lt">
										<div class="mid">FEATURES</div>
									</div></th>
								<th class="thProS"><div class="lt">
										<div class="mid">
											Professional - Single<br /> <span>$<?php echo $priceSigle;?>/month
											</span>
										</div>
									</div></th>
								<th class="thProM"><div class="lt">
										<div class="mid">
											Professional - Multi<br /> <span>$<?php echo $priceMulti;?>/month per seat
											</span>
										</div>
									</div></th>
								<th class="thEnt"><div class="lt">
										<div class="mid">
											Enterprise<br /> <span>Call us for pricing</span>
										</div>
									</div></th>
							</tr>
						</thead>
						<?php  the_content();?>
					</table>
				</div>
				<!-- /.tableWrap -->
				<footer>
					<p>
						<span>Need help deciding? </span>We are happy to answer any of your questions. <span class="tel">1-866-867-2633</span> 
						<a target="_blank" href="<?php echo $lnkSalesTalk;?>" class="btnTalk">Talk with Sales</a>
					</p>
				</footer>
			</article>
		</div>
	</div>
	<!-- /.comparePlans -->
	<?php 
	endwhile; endif;
	//wp_reset_query();
	wp_reset_postdata();
	?>
</div>
<?php get_footer(''); ?>
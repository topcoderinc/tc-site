<?php
/*
Template Name: Search Results
*/
?>
<?php
get_header ();
?>

<div class="content">
	<div id="main">

	<!-- Start Overview Page-->
		<div class="pageTitleWrapper">
			<div class="pageTitle container">
				<h2 class="overviewPageTitle SingleColumnTestPageTitle">Search Results</h2>
			</div>
		</div>

		<div class="searchBox container">
			<form id="formSearch" action="/search" method="GET">
				<input type="text" name="s" class="text isBlured">
				<input type="submit" style="display:none">
				<a class="btn" href="javascript:$('#formSearch').submit();">Search</a>
			</form>		
		</div>		

		<article id="mainContent" class="splitLayout singleColumnPage">


			<div class="container">
				<div class="rightSplit  grid-1-1">
					<div class="mainStream grid-2-3">
						<section id="searchPageContent" class="pageContent">
							<div id="searchBlogsWrapper">
							<?php 
								//wp_reset_query();
								$args = array(
										'post_type' => array ( 'blog', 'page','post'),
										'posts_per_page' => 10,
										'paged' => $pageNumber,
										's' => $searchKey
								);							
								//query_posts($args);
								$postQuery = new WP_Query($args);
								if ( $postQuery->have_posts() ) :
									while ( $postQuery->have_posts() ) : 
										$postQuery->the_post();
							?>		
								<!-- Blog Item -->
								<div class="searchResultItem">
									
									<h3><a href="<?php the_permalink();?>" class="blueLink"><?php the_title();?></a></h3>
									
									<!-- Blog Desc -->
									<div class="searchResultDescBox">
										<div class="postCategory">Post type: <?php echo get_post_type( $post ) ?>		
										</div>
									</div>
									<!-- Blog Desc End -->
									<?php 
											$excerpt = wrap_content_strip_html(wpautop($post->post_content), 400, true,'\n\r','');
											echo $excerpt;
									?>
								</div>
								<!-- Blog Item End -->
							<?php 
									endwhile;
							?>
							<?php 
							if(function_exists('wp_paginate')) {
	    						wp_paginate();
							} 
							?>
							</div>
						</section>
						<!-- /.pageContent -->
					</div>
					<!-- /.mainStream -->			
					<?php
							else :
						?>
						<div class="noResult">No matches found</div>
					<?php
							endif;
							wp_reset_postdata();
						?>
				</div>
				<!-- /.rightSplit -->
				<div class="clear"></div>
			</div>
			</article>
			<!-- /#mainContent -->
	</div>
</div>		
		
<?php get_footer(); ?>
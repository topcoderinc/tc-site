<?php
/**
 * Template Name: Category Blog
 */
?>
<?php

get_header ();

$values = get_post_custom ( $post->ID );

$currUrl = curPageURL();
$userkey = get_option ( 'api_user_key' );
$currPage = (int) get_query_var ( 'page' ) != "" ? (int) get_query_var ( 'page' ) : 1;
$postPerPage = get_option("posts_per_page") == "" ? 5 : get_option("posts_per_page");
$siteURL = site_url ();

$blogPageTitle = get_option("blog_page_title") == "" ? "Welcome to the TopCoder-CloudSpokes Blog" : get_option("blog_page_title");
?>

<script type="text/javascript">
	var siteurl = "<?php bloginfo('siteurl');?>";
	var page = <?php echo $currPage; ?>;
</script>
<div class="content">
	<div id="main">
	<?php
		$catId = $cat;
		
		$activeMenuObj = get_category( $catId );
		$categories = $activeMenuObj!=null ? $activeMenuObj->cat_name : "Categories";
	?>
	<!-- Start Overview Page-->
	
		<!-- page title -->
		<div class="pageTitleWrapper">
			<div class="pageTitle container">
				<h2 class="blogPageTitle"><?php echo $blogPageTitle;?></h2>
				<span class="blogIcon"></span>
			</div>
			<div class="blogCategoryWrapper">
				<div class="container">
					<div class="innerWrapper">
						<div class="blogCategoryMenu">
						<?php
							$items = wp_get_nav_menu_items( BLOG );
							if($items!=null)
							foreach($items as $menu) :
								$active = $catId == $menu->object_id ? "active" : "";
						?>
							<a href="<?php echo $menu->url;?>" class="<?php echo $active;?>"><?php echo $menu->title;?></a>
						<?php endforeach; ?>
						</div>
						<ul class="blogMenuMobile">
							<div class="default">Categories<span class="arrow"></span></div>
							<div class="current"><?php echo $categories;?><span class="arrow"></span></div>
							<?php
								if($items!=null)
								foreach($items as $menu) :
							?>
								<li><a href="<?php echo $menu->url;?>"><?php echo $menu->title;?></a></li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<!-- page title end -->




		<article id="mainContent" class="splitLayout overviewPage">
			<div class="container blogPageMainContent">
				<div class="rightSplit  grid-3-3">
					<div class="mainStream grid-2-3">
						<section id="blogPageContent" class="pageContent">
						<div class="subscribeTopWrapper">
							<?php
								$catName = $activeMenuObj->cat_name;
								$feedUrl = get_bloginfo("wpurl")."/feed/?cat=$catId&post_type=blog";
							?>
							<a class="currentCatLink rssCat">Browsing '<?php echo $catName;?>'</a>
							<a class="feedBtn" href="<?php echo $feedUrl;?>">Subscribe to <?php echo $catName;?></a>
						</div>
						<div class="blogsWrapper">
							<input type="hidden" class="pageNo" value="<?php echo $currPage; ?>" />
							<input type="hidden" class="catId" value="<?php echo $catId; ?>" />
						<?php 
							//wp_reset_query();
							$args = "post_type=".BLOG;
							$args .= "&cat=$catId&order=DESC";
							if($showAll=="") {
								$args .= "&posts_per_page=".$postPerPage;
								$args .= "&paged=$currPage";
							}
							
							//query_posts($args);
							$postQuery = new WP_Query($args);
							if ( $postQuery->have_posts() ) :
								while ( $postQuery->have_posts() ) : 
									$postQuery->the_post();  // iterate and set global $post var
									$postId = $post->ID;
									$image = wp_get_attachment_image_src( get_post_thumbnail_id( $postId ), 'blog-thumb' );
									if($image!=null) $imageUrl = $image[0];
									else $imageUrl = get_bloginfo('stylesheet_directory')."/i/blog-thumb-placeholder.png";
									
									$imageMobile = wp_get_attachment_image_src( get_post_thumbnail_id( $postId ), 'blog-thumb' );
									if($imageMobile!=null) $imageUrlMobile = $imageMobile[0];
									else $imageUrlMobile = get_bloginfo('stylesheet_directory')."/i/story-side-pic.png";
									
									$dateObj = DateTime::createFromFormat('Y-m-d H:i:s', $post->post_date);
									$dateStr = $dateObj->format('M j, Y');
									
									$twitterText = urlencode(wrap_content_strip_html(wpautop($post->post_content), 130, true,'\n\r',''));
									$title = htmlspecialchars($post->post_title);
									$subject = htmlspecialchars(get_bloginfo('name')).' : '.$title;
									$body = htmlspecialchars($post->post_content);
									$email_article = 'mailto:?subject='.rawurlencode($subject).'&body='.rawurlencode($body);
									$twitterShare = "http://twitter.com/home?status=".$twitterText;
									$fbShare = "http://www.facebook.com/sharer/sharer.php?s=100&p[url]=".get_permalink()."&p[images][0]=".$imageUrl."&p[title]=".get_the_title()."&p[summary]=".$twitterText;
									$gplusShare = "https://plus.google.com/share?url=".get_permalink();
									
									$authorObj = get_user_by("id",$post->post_author);
									$authorLink = get_bloginfo("wpurl")."/author/".$authorObj->user_nicename;
						?>		
							<!-- Blog Item -->
							<div class="blogItem">
								<!-- Thumb place holder -->
								<div class="mobiThumbPlaceholder">
									<a href="<?php the_permalink();?>"><img src="<?php echo $imageUrlMobile;?>" width="300" height="160" /></a>
								</div>
								<!-- Thumb place holder end -->
								<a href="<?php the_permalink();?>" class="blogTitle blueLink"><?php the_title();?></a>
								
								<!-- Blog Desc -->
								<div class="blogDescBox">
									<div class="postDate"><?php echo $dateStr;?> &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; By:&nbsp;&nbsp;</div>
									<div class="postAuthor"><a href="<?php echo $authorLink; ?>" class="author blueLink"><?php the_author();?></a></div>
									<div class="postCategory">In : 
									<?php
										$categories = get_the_category();
										$separator = ', ';
										$output = '';
										if($categories){
											foreach($categories as $key=>$category) {
												if(strtolower($category->name)!=BLOG)
													$output .= '<a href="'.get_category_link( $category->term_id ).'" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '">'.$category->cat_name.'</a>'.$separator;
											}
										}
										echo trim($output, $separator);
									?>									
									</div>
								</div>
								<!-- Blog Desc End -->
								
								<!-- Blog Right Section -->
								<div class="blogRightSection">
									<!-- Imageplacehoder -->
									<div class="imagePlaceholder">
										<a href="<?php the_permalink();?>"><img src="<?php echo $imageUrl;?>" width="158" height="158" /></a>
									</div>
									<!-- Imageplacehoder End -->
									
									<!-- Content Right -->
									<div class="contentRight">
										<div class="excerpt">
											<?php 
												$excerpt = wrap_content_strip_html(wpautop($post->post_content), 400, true,'\n\r','');
												echo $excerpt;
											?>
										</div>
										<div class="shareVia">
											<span>Share via : </span>
											<a href="<?php echo $email_article;?>" class="shareButton"><img src="<?php bloginfo( 'stylesheet_directory' ); ?>/i/shares-mail.png" width="21" height="21" /></a>
											<a href="<?php echo $fbShare;?>" class="shareButton"><img src="<?php bloginfo( 'stylesheet_directory' ); ?>/i/shares-fb.png" width="21" height="21" /></a>
											<a href="<?php echo $twitterShare;?>" class="shareButton"><img src="<?php bloginfo( 'stylesheet_directory' ); ?>/i/shares-twitter.png" width="21" height="21" /></a>
											<a href="<?php echo $gplusShare;?>" class="shareButton"><img src="<?php bloginfo( 'stylesheet_directory' ); ?>/i/shares-gplus.png" width="21" height="21" /></a>
										</div>
										<a href="<?php the_permalink();?>" class="continueReading">Continue Reading</a>
									</div>
									<!-- Content Right End -->
									
								</div>
								<!-- Blog Right Section End -->
								
							</div>
							<!-- Blog Item End -->
						<?php 
								endwhile;
						?>
							</div>
							<div class="showMoreWrapper showMoreWrapperMobile">
								<a id="showMoreBlogPost" href="javascript:;" class="btn">Show More</a>
								<span class="morePostLoading">&nbsp;</span>
								<span class="noMorePostExist">No more post exist!</span>
							</div>
						<?php
							else :
						?>
							<div class="noResult">No Article in <?php echo $activeMenuObj->cat_name;?></div>
						<?php
							endif;
						?>
						<?php
							//wp_reset_query();
							wp_reset_postdata(); // reset post global var since it was updated above
							$args = "post_type=".BLOG;
							$args .= "&posts_per_page=-1&cat=$catId";
							//$wpQueryAll = query_posts($args);
							$allPostsQuery = new WP_Query($args);
							$allPostsQuery->get_posts();
							$postCount = $allPostsQuery->found_posts;
							//$postCount = count($wpQueryAll);
							
							$prevLink = add_query_arg("page",($currPage-1),$currentUrl);
							$nextLink = add_query_arg("page",($currPage+1),$currentUrl);
							
							if($postCount > $postPerPage) :
						?>
							<div class="pagingWrapper">
								<?php if($currPage>1) :?><a class="prev" href="<?php echo $prevLink;?>">Newer Posts</a><?php endif; ?>
								<?php if( $postCount > ($currPage * $postPerPage)) : ?><a class="next" href="<?php echo $nextLink;?>">Older Posts</a><?php endif;?>
							</div>
						<?php endif; ?>	
							
						</section>
					
					
						<!-- /.pageContent -->

					</div>
					<!-- /.mainStream -->
					<aside class="sideStream  grid-1-3">
						
						<?php get_sidebar("blog"); ?>		
						
					</aside>
					<!-- /.sideStream -->
					<div class="clear"></div>
				</div>
				<!-- /.rightSplit -->
			</div>
		</article>
		<!-- /#mainContent -->
<?php get_footer(); ?>
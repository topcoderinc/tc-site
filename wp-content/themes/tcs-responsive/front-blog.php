<?php 
/*
 * Template Name: Front Blog
 */
    get_header('blog');
    
    $values = get_post_custom($post->ID);
?>
	
    <div id="content">
        <div class="container">
            <div class="mainRail">
               
               
				<?php
                // fetch blog items from database
                $current_page = (get_query_var('paged')) ? get_query_var('paged') : 1;
                $query = array(
                    'post_type' => 'blog',
                    'paged' => $current_page
                );
                //query_posts($query);
                $postQuery = new WP_Query($query);
                $postQuery->get_posts();
                
                $total_pages = $postQuery->max_num_pages;
                $total_posts = $postQuery->found_posts;
                $displaying_posts = $postQuery->post_count;
                
                if ($postQuery->have_posts() ): while ($postQuery->have_posts()): $postQuery->the_post();
                    $blog_values = get_post_custom($post->ID);
                ?>
                <section>
                    <h2><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <div class="meta"><!--<?php the_time('F d, Y'); ?><span>|</span> -->By <?php the_author_posts_link();?></div>
                    <figure>
                         <?php  echo get_tc_thumbnail($post->ID, 'thumbnail'); ?>
                        <figcaption> 
                            <?php echo get_the_excerpt();  ?>
                            <a href="<?php echo get_permalink(); ?>">Read More</a>
                        </figcaption>
                    </figure>
                </section>
                <?php
                endwhile; endif;
                //wp_reset_query();
                wp_reset_postdata();
                ?>
                
				<?php if(function_exists('wp_paginate')) {
						wp_paginate();
				} ?>
							
				
            </div><!-- End of .mainRail -->
            <aside class="rightRail">
				<div class="commonWidget categoriesWidg posts">
					<header>
						<ul>
							<li class="current">Categories</li>
						</ul>
					</header>
					<div class="content">

						<ul class="categoryList">
								<li class="cat-item cat-item-325"><a title="View all posts filed under Challenges" href="http://eoi.kedaitech.com/blog/category/blog/challenges-blog/">Challenges</a> <a title="RSS" href="http://eoi.kedaitech.com/blog/category/blog/challenges-blog/feed/"><img title="RSS" alt="RSS" src="http://eoi.kedaitech.com/wp-content/themes/tc-eoi-theme-v3.2/i/rss-small.png"></a>
							</li>
								<li class="cat-item cat-item-326"><a title="View all posts filed under Design Studio" href="http://eoi.kedaitech.com/blog/category/blog/design-studio/">Design Studio</a> <a title="RSS" href="http://eoi.kedaitech.com/blog/category/blog/design-studio/feed/"><img title="RSS" alt="RSS" src="http://eoi.kedaitech.com/wp-content/themes/tc-eoi-theme-v3.2/i/rss-small.png"></a>
							</li>
								<li class="cat-item cat-item-324"><a title="View all posts filed under Platform" href="http://eoi.kedaitech.com/blog/category/blog/platform/">Platform</a> <a title="RSS" href="http://eoi.kedaitech.com/blog/category/blog/platform/feed/"><img title="RSS" alt="RSS" src="http://eoi.kedaitech.com/wp-content/themes/tc-eoi-theme-v3.2/i/rss-small.png"></a>
							</li>
								<li class="cat-item cat-item-328"><a title="View all posts filed under Stories" href="http://eoi.kedaitech.com/blog/category/blog/stories/">Stories</a> <a title="RSS" href="http://eoi.kedaitech.com/blog/category/blog/stories/feed/"><img title="RSS" alt="RSS" src="http://eoi.kedaitech.com/wp-content/themes/tc-eoi-theme-v3.2/i/rss-small.png"></a>
							</li>
								<li class="cat-item cat-item-323"><a title="View all posts filed under Strategy" href="http://eoi.kedaitech.com/blog/category/blog/strategy/">Strategy</a> <a title="RSS" href="http://eoi.kedaitech.com/blog/category/blog/strategy/feed/"><img title="RSS" alt="RSS" src="http://eoi.kedaitech.com/wp-content/themes/tc-eoi-theme-v3.2/i/rss-small.png"></a>
							</li>
								<li class="cat-item cat-item-327"><a title="View all posts filed under Wins" href="http://eoi.kedaitech.com/blog/category/blog/wins/">Wins</a> <a title="RSS" href="http://eoi.kedaitech.com/blog/category/blog/wins/feed/"><img title="RSS" alt="RSS" src="http://eoi.kedaitech.com/wp-content/themes/tc-eoi-theme-v3.2/i/rss-small.png"></a>
							</li>
						</ul>


					</div>


					<div class="corner tl"></div>
					<div class="corner tr"></div>
					<div class="corner bl"></div>
					<div class="corner br"></div>
				</div>
				
                <?php
                      dynamic_sidebar('Blog page right sidebar');
                ?>
				
				
				
            </aside><!-- End of .rightRail -->
            <div class="clear"></div>
        </div><!-- End of .contentInner -->
    </div><!-- End of #content -->	
	
	

<?php get_footer(); ?>

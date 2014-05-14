<?php
/**
 * Template Name: Challenges Review Opportunities Page
 */

$listType = get_post_meta($postId,"List Type",true) =="" ? "Review" : get_post_meta($postId,"List Type",true);

include locate_template('header-challenge-landing.php');
?>


<div class="content">
        <div id="main">

                <?php include(locate_template('nav-challenges-list-tabs.php'));?>

                <article id="mainContent" class="layChallenges">
                    <div class="container">
                        <header>
                            <h1>
                                <?php echo $page_title; ?>
                            </h1>
                        </header>
                        <div class="actions">
                            <?php include(locate_template('nav-challenges-list-type.php'));?>
                            <div class="rt">
                                <a href="javascript:;" class="searchLink advSearch">
                                    <i></i>Advanced Search
                                </a>
                            </div>
                        </div>
                        
						<?php 
						$datepicker_label = 'Review Start';	
						if ($contest_type == 'design') {
							$datepicker_label = "R1 Start Date ";
						}
						
						get_template_part("contest-advanced-search"); ?>
						

                                <div id="tableView" class=" viewTab">
                                        <div class="tableWrap tcoTableWrap">
                                                <table class="dataTable tcoTable centeredTable reviewTable">
                                                    <thead>
                                                        <!-- AJAX script will load table head here -->
                                                    </thead>
                                                    <tbody>
                                                        <!-- AJAX script will load table data here -->
                                                    </tbody>
                                                </table>
                                        </div>
                                </div>
                                <!-- /#tableView -->
                                <div id="gridView" class="viewTab hide">
                                        <div class="contestGrid alt">

                                        </div>
                                        <!-- /.contestGrid -->
                                </div>
                                <!-- /#gridView -->
                                <div class="dataChanges alt">
                                        <div class="lt">
                                                <a href="javascript:;" class="viewAll">View All</a>
                                        </div>
                                        <div id="challengeNav" class="rt">
                                                <a href="javascript:;" class="prevLink">
                                                        <i></i> Prev
                                                </a>
                                                <a href="javascript:;" class="nextLink">
                                                        Next <i></i>
                                                </a>
                                        </div>
                                        <div class="mid onMobi">
                                                <a href="#" class="viewPastCh">
                                                        View Past Challenges<i></i>
                                                </a>
                                                <a href="#" class="viewUpcomingCh">
                                                        View Upcoming Challenges<i></i>
                                                </a>
                                        </div>
                                </div>
                                <!-- /.dataChanges -->
                                <div class="note">
                                 <?php if(have_posts()) : the_post();?>
										<?php the_content();?>
								<?php endif; wp_reset_query();?>
                                </div>
                        </div>
                </article>
                <!-- /#mainContent -->
<?php get_footer(); ?>

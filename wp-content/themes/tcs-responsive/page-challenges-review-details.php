<?php
/**
 * Template Name: Challenges Review Details Page
 */
$contestID = get_query_var('contestID');
$listType = get_post_meta($postId,"List Type",true) =="" ? "Review Details" : get_post_meta($postId,"List Type",true);
include locate_template('header-challenge-landing.php');
?>


<div class="content">
        <div id="main">

        <?php if(have_posts()) : the_post();?>
                <?php the_content();?>
        <?php endif; wp_reset_query();?>
                <article id="mainContent" class="layChallenges">
                        <div class="container">
                            <header class="reviewDetailHeader">
                                <h2 class="reviewTitle">Review Opportunity Details</h2>
                                <h3 class="reviewOppType"></h3>
                                <div class="clear"></div>
                            </header>
                            <h1 class="reviewOppTitle"></h1>
                                <div id="tableView" class=" viewTab">
                                        <div class="tableWrap tcoTableWrap">
                                                <table class="dataTable reviewTable reviewTimelineTable">
                                                <caption class="reviewDetail">Timeline</caption>
                                                    <thead>
                                                        <tr>
                                                            <th class="colPhase" data-placeholder="type">Phase<i></i></th>
                                                            <th class="colRstart" data-placeholder="scheduledStartTime">Start<i></i></th>
                                                            <th class="colRend" data-placeholder="scheduledEndTime">End<i></i></th>
                                                            <th class="colDur" data-placeholder="duration">Duration (hours)<i></i></th>
                                                            <th class="colStatus noSort">Status<i></i></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- AJAX script will load table data here -->
                                                    </tbody>
                                                </table>
                                                <div id="reviewOpenPositions" class="hide">
                                                <span>Select the review roles you would like to apply for and click the button. The system will assign members that best meet the review requirements for this challenge. Although you will be assigned to at most one review position, applying for multiple roles increases your chances of being selected.</span>
                                                <table class="dataTable reviewTable reviewOpenTable">
                                                <caption class="reviewDetail">Open Positions</caption>
                                                    <thead>
                                                        <tr>
                                                            <th class="colRevRole" data-placeholder="role">Role<i></i></th>
                                                            <th class="colRevPos" data-placeholder="positions">Positions<i></i></th>
                                                            <th class="colRevPay" data-placeholder="payment">Payment<i></i></th>
                                                            <th class="colRevReg noSort">Register<i></i></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- AJAX script will load table data here -->
                                                    </tbody>
                                                </table>
                                                <div id="reviewDetailDateAssign"></div>
                                                <p>* Depends on the number of submissions, the actual payment may differ.</p>
                                                </div>
                                                <!-- /.OpenPositions -->

                                                <table class="dataTable reviewTable reviewAppTable">
                                                <caption class="reviewDetail">Review Applications</caption>
                                                    <thead>
                                                        <tr>
                                                            <th class="colAppHandle" data-placeholder="handle">Handle<i></i></th>
                                                            <th class="colAppRole" data-placeholder="role">Role<i></i></th>
                                                            <th class="colAppRating" data-placeholder="reviewerRating">Reviewer Rating<i></i></th>
                                                            <th class="colAppStatus" data-placeholder="status">Status<i></i></th>
                                                            <th class="colAppDate asc" data-placeholder="applicationDate">Application Date<i></i></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- AJAX script will load table data here -->
                                                    </tbody>
                                                </table>
                                        </div>
                                </div>

                                <!-- /.dataChanges -->
                        </div>
                </article>
                <!-- /#mainContent -->
<?php get_footer(); ?>

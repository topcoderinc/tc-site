<?php
/**
 * Template Name: Challenge Submit Template
 */
// Hard-coded at the moment. Should be passed in the url. 
$challengeType = 'develop';
get_header('challenge-submit');
$contest = get_contest_detail('', get_query_var('contestID'), $challengeType);
?>
  <div class="content">
    <div id="main" class="registerForChallenge submitForChallenge develop">
      <article id="mainContent">
        <div class="container submitContainer">
            <span class="competitionType develop"></span>
            <!-- /#end competition type-->
            <a href='<?php bloginfo("siteurl"); ?>/challenge-details/<?php echo $contestID; ?>/?type=develop' class="back">Back to Active Challenge</a>
            <h2 class="pageTitle"><?php echo $contest->challengeName; ?></h2>
            <!-- /#end page title-->
            <form action="http://studio.topcoder.com/" method="POST" name="submitForm" enctype="multipart/form-data" id="submitForm" autocomplete="off">
                <section class="formSection browseFile">
                    <h3>SUBMISSION UPLOAD</h3>

                    <div class="leftCol col">
                        <dl class="group fileField linkRow jqtransformdone" style="z-index: 985;">
                            <dt>Submission File (*.zip)</dt>
                            <dd>
                                <p class="fileNameDisplay fileNameDisplayNoFile">Select file to upload...</p>
                                <a class="btn fileBrowser btnGreen" href="javascript:;">Browse</a>
                                <span class="error">Please upload a submission first</span>
                                <input id="submission" data-type="zip" type="file" onchange="browseFileTrigger(this)" name="submission"
                                       class="fileInput">
                            </dd>
                        </dl>
                        <div><h4>If you experience issues uploading large files, please email your submission to <a href="mailto:support@topcoder.com">support@topcoder.com</a> along with a link to this page. We apologize for this inconvenience and working to resolve the issue.</h4></div>
                    </div>
                    <div class="rightCol col">
                        <p>Please organize and upload files as per the submission requirements for the challenge</p>
                    </div>
                    <div class="clear"></div>
                </section>
                <section class="agreement notAgreed">
                    <input id="agree" type="checkbox"/>
                    <label>by clicking this box you agree to our <a href="http://www.topcoder.com/community/terms-and-conditions/" target="_blank">terms and conditions.</a></label>
                    <a href="javascript:" id="submit" class="btn">Submit</a>
                </section>
                <!-- #/end agreement section-->
                <section class="uploadContent">
                    <h3>Uploading Submission</h3>

                    <p>Please don’t close the browser until the file is uploaded</p>

                    <div class="uploadBar">
                        <div class="loader"></div>
                        <div class="percentage">0%</div>
                    </div>
                    <!-- #/end uploadBar-->
                    <div class="buttonBar">
                        <a href="javascript:" id="cancelUpload" class="btn btnGreen">Cancel</a>
                    </div>
                    <!-- #/end buttonBar-->
                </section>
                <!-- #/end upload section-->
            </form>

            <div class="mask"></div>
        </div>
        <div class="container successContainer hide">
            <span class="competitionType develop"></span>
            <!-- /#end competition type-->
            <a href='<?php bloginfo("siteurl"); ?>/challenge-details/<?php echo $contestID; ?>/?type=develop' class="back">Back to Active Challenge</a>
            <h2 class="pageTitle"><?php echo $contest->challengeName; ?></h2>
            <!-- /#end page title-->
            <!-- /#end success content-->
            <section class="viewSubmission">
                <h2>Submission successfully uploaded</h2>
            </section>
            <!-- /#end view submission-->
            <!--
            <section class="submissionTable">
                <div class="submissions">
                    <a href="javascript:" class="file"></a>
                    <a href="javascript:" class="delete">.</a>
                </div>
            </section>
            -->
            <!-- /#end submission table-->

        </div>
    </article>
    </div>
  </div>
  <!-- /#mainContent -->
<?php get_footer(); ?>
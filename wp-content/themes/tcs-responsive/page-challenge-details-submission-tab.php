	<?php
		$contestResults = $contest->submissions; 
		$mockSubmissionData = new stdClass();
		$mockSubmissionData->isPrivate = false;
		$mockSubmissionData->viewCount = 290;
		$mockSubmissionData->downloadCount = 40;
		$mockSubmissionData->country = "China";
		$mockSubmissionData->comment = "<p>Lorem ipsum dolor sit amet conseqtetur adispicing elit orem ipsum dolor sit amet conseqtetur adispicing elit</p>";
		$mockSubmissionData->submissionThumbs = array(
			"http://studio.topcoder.com/?module=DownloadSubmission&sbmid=177659&sbt=small&sfi=1",
			"http://studio.topcoder.com/?module=DownloadSubmission&sbmid=177659&sbt=small&sfi=1",
			"http://studio.topcoder.com/?module=DownloadSubmission&sbmid=177659&sbt=small&sfi=1",
			"http://studio.topcoder.com/?module=DownloadSubmission&sbmid=177659&sbt=small&sfi=1",
			"http://studio.topcoder.com/?module=DownloadSubmission&sbmid=177659&sbt=small&sfi=1",
			"http://studio.topcoder.com/?module=DownloadSubmission&sbmid=177659&sbt=small&sfi=1"
		);
		$mockSubmissionData->submissionFull = "http://studio.topcoder.com/?module=DownloadSubmission&sbmid=177659&sbt=full&sfi=1";
		$mockSubmissionData->stockArts = array(
			"http://stockartlink.com/123451","http://stockartlink.com/123452","http://stockartlink.com/123453",
			"http://stockartlink.com/123454","http://stockartlink.com/123455","http://stockartlink.com/123456"
		);
		$mockSubmissionData->fonts = array(
			"Arial","Tahoma","Helvetica"
		);
	?>
	
<!-- Submission Section -->
<div>	
<?php
	// Submission Section
	if ($contest->currentStatus!='Completed'):
?>	
        <article>
            <div class="notView2">
                <p><strong>Submissions are not viewable for this challenge</strong></p>
            </div>
        </article>
<?php 
	elseif($mockSubmissionData->isPrivate):
?>
        <article>
            <div class="notView">
                Private Challenge
                <p>Submissions are not viewable for this challenge</p>
            </div>
        </article>
<?php else: ?>
		<div class="submissionAllView">
            <h1>SUBMISSIONS</h1>
            <ul class="submissionList"><!--id="submissionList"-->
			<?php
				$pageCounter = 1;
				if( $contestResults!=null ) 
				foreach( $contestResults as $key=>$submissionObj ) :
					$submissionGridViewImg = "http://studio.topcoder.com/studio.jpg?module=DownloadSubmission&sbmid=".$submissionObj->submissionId."&sbt=small&sfi=1";
					$submissionDownloadUrl = "http://studio.topcoder.com/?module=DownloadSubmission&sbmid=".$submissionObj->submissionId;
					$dateStr = substr($submissionObj->submissionTime, 0, 10)." ".substr($submissionObj->submissionTime, 11, 5);
					//dateStr format : 2014-04-02 07:10
					$dateObj = DateTime::createFromFormat('Y-m-d H:i', $dateStr);
					$dateFormatted = $dateObj!=null ? $dateObj->format('d.m.Y , H:i')." EST" : "";
			?>
		<?php if($key==0) : ?>
			<span class="submissionPage page1">
		<?php endif;?>
                <li>
                    <div>
                        <img src="<?php echo $submissionGridViewImg; ?>" alt="" width="225" height="226">
                        
                        <p>
                            <span class="subNum">#<?php echo $submissionObj->submissionId;?></span>
                            <a href="<?php bloginfo('wpurl'); ?>/member-profile/<?php echo $submissionObj->submitter; ?>/?tab=design" class="handle coderTextOrange"><?php echo $submissionObj->submitter;?></a></p>
                        <p class="submissionInfo">
                            <span class="metaDate"><?php echo $dateFormatted;?></span>
                            
                            <span class="viewSubmission jsViewSubmission"><a href="http://studio.topcoder.com/?module=DownloadSubmission&sbmid=<?php echo $submissionObj->submissionId;?>&sbt=full"></a></span>
                            <span class="download"><a href="<?php echo $submissionDownloadUrl;?>"></a></span>
                        </p>
                    </div>
                </li>
			<?php 
				if(($key+1)%12==0 && $key>0 ) {
					echo "</span>";
					$pageCounter++;
					if(($key+1)<count($contestResults))
						echo '<span class="submissionPage hide page'.$pageCounter.'">';
				}
			?>						
			<?php endforeach; ?>
			
			<?php 
				if(count($contestResults)%12!=0) echo "</span>";
				echo "<input type='hidden' class='submissionPageCount' value='$pageCounter' />";
			?>
            </ul>

            <div class="submissionSlider hide">
                <ul>
				<?php
					if( $contestResults!=null ) 
					foreach( $contestResults as $key=>$submissionObj ) :
						$dateStr = substr($submissionObj->submissionDate, 0, 10)." ".substr($submissionObj->submissionDate, 11, 5);
						//dateStr format : 2014-04-02 07:10
						$dateObj = DateTime::createFromFormat('Y-m-d H:i', $dateStr);
						$dateFormatted = $dateObj!=null ? $dateObj->format('d.m.Y , H:i') : "";
						$submissionGridViewImg = "http://studio.topcoder.com/studio.jpg?module=DownloadSubmission&sbmid=".$submissionObj->submissionId."&sbt=small&sfi=1";
						$submissionDownloadUrl = "http://studio.topcoder.com/?module=DownloadSubmission&sbmid=".$submissionObj->submissionId;
				?>
				<?php if($key==0) : ?>
                    <li class="slide">
				<?php endif;?>
                        <div>
                            <a href="javascript:;"><img src="<?php echo $submissionGridViewImg; ?>" alt=""></a>
                            
                            <p>
                                <span class="subNum">#<?php echo $submissionObj->submissionId; ?></span>
                                <a href="<?php bloginfo('wpurl'); ?>/member-profile/<?php echo $submissionObj->handle; ?>/?tab=design" class="handle coderTextOrange"><?php echo $submissionObj->submitter;?></a></p>
                            <p class="submissionInfo">
                                <span class="metaDate"><?php echo $dateFormatted;?></span>
                                
                                <span class="viewSubmission jsViewSubmission"><a href="javascript:;"></a><?php echo $mockSubmissionData->viewCount;?></span>
                                <span class="download"><a href="<?php echo $submissionDownloadUrl;?>"><?php echo $mockSubmissionData->downloadCount;?></a></span>
                            </p>
                        </div>
				<?php 
					if(($key+1)%3==0 && $key>0 ) {
						echo "</li>";
						if(($key+1)<count($contestResults))
							echo '<li class="slide">';
					}
				?>
				<?php endforeach; ?>
				<?php 
					if(count($contestResults)%3!=0) echo "</li>";
				?>				
                </ul>
            </div>
            <div class="clear"></div>
		<?php if($pageCounter>1) : ?>
            <div id="submissionPaging" class="pager">
                <div class="lt">
                    <a href="javascript:;" class="viewAll">View All</a>
                </div>
                <div class="rt">
                    <a href="javascript:;" class="prevLink hide">
                        <i></i> Prev
                    </a>
                    <a href="javascript:;" class="nextLink">
                        Next <i></i>
                    </a>
                </div>
            </div>
		<?php endif; ?>
		
        </div>
<?php endif; ?>
</div>
<!-- Submission Section End -->

		
        <div class="submissionSingleView hide studio">
            <div class="informationViewSlider hide">
                <div class="basicInfo">
                    <div class="basicInfoT">
                        <span class="meataAction">
                            <span class="metaDate">01.24.2014,16:26WIT</span>
                        </span>
                        <span class="subNum">#<?php echo $submissionObj->submissionId; ?></span>
                    </div>
                    <div class="basicInfoB">
                        <img src="<?php echo THEME_URL ?>/i/avatar.png" alt="">
                        <a href="javascript:;" class="handle coderTextOrange">Handlename</a>
                        <span class="country"><?php echo $mockSubmissionData->country; ?></span>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="clear"></div>
                <ul>
                    <li class="slide">
                        <div class="basicInfoAction">
                            <a href="javascript:;" class="viewSubmission"></a><span class="viewNum">Views <?php echo $mockSubmissionData->viewCount;?></span>
                            <a href="javascript:;" class="download">Downloads <?php echo $mockSubmissionData->downloadCount;?></a>
                        </div>
                    </li>
                    <li class="slide">
                        <div class="commentInfo">
                            <h6>DECLARATION</h6>
                            <label>Comment:</label>
                            <p><a href="javascript:;"><?php echo $mockSubmissionData->comment;?></a></p>
                        </div>
                        <!-- /.furtherInfo -->
                    </li>
                    <li class="slide">
                       <div class="fontInfo">
                            <label>Fonts:</label>
						<?php 
							if(count($mockSubmissionData->fonts)>0) 
							foreach($mockSubmissionData->fonts as $font) :
						?>
                            <p><a href="javascript:;"><?php echo $font;?></a></p>
						<?php endforeach;?>
                        </div>
                        <!-- /.fontInfo -->
                        <div class="stockArtInfo">
                            <label>Stock Art:</label>
						<?php
							if($mockSubmissionData->stockArts!=null)
							foreach($mockSubmissionData->stockArts as $key=>$stockArt):
						?>
						<?php
								if($key==3 && count($mockSubmissionData->stockArts)>3) :
						?>
							<a href="javascript:;" class="jsSeeMore seeMoreLink">See More</a>
							<div class="seeMoreInfo hide">						
						<?php endif;?>
								<p><a href="javascript:;"><?php echo $stockArt;?></a></p>
						<?php
							endforeach;
						?>
						<?php if(count($mockSubmissionData->stockArts)>3) :?>
							</div>
						<?php endif;?>
                        </div>
                    </li>
                </ul>
            </div>
            <!-- /.informationViewSlider -->

            <div class="informationView">
                <div class="basicInfo">
                    <span class="meataAction">
                        <span class="metaDate">01.24.2014,16:26WIT</span>
                        <a href="javascript:;" class="viewSubmission"></a><span class="viewNum">Views <?php echo $mockSubmissionData->viewCount; ?></span>
                        <a href="javascript:;" class="download">Downloads <?php echo $mockSubmissionData->downloadCount; ?></a>
                    </span>
                    <span class="subNum">#<?php echo $submissionObj->submissionId; ?></span>
                    <a href="javascript:;" class="handle coderTextOrange">Handlename</a>
                </div>
                <!-- /.basicInfo -->
                <div class="furtherInfo">
                    <h6>DECLARATION</h6>
                    <div class="commentInfo">
                        <label>Comment:</label>
                        <p><a href="javascript:;"><?php echo $mockSubmissionData->comment;?></a></p>
                    </div>
                    <!-- /.furtherInfo -->
                    <div class="fontInfo">
                        <label>Fonts:</label>
						<?php 
							if(count($mockSubmissionData->fonts)>0) 
							foreach($mockSubmissionData->fonts as $font) :
						?>
                            <p><a href="javascript:;"><?php echo $font;?></a></p>
						<?php endforeach;?>
                    </div>
                    <!-- /.fontInfo -->
                    <div class="stockArtInfo">
                        <label>Stock Art:</label>
						<?php
							if($mockSubmissionData->stockArts!=null)
							foreach($mockSubmissionData->stockArts as $key=>$stockArt):
						?>
						<?php
								if($key==3 && count($mockSubmissionData->stockArts)>3) :
						?>
							<a href="javascript:;" class="jsSeeMore seeMoreLink">See More</a>
							<div class="seeMoreInfo hide">						
						<?php endif;?>
								<p><a href="javascript:;"><?php echo $stockArt;?></a></p>
						<?php
							endforeach;
						?>
						<?php if(count($mockSubmissionData->stockArts)>3) :?>
							</div>
						<?php endif;?>
                        <!-- /.seeMoreInfo -->
                    </div>
                    <!-- /.stockArtInfo -->
                    <div class="clear"></div>
                </div>
                <!-- /.furtherInfo -->
            </div>
            <!-- /.informationView -->

            <div class="submissionSingleSlider hide">
                <ul>
					<li class="slide">
					<?php
						if($mockSubmissionData->submissionThumbs!=null)
						foreach($mockSubmissionData->submissionThumbs as $key=>$submissionThumb):
					?>
							<div>
								<a href="javascript:;" class="jsFullScreenBtn"><img src="<?php echo $submissionThumb; ?>" alt=""></a>
							</div>
						<?php 
							if(($key+1)%2==0 && $key>0 ) {
								echo "</li>";
								if(($key+1)<count($mockSubmissionData->submissionThumbs))
									echo '<li class="slide">';
							}
						?>							
					<?php
						endforeach;
					?>
					<?php if(count($mockSubmissionData->submissionThumbs)%2!=0) : ?>
						</li>
					<?php endif;?>
                </ul>
            </div>
            <!-- /.submissionSingleSlider -->
            <div class="clear"></div>
            

            <!-- /.informationView -->
            <div class="submissionShowcase">
                <div class="scrollPane">
                <ul class="submissionShowcaseList">
				<?php
					if($mockSubmissionData->submissionThumbs!=null)
					foreach($mockSubmissionData->submissionThumbs as $key=>$submissionThumb):
						$active = $key==0 ? "active" : "";
				?>
                    <li><a href="javascript:;" class="<?php echo $active;?>"><img src="<?php echo $submissionThumb; ?>" alt="" width="191" height="154"/></a></li>
				<?php
					endforeach;
				?>
                </ul>
                </div>
                <div class="submissionBig">
                    <img src="<?php echo $mockSubmissionData->submissionFull; ?>" alt="" width="738" height="592" />
                    <p><a href="javascript:;" class=" btn btnAlt fullScreenBtn jsFullScreenBtn">FULL SCREEN</a></p>
                </div>
                <div class="hide submissionBigMock">
                    <img src="<?php echo $mockSubmissionData->submissionFull; ?>" alt="" width="738" height="592" />
                    <p><a href="javascript:;" class=" btn btnAlt fullScreenBtn jsFullScreenBtn">FULL SCREEN</a></p>
                </div>
                <div class="clear"></div>
            </div>
            <!-- /.submissionShowcase -->
        </div>
        <!-- /.submissionSingleView -->


<?php if($_GET["tab"] == "submission") : ?>
<script>
$(document).ready(function(){
	var id = "#submissions";
	$('.tableWrap').hide();
	$('#submissions').show();
	$(id).fadeIn();
	$('.tabNav .active').removeClass('active');
	$('#submissionTabLink').addClass('active');
	$('#submissionTabLinkMobile').addClass('active');


	$('.submissionAllView').show();
	$('.submissionSingleView').hide();
	/**
	 * Submission remove right tab
	 */
	console.log(id);
	if(id=="#submissions") {
		$("#mainContent .rightSplit").removeClass("grid-3-3");
		$("#mainContent .rightSplit").addClass("fullWidth");
		$(".sideStream").hide();
		if ($(window).width() > 1019) { 
			$("#rightTitleOnFull").show();
		}
	}
	else {
		$("#mainContent .rightSplit").addClass("grid-3-3");
		$("#mainContent .rightSplit").removeClass("fullWidth");
		$(".sideStream").show();
		$("#rightTitleOnFull").hide();
	}
});	
</script>	
<?php endif;?>
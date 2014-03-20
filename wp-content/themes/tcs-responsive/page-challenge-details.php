<?php
/**
 * Template Name: Challenge details
 */
 
 /* 
added by @pemula 2014-01-17
source : http://stackoverflow.com/questions/8273804/convert-seconds-into-days-hours-minutes-and-seconds
*/
function secondsToTime($inputSeconds) {

    $secondsInAMinute = 60;
    $secondsInAnHour  = 60 * $secondsInAMinute;
    $secondsInADay    = 24 * $secondsInAnHour;

    // extract days
    $days = floor($inputSeconds / $secondsInADay);

    // extract hours
    $hourSeconds = $inputSeconds % $secondsInADay;
    $hours = floor($hourSeconds / $secondsInAnHour);

    // extract minutes
    $minuteSeconds = $hourSeconds % $secondsInAnHour;
    $minutes = floor($minuteSeconds / $secondsInAMinute);

    // extract the remaining seconds
    $remainingSeconds = $minuteSeconds % $secondsInAMinute;
    $seconds = ceil($remainingSeconds);

    // return the final array
    $obj = array(
        'd' => (int) $days,
        'h' => (int) $hours,
        'm' => (int) $minutes,
        's' => (int) $seconds,
    );
    return $obj;
}

?>
<?php

$isChallengeDetails = true;
get_header();


$values = get_post_custom ( $post->ID );

$userkey = get_option ( 'api_user_key' );
$siteURL = site_url ();


$contestID = get_query_var('contestID');
//$contestType = get_query_var ( 'type' );
$contestType = $_GET['type'];
$contest = get_contest_detail('',$contestID, $contestType);
$registrants = $contest->registrants;
#print_r($contest);
?>

<?php
// get contest details
	$contest_type = get_query_var ( 'contest_type' );
	$contest_type = str_replace("_", " ", $contest_type);
	$postPerPage = get_option("contest_per_page") == "" ? 30 : get_option("contest_per_page");
?>

<div class="content challenge-detail <?php if($contestType != 'design') {echo 'develop';} ?>" >
	<div id="main">
	
	<div class="container">
					<header class="pageHeading aboutPage">
						<h1><?php echo $contest->challengeName;?></h1>
                        <h2>CONTEST TYPE: <span><?php echo $contest->challengeType;?></span></h2>
					</header>
                    
                    <div id="stepBox"> 
						<div class="container">
							 
                             <div class="leftColumn">
                             	<?php 
								if ( $contestType != 'design' ):
								?>								
									<a class="btn btnAction" href="http://community.topcoder.com/tc?module=ViewRegistration&pj=<?php echo $contestID;?>"><span>1</span> <strong>Register For This Contest</strong></a>
									<a class="btn btnAction" href="https://software.topcoder.com/review/actions/UploadContestSubmission.do?method=uploadContestSubmission&pid=<?php echo $contestID ;?>"><span>2</span> <strong>Submit Your Entries</strong></a> 
								<?php
								else:
								?>
									<a class="btn btnAction" href="http://studio.topcoder.com/?module=ViewRegistration&ct=<?php echo $contestID  ;?>"><span>1</span> <strong>Register For This Contest</strong></a>
									<a class="btn btnAction" href="http://studio.topcoder.com/?module=ViewRegistration&ct=<?php echo $contestID  ;?>"><span>2</span> <strong>Submit Your Entries</strong></a>
									<a class="btn btnAction" href="http://studio.topcoder.com/?module=ViewSubmission&ct=<?php echo $contestID  ;?>"><span>3</span> <strong>View Your Submission</strong></a>
								<?php
								endif;
								?>
							 </div>
                             <?php 
																			if ( $contestType != 'design' ):
																			?>
                             <div class="middleColumn">
                             <?php 
																			else:
																			?>
																			<div class="middleColumn studio">
																			<?php
								                 endif;
								                 ?>
                             	<table class="prizeTable"> 
                                  <tbody><tr>
                                  		<?php 
																								if ( $contestType != 'design' && $contest->challengeType != "Code" ):
																								?>
                                    <td class="fifty">
                                    	<h2>1st PLACE</h2>
                                        <h3><small>$</small><?php if( $contest->prize[0] !== null){echo number_format($contest->prize[0]);}?></h3>
                                    </td>
                                    <td class="fifty">
                                    	<h2>2nd PLACE</h2>
                                        <h3><small>$</small><?php if( $contest->prize[1] !== null){echo number_format($contest->prize[1]);}?></h3>
                                    </td>
                                    <?php 
																								else:
																								?>
																								<?php 
																								if ( $contest->prize[0] !== null && $contest->prize[0] !== 0 ):
																								?>
																								<td class="twenty">
                                    	<h2>1st PLACE</h2>
                                        <h3><small>$</small><?php echo number_format($contest->prize[0]); ?></h3>
                                    </td>
                                    <?php 
																								else:
																								?>
																								<td class="twenty noPrize">
                                    	<h2>1st PLACE</h2>
                                        <h3><small>$</small><?php echo number_format(0) ?></h3>
                                    </td>
																								<?php
								                        endif;
								                        ?>
								                        <?php 
																								if ( $contest->prize[1] !== null && $contest->prize[1] !== 0 ):
																								?>
																								<td class="twenty">
                                    	<h2>2nd PLACE</h2>
                                        <h3><small>$</small><?php echo number_format($contest->prize[1]); ?></h3>
                                    </td>
                                    <?php 
																								else:
																								?>
																								<td class="twenty noPrize">
                                    	<h2>2nd PLACE</h2>
                                        <h3><small>$</small><?php echo number_format(0) ?></h3>
                                    </td>
																								<?php
								                        endif;
								                        ?>
								                        <?php 
																								if ( $contest->prize[2] !== null && $contest->prize[2] !== 0 ):
																								?>
																								<td class="twenty">
                                    	<h2>3rd PLACE</h2>
                                        <h3><small>$</small><?php echo number_format($contest->prize[2]); ?></h3>
                                    </td>
                                    <?php 
																								else:
																								?>
																								<td class="twenty noPrize">
                                    	<h2>3rd PLACE</h2>
                                        <h3><small>$</small><?php echo number_format(0) ?></h3>
                                    </td>
																								<?php
								                        endif;
								                        ?>
								                        <?php 
																								if ( $contest->prize[3] !== null && $contest->prize[3] !== 0 ):
																								?>
																								<td class="twenty">
                                    	<h2>4th PLACE</h2>
                                        <h3><small>$</small><?php echo number_format($contest->prize[3]); ?></h3>
                                    </td>
                                    <?php 
																								else:
																								?>
																								<td class="twenty noPrize">
                                    	<h2>4th PLACE</h2>
                                        <h3><small>$</small><?php echo number_format(0) ?></h3>
                                    </td>
																								<?php
								                        endif;
								                        ?>
								                        <?php 
																								if ( $contest->prize[4] !== null && $contest->prize[4] !== 0 ):
																								?>
																								<td class="twenty">
                                    	<h2>4th PLACE</h2>
                                        <h3><small>$</small><?php echo number_format($contest->prize[4]); ?></h3>
                                    </td>
                                    <?php 
																								else:
																								?>
																								<td class="twenty noPrize">
                                    	<h2>5th PLACE</h2>
                                        <h3><small>$</small><?php echo number_format(0) ?></h3>
                                    </td>
																								<?php
								                        endif;
								                        ?>
								                        <?php 
																								if ( sizeof($contest->prize) > 5 ):
																								?>
                                    <td class="morePayments active closed" rowspan="<?php echo 2 + (int) ((sizeof($contest->prize)-5)/5) ?>">
                                    </td>
                                    <?php 
																								else:
																								?>
																								<td class="morePayments inactive" rowspan="<?php echo 2 + (int) ((sizeof($contest->prize)-5)/5) ?>">
                                    </td>
																								<?php
																								endif;
																								?>
                                    <?php
																								endif;
																								?>
                                  </tr>
																							<?php 
																							if ( sizeof($contest->prize) > 5 )
																									for ($i = 0; $i < (sizeof($contest->prize)-5)/5; $i++) :
																							?>                                  
                                  <tr class="additionalPrizes hide">
                                  <?php 
																						 if ( sizeof($contest->prize) > 5+$i*5 ):
																						 ?>
                                  <td class="twenty">
                                  			<h2><?php echo 5+$i*5+1;?>th PLACE</h2>
                                     	<h3><small>$</small><?php echo number_format($contest->prize[5+$i*5]); ?></h3>
                                  </td>
                                  <?php
																						 endif;
																						 ?>
																						 <?php 
																						 if ( sizeof($contest->prize) > 5+$i*5+1 ):
																						 ?>
                                  <td class="twenty">
                                  			<h2><?php echo 5+$i*5+2;?>th PLACE</h2>
                                     	<h3><small>$</small><?php echo number_format($contest->prize[5+$i*5+1]); ?></h3>
                                  </td>
                                  <?php
																						 endif;
																						 ?>
																						 <?php 
																						 if ( sizeof($contest->prize) > 5+$i*5+2 ):
																						 ?>
                                  <td class="twenty">
                                  			<h2><?php echo 5+$i*5+3;?>th PLACE</h2>
                                     	<h3><small>$</small><?php echo number_format($contest->prize[5+$i*5+2]); ?></h3>
                                  </td>
                                  <?php
																						 endif;
																						 ?>
																						 <?php 
																						 if ( sizeof($contest->prize) > 5+$i*5+3 ):
																						 ?>
                                  <td class="twenty">
                                  			<h2><?php echo 5+$i*5+4;?>th PLACE</h2>
                                     	<h3><small>$</small><?php echo number_format($contest->prize[5+$i*5+3]); ?></h3>
                                  </td>
                                  <?php
																						 endif;
																						 ?>
																						 <?php 
																						 if ( sizeof($contest->prize) > 5+$i*5+4 ):
																						 ?>
                                  <td class="twenty">
                                  			<h2><?php echo 5+$i*5+5;?>th PLACE</h2>
                                     	<h3><small>$</small><?php echo number_format($contest->prize[5+$i*5+4]); ?></h3>
                                  </td>
                                  <?php
																						 endif;
																						 ?>
                                  </tr>
                                  <?php 
                                  endfor;
                                  ?>
                                  <tr>
                                  		<?php 
																								if ( $contestType != 'design' ):
																								?>
                                    <td 
                                    	<?php
                                    		//Adjust the colspan for CODE challenge type since the 5 prizes add more columns 
                                			if ( $contest->challengeType == "Code" ) echo 'colspan="2"'; 
                                		?> 
                                    	>
                                    	<p class="realibilityPara">Reliability Bonus 

											<?php
											if ( empty($contest->reliabilityBonus) ):
											?>								
                        	            		<span>$<?php echo "0" ?></span>
											<?php
											else:
											?>
                                    			<span>$<?php echo $contest->reliabilityBonus; ?></span>
											<?php
											endif;
											?>
                                    	</p>
                                    </td>
                                    <td 
                                    	<?php
                                    		//Adjust the colspan for CODE challenge type since the 5 prizes add more columns 
                                			if ( $contest->challengeType == "Code" ) echo 'colspan="3"'; 
                                		?> 
                                    	>
                                    
                                    	<p class="drPointsPara">DR Points <span><?php echo $contest->digitalRunPoints;?></span></p>
                                    </td>
                                    <?php 
																								else:
																								?>
																								<td colspan="2">
																									<?php 
																								  if ( $contest->digitalRunPoints != null && $contest->digitalRunPoints != 0 ):
																								  ?>
                                    	<p class="scPoints"><span><?php echo $contest->digitalRunPoints;?></span> STUDIO CUP POINTS</p>
                                    	<?php 
																								  else:
																								  ?>
																								  <p class="scPoints">NO STUDIO CUP POINTS</p>
																								  <?php
																								  endif;
																								  ?>
                                    </td>
                                    <td colspan="3">
                                    	<p class="scPoints"><span><?php echo $contest->numberOfCheckpointsPrizes;?></span> CHECKPOINT AWARDS WORTH <span>$<?php echo $contest->topCheckPointPrize;?></span> EACH</p>
                                    </td>
																								<?php
																								endif;
																								?>
                                  </tr>
                                </tbody></table>
                                
																					<div class="prizeSlider hide">
																						<ul>
																							<li class="slide">
                   												<table> 
                                  <tbody><tr>
																								<?php 
																								if ( $contest->prize[0] !== null && $contest->prize[0] !== 0 ):
																								?>
																								<td class="twenty">
                                    	<h2>1st PLACE</h2>
                                        <h3><small>$</small><?php echo number_format($contest->prize[0]); ?></h3>
                                    </td>
                                    <?php 
																								else:
																								?>
																								<td class="twenty noPrize">
                                    	<h2>1st PLACE</h2>
                                        <h3><small>$</small><?php echo number_format(0) ?></h3>
                                    </td>
																								<?php
								                        endif;
								                        ?>
								                        <?php 
																								if ( $contest->prize[1] !== null && $contest->prize[1] !== 0 ):
																								?>
																								<td class="twenty">
                                    	<h2>2nd PLACE</h2>
                                        <h3><small>$</small><?php echo number_format($contest->prize[1]); ?></h3>
                                    </td>
                                    <?php 
																								else:
																								?>
																								<td class="twenty noPrize">
                                    	<h2>2nd PLACE</h2>
                                        <h3><small>$</small><?php echo number_format(0) ?></h3>
                                    </td>
																								<?php
								                        endif;
								                        ?>
								                        <?php 
																								if ( $contest->prize[2] !== null && $contest->prize[2] !== 0 ):
																								?>
																								<td class="twenty">
                                    	<h2>3rd PLACE</h2>
                                        <h3><small>$</small><?php echo number_format($contest->prize[2]); ?></h3>
                                    </td>
                                    <?php 
																								else:
																								?>
																								<td class="twenty noPrize">
                                    	<h2>3rd PLACE</h2>
                                        <h3><small>$</small><?php echo number_format(0) ?></h3>
                                    </td>
																								<?php
								                        endif;
								                        ?>
                                  </tr>
                                </tbody></table>                     
																							</li>
																							<?php 
																							if ( sizeof($contest->prize) > 3 )
																									for ($i = 0; $i < (sizeof($contest->prize)-3)/3; $i++) :
																							?>
																							<li>
																							<table> 
                                  <tbody><tr>
																								<?php 
																						   if ( sizeof($contest->prize) > 3+$i*3 ):
																						   ?>
                                    <td class="twenty">
                                  			  <h2><?php echo 3+$i*3+1;?>th PLACE</h2>
                                     	  <h3><small>$</small><?php echo number_format($contest->prize[3+$i*3]); ?></h3>
                                    </td>
                                    <?php
																						   endif;
																						   ?>
																						   <?php 
																						   if ( sizeof($contest->prize) > 3+$i*3+1 ):
																						   ?>
                                    <td class="twenty">
                                  			  <h2><?php echo 3+$i*3+2;?>th PLACE</h2>
                                     	  <h3><small>$</small><?php echo number_format($contest->prize[3+$i*3+1]); ?></h3>
                                    </td>
                                    <?php
																						   endif;
																						   ?>
																						   <?php 
																						   if ( sizeof($contest->prize) > 3+$i*3+2 ):
																						   ?>
                                    <td class="twenty">
                                  			  <h2><?php echo 3+$i*3+3;?>th PLACE</h2>
                                     	  <h3><small>$</small><?php echo number_format($contest->prize[3+$i*3+2]); ?></h3>
                                    </td>
                                    <?php
																						   endif;
																						   ?>
                                  </tr>
                                </tbody></table>  
																							</li>
																							<?php 
                                  endfor;
                                  ?>
																						</ul>
																						<div>
																							<table>
																								<tbody>
																									<tr>
																										<td>
																											<?php 
																								  		if ( $contest->digitalRunPoints != null && $contest->digitalRunPoints != 0 ):
																								  		?>
                                    			<p class="scPoints"><span><?php echo $contest->digitalRunPoints;?></span> STUDIO CUP POINTS</p>
                                    			<?php 
																								  		else:
																								  		?>
																								  		<p class="scPoints">NO STUDIO CUP POINTS</p>
																								  		<?php
																								  		endif;
																								  		?>
                                    		</td>																			
																									</tr>		
																									<tr>
																										<td>
                                    			<p class="scPoints"><span><?php echo $contest->numberOfCheckpointsPrizes;?></span> CHECKPOINT AWARDS WORTH <span>$100</span> EACH</p>
                                   		 </td>	
																									</tr>																						
																								</tbody>
																							</table>
																						</div>                          
                                </div>
                             </div>

                             <div class="rightColumn">
 
                            <div class="nextBox "> 
                    
                                <div class="nextBoxContent nextDeadlineNextBoxContent">
                                	<div class="icoTime">
                                        <span class="nextDTitle">Current Phase</span>
                                        <span class="CEDate"><?php echo ( $contest->currentStatus == 'Completed' )?"Completed":$contest->currentPhaseName;?></span>
                                    </div>
                                    <span class="timeLeft">
									<?php 
									$remaining = secondsToTime($contest->currentPhaseRemainingTime);
									echo ( $contest->currentStatus == 'Completed' || $contest->currentStatus == 'Deleted' )? "" : $remaining[d]." <small>Days</small> ".$remaining[h]." <small>Hours</small> ".$remaining[m]." <small>Mins</small>";
									?>
									</span>
                                </div>
                                <!--End nextBoxContent-->
                                <?php 
																					if ( $contestType != 'design' ):
																					?>
                                <div class="nextBoxContent allDeadlineNextBoxContent hide">
                                    <p><label>Posted On:</label><span><?php echo date("M d, Y H:i", strtotime("$contest->postingDate")) . " EST" ;?></span></p>
                    
                                    
                                        <p><label>Register By:</label>
                                           <span><?php echo date("M d, Y H:i", strtotime("$contest->registrationEndDate")) . " EST" ;?>
                                           </span>
                                        </p>
                                    <p class="last"><label>Submit By:</label><span><?php echo date("M d, Y H:i", strtotime("$contest->submissionEndDate")) . " EST" ;?></span></p>
                    
                                </div>
                                <!--End nextBoxContent-->
                                <?php 
																					else:
																					?>
																					<div class="nextBoxContent allDeadlineNextBoxContent studio hide">
                                    <p><label>Start Date:</label><span><?php echo date("M d, Y H:i", strtotime("$contest->postingDate")) . " EST" ;?></span></p>
                                    <p><label>Checkpoint:</label><span><?php echo date("M d, Y H:i", strtotime("$contest->checkpointSubmissionEndDate")) . " EST" ;?></span></p>
                                    <p><label>End Date:</label><span><?php echo date("M d, Y H:i", strtotime("$contest->submissionEndDate")) . " EST" ;?></span></p>
                    											<p class="last"><label>Winners<br/>Announced:</label><span><?php echo date("M d, Y H:i", strtotime("$contest->appealsEndDate")) . " EST" ;?></span></p>
                                </div>
                                <!--End nextBoxContent-->
																					<?php
																					endif;
																					?>
                            </div>
                    
                            <!--End nextBox-->
                            <div class="deadlineBox"> 
                    
                                <div class="deadlineBoxContent nextDeadlinedeadlineBoxContent ">
                                    <a class="viewAllDeadLineBtn" href="javascript:">View all deadlines +</a>
                                </div>
                                <!--End deadlineBoxContent-->
                                <div class="deadlineBoxContent allDeadlinedeadlineBoxContent hide">
                                    <a class="viewNextDeadLineBtn" href="javascript:">View next deadline +</a>
                                </div>
                                <!--End deadlineBoxContent-->
                            </div>
                            <!--End deadlineBox-->
                        </div>
                             
						</div> 
				</div>
				<!-- /#hero -->
                    
				</div>
<!-- /.pageHeading -->


		<article id="mainContent" class="splitLayout ">
					<div class="container">
					  <div class="rightSplit  grid-3-3">
							<div class="mainStream partialList">
								 
                                 <section class="tabsWrap"> 
									<nav class="tabNav">
										<ul>
											<?php 
											if ( $contestType != 'design' ):
										  ?>
											<li><a href="#contest-overview" class="active link">Details</a></li>
											<li><a href="#viewRegistrant" class="link">Registrants</a></li>
											<li><a href="#winner" class="link">Results</a></li>
											<?php 
											else:
											?>
											<li><a href="#contest-overview" class="active link">Details</a></li>
											<li><a href="#viewRegistrant" class="link">Registrants</a></li>
											<?php 
											if ( strpos($contest->currentPhaseName,'Submission') !== FALSE ):
										  ?>
										  <li><span class="inactive">Checkpoints</span></li>
											<?php 
											else:
											?>
											<li><a href="#checkpoints" class="link">Checkpoints</a></li>
											<?php
											endif;
											?>
											<?php 
											if ( strpos($contest->currentPhaseName,'Submission') !== FALSE ):
										  ?>
										  <li><span class="inactive">Submissions</span></li>
											<?php 
											else:
											?>
											<li><a href="#submissions" class="link">Submissions</a></li>
											<?php
											endif;
											?>
											<?php 
											if ( strpos($contest->currentPhaseName,'Submission') !== FALSE || strpos($contest->currentPhaseName,'Screening') !== FALSE || strpos($contest->currentPhaseName,'Review') !== FALSE ):
										  ?>
										  <li><span class="inactive">Results</span></li>
											<?php 
											else:
											?>
											<li><a href="#winner" class="link">Results</a></li>
											<?php
											endif;
											?>
											<?php
											endif;
											?>
										</ul>
									</nav>
									<nav class="tabNav firstTabNav designFirstTabNav mobile hide">
										<ul>
											<li><a href="#contest-overview" class="active link">Details</a></li>
											<li><a href="#viewRegistrant" class="link">Registrants</a></li>
										</ul>
									</nav>
									<nav class="tabNav firstTabNav designSecondTabNav mobile hide">
										<ul>
											<?php 
											if ( strpos($contest->currentPhaseName,'Submission') !== FALSE ):
										  ?>
										  <li><span class="inactive">Checkpoints</span></li>
											<?php 
											else:
											?>
											<li><a href="#checkpoints" class="link">Checkpoints</a></li>
											<li>
											<?php
											endif;
											?>
											<?php 
											if ( strpos($contest->currentPhaseName,'Submission') !== FALSE ):
										  ?>
										  <li><span class="inactive">Submissions</span></li>
											<?php 
											else:
											?>
											<li><a href="#submissions" class="link">Submissions</a></li>
											<?php
											endif;
											?>
											</li>
											<li>
											<?php 
											if ( strpos($contest->currentPhaseName,'Submission') !== FALSE || strpos($contest->currentPhaseName,'Screening') !== FALSE || strpos($contest->currentPhaseName,'Review') !== FALSE ):
										  ?>
										  <li><span class="inactive">Results</span></li>
											<?php 
											else:
											?>
											<li><a href="#winner" class="link">Results</a></li>
											<?php
											endif;
											?>
											</li>
										</ul>
									</nav>
							  <div id="contest-overview" class="tableWrap tab">
									<?php 
								if ( $contestType != 'design' ):
								?>								
                                <article id="contestOverview">
                                <h1>Challenge Overview</h1>
									<p><?php echo $contest->detailedRequirements;?></p>

                             
									<article id="technologies">
										<h1>Technologies</h1>
										<?php 
											
											echo '<ul>';
											if (!empty($contest->technology)){
											  	foreach( $contest->technology as $value ){
													echo '<li><strong>' . $value . '</li></strong>';
											  	}
											} else {
												echo '<li><strong>Not Specified</li></strong>';
											}
										  	echo '</ul>';
										?>  
									</article>

<h3>Final Submission Guidelines</h3>
<?php echo $contest->finalSubmissionGuidelines;?>


        
<article id="payments">
	<h1>Payments</h1>
    <p>TopCoder will compensate members in accordance with the payment structure of this challenge.  
    Initial payment for the winning member will be distributed in two installments. The first payment 
    will be made at the closure of the approval phase. The second payment will be made at the 
    completion of the support period.</p>

<h2>Reliability Rating and Bonus</h2>
<p>For challenges that have a reliability bonus, the bonus depends on the reliability rating at 
	the moment of registration for that project. A participant with no previous projects is 
	considered to have no reliability rating, and therefore gets no bonus.
	Reliability bonus does not apply to Digital Run winnings. Since reliability rating is 
	based on the past 15 projects, it can only have 15 discrete values.<br>
<a href="http://apps.topcoder.com/wiki/x/MQD9Ag">Read more.</a></p>
</article>


<article id="eligibility">
	<h1>Eligibility</h1>
    <p>You must be a TopCoder member, at least 18 years of age, meeting all of the membership requirements. In addition, you must fit into one of the following categories.</p>

	<p>If you reside in the United States, you must be either:</p>
	<p>
		<ul>A US Citizen
			<li>A Lawful Permanent Resident of the US</li>
			<li>A temporary resident, asylee, refugee of the U.S., or have a lawfully issued work authorization card permitting unrestricted employment in the U.S.</li>
		</ul>
	</p>
	<p>If you do not reside in the United States:</p>
	<ul><li>You must be authorized to perform services as an independent contractor.
	(Note: In most cases you will not need to do anything to become authorized)
	</li></ul>

</article>



</article>
 
							  </div>
							  <?php 
								else:
								?> 
<article id="contestOverview">
<article id="fullDescription">
	<h1>FULL DESCRIPTION &amp; PROJECT GUIDE</h1>
	<p><?php echo $contest->detailedRequirements;?></p>
</article>

<article id="stockPhotography">
	<h1>STOCK PHOTOGRAPHY</h1>
	<p>Stock photography is not allowed in this contest. All submitted elements must be designed solely by you.<br>
  <a href="http://topcoder.com/home/studio/the-process/copyright-questions/">See this page for more details.</a></p>
</article>

<article id="howtosubmit">
	<h1>How to Submit</h1>
	<p>
		<ul class="howToSubmit">
			<li>New to Studio? <a href="http://topcoder.com/home/studio/new-member-guide/" target="_blank">Learn how to compete
				here</a>.
			</li>
			<li>Upload your submission in three parts (<a
					href="http://topcoder.com/home/studio/the-process/how-to-submit-to-a-contest/" target="_blank">see this FAQs for
				more information</a>). Your design should be finalized and should contain only a single design
				concept (do not include multiple designs in a single submission).
			</li>
			<li>If your submission wins, your source files must be correct and
				"<a href="http://topcoder.com/home/studio/the-process/final-fixes/" target="_blank">Final Fixes</a>" (if
				applicable) must be completed before payment can be released.
			</li>
			<li>You may submit as many times as you'd like during the submission phase, but only the number of files
				listed above in the Submission Limit that you rank the highest will be considered. You can change
				the order of your submissions at any time during the submission phase. If you make revisions to your
				design, please delete submissions you are replacing.
			</li>
		</ul>
	</p>
</article>

<article id="winnerselection">
        <h1>Winner Selection</h1>
        <p>
            Submissions are viewable to the client as they are entered into the contest. Winners are selected by the
            client and are chosen solely at the Client's discretion.
        </p>
</article>

<article id="payments">
	<h1>Payments</h1>
    <p>TopCoder will compensate members in accordance with the payment structure of this challenge.  
    Initial payment for the winning member will be distributed in two installments. The first payment 
    will be made at the closure of the approval phase. The second payment will be made at the 
    completion of the support period.</p>

<h2>Reliability Rating and Bonus</h2>
<p>For challenges that have a reliability bonus, the bonus depends on the reliability rating at 
	the moment of registration for that project. A participant with no previous projects is 
	considered to have no reliability rating, and therefore gets no bonus.
	Reliability bonus does not apply to Digital Run winnings. Since reliability rating is 
	based on the past 15 projects, it can only have 15 discrete values.<br>
<a href="http://apps.topcoder.com/wiki/x/MQD9Ag">Read more.</a></p>
</article>


<article id="eligibility">
	<h1>Eligibility</h1>
    <p>You must be a TopCoder member, at least 18 years of age, meeting all of the membership requirements. In addition, you must fit into one of the following categories.</p>

	<p>If you reside in the United States, you must be either:</p>
	<p>
		<ul>A US Citizen
			<li>A Lawful Permanent Resident of the US</li>
			<li>A temporary resident, asylee, refugee of the U.S., or have a lawfully issued work authorization card permitting unrestricted employment in the U.S.</li>
		</ul>
	</p>
	<p>If you do not reside in the United States:</p>
	<ul><li>You must be authorized to perform services as an independent contractor.
	(Note: In most cases you will not need to do anything to become authorized)
	</li></ul>

</article>

</article>
 
							  </div>
							 <?php
								endif;
								?>          
<div id="viewRegistrant" class="tableWrap hide tab">
 
  
  <article>
  <h1>REGISTRANTS</h1>
  <table class="registrantsTable">
		<thead>
			<tr>
				<th class="handleColumn">
					<div>Handle</div>
				</th>
				<?php 
				if ( $contestType != 'design' ):
				?>							
				<th class="ratingColumn">
					<div>Rating</div>
				</th>
				<th class="reliabilityColumn">
					<div>Reliability</div>
				</th>
				<?php
				endif;
				?>  
				<th class="regDateColumn">
					<div>Registration Date</div>	
				</th>
				<th class="subDateColumn">
					<div>Submission Date</div>
				</th>
			</tr>	
		</thead>
		<tbody>
			<?php
			for ($i = 0; $i < count($registrants); $i++) :
				$registrant = $registrants[$i];
		 ?>
		 <?php 
		 	/*$registrant_details = get_raw_coder($registrant->handle);
			$registrant_ratings = $registrant_details->ratingsSummary;

			$registrant_color = $registrant_ratings[0]->colorStyle;

			$registrant_max_rating = 'Not Rated';

			

			if (count ( $registrant_ratings ) > 0) {

				foreach ( $registrant_ratings as $registrant_rating ) {

					$cur_rating = $registrant_rating->rating;

					if ($registrant_max_rating == 'Not Rated' || $registrant_max_rating < $cur_rating) {

						$registrant_max_rating = $cur_rating;

						$registrant_color = $registrant_rating->colorStyle;

					}

				}

			}

			if ($registrant_details->isPM) {

				$registrant_color = "color: #FF9900";

			}*/
		 ?>
			<tr class="<?php if($i % 2 == 1) { echo 'even'; } ?>">
				<td class="handleColumn">
					<span><?php echo $registrant->handle; ?></span>
				</td>
				<?php 
				if ( $contestType != 'design' ):
				?>
				<td class="ratingColumn">
					Not Rated
				</td>
				<td class="reliabilityColumn">
					<?php echo $registrant->reliability; ?>			
				</td>
				<?php
				endif;
				?>
				<td class="regDateColumn">
					<?php echo date("M d, Y H:i", strtotime("$registrant->registrationDate")) . " EST" ?>	
				</td>
				<td class="subDateColumn">
					--		
				</td>
			</tr>
			<?php endfor; ?> 
		</tbody>
	</table>
  <div class="registrantsTable mobile hide">
  	<?php
			for ($i = 0; $i < count($registrants); $i++) :
				$registrant = $registrants[$i];
		?>
  	<div class="registrantSection">
			<div class="registrantSectionRow registrantHandle"><?php echo $registrant->handle; ?></div>
			<?php 
			if ( $contestType != 'design' ):
			?>
			<div class="registrantSectionRow">
				<div class="registrantLabel">Rating:</div>
				<div class="registrantField">Not Rated</div>
				<div class="clear"></div>
			</div>
			<div class="registrantSectionRow">
				<div class="registrantLabel">Reliability:</div>
				<div class="registrantField"><?php echo $registrant->reliability; ?></div>
				<div class="clear"></div>
			</div>
			<?php
			endif;
			?>
			<div class="registrantSectionRow">
				<div class="registrantLabel">Registration Date:</div>
				<div class="registrantField"><?php echo date("M d, Y H:i", strtotime("$registrant->registrationDate")) . " EST" ?></div>
				<div class="clear"></div>
			</div>
			<div class="registrantSectionRow">
				<div class="registrantLabel">Submission Date:</div>
				<div class="registrantField">--</div>
				<div class="clear"></div>
			</div>
  	</div>
  	<?php endfor; ?>
  </div>
  </article>
  
</div>
                              <div id="winner" class="tableWrap hide tab">
										 
                                         
                                         <article>
                                         Coming Soon...
                                         </article>
                                         
									</div>
                              <div id="checkpoints" class="tableWrap hide tab">
										 
                                         
                                         <article>
                                         Coming Soon...
                                         </article>
                                         
									</div>
																				<div id="submissions" class="tableWrap hide tab">
										 
                                         
                                         <article>
                                         Coming Soon...
                                         </article>
                                         
									</div>
                              </section>
                              </div>
									 
									  
                                 
							</div>
							<!-- /.mainStream -->
							<aside class="sideStream  grid-1-3">
								  
                            <div class="topRightTitle"> 
                            
                             	<?php 
								if ( $contestType != 'design' ):
								?>								
	                            	<a href="http://apps.topcoder.com/forums/?module=Category&categoryID=<?php echo $contest->forumId;?>" class="contestForumIcon" target="_blank">Challenge Discussion</a>  
								<?php
								else:
								?>
	                            	<a href="http://studio.topcoder.com/forums?module=ThreadList&forumID=<?php echo $contest->forumId;?>" class="contestForumIcon" target="_blank">Challenge Discussion</a>  
								<?php
								endif;
								?>                            
                            
							</div>
                            
                            <div class="columnSideBar"> 
                            
                            <div class="slider">
									<ul>
										<?php 
										if ( $contestType != 'design' ):
										?>
										<li class="slide">
						                   <div class="slideBox">
						               		     <h3>Downloads:</h3>                                
						                   	  	<div class="inner">						                   	  	
						                   	  	<ul class="attachedDocs">
						                   	  	<?php 
						                   	  	$docs = $contest->Documents;
						                   	  	if(count($docs)==0){
													echo '<li>No file to download.</li>';
												}
						                   	  	foreach ($docs as &$doc) {
						                   	  		echo '<li> <a href='.$doc->url.'>'.$doc->documentName.'</a></li>';
						                   	  	}
						                   	  	?>
													</ul>								               	  	
						                   	  	</div>
						                   </div>                          
										</li>
										<li class="slide">											 
                                             <div class="reviewStyle slideBox">
                                                <h3>Review Style:</h3>
                                                <div class="inner">
                                                    <p><strong>Final Review: </strong><span>Community Review Board</span>
                                                            <a onmouseout="hideTooltip('FinalReview');" onmouseover="showTooltip(this, 'FinalReview');" class="tooltip" href="javascript:;"> </a></p>
                                                       <p><strong>Approval: </strong><span>User Sign-Off</span>
                                                            <a onmouseout="hideTooltip('Approval');" onmouseover="showTooltip(this, 'Approval');" class="tooltip" href="javascript:;"> </a>
                                                        </p> 
                                                </div>
                                                
                                            </div>
                                            <!-- End review style section -->
                                             
										</li>
										<li class="slide">
											 
                                             <div class="contestLinks slideBox">
                                                <h3>Contest Links:</h3>
                                                <div class="inner">
												   <p><a href="https://software.topcoder.com/review/actions/ViewScorecard.do?method=viewScorecard&scid=<?php echo $contest->screeningScorecardId;?>">Screening Scorecard</a></p>
                                                   <p><a href="http://software.topcoder.com/review/actions/ViewScorecard.do?method=viewScorecard&scid=<?php echo $contest->reviewScorecardId;?>">Review Scorecard</a></p> 
												</div>
                                                
                                            </div>
                                             
										</li>
										
										<li class="slide">
											 <div class="forumFeed slideBox">&nbsp;<br />
                                         <!--                    
                                
								<h3>Forums Feed:</h3> 
								<div class="inner">
                                	<div class="scroll-pane jspScrollable" style="overflow: hidden; padding: 0px; width: 263px;" tabindex="0">
                                    
                                    
                                        
                                    <div class="jspContainer" style="width: 263px; height: 400px;"><div class="jspPane" style="padding: 0px; width: 256px; top: 0px;"><div class="forumItemWrapper">
                                    <div class="forumItem">
                                        	<p class="forumTitle"><a href="#">Forum title lorem ipsum</a></p>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas eu eros id nunc</p>
                                            <p class="forumInfo">
                                            Post by <a href="#">Someone</a> |  12/13/13  07:00 ET
                                            </p>
                                       </div>
                                       <div class="forumItem">
                                        	<p class="forumTitle"><a href="#">Forum title lorem ipsum</a></p>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas eu eros id nunc</p>
                                            <p class="forumInfo">
                                            Post by <a href="#">Someone</a> |  12/13/13  07:00 ET
                                            </p>
                                       </div>
                                       <div class="forumItem">
                                        	<p class="forumTitle"><a href="#">Forum title lorem ipsum</a></p>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas eu eros id nunc</p>
                                            <p class="forumInfo">
                                            Post by <a href="#">Someone</a> |  12/13/13  07:00 ET
                                            </p>
                                        </div>
                                        <div class="forumItem">
                                        	<p class="forumTitle"><a href="#">Forum title lorem ipsum</a></p>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas eu eros id nunc</p>
                                            <p class="forumInfo">
                                            Post by <a href="#">Someone</a> |  12/13/13  07:00 ET
                                            </p>
                                        <div class="forumItem">
                                        </div>
                                        	<p class="forumTitle"><a href="#">Forum title lorem ipsum</a></p>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas eu eros id nunc</p>
                                            <p class="forumInfo">
                                            Post by <a href="#">Someone</a> |  12/13/13  07:00 ET
                                            </p>
                                        </div>
                                        <div class="forumItem">
                                        	<p class="forumTitle"><a href="#">Forum title lorem ipsum</a></p>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas eu eros id nunc</p>
                                            <p class="forumInfo">
                                            Post by <a href="#">Someone</a> |  12/13/13  07:00 ET
                                            </p>
                                        </div>
                                        <div class="forumItem">
                                        	<p class="forumTitle"><a href="#">Forum title lorem ipsum</a></p>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas eu eros id nunc</p>
                                            <p class="forumInfo">
                                            Post by <a href="#">Someone</a> |  12/13/13  07:00 ET
                                            </p>
                                        </div>
                                        <div class="forumItem">
                                        	<p class="forumTitle"><a href="#">Forum title lorem ipsum</a></p>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas eu eros id nunc</p>
                                            <p class="forumInfo">
                                            Post by <a href="#">Someone</a> |  12/13/13  07:00 ET
                                            </p>
                                        </div>
                                        </div></div><div class="jspVerticalBar"><div class="jspCap jspCapTop"></div><div class="jspTrack" style="height: 400px;"><div class="jspDrag" style="height: 214px;"><div class="jspDragTop"></div><div class="jspDragBottom"></div></div></div><div class="jspCap jspCapBottom"></div></div></div></div>
                                </div>
								-->
                                
                            </div>
										</li>
										<?php
								   else:
								   ?>
								   <li class="slide">
                   <div class="slideBox">
               		     <h3>Downloads:</h3>                                
                   	  	<div class="inner">
                   	  	
                   	  	<ul class="attachedDocs">
                   	  	<?php 
                   	  	$docs = $contest->Documents;
                   	  	if(count($docs)==0){
							echo '<li>No file to download.</li>';
						}
                   	  	foreach ($docs as &$doc) {
                   	  		echo '<li> <a href='.$doc->url.'>'.$doc->documentName.'</a></li>';
                   	  	}
                   	  	?>
							</ul>								               	  	
                   	  	</div>
                   </div>                          
										</li>
										<li class="slide">
                   <div class="slideBox">
               		  <h3>How to Format Your Submission:</h3>                                
                   	  	<div class="inner">
                <ul>
		    <b>Your Design Files:</b><br>
                    <li>1. Look for instructions in this contest regarding what files to provide.
                    </li>
                    <li>2. Place your submission files into a "Submission.zip" file.</li>
                    <li>3. Place all of your source files into a "Source.zip" file.</li>
                    <li>4. Create a JPG preview file.</li>
                </ul>

                <p>Trouble formatting your submission or want to learn more?
                    <a href="http://topcoder.com/home/studio/the-process/how-to-submit-to-a-contest/">Read this FAQs</a>.</p>
		
		<p><strong>Fonts:</strong><br> All fonts within your design must be declared when you submit. DO NOT <a style="white-space:nowrap;">include any font files in your submission</a><a style="white-space:nowrap;"> <br>or source files. </a><a href="http://topcoder.com/home/studio/the-process/font-policy/" style="white-space:nowrap;">Read the font policy here</a>.
                </p>

		<p><strong>Screening:</strong><br>All submissions are screened for eligibility before the contest holder picks winners. Don't let your hard work go to waste.<br> <a href="http://community.topcoder.com/studio/the-process/screening/">Learn more about how to pass screening here</a>.
		</p>

		<p>Questions? <a href="http://studio.topcoder.com/forums?module=ThreadList&amp;forumID=6">Ask in the Forums</a>.
		</p>                   	  	
                   	  	
                   	  	</div>
                   </div>                          
										</li>
										<li class="slide">
                   <div class="slideBox">
               		     <!-- <h3>Forums Feed:</h3> -->                                
                   	  	<div class="inner"></div>
                   </div>                           
										</li>
										<li class="slide">
                   <div class="slideBox">
               		     <h3>Source Files:</h3>                                
                   	  	<div class="inner">

                <ul>
                    
                        <li><strong>Text or Word Document containing all of your ideas and supporting information.</strong></li>
                    
                </ul>

                <p>You must include all source files with your submission. </p>                   	  	
                   	  	</div>
                   </div>                          
										</li>
										<li class="slide">
                   <div class="slideBox">
               		     <h3>Submission Limit:</h3>                                
                   	  	<div class="inner">
																<p>
                
                    
                        <strong>Unlimited</strong>
                    
                    
                    
                
            </p>                   	  	
                   	  	</div>
                   </div>                          
										</li>
										<li class="slide">
                   <div class="slideBox">
                   &nbsp;
                   <br/>
                   </div>                         
										</li>
								   <?php
										endif;
										?>  
									</ul>
								</div>
                            
                            </div>
                                
							</aside>
							<!-- /.sideStream -->
							<div class="clear"></div>
						</div>
						<!-- /.rightSplit -->   
                    </article>
		<!-- /#mainContent -->
		
<?php get_footer('tooltip'); ?>

<?php
/**
 * Template Name: Referral template
 */
?>
<?php

get_header();

$values = get_post_custom ( $post->ID );

$userkey = get_option ( 'api_user_key' );
$siteURL = site_url ();

?>

<script type="text/javascript">
	var siteurl = "<?php bloginfo('siteurl');?>";	
</script>
<div class="content">
	<div id="main">

	<?php
	
	if (have_posts ()) :
		the_post ();
		$quote = get_post_meta ( $post->ID, "Quote", true );
		$qAuthor = get_post_meta ( $post->ID, "Quote author", true );
		?>
	<!-- Start Overview Page-->
		<div class="pageTitleWrapper">
			<div class="pageTitle container">
				<h2 class="overviewPageTitle"><?php the_title();?></h2>
			</div>
		</div>




		<article id="mainContent" class="splitLayout overviewPage">
			<div class="container">
				<div class="rightSplit  grid-3-3">
					<div class="mainStream grid-2-3">
						
						<section class="pageContent">
						<?php the_content();?>
						</section>
					<?php endif; wp_reset_query();?>
					
					
						<!-- /.pageContent -->

					</div>
					<!-- /.mainStream -->
					<aside class="sideStream  grid-1-3">
       <div class="slideBox">
         <h3>Share:</h3>
     
         <div class="inner">
           <!-- AddThis Button BEGIN -->
           <div class="addthis_toolbox addthis_default_style addthis_32x32_style">
             <a class="addthis_button_preferred_1"></a>
             <a class="addthis_button_preferred_2"></a>
             <a class="addthis_button_preferred_3"></a>
             <a class="addthis_button_compact"></a>
             <a class="addthis_counter addthis_bubble_style"></a>
           </div>
           <script type="text/javascript">
													$(document).ready(function(e) {

															// URL Referral Generator
															if ( $('#referralText').length>0 ) {
																	var tcsso = getCookie('tcsso');
																	if (tcsso) {
																			var tcssoValues = tcsso.split("|");
																			var handle = '';
																								
																			$.getJSON("http://community.topcoder.com/tc?module=BasicData&c=get_handle_by_id&dsid=30&uid=" + tcssoValues[0] + "&json=true", function(data) {
																					handle = data['data'][0]['handle'];
																				
																					if ( handle!='' ) {
																							$base_url = '<?php echo get_post_meta( $post->ID, "_tc_base_url", true ); ?>';
																							$ref_url =  $base_url.replace('{handle}', handle); 
																							$('#referralText').val($ref_url).focus();
																							
																							$('.addthis_toolbox a').attr('addthis:url', $ref_url);
																							$('.addthis_toolbox a').attr('addthis:title', '[topcoder] Referral');
																							$('.addthis_toolbox a').attr('addthis:description', '<?php echo get_post_meta( $post->ID, "_tc_text_snippet", true ); ?>');
																							
																					} else {
																							$('#loginFirst').show();
																							$('#referralText').remove();
																					}
																			});																		
																} else {							
																		$('#loginFirst').show();
																		$('#referralText').remove();
																}
																
																$('#referralText').focus(function(){
																		$(this).select();
																});
														}           
            });
           </script>
           <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-52f22306211cecfc"></script>
           <!-- AddThis Button END -->
         </div>
       </div>

					</aside>
					<!-- /.sideStream -->
					<div class="clear"></div>
				</div>
				<!-- /.rightSplit -->
			</div>
		</article>
		<!-- /#mainContent -->
<?php get_footer(); ?>
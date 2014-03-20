<?php
/**
 * Template Name: Terms List Template
 */
?>
<?php
get_header ();
?>
<script type="text/javascript">
	var siteurl = "<?php bloginfo('siteurl');?>";
	var challengeId = "<?php echo get_query_var('contestID');?>";
</script>
<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_directory' ); ?>/css/terms.css" />
<script src="<?php bloginfo( 'stylesheet_directory' ); ?>/js/terms-list.js" type="text/javascript"></script>
<div class="content">
	<div id="main" class="registerForChallenge">
		<article id="mainContent">
			<div class="container">
       <h2 class="pageTitle">Challenge Terms</h2>
       <!-- /#end page title-->
       <div class="formContent">
           <p class="terms hide">
               The following groups of terms apply to this challenge. You need to agree to all of the terms within the group before you can register
           </p>
           <p class="terms warning hide"></p>
           <table class="termTable hide">
               <thead>
	               <tr>
	                   <th>Terms</th>
	                   <th>Status</th>
	               </tr>
               </thead>
               <tbody>
               </tbody>
           </table>
           <!-- /#end terms-->
           <div class="termsBtnRegister hide"><a href="javascript:;" class="btn">Register</a></div>
       </div>
       <!-- /#end form content-->
   </div>
		</article>
		</div>
</div>
<!-- /#mainContent -->
<?php get_footer(); ?>
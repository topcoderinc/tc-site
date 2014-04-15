<?php
/**
 * Template Name: Terms Details Template
 */

$termType = 'detail';
get_header('terms');

?>

<div class="content">
  <div id="main">
    <div class="pageTitleWrapper">
      <div class="pageTitle container">
        <h2 class="overviewPageTitle"></h2>
      </div>
    </div>
    <article id="mainContent splitLayout">
      <div class="container">
        <div class="formContent">
          <p class="terms termsText hide"></p>

          <p class="terms warning hide"></p>

          <form id="submitForm">
            <section class="agreement notAgreed">
              <div class="agree-label">
                <span><a href="javascript:;"></a><input id="agree" type="checkbox"></span>
                <label style="cursor: pointer;">I Agree to the Terms and Conditions stated above</label>
              </div>
              <div>
                <a href="javascript:" id="termSubmit" class="btn">Submit</a>
              </div>
            </section>
          </form>
        </div>
      </div>
      <!-- /#end form content-->
  </article>
</div>
</div>
<!-- /#mainContent -->
<?php get_footer(); ?>

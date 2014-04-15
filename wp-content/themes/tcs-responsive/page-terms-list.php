<?php
/**
 * Template Name: Terms List Template
 */

$termType = 'list';
get_header('terms');

?>

  <div class="content">
    <div id="main" class="registerForChallenge">
      <article id="mainContent">
        <div class="container">
          <h2 class="pageTitle"><?php the_title(); ?></h2>
          <!-- /#end page title-->
          <div class="formContent">
            <p class="terms"><?php echo $post->post_content; ?></p>
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
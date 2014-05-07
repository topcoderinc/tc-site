<?php
/*
Template Name: 404
*/
get_header();
?>
<div class='content not-found'>
    <div class='container'>
        <div class='logo-tc'>
            <img src='<?php echo get_template_directory_uri(); ?>/i/logo-tc.png' />
        </div>
    </div>
    <div class='error-info'>
        <img src='<?php echo get_template_directory_uri(); ?>/i/logo-shadow-line.png' />
        <div class='wrap'>
            <h1>ERROR 404: Page not Found</h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris malesuada tristique lacus, vel auctor arcu aliquam sit amet. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec placerat, nulla eget suscipit pulvinar, lacus leo consectetur lorem, a lacinia eros tellus eu libero.</p>
        </div>
    </div>
    <div class='wrap'>
        <div class='origami'>
            <img class='origami-image' src='<?php echo get_template_directory_uri(); ?>/i/origami-print.png' />
            <div class='step-1'>
                Fold an 85 cm by 120 cm. <br/>
                sheet of paper in half <br/>
                from top to bottom
            </div>
            <div class='step-2'>
                Fold down the top corners <br/>
                while leaving 6 cm. of space <br/>
                at the bottom.
            </div>
            <div class='step-3'>
                Fold the bottom of the <br/>
                paper up against both <br/>
                sides and fold the <br/>
                top corner in.
            </div>
            <div class='step-4'>
                Lift the middle and <br/>
                push both points together.<br/>
                to make a squash fold
            </div>
            <div class='step-5'>
                Squash fold in progress
            </div>
            <div class='step-6'>
                Fold bottom point <br/>
                about half.<br/>
                repeat behind
            </div>
            <div class='step-7'>
                Repeat behind
            </div>
            <div class='step-8'>
                Lift the middle and<br/>
                push both points together.<br/>
                to make a squash fold
            </div>
            <div class='step-9'>
                Squash fold in progress
            </div>
            <div class='step-10'>
                Pull the points out<br/>
                to shape the hat
            </div>
            <div class='step-11'>
                Fold the left and right<br/>
                corners in and fold <br/>
                the bottom corner up
            </div>
            <div class='step-12'>
                Fold the left and right<br/>
                corners in 
            </div>
            <div class='step-13'>
                Flatten well to crease all folds.<br/>
                Then open out slightly
            </div>
            <div class='clear'></div>
        </div>
        <div class='button'>
            <a href='javascript:window.print();'>PRINT</a>
        </div>
    </div>
    <div class='footer'>
        <div class='wrap'>
            &#64; 2014 topcoder. All rights reserved.<br/>
            <a href='#'>Privacy Statement</a>  |  <a href='#'>Legal Disclaimer</a>
        </div>
    </div>
</div>
<?php wp_footer(); ?>
</body>
</html>

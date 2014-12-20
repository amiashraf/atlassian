<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package Atlastheme
 * @subpackage Atlassian
 * @since Atlassian 1.0
 */
?>


<!-- FOOTER -->
<div class="footer-block">
    <div class="container">
        <div class="row">
            <div class="foot-top-line"></div>
            <div class="foot-bot-line"></div>
            <a class="scroll-top" href="#"><i class="fa fa-angle-up"></i> </a> </div>
    </div>
    <?php get_sidebar( 'footer' ); ?>
    <div class="container">
        <div class="row text-center copyright-info">
            <p>BJGroup, Star Trade © All rights reserved <?php the_date('Y'); ?></p>
        </div>
        <div class="row text-center copyright-info">
            <p>Designed and Developed by <a target="_blank" href="http://www.everexpert.com">EverExpert</a></p>
        </div>
    </div>
</div>
<!-- END -->

<!-- ABSOLUTE FOOTER -->
<div class="copyright-block">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 left">
                <ul class="unstyled clearfix social-team">
                    <li><a href="http://fb.com/#" target="_blank"><i class="fa fa-facebook"></i></a></li>
                    <li><a href="http://twitter.com/#" target="_blank"><i class="fa fa-twitter"></i></a></li>
                    <li><a href="http://plus.google.com/#" target="_blank"><i class="fa fa-google"></i></a></li>
                    <li><a href="http://youtube.com/#" target="_blank"><i class="fa fa-youtube"></i></a></li>
                    <li><a href="http://rss.com/#" target="_blank"><i class="fa fa-rss"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- END -->

<!-- SCRIPTS -->
<?php wp_footer(); ?>
</body>
</html>

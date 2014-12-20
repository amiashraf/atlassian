<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other 'pages' on your WordPress site will use a different template.
 *
 * @package Atlastheme
 * @subpackage Atlassian
 * @since Atlassian 1.0
 */

get_header(); ?>

<main id="main" class="section">
    <div class="container">
        <div class="row">
        <?php
            if ( is_front_page() && atlassian_has_featured_posts() ) {
                // Include the featured content template.
                get_template_part( 'featured-content' );
            }
        ?>
            <div id="primary" class="content-area col-xs-12 col-sm-12 col-md-9">
                <div id="content" class="site-content" role="main">

                    <?php
                        // Start the Loop.
                        while ( have_posts() ) : the_post();

                            // Include the page content template.
                            get_template_part( 'content', 'page' );

                            // If comments are open or we have at least one comment, load up the comment template.
                            /*if ( comments_open() || get_comments_number() ) {
                                comments_template();
                            }*/
                        endwhile;
                    ?>
                </div><!-- #content -->
            </div><!-- #primary -->
        <?php
        get_sidebar();
        ?>
        </div>
    </div>
</main>
<?php
get_footer();

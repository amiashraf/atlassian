<?php
/**
 * The template for displaying Category pages
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Atlastheme
 * @subpackage Atlassian
 * @since Atlassian 1.0
 */

get_header(); ?>
    <main id="main" class="section">
        <div class="container">
            <div class="row">
                <!-- MAIN CONTENT -->
                <div class="col-xs-12 col-sm-12 col-md-9">
                    <section class="main-content" role="main">
                        <?php if ( have_posts() ) : ?>

                            <header class="archive-header">
                                <div class="carousel-title ">
                                    <div class="transform-please-2 upcoming-cars"> <span> <?php printf( __( 'Category : %s', 'atlassian' ), single_cat_title( '', false ) ); ?> </span> </div>
                                </div>

                                <?php
                                // Show an optional term description.
                                $term_description = term_description();
                                if ( ! empty( $term_description ) ) :
                                    printf( '<div class="taxonomy-description">%s</div>', $term_description );
                                endif;
                                ?>
                            </header><!-- .archive-header -->
                        <ul class="list-group catalog-product-list">

                            <?php
                            // Start the Loop.
                            while ( have_posts() ) : the_post();
?>
                                <li class="list-group-item    animated" data-animation="bounceInLeft">
                                    <div class="media col-md-5  col-sm-5 col-xs-12"> <a class="arrow-link" href="#"> <span class="icon-transform transform-please"><i class="fa fa-angle-right"></i></span></a>
                                        <figure class="pull-left"> <a href="#"> <?php the_post_thumbnail('categorylist-thumb'); ?></a> </figure>
                                    </div>
                                    <div class="col-md-7 col-xs-12  col-sm-7 list-group-item-content">
                                        <h4 class="list-group-item-heading"> <?php the_title();?> <span  class="seller">By: <a href="#">AutoMaker</a></span> </h4>
                                        <div class="price">
                                            <?php $vs_model_year_meta = get_post_meta( $post->ID, 'vs_model_year', true );?>
                                            <h2> <?php echo $vs_model_year_meta ?></h2>
                                        </div>
                                        <p class="list-group-item-text"> <?php the_excerpt(); ?> </p>
                                        <div class="product-box">
                                            <div class="btn-group"> <a href="<?php the_permalink(); ?>" class="btn btn-default btn-black "><i class="fa fa-car"></i><span>View more</span></a>  </div>
                                        </div>
                                    </div>
                                </li>

<?php                            endwhile;
                            ?>
                            </ul>
        <?php
                            // Previous/next page navigation.
                            atlassian_paging_nav();

                        else :
                            // If no content, include the "No posts found" template.
                            get_template_part( 'content', 'none' );

                        endif;
                        ?>


                    </section>
                </div>
                <!-- // MAIN CONTENT -->
                <?php
                get_sidebar();
                ?>
            </div>
        </div>
    </main>
<?php
get_footer();

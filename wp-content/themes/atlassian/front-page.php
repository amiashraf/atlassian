<?php
/**
 * The Header for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Atlastheme
 * @subpackage Atlassian
 * @since Atlassian 1.0
 */
?>
    <!DOCTYPE html>
    <!--[if IE 7]>
    <html class="ie ie7" <?php language_attributes(); ?>>
    <![endif]-->
    <!--[if IE 8]>
    <html class="ie ie8" <?php language_attributes(); ?>>
    <![endif]-->
    <!--[if !(IE 7) & !(IE 8)]><!-->
<html <?php language_attributes(); ?>>
    <!--<![endif]-->
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php wp_title( '|', true, 'right' ); ?></title>
        <link rel="shortcut icon" type="image/x-icon" href="favicon.png" />
        <link rel="profile" href="http://gmpg.org/xfn/11">
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
        <!--[if lt IE 9]>
        <script src="<?php echo get_template_directory_uri(); ?>/js/html5shiv-3.7.0.js"></script>
        <script src="<?php echo get_template_directory_uri(); ?>/js/respond-1.4.2.js"></script>
        <![endif]-->
        <?php wp_head(); ?>
    </head>
<body <?php body_class(array('noIE','inner-page')); ?>>
<!-- Loader -->
<div id="ip-container" class="ip-container">
    <!-- initial header -->
    <header class="ip-header">
        <div class="ip-loader"> 	<svg class="ip-inner" width="60px" height="60px" viewBox="0 0 80 80">
                <path class="ip-loader-circlebg" d="M40,10C57.351,10,71,23.649,71,40.5S57.351,71,40.5,71 S10,57.351,10,40.5S23.649,10,39.3,10z"/>
                <path id="ip-loader-circle" class="ip-loader-circle" d="M40,10C57.351,10,71,23.649,71,40.5S57.351,71,40.5,71 S10,57.351,10,40.5S23.649,10,40.5,10z"/>
            </svg>
        </div>
    </header>
    <!-- Loader end -->

</div>

<!-- HEADER -->
<header id="main-header">
    <div id="cd-nav"> <a href="#0" class="cd-nav-trigger">Menu<span></span></a>
        <nav id="cd-main-nav">
            <ul>
                <li class="active"><a href="index.html"><span>Star Trade</span> <small> BEST COLLECTION OF CARS</small> </a></li>
                <li><a href="blog.html"><span>Services</span> <small>NEWS ABOUT UPCOMING</small></a></li>
                <li><a href="catalog.html" class="dropdown-toggle" data-toggle="dropdown"><span>Catalog <b class="caret"></b></span> <small>List of all cars</small></a>
                    <ul>
                        <li><a href="catalog.html">Toyota</a></li>
                        <li><a href="catalog.html">Nissan</a></li>
                        <li><a href="catalog.html">Honda</a></li>
                        <li><a href="catalog.html">Mitsubishi</a></li>
                    </ul>
                </li>
                <li><a href="#"><span>Warehouse</span> <small>SEE ALL NEWEST MODELS</small></a></li>
                <li><a href="shop.html"  class="dropdown-toggle" data-toggle="dropdown"><span>Other Concern<b class="caret"></b> </span> <small>SHARE YOUR OPINION</small></a>
                    <ul>
                        <li><a href="catalog.html">Japans car</a></li>
                        <li><a href="product.html">Automobile Solutions</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
    <div class="container ">
        <div class="row">
            <div class="col-md-2 spacer"> </div>
            <div class="col-md-7">
                <!-- NAVIGATION -->
                <div class="navbar navbar-default navbar-top ">
                    <div class="navbar-collapse">
                        <ul class="nav navbar-nav nav-justified">
                            <li><a href="#"><span>About</span></a></li>
                            <li class="current" ><a href="#"><span>Automobile</span></a></li>
                            <li><a href="#"><span>Properties</span></a></li>
                            <li><a href="#"><span>Agro Aqua</span></a></li>
                            <li><a href="#"><span>Contact</span></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <!-- LOGO -->
            <?php if ( get_header_image() ) : ?>
                <div id="logo" > <span class="bglogo1 transform-please-2"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="logo"/></a></span> <span class="bglogo2 transform-please-2"></span> <span class="bglogo3 transform-please-2"></span> <span class="bglogo4 transform-please-2"></span> </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="container">
        <nav id="main-nav">
            <div class="row">
                <div class="col-md-12">
                    <!-- NAVIGATION -->
                    <div class="navbar navbar-default" role="navigation">
                        <div class="navbar-header"> </div>
                        <div class="navbar-collapse collapse">
                            <?php
                            $at_walker_obj = new At_Walker_Nav_Menu();

                            wp_nav_menu(
                                array(
                                    'theme_location' => 'primary',
                                    'menu_class' => 'nav-menu',
                                    'items_wrap' => '<ul id="%1$s" class="nav navbar-nav nav-justified">%3$s</ul>',
                                    'walker' => $at_walker_obj
                                )
                            ); ?>
                        </div>
                        <div class="mini-search-top">
                            <?php get_search_form(); ?>
                            <form class="form-mini-search" >
                                <input type="text" placeholder="Search..">
                                <button  type="submit" class="arrow-link submit-link"> <span class="icon-transform transform-please"><i class="fa fa-search"></i></span></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>
<!-- HEADER END -->

<?php
    $baseurl_upload = wp_upload_dir()['baseurl'];
?>

<!-- HOME SLIDER -->
<div id="slider">
    <div class="camera_slider">
        <?php  get_featured_post();  ?>
    </div>
</div>
<!-- HOME SLIDER END -->

        <?php
        if ( have_posts() ) :
            // Start the Loop.
            while ( have_posts() ) : the_post();
                    the_content();
            endwhile;
        else :
            // If no content, include the "No posts found" template.
            get_template_part( 'content', 'none' );
        endif;
        ?>

<?php
if ( ! is_active_sidebar( 'sidebar-3' ) ) {
    return;
}
 dynamic_sidebar( 'sidebar-4' );
get_footer();
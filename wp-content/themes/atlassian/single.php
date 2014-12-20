<?php
/**
 * The Template for displaying all single posts
 *
 * @package Atlastheme
 * @subpackage Atlassian
 * @since Atlassian 1.0
 */

get_header(); ?>
    <section class="main-content" role="main">
    <div class="bg-overlay overlay-top">
        <div class="container">
            <?php
            // Start the Loop.
            while ( have_posts() ) : the_post();

            ?>
                <div class="row">
                    <div class="page-title col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <h3><?php the_title();?></h3>
                        <div class="line transform-please-2"></div>
                    </div>
                    <div class="pb-left-column col-xs-12 col-sm-12 col-md-7 col-lg-7">
                        <div class="clearfix" id="image-block">
                            <div id="slider-product" class="flexslider">
                                <?php
                                if( class_exists('Dynamic_Featured_Image') ) {
                                    global $dynamic_featured_image;
                                    $featured_images = $dynamic_featured_image->get_featured_images( );
                                    //print_r($featured_images); die;
                                    //You can now loop through the image to display them as required
                                }
                                ?>
                                <ul class="slides">
                                    <?php foreach($featured_images as $each_image):
                                        echo '<li> <a class="fancybox"  rel="flickr" href="'.$each_image['full'].'"> <img src="'.$each_image['full'].'" alt="alt" /></a> </li>';
                                    endforeach;?>
                                </ul>
                            </div>
                            <div id="carousel" class="flexslider">
                                <ul class="slides">
                                    <?php foreach($featured_images as $each_image):
                                    echo '<li> <img src="'.$each_image['thumb'].'" alt="alt" /> </li>';
                                     endforeach;?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <?php
                    $vs_model_no_meta = get_post_meta( $post->ID, 'vs_model_no', true );
                    $vs_model_year_meta = get_post_meta( $post->ID, 'vs_model_year', true );
                    $vs_color_meta = get_post_meta( $post->ID, 'vs_color', true );
                    $vs_cubic_capacity_meta = get_post_meta( $post->ID, 'vs_cubic_capacity', true );
                    $vs_chassis_no_meta = get_post_meta( $post->ID, 'vs_chassis_no', true );
                    $vs_fuel_type_meta = get_post_meta( $post->ID, 'vs_fuel_type', true );
                    $vs_stock_availability_meta = ucfirst(get_post_meta( $post->ID, 'vs_stock_availability', true ));
                    $vs_condition_meta = ucfirst(get_post_meta( $post->ID, 'vs_condition', true ));
                    $vs_other_specfications = get_post_meta( $post->ID, 'vs_other_specfications', true );
                    ?>
                    <div class="pb-center-column col-xs-12 col-sm-12 col-md-5 col-lg-5">
                        <div class="price-line">
                            <div class="price-box transform-please"><span> YEAR - <?php echo $vs_model_year_meta;  ?> </span></div>
                            <div class="price-informer"> </div>
                        </div>
                        <div class="product-featured">
                            <div  class="featured-box">
                                <ul  class="featured-title">
                                    <li>REG. YEAR</li>
                                </ul>
                                <div  class="featured-content">02/2013</div>
                            </div>
                            <div  class="featured-box">
                                <ul  class="featured-title">
                                    <li>ENGINE</li>
                                </ul>
                                <div  class="featured-content">3598 cm³</div>
                            </div>
                            <div  class="featured-box">
                                <div  class="featured-content"> <i class="fa fa-facebook"></i> 65,200 KM </div>
                                <div  class="featured-content"> <i class="fa fa-facebook"></i> PETROL</div>
                            </div>
                        </div>
                        <table class="table-data-sheet">
                            <tbody>
                            <tr>
                                <td>Model No:</td>
                                <td><?php echo $vs_model_no_meta;?></td>
                            </tr>
                            <tr>
                                <td>Manufacturing year:</td>
                                <td><?php echo $vs_model_year_meta;?></td>
                            </tr>
                            <tr>
                                <td>Color:</td>
                                <td><?php echo $vs_color_meta;?></td>
                            </tr>
                            <tr>
                                <td>Cubic Capacity(CC):</td>
                                <td><?php echo $vs_cubic_capacity_meta;?></td>
                            </tr>
                            <tr>
                                <td>Chassis no:</td>
                                <td><?php echo $vs_chassis_no_meta;?></td>
                            </tr>
                            <tr>
                                <td>Fuel Type:</td>
                                <td><?php echo $vs_fuel_type_meta;?></td>
                            </tr>
                            <tr>
                                <td>Stock Availability:</td>
                                <td><?php echo $vs_stock_availability_meta;?></td>
                            </tr>
                            <tr>
                                <td>Condition:</td>
                                <td><?php echo $vs_condition_meta;?></td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="desc">
                            <ul  class="featured-title">
                                <li>VEHICLE INFORMATION</li>
                            </ul>
                            <p><?php echo $vs_other_specfications;?></p>
                        </div>
                        <div class="offer_specification">  </div>
                    </div>
                </div>
                <div class="row">
                <div class="product-tab">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#tab1" data-loading-text="Loading...">Features</a></li>
                        <!--<li><a data-toggle="tab" href="#tab2" data-loading-text="Loading...">Section 2</a></li>
                        <li><a data-toggle="tab" href="#tab3" data-loading-text="Loading...">Section 3</a></li>-->
                    </ul>
                    <div class="tab-content">
                        <div id="tab1" class="tab-pane active fade in">
                            <div class="row">
                                <?php the_content(); ?>
                            </div>
                        </div>
                        <!--<div id="tab2" class="tab-pane fade">
                            <p> Vestibulum nec erat eu nulla rhoncus fringilla ut non neque. Vivamus nibh urna, ornare id gravida ut, mollis a magna. Aliquam porttitor condimentum nisi, eu viverra ipsum porta ut. Nam hendrerit bibendum turpis, sed molestie mi fermentum id. Aenean volutpat velit sem. Sed consequat ante in rutrum convallis. Nunc facilisis leo at faucibus adipiscing. Duis auctor dictum erat hendrerit dapibus. </p>
                        </div>
                        <div id="tab3" class="tab-pane fade">
                            <p> WInteger convallis, nulla in sollicitudin placerat, ligula enim auctor lectus, in mollis diam dolor at lorem. Sed bibendum nibh sit amet dictum feugiat. Vivamus arcu sem, cursus a feugiat ut, iaculis at erat. Donec vehicula at ligula vitae venenatis. Sed nunc nulla, vehicula non porttitor in, pharetra et dolor. Fusce nec velit velit. Pellentesque consectetur eros nec interdum varius. Quisque at mi dolor. </p>
                        </div>-->
                    </div>
                </div>
                </div>
                <section>
                    <?php
                    // Previous/next post navigation.
                    //atlassian_post_nav();
                    ?>
                </section>
            <?php
            endwhile;
            ?>

        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <section class="btn-set-section">
                    <div class="btn-set-middle">
                        <hr>
                        <a href="#" class="btn-call"> <span> CALL US AT: +02 9886196 </span></a> <a href="<?php echo get_permalink(145);?>" class="btn-email"> <span> MAIL US ABOUT THIS CAR </span></a>
                    </div>
                </section>
                <!--<section class="carousel-3">
                    <div class="carousel-title ">
                        <div class="transform-please-2 "> <span> SIMILAR CARS</span> </div>
                    </div>
                    <ul>
                        <li><a href="#"><img alt="alt" src="media/product/small/item_01.jpg" width="170" height="120"></a>
                            <h3>CHRYSLER MINIVANS</h3>
                        </li>
                        <li><a href="#"><img alt="alt" src="media/product/small/item_02.jpg" width="170" height="120"></a>
                            <h3>HYUNDAI SONATA </h3>
                        </li>
                        <li><a href="#"><img alt="alt" src="media/product/small/item_03.jpg" width="170" height="120"></a>
                            <h3>CHEVROLET VOLT</h3>
                        </li>
                        <li><a href="#"><img alt="alt" src="media/product/small/item_04.jpg" width="170" height="120"></a>
                            <h3>HONDA ACCORD</h3>
                        </li>
                        <li><a href="#"><img alt="alt" src="media/product/small/item_05.jpg" width="170" height="120"></a>
                            <h3>ALFA ROMEO BRERA</h3>
                        </li>
                        <li><a href="#"><img alt="alt" src="media/product/small/item_01.jpg" width="170" height="120"></a>
                            <h3>CHRYSLER MINIVANS</h3>
                        </li>
                        <li><a href="#"><img alt="alt" src="media/product/small/item_02.jpg" width="170" height="120"></a>
                            <h3>HYUNDAI SONATA </h3>
                        </li>
                        <li><a href="#"><img alt="alt" src="media/product/small/item_03.jpg" width="170" height="120"></a>
                            <h3>CHEVROLET VOLT</h3>
                        </li>
                        <li><a href="#"><img alt="alt" src="media/product/small/item_04.jpg" width="170" height="120"></a>
                            <h3>HONDA ACCORD</h3>
                        </li>
                        <li><a href="#"><img  alt="alt" src="media/product/small/item_05.jpg" width="170" height="120"></a>
                            <h3>ALFA ROMEO BRERA</h3>
                        </li>
                    </ul>
                </section>-->
            </div>
        </div>
    </div>
    </section>
<?php
//get_sidebar( 'content' );
//get_sidebar();
get_footer();

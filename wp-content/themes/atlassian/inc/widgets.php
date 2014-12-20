<?php
/**
 * Custom Widget for displaying specific post formats
 *
 * Displays posts from Aside, Quote, Video, Audio, Image, Gallery, and Link formats.
 *
 * @link http://codex.wordpress.org/Widgets_API#Developing_Widgets
 *
 * @package Atlastheme
 * @subpackage Atlassian
 * @since Atlassian 1.0
 */

class At_Ephemera_Widget extends WP_Widget {

	/**
	 * The supported post formats.
	 *
	 * @access private
	 * @since Atlassian  1.0
	 *
	 * @var array
	 */
	private $formats = array( 'aside', 'image', 'video', 'audio', 'quote', 'link', 'gallery' );

	/**
	 * Constructor.
	 *
	 * @since Atlassian 1.0
	 *
	 * @return At_Ephemera_Widget
	 */
	public function __construct() {
		parent::__construct( 'widget_atlassian_ephemera', __( 'Twenty Fourteen Ephemera', 'atlassian' ), array(
			'classname'   => 'widget_atlassian_ephemera',
			'description' => __( 'Use this widget to list your recent Aside, Quote, Video, Audio, Image, Gallery, and Link posts.', 'atlassian' ),
		) );
	}

	/**
	 * Output the HTML for this widget.
	 *
	 * @access public
	 * @since Atlassian  1.0
	 *
	 * @param array $args     An array of standard parameters for widgets in this theme.
	 * @param array $instance An array of settings for this widget instance.
	 */
	public function widget( $args, $instance ) {
		$format = isset( $instance['format'] ) && in_array( $instance['format'], $this->formats ) ? $instance['format'] : 'aside';

		switch ( $format ) {
			case 'image':
				$format_string      = __( 'Images', 'atlassian' );
				$format_string_more = __( 'More images', 'atlassian' );
				break;
			case 'video':
				$format_string      = __( 'Videos', 'atlassian' );
				$format_string_more = __( 'More videos', 'atlassian' );
				break;
			case 'audio':
				$format_string      = __( 'Audio', 'atlassian' );
				$format_string_more = __( 'More audio', 'atlassian' );
				break;
			case 'quote':
				$format_string      = __( 'Quotes', 'atlassian' );
				$format_string_more = __( 'More quotes', 'atlassian' );
				break;
			case 'link':
				$format_string      = __( 'Links', 'atlassian' );
				$format_string_more = __( 'More links', 'atlassian' );
				break;
			case 'gallery':
				$format_string      = __( 'Galleries', 'atlassian' );
				$format_string_more = __( 'More galleries', 'atlassian' );
				break;
			case 'aside':
			default:
				$format_string      = __( 'Asides', 'atlassian' );
				$format_string_more = __( 'More asides', 'atlassian' );
				break;
		}

		$slider_number = empty( $instance['slider_number'] ) ? 2 : absint( $instance['slider_number'] );
		$title  = apply_filters( 'widget_title', empty( $instance['title'] ) ? $format_string : $instance['title'], $instance, $this->id_base );

		$ephemera = new WP_Query( array(
			'order'          => 'DESC',
			'posts_per_page' => $slider_number,
			'no_found_rows'  => true,
			'post_status'    => 'publish',
			'post__not_in'   => get_option( 'sticky_posts' ),
			'tax_query'      => array(
				array(
					'taxonomy' => 'post_format',
					'terms'    => array( "post-format-$format" ),
					'field'    => 'slug',
					'operator' => 'IN',
				),
			),
		) );

		if ( $ephemera->have_posts() ) :
			$tmp_content_width = $GLOBALS['content_width'];
			$GLOBALS['content_width'] = 306;

			echo $args['before_widget'];
			?>
			<h1 class="widget-title <?php echo esc_attr( $format ); ?>">
				<a class="entry-format" href="<?php echo esc_url( get_post_format_link( $format ) ); ?>"><?php echo $title; ?></a>
			</h1>
			<ol>

				<?php
					while ( $ephemera->have_posts() ) :
						$ephemera->the_post();
						$tmp_more = $GLOBALS['more'];
						$GLOBALS['more'] = 0;
				?>
				<li>
				<article <?php post_class(); ?>>
					<div class="entry-content">
						<?php
							if ( has_post_format( 'gallery' ) ) :

								if ( post_password_required() ) :
									the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'atlassian' ) );
								else :
									$images = array();

									$galleries = get_post_galleries( get_the_ID(), false );
									if ( isset( $galleries[0]['ids'] ) )
										$images = explode( ',', $galleries[0]['ids'] );

									if ( ! $images ) :
										$images = get_posts( array(
											'fields'         => 'ids',
											'numberposts'    => -1,
											'order'          => 'ASC',
											'orderby'        => 'menu_order',
											'post_mime_type' => 'image',
											'post_parent'    => get_the_ID(),
											'post_type'      => 'attachment',
										) );
									endif;

									$total_images = count( $images );

									if ( has_post_thumbnail() ) :
										$post_thumbnail = get_the_post_thumbnail();
									elseif ( $total_images > 0 ) :
										$image          = array_shift( $images );
										$post_thumbnail = wp_get_attachment_image( $image, 'post-thumbnail' );
									endif;

									if ( ! empty ( $post_thumbnail ) ) :
						?>
						<a href="<?php the_permalink(); ?>"><?php echo $post_thumbnail; ?></a>
						<?php endif; ?>
						<p class="wp-caption-text">
							<?php
								printf( _n( 'This gallery contains <a href="%1$s" rel="bookmark">%2$s photo</a>.', 'This gallery contains <a href="%1$s" rel="bookmark">%2$s photos</a>.', $total_images, 'atlassian' ),
									esc_url( get_permalink() ),
									number_format_i18n( $total_images )
								);
							?>
						</p>
						<?php
								endif;

							else :
								the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'atlassian' ) );
							endif;
						?>
					</div><!-- .entry-content -->

					<header class="entry-header">
						<div class="entry-meta">
							<?php
								if ( ! has_post_format( 'link' ) ) :
									the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' );
								endif;

								printf( '<span class="entry-date"><a href="%1$s" rel="bookmark"><time class="entry-date" datetime="%2$s">%3$s</time></a></span> <span class="byline"><span class="author vcard"><a class="url fn n" href="%4$s" rel="author">%5$s</a></span></span>',
									esc_url( get_permalink() ),
									esc_attr( get_the_date( 'c' ) ),
									esc_html( get_the_date() ),
									esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
									get_the_author()
								);

								if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
							?>
							<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'atlassian' ), __( '1 Comment', 'atlassian' ), __( '% Comments', 'atlassian' ) ); ?></span>
							<?php endif; ?>
						</div><!-- .entry-meta -->
					</header><!-- .entry-header -->
				</article><!-- #post-## -->
				</li>
				<?php endwhile; ?>

			</ol>
			<a class="post-format-archive-link" href="<?php echo esc_url( get_post_format_link( $format ) ); ?>">
				<?php
					/* translators: used with More archives link */
					printf( __( '%s <span class="meta-nav">&rarr;</span>', 'atlassian' ), $format_string_more );
				?>
			</a>
			<?php

			echo $args['after_widget'];

			// Reset the post globals as this query will have stomped on it.
			wp_reset_postdata();

			$GLOBALS['more']          = $tmp_more;
			$GLOBALS['content_width'] = $tmp_content_width;

		endif; // End check for ephemeral posts.
	}

	/**
	 * Deal with the settings when they are saved by the admin.
	 *
	 * Here is where any validation should happen.
	 *
	 * @since Atlassian  1.0
	 *
	 * @param array $new_instance New widget instance.
	 * @param array $instance     Original widget instance.
	 * @return array Updated widget instance.
	 */
	function update( $new_instance, $instance ) {
		$instance['title']  = strip_tags( $new_instance['title'] );
		$instance['slider_number'] = empty( $new_instance['slider_number'] ) ? 2 : absint( $new_instance['slider_number'] );
		if ( in_array( $new_instance['format'], $this->formats ) ) {
			$instance['format'] = $new_instance['format'];
		}

		return $instance;
	}

	/**
	 * Display the form for this widget on the Widgets page of the Admin area.
	 *
	 * @since Atlassian  1.0
	 *
	 * @param array $instance
	 */
	function form( $instance ) {
		$title  = empty( $instance['title'] ) ? '' : esc_attr( $instance['title'] );
		$number = empty( $instance['number'] ) ? 2 : absint( $instance['number'] );
		$format = isset( $instance['format'] ) && in_array( $instance['format'], $this->formats ) ? $instance['format'] : 'aside';
		?>
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'atlassian' ); ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>"></p>

			<p><label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php _e( 'Number of posts to show:', 'atlassian' ); ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" size="3"></p>

			<p><label for="<?php echo esc_attr( $this->get_field_id( 'format' ) ); ?>"><?php _e( 'Post format to show:', 'atlassian' ); ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'format' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'format' ) ); ?>">
				<?php foreach ( $this->formats as $slug ) : ?>
				<option value="<?php echo esc_attr( $slug ); ?>"<?php selected( $format, $slug ); ?>><?php echo get_post_format_string( $slug ); ?></option>
				<?php endforeach; ?>
			</select>
		<?php
	}
}

class At_Upcoming_Cars_Widget extends WP_Widget {

    /**
     * The supported post formats.
     *
     * @access private
     * @since Atlassian  1.0
     *
     * @var array
     */
    private $formats = array( 'aside', 'image', 'video', 'audio', 'quote', 'link', 'gallery' );

    /**
     * Constructor.
     *
     * @since Atlassian 1.0
     *
     * @return At_Ephemera_Widget
     */
    public function __construct() {
        parent::__construct( 'widget_at_upcoming_cars', __( 'Upcoming Cars', 'atlassian' ), array(
            'classname'   => 'widget_at_upcoming_cars',
            'description' => __( 'Show all upcoming cars with carousal effect', 'atlassian' ),
        ) );
    }

    /**
     * Output the HTML for this widget.
     *
     * @access public
     * @since Atlassian  1.0
     *
     * @param array $args     An array of standard parameters for widgets in this theme.
     * @param array $instance An array of settings for this widget instance.
     */
    public function widget( $args, $instance ) {
        $number = empty( $instance['number'] ) ? 2 : absint( $instance['number'] );
        $title  = apply_filters( 'widget_title', empty( $instance['title'] ) ? $format_string : $instance['title'], $instance, $this->id_base );
        $args = array(
            'posts_per_page'   => $number,
            'offset'           => 0,
            'category'         => '',
            'category_name'    => '',
            'orderby'          => 'post_date',
            'order'            => 'DESC',
            'meta_key'         => 'vs_stock_availability',
            'meta_value'       => 'upcoming',
            'post_type'        => 'post',
            'post_status'      => 'publish',
            'suppress_filters' => true );

        $upcoming_cars = new WP_Query( $args );

        if ( $upcoming_cars->have_posts() ) :
            ?>
            <!-- SECTION -->
            <div class="section-3" >
                <div class="container">
                    <div class="row" >
                        <section class="carousel carousel-reviews">
                            <div class="carousel-title ">
                                <div class="transform-please-2 upcoming-cars"> <span> <?php echo $title; ?> </span> </div>
                            </div>
                            <ul class="carousel-1">
                                <?php
                                while ( $upcoming_cars->have_posts() ) :
                                    $upcoming_cars->the_post();?>
                                    <li>
                                        <div class="media"> <a href="<?php the_permalink();?>"><?php the_post_thumbnail('upcoming-thumb');?></a>
                                            <div class="carousel-item-content">
                                                <div class="text-right"><a class="arrow-link" href="<?php the_permalink();?>"> <span class="icon-transform transform-please-2"><i class="fa fa-angle-right"></i></span></a></div>
                                                <a href="<?php the_permalink();?>" class="transform-please-2 carousel-title"><span><?php the_title('','',true);?></span> </a> </div>
                                        </div>
                                        <div class="carousel-text">
                                            <p> <?php the_excerpt();?></p>
                                        </div>
                                        <div class="box-more-info">
                                            <div class="transform-revers"> <a href="<?php the_permalink(); ?>">READMORE</a></div>
                                        </div>
                                    </li>
                                <?php endwhile; ?>
                            </ul>
                        </section>
                    </div>
                </div>
            </div>
            <!--END-->

            <?php
            // Reset the post globals as this query will have stomped on it.
            wp_reset_postdata();

        endif; // End check for ephemeral posts.
    }

    /**
     * Deal with the settings when they are saved by the admin.
     *
     * Here is where any validation should happen.
     *
     * @since Atlassian  1.0
     *
     * @param array $new_instance New widget instance.
     * @param array $instance     Original widget instance.
     * @return array Updated widget instance.
     */
    function update( $new_instance, $instance ) {
        $instance['title']  = strip_tags( $new_instance['title'] );
        $instance['number'] = empty( $new_instance['number'] ) ? 2 : absint( $new_instance['number'] );
        return $instance;
    }

    /**
     * Display the form for this widget on the Widgets page of the Admin area.
     *
     * @since Atlassian  1.0
     *
     * @param array $instance
     */
    function form( $instance ) {
        $title  = empty( $instance['title'] ) ? '' : esc_attr( $instance['title'] );
        $number = empty( $instance['number'] ) ? 2 : absint( $instance['number'] );
        ?>
        <p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'atlassian' ); ?></label>
            <input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>"></p>

        <p><label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php _e( 'Number of posts to show:', 'atlassian' ); ?></label>
            <input id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" size="3"></p>
    <?php
    }
}

class At_Available_Cars_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct( 'widget_at_available_cars', __( 'Available Cars', 'atlassian' ), array(
            'classname'   => 'widget_at_available_cars',
            'description' => __( 'Show all available cars with carousal effect', 'atlassian' ),
        ) );
    }

    /**
     * Output the HTML for this widget.
     *
     * @access public
     * @since Atlassian  1.0
     *
     * @param array $args     An array of standard parameters for widgets in this theme.
     * @param array $instance An array of settings for this widget instance.
     */
    public function widget( $args, $instance ) {
        $number = empty( $instance['number'] ) ? 2 : absint( $instance['number'] );
        $title  = apply_filters( 'widget_title', empty( $instance['title'] ) ? $format_string : $instance['title'], $instance, $this->id_base );
        $args = array(
            'posts_per_page'   => $number,
            'offset'           => 0,
            'category'         => '',
            'category_name'    => '',
            'orderby'          => 'post_date',
            'order'            => 'DESC',
            'meta_key'         => 'vs_stock_availability',
            'meta_value'       => 'available',
            'post_type'        => 'post',
            'post_status'      => 'publish',
            'suppress_filters' => true );

        $available_cars = new WP_Query( $args );

        if ( $available_cars->have_posts() ) :
            ?>
            <!-- SECTION -->
            <div class="section-7">
                <div class="container">
                    <div class="row animated " data-animation="bounceInLeft">
                        <section class="carousel-3">
                            <div  class="carousel-title ">
                                <div class="transform-please-2 "> <span> <?php echo $title; ?></span> </div>
                            </div >
                            <ul>
            <?php
            while ( $available_cars->have_posts() ) :
                $available_cars->the_post();?>
                                <li>
                                    <div class="media"><a  href="<?php the_permalink();?>"><?php the_post_thumbnail('available-thumb');?></a></div>
                                    <h3><a href="<?php the_permalink();?>"> <?php the_title('','',true);?></a></h3>
                                    <p><?php the_excerpt();?></p>
                                    <div class="box-more-info">
                                        <div class="transform-revers"> <a href="<?php the_permalink();?>">READMORE</a></div>
                                    </div>
                                </li>
            <?php endwhile; ?>
                            </ul>
                        </section>
                    </div>
                </div>
            </div>
            <!-- END -->
            <?php
            // Reset the post globals as this query will have stomped on it.
            wp_reset_postdata();

        endif; // End check for ephemeral posts.
    }

    /**
     * Deal with the settings when they are saved by the admin.
     *
     * Here is where any validation should happen.
     *
     * @since Atlassian  1.0
     *
     * @param array $new_instance New widget instance.
     * @param array $instance     Original widget instance.
     * @return array Updated widget instance.
     */
    function update( $new_instance, $instance ) {
        $instance['title']  = strip_tags( $new_instance['title'] );
        $instance['number'] = empty( $new_instance['number'] ) ? 2 : absint( $new_instance['number'] );
        return $instance;
    }

    /**
     * Display the form for this widget on the Widgets page of the Admin area.
     *
     * @since Atlassian  1.0
     *
     * @param array $instance
     */
    function form( $instance ) {
        $title  = empty( $instance['title'] ) ? '' : esc_attr( $instance['title'] );
        $number = empty( $instance['number'] ) ? 2 : absint( $instance['number'] );
        ?>
        <p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'atlassian' ); ?></label>
            <input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>"></p>

        <p><label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php _e( 'Number of posts to show:', 'atlassian' ); ?></label>
            <input id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" size="3"></p>
    <?php
    }
}

/*Category widget with nice counter*/
class AT_Walker_Category extends Walker_Category
{
    public function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
        /** This filter is documented in wp-includes/category-template.php */
        $cat_name = apply_filters(
            'list_cats',
            esc_attr( $category->name ),
            $category
        );

        $link = '<a href="' . esc_url( get_term_link( $category ) ) . '" ';
        if ( $args['use_desc_for_title'] && ! empty( $category->description ) ) {
            /**
             * Filter the category description for display.
             *
             * @since 1.2.0
             *
             * @param string $description Category description.
             * @param object $category    Category object.
             */
            $link .= 'title="' . esc_attr( strip_tags( apply_filters( 'category_description', $category->description, $category ) ) ) . '"';
        }

        $link .= '>';
        $link .= $cat_name . '</a>';

        if ( ! empty( $args['feed_image'] ) || ! empty( $args['feed'] ) ) {
            $link .= ' ';

            if ( empty( $args['feed_image'] ) ) {
                $link .= '(';
            }

            $link .= '<a href="' . esc_url( get_term_feed_link( $category->term_id, $category->taxonomy, $args['feed_type'] ) ) . '"';

            if ( empty( $args['feed'] ) ) {
                $alt = ' alt="' . sprintf(__( 'Feed for all posts filed under %s' ), $cat_name ) . '"';
            } else {
                $alt = ' alt="' . $args['feed'] . '"';
                $name = $args['feed'];
                $link .= empty( $args['title'] ) ? '' : $args['title'];
            }

            $link .= '>';

            if ( empty( $args['feed_image'] ) ) {
                $link .= $name;
            } else {
                $link .= "<img src='" . $args['feed_image'] . "'$alt" . ' />';
            }
            $link .= '</a>';

            if ( empty( $args['feed_image'] ) ) {
                $link .= ')';
            }
        }

        if ( ! empty( $args['show_count'] ) ) {
            $link .= ' <span class="posts-count" >' . number_format_i18n( $category->count ) . '</span>';
        }
        if ( 'list' == $args['style'] ) {
            $output .= "\t<li";
            $class = 'cat-item cat-item-' . $category->term_id;
            if ( ! empty( $args['current_category'] ) ) {
                $_current_category = get_term( $args['current_category'], $category->taxonomy );
                if ( $category->term_id == $args['current_category'] ) {
                    $class .=  ' current-cat';
                } elseif ( $category->term_id == $_current_category->parent ) {
                    $class .=  ' current-cat-parent';
                }
            }
            $output .=  ' class="' . $class . '"';
            $output .= ">$link\n";
        } else {
            $output .= "\t$link<br />\n";
        }
    }
}
class At_Widget_Categories extends WP_Widget
{

    public function __construct()
    {
        $widget_ops = array('classname' => 'at_widget_categories', 'description' => __("A list or dropdown of categories."));
        parent::__construct('categories', __('Atlassian Categories'), $widget_ops);
    }

    public function widget($args, $instance)
    {

        /** This filter is documented in wp-includes/default-widgets.php */
        $title = apply_filters('widget_title', empty($instance['title']) ? __('Categories') : $instance['title'], $instance, $this->id_base);

        $c = !empty($instance['count']) ? '1' : '0';
        $h = !empty($instance['hierarchical']) ? '1' : '0';
        $d = !empty($instance['dropdown']) ? '1' : '0';

        echo $args['before_widget'];
        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        $cat_args = array('orderby' => 'name', 'show_count' => $c, 'hierarchical' => $h);

        if ($d) {
            $cat_args['show_option_none'] = __('Select Category');

            /**
             * Filter the arguments for the Categories widget drop-down.
             *
             * @since 2.8.0
             *
             * @see wp_dropdown_categories()
             *
             * @param array $cat_args An array of Categories widget drop-down arguments.
             */
            wp_dropdown_categories(apply_filters('widget_categories_dropdown_args', $cat_args));
            ?>

            <script type='text/javascript'>
                /* <![CDATA[ */
                var dropdown = document.getElementById("cat");
                function onCatChange() {
                    if (dropdown.options[dropdown.selectedIndex].value > 0) {
                        location.href = "<?php echo home_url(); ?>/?cat=" + dropdown.options[dropdown.selectedIndex].value;
                    }
                }
                dropdown.onchange = onCatChange;
                /* ]]> */
            </script>

        <?php
        } else {
            ?>
            <ul class="category-list unstyled clearfix">
                <?php
                $cat_args['title_li'] = '';

                /**
                 * Filter the arguments for the Categories widget.
                 *
                 * @since 2.8.0
                 *
                 * @param array $cat_args An array of Categories widget options.
                 */
                $this->wp_list_categories(apply_filters('widget_categories_args', $cat_args));
                ?>
            </ul>
        <?php
        }

        echo $args['after_widget'];
    }

    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['count'] = !empty($new_instance['count']) ? 1 : 0;
        $instance['hierarchical'] = !empty($new_instance['hierarchical']) ? 1 : 0;
        $instance['dropdown'] = !empty($new_instance['dropdown']) ? 1 : 0;

        return $instance;
    }

    public function form($instance)
    {
        //Defaults
        $instance = wp_parse_args((array)$instance, array('title' => ''));
        $title = esc_attr($instance['title']);
        $count = isset($instance['count']) ? (bool)$instance['count'] : false;
        $hierarchical = isset($instance['hierarchical']) ? (bool)$instance['hierarchical'] : false;
        $dropdown = isset($instance['dropdown']) ? (bool)$instance['dropdown'] : false;
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>"/></p>

        <p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('dropdown'); ?>"
                  name="<?php echo $this->get_field_name('dropdown'); ?>"<?php checked($dropdown); ?> />
            <label for="<?php echo $this->get_field_id('dropdown'); ?>"><?php _e('Display as dropdown'); ?></label><br/>

            <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>"
                   name="<?php echo $this->get_field_name('count'); ?>"<?php checked($count); ?> />
            <label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Show post counts'); ?></label><br/>

            <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('hierarchical'); ?>"
                   name="<?php echo $this->get_field_name('hierarchical'); ?>"<?php checked($hierarchical); ?> />
            <label for="<?php echo $this->get_field_id('hierarchical'); ?>"><?php _e('Show hierarchy'); ?></label></p>
    <?php
    }

    private function wp_list_categories($args = '')
    {
        $defaults = array(
            'show_option_all' => '', 'show_option_none' => __('No categories'),
            'orderby' => 'name', 'order' => 'ASC',
            'style' => 'list',
            'show_count' => 0, 'hide_empty' => 1,
            'use_desc_for_title' => 1, 'child_of' => 0,
            'feed' => '', 'feed_type' => '',
            'feed_image' => '', 'exclude' => '',
            'exclude_tree' => '', 'current_category' => 0,
            'hierarchical' => true, 'title_li' => __('Categories'),
            'echo' => 1, 'depth' => 0,
            'taxonomy' => 'category'
        );

        $r = wp_parse_args($args, $defaults);

        if (!isset($r['pad_counts']) && $r['show_count'] && $r['hierarchical'])
            $r['pad_counts'] = true;

        if (true == $r['hierarchical']) {
            $r['exclude_tree'] = $r['exclude'];
            $r['exclude'] = '';
        }

        if (!isset($r['class']))
            $r['class'] = ('category' == $r['taxonomy']) ? 'categories' : $r['taxonomy'];

        if (!taxonomy_exists($r['taxonomy'])) {
            return false;
        }

        $show_option_all = $r['show_option_all'];
        $show_option_none = $r['show_option_none'];

        $categories = get_categories($r);

        $output = '';
        if ($r['title_li'] && 'list' == $r['style']) {
            $output = '<li class="' . esc_attr($r['class']) . '">' . $r['title_li'] . '<ul>';
        }
        if (empty($categories)) {
            if (!empty($show_option_none)) {
                if ('list' == $r['style']) {
                    $output .= '<li class="cat-item-none">' . $show_option_none . '</li>';
                } else {
                    $output .= $show_option_none;
                }
            }
        } else {
            if (!empty($show_option_all)) {
                $posts_page = ('page' == get_option('show_on_front') && get_option('page_for_posts')) ? get_permalink(get_option('page_for_posts')) : home_url('/');
                $posts_page = esc_url($posts_page);
                if ('list' == $r['style']) {
                    $output .= "<li class='cat-item-all'><a href='$posts_page'>$show_option_all</a></li>";
                } else {
                    $output .= "<a href='$posts_page'>$show_option_all</a>";
                }
            }

            if (empty($r['current_category']) && (is_category() || is_tax() || is_tag())) {
                $current_term_object = get_queried_object();
                if ($current_term_object && $r['taxonomy'] === $current_term_object->taxonomy) {
                    $r['current_category'] = get_queried_object_id();
                }
            }

            if ($r['hierarchical']) {
                $depth = $r['depth'];
            } else {
                $depth = -1; // Flat.
            }
            $output .= $this->walk_category_tree($categories, $depth, $r);
        }

        if ($r['title_li'] && 'list' == $r['style'])
            $output .= '</ul></li>';

        /**
         * Filter the HTML output of a taxonomy list.
         *
         * @since 2.1.0
         *
         * @param string $output HTML output.
         * @param array $args An array of taxonomy-listing arguments.
         */
        $html = apply_filters('wp_list_categories', $output, $args);

        if ($r['echo']) {
            echo $html;
        } else {
            return $html;
        }
    }

    function walk_category_tree() {
        $args = func_get_args();
        // the user's options are the third parameter
        if ( empty($args[2]['walker']) || !is_a($args[2]['walker'], 'Walker') )
            $walker = new AT_Walker_Category;
        else
            $walker = $args[2]['walker'];

        return call_user_func_array(array( &$walker, 'walk' ), $args );
    }

}

/*Tag cloud widget*/
class At_WP_Widget_Tag_Cloud extends WP_Widget {

    public function __construct() {
        $widget_ops = array( 'description' => __( "A cloud of your most used tags.") );
        parent::__construct('at_tag_cloud', __('Atlassian Tag Cloud'), $widget_ops);
    }

    public function widget( $args, $instance ) {
        $current_taxonomy = $this->_get_current_taxonomy($instance);
        if ( !empty($instance['title']) ) {
            $title = $instance['title'];
        } else {
            if ( 'post_tag' == $current_taxonomy ) {
                $title = __('Tags');
            } else {
                $tax = get_taxonomy($current_taxonomy);
                $title = $tax->labels->name;
            }
        }

        $format='flat';
        if(isset($instance['format']))
            $format=$instance['format'];
        /** This filter is documented in wp-includes/default-widgets.php */
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

        echo $args['before_widget'];
        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        echo '<div class="tag-cloud">';

        /**
         * Filter the taxonomy used in the Tag Cloud widget.
         *
         * @since 2.8.0
         * @since 3.0.0 Added taxonomy drop-down.
         *
         * @see wp_tag_cloud()
         *
         * @param array $current_taxonomy The taxonomy to use in the tag cloud. Default 'tags'.
         */
        wp_tag_cloud( apply_filters( 'widget_tag_cloud_args', array(
            'taxonomy' => $current_taxonomy,'format'=>$format
        ) ) );

        echo "</div>\n";
        echo $args['after_widget'];
    }

    public function update( $new_instance, $old_instance ) {
        $instance['title'] = strip_tags(stripslashes($new_instance['title']));
        $instance['taxonomy'] = stripslashes($new_instance['taxonomy']);
        $instance['format'] = stripslashes($new_instance['format']);
        return $instance;
    }

    public function form( $instance ) {
        $current_taxonomy = $this->_get_current_taxonomy($instance);
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php if (isset ( $instance['title'])) {echo esc_attr( $instance['title'] );} ?>" /></p>
        <p><label for="<?php echo $this->get_field_id('taxonomy'); ?>"><?php _e('Taxonomy:') ?></label>
        <select class="widefat" id="<?php echo $this->get_field_id('taxonomy'); ?>" name="<?php echo $this->get_field_name('taxonomy'); ?>">
            <?php foreach ( get_taxonomies() as $taxonomy ) :
                $tax = get_taxonomy($taxonomy);
                if ( !$tax->show_tagcloud || empty($tax->labels->name) )
                    continue;
                ?>
                <option value="<?php echo esc_attr($taxonomy) ?>" <?php selected($taxonomy, $current_taxonomy) ?>><?php echo $tax->labels->name; ?></option>
            <?php endforeach; ?>
        </select></p>
        <p><label for="<?php echo $this->get_field_id('format'); ?>"><?php _e('Format:') ?></label>
        <select class="widefat" id="<?php echo $this->get_field_id('format'); ?>" name="<?php echo $this->get_field_name('format'); ?>">
            <?php
            if(isset($instance['format']))
                $current_format =$instance['format'];
            else
                $current_format ='flat';
                ?>
                <option value="flat" <?php selected('flat', $current_format) ?>>Flat</option>
                <option value="list" <?php selected('list', $current_format) ?>>List</option>
        </select></p><?php
    }

    public function _get_current_taxonomy($instance) {
        if ( !empty($instance['taxonomy']) && taxonomy_exists($instance['taxonomy']) )
            return $instance['taxonomy'];

        return 'post_tag';
    }
}

/*Showing recent, popular post and comments*/
class At_Post_Comment_Tab extends WP_Widget{
    public function __construct() {
        $widget_ops = array( 'description' => __( "Show recent, popular posts and comment with tab.") );
        parent::__construct('at_post_comment_tab', __('Atlassian Post Comment Tab'), $widget_ops);
    }

    public function widget( $args, $instance ) {
        $current_taxonomy = $this->_get_current_taxonomy($instance);
        if ( !empty($instance['title']) ) {
            $title = $instance['title'];
        } else {
            if ( 'post_tag' == $current_taxonomy ) {
                $title = __('Tags');
            } else {
                $tax = get_taxonomy($current_taxonomy);
                $title = $tax->labels->name;
            }
        }

        $format='flat';
        if(isset($instance['format']))
            $format=$instance['format'];
        /** This filter is documented in wp-includes/default-widgets.php */
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

        echo $args['before_widget'];
        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        echo '<div class="widget widget-tabbed">';
?>
        <!-- TABBED CONTENT WIDGET -->

        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-popular" data-toggle="tab">popular</a></li>
            <li><a href="#tab-recent" data-toggle="tab">recent</a></li>
            <li><a href="#tab-comments" data-toggle="tab">comments</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab-popular">
                <ul class="entry-list unstyled">
                    <li>
                        <div class="entry-thumbnail"> <a href="#" class="img"> <img src="media/small/1.jpg" width="70" height="70" alt=""/></a> </div>
                        <div class="entry-main">
                            <div class="entry-meta">
                                <time class="entry-datetime" datetime="2013-10-27" title="2013-10-27"> <a href="#"><span aria-hidden="true" class="icon-clock"></span> 17 March , 2013</a> </time>
                            </div>
                            <div class="entry-header">
                                <h5 class="entry-title"><a href="#">Standard Post with Blog [...]</a></h5>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </li>
                    <li>
                        <div class="entry-thumbnail"> <a href="#" class="img"> <img src="media/small/2.jpg" width="70" height="70" alt=""/></a> </div>
                        <div class="entry-main">
                            <div class="entry-meta">
                                <time class="entry-datetime" datetime="2013-10-27" title="2013-10-27"> <a href="#"><span aria-hidden="true" class="icon-clock"></span> 16 March , 2013</a> </time>
                            </div>
                            <div class="entry-header">
                                <h5 class="entry-title"><a href="#">Standard Post with Blog [...]</a></h5>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </li>
                    <li>
                        <div class="entry-thumbnail"> <a href="#" class="img"> <img src="media/small/3.jpg" width="70" height="70" alt=""/> </a> </div>
                        <div class="entry-main">
                            <div class="entry-meta">
                                <time class="entry-datetime" datetime="2013-10-27" title="2013-10-27"> <a href="#"><span aria-hidden="true" class="icon-clock"></span> 15 March , 2014</a> </time>
                            </div>
                            <div class="entry-header">
                                <h5 class="entry-title"><a href="#">Standard Post with Blog [...]</a></h5>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </li>
                    <li>
                        <div class="entry-thumbnail"> <a href="#" class="img"> <img src="media/small/4.jpg" width="70" height="70" alt=""/></a> </div>
                        <div class="entry-main">
                            <div class="entry-meta">
                                <time class="entry-datetime" datetime="2013-10-27" title="2013-10-27"> <a href="#"><span aria-hidden="true" class="icon-clock"></span> 14 March , 2013</a> </time>
                            </div>
                            <div class="entry-header">
                                <h5 class="entry-title"><a href="#">Standard Post with Blog [...]</a></h5>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </li>
                </ul>
            </div>
            <div class="tab-pane" id="tab-recent">
                <ul class="entry-list unstyled">
                    <li>
                        <div class="entry-thumbnail"> <a href="#" class="img"> <img src="media/small/1.jpg" width="70" height="70" alt=""/> </a> </div>
                        <div class="entry-main">
                            <div class="entry-header">
                                <h5 class="entry-title"><a href="#">Standard Post with Blog [...]</a></h5>
                            </div>
                            <div class="entry-meta">
                                <time class="entry-datetime" datetime="2013-10-27" title="2013-10-27"> <a href="#"><span aria-hidden="true" class="icon-clock"></span> 12 March , 2013</a> </time>
                                <div class="entry-comments"> <a href="#"> <span aria-hidden="true" class="icon-bubbles"></span> 47 </a> </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </li>
                    <li>
                        <div class="entry-thumbnail"> <a href="#" class="img"> <img src="media/small/2.jpg" width="70" height="70" alt=""/> </a> </div>
                        <div class="entry-main">
                            <div class="entry-header">
                                <h5 class="entry-title"><a href="#">Standard Post with Blog [...]</a></h5>
                            </div>
                            <div class="entry-meta">
                                <time class="entry-datetime" datetime="2013-10-27" title="2013-10-27"> <a href="#"><span aria-hidden="true" class="icon-clock"></span> 11 March , 2013</a> </time>
                                <div class="entry-comments"> <a href="#"> <span aria-hidden="true" class="icon-bubbles"></span> 47 </a> </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </li>
                    <li>
                        <div class="entry-thumbnail"> <a href="#" class="img"> <img src="media/small/3.jpg" width="70" height="70" alt=""/> </a> </div>
                        <div class="entry-main">
                            <div class="entry-header">
                                <h5 class="entry-title"><a href="#">Standard Post with Blog [...]</a></h5>
                            </div>
                            <div class="entry-meta">
                                <time class="entry-datetime" datetime="2013-10-27" title="2013-10-27"> <a href="#"><span aria-hidden="true" class="icon-clock"></span> 10 March , 2013</a> </time>
                                <div class="entry-comments"> <a href="#"> <span aria-hidden="true" class="icon-bubbles"></span> 47 </a> </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </li>
                    <li>
                        <div class="entry-thumbnail"> <a href="#" class="img"> <img src="media/small/4.jpg" width="70" height="70" alt=""/> </a> </div>
                        <div class="entry-main">
                            <div class="entry-header">
                                <h5 class="entry-title"><a href="#">Standard Post with Blog [...]</a></h5>
                            </div>
                            <div class="entry-meta">
                                <time class="entry-datetime" datetime="2013-10-27" title="2013-10-27"> <a href="#"><span aria-hidden="true" class="icon-clock"></span> 7 March , 2013</a> </time>
                                <div class="entry-comments"> <a href="#"> <span aria-hidden="true" class="icon-bubbles"></span> 47 </a> </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </li>
                </ul>
            </div>
            <div class="tab-pane" id="tab-comments">
                <ul class="entry-list unstyled">
                    <li>
                        <div class="entry-thumbnail"> <a href="#" class="img"><img src="media/small/1.jpg" width="70" height="70" alt=""/> </a> </div>
                        <div class="entry-main">
                            <div class="entry-header">
                                <h5 class="entry-title"><a href="#">John Doe </a>Says:</h5>
                            </div>
                            <div class="entry-content">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam gravida nibh sed faucibus mattis <a href="#" class="readmore">[...]</a></p>
                            </div>
                            <div class="entry-meta">
                                <time class="entry-datetime" datetime="2013-10-27" title="2013-10-27"> <a href="#"><span aria-hidden="true" class="icon-clock"></span> 17 March , 2013</a> </time>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </li>
                    <li>
                        <div class="entry-thumbnail"> <a href="#" class="img"> <img src="media/small/2.jpg" width="70" height="70" alt=""/> </a> </div>
                        <div class="entry-main">
                            <div class="entry-header">
                                <h5 class="entry-title"><a href="#">John Doe </a>Says:</h5>
                            </div>
                            <div class="entry-content">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam gravida nibh sed faucibus mattis <a href="#" class="readmore">[...]</a></p>
                            </div>
                            <div class="entry-meta">
                                <time class="entry-datetime" datetime="2013-10-27" title="2013-10-27"> <a href="#"><span aria-hidden="true" class="icon-clock"></span> 17 March , 2013</a> </time>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </li>
                    <li>
                        <div class="entry-thumbnail"> <a href="#" class="img"><img src="media/small/3.jpg" width="70" height="70" alt=""/> </a> </div>
                        <div class="entry-main">
                            <div class="entry-header">
                                <h5 class="entry-title"><a href="#">John Doe </a>Says:</h5>
                            </div>
                            <div class="entry-content">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam gravida nibh sed faucibus mattis <a href="#" class="readmore">[...]</a></p>
                            </div>
                            <div class="entry-meta">
                                <time class="entry-datetime" datetime="2013-10-27" title="2013-10-27"> <a href="#"><span aria-hidden="true" class="icon-clock"></span> 17 March , 2013</a> </time>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </li>
                </ul>
            </div>
        </div>

<!-- // TABBED CONTENT WIDGET -->
        <?php
        echo "</div>\n";
        echo $args['after_widget'];
    }

    public function update( $new_instance, $old_instance ) {
        $instance['title'] = strip_tags(stripslashes($new_instance['title']));
        $instance['taxonomy'] = stripslashes($new_instance['taxonomy']);
        $instance['format'] = stripslashes($new_instance['format']);
        return $instance;
    }

    public function form( $instance ) {
        $current_taxonomy = $this->_get_current_taxonomy($instance);
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php if (isset ( $instance['title'])) {echo esc_attr( $instance['title'] );} ?>" /></p>
        <p><label for="<?php echo $this->get_field_id('taxonomy'); ?>"><?php _e('Taxonomy:') ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('taxonomy'); ?>" name="<?php echo $this->get_field_name('taxonomy'); ?>">
                <?php foreach ( get_taxonomies() as $taxonomy ) :
                    $tax = get_taxonomy($taxonomy);
                    if ( !$tax->show_tagcloud || empty($tax->labels->name) )
                        continue;
                    ?>
                    <option value="<?php echo esc_attr($taxonomy) ?>" <?php selected($taxonomy, $current_taxonomy) ?>><?php echo $tax->labels->name; ?></option>
                <?php endforeach; ?>
            </select></p>
        <p><label for="<?php echo $this->get_field_id('format'); ?>"><?php _e('Format:') ?></label>
        <select class="widefat" id="<?php echo $this->get_field_id('format'); ?>" name="<?php echo $this->get_field_name('format'); ?>">
            <?php
            if(isset($instance['format']))
                $current_format =$instance['format'];
            else
                $current_format ='flat';
            ?>
            <option value="flat" <?php selected('flat', $current_format) ?>>Flat</option>
            <option value="list" <?php selected('list', $current_format) ?>>List</option>
        </select></p><?php
    }
}

/*modified recent post widget*/
class At_Widget_Recent_Posts extends WP_Widget {

    public function __construct() {
        $widget_ops = array('classname' => 'at_widget_recent_entries', 'description' => __( "Your site&#8217;s most recent Posts.") );
        parent::__construct('at-recent-posts', __('Atlassian Recent Posts'), $widget_ops);
        $this->alt_option_name = 'at_widget_recent_entries';

        add_action( 'save_post', array($this, 'flush_widget_cache') );
        add_action( 'deleted_post', array($this, 'flush_widget_cache') );
        add_action( 'switch_theme', array($this, 'flush_widget_cache') );
    }

    public function widget($args, $instance) {
        $cache = array();
        if ( ! $this->is_preview() ) {
            $cache = wp_cache_get( 'at_widget_recent_posts', 'widget' );
        }

        if ( ! is_array( $cache ) ) {
            $cache = array();
        }

        if ( ! isset( $args['widget_id'] ) ) {
            $args['widget_id'] = $this->id;
        }

        if ( isset( $cache[ $args['widget_id'] ] ) ) {
            echo $cache[ $args['widget_id'] ];
            return;
        }

        ob_start();

        $title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Posts' );

        /** This filter is documented in wp-includes/default-widgets.php */
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

        $slider_number = ( ! empty( $instance['slider_number'] ) ) ? absint( $instance['slider_number'] ) : 5;
        $thumbnail_number = ( ! empty( $instance['thumbnail_number'] ) ) ? absint( $instance['thumbnail_number'] ) : 5;
        if ( ! $slider_number )
            $slider_number = 3;
        if ( ! $thumbnail_number )
            $thumbnail_number = 4;
        $show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;

        /**
         * Filter the arguments for the Recent Posts widget.
         *
         * @since 3.4.0
         *
         * @see WP_Query::get_posts()
         *
         * @param array $args An array of arguments used to retrieve the recent posts.
         */
        $r = new WP_Query( apply_filters( 'widget_posts_args', array(
            'posts_per_page'      => $slider_number+$thumbnail_number,
            'no_found_rows'       => true,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true
        ) ) );

        $featured_post=$slider_number;
        if ($r->have_posts()) :
            ?>
            <?php echo $args['before_widget']; ?>
            <div class="widget widget-latest-post">
            <?php if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        } ?>
                <ul class="carousel1">
                <?php while ( $r->have_posts() && $featured_post ) : $r->the_post(); ?>
                    <li>
                        <div class="media"> <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('home-featured-thumb') ?> </a>
                            <div class="media-desc">
                                <h5 class="entry-title"> <?php get_the_title() ? the_title() : the_ID(); ?></h5>
                                <?php if ( $show_date ) : ?>
                                <time class="entry-datetime" datetime="<?php echo get_the_date(); ?>" title="<?php echo get_the_date(); ?>"> <a href="#"> <i class="fa fa-clock-o"></i> <?php echo get_the_date(); ?></a> </time>
                                <?php endif; ?>
                            </div>
                        </div>
                    </li>
                <?php $featured_post--; ?>
                <?php endwhile; ?>
                </ul>
                <div id="tab-recent2" class="tab-pane active">
                    <ul class="entry-list unstyled">
                    <?php while ( $r->have_posts()) : $r->the_post(); ?>
                        <li>
                            <div class="entry-thumbnail"> <a class="img" href="<?php the_permalink(); ?>"> <?php the_post_thumbnail(array(70,70)) ?> </a> </div>
                            <div class="entry-main">
                                <div class="entry-header">
                                    <h5 class="entry-title"><a href="#"><?php echo substr( get_the_title() ?  the_title('','',false) : the_ID(),0,20); ?></a></h5>
                                </div>
                                <div class="entry-meta">
                                    <?php if ( $show_date ) : ?>
                                    <time title="<?php echo get_the_date(); ?>" datetime="<?php echo get_the_date(); ?>" class="entry-datetime"> <a href="<?php the_permalink(); ?>"> <i class="fa fa-clock-o"></i> <?php echo get_the_date(); ?></a> </time>
                                    <?php endif; ?>
                                    <div class="entry-comments"> <a href="#"> <i class="fa fa-comment-o"></i> 47 </a> </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </li>
                    <?php endwhile; ?>
                    </ul>
                </div>
            </div>
            <?php echo $args['after_widget']; ?>
            <?php
            // Reset the global $the_post as this query will have stomped on it
            wp_reset_postdata();

        endif;

        if ( ! $this->is_preview() ) {
            $cache[ $args['widget_id'] ] = ob_get_flush();
            wp_cache_set( 'at_widget_recent_posts', $cache, 'widget' );
        } else {
            ob_end_flush();
        }
    }


    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['slider_number'] = (int) $new_instance['slider_number'];
        $instance['thumbnail_number'] = (int) $new_instance['thumbnail_number'];
        $instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
        $this->flush_widget_cache();

        $alloptions = wp_cache_get( 'alloptions', 'options' );
        if ( isset($alloptions['widget_recent_entries']) )
            delete_option('widget_recent_entries');

        return $instance;
    }

    public function flush_widget_cache() {
        wp_cache_delete('at_widget_recent_posts', 'widget');
    }

    public function form( $instance ) {
        $title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $slider_number    = isset( $instance['slider_number'] ) ? absint( $instance['slider_number'] ) : 5;
        $thumbnail_number    = isset( $instance['thumbnail_number'] ) ? absint( $instance['thumbnail_number'] ) : 5;
        $show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
        ?>
        <p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

        <p><label for="<?php echo $this->get_field_id( 'slider_number' ); ?>"><?php _e( 'Slider posts to show:' ); ?></label>
            <input id="<?php echo $this->get_field_id( 'slider_number' ); ?>" name="<?php echo $this->get_field_name( 'slider_number' ); ?>" type="text" value="<?php echo $slider_number; ?>" size="3" /></p>

        <p><label for="<?php echo $this->get_field_id( 'thumbnail_number' ); ?>"><?php _e( 'Thumbnail posts to show:' ); ?></label>
            <input id="<?php echo $this->get_field_id( 'thumbnail_number' ); ?>" name="<?php echo $this->get_field_name( 'thumbnail_number' ); ?>" type="text" value="<?php echo $thumbnail_number; ?>" size="3" /></p>

        <p><input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Display post date?' ); ?></label></p>
    <?php
    }
}
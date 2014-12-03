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

class Atlassian_Ephemera_Widget extends WP_Widget {

	/**
	 * The supported post formats.
	 *
	 * @access private
	 * @since Twenty Fourteen 1.0
	 *
	 * @var array
	 */
	private $formats = array( 'aside', 'image', 'video', 'audio', 'quote', 'link', 'gallery' );

	/**
	 * Constructor.
	 *
	 * @since Twenty Fourteen 1.0
	 *
	 * @return Atlassian_Ephemera_Widget
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
	 * @since Twenty Fourteen 1.0
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

		$number = empty( $instance['number'] ) ? 2 : absint( $instance['number'] );
		$title  = apply_filters( 'widget_title', empty( $instance['title'] ) ? $format_string : $instance['title'], $instance, $this->id_base );

		$ephemera = new WP_Query( array(
			'order'          => 'DESC',
			'posts_per_page' => $number,
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
	 * @since Twenty Fourteen 1.0
	 *
	 * @param array $new_instance New widget instance.
	 * @param array $instance     Original widget instance.
	 * @return array Updated widget instance.
	 */
	function update( $new_instance, $instance ) {
		$instance['title']  = strip_tags( $new_instance['title'] );
		$instance['number'] = empty( $new_instance['number'] ) ? 2 : absint( $new_instance['number'] );
		if ( in_array( $new_instance['format'], $this->formats ) ) {
			$instance['format'] = $new_instance['format'];
		}

		return $instance;
	}

	/**
	 * Display the form for this widget on the Widgets page of the Admin area.
	 *
	 * @since Twenty Fourteen 1.0
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

class Atlassian_Upcoming_Cars_Widget extends WP_Widget {

    /**
     * The supported post formats.
     *
     * @access private
     * @since Twenty Fourteen 1.0
     *
     * @var array
     */
    private $formats = array( 'aside', 'image', 'video', 'audio', 'quote', 'link', 'gallery' );

    /**
     * Constructor.
     *
     * @since Twenty Fourteen 1.0
     *
     * @return Atlassian_Ephemera_Widget
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
     * @since Twenty Fourteen 1.0
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
     * @since Twenty Fourteen 1.0
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
     * @since Twenty Fourteen 1.0
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

class Atlassian_Available_Cars_Widget extends WP_Widget {

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
     * @since Twenty Fourteen 1.0
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
     * @since Twenty Fourteen 1.0
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
     * @since Twenty Fourteen 1.0
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
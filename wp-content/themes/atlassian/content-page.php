<?php
/**
 * The template used for displaying page content
 *
 * @package Atlastheme
 * @subpackage Atlassian
 * @since Atlassian 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header"><h1 class="entry-title">
            <div class="carousel-title ">
                <div class="transform-please-2 upcoming-cars"> <span> <?php the_title(); ?> </span> </div>
            </div>
        </h1>
    </header>
	<?php
		// Page thumbnail and title.
		atlassian_post_thumbnail();
		//the_title( '<header class="entry-header"><h1 class="entry-title">', '</h1></header><!-- .entry-header -->' );
	?>

	<div class="entry-content">
		<?php
			the_content();
			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'atlassian' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
			) );

			edit_post_link( __( 'Edit', 'atlassian' ), '<span class="edit-link">', '</span>' );
		?>
	</div><!-- .entry-content -->
</article><!-- #post-## -->

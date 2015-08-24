<?php
/**
 * The Template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<?php
				// Start the Loop.
				while ( have_posts() ) : the_post();
					/*
					 * Include the post format-specific template for the content. If you want to
					 * use this in a child theme, then include a file called called content-___.php
					 * (where ___ is the post format) and that will be used instead.
					 */
					//the_post_thumbnail( 'halvbredd' );
					get_template_part( 'content-slakt_handelser', get_post_format() );

					// Previous/next post navigation.
					twentyfourteen_post_nav();

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
				endwhile;
			?>
		</div><!-- #content -->
	</div><!-- #primary -->
	<div id="content-sidebar" class="content-sidebar widget-area" role="complementary">
		<?php 
			if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('atib-medlem-sidebar-1') ) :
			endif; 
		?>
	</div><!-- #content-sidebar -->

<?php
// get_sidebar( 'content' );
// get_sidebar();
get_footer();

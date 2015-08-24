<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * Modifierad för att visa CPT Släkthändelser
 * Visar händelse-typ ovanför titel, släktgren under titel, författare är borttaget.
 * Denna template anropas med "get_template_part( 'content-slakt_handelser', get_post_format() );"
 * Anropas från följande template-filer:
 * - single-slakt_handelser.php
 * - archive-slakt_handelser.php
 * - taxonomy-slakt-gren.php
 * - taxonomy-handelse-typ.php
 * - 
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
 
 /**
 * Print HTML with meta information for the current post-date/time and author.
 * Copied from template-tags.php
 * Removed author and sticky
 *
 * @since Twenty Fourteen 1.0
 */
if ( ! function_exists( 'atib_handelser_twentyfourteen_posted_on' ) ) :
function atib_handelser_twentyfourteen_posted_on() {
	// Set up and print post meta information.
	printf( '<span class="entry-date"><a href="%1$s" rel="bookmark"><time class="entry-date" datetime="%2$s">%3$s</time></a></span>',
		esc_url( get_permalink() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);
}
endif;

?>

 <?php // if ( is_user_logged_in()  ) { ?> 

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php twentyfourteen_post_thumbnail(); ?>

	<header class="entry-header">

			<span class="cat-links"><?php echo get_the_term_list( $post->ID, 'handelse-typ', '', ', ', '' ); ?></span>

			<?php if ( is_single() ) :
				the_title( '<h1 class="entry-title">', '</h1>' );
			else :
				the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' );
			endif;
		?>

		<div class="entry-meta">
			<?php echo get_the_term_list( $post->ID, 'slakt-gren', '', ', ', '' ); ?>

			<?php
				//if ( 'post' == get_post_type() )
					atib_handelser_twentyfourteen_posted_on();

				if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
			?>
			<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'twentyfourteen' ), __( '1 Comment', 'twentyfourteen' ), __( '% Comments', 'twentyfourteen' ) ); ?></span>
			<?php
				endif;

				edit_post_link( __( 'Edit', 'twentyfourteen' ), '<span class="edit-link">', '</span>' );
			?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<?php if ( is_search() ) : ?>
	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
	<div class="entry-content">
		<?php
			/* translators: %s: Name of current post */
			the_content( sprintf(
				__( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'twentyfourteen' ),
				the_title( '<span class="screen-reader-text">', '</span>', false )
			) );

			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentyfourteen' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
			) );
		?>
	</div><!-- .entry-content -->
	<?php endif; ?>

	<?php the_tags( '<footer class="entry-meta"><span class="tag-links">', '', '</span></footer>' ); ?>
</article><!-- #post-## -->

<?php// } 	?>

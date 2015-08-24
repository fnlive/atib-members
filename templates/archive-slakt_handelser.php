<?php
/**
 * The template for displaying Archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each specific one. For example, Twenty Fourteen
 * already has tag.php for Tag archives, category.php for Category archives,
 * and author.php for Author archives.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<?php if ( have_posts() ) : ?>

			<header class="entry-header">
				<h1 class="entry-title">
					<?php
						if ( is_day() ) :
							printf( __( 'Daily Archives: %s', 'twentyfourteen' ), get_the_date() );

						elseif ( is_month() ) :
							printf( __( 'Monthly Archives: %s', 'twentyfourteen' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'twentyfourteen' ) ) );

						elseif ( is_year() ) :
							printf( __( 'Yearly Archives: %s', 'twentyfourteen' ), get_the_date( _x( 'Y', 'yearly archives date format', 'twentyfourteen' ) ) );

						elseif ( is_post_type_archive( 'slakt_handelser' ) ) :
							echo("Släkthändelser");

						elseif ( is_tax( 'slakt-gren' ) ) :
							echo("Släkthändelser för ");
							echo get_the_term_list( $post->ID, 'slakt-gren', '', ', ', '' ); 

						elseif ( is_tax( 'handelse-typ' ) ) :
							echo get_the_term_list( $post->ID, 'handelse-typ', '', ', ', '' ); 

						else :
							echo("Arkiv det vanliga!");
							_e( 'Archives', 'twentyfourteen' );

						endif;
					?>
				</h1>
			</header><!-- .page-header -->
			<div class="entry-content">
				<p>På denna sida hittar du <a href="<?php echo home_url('/slakt-handelser/'); ?>">aktuella släkthändelser</a> såsom födda, döda, vigda, födelsedagar och andra bemärkelsedagar. <a href="<?php echo home_url('/foreningen/kontakt#kontakta-oss');?>">Kontakta föreningen</a> om du vill rapportera in något för att publicera här. Innehållet på denna sida är enbart åtkomligt för betalande medlemmar i släktföreningen. 
				</p>
			</div>


			<?php
					// Start the Loop.
					while ( have_posts() ) : the_post();

						/*
						 * Include the post format-specific template for the content. If you want to
						 * use this in a child theme, then include a file called called content-___.php
						 * (where ___ is the post format) and that will be used instead.
						 */
						get_template_part( 'content-slakt_handelser', get_post_format() );

					endwhile;
					// Previous/next page navigation.
					twentyfourteen_paging_nav();

				else :
					// If no content, include the "No posts found" template.
					get_template_part( 'content', 'none' );

				endif;
			?>
		</div><!-- #content -->
	</section><!-- #primary -->
	<div id="content-sidebar" class="content-sidebar widget-area" role="complementary">
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('atib-medlem-sidebar-1') ) :
		 
		endif; ?>
	</div><!-- #content-sidebar -->

<?php
// get_sidebar( 'content' );
// get_sidebar();
get_footer();

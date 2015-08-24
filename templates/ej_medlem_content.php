<?php
/**
 * The template for displaying "Skyddat medlems-innehåll"
 * Denna sida visas när användaren vill titta på innehåll som bara är åtkomligt för 
 * betalande medlemmar och användaren inte är behörig. 
 * Om användaren inte är inloggad ombeds hsn att logga in.
 * Om användare är inloggad och får upp denna sida har han inte behörigheten "visa_medlems_innehall" 
 * vilket förmodligen beror på att han har rollen prenumerant och inte rollen "betalande_medlem".
 * Om användaren är författare, redaktör, etc. behöver behörigheten "visa_medlems_innehall" läggas till på den rollen. 
 *
 * Sidan returneras via filter som testar om användaren är behörig. 
 * Filtret installeras i functions.php funktionen atib_ej_medlem_template()
 *
 */
 get_header(); // Loads the header.php template. ?>

	<section id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<header class="entry-header">
				<h1 class="entry-title">
					Medlemsinnehåll
				</h1>
			</header><!-- .page-header -->
			<div class="entry-content">
				<?php 
					$url = home_url();
					if ( !is_user_logged_in() ) { ?>
					<h1>OBS! Medlemsinnehåll är under test och utvärdering!</h1>
					<p>Innehållet på denna sida är endast åtkomligt för släktföreningens betalande medlemmar. Som betalande medlem loggar du in med ditt konto i menyn till höger på sidan. 
					</p>
					<p>Har du inget konto kan du skapa ett konto genom att klicka på "Registrera konto" i menyn till höger. Det kan sedan ta något dygn innan kontot blivit godkänt som "Betalande medlem". Du kan också maila <a href="mailto:kontakt@annikaochtorkeliberg.se">kontakt@annikaochtorkeliberg.se</a> och be få ett konto uppsatt. 
					</p>
				<?php } else { ?>
					<h1>OBS! Medlemsinnehåll är under test och utvärdering!</h1>
					<p>Innehållet på denna sida är bara åtkomligt för betalande medlemmar i släktföreningen. Om du redan har betalat medlemsavgift så <a href="<?php echo $url;?>/foreningen/kontakt#kontakta-oss">kontakta oss</a> för att ge dig rätt behörighet. Om du inte har betalat medlemsavgift så gå till sidan <a href="<?php echo $url;?>/foreningen/bli-medlem/">bli medlem</a> för att betala medlemsavgiften. </p>
				<?php } ?>
			</div>
	
		</div><!-- #content -->
	</section><!-- #primary -->
	<div id="content-sidebar" class="content-sidebar widget-area" role="complementary">
		<?php 	
		 if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('atib-medlem-sidebar-1') ) :
		 endif;
		 ?>
	</div><!-- #content-sidebar -->
	
<?php 
 get_footer(); // Loads the footer.php template. ?>

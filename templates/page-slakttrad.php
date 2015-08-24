<?php
/**
 * The template for displaying Släktträdet
 * Modifierad från page.php
 *
 * Sidan slakttrad anropas med parametern p="personakt", 
 * t.ex. http://annikaochtorkeliberg.se/slakttrad/?p=pbe3e91eb där pbe3e91eb.html är personakts-filen.
 * Om ingen parameter anges eller om textsträngen är tom så visas Torkels personakt.
 * Släktträdsfilerna skall ligga i ...wp-contents/uploads/membersonly/slakttrad/
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>

<div id="main-content" class="main-content">

	<?php
	  $personakt = $_GET[ 'p' ];
	  if (empty($personakt)) {
		  // Öppna Torkels personakt
		  $personakt = "p2e222c41.html";
	  } else {
		  $personakt .= ".html";
	  }
	  $upload_dir = wp_upload_dir(); 
	  $url_slakttrad_dir = $upload_dir['baseurl']."/membersonly/slakttrad/";
	  $url_personlist = $url_slakttrad_dir."personlist.html";
	  $url_personakt = $url_slakttrad_dir.$personakt;
	?>

	<iframe  src="<?php echo $url_personlist;?>" name='CatalogFrame' width="25%" height="800"align="left"></iframe> 
	<iframe src="<?php echo $url_personakt;?>" name='TopicFrame' width="75%" height="800" ></iframe> 

</div><!-- #main-content -->

<?php
get_footer();

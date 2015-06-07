<?php
/*
Plugin Name: ATIB Members
Plugin URI:  https://github.com/fnlive/ATIB-Shortcodes
Description: Membership site functions for "Annika och Torkel i Berg"
Version:     0.0.1
Author:      Fredrik Nilsson
Author URI:  https://github.com/fnlive
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

add_filter( 'template_include', 'atib_ej_medlem_template', 99 );

function atib_ej_medlem_template( $template ) {
	$kan_visa_medlems_innehall = current_user_can( 'visa_medlems_innehall' );
	if ( is_singular( array( 'slakt_handelser' ) ) && !$kan_visa_medlems_innehall ){
		$template = locate_template( array( 'ej_medlem_content.php' ) );
	}
	elseif ( is_post_type_archive( array( 'slakt_handelser' ) ) && !$kan_visa_medlems_innehall ){
		$template = locate_template( array( 'ej_medlem_content.php' ) );
	}
	elseif ( is_tax( array( 'slakt-gren', 'handelse-typ' ) ) && !$kan_visa_medlems_innehall ){
		$template = locate_template( array( 'ej_medlem_content.php' ) );
	}
	elseif ( is_page( 'slakttrad' ) && !$kan_visa_medlems_innehall ){
		$template = locate_template( array( 'ej_medlem_content.php' ) );
	}
	else {
	}
	return $template;
}


// Sök filter
// Ta bort sökresultat om ej betalande medlem, dvs  ej kan visa_medlems_innehall
// Ta bort CPT slakt_handalser (dvs. visa bara resultat från 'post','page')
function atib_search_filter( $query ) {
    if ( !$query->is_admin && $query->is_search && !current_user_can( 'visa_medlems_innehall' ) ) {
		$query->set( 'post_type',array('post','page' ) );
	}
    return $query;
}
add_filter( 'pre_get_posts', 'atib_search_filter' );


// Visa bara verktygsraden för adminstrator, 
// ej för andra inloggade användare.
add_action( 'after_setup_theme', 'remove_admin_bar' );

function remove_admin_bar() {
  if ( !current_user_can( 'administrator' ) && !is_admin() ) {
    show_admin_bar( false );
  }
}


// Registrera Widget Sidebar för sidan med medlemsinnehåll
// Denna kan sedan visas genom anrop i respektive template-fil
// http://wpgyan.com/how-to-create-a-widget-area-in-wordpress-theme/
add_action( 'widgets_init', 'atib_medlem_widgets_init' );

function atib_medlem_widgets_init() {

	$args = array(
		'id'            => 'atib-medlem-sidebar-1',
		'name'          => __( 'Sidofält Medlem', 'twentyfourteen' ),
		'description'   => __( 'Sidofält som kan visas på höger sida på medlems-sidor.', 'twentyfourteen' ),
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
	);
	register_sidebar( $args );
}


// Extra medlemsinformation i användarprofilen
// Lägg till släkt-gren som metadata under user profle
// 
add_action( 'show_user_profile', 'atib_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'atib_show_extra_profile_fields' );

function atib_show_extra_profile_fields( $user ) { ?>

	<h3>Extra medlemsinformation</h3>

	<table class="form-table">
		<tr>
			<th><label for="slaktgren">Släktgren</label></th>
			<td>
			<?php 
            //get dropdown saved value
            $selected = get_the_author_meta( 'slaktgren', $user->ID );
            ?>
				<select name="slaktgren" id="slaktgren">
					<option value="" <?php echo ( $selected == "" ) ?  'selected="selected"' : ''; ?>>Ej vald</option>
					<option value="Anna-grenen" <?php echo ( $selected == "Anna-grenen" ) ?  'selected="selected"' : ''; ?>>Anna-grenen</option>
					<option value="Andreas-grenen" <?php echo ( $selected == "Andreas-grenen" ) ?  'selected="selected"' : ''; ?>>Andreas-grenen</option>
					<option value="Anders-grenen" <?php echo ( $selected == "Anders-grenen" ) ?  'selected="selected"' : ''; ?>>Anders-grenen</option>
					<option value="Maria-grenen" <?php echo ( $selected == "Maria-grenen" ) ?  'selected="selected"' : ''; ?>>Maria-grenen</option>
					<option value="Annika-grenen" <?php echo ( $selected == "Annika-grenen" ) ?  'selected="selected"' : ''; ?>>Annika-grenen</option>
					<option value="Anna Stina-grenen" <?php echo ( $selected == "Anna Stina-grenen" ) ?  'selected="selected"' : ''; ?>>Anna Stina-grenen</option>
					<option value="Ej Ättling" <?php echo ( $selected == "Ej Ättling" ) ?  'selected="selected"' : ''; ?>>Ej Ättling</option>
				</select>
				<span class="description">Välj släktgren</span>
			</td>
		</tr>
	</table>
	<?php 
}

add_action( 'personal_options_update', 'atib_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'atib_save_extra_profile_fields' );

function atib_save_extra_profile_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}
	/* Copy and paste this line for additional fields. */
	update_usermeta( $user_id, 'slaktgren', $_POST['slaktgren'] );
}


/*
* Funktioner för släkthändelser nedan
*
* Skapa custom post types för "Släkthändelser": slakt_handelser
* Lägg till taxonomy slakt-gren
* Lägg till taxonomy handelse-typ
* http://generatewp.com/snippet/bkdra1w/
* Skapa template för arkiv och single (template-filer i themes-folder)
* ((http://premium.wpmudev.org/blog/create-wordpress-custom-post-types/))
* http://www.wpbeginner.com/wp-tutorials/how-to-create-custom-post-types-in-wordpress/
* 
* CPT släkthändelser: 'slakt_handelser'
*
* CPT-taxonomies: 'handelse-typ': Födda, In Memoriam, Vigda, ...
* CPT-taxonomies: 'slakt-gren': Andreas-grenen, Anders-grenen, ...
*
* Följande mallar behövs i themes-folder:
* content-slakt_handelser.php	- visa innehållet i CPT
* single-slakt_handelser.php	- visa enskild/"Single" CPT
* archive-slakt_handelser.php	- visa arkiv med alla CPT'er
* taxonomy-handelse-typ.php	 	- visa arkiv med alla CPT'er med Händelsetyp x
* taxonomy-slakt-gren.php	 	- visa arkiv med alla CPT'er med släktgren x 
*
*/


// Register Custom Post Type
function atib_slakt_handelse_cpt() {

	$labels = array(
		'name'                => _x( 'Släkthändelser', 'Post Type General Name', 'twentyfourteen' ),
		'singular_name'       => _x( 'Släkthändelse', 'Post Type Singular Name', 'twentyfourteen' ),
		'menu_name'           => __( 'Släkthändelser', 'twentyfourteen' ),
		'parent_item_colon'   => __( 'Parent Item:', 'twentyfourteen' ),
		'all_items'           => __( 'Alla händelser', 'twentyfourteen' ),
		'view_item'           => __( 'Visa', 'twentyfourteen' ),
		'add_new_item'        => __( 'Skapa ny händelse', 'twentyfourteen' ),
		'add_new'             => __( 'Skapa ny', 'twentyfourteen' ),
		'edit_item'           => __( 'Redigera händelse', 'twentyfourteen' ),
		'update_item'         => __( 'Update Item', 'twentyfourteen' ),
		'search_items'        => __( 'Search Item', 'twentyfourteen' ),
		'not_found'           => __( 'Hittade inga händelser', 'twentyfourteen' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'twentyfourteen' ),
		);
	$rewrite = array(
		'slug'                => 'slakt-handelser',
		'with_front'          => true,
		'pages'               => true,
		'feeds'               => false,
	);
	$args = array(
		'label'               => __( 'slakt_handelser', 'twentyfourteen' ),
		'description'         => __( 'Post Type för Släkt händelser såsom födda, döda, vigda, födelsedag, etc.', 'twentyfourteen' ),
		'labels'              => $labels,
		'supports'            => array(  'title', 'editor', 'author', 'comments', ),
		//'taxonomies'          => array( 'handelse_typ', 'slakt-gren' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-star-filled',
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'rewrite'             => $rewrite,
		'capability_type'     => 'page',
	);
	register_post_type( 'slakt_handelser', $args );
}

// Hook into the 'init' action
add_action( 'init', 'atib_slakt_handelse_cpt', 0 );


// Register Custom Taxonomy
function atib_slakt_gren_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Släktgrenar', 'Taxonomy General Name', 'twentyfourteen' ),
		'singular_name'              => _x( 'Släktgren', 'Taxonomy Singular Name', 'twentyfourteen' ),
		'menu_name'                  => __( 'Släktgrenar', 'twentyfourteen' ),
		'all_items'                  => __( 'All Items', 'twentyfourteen' ),
		'parent_item'                => __( 'Parent Item', 'twentyfourteen' ),
		'parent_item_colon'          => __( 'Parent Item:', 'twentyfourteen' ),
		'new_item_name'              => __( 'New Item Name', 'twentyfourteen' ),
		'add_new_item'               => __( 'Lägg till släktgren', 'twentyfourteen' ),
		'edit_item'                  => __( 'Redigera släkt-gren', 'twentyfourteen' ),
		'update_item'                => __( 'Update Item', 'twentyfourteen' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'twentyfourteen' ),
		'search_items'               => __( 'Search Items', 'twentyfourteen' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'twentyfourteen' ),
		'choose_from_most_used'      => __( 'Choose from the most used items', 'twentyfourteen' ),
		'not_found'                  => __( 'Not Found', 'twentyfourteen' ),
	);
	$rewrite = array(
		'slug'                       => 'handelser/gren',
		'with_front'                 => true,
		'hierarchical'               => false,
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => false,
		'rewrite'                    => $rewrite,
	);
	register_taxonomy( 'slakt-gren', array( 'slakt_handelser' ), $args );

}

// Hook into the 'init' action
add_action( 'init', 'atib_slakt_gren_taxonomy', 0 );


// Register Custom Taxonomy
function atib_slakt_handelse_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Händelsetyper', 'Taxonomy General Name', 'twentyfourteen' ),
		'singular_name'              => _x( 'Händelsetyp', 'Taxonomy Singular Name', 'twentyfourteen' ),
		'menu_name'                  => __( 'Händelsetyper', 'twentyfourteen' ),
		'all_items'                  => __( 'All Items', 'twentyfourteen' ),
		'parent_item'                => __( 'Parent Item', 'twentyfourteen' ),
		'parent_item_colon'          => __( 'Parent Item:', 'twentyfourteen' ),
		'new_item_name'              => __( 'New Item Name', 'twentyfourteen' ),
		'add_new_item'               => __( 'Lägg till händelsetyp', 'twentyfourteen' ),
		'edit_item'                  => __( 'Redigera händelsetyp', 'twentyfourteen' ),
		'update_item'                => __( 'Update Item', 'twentyfourteen' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'twentyfourteen' ),
		'search_items'               => __( 'Search Items', 'twentyfourteen' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'twentyfourteen' ),
		'choose_from_most_used'      => __( 'Choose from the most used items', 'twentyfourteen' ),
		'not_found'                  => __( 'Not Found', 'twentyfourteen' ),
	);
	$rewrite = array(
		'slug'                       => 'handelser',
		'with_front'                 => true,
		'hierarchical'               => false,
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => false,
		'rewrite'                    => $rewrite,
	);
	register_taxonomy( 'handelse-typ', array( 'slakt_handelser' ), $args );

}

// Hook into the 'init' action
add_action( 'init', 'atib_slakt_handelse_taxonomy', 0 );




// Funktioner nedanför används till släktträdet. 
//
// Add Shortcode. Shortcode skapar länk till personakt i släktträdet. 
// Kan användas i inlägg och på sidor genom att sätta kort-koden runt ett namn.
// Ange namn på personaktsfil som parameter p. Namn finns i filen gendex.txt
// Användning: [atib_person p=p2592929b]Anna Moberg[/atib_person]
// Namn på personaktsfilen är då alltså p2592929b.html
// Öppnar personakt i nytt fönster eller tab (target="_blank")
// Säg till sökrobot att inte följa länk (rel="nofollow")
function atib_slakttrad_person( $atts , $content = null ) {

	// Attributes
	extract( shortcode_atts(
		array(
			'p' => 'p2e222c41',
		), $atts )
	);
	$url = get_home_url();

	// Code
return '<a title="Personakt visas i nytt fönster" href="'.$url.'/slakttrad/?p='.$p.'" target="_blank" rel="nofollow">' . $content . '</a>';
}
add_shortcode( 'atib_person', 'atib_slakttrad_person' );

// Add Quicktag (knapp) till text-editorn 
// skapar en shortcode med personakt i släktträdet. Se shortcode nedan.
// Markera en text i texteditorn och tryck sedan på knappen "person"
function person_quicktags() {
	if ( wp_script_is( 'quicktags' ) ) {
		?>
		<script type="text/javascript">
			QTags.addButton( 'atib_person', 'person', '[atib_person p=""]', '[/atib_person]', 'p', 'personakt', 1 );
		</script>
		<?php
	}
}
// Hook into the 'admin_print_footer_scripts' action
add_action( 'admin_print_footer_scripts', 'person_quicktags' );


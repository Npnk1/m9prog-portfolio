<?php
/**
 * Theme setup: supports, menu's en Bootstrap-klassen voor het menu.
 *
 * @package NP_Portfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registreert wat het theme ondersteunt.
 */
function np_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support(
		'html5',
		array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);

	// Vaste verhouding 16:10 voor projectafbeeldingen.
	add_image_size( 'np-project', 1200, 750, true );

	register_nav_menus(
		array(
			'primary' => __( 'Hoofdmenu', 'np-portfolio' ),
		)
	);
}
add_action( 'after_setup_theme', 'np_setup' );

/**
 * Is dit menu-item de link naar het projectoverzicht terwijl we op een projectpagina zijn?
 * WordPress markeert een eigen link (custom link) niet automatisch als actief.
 *
 * @param WP_Post $item Menu-item.
 * @return bool
 */
function np_is_projects_item_active( $item ) {
	if ( ! is_post_type_archive( 'project' ) && ! is_singular( 'project' ) ) {
		return false;
	}
	$archive = get_post_type_archive_link( 'project' );
	return $archive && untrailingslashit( $item->url ) === untrailingslashit( $archive );
}

/**
 * Voegt de Bootstrap-klasse nav-item toe aan elk <li> in het hoofdmenu.
 *
 * @param array    $classes Klassen van het <li>.
 * @param WP_Post  $item    Menu-item.
 * @param stdClass $args    Argumenten van wp_nav_menu().
 * @return array
 */
function np_nav_menu_css_class( $classes, $item, $args ) {
	if ( isset( $args->theme_location ) && 'primary' === $args->theme_location ) {
		$classes[] = 'nav-item';
		if ( np_is_projects_item_active( $item ) ) {
			$classes[] = 'current-menu-item';
		}
	}
	return $classes;
}
add_filter( 'nav_menu_css_class', 'np_nav_menu_css_class', 10, 3 );

/**
 * Voegt de Bootstrap-klasse nav-link toe aan elke link in het hoofdmenu
 * en markeert de huidige pagina met aria-current.
 *
 * @param array    $atts HTML-attributen van de link.
 * @param WP_Post  $item Menu-item.
 * @param stdClass $args Argumenten van wp_nav_menu().
 * @return array
 */
function np_nav_menu_link_attributes( $atts, $item, $args ) {
	if ( isset( $args->theme_location ) && 'primary' === $args->theme_location ) {
		$atts['class'] = 'nav-link';
		if ( $item->current || np_is_projects_item_active( $item ) ) {
			$atts['class']       .= ' active';
			$atts['aria-current'] = 'page';
		}
	}
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'np_nav_menu_link_attributes', 10, 3 );

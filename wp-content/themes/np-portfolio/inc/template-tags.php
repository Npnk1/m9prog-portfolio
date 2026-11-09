<?php
/**
 * Kleine hulpfuncties die in de templates worden gebruikt.
 *
 * @package NP_Portfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * URL van een pagina op basis van de slug, met een veilige terugval.
 *
 * @param string $slug Slug van de pagina, bijv. 'contact'.
 * @return string
 */
function np_page_url( $slug ) {
	$page = get_page_by_path( $slug );
	return $page ? get_permalink( $page ) : home_url( '/' . $slug . '/' );
}

/**
 * URL van het projectoverzicht.
 *
 * @return string
 */
function np_projects_url() {
	$url = get_post_type_archive_link( 'project' );
	return $url ? $url : home_url( '/projecten/' );
}

/**
 * Technieken van een project als array (ingevoerd als komma-gescheiden tekst).
 *
 * @param int|null $post_id Post-ID, standaard het huidige project.
 * @return string[]
 */
function np_get_project_tech( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$raw     = (string) get_post_meta( $post_id, '_np_tech', true );
	if ( '' === $raw ) {
		return array();
	}
	return array_values( array_filter( array_map( 'trim', explode( ',', $raw ) ) ) );
}

/**
 * Links naar profielen buiten de site. Pas hier je eigen links aan.
 *
 * @return array<string, string> Label => URL.
 */
function np_profile_links() {
	return array(
		'GitHub' => 'https://github.com/Npnk1',
	);
}

/**
 * Terugval-menu als er in WordPress nog geen menu is gekoppeld aan "Hoofdmenu".
 */
function np_fallback_menu() {
	$items = array(
		'Home'      => home_url( '/' ),
		'Over mij'  => np_page_url( 'over-mij' ),
		'Projecten' => np_projects_url(),
		'Contact'   => np_page_url( 'contact' ),
	);

	echo '<ul class="navbar-nav ms-auto">';
	foreach ( $items as $label => $url ) {
		printf(
			'<li class="nav-item"><a class="nav-link" href="%1$s">%2$s</a></li>',
			esc_url( $url ),
			esc_html( $label )
		);
	}
	echo '</ul>';
}

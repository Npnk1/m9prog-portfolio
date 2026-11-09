<?php
/**
 * Laadt de gebouwde assets uit /dist.
 * De bestanden worden gemaakt met `npm run build` (zie README).
 *
 * @package NP_Portfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Versienummer op basis van de wijzigingsdatum van het bestand,
 * zodat de browser na een nieuwe build geen oude cache gebruikt.
 *
 * @param string $relative_path Pad vanaf de theme-map, bijv. '/dist/main.css'.
 * @return string
 */
function np_asset_version( $relative_path ) {
	$file = NP_THEME_DIR . $relative_path;
	return file_exists( $file ) ? (string) filemtime( $file ) : NP_THEME_VERSION;
}

/**
 * CSS en JavaScript inladen.
 */
function np_enqueue_assets() {
	wp_enqueue_style(
		'np-main',
		NP_THEME_URI . '/dist/main.css',
		array(),
		np_asset_version( '/dist/main.css' )
	);

	wp_enqueue_script(
		'np-main',
		NP_THEME_URI . '/dist/main.js',
		array(),
		np_asset_version( '/dist/main.js' ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'np_enqueue_assets' );

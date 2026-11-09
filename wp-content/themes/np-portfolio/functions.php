<?php
/**
 * Startpunt van het theme.
 * Elk onderdeel staat in een eigen bestand in /inc, zodat functions.php overzichtelijk blijft.
 *
 * @package NP_Portfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'NP_THEME_VERSION', '1.0.0' );
define( 'NP_THEME_DIR', get_template_directory() );
define( 'NP_THEME_URI', get_template_directory_uri() );

require_once NP_THEME_DIR . '/inc/setup.php';         // theme supports, menu's, afbeeldingsformaten
require_once NP_THEME_DIR . '/inc/enqueue.php';       // CSS en JS uit /dist laden
require_once NP_THEME_DIR . '/inc/template-tags.php'; // kleine hulpfuncties voor templates
require_once NP_THEME_DIR . '/inc/cpt-project.php';   // Custom Post Type "project"
require_once NP_THEME_DIR . '/inc/project-meta.php';  // extra velden: rol, technieken, resultaat
require_once NP_THEME_DIR . '/inc/contact.php';       // contactformulier verwerken en opslaan

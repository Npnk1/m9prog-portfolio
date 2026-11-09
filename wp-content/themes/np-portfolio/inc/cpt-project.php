<?php
/**
 * Custom Post Type "project" voor portfolio-projecten.
 * Overzicht: /projecten/  (archive-project.php)
 * Detail:    /projecten/<slug>/  (single-project.php)
 *
 * @package NP_Portfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registreert het post type.
 */
function np_register_project_post_type() {
	$labels = array(
		'name'                  => 'Projecten',
		'singular_name'         => 'Project',
		'menu_name'             => 'Projecten',
		'all_items'             => 'Alle projecten',
		'add_new'               => 'Nieuw project',
		'add_new_item'          => 'Nieuw project toevoegen',
		'edit_item'             => 'Project bewerken',
		'new_item'              => 'Nieuw project',
		'view_item'             => 'Project bekijken',
		'view_items'            => 'Projecten bekijken',
		'search_items'          => 'Projecten zoeken',
		'not_found'             => 'Geen projecten gevonden',
		'not_found_in_trash'    => 'Geen projecten in de prullenbak',
		'featured_image'        => 'Projectafbeelding',
		'set_featured_image'    => 'Projectafbeelding instellen',
		'remove_featured_image' => 'Projectafbeelding verwijderen',
		'use_featured_image'    => 'Gebruiken als projectafbeelding',
	);

	register_post_type(
		'project',
		array(
			'labels'        => $labels,
			'public'        => true,
			'has_archive'   => 'projecten',
			'rewrite'       => array(
				'slug'       => 'projecten',
				'with_front' => false,
			),
			'menu_icon'     => 'dashicons-portfolio',
			'menu_position' => 5,
			'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
			'show_in_rest'  => true, // nodig voor de blok-editor
		)
	);
}
add_action( 'init', 'np_register_project_post_type' );

/**
 * Na het activeren van het theme de permalinks verversen,
 * anders geeft /projecten/ een 404.
 */
function np_flush_rewrite_rules() {
	np_register_project_post_type();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'np_flush_rewrite_rules' );

/**
 * Projectoverzicht: nieuwste eerst, maximaal 12 per pagina.
 *
 * @param WP_Query $query De query die WordPress gaat uitvoeren.
 */
function np_project_archive_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_post_type_archive( 'project' ) ) {
		return;
	}
	$query->set( 'posts_per_page', 12 );
	$query->set( 'orderby', 'date' );
	$query->set( 'order', 'DESC' );
}
add_action( 'pre_get_posts', 'np_project_archive_query' );

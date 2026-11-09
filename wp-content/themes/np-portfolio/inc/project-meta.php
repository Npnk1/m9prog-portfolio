<?php
/**
 * Extra velden voor projecten (meta box): rol, technieken, resultaat, jaar en link.
 * Bewust zonder plugin (zoals ACF), zodat je ziet hoe het in WordPress zelf werkt.
 *
 * @package NP_Portfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Definitie van alle velden. De meta-keys beginnen met _ zodat ze niet
 * dubbel verschijnen in het standaard "Aangepaste velden"-blok.
 *
 * @return array<string, array>
 */
function np_project_fields() {
	return array(
		'_np_role'   => array(
			'label' => 'Mijn rol',
			'type'  => 'text',
			'help'  => 'Bijvoorbeeld: front-end developer in een team van vier.',
		),
		'_np_tech'   => array(
			'label' => 'Technieken',
			'type'  => 'text',
			'help'  => 'Scheid met komma’s, bijvoorbeeld: PHP, Sass, Docker.',
		),
		'_np_result' => array(
			'label' => 'Resultaat',
			'type'  => 'textarea',
			'help'  => 'Wat heeft het project opgeleverd?',
		),
		'_np_year'   => array(
			'label' => 'Jaar',
			'type'  => 'text',
			'help'  => '',
		),
		'_np_link'   => array(
			'label' => 'Link (GitHub of live versie)',
			'type'  => 'url',
			'help'  => 'Optioneel.',
		),
	);
}

/**
 * Voegt de meta box toe aan het bewerkscherm van een project.
 */
function np_add_project_meta_box() {
	add_meta_box(
		'np_project_details',
		'Projectdetails',
		'np_render_project_meta_box',
		'project',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'np_add_project_meta_box' );

/**
 * Toont de velden in de meta box.
 *
 * @param WP_Post $post Het project dat wordt bewerkt.
 */
function np_render_project_meta_box( $post ) {
	wp_nonce_field( 'np_save_project_meta', 'np_project_meta_nonce' );

	echo '<table class="form-table" role="presentation"><tbody>';

	foreach ( np_project_fields() as $key => $field ) {
		$value = (string) get_post_meta( $post->ID, $key, true );
		$id    = 'np-field' . str_replace( '_', '-', $key );

		echo '<tr><th scope="row">';
		printf( '<label for="%1$s">%2$s</label>', esc_attr( $id ), esc_html( $field['label'] ) );
		echo '</th><td>';

		if ( 'textarea' === $field['type'] ) {
			printf(
				'<textarea id="%1$s" name="%2$s" rows="4" class="large-text">%3$s</textarea>',
				esc_attr( $id ),
				esc_attr( $key ),
				esc_textarea( $value )
			);
		} else {
			printf(
				'<input type="%1$s" id="%2$s" name="%3$s" value="%4$s" class="regular-text">',
				esc_attr( $field['type'] ),
				esc_attr( $id ),
				esc_attr( $key ),
				esc_attr( $value )
			);
		}

		if ( '' !== $field['help'] ) {
			printf( '<p class="description">%s</p>', esc_html( $field['help'] ) );
		}

		echo '</td></tr>';
	}

	echo '</tbody></table>';
}

/**
 * Slaat de velden veilig op: nonce, autosave en rechten controleren, daarna opschonen.
 *
 * @param int $post_id ID van het project.
 */
function np_save_project_meta( $post_id ) {
	if ( ! isset( $_POST['np_project_meta_nonce'] ) ) {
		return;
	}
	$nonce = sanitize_text_field( wp_unslash( $_POST['np_project_meta_nonce'] ) );
	if ( ! wp_verify_nonce( $nonce, 'np_save_project_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	foreach ( np_project_fields() as $key => $field ) {
		if ( ! isset( $_POST[ $key ] ) ) {
			continue;
		}

		$raw = wp_unslash( $_POST[ $key ] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- wordt hieronder per type opgeschoond.

		switch ( $field['type'] ) {
			case 'url':
				$value = esc_url_raw( $raw );
				break;
			case 'textarea':
				$value = sanitize_textarea_field( $raw );
				break;
			default:
				$value = sanitize_text_field( $raw );
		}

		if ( '' === $value ) {
			delete_post_meta( $post_id, $key );
		} else {
			update_post_meta( $post_id, $key, $value );
		}
	}
}
add_action( 'save_post_project', 'np_save_project_meta' );

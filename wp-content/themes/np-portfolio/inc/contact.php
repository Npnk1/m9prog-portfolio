<?php
/**
 * Contactformulier.
 * - Het formulier staat in page-contact.php.
 * - Verwerking gebeurt hier, vóór de pagina wordt getoond (template_redirect).
 * - Elk bericht wordt opgeslagen als "Bericht" in het dashboard en daarna gemaild.
 *   Lokaal in Docker werkt mailen meestal niet; het bericht staat dan alsnog in het dashboard.
 *
 * @package NP_Portfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Post type voor binnengekomen berichten. Niet publiek, alleen zichtbaar in het dashboard.
 */
function np_register_message_post_type() {
	register_post_type(
		'np_message',
		array(
			'labels'          => array(
				'name'          => 'Berichten',
				'singular_name' => 'Bericht',
				'menu_name'     => 'Berichten',
				'all_items'     => 'Alle berichten',
				'edit_item'     => 'Bericht bekijken',
				'not_found'     => 'Nog geen berichten',
			),
			'public'          => false,
			'show_ui'         => true,
			'menu_icon'       => 'dashicons-email',
			'menu_position'   => 26,
			'supports'        => array( 'title', 'editor', 'custom-fields' ),
			'capability_type' => 'post',
			'capabilities'    => array( 'create_posts' => 'do_not_allow' ), // berichten komen alleen via het formulier
			'map_meta_cap'    => true,
		)
	);
}
add_action( 'init', 'np_register_message_post_type' );

/**
 * Houdt de foutmeldingen en ingevulde waarden bij tijdens één request,
 * zodat het formulier ze kan tonen zonder dat de bezoeker alles opnieuw moet typen.
 *
 * @param array|null $new_state Nieuwe status, of null om alleen te lezen.
 * @return array{errors: array<string,string>, values: array<string,string>}
 */
function np_contact_state( $new_state = null ) {
	static $state = array(
		'errors' => array(),
		'values' => array(
			'name'    => '',
			'email'   => '',
			'company' => '',
			'message' => '',
		),
	);

	if ( null !== $new_state ) {
		$state = $new_state;
	}
	return $state;
}

/**
 * Foutmelding voor één veld (lege string als er geen fout is).
 *
 * @param string $field Veldnaam: name, email, message of form.
 * @return string
 */
function np_contact_error( $field ) {
	$state = np_contact_state();
	return isset( $state['errors'][ $field ] ) ? $state['errors'][ $field ] : '';
}

/**
 * Ingevulde waarde van één veld.
 *
 * @param string $field Veldnaam.
 * @return string
 */
function np_contact_value( $field ) {
	$state = np_contact_state();
	return isset( $state['values'][ $field ] ) ? $state['values'][ $field ] : '';
}

/**
 * Verwerkt het formulier.
 */
function np_handle_contact_form() {
	if ( ! isset( $_SERVER['REQUEST_METHOD'] ) || 'POST' !== $_SERVER['REQUEST_METHOD'] || ! isset( $_POST['np_contact_submit'] ) ) {
		return;
	}

	$page_url = get_permalink( get_queried_object_id() );

	// Honeypot: dit veld is onzichtbaar voor bezoekers. Is het ingevuld, dan is het vrijwel zeker spam.
	if ( ! empty( $_POST['np_website'] ) ) {
		wp_safe_redirect( add_query_arg( 'verzonden', '1', $page_url ) );
		exit;
	}

	$values = array(
		'name'    => sanitize_text_field( wp_unslash( $_POST['np_name'] ?? '' ) ),
		'email'   => sanitize_text_field( wp_unslash( $_POST['np_email'] ?? '' ) ), // wordt hieronder gecontroleerd met is_email()
		'company' => sanitize_text_field( wp_unslash( $_POST['np_company'] ?? '' ) ),
		'message' => sanitize_textarea_field( wp_unslash( $_POST['np_message'] ?? '' ) ),
	);

	$errors = array();
	$nonce  = sanitize_text_field( wp_unslash( $_POST['np_contact_nonce'] ?? '' ) );

	if ( ! wp_verify_nonce( $nonce, 'np_contact' ) ) {
		$errors['form'] = 'Het formulier is verlopen. Verstuur het opnieuw.';
	}
	if ( '' === $values['name'] ) {
		$errors['name'] = 'Vul je naam in.';
	}
	if ( '' === $values['email'] ) {
		$errors['email'] = 'Vul je e-mailadres in.';
	} elseif ( ! is_email( $values['email'] ) ) {
		$errors['email'] = 'Vul een geldig e-mailadres in, bijvoorbeeld naam@bedrijf.nl.';
	}
	if ( mb_strlen( $values['message'] ) < 10 ) {
		$errors['message'] = 'Schrijf een bericht van minimaal 10 tekens.';
	}

	if ( $errors ) {
		// De ingevulde waarden gaan terug naar het formulier, zodat de bezoeker niets opnieuw hoeft te typen.
		np_contact_state(
			array(
				'errors' => $errors,
				'values' => $values,
			)
		);
		return; // De pagina wordt nu getoond met foutmeldingen.
	}

	$values['email'] = sanitize_email( $values['email'] );

	$post_id = wp_insert_post(
		array(
			'post_type'    => 'np_message',
			'post_status'  => 'private',
			'post_title'   => sprintf( 'Bericht van %s', $values['name'] ),
			'post_content' => $values['message'],
			'meta_input'   => array(
				'email'   => $values['email'],
				'bedrijf' => $values['company'],
			),
		),
		true
	);

	if ( is_wp_error( $post_id ) ) {
		np_contact_state(
			array(
				'errors' => array( 'form' => 'Het bericht kon niet worden opgeslagen. Probeer het later opnieuw.' ),
				'values' => $values,
			)
		);
		return;
	}

	$body = sprintf(
		"Naam: %s\nE-mail: %s\nBedrijf: %s\n\n%s",
		$values['name'],
		$values['email'],
		$values['company'] ? $values['company'] : '-',
		$values['message']
	);

	wp_mail(
		get_option( 'admin_email' ),
		sprintf( '[%s] Nieuw bericht van %s', get_bloginfo( 'name' ), $values['name'] ),
		$body,
		array( sprintf( 'Reply-To: %s <%s>', $values['name'], $values['email'] ) )
	);

	// Post/Redirect/Get: na verversen wordt het formulier niet nog een keer verstuurd.
	wp_safe_redirect( add_query_arg( 'verzonden', '1', $page_url ) . '#contactformulier' );
	exit;
}
add_action( 'template_redirect', 'np_handle_contact_form' );

<?php
/**
 * Pagina "Contact" (slug: contact) met een toegankelijk contactformulier.
 * De verwerking staat in inc/contact.php.
 *
 * @package NP_Portfolio
 */

get_header();

// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- alleen een melding tonen.
$np_sent       = isset( $_GET['verzonden'] );
$np_form_error = np_contact_error( 'form' );
$np_state      = np_contact_state();
$np_has_errors = ! empty( $np_state['errors'] );

$np_labels = array(
	'name'    => 'Naam',
	'email'   => 'E-mailadres',
	'message' => 'Bericht',
);

while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class(); ?>>
		<header class="page-head">
			<div class="container">
				<h1 class="page-head__title"><?php the_title(); ?></h1>
			</div>
		</header>

		<div class="container section section--tight">
			<div class="row gy-5">
				<div class="col-lg-5 entry-content">
					<?php the_content(); ?>
				</div>

				<div class="col-lg-7" id="contactformulier">
					<?php if ( $np_sent ) : ?>
						<div class="form-notice form-notice--success" role="status" tabindex="-1">
							<h2 class="h4">Bericht verstuurd</h2>
							<p>Bedankt. Ik lees je bericht en reageer zo snel mogelijk.</p>
						</div>
					<?php endif; ?>

					<?php if ( $np_has_errors ) : ?>
						<div class="form-notice form-notice--error" role="alert" tabindex="-1">
							<h2 class="h4">Het bericht is nog niet verstuurd</h2>
							<?php if ( $np_form_error ) : ?>
								<p><?php echo esc_html( $np_form_error ); ?></p>
							<?php endif; ?>
							<ul>
								<?php foreach ( $np_labels as $np_field => $np_label ) : ?>
									<?php if ( np_contact_error( $np_field ) ) : ?>
										<li><a href="#np-<?php echo esc_attr( $np_field ); ?>"><?php echo esc_html( $np_label . ': ' . np_contact_error( $np_field ) ); ?></a></li>
									<?php endif; ?>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>

					<form class="contact-form" method="post" action="<?php echo esc_url( get_permalink() ); ?>#contactformulier" novalidate>
						<?php wp_nonce_field( 'np_contact', 'np_contact_nonce' ); ?>

						<div class="mb-4">
							<label class="form-label" for="np-name">Naam</label>
							<input class="form-control<?php echo np_contact_error( 'name' ) ? ' is-invalid' : ''; ?>" type="text" id="np-name" name="np_name" autocomplete="name" required
								value="<?php echo esc_attr( np_contact_value( 'name' ) ); ?>"
								<?php echo np_contact_error( 'name' ) ? 'aria-invalid="true" aria-describedby="np-name-error"' : ''; ?>>
							<?php if ( np_contact_error( 'name' ) ) : ?>
								<p class="invalid-feedback" id="np-name-error"><?php echo esc_html( np_contact_error( 'name' ) ); ?></p>
							<?php endif; ?>
						</div>

						<div class="mb-4">
							<label class="form-label" for="np-email">E-mailadres</label>
							<input class="form-control<?php echo np_contact_error( 'email' ) ? ' is-invalid' : ''; ?>" type="email" id="np-email" name="np_email" autocomplete="email" required
								value="<?php echo esc_attr( np_contact_value( 'email' ) ); ?>"
								<?php echo np_contact_error( 'email' ) ? 'aria-invalid="true" aria-describedby="np-email-error"' : ''; ?>>
							<?php if ( np_contact_error( 'email' ) ) : ?>
								<p class="invalid-feedback" id="np-email-error"><?php echo esc_html( np_contact_error( 'email' ) ); ?></p>
							<?php endif; ?>
						</div>

						<div class="mb-4">
							<label class="form-label" for="np-company">Bedrijf <span class="form-optional">(optioneel)</span></label>
							<input class="form-control" type="text" id="np-company" name="np_company" autocomplete="organization"
								value="<?php echo esc_attr( np_contact_value( 'company' ) ); ?>">
						</div>

						<div class="mb-4">
							<label class="form-label" for="np-message">Bericht</label>
							<textarea class="form-control<?php echo np_contact_error( 'message' ) ? ' is-invalid' : ''; ?>" id="np-message" name="np_message" rows="6" required
								<?php echo np_contact_error( 'message' ) ? 'aria-invalid="true" aria-describedby="np-message-error"' : ''; ?>><?php echo esc_textarea( np_contact_value( 'message' ) ); ?></textarea>
							<?php if ( np_contact_error( 'message' ) ) : ?>
								<p class="invalid-feedback" id="np-message-error"><?php echo esc_html( np_contact_error( 'message' ) ); ?></p>
							<?php endif; ?>
						</div>

						<?php // Honeypot tegen spam: verborgen voor bezoekers en schermlezers. ?>
						<div class="hp-field" aria-hidden="true">
							<label for="np-website">Laat dit veld leeg</label>
							<input type="text" id="np-website" name="np_website" tabindex="-1" autocomplete="off">
						</div>

						<button class="btn btn-primary btn-lg" type="submit" name="np_contact_submit" value="1">Bericht versturen</button>
					</form>
				</div>
			</div>
		</div>
	</article>
	<?php
endwhile;

get_footer();

<?php
/**
 * Detailpagina van één project.
 *
 * @package NP_Portfolio
 */

get_header();

while ( have_posts() ) :
	the_post();

	$np_role   = get_post_meta( get_the_ID(), '_np_role', true );
	$np_result = get_post_meta( get_the_ID(), '_np_result', true );
	$np_year   = get_post_meta( get_the_ID(), '_np_year', true );
	$np_link   = get_post_meta( get_the_ID(), '_np_link', true );
	$np_tech   = np_get_project_tech();
	?>

	<article <?php post_class( 'project' ); ?>>
		<header class="page-head">
			<div class="container">
				<a class="back-link" href="<?php echo esc_url( np_projects_url() ); ?>">Alle projecten</a>
				<h1 class="page-head__title"><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?>
					<p class="page-head__lead"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>
			</div>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<figure class="container project__figure">
				<?php the_post_thumbnail( 'large' ); ?>
			</figure>
		<?php endif; ?>

		<div class="container section section--tight">
			<div class="row gy-5">
				<aside class="col-lg-4 order-lg-2" aria-label="Projectgegevens">
					<dl class="project-facts">
						<?php if ( $np_role ) : ?>
							<dt>Rol</dt>
							<dd><?php echo esc_html( $np_role ); ?></dd>
						<?php endif; ?>

						<?php if ( $np_tech ) : ?>
							<dt>Technieken</dt>
							<dd>
								<ul class="tech-list">
									<?php foreach ( $np_tech as $np_item ) : ?>
										<li><?php echo esc_html( $np_item ); ?></li>
									<?php endforeach; ?>
								</ul>
							</dd>
						<?php endif; ?>

						<?php if ( $np_result ) : ?>
							<dt>Resultaat</dt>
							<dd><?php echo nl2br( esc_html( $np_result ) ); ?></dd>
						<?php endif; ?>

						<?php if ( $np_year ) : ?>
							<dt>Jaar</dt>
							<dd><?php echo esc_html( $np_year ); ?></dd>
						<?php endif; ?>

						<?php if ( $np_link ) : ?>
							<dt>Link</dt>
							<dd><a href="<?php echo esc_url( $np_link ); ?>"><?php echo esc_html( preg_replace( '#^https?://#', '', $np_link ) ); ?></a></dd>
						<?php endif; ?>
					</dl>
				</aside>

				<div class="col-lg-8 order-lg-1 entry-content">
					<?php the_content(); ?>
				</div>
			</div>

			<nav class="project-nav" aria-label="Andere projecten">
				<div class="project-nav__prev"><?php previous_post_link( '%link', 'Vorig project: %title' ); ?></div>
				<div class="project-nav__next"><?php next_post_link( '%link', 'Volgend project: %title' ); ?></div>
			</nav>
		</div>
	</article>

	<?php
endwhile;

get_footer();

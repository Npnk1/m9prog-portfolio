<?php
/**
 * Pagina "Over mij" (slug: over-mij).
 * WordPress kiest dit bestand automatisch via de templatehiërarchie: page-{slug}.php.
 *
 * @package NP_Portfolio
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class(); ?>>
		<header class="page-head">
			<div class="container">
				<h1 class="page-head__title"><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?>
					<p class="page-head__lead"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>
			</div>
		</header>

		<div class="container section section--tight">
			<div class="row gy-5">
				<div class="col-lg-8 entry-content">
					<?php the_content(); ?>
				</div>

				<aside class="col-lg-4" aria-label="Verder kijken">
					<div class="side-note">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'medium_large', array( 'class' => 'side-note__photo' ) ); ?>
						<?php endif; ?>
						<p>Benieuwd wat ik heb gemaakt?</p>
						<a class="btn btn-primary" href="<?php echo esc_url( np_projects_url() ); ?>">Bekijk mijn projecten</a>
						<p class="side-note__small">
							Of <a href="<?php echo esc_url( np_page_url( 'contact' ) ); ?>">neem direct contact op</a>.
						</p>
					</div>
				</aside>
			</div>
		</div>
	</article>
	<?php
endwhile;

get_footer();

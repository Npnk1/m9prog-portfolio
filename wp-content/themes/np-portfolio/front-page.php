<?php
/**
 * Homepagina. WordPress gebruikt dit bestand automatisch voor de voorpagina
 * (templatehiërarchie: front-page.php gaat voor page.php en index.php).
 * De introductietekst komt uit de inhoud van de pagina "Home".
 *
 * @package NP_Portfolio
 */

get_header();
?>

<section class="hero" aria-labelledby="hero-title">
	<div class="container">
		<h1 id="hero-title" class="hero__name"><?php bloginfo( 'name' ); ?></h1>
		<p class="hero__role"><?php bloginfo( 'description' ); ?></p>

		<div class="hero__intro">
			<?php
			while ( have_posts() ) :
				the_post();
				the_content();
			endwhile;
			?>
		</div>

		<div class="hero__actions">
			<a class="btn btn-accent btn-lg" href="<?php echo esc_url( np_projects_url() ); ?>">Bekijk mijn projecten</a>
			<a class="btn btn-outline-light btn-lg" href="<?php echo esc_url( np_page_url( 'contact' ) ); ?>">Neem contact op</a>
		</div>
	</div>
</section>

<section class="section" aria-labelledby="recent-title">
	<div class="container">
		<div class="section__head">
			<h2 id="recent-title">Recente projecten</h2>
			<a class="section__more" href="<?php echo esc_url( np_projects_url() ); ?>">Alle projecten</a>
		</div>

		<?php
		$np_recent = new WP_Query(
			array(
				'post_type'      => 'project',
				'posts_per_page' => 3,
				'no_found_rows'  => true,
			)
		);

		if ( $np_recent->have_posts() ) :
			?>
			<ul class="project-list">
				<?php
				while ( $np_recent->have_posts() ) :
					$np_recent->the_post();
					get_template_part( 'template-parts/project-row' );
				endwhile;
				wp_reset_postdata();
				?>
			</ul>
		<?php else : ?>
			<p class="empty-state">Er staan nog geen projecten online. Voeg ze toe in het dashboard onder Projecten.</p>
		<?php endif; ?>
	</div>
</section>

<?php
$np_about = get_page_by_path( 'over-mij' );
if ( $np_about ) :
	?>
	<section class="section section--about" aria-labelledby="about-title">
		<div class="container">
			<div class="row gy-3">
				<div class="col-lg-4">
					<h2 id="about-title">Over mij</h2>
				</div>
				<div class="col-lg-8">
					<p class="about-teaser"><?php echo esc_html( get_the_excerpt( $np_about ) ); ?></p>
					<a class="btn btn-primary" href="<?php echo esc_url( get_permalink( $np_about ) ); ?>">Lees meer over mij</a>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php
get_footer();

<?php
/**
 * Overzicht van alle projecten (/projecten/).
 *
 * @package NP_Portfolio
 */

get_header();
?>

<header class="page-head">
	<div class="container">
		<h1 class="page-head__title">Projecten</h1>
		<p class="page-head__lead">Schoolopdrachten en eigen projecten, met per project mijn rol, de technieken en wat het heeft opgeleverd.</p>
	</div>
</header>

<div class="container section section--tight">
	<?php if ( have_posts() ) : ?>
		<ul class="project-list">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/project-row' );
			endwhile;
			?>
		</ul>

		<?php
		the_posts_pagination(
			array(
				'prev_text'          => 'Vorige',
				'next_text'          => 'Volgende',
				'screen_reader_text' => 'Meer projecten',
			)
		);
		?>
	<?php else : ?>
		<p class="empty-state">Er staan nog geen projecten online.</p>
	<?php endif; ?>
</div>

<?php
get_footer();

<?php
/**
 * Eén blogbericht.
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
				<p class="page-head__lead"><?php echo esc_html( get_the_date() ); ?></p>
			</div>
		</header>
		<div class="container section section--tight">
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
		</div>
	</article>
	<?php
endwhile;

get_footer();

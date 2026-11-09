<?php
/**
 * Terugval-template. WordPress gebruikt dit bestand als er geen specifiekere template is
 * (bijvoorbeeld voor blogberichten of zoekresultaten).
 *
 * @package NP_Portfolio
 */

get_header();
?>

<header class="page-head">
	<div class="container">
		<h1 class="page-head__title">
			<?php
			if ( is_search() ) {
				printf( 'Zoekresultaten voor “%s”', esc_html( get_search_query() ) );
			} elseif ( is_archive() ) {
				echo esc_html( wp_strip_all_tags( get_the_archive_title() ) );
			} else {
				single_post_title();
			}
			?>
		</h1>
	</div>
</header>

<div class="container section section--tight">
	<?php if ( have_posts() ) : ?>
		<div class="post-list">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article <?php post_class( 'post-list__item' ); ?>>
					<h2 class="h3"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<p class="post-list__meta"><?php echo esc_html( get_the_date() ); ?></p>
					<?php the_excerpt(); ?>
				</article>
			<?php endwhile; ?>
		</div>
		<?php
		the_posts_pagination(
			array(
				'prev_text' => 'Vorige',
				'next_text' => 'Volgende',
			)
		);
		?>
	<?php else : ?>
		<p class="empty-state">Er is hier nog niets te zien.</p>
	<?php endif; ?>
</div>

<?php
get_footer();

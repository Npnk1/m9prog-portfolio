<?php
/**
 * Eén project in een lijst (homepage en projectoverzicht).
 * Wordt aangeroepen binnen de Loop met get_template_part().
 *
 * @package NP_Portfolio
 */

$np_tech = np_get_project_tech();
$np_year = get_post_meta( get_the_ID(), '_np_year', true );
?>
<li class="project-row">
	<a class="project-row__link" href="<?php the_permalink(); ?>">
		<div class="project-row__media">
			<?php
			if ( has_post_thumbnail() ) {
				// Leeg alt-attribuut: de titel naast de afbeelding beschrijft de link al.
				the_post_thumbnail(
					'np-project',
					array(
						'alt'     => '',
						'loading' => 'lazy',
					)
				);
			}
			?>
		</div>
		<div class="project-row__body">
			<h3 class="project-row__title"><?php the_title(); ?></h3>
			<?php if ( has_excerpt() ) : ?>
				<p class="project-row__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>
			<?php if ( $np_tech ) : ?>
				<p class="project-row__tech"><?php echo esc_html( implode( ', ', $np_tech ) ); ?></p>
			<?php endif; ?>
		</div>
		<?php if ( $np_year ) : ?>
			<p class="project-row__year"><?php echo esc_html( $np_year ); ?></p>
		<?php endif; ?>
	</a>
</li>

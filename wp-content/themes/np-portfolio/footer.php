<?php
/**
 * Footer: oproep tot contact, profielen en copyright.
 *
 * @package NP_Portfolio
 */

?>
</main>

<footer class="site-footer">
	<div class="container">
		<div class="row gy-4 align-items-end">
			<div class="col-lg-7">
				<?php if ( ! is_page( 'contact' ) ) : ?>
					<p class="site-footer__lead">
						Heb je een stageplek of een project waar ik aan kan bijdragen?
						<a href="<?php echo esc_url( np_page_url( 'contact' ) ); ?>">Stuur me een bericht</a>.
					</p>
				<?php endif; ?>
			</div>
			<div class="col-lg-5 text-lg-end">
				<ul class="site-footer__links">
					<?php foreach ( np_profile_links() as $label => $url ) : ?>
						<li><a href="<?php echo esc_url( $url ); ?>" rel="me"><?php echo esc_html( $label ); ?></a></li>
					<?php endforeach; ?>
					<li><a href="<?php echo esc_url( np_projects_url() ); ?>">Projecten</a></li>
				</ul>
				<p class="site-footer__copy">&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></p>
			</div>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>

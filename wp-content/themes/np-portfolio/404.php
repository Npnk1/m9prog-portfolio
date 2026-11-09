<?php
/**
 * Pagina niet gevonden.
 *
 * @package NP_Portfolio
 */

get_header();
?>

<header class="page-head">
	<div class="container">
		<h1 class="page-head__title">Deze pagina bestaat niet</h1>
		<p class="page-head__lead">De link is misschien verouderd of er zit een typfout in het adres.</p>
	</div>
</header>

<div class="container section section--tight">
	<a class="btn btn-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">Naar de homepage</a>
	<a class="btn btn-outline-primary ms-2" href="<?php echo esc_url( np_projects_url() ); ?>">Bekijk projecten</a>
</div>

<?php
get_footer();

<?php
/**
 * Header: <head>, skip-link en hoofdnavigatie.
 *
 * @package NP_Portfolio
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#inhoud">Direct naar de inhoud</a>

<header class="site-header">
	<nav class="navbar navbar-expand-md" aria-label="Hoofdmenu">
		<div class="container">
			<a class="site-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php bloginfo( 'name' ); ?>
			</a>

			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#hoofdmenu" aria-controls="hoofdmenu" aria-expanded="false" aria-label="Menu openen">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="hoofdmenu">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'navbar-nav ms-auto',
						'depth'          => 1,
						'fallback_cb'    => 'np_fallback_menu',
					)
				);
				?>
			</div>
		</div>
	</nav>
</header>

<main id="inhoud" class="site-main" tabindex="-1">

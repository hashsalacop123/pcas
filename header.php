<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php wp_title('|', true, 'right'); bloginfo('name'); ?></title>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header id="top" class="hero site-header">
	<?php
	$top_banner = function_exists('get_field') ? get_field('top_banner', 'option') : '';
	if ($top_banner) : ?>
		<div class="top-strip"><?php echo wp_kses_post($top_banner); ?></div>
	<?php endif; ?>
	<nav class="nav" aria-label="Primary navigation">
		<div class="nav-inner">
			<a class="brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="PCAS home">
				<?php
				$logo = function_exists('get_field') ? get_field('logo', 'option') : false;
				if (is_array($logo) && !empty($logo['url'])) : ?>
					<img class="brand-logo" src="<?php echo esc_url($logo['url']); ?>" alt="<?php echo esc_attr($logo['alt'] ?? 'Pacific Center for Advanced Studies'); ?>">
				<?php else : ?>
					<img class="brand-logo" src="<?php echo esc_url(get_template_directory_uri() . '/img/logo.png'); ?>" alt="Pacific Center for Advanced Studies">
				<?php endif; ?>
			</a>
			<button class="nav-toggle" type="button" aria-expanded="false" aria-controls="pcas-primary-menu">
				<span class="nav-toggle-icon" aria-hidden="true"><i></i><i></i><i></i></span>
				<span>Menu</span>
			</button>
			<?php
			wp_nav_menu(array(
				'theme_location' => 'main',
				'menu'           => 'main',
				'container'      => false,
				'menu_id'        => 'pcas-primary-menu',
				'menu_class'     => 'nav-links',
				'depth'          => 1,
				'fallback_cb'    => false,
			));
			?>
		</div>
	</nav>
	<nav class="navigation-bottom" aria-label="More about PCAS">
		<div class="container">
			<ul class="bottom-list">
				<li><a href="<?php echo esc_url(home_url('/about-us/')); ?>">About PCAS</a></li>
				<li><a href="<?php echo esc_url(home_url('/faculty/')); ?>">Faculty</a></li>
				<li><a href="<?php echo esc_url(home_url('/gallery/')); ?>">Gallery</a></li>
				<li><a href="<?php echo esc_url(home_url('/contact/')); ?>">Contact</a></li>
			</ul>
		</div>
	</nav>
</header>

<?php
/**
 * Template Name: Gallery PCAS
 * Description: Branded gallery page for Pacific Center for Advanced Studies.
 */

get_header();

$facilities = get_field('gallery_facilities');
$trainors = get_field('trainors');
$facilities = is_array($facilities) ? $facilities : array();
$trainors = is_array($trainors) ? $trainors : array();

/** Render an ACF gallery image array. */
function pcas_render_gallery_images($images, $group) {
	if (empty($images)) {
		echo '<p class="pcas-gallery-empty">Photos will appear here as soon as they are added to this gallery.</p>';
		return;
	}

	echo '<div class="pcas-gallery-grid">';
	foreach ($images as $image) {
		if (empty($image['url'])) {
			continue;
		}
		$url = $image['url'];
		$thumb = !empty($image['sizes']['large']) ? $image['sizes']['large'] : $url;
		$alt = isset($image['alt']) ? $image['alt'] : '';
		$caption = !empty($image['caption']) ? $image['caption'] : '';
		$label = $caption ? $caption : ($alt ? $alt : 'View gallery photo');
		echo '<a class="pcas-gallery-item" href="' . esc_url($url) . '" data-gallery="' . esc_attr($group) . '" aria-label="' . esc_attr($label) . '">';
		echo '<img src="' . esc_url($thumb) . '" alt="' . esc_attr($alt) . '" loading="lazy" decoding="async">';
		echo '<span class="pcas-gallery-zoom" aria-hidden="true">↗</span>';
		if ($caption) {
			echo '<span class="pcas-gallery-caption">' . esc_html($caption) . '</span>';
		}
		echo '</a>';
	}
	echo '</div>';
}
?>
<main class="pcas-gallery">
	<section class="pcas-gallery-hero">
		<div class="pcas-gallery-wrap">
			<span class="pcas-gallery-kicker">Pacific Center for Advanced Studies</span>
			<h1>Life at PCAS</h1>
			<p>A look inside our learning spaces, facilities, and the people who share their knowledge with our students.</p>
			<div class="pcas-gallery-stats">
				<span><strong><?php echo esc_html(count($facilities)); ?></strong> Facility photos</span>
				<span><strong><?php echo esc_html(count($trainors)); ?></strong> Trainer photos</span>
			</div>
		</div>
	</section>

	<div class="pcas-gallery-body">
		<section class="pcas-gallery-section pcas-gallery-wrap" aria-labelledby="pcas-facilities-title">
			<div class="pcas-gallery-heading"><div><span class="pcas-gallery-eyebrow">Our campus</span><h2 id="pcas-facilities-title">Facilities &amp; learning spaces</h2></div><span class="pcas-gallery-count"><?php echo esc_html(count($facilities)); ?> photos</span></div>
			<?php pcas_render_gallery_images($facilities, 'facilities'); ?>
		</section>

		<section class="pcas-gallery-section pcas-gallery-wrap" aria-labelledby="pcas-trainers-title">
			<div class="pcas-gallery-heading"><div><span class="pcas-gallery-eyebrow">Meet our educators</span><h2 id="pcas-trainers-title">Our trainers</h2></div><span class="pcas-gallery-count"><?php echo esc_html(count($trainors)); ?> photos</span></div>
			<?php pcas_render_gallery_images($trainors, 'trainers'); ?>
		</section>
	</div>
</main>
<?php get_footer(); ?>

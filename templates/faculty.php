<?php
/**
 * Template Name: Faculty
 * Description: Meet the faculty and professionals at PCAS.
 */

get_header();
$upload_base = wp_upload_dir()['baseurl'];
$faculty = array(
	array(
		'name' => 'William “Bill” Owensby',
		'role' => 'Head Consultant, Mortuary Service',
		'image' => 'bill-owensby-profile1-scaled.jpg',
		'alt' => 'William Bill Owensby',
		'credentials' => array(
			'Retired Mortuary Officer, U.S. Department of Defense, with 35 years of service',
			'U.S. Air Force Medal of Valor awardee for mortuary work during disaster response',
			'Former Director, Veterans Affairs Nashville National Cemetery',
			'Former Director, Leavenworth National Cemetery',
			'Funeral Director and Licensed Embalmer; Gupton-Jones College of Mortuary Science',
		),
	),
	array(
		'name' => 'Sarah Dychangco-Reyes',
		'role' => 'Vice President for Finance, Cosmopolitan Funeral Home Inc.',
		'image' => 'sarah-dychangco-profile-scaled.jpg',
		'alt' => 'Sarah Dychangco-Reyes',
		'credentials' => array(
			'Bachelor of Science in Business Management, University of Cebu',
			'Associate Degree in Mortuary Science, Cypress College, California, U.S.A.',
			'Department of Health-accredited trainer for embalmers',
			'Licensed embalmer',
		),
	),
);
?>
<main class="pcas-faculty">
	<section class="pcas-faculty-hero" style="--faculty-hero-image: url('<?php echo esc_url($upload_base . '/2026/10/sl1.jpg'); ?>')">
		<div class="pcas-faculty-wrap pcas-faculty-hero-inner">
			<div class="pcas-faculty-hero-copy">
				<span class="pcas-faculty-kicker">Pacific Center for Advanced Studies</span>
				<h1>Learn from professionals.</h1>
				<p>Meet the experienced educators and industry professionals who bring practical knowledge to PCAS training.</p>
				<div class="pcas-faculty-actions"><a class="pcas-faculty-button pcas-faculty-button-gold" href="<?php echo esc_url(home_url('/registration/')); ?>">Start enrollment <span aria-hidden="true">→</span></a><a class="pcas-faculty-button pcas-faculty-button-outline" href="#faculty-members">Meet the faculty</a></div>
			</div>
			<div class="pcas-faculty-hero-badge" aria-hidden="true"><span>PCAS</span><small>Learn · Practice · Serve</small></div>
		</div>
	</section>

	<section class="pcas-faculty-directory" id="faculty-members">
		<div class="pcas-faculty-wrap">
			<div class="pcas-faculty-heading">
				<div><span class="pcas-faculty-eyebrow">Our people</span><h2>Experience that guides your learning.</h2></div>
				<p>Our faculty brings together professional education, leadership, and hands-on experience in mortuary service.</p>
			</div>
			<div class="pcas-faculty-grid">
				<?php foreach ($faculty as $person) : ?>
					<article class="pcas-faculty-card">
						<div class="pcas-faculty-profile">
							<img src="<?php echo esc_url($upload_base . '/2026/10/' . $person['image']); ?>" alt="<?php echo esc_attr($person['alt']); ?>" loading="lazy" decoding="async">
							<div class="pcas-faculty-profile-copy"><span class="pcas-faculty-eyebrow">Faculty &amp; leadership</span><h3><?php echo esc_html($person['name']); ?></h3><p><?php echo esc_html($person['role']); ?></p></div>
						</div>
						<div class="pcas-faculty-credentials"><h4>Experience &amp; credentials</h4><ul><?php foreach ($person['credentials'] as $credential) : ?><li><?php echo esc_html($credential); ?></li><?php endforeach; ?></ul></div>
					</article>
				<?php endforeach; ?>
			</div>
			<div class="pcas-faculty-cta"><div><strong>Ready to take the next step?</strong><span>Talk with our team about PCAS programs and enrollment.</span></div><a class="pcas-faculty-button pcas-faculty-button-gold" href="<?php echo esc_url(home_url('/contact/')); ?>">Contact PCAS <span aria-hidden="true">→</span></a></div>
		</div>
	</section>
</main>
<?php get_footer(); ?>

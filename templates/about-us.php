<?php
/**
 * Template Name: About us page
 * Description: About Pacific Center for Advanced Studies.
 */

get_header();
$upload_base = wp_upload_dir()['baseurl'];
$registration_url = home_url('/registration/');
$contact_url = home_url('/contact/');
?>
<main class="pcas-about">
	<section class="pcas-about-hero" style="--about-hero-image: url('<?php echo esc_url($upload_base . '/2026/10/sl1.jpg'); ?>')">
		<div class="pcas-about-wrap pcas-about-hero-inner">
			<div class="pcas-about-hero-copy">
				<span class="pcas-about-kicker">Pacific Center for Advanced Studies</span>
				<h1>Quality education for future professionals.</h1>
				<p>PCAS is a Department of Health accredited training institution through the Committee of Examiners for Undertakers and Embalmers. Our programs have produced topnotchers in written and oral examinations, reflecting our commitment to quality embalming education.</p>
				<div class="pcas-about-actions">
					<a class="pcas-about-button pcas-about-button-gold" href="<?php echo esc_url($registration_url); ?>">Start enrollment <span aria-hidden="true">→</span></a>
					<a class="pcas-about-button pcas-about-button-outline" href="#our-story">Discover PCAS</a>
				</div>
			</div>
			<div class="pcas-about-hero-badge"><span>PCAS</span><small>Learn · Practice · Serve</small></div>
			<div class="pcas-about-trust" aria-label="PCAS highlights">
				<div><strong>80</strong><span>seat classroom</span></div>
				<div><strong>Modern</strong><span>learning facilities</span></div>
				<div><strong>DOH</strong><span>accredited programs</span></div>
			</div>
		</div>
	</section>

	<section class="pcas-about-story" id="our-story">
		<div class="pcas-about-wrap">
			<div class="pcas-about-story-intro">
				<span class="pcas-about-eyebrow">Our story</span>
				<h2>Learning that helps people move forward.</h2>
				<p>We believe every success story is built on hard work, practice, and the right support. At PCAS, we create opportunities for students to grow their knowledge, build confidence, and reach their full potential.</p>
			</div>
			<div class="pcas-about-pillars">
				<article class="pcas-about-pillar">
					<span class="pcas-about-pillar-icon" aria-hidden="true"><i class="fa fa-users"></i></span>
					<span class="pcas-about-card-label">Our community</span>
					<h3>Our group</h3>
					<p>The Philippine Mortuary Association brings together mortuary owners and professionals across the Philippines. PCAS is a charter member and partner in providing education, training, and professional updates.</p>
				</article>
				<article class="pcas-about-pillar">
					<span class="pcas-about-pillar-icon" aria-hidden="true"><i class="fa fa-history"></i></span>
					<span class="pcas-about-card-label">Our roots</span>
					<h3>Our history</h3>
					<p>Originating in Cebu, PCAS is now based in Metro Manila. Our learning spaces include an air-conditioned, Wi-Fi enabled classroom, a mortuary library, and a conference room.</p>
				</article>
				<article class="pcas-about-pillar">
					<span class="pcas-about-pillar-icon" aria-hidden="true"><i class="fa fa-handshake-o"></i></span>
					<span class="pcas-about-card-label">Working together</span>
					<h3>Our partners</h3>
					<p>Our partnership with Cosmopolitan Memorial Chapels Inc. gives students access to practical learning in a modern embalming facility and supports a rich, hands-on education.</p>
				</article>
			</div>
		</div>
	</section>

	<section class="pcas-about-programs" id="programs">
		<div class="pcas-about-wrap">
			<div class="pcas-about-program-heading">
				<div><span class="pcas-about-eyebrow">Learn with purpose</span><h2>Programs built for your next step.</h2></div>
				<p>Explore professional training for licensure, continuing education, and international learners.</p>
			</div>
			<div class="pcas-about-program-grid">
				<article class="pcas-about-program-card">
					<div class="pcas-about-program-image"><img src="<?php echo esc_url($upload_base . '/2026/10/sl1.jpg'); ?>" alt="PCAS embalming licensure training" loading="lazy"></div>
					<div class="pcas-about-program-body"><span class="pcas-about-card-label">ELR · Licensure preparation</span><h3>Embalming Licensure Course</h3><p>This two-month course combines theoretical and practical sessions to prepare students for the Department of Health licensure examination for undertakers and embalmers.</p><a href="<?php echo esc_url($contact_url); ?>">Ask about this program <span aria-hidden="true">→</span></a></div>
				</article>
				<article class="pcas-about-program-card">
					<div class="pcas-about-program-image"><img src="<?php echo esc_url($upload_base . '/2026/10/sl2.jpg'); ?>" alt="Continuing education for embalmers at PCAS" loading="lazy"></div>
					<div class="pcas-about-program-body"><span class="pcas-about-card-label">CEE · Professional development</span><h3>Continuing Embalmers Education</h3><p>Build new skills and earn credited units for license renewal through topics such as:</p><ul><li>Team building for mortuary professionals</li><li>Mortuary marketing and financial management</li><li>Effective funeral directing and business operations</li><li>Infection control and embalming analysis</li><li>Restorative art and first aid management</li></ul><a href="<?php echo esc_url($contact_url); ?>">Ask about this program <span aria-hidden="true">→</span></a></div>
				</article>
				<article class="pcas-about-program-card">
					<div class="pcas-about-program-image"><img src="<?php echo esc_url($upload_base . '/2026/10/sl3.jpg'); ?>" alt="International students learning at PCAS" loading="lazy"></div>
					<div class="pcas-about-program-body"><span class="pcas-about-card-label">ISP · Flexible training</span><h3>International Student Program</h3><p>Students from neighboring countries can learn professional practices through an intensive two-week theoretical program. Customized programs are also available.</p><a href="<?php echo esc_url($contact_url); ?>">Ask about this program <span aria-hidden="true">→</span></a></div>
				</article>
			</div>
			<div class="pcas-about-bottom-cta"><div><strong>Ready to learn more?</strong><span>Talk with our team about the right program for you.</span></div><a class="pcas-about-button pcas-about-button-gold" href="<?php echo esc_url($contact_url); ?>">Contact PCAS <span aria-hidden="true">→</span></a></div>
		</div>
	</section>
</main>
<?php get_footer(); ?>

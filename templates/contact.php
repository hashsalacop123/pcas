<?php
/**
 * Template Name: Contact PCAS
 * Description: Branded contact page for Pacific Center for Advanced Studies.
 */

get_header();

$contact_email   = get_field('contact_email', 'option');
$contact_phone   = get_field('contact_phone', 'option');
$contact_address = get_field('contact_address', 'option');
?>
<main class="pcas-contact">
	<section class="pcas-contact-hero">
		<div class="pcas-contact-wrap">
			<div class="pcas-contact-hero-copy">
				<span class="pcas-contact-kicker">Pacific Center for Advanced Studies</span>
				<h1>Let’s talk about your future.</h1>
				<p>Have a question about our programs, admissions, or training? Our team is ready to help you take the next step.</p>
				<a class="pcas-contact-button" href="#contact-details">Get in touch <span aria-hidden="true">↓</span></a>
			</div>
			<div class="pcas-contact-seal" aria-hidden="true"><span>PCAS</span><small>Learn · Practice · Serve</small></div>
		</div>
	</section>

	<section class="pcas-contact-main" id="contact-details">
		<div class="pcas-contact-wrap pcas-contact-grid">
			<div class="pcas-contact-intro">
				<span class="pcas-contact-eyebrow">We’re here to help</span>
				<h2>Connect with our team</h2>
				<p>Whether you’re exploring a course or ready to enroll, reach out and we’ll point you in the right direction.</p>
				<div class="pcas-contact-details">
					<?php if ($contact_email) : ?>
						<a class="pcas-contact-detail" href="mailto:<?php echo esc_attr(antispambot($contact_email)); ?>"><span class="pcas-contact-icon" aria-hidden="true">✉</span><span><small>Email</small><strong><?php echo esc_html(antispambot($contact_email)); ?></strong></span></a>
					<?php endif; ?>
					<?php if ($contact_phone) : ?>
						<a class="pcas-contact-detail" href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $contact_phone)); ?>"><span class="pcas-contact-icon" aria-hidden="true">☎</span><span><small>Call us</small><strong><?php echo esc_html($contact_phone); ?></strong></span></a>
					<?php endif; ?>
					<?php if ($contact_address) : ?>
						<div class="pcas-contact-detail"><span class="pcas-contact-icon" aria-hidden="true">⌖</span><span><small>Visit us</small><strong><?php echo nl2br(esc_html($contact_address)); ?></strong></span></div>
					<?php endif; ?>
					<?php if (!$contact_email && !$contact_phone && !$contact_address) : ?>
						<p class="pcas-contact-note">Contact details can be added in the site’s ACF Options settings.</p>
					<?php endif; ?>
				</div>
				<div class="pcas-contact-note"><strong>Admissions inquiry?</strong><br>Tell us which program you’re interested in and we’ll help with the next steps.</div>
			</div>

			<div class="pcas-contact-form-card">
				<span class="pcas-contact-eyebrow">Send an inquiry</span>
				<h2>How can we help?</h2>
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<?php if (trim(get_the_content())) : ?>
						<div class="pcas-contact-form-content"><?php the_content(); ?></div>
					<?php else : ?>
						<p class="pcas-contact-note">Add your contact form here using a form plugin shortcode in this page’s editor.</p>
					<?php endif; ?>
				<?php endwhile; endif; ?>
			</div>
		</div>
	</section>
</main>
<?php get_footer(); ?>

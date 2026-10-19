<?php
/**
 * Template Name: Registration
 * Description: Branded PCAS student account registration page.
 */

get_header();
$registration_errors = get_transient('hash_registration_errors_' . wp_get_session_token());
if ($registration_errors) {
    delete_transient('hash_registration_errors_' . wp_get_session_token());
}
?>
<main class="pcas-account-page pcas-register-page">
	<section class="pcas-account-aside">
		<div class="pcas-account-aside-inner">
			<span class="pcas-account-kicker">Pacific Center for Advanced Studies</span>
			<div class="pcas-account-mark" aria-hidden="true">PCAS</div>
			<h1>Build your next chapter.</h1>
			<p>Create your student account to get started with PCAS training and learning resources.</p>
			<div class="pcas-account-aside-note"><span aria-hidden="true">✦</span> Quality education for future professionals</div>
		</div>
	</section>
	<section class="pcas-account-content">
		<div class="pcas-account-card">
			<span class="pcas-account-eyebrow">Join the PCAS community</span>
			<h2>Create your account</h2>
			<p class="pcas-account-lead">Fill in your details below to set up your account.</p>

			<?php if (!empty($registration_errors) && is_array($registration_errors)) : ?>
				<div class="pcas-account-alert" role="alert">
					<strong>Please review the following:</strong>
					<ul><?php foreach ($registration_errors as $error) : ?><li><?php echo esc_html($error); ?></li><?php endforeach; ?></ul>
				</div>
			<?php endif; ?>

			<form class="pcas-account-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
				<input type="hidden" name="action" value="hash_frontend_registration">
				<?php wp_nonce_field('hash_frontend_registration', 'hash_registration_nonce'); ?>
				<div class="pcas-account-fields pcas-account-fields-two">
					<div class="pcas-account-field"><label for="first_name">First name</label><input type="text" id="first_name" name="first_name" autocomplete="given-name" value="<?php echo esc_attr(wp_unslash($_POST['first_name'] ?? '')); ?>"></div>
					<div class="pcas-account-field"><label for="last_name">Last name</label><input type="text" id="last_name" name="last_name" autocomplete="family-name" value="<?php echo esc_attr(wp_unslash($_POST['last_name'] ?? '')); ?>"></div>
				</div>
				<div class="pcas-account-field"><label for="username">Username</label><input type="text" id="username" name="username" autocomplete="username" required value="<?php echo esc_attr(wp_unslash($_POST['username'] ?? '')); ?>"></div>
				<div class="pcas-account-field"><label for="email">Email address</label><input type="email" id="email" name="email" autocomplete="email" required value="<?php echo esc_attr(wp_unslash($_POST['email'] ?? '')); ?>"></div>
				<div class="pcas-account-fields pcas-account-fields-two">
					<div class="pcas-account-field"><label for="password">Password</label><input type="password" id="password" name="password" autocomplete="new-password" minlength="8" required><small>Use at least 8 characters.</small></div>
					<div class="pcas-account-field"><label for="confirm_password">Confirm password</label><input type="password" id="confirm_password" name="confirm_password" autocomplete="new-password" minlength="8" required></div>
				</div>
				<button class="pcas-account-submit" type="submit">Create account <span aria-hidden="true">→</span></button>
			</form>
			<p class="pcas-account-switch">Already have an account? <a href="<?php echo esc_url(home_url('/login/')); ?>">Log in</a></p>
		</div>
	</section>
</main>
<?php get_footer(); ?>

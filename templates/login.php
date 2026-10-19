<?php
/**
 * Template Name: Login
 * Description: Branded PCAS student account login page.
 */

get_header();
$login_error = isset($_GET['login_error']) ? sanitize_key(wp_unslash($_GET['login_error'])) : '';
?>
<main class="pcas-account-page pcas-login-page">
	<section class="pcas-account-aside">
		<div class="pcas-account-aside-inner">
			<span class="pcas-account-kicker">Pacific Center for Advanced Studies</span>
			<div class="pcas-account-mark" aria-hidden="true">PCAS</div>
			<h1>Your learning journey continues.</h1>
			<p>Sign in to access your PCAS account and continue building the skills that move your future forward.</p>
			<div class="pcas-account-aside-note"><span aria-hidden="true">✦</span> Learn · Practice · Serve</div>
		</div>
	</section>
	<section class="pcas-account-content">
		<div class="pcas-account-card pcas-login-card">
			<span class="pcas-account-eyebrow">Welcome back</span>
			<h2>Log in to PCAS</h2>
			<p class="pcas-account-lead">Enter your username or email and password to continue.</p>

			<?php if ($login_error === 'required') : ?>
				<div class="pcas-account-alert" role="alert">Please enter your username and password.</div>
			<?php elseif ($login_error === 'invalid') : ?>
				<div class="pcas-account-alert" role="alert">We couldn’t sign you in with those details. Check your username or email and password, then try again.</div>
			<?php endif; ?>

			<form class="pcas-account-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
				<input type="hidden" name="action" value="hash_frontend_login">
				<?php wp_nonce_field('hash_frontend_login', 'hash_login_nonce'); ?>
				<div class="pcas-account-field"><label for="login_username">Username or email</label><input type="text" id="login_username" name="username" autocomplete="username" required value="<?php echo esc_attr(wp_unslash($_POST['username'] ?? '')); ?>"></div>
				<div class="pcas-account-field"><label for="login_password">Password</label><input type="password" id="login_password" name="password" autocomplete="current-password" required></div>
				<div class="pcas-account-form-meta"><label class="pcas-account-remember" for="remember"><input type="checkbox" id="remember" name="remember" value="1"><span>Remember me</span></label><a href="<?php echo esc_url(wp_lostpassword_url()); ?>">Forgot password?</a></div>
				<button class="pcas-account-submit" type="submit">Log in <span aria-hidden="true">→</span></button>
			</form>
			<p class="pcas-account-switch">New to PCAS? <a href="<?php echo esc_url(home_url('/registration/')); ?>">Create an account</a></p>
		</div>
	</section>
</main>
<?php get_footer(); ?>

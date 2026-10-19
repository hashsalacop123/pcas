<?php
/** Student dashboard for enrolled courses, progress, and account details. */
// Template Name: My Account
get_header();

$browse_url = home_url('/browse-lessons/');
$login_url = home_url('/login/');
$register_url = home_url('/registration/');
$profile_status = isset($_GET['profile']) ? sanitize_key(wp_unslash($_GET['profile'])) : '';
?>
<main class="pcas-account-dashboard">
	<?php if (!is_user_logged_in()) : ?>
		<section class="pcas-account-welcome">
			<div class="pcas-account-wrap">
				<span class="pcas-dashboard-kicker">PCAS Student Portal</span>
				<h1>Your learning, all in one place.</h1>
				<p>Sign in to continue your courses, review your progress, and manage your student profile.</p>
				<div class="pcas-dashboard-actions"><a class="pcas-dashboard-button" href="<?php echo esc_url($login_url); ?>">Log in</a><a class="pcas-dashboard-button is-light" href="<?php echo esc_url($register_url); ?>">Create an account</a></div>
			</div>
		</section>
	<?php else :
		$user = wp_get_current_user();
		$user_id = (int) $user->ID;
		$enrolled_ids = array_values(array_unique(array_filter(array_map('absint', (array) get_user_meta($user_id, 'pcas_enrolled_courses', true)))));
		$completed_ids = array_values(array_unique(array_filter(array_map('absint', (array) get_user_meta($user_id, 'pcas_completed_lessons', true)))));
		$enrolled_courses = $enrolled_ids ? get_posts(array('post_type' => 'course', 'post_status' => 'publish', 'posts_per_page' => -1, 'post__in' => $enrolled_ids, 'orderby' => 'post__in')) : array();
		$subjects = get_posts(array('post_type' => 'subject', 'post_status' => 'publish', 'posts_per_page' => -1, 'orderby' => 'menu_order title', 'order' => 'ASC'));
		$modules = get_posts(array('post_type' => 'module', 'post_status' => 'publish', 'posts_per_page' => -1, 'orderby' => 'menu_order title', 'order' => 'ASC'));
		$lessons = get_posts(array('post_type' => 'lesson', 'post_status' => 'publish', 'posts_per_page' => -1, 'orderby' => 'menu_order title', 'order' => 'ASC'));
		$parent_id = static function ($details, $key) {
			if (!is_array($details) || empty($details[$key])) return 0;
			$value = $details[$key];
			return $value instanceof WP_Post ? (int) $value->ID : absint($value);
		};
		$course_progress = array();
		$relevant_completed_total = 0;
		foreach ($enrolled_courses as $course) {
			$course_subject_ids = array();
			foreach ($subjects as $subject) {
				$details = function_exists('get_field') ? get_field('subject_details', $subject->ID) : array();
				if ($parent_id($details, 'parent_course') === (int) $course->ID) $course_subject_ids[] = (int) $subject->ID;
			}
			$course_module_ids = array();
			foreach ($modules as $module) {
				$details = function_exists('get_field') ? get_field('module_details', $module->ID) : array();
				if (in_array($parent_id($details, 'parent_subject'), $course_subject_ids, true)) $course_module_ids[] = (int) $module->ID;
			}
			$course_lesson_ids = array();
			foreach ($lessons as $lesson) {
				$details = function_exists('get_field') ? get_field('lesson_details', $lesson->ID) : array();
				if (in_array($parent_id($details, 'parent_module'), $course_module_ids, true)) $course_lesson_ids[] = (int) $lesson->ID;
			}
			$done_ids = array_values(array_intersect($course_lesson_ids, $completed_ids));
			$relevant_completed_total += count($done_ids);
			$next_lesson = null;
			foreach ($course_lesson_ids as $lesson_id) {
				if (!in_array($lesson_id, $completed_ids, true)) { $next_lesson = get_post($lesson_id); break; }
			}
			if (!$next_lesson && $course_lesson_ids) $next_lesson = get_post($course_lesson_ids[0]);
			$course_progress[(int) $course->ID] = array('total' => count($course_lesson_ids), 'done' => count($done_ids), 'next' => $next_lesson);
		}
		$first_name = $user->first_name ?: $user->display_name;
		?>
		<section class="pcas-account-welcome">
			<div class="pcas-account-wrap pcas-account-welcome-row">
				<div><span class="pcas-dashboard-kicker">PCAS Student Portal</span><h1>Welcome back, <?php echo esc_html($first_name); ?>.</h1><p>Pick up where you left off or explore another course.</p></div>
				<a class="pcas-dashboard-logout" href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>">Log out <span aria-hidden="true">↗</span></a>
			</div>
		</section>
		<div class="pcas-account-wrap pcas-account-body">
			<?php if ('updated' === $profile_status) : ?><div class="pcas-dashboard-notice" role="status">Your profile has been updated.</div><?php elseif ('invalid' === $profile_status) : ?><div class="pcas-dashboard-notice is-error" role="alert">Please enter a valid email address that is not already in use.</div><?php endif; ?>
			<section class="pcas-account-summary" aria-label="Learning summary">
				<div><span>Enrolled courses</span><strong><?php echo esc_html(number_format_i18n(count($enrolled_courses))); ?></strong></div>
				<div><span>Lessons completed</span><strong><?php echo esc_html(number_format_i18n($relevant_completed_total)); ?></strong></div>
				<div><span>Account email</span><strong class="pcas-summary-email"><?php echo esc_html($user->user_email); ?></strong></div>
			</section>
			<section class="pcas-dashboard-section" id="my-courses">
				<header class="pcas-dashboard-section-head"><div><span class="pcas-dashboard-kicker">Your learning path</span><h2>My courses</h2><p>Continue a lesson or review your course outline and progress.</p></div><a class="pcas-dashboard-text-link" href="<?php echo esc_url($browse_url); ?>">Browse all courses <span aria-hidden="true">→</span></a></header>
				<?php if ($enrolled_courses) : ?><div class="pcas-dashboard-course-grid">
					<?php foreach ($enrolled_courses as $course) : $progress = $course_progress[(int) $course->ID]; $percent = $progress['total'] ? (int) round(100 * $progress['done'] / $progress['total']) : 0; ?>
						<article class="pcas-dashboard-course">
							<span class="pcas-dashboard-course-kicker">PCAS course</span><h3><?php echo esc_html(get_the_title($course)); ?></h3>
							<div class="pcas-dashboard-progress-label"><span><?php echo esc_html($progress['done']); ?> of <?php echo esc_html($progress['total']); ?> lessons complete</span><strong><?php echo esc_html($percent); ?>%</strong></div>
							<progress value="<?php echo esc_attr($progress['done']); ?>" max="<?php echo esc_attr(max(1, $progress['total'])); ?>"><?php echo esc_html($percent); ?>%</progress>
							<div class="pcas-dashboard-course-actions"><a class="pcas-dashboard-button" href="<?php echo esc_url($progress['next'] ? get_permalink($progress['next']) : get_permalink($course)); ?>"><?php echo $progress['total'] && $progress['done'] === $progress['total'] ? 'Review course' : ($progress['done'] ? 'Continue learning' : 'Start learning'); ?> <span aria-hidden="true">→</span></a><a class="pcas-dashboard-course-outline" href="<?php echo esc_url(get_permalink($course)); ?>">Course outline</a></div>
						</article>
					<?php endforeach; ?>
				</div><?php else : ?><div class="pcas-dashboard-empty"><h3>Your learning starts here.</h3><p>You haven’t enrolled in a course yet. Browse the course library to find a program that fits your goals.</p><a class="pcas-dashboard-button" href="<?php echo esc_url($browse_url); ?>">Explore courses <span aria-hidden="true">→</span></a></div><?php endif; ?>
			</section>
			<section class="pcas-dashboard-section pcas-profile-section" id="profile">
				<header class="pcas-dashboard-section-head"><div><span class="pcas-dashboard-kicker">Account details</span><h2>Student profile</h2><p>Keep your name and email up to date for PCAS account messages.</p></div><a class="pcas-dashboard-text-link" href="<?php echo esc_url(wp_lostpassword_url($login_url)); ?>">Change password <span aria-hidden="true">→</span></a></header>
				<form class="pcas-profile-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
					<input type="hidden" name="action" value="pcas_update_profile"><?php wp_nonce_field('pcas_update_profile', 'pcas_profile_nonce'); ?>
					<label>First name<input type="text" name="first_name" autocomplete="given-name" value="<?php echo esc_attr($user->first_name); ?>"></label>
					<label>Last name<input type="text" name="last_name" autocomplete="family-name" value="<?php echo esc_attr($user->last_name); ?>"></label>
					<label class="pcas-profile-email">Email address<input type="email" name="email" autocomplete="email" required value="<?php echo esc_attr($user->user_email); ?>"></label>
					<button class="pcas-dashboard-button" type="submit">Save profile <span aria-hidden="true">✓</span></button>
				</form>
			</section>
		</div>
	<?php endif; ?>
</main>
<?php get_footer(); ?>

<?php
/**
 * Template Name: Browse Lessons
 * Description: Browse published PCAS courses and their lesson outlines.
 */

get_header();

$enrollment_status = isset($_GET['enrollment']) ? sanitize_key(wp_unslash($_GET['enrollment'])) : '';
$courses = get_posts(array(
	'post_type'      => 'course',
	'post_status'    => 'publish',
	'posts_per_page' => -1,
	'orderby'        => 'title',
	'order'          => 'ASC',
));
$subjects = get_posts(array(
	'post_type'      => 'subject',
	'post_status'    => 'publish',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order title',
	'order'          => 'ASC',
));
$modules = get_posts(array(
	'post_type'      => 'module',
	'post_status'    => 'publish',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order title',
	'order'          => 'ASC',
));
$lessons = get_posts(array(
	'post_type'      => 'lesson',
	'post_status'    => 'publish',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order title',
	'order'          => 'ASC',
));

$enrolled_courses = is_user_logged_in() ? (array) get_user_meta(get_current_user_id(), 'pcas_enrolled_courses', true) : array();
$enrolled_courses = array_map('absint', $enrolled_courses);

$get_parent_id = static function ($details, $key) {
	if (!is_array($details) || empty($details[$key])) {
		return 0;
	}
	$parent = $details[$key];
	return $parent instanceof WP_Post ? (int) $parent->ID : absint($parent);
};
?>
<main class="pcas-browse-page">
	<section class="pcas-browse-hero">
		<div class="pcas-browse-wrap">
			<span class="pcas-browse-kicker">PCAS Learning</span>
			<h1>Find the lesson that moves you forward.</h1>
			<p>Explore our published courses and preview the subjects, modules, and lessons inside. Enroll in a course to keep its learning path together in your student account.</p>
			<div class="pcas-browse-search-wrap">
				<label class="screen-reader-text" for="pcas-course-search">Search courses and lessons</label>
				<input id="pcas-course-search" class="pcas-browse-search" type="search" placeholder="Search courses or lessons" autocomplete="off">
				<span aria-hidden="true">⌕</span>
			</div>
			<div class="pcas-browse-hero-meta"><span><?php echo esc_html(count($courses)); ?> published courses</span><span>Self-paced learning paths</span></div>
		</div>
	</section>

	<section class="pcas-browse-content pcas-browse-wrap" aria-labelledby="pcas-browse-heading">
		<div class="pcas-browse-section-heading">
			<div><span class="pcas-browse-kicker">Course library</span><h2 id="pcas-browse-heading">Browse learning paths</h2></div>
			<p>Choose a course to see its lesson outline and enrollment status.</p>
		</div>

		<?php if ('success' === $enrollment_status) : ?>
			<div class="pcas-browse-notice" role="status">You’re enrolled. Your course is now saved to your student account.</div>
		<?php elseif ('already' === $enrollment_status) : ?>
			<div class="pcas-browse-notice" role="status">You’re already enrolled in that course.</div>
		<?php elseif ('error' === $enrollment_status) : ?>
			<div class="pcas-browse-notice pcas-browse-notice-error" role="alert">We couldn’t complete enrollment. Please sign in and try again.</div>
		<?php endif; ?>

		<?php if ($courses) : ?>
			<div class="pcas-course-grid" id="pcas-course-grid">
				<?php foreach ($courses as $course) :
					$course_details = function_exists('get_field') ? get_field('course_details', $course->ID) : array();
					$duration = is_array($course_details) ? ($course_details['estimated_duration'] ?? '') : '';
					$level = is_array($course_details) ? ($course_details['course_level'] ?? '') : '';
					$course_subjects = array();
					foreach ($subjects as $subject) {
						$subject_details = function_exists('get_field') ? get_field('subject_details', $subject->ID) : array();
						if ($get_parent_id($subject_details, 'parent_course') === (int) $course->ID) {
							$course_subjects[] = array($subject, $subject_details);
						}
					}
					$lesson_count = 0;
					foreach ($course_subjects as $subject_pair) {
						foreach ($modules as $module) {
							$module_details = function_exists('get_field') ? get_field('module_details', $module->ID) : array();
							if ($get_parent_id($module_details, 'parent_subject') !== (int) $subject_pair[0]->ID) continue;
							foreach ($lessons as $lesson) {
								$lesson_details = function_exists('get_field') ? get_field('lesson_details', $lesson->ID) : array();
								if ($get_parent_id($lesson_details, 'parent_module') === (int) $module->ID) $lesson_count++;
							}
						}
					}
					$search_text = strtolower($course->post_title . ' ' . wp_strip_all_tags($course->post_content));
					foreach ($course_subjects as $subject_pair) {
						$search_text .= ' ' . strtolower($subject_pair[0]->post_title);
						foreach ($modules as $module) {
							$module_details = function_exists('get_field') ? get_field('module_details', $module->ID) : array();
							if ($get_parent_id($module_details, 'parent_subject') !== (int) $subject_pair[0]->ID) continue;
							$search_text .= ' ' . strtolower($module->post_title);
							foreach ($lessons as $lesson) {
								$lesson_details = function_exists('get_field') ? get_field('lesson_details', $lesson->ID) : array();
								if ($get_parent_id($lesson_details, 'parent_module') === (int) $module->ID) $search_text .= ' ' . strtolower($lesson->post_title);
							}
						}
					}
					$search_text = trim(preg_replace('/\s+/', ' ', $search_text));
					$is_enrolled = in_array((int) $course->ID, $enrolled_courses, true);
					$thumbnail = get_the_post_thumbnail_url($course->ID, 'large');
				?>
				<article class="pcas-course-card" data-search="<?php echo esc_attr($search_text); ?>">
					<div class="pcas-course-card-top"<?php if ($thumbnail) : ?> style="--pcas-course-image:url('<?php echo esc_url($thumbnail); ?>')"<?php endif; ?>>
						<span class="pcas-course-label">PCAS Course</span>
						<span class="pcas-course-mark" aria-hidden="true">✦</span>
					</div>
					<div class="pcas-course-card-body">
						<div class="pcas-course-meta">
							<?php if ($level) : ?><span><?php echo esc_html(ucfirst($level)); ?></span><?php endif; ?>
							<?php if ($duration) : ?><span><?php echo esc_html($duration); ?></span><?php endif; ?>
						</div>
						<h3><?php echo esc_html(get_the_title($course)); ?></h3>
						<div class="pcas-course-description"><?php echo esc_html(wp_trim_words(wp_strip_all_tags($course->post_content), 25, '…')); ?></div>
						<div class="pcas-course-count"><span aria-hidden="true">▤</span><?php echo esc_html(sprintf(_n('%s subject', '%s subjects', count($course_subjects), 'pcas-template'), number_format_i18n(count($course_subjects)))); ?> <span class="pcas-count-separator">·</span> <?php echo esc_html(sprintf(_n('%s lesson', '%s lessons', $lesson_count, 'pcas-template'), number_format_i18n($lesson_count))); ?></div>

						<details class="pcas-course-outline">
							<summary>Preview lessons <span aria-hidden="true">+</span></summary>
							<div class="pcas-outline-content">
								<?php if ($course_subjects) : foreach ($course_subjects as $subject_pair) : ?>
									<div class="pcas-outline-subject"><h4><?php echo esc_html(get_the_title($subject_pair[0])); ?></h4>
										<?php foreach ($modules as $module) :
											$module_details = function_exists('get_field') ? get_field('module_details', $module->ID) : array();
											if ($get_parent_id($module_details, 'parent_subject') !== (int) $subject_pair[0]->ID) continue;
										?>
										<div class="pcas-outline-module"><strong><?php echo esc_html(get_the_title($module)); ?></strong>
											<ul><?php foreach ($lessons as $lesson) :
												$lesson_details = function_exists('get_field') ? get_field('lesson_details', $lesson->ID) : array();
												if ($get_parent_id($lesson_details, 'parent_module') !== (int) $module->ID) continue;
											?><li><a href="<?php echo esc_url(get_permalink($lesson)); ?>"><?php echo esc_html(get_the_title($lesson)); ?></a></li><?php endforeach; ?></ul>
										</div>
										<?php endforeach; ?>
									</div>
								<?php endforeach; else : ?><p>Course outline details are being added.</p><?php endif; ?>
							</div>
						</details>

						<?php if (is_user_logged_in()) : ?>
							<?php if ($is_enrolled) : ?>
								<a class="pcas-course-enroll is-enrolled" href="<?php echo esc_url(home_url('/my-account/')); ?>">Enrolled <span aria-hidden="true">✓</span></a>
							<?php else : ?>
								<form class="pcas-course-enroll-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
									<input type="hidden" name="action" value="pcas_enroll_course">
									<input type="hidden" name="course_id" value="<?php echo esc_attr($course->ID); ?>">
									<?php wp_nonce_field('pcas_enroll_course_' . $course->ID, 'pcas_enroll_nonce'); ?>
									<button class="pcas-course-enroll" type="submit">Enroll in this course <span aria-hidden="true">→</span></button>
								</form>
							<?php endif; ?>
						<?php else : ?>
							<a class="pcas-course-enroll" href="<?php echo esc_url(home_url('/login/')); ?>">Log in to enroll <span aria-hidden="true">→</span></a>
						<?php endif; ?>
					</div>
				</article>
				<?php endforeach; ?>
			</div>
			<p class="pcas-browse-empty" id="pcas-browse-empty" hidden>No courses or lessons match your search. Try another title or keyword.</p>
		<?php else : ?>
			<div class="pcas-browse-empty-state"><span aria-hidden="true">✦</span><h3>New learning paths are on the way.</h3><p>There are no published courses to browse right now. Please check back soon or <a href="<?php echo esc_url(home_url('/contact/')); ?>">contact PCAS</a> for help.</p></div>
		<?php endif; ?>
	</section>
</main>
<script>
document.addEventListener('DOMContentLoaded', function () {
	var search = document.getElementById('pcas-course-search');
	var cards = Array.prototype.slice.call(document.querySelectorAll('.pcas-course-card'));
	var empty = document.getElementById('pcas-browse-empty');
	if (!search) return;
	search.addEventListener('input', function () {
		var query = search.value.trim().toLowerCase();
		var visible = 0;
		cards.forEach(function (card) {
			var matches = !query || (card.getAttribute('data-search') || '').indexOf(query) !== -1;
			card.hidden = !matches;
			if (matches) visible++;
		});
		if (empty) empty.hidden = visible !== 0;
	});
});
</script>
<?php get_footer(); ?>

<?php
/** Student course overview and lesson outline. */
get_header();

while (have_posts()) :
	the_post();
	$course_id = get_the_ID();
	$user_id = get_current_user_id();
	$enrolled_ids = is_user_logged_in() ? array_map('absint', (array) get_user_meta($user_id, 'pcas_enrolled_courses', true)) : array();
	$is_enrolled = in_array($course_id, $enrolled_ids, true);
	$course_details = function_exists('get_field') ? get_field('course_details', $course_id) : array();
	$subjects = get_posts(array('post_type' => 'subject', 'post_status' => 'publish', 'posts_per_page' => -1, 'orderby' => 'menu_order title', 'order' => 'ASC'));
	$modules = get_posts(array('post_type' => 'module', 'post_status' => 'publish', 'posts_per_page' => -1, 'orderby' => 'menu_order title', 'order' => 'ASC'));
	$lessons = get_posts(array('post_type' => 'lesson', 'post_status' => 'publish', 'posts_per_page' => -1, 'orderby' => 'menu_order title', 'order' => 'ASC'));
	$parent_id = static function ($details, $key) {
		if (!is_array($details) || empty($details[$key])) return 0;
		$value = $details[$key];
		return $value instanceof WP_Post ? (int) $value->ID : absint($value);
	};
	$course_subjects = array();
	foreach ($subjects as $subject) {
		$details = function_exists('get_field') ? get_field('subject_details', $subject->ID) : array();
		if ($parent_id($details, 'parent_course') === $course_id) $course_subjects[] = $subject;
	}
	usort($course_subjects, static function ($a, $b) {
		$a_details = function_exists('get_field') ? get_field('subject_details', $a->ID) : array();
		$b_details = function_exists('get_field') ? get_field('subject_details', $b->ID) : array();
		return (int) ($a_details['subject_order'] ?? 0) <=> (int) ($b_details['subject_order'] ?? 0);
	});
	$subject_modules = static function ($subject_id) use ($modules, $parent_id) {
		$matches = array();
		foreach ($modules as $item) {
			$details = function_exists('get_field') ? get_field('module_details', $item->ID) : array();
			if ($parent_id($details, 'parent_subject') === (int) $subject_id) $matches[] = $item;
		}
		usort($matches, static function ($a, $b) {
			$a_details = function_exists('get_field') ? get_field('module_details', $a->ID) : array();
			$b_details = function_exists('get_field') ? get_field('module_details', $b->ID) : array();
			return (int) ($a_details['module_order'] ?? 0) <=> (int) ($b_details['module_order'] ?? 0);
		});
		return $matches;
	};
	$module_lessons = static function ($module_id) use ($lessons, $parent_id) {
		$matches = array();
		foreach ($lessons as $item) {
			$details = function_exists('get_field') ? get_field('lesson_details', $item->ID) : array();
			if ($parent_id($details, 'parent_module') === (int) $module_id) $matches[] = $item;
		}
		usort($matches, static function ($a, $b) {
			$a_details = function_exists('get_field') ? get_field('lesson_details', $a->ID) : array();
			$b_details = function_exists('get_field') ? get_field('lesson_details', $b->ID) : array();
			return (int) ($a_details['lesson_order'] ?? 0) <=> (int) ($b_details['lesson_order'] ?? 0);
		});
		return $matches;
	};
	$course_lessons = array();
	foreach ($course_subjects as $subject) {
		foreach ($subject_modules($subject->ID) as $module) {
			$course_lessons = array_merge($course_lessons, $module_lessons($module->ID));
		}
	}
	$completed_ids = is_user_logged_in() ? array_map('absint', (array) get_user_meta($user_id, 'pcas_completed_lessons', true)) : array();
	$completed_in_course = array_intersect($completed_ids, array_map(static function ($lesson) { return (int) $lesson->ID; }, $course_lessons));
	$continue_lesson = null;
	foreach ($course_lessons as $lesson) {
		if (!in_array((int) $lesson->ID, $completed_ids, true)) { $continue_lesson = $lesson; break; }
	}
	if (!$continue_lesson && $course_lessons) $continue_lesson = $course_lessons[0];
?>
<main class="pcas-learning-page pcas-course-overview">
	<section class="pcas-learning-hero">
		<div class="pcas-learning-wrap">
			<a class="pcas-learning-back" href="<?php echo esc_url(home_url('/browse-lessons/')); ?>">← Browse lessons</a>
			<span class="pcas-learning-kicker">PCAS Learning Path</span>
			<h1><?php the_title(); ?></h1>
			<div class="pcas-learning-intro"><?php the_content(); ?></div>
			<div class="pcas-learning-meta">
				<?php if (!empty($course_details['course_level'])) : ?><span><?php echo esc_html(ucfirst($course_details['course_level'])); ?></span><?php endif; ?>
				<?php if (!empty($course_details['estimated_duration'])) : ?><span><?php echo esc_html($course_details['estimated_duration']); ?></span><?php endif; ?>
				<span><?php echo esc_html(sprintf(_n('%s subject', '%s subjects', count($course_subjects), 'pcas-template'), number_format_i18n(count($course_subjects)))); ?></span>
			</div>
				<?php if ($is_enrolled) : ?>
					<?php if ($continue_lesson) : ?><a class="pcas-learning-button" href="<?php echo esc_url(get_permalink($continue_lesson)); ?>"><?php echo count($completed_in_course) >= count($course_lessons) ? 'Review course' : ($completed_in_course ? 'Continue learning' : 'Start learning'); ?> <span aria-hidden="true">→</span></a><?php else : ?><p class="pcas-learning-note">Lessons for this course are being prepared.</p><?php endif; ?>
					<div class="pcas-course-progress"><div><strong><?php echo esc_html(count($completed_in_course)); ?></strong> of <?php echo esc_html(count($course_lessons)); ?> lessons complete</div><progress value="<?php echo esc_attr(count($completed_in_course)); ?>" max="<?php echo esc_attr(max(1, count($course_lessons))); ?>"><?php echo esc_html(count($completed_in_course)); ?> / <?php echo esc_html(count($course_lessons)); ?></progress></div>
			<?php elseif (is_user_logged_in()) : ?>
				<form class="pcas-learning-enroll" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
					<input type="hidden" name="action" value="pcas_enroll_course"><input type="hidden" name="course_id" value="<?php echo esc_attr($course_id); ?>">
					<?php wp_nonce_field('pcas_enroll_course_' . $course_id, 'pcas_enroll_nonce'); ?>
					<button class="pcas-learning-button" type="submit">Enroll in this course <span aria-hidden="true">→</span></button>
				</form>
			<?php else : ?><a class="pcas-learning-button" href="<?php echo esc_url(home_url('/login/')); ?>">Log in to enroll <span aria-hidden="true">→</span></a><?php endif; ?>
		</div>
	</section>
	<section class="pcas-learning-wrap pcas-learning-outline">
		<header><span class="pcas-learning-kicker">Your learning path</span><h2>Course outline</h2><p>Work through each lesson in order. Your course enrollment keeps this path together in your account.</p></header>
		<?php if ($course_subjects) : foreach ($course_subjects as $subject) : ?>
			<section class="pcas-learning-subject"><h3><?php echo esc_html(get_the_title($subject)); ?></h3>
			<?php foreach ($subject_modules($subject->ID) as $module) :
				$module_lesson_list = $module_lessons($module->ID);
			?>
				<div class="pcas-learning-module"><h4><?php echo esc_html(get_the_title($module)); ?> <span><?php echo esc_html(count($module_lesson_list)); ?> lessons</span></h4>
					<?php if ($module_lesson_list) : ?><ol><?php foreach ($module_lesson_list as $lesson) : ?><li>
						<?php if ($is_enrolled) : ?><a href="<?php echo esc_url(get_permalink($lesson)); ?>"><?php echo esc_html(get_the_title($lesson)); ?><span aria-hidden="true">→</span></a>
						<?php else : ?><span><?php echo esc_html(get_the_title($lesson)); ?><small>Enroll to open this lesson</small></span><?php endif; ?>
					</li><?php endforeach; ?></ol><?php else : ?><p>Lessons are being added to this module.</p><?php endif; ?>
				</div>
			<?php endforeach; ?></section>
		<?php endforeach; else : ?><p>The course outline is being prepared.</p><?php endif; ?>
	</section>
</main>
<?php endwhile; get_footer(); ?>

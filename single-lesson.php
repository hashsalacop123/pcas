<?php
/** Student facing lesson viewer with course navigation and progress. */
get_header();

while (have_posts()) :
	the_post();
	$lesson_id = get_the_ID();
	$lesson_details = function_exists('get_field') ? get_field('lesson_details', $lesson_id) : array();
	$parent_id = static function ($details, $key) {
		if (!is_array($details) || empty($details[$key])) return 0;
		$value = $details[$key];
		return $value instanceof WP_Post ? (int) $value->ID : absint($value);
	};
	$module_id = $parent_id($lesson_details, 'parent_module');
	$module = $module_id ? get_post($module_id) : null;
	$module_details = $module ? (function_exists('get_field') ? get_field('module_details', $module_id) : array()) : array();
	$subject_id = $parent_id($module_details, 'parent_subject');
	$subject = $subject_id ? get_post($subject_id) : null;
	$subject_details = $subject ? (function_exists('get_field') ? get_field('subject_details', $subject_id) : array()) : array();
	$course_id = $parent_id($subject_details, 'parent_course');
	$course = $course_id ? get_post($course_id) : null;
	$user_id = get_current_user_id();
	$enrolled_ids = is_user_logged_in() ? array_map('absint', (array) get_user_meta($user_id, 'pcas_enrolled_courses', true)) : array();
	$is_enrolled = $course && in_array($course_id, $enrolled_ids, true);
	$completed_ids = is_user_logged_in() ? array_map('absint', (array) get_user_meta($user_id, 'pcas_completed_lessons', true)) : array();
	$is_complete = in_array($lesson_id, $completed_ids, true);
	$ordered_lessons = get_posts(array('post_type' => 'lesson', 'post_status' => 'publish', 'posts_per_page' => -1, 'orderby' => 'menu_order title', 'order' => 'ASC'));
	$course_lessons = array();
	foreach ($ordered_lessons as $candidate) {
		$details = function_exists('get_field') ? get_field('lesson_details', $candidate->ID) : array();
		$candidate_module_id = $parent_id($details, 'parent_module');
		$candidate_module = $candidate_module_id ? get_post($candidate_module_id) : null;
		$candidate_module_details = $candidate_module ? (function_exists('get_field') ? get_field('module_details', $candidate_module_id) : array()) : array();
		$candidate_subject_id = $parent_id($candidate_module_details, 'parent_subject');
		$candidate_subject_details = $candidate_subject_id ? (function_exists('get_field') ? get_field('subject_details', $candidate_subject_id) : array()) : array();
		if ($parent_id($candidate_subject_details, 'parent_course') === $course_id) $course_lessons[] = $candidate;
	}
	usort($course_lessons, static function ($a, $b) use ($parent_id) {
		$sort_key = static function ($lesson) use ($parent_id) {
			$details = function_exists('get_field') ? get_field('lesson_details', $lesson->ID) : array();
			$module_id = $parent_id($details, 'parent_module');
			$module_details = $module_id && function_exists('get_field') ? get_field('module_details', $module_id) : array();
			$subject_id = $parent_id($module_details, 'parent_subject');
			$subject_details = $subject_id && function_exists('get_field') ? get_field('subject_details', $subject_id) : array();
			return array((int) ($subject_details['subject_order'] ?? 0), (int) ($module_details['module_order'] ?? 0), (int) ($details['lesson_order'] ?? 0), $lesson->post_title);
		};
		return $sort_key($a) <=> $sort_key($b);
	});
	$current_index = -1;
	foreach ($course_lessons as $index => $candidate) if ((int) $candidate->ID === $lesson_id) { $current_index = $index; break; }
	$previous_lesson = $current_index > 0 ? $course_lessons[$current_index - 1] : null;
	$next_lesson = ($current_index >= 0 && isset($course_lessons[$current_index + 1])) ? $course_lessons[$current_index + 1] : null;
	$lesson_type = $lesson_details['lesson_type'] ?? 'text';
	$video = $lesson_details['video'] ?? null;
	$pdf = $lesson_details['pdf_file'] ?? null;
?>
<main class="pcas-learning-page pcas-lesson-page">
	<?php if (!$course || !$module || !$subject) : ?>
		<section class="pcas-learning-wrap pcas-lesson-message"><span class="pcas-learning-kicker">PCAS Learning</span><h1><?php the_title(); ?></h1><p>This lesson is missing part of its course path. Please contact the school for help.</p><a class="pcas-learning-button" href="<?php echo esc_url(home_url('/browse-lessons/')); ?>">Browse lessons</a></section>
	<?php elseif (!$is_enrolled) : ?>
		<section class="pcas-learning-wrap pcas-lesson-message"><span class="pcas-learning-kicker">Course lesson</span><h1><?php the_title(); ?></h1><p>This lesson is part of <strong><?php echo esc_html(get_the_title($course)); ?></strong>. Enroll in the course to open lesson content and track your progress.</p><a class="pcas-learning-button" href="<?php echo esc_url(get_permalink($course)); ?>">View course and enroll <span aria-hidden="true">→</span></a></section>
	<?php else : ?>
		<div class="pcas-lesson-layout pcas-learning-wrap">
			<aside class="pcas-lesson-sidebar">
				<a class="pcas-learning-back" href="<?php echo esc_url(get_permalink($course)); ?>">← <?php echo esc_html(get_the_title($course)); ?></a>
				<h2>Course lessons</h2>
				<p><?php echo esc_html(get_the_title($subject)); ?> · <?php echo esc_html(get_the_title($module)); ?></p>
				<ol><?php foreach ($course_lessons as $item) : ?><li class="<?php echo (int) $item->ID === $lesson_id ? 'is-current' : ''; ?> <?php echo in_array((int) $item->ID, $completed_ids, true) ? 'is-complete' : ''; ?>">
					<a href="<?php echo esc_url(get_permalink($item)); ?>"><span><?php echo in_array((int) $item->ID, $completed_ids, true) ? '✓' : '○'; ?></span><?php echo esc_html(get_the_title($item)); ?></a>
				</li><?php endforeach; ?></ol>
				<div class="pcas-lesson-progress"><strong><?php echo esc_html(count(array_intersect($completed_ids, array_map(static function ($item) { return (int) $item->ID; }, $course_lessons)))); ?></strong> of <?php echo esc_html(count($course_lessons)); ?> lessons complete</div>
			</aside>
			<article class="pcas-lesson-content">
				<nav class="pcas-lesson-breadcrumb" aria-label="Breadcrumb"><a href="<?php echo esc_url(home_url('/browse-lessons/')); ?>">Courses</a><span>›</span><a href="<?php echo esc_url(get_permalink($course)); ?>"><?php echo esc_html(get_the_title($course)); ?></a><span>›</span><?php echo esc_html(get_the_title($subject)); ?></nav>
				<div class="pcas-lesson-heading"><span class="pcas-learning-kicker"><?php echo esc_html(ucfirst($lesson_type)); ?> lesson<?php echo $current_index >= 0 ? ' · Lesson ' . esc_html((string) ($current_index + 1)) . ' of ' . esc_html((string) count($course_lessons)) : ''; ?></span><h1><?php the_title(); ?></h1><p><?php echo esc_html(get_the_title($module)); ?></p></div>
				<div class="pcas-lesson-body">
					<?php if ('video' === $lesson_type && is_array($video) && !empty($video['url'])) : ?>
						<video controls preload="metadata"><source src="<?php echo esc_url($video['url']); ?>" type="<?php echo esc_attr($video['mime_type'] ?? 'video/mp4'); ?>">Your browser does not support embedded video. <a href="<?php echo esc_url($video['url']); ?>">Download the video</a>.</video>
					<?php elseif ('pdf' === $lesson_type && is_array($pdf) && !empty($pdf['url'])) : ?>
						<p class="pcas-lesson-download">This lesson uses a PDF handout. <a href="<?php echo esc_url($pdf['url']); ?>" target="_blank" rel="noopener">Open or download the PDF <span aria-hidden="true">↗</span></a></p>
						<iframe class="pcas-lesson-pdf" src="<?php echo esc_url($pdf['url']); ?>" title="<?php echo esc_attr(get_the_title() . ' PDF'); ?>"></iframe>
					<?php else : ?>
						<div class="pcas-lesson-text"><?php echo !empty($lesson_details['text']) ? wp_kses_post($lesson_details['text']) : apply_filters('the_content', get_the_content()); ?></div>
					<?php endif; ?>
				</div>
				<div class="pcas-lesson-actions">
					<?php if ($previous_lesson) : ?><a class="pcas-learning-button is-secondary" href="<?php echo esc_url(get_permalink($previous_lesson)); ?>">← Previous lesson</a><?php else : ?><span></span><?php endif; ?>
					<?php if (!$is_complete) : ?><form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>"><input type="hidden" name="action" value="pcas_complete_lesson"><input type="hidden" name="lesson_id" value="<?php echo esc_attr($lesson_id); ?>"><?php wp_nonce_field('pcas_complete_lesson_' . $lesson_id, 'pcas_complete_nonce'); ?><button class="pcas-learning-button" type="submit">Mark lesson complete <span aria-hidden="true">✓</span></button></form><?php else : ?><span class="pcas-lesson-done" role="status">Lesson complete ✓</span><?php endif; ?>
					<?php if ($next_lesson) : ?><a class="pcas-learning-button" href="<?php echo esc_url(get_permalink($next_lesson)); ?>"><?php echo $is_complete ? 'Next lesson' : 'Continue'; ?> <span aria-hidden="true">→</span></a><?php else : ?><a class="pcas-learning-button" href="<?php echo esc_url(home_url('/my-account/')); ?>">Finish course <span aria-hidden="true">✓</span></a><?php endif; ?>
				</div>
			</article>
		</div>
	<?php endif; ?>
</main>
<?php endwhile; get_footer(); ?>

<?php
/** Admin only LMS dashboard for course structure and student enrollments. */

function pcas_lms_admin_menu() {
	add_menu_page(
		'PCAS LMS Dashboard',
		'PCAS LMS',
		'manage_options',
		'pcas-lms-dashboard',
		'pcas_lms_admin_render_dashboard',
		'dashicons-welcome-learn-more',
		26
	);
}
add_action('admin_menu', 'pcas_lms_admin_menu');

function pcas_lms_admin_assets($hook) {
	if ('toplevel_page_pcas-lms-dashboard' !== $hook) return;
	wp_enqueue_style('pcas-lms-admin', get_template_directory_uri() . '/css/lms-admin.css', array(), '1.0.0');
}
add_action('admin_enqueue_scripts', 'pcas_lms_admin_assets');

function pcas_lms_admin_parent_id($details, $key) {
	if (!is_array($details) || empty($details[$key])) return 0;
	$value = $details[$key];
	return $value instanceof WP_Post ? (int) $value->ID : absint($value);
}

function pcas_lms_admin_posts($post_type) {
	return get_posts(array(
		'post_type'      => $post_type,
		'post_status'    => array('publish', 'draft', 'pending', 'private', 'future'),
		'posts_per_page' => -1,
		'orderby'        => 'title',
		'order'          => 'ASC',
	));
}

function pcas_lms_admin_edit_link($post) {
	return esc_url(get_edit_post_link($post->ID, ''));
}

function pcas_lms_admin_render_dashboard() {
	if (!current_user_can('manage_options')) {
		wp_die(esc_html__('You do not have permission to view this page.', 'pcas-template'));
	}

	$courses = pcas_lms_admin_posts('course');
	$subjects = pcas_lms_admin_posts('subject');
	$modules = pcas_lms_admin_posts('module');
	$lessons = pcas_lms_admin_posts('lesson');
	$page = max(1, absint($_GET['paged'] ?? 1));
	$users_query = new WP_User_Query(array(
		'meta_key'     => 'pcas_enrolled_courses',
		'meta_compare' => 'EXISTS',
		'number'       => 20,
		'paged'        => $page,
		'orderby'      => 'display_name',
		'order'        => 'ASC',
		'count_total'  => true,
	));
	$students = $users_query->get_results();
	$student_count = (int) $users_query->get_total();
	$total_pages = max(1, (int) ceil($student_count / 20));
	$enrollment_count = 0;
	$course_enrollments = array();
	foreach ($courses as $course) $course_enrollments[(int) $course->ID] = 0;
	$all_enrolled_user_ids = get_users(array('meta_key' => 'pcas_enrolled_courses', 'meta_compare' => 'EXISTS', 'fields' => 'ID', 'number' => -1));
	foreach ($all_enrolled_user_ids as $enrolled_user_id) {
		$student_course_ids = array_unique(array_filter(array_map('absint', (array) get_user_meta($enrolled_user_id, 'pcas_enrolled_courses', true))));
		$enrollment_count += count($student_course_ids);
		foreach ($student_course_ids as $course_id) {
			if (isset($course_enrollments[$course_id])) $course_enrollments[$course_id]++;
		}
	}
	?>
	<div class="wrap pcas-lms-admin">
		<div class="pcas-lms-admin-header">
			<div><span>PCAS Learning Management</span><h1>Admin dashboard</h1><p>Review student enrollments and manage your course content.</p></div>
			<a class="button button-primary" href="<?php echo esc_url(admin_url('post-new.php?post_type=course')); ?>">＋ Add course</a>
		</div>
		<div class="pcas-lms-admin-stats">
			<div><span>Enrolled students</span><strong><?php echo esc_html(number_format_i18n($student_count)); ?></strong></div>
			<div><span>Enrollments</span><strong><?php echo esc_html(number_format_i18n($enrollment_count)); ?></strong></div>
			<div><span>Courses</span><strong><?php echo esc_html(number_format_i18n(count($courses))); ?></strong><a href="<?php echo esc_url(admin_url('edit.php?post_type=course')); ?>">Manage courses →</a></div>
			<div><span>Subjects · Modules · Lessons</span><strong><?php echo esc_html(number_format_i18n(count($subjects))); ?> · <?php echo esc_html(number_format_i18n(count($modules))); ?> · <?php echo esc_html(number_format_i18n(count($lessons))); ?></strong><a href="<?php echo esc_url(admin_url('edit.php?post_type=lesson')); ?>">Manage lessons →</a></div>
		</div>

		<section class="pcas-lms-admin-panel">
			<div class="pcas-lms-admin-panel-heading"><div><h2>Student enrollments</h2><p>Students registered in at least one course.</p></div><span><?php echo esc_html(sprintf(_n('%s student', '%s students', $student_count, 'pcas-template'), number_format_i18n($student_count))); ?></span></div>
			<div class="pcas-lms-admin-table-wrap"><table class="widefat striped pcas-lms-admin-table"><thead><tr><th>Student</th><th>Email</th><th>Enrolled courses</th><th>Lesson progress</th><th>Account</th></tr></thead><tbody>
			<?php if ($students) : foreach ($students as $student) :
				$student_course_ids = array_unique(array_filter(array_map('absint', (array) get_user_meta($student->ID, 'pcas_enrolled_courses', true))));
				$completed_ids = array_unique(array_filter(array_map('absint', (array) get_user_meta($student->ID, 'pcas_completed_lessons', true))));
				$student_course_posts = $student_course_ids ? get_posts(array('post_type' => 'course', 'post_status' => array('publish', 'draft', 'pending', 'private', 'future'), 'post__in' => $student_course_ids, 'posts_per_page' => -1, 'orderby' => 'post__in')) : array();
				$course_lesson_ids = array();
				foreach ($lessons as $lesson) {
					$lesson_details = function_exists('get_field') ? get_field('lesson_details', $lesson->ID) : array();
					$module_id = pcas_lms_admin_parent_id($lesson_details, 'parent_module');
					$module_details = $module_id && function_exists('get_field') ? get_field('module_details', $module_id) : array();
					$subject_id = pcas_lms_admin_parent_id($module_details, 'parent_subject');
					$subject_details = $subject_id && function_exists('get_field') ? get_field('subject_details', $subject_id) : array();
					$course_id = pcas_lms_admin_parent_id($subject_details, 'parent_course');
					if (in_array($course_id, $student_course_ids, true)) $course_lesson_ids[] = (int) $lesson->ID;
				}
				$completed_for_student_courses = array_intersect($completed_ids, $course_lesson_ids);
			?>
				<tr>
					<td><strong><?php echo esc_html($student->display_name); ?></strong></td>
					<td><a href="mailto:<?php echo esc_attr($student->user_email); ?>"><?php echo esc_html($student->user_email); ?></a></td>
					<td><?php if ($student_course_posts) : ?><ul class="pcas-lms-course-links"><?php foreach ($student_course_posts as $course_post) : ?><li><a href="<?php echo pcas_lms_admin_edit_link($course_post); ?>"><?php echo esc_html(get_the_title($course_post)); ?></a></li><?php endforeach; ?></ul><?php else : ?><span class="pcas-lms-muted">Course record unavailable</span><?php endif; ?></td>
					<td><?php echo esc_html(count($completed_for_student_courses)); ?> / <?php echo esc_html(count($course_lesson_ids)); ?> lessons</td>
					<td><a href="<?php echo esc_url(get_edit_user_link($student->ID)); ?>">View student</a></td>
				</tr>
			<?php endforeach; else : ?><tr><td colspan="5">No student enrollments yet. Enrollments will appear here when students join a course.</td></tr><?php endif; ?>
			</tbody></table></div>
			<?php if ($total_pages > 1) : ?><div class="tablenav"><div class="tablenav-pages"><?php echo wp_kses_post(paginate_links(array('base' => add_query_arg('paged', '%#%', menu_page_url('pcas-lms-dashboard', false)), 'format' => '', 'current' => $page, 'total' => $total_pages, 'prev_text' => '‹', 'next_text' => '›'))); ?></div></div><?php endif; ?>
		</section>

		<section class="pcas-lms-admin-panel">
			<div class="pcas-lms-admin-panel-heading"><div><h2>Course structure</h2><p>Open any item to edit its title, content, or ACF details.</p></div><div class="pcas-lms-add-links"><a href="<?php echo esc_url(admin_url('post-new.php?post_type=subject')); ?>">＋ Subject</a><a href="<?php echo esc_url(admin_url('post-new.php?post_type=module')); ?>">＋ Module</a><a href="<?php echo esc_url(admin_url('post-new.php?post_type=lesson')); ?>">＋ Lesson</a></div></div>
			<?php if ($courses) : ?><div class="pcas-lms-structure">
			<?php foreach ($courses as $course) : $course_id = (int) $course->ID; $course_subjects = array();
				foreach ($subjects as $subject) { $details = function_exists('get_field') ? get_field('subject_details', $subject->ID) : array(); if (pcas_lms_admin_parent_id($details, 'parent_course') === $course_id) $course_subjects[] = $subject; }
			?>
				<details class="pcas-lms-course-tree" open><summary><span><strong><?php echo esc_html(get_the_title($course)); ?></strong> <em><?php echo esc_html(get_post_status_object(get_post_status($course))->label ?? ''); ?></em></span><a href="<?php echo pcas_lms_admin_edit_link($course); ?>">Edit course</a><small><?php echo esc_html((int) ($course_enrollments[$course_id] ?? 0)); ?> enrolled</small></summary>
					<?php if ($course_subjects) : ?><div class="pcas-lms-tree-branches"><?php foreach ($course_subjects as $subject) : $subject_id = (int) $subject->ID; ?>
						<div class="pcas-lms-tree-subject"><div class="pcas-lms-tree-label"><span>Subject</span><a href="<?php echo pcas_lms_admin_edit_link($subject); ?>"><?php echo esc_html(get_the_title($subject)); ?></a></div>
							<?php $subject_modules = array(); foreach ($modules as $module) { $details = function_exists('get_field') ? get_field('module_details', $module->ID) : array(); if (pcas_lms_admin_parent_id($details, 'parent_subject') === $subject_id) $subject_modules[] = $module; } ?>
							<?php if ($subject_modules) : ?><div class="pcas-lms-tree-modules"><?php foreach ($subject_modules as $module) : $module_id = (int) $module->ID; ?>
								<div class="pcas-lms-tree-module"><div class="pcas-lms-tree-label"><span>Module</span><a href="<?php echo pcas_lms_admin_edit_link($module); ?>"><?php echo esc_html(get_the_title($module)); ?></a></div>
									<?php $module_lessons = array(); foreach ($lessons as $lesson) { $details = function_exists('get_field') ? get_field('lesson_details', $lesson->ID) : array(); if (pcas_lms_admin_parent_id($details, 'parent_module') === $module_id) $module_lessons[] = $lesson; } ?>
									<?php if ($module_lessons) : ?><ul><?php foreach ($module_lessons as $lesson) : ?><li><span>Lesson</span><a href="<?php echo pcas_lms_admin_edit_link($lesson); ?>"><?php echo esc_html(get_the_title($lesson)); ?></a></li><?php endforeach; ?></ul><?php else : ?><p class="pcas-lms-muted">No lessons in this module.</p><?php endif; ?>
								</div><?php endforeach; ?></div><?php else : ?><p class="pcas-lms-muted">No modules in this subject.</p><?php endif; ?>
						</div><?php endforeach; ?></div><?php else : ?><p class="pcas-lms-muted pcas-lms-empty-tree">No subjects linked to this course yet.</p><?php endif; ?>
				</details>
			<?php endforeach; ?></div><?php else : ?><p>No courses have been created yet. Add a course to begin building your learning paths.</p><?php endif; ?>
		</section>
	</div>
	<?php
}

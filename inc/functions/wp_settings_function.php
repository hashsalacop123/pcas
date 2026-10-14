<?php 
/**
 * Register theme menus.
 */
function mytheme_register_menus() {
    register_nav_menus(
        array(
            'main' => __('Main Menu', 'pcas'),
        )
    );
}
add_action('after_setup_theme', 'mytheme_register_menus');

//=============SUPPORT OF THE  THEME MENUS==========
    add_action( 'after_setup_theme', 'my_setup' );
     if ( ! function_exists( 'my_setup' ) ) {
      function my_setup() {
       if ( function_exists( 'add_theme_support' ) ) { // Added in 2.9
        add_theme_support( 'post-thumbnails' );
       }

       add_theme_support( 'menus' );

       if ( function_exists( 'register_nav_menus' ) ) {
        register_nav_menus(
         array(
           'header_menu' => 'Header Menu',
           'footer_menu' => 'Footer Menu'
         )
        );
       }   
      }
     }
//=============SUPPORT OF THE THEME WIDGETS==========
    function arphabet_widgets_init() {
        register_sidebar( array(
            'name'          => 'Left footer',
            'id'            => 'home_right_1',
            'before_widget' => '<div>',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="rounded">',
            'after_title'   => '</h2>',
        ) );
        register_sidebar( array(
            'name'          => 'Right footer',
            'id'            => 'home_right_2',
            'before_widget' => '<div>',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="rounded">',
            'after_title'   => '</h2>',
        ) );
    }
    add_action( 'widgets_init', 'arphabet_widgets_init' );

    // MENU CLASS


class My_Walker_Nav_Menu extends Walker_Nav_Menu {
   function start_lvl( &$output, $depth = 0, $args = array() ) {
    $indent = str_repeat("\t", $depth);
    $output .= "\n$indent<ul class=\"my-hash-menu \">\n";
  }
}

function add_menuclass($ulclass) {
   return preg_replace('/<a /', '<a class="nav-link "', $ulclass);
}
add_filter('wp_nav_menu','add_menuclass');


if ( ! function_exists( 'post_pagination' ) ) :
   function post_pagination() {
     global $wp_query;
     $pager = 999999999; // need an unlikely integer
 
        echo paginate_links( array(
             'base' => str_replace( $pager, '%#%', esc_url( get_pagenum_link( $pager ) ) ),
             'format' => '?paged=%#%',
             'current' => max( 1, get_query_var('paged') ),
             'total' => $wp_query->max_num_pages
        ) );
   }
endif;


/**
 * Allow SVG file uploads in WordPress.
 *
 * @param array $mimes Allowed mime types.
 * @return array
 */
function hash_allow_svg_upload( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';

	return $mimes;
}
add_filter( 'upload_mimes', 'hash_allow_svg_upload' );




/**
 * Create the main LMS admin menu.
 *
 * This creates a top-level WordPress admin menu:
 *
 * LMS
 *
 * Your ACF-registered CPTs will then be placed
 * underneath this menu.
 */
function hash_lms_admin_menu() {

    add_menu_page(

        // Browser/page title.
        'Learning Management System',

        // Sidebar menu title.
        'LMS',

        // Capability required to access the menu.
        'manage_options',

        // IMPORTANT:
        // This is the menu slug that your ACF CPTs
        // will use as their "Admin Menu Parent".
        'hash-lms',

        // Callback for the LMS dashboard.
        'hash_lms_dashboard',

        // WordPress Dashicon.
        'dashicons-welcome-learn-more',

        // Menu position.
        25
    );
}

add_action(
    'admin_menu',
    'hash_lms_admin_menu'
);


/**
 * Display the LMS dashboard.
 */
function hash_lms_dashboard() {
    ?>

    <div class="wrap">

        <h1>Learning Management System</h1>

        <p>
            Welcome to your custom LMS.
        </p>

    </div>

    <?php
}


/**
 * Reorder the LMS submenu items.
 *
 * This changes the order to:
 *
 * LMS
 * ├── All Courses
 * ├── All Subjects
 * ├── All Modules
 * └── All Lessons
 */
function hash_reorder_lms_submenu() {

    global $submenu;

    /*
     * IMPORTANT:
     *
     * Change this if your LMS parent menu slug
     * is not "hash-lms".
     */
    $lms_menu_slug = 'hash-lms';

    /*
     * Make sure the LMS submenu exists.
     */
    if ( empty( $submenu[ $lms_menu_slug ] ) ) {
        return;
    }

    /*
     * This is the order we want.
     *
     * These are the actual submenu titles
     * shown in your screenshot.
     */
    $desired_order = [
        'All Courses',
        'All Subjects',
        'All Modules',
        'All Lessons',
    ];

    /*
     * Get the existing submenu items.
     */
    $existing_items = $submenu[ $lms_menu_slug ];

    /*
     * Create our new ordered array.
     */
    $ordered_items = [];

    /*
     * Go through our desired order.
     */
    foreach ( $desired_order as $desired_title ) {

        foreach ( $existing_items as $key => $item ) {

            /*
             * WordPress stores the submenu title
             * in $item[0].
             */
            if (
                isset( $item[0] ) &&
                trim( wp_strip_all_tags( $item[0] ) ) === $desired_title
            ) {

                $ordered_items[] = $item;

                /*
                 * Remove this item from the original
                 * array so it doesn't get added twice.
                 */
                unset( $existing_items[ $key ] );

                break;
            }
        }
    }

    /*
     * Add anything that wasn't included in our
     * desired order after the ordered items.
     *
     * This is useful if later you add:
     *
     * Students
     * Quizzes
     * Enrollments
     * Settings
     */
    foreach ( $existing_items as $item ) {
        $ordered_items[] = $item;
    }

    /*
     * Replace the original LMS submenu.
     */
    $submenu[ $lms_menu_slug ] = $ordered_items;
}

/*
 * Run this very late so ACF has already registered
 * all of your CPT submenu items.
 */
add_action(
    'admin_menu',
    'hash_reorder_lms_submenu',
    99999
);
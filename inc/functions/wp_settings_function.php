<?php 
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




?>
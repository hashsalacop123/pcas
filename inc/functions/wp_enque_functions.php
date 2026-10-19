<?php 
//THIS THE FUCNTION OR API THAT SUPPORT ON THE BLANK TEMP
function theme_enqueue_styles() {
    $parent_style = 'parent-style';
    wp_enqueue_style( 'font-awesome','https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
    wp_enqueue_style( 'bootstrap.min', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css' );
     //=============SLICK=========
    wp_enqueue_style( 'slick', get_template_directory_uri() . '/api-blocks/slick/slick/slick.css' );
    wp_enqueue_style( 'slick-theme', get_template_directory_uri() . '/api-blocks/slick/slick/slick-theme.css' );
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'home', get_template_directory_uri() . '/css/home.css' );
    wp_enqueue_style( 'header', get_template_directory_uri() . '/css/header.css' );
     wp_enqueue_style( 'general', get_template_directory_uri() . '/css/general.css' );
    wp_enqueue_style( 'responsive', get_template_directory_uri() . '/css/responsive.css' );
    wp_enqueue_script('pcas-header-menu', get_template_directory_uri() . '/js/header-menu.js', array(), '1.0.0', true);
    if (is_page_template('templates/contact.php')) {
        wp_enqueue_style('pcas-contact', get_template_directory_uri() . '/css/contact.css', array('home', 'header', 'general', 'responsive'), '1.0.0');
    }
    if (is_page_template('templates/registraion.php') || is_page_template('templates/login.php') || is_page_template('templates/user-account.php')) {
        wp_enqueue_style('pcas-account', get_template_directory_uri() . '/css/account.css', array('home', 'header', 'general', 'responsive'), '1.0.0');
    }
    if (is_page_template('templates/gallery.php')) {
        wp_enqueue_style('pcas-gallery', get_template_directory_uri() . '/css/gallery.css', array('home', 'header', 'general', 'responsive'), '1.0.0');
        wp_enqueue_script('pcas-gallery-lightbox', get_template_directory_uri() . '/js/gallery-lightbox.js', array(), '1.0.0', true);
    }
    if (is_page_template('templates/about-us.php')) {
        wp_enqueue_style('pcas-about', get_template_directory_uri() . '/css/about.css', array('home', 'header', 'general', 'responsive'), '1.0.0');
    }
    if (is_page_template('templates/faculty.php')) {
        wp_enqueue_style('pcas-faculty', get_template_directory_uri() . '/css/faculty.css', array('home', 'header', 'general', 'responsive'), '1.0.0');
    }
    if (is_page_template('templates/browse-lessons.php')) {
        wp_enqueue_style('pcas-browse-lessons', get_template_directory_uri() . '/css/browse-lessons.css', array('home', 'header', 'general', 'responsive'), '1.0.0');
    }
    if (is_singular(array('course', 'lesson'))) {
        wp_enqueue_style('pcas-learning', get_template_directory_uri() . '/css/learning.css', array('home', 'header', 'general', 'responsive'), '1.0.0');
    }
    //=============FANCYBOX=========    
    wp_enqueue_style( 'jquery.fancybox.css', get_template_directory_uri() . '/api-blocks/fancyapps/source/jquery.fancybox.css');
    wp_enqueue_style( 'jquery.fancybox-buttons.css', get_template_directory_uri() . '/api-blocks/fancyapps/source/helpers/jquery.fancybox-buttons.css' );
    wp_enqueue_style( 'jquery.fancybox-thumbs.css', get_template_directory_uri() . '/api-blocks/fancyapps/source/helpers/jquery.fancybox-thumbs.css');
     wp_enqueue_script('jquery');
     wp_enqueue_script( 'bootstrap.min.js','https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js', array( 'jquery' ));
     //====SLICK JS=====
     wp_enqueue_script( 'slick.min.js',get_stylesheet_directory_uri() . '/api-blocks/slick/slick/slick.min.js', array( 'jquery' ));
    //=============FANCYBOX=========
     wp_enqueue_script('jquery.mousewheel-3.0.6.pack.js',get_stylesheet_directory_uri() . '/api-blocks/fancyapps/lib/jquery.mousewheel-3.0.6.pack.js', array( 'jquery' ));
     wp_enqueue_script('jquery.fancybox.pack.js',get_stylesheet_directory_uri() . '/api-blocks/fancyapps/source/jquery.fancybox.pack.js', array( 'jquery' ));
     wp_enqueue_script('jquery.fancybox-buttons.js',get_stylesheet_directory_uri() . '/api-blocks/fancyapps/source/helpers/jquery.fancybox-buttons.js', array( 'jquery' ));
     wp_enqueue_script('jquery.fancybox-media.js',get_stylesheet_directory_uri() . '/api-blocks/fancyapps/source/helpers/jquery.fancybox-media.js', array( 'jquery' ));
     wp_enqueue_script('jquery.fancybox-thumbs.js',get_stylesheet_directory_uri() . '/api-blocks/fancyapps/source/helpers/jquery.fancybox-thumbs.js', array( 'jquery' ));
     wp_enqueue_script( 'jquery-scripts.js',get_stylesheet_directory_uri() . '/js/jquery-scripts.js', array( 'jquery' ));
    }
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

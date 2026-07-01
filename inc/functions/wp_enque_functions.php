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
    wp_enqueue_style( 'responsive', get_template_directory_uri() . '/css/responsive.css' );
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
<?php  global $redux_demo; ?>
<!DOCTYPE HTML>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php wp_title('|',TRUE,'right'); bloginfo('name'); ?></title>
<!--    <link rel="shortcut icon" href="/wp-content/uploads/2016/08/cropped-favicon-32x32.png" />-->
</head>
    <?php wp_head(); ?>
    <body <?php body_class(); ?>>
    <header id = "header_bg_color">
        <div class = "header-wrapper">
            <div class = "container"  id="navbar">
            <div class = "row">
                 <div class = "col-sm-12 col-md-6">
                    <div class = "logo">
                    
                        <a href = "<?php echo get_home_url(); ?>">
                          <img src = "<?php echo  $logo; ?>"  class = "img-fluid" alt = "logo"/>
                        </a>
                    </div>
                 </div>
                <div class = "col-sm-12 col-md-6 ">
                  <nav class="navbar navbar-toggleable-md navbar-light bg-faded">
                          <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="navbar-toggler-icon"></span>
                         </button>
                            <!-- <a class="navbar-brand" href="#">Navbar</a> -->
                                   <?php    if ( has_nav_menu('header_menu') ) :
                                             wp_nav_menu( array( 'theme_location'  => 'header_menu',
                                                                'container_class' => 'collapse navbar-collapse',
                                                                'container_id'    => 'navbarNavDropdown',
                                                                'menu_class'      => 'navbar-nav ml-auto',
                                                                'walker' => new My_Walker_Nav_Menu()
                                                      ) ); 
                                            endif;  ?> 
                   </nav>
                </div>
              
                </div>
            </div>
        </div>
    </header>
    
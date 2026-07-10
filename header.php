
<!DOCTYPE HTML>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php wp_title('|',TRUE,'right'); bloginfo('name'); ?></title>
<!--    <link rel="shortcut icon" href="/wp-content/uploads/2016/08/cropped-favicon-32x32.png" />-->
</head>
   <header id="top" class="hero">
    <?php wp_head(); ?>
    <body <?php body_class(); ?>>
    <?php 
      $top_banner = get_field('top_banner','option');
        if($top_banner) :
    ?>
          <div class="top-strip"><?php echo $top_banner;  ?></div>
          <?php endif; ?>
  <nav class="nav">
    <div class="nav-inner">
      <a class="brand" href="#top" aria-label="PCAS home">
        <?php 
            $logo = get_field('logo','option');
            if($logo) :
        ?>
            <img src="<?php echo $logo['url']; ?>" alt="Logo">
          <?php 
            endif; 
          ?>
            <!-- <span class="brand-copy"><strong>Pacific Center for Advanced Studies</strong><small>Online Embalming Course</small></span> -->
      </a>
   <?php
/**
 * Display the Main menu without <ul> and <li>.
 */
$menu_name = 'Main'; // The menu name in Appearance > Menus.
$menu = wp_get_nav_menu_object($menu_name);

if ($menu) {
    $menu_items = wp_get_nav_menu_items($menu->term_id);

    if ($menu_items) {
        echo '<div class="nav-links">';

        foreach ($menu_items as $item) {

            // Default class.
            $class = '';

            // Add a button class if the menu item has "btn-applyonline".
            if (in_array('btn-applyonline', $item->classes, true)) {
                $class = 'btn';
            }

            printf(
                '<a href="%1$s"%2$s>%3$s</a>',
                esc_url($item->url),
                $class ? ' class="' . esc_attr($class) . '"' : '',
                esc_html($item->title)
            );
        }

        echo '</div>';
    }
}?>
    </div>
  </nav>
   
  
  </header>
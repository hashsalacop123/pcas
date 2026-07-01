<?php get_header(); ?>
<div class = "ind-template">
<?php 
	global $redux_demo;

	// echo '<pre>';
	// var_dump($redux_demo);
?>
    <div class = "container">
        <?php 
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
             
            query_posts(array(
                'post_type' => 'post', // You can add a custom post type if you like
                'paged' => $paged,
                'posts_per_page' => 2 // limit of posts
            ));
             
            if ( have_posts() ) :  while ( have_posts() ) : the_post();
               echo '<div class = "main-content">';
                        echo get_the_title(); 
                        echo get_the_content();
                echo '</div>';
             
             endwhile;
             
                post_pagination();
             
             else :
             
            echo  'no posts found message goes here...';
             
             endif; ?>
         </div>
</div>
<?php get_footer();?>


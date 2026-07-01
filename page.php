<?php 
//====THIS ARE THE DEFAULT TEMPLATE====
get_header(); ?>

<div class = "general-template">
	<div class = "container">			
	    <h1><?php the_title(); ?></h1>
	    <?php 	if (have_posts()) : ?>
	        <?php while (have_posts()):the_post();	?>
	                    <?php the_content(); ?>
	       <?php endwhile; endif;	?>
	</div>
</div>

<?php get_footer(); ?>
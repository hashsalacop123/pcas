<?php get_header(); ?>
<div class = "ind-template">
    <div class = "container">
        <?php 	if (have_posts()) : ?>
        <?php while (have_posts()):the_post();	?>
                    <?php the_title(); ?>
                    <?php the_content(); ?>
       <?php endwhile; endif;	?>
    </div>
</div>
<?php get_footer();?>
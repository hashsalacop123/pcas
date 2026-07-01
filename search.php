<?php get_header(); ?>

	<main role="main">
		<!-- section -->
		<section class = "searchesresults">
				<div class = "container">
				<h1>Search Results</h1>
		
				 <?php 	if (have_posts()) : ?>
			        <?php while (have_posts()):the_post();	?>
			        			<h3><a href = "<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			                    <?php the_content(); ?>
			       <?php endwhile;
			       	else :
			       		echo '<p class = "notfound">Sorry, but nothing matched your search terms. Please try again with some different keywords.</p>';
			        endif;	?>

				</div>
		</section>
		<!-- /section -->
	</main>


<?php get_footer(); ?>

<?php  global $redux_demo; ?>
<footer>
    <div class = "footer-wrapper">
        <div class = "container">
                       <?php 
                       	if ( has_nav_menu('footer_menu') ) :
                      		 wp_nav_menu( array( 'theme_location' => 'footer_menu',
                                            	  'container_class' => 'menu-container'
                                         ) );
                   		endif;


                        ?>

        </div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
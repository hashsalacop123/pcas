<?php  
// Template Name: My Account
get_header(); ?>

<?php
/**
 * Get the current post's featured image URL
 * and use it as a background image.
 */
$featured_image = get_the_post_thumbnail_url(
    get_the_ID(),
    'full'
);
?>
<div class = "main-account-template">
    <div class = "banner-account" style="background-image: url('<?php echo esc_url($featured_image); ?>');">
        <div class = "content container">
            <?php
            $current_user = wp_get_current_user();
?>

<?php if ( $current_user->exists() ) : ?>

    <h2>
        Welcome to Pcas,<?php echo esc_html( $current_user->first_name ); ?>!
    </h2>
    <p>You're one step closer to build your future in funeral serivce education.</p>

    <p>Thank you for joining PCAS, Your account has been created successfully.</p>
    <p>Start exploring our courses, or follow the steps below to complete your setup and begin learning</p>
    <a href = "#" class = "btn">Start Learning</a>

<?php endif; ?>
        </div>
    </div>

    <section class = "user-enrolled container">

    <!-- IF THE USER JUST LOOGED OR NO COURSE -->
    <div class = "new-user-no-course">
        <h4>Start Your Learning Journey</h4>
        <p>Explore our available courses and choose the programs that's right for you.</p>
        <a href = "#" class = "btn">Browser All Courses</a>
    </div>

    </section>

    <div class = "container-action">
        <div class = "container">
            <div class = "row">
                <div class = "heading-space col-xl-12 col-sm-12">
                    <h3>Quick Actions</h3>
                </div>
                <div class = "col-xl-3 col-md-6 col-sm-12">
                    <div class = "box">
                        
                        <i class="fa fa-book"></i>
                        <h5>Browse All Courses</h5>
                        <p>Explore our full courses Library </p>
                    </div>
                </div>
                   <div class = "col-xl-3 col-md-6 col-sm-12">
                    <div class = "box">
                        
                        <i class="fa fa-list-ol"></i>
                        <h5>My Enrollments</h5>
                        <p>View your enrolled courses</p>
                    </div>
                </div>
                   <div class = "col-xl-3 col-md-6 col-sm-12">
                    <div class = "box">
                        
                        <i class="fa fa-trophy"></i>
                        <h5>My Certificates</h5>
                        <p>Access your completed certificates </p>
                    </div>
                </div>
                   <div class = "col-xl-3 col-md-6 col-sm-12">
                    <div class = "box">
                        
                        <i class="fa fa-user"></i>
                        <h5>Edit Profile</h5>
                        <p>Update your perfonel information </p>
                    </div>
                </div>
            </div>
        </div>

</div>
</div>

<?php get_footer(); ?>
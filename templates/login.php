<?php 
/**
 * Template Name: Login
 * Description: A custom page template.
 */


get_header(); ?>
<div class = " page-main-content">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <h2 class="mb-4">Login</h2>

            <?php
            // Display login error messages.
            if (isset($_GET['login_error'])) :

                if ($_GET['login_error'] === 'required') :
            ?>

                <div class="alert alert-danger">
                    Please enter your username and password.
                </div>

            <?php
                elseif ($_GET['login_error'] === 'invalid') :
            ?>

                <div class="alert alert-danger">
                    Invalid username or password.
                </div>

            <?php
                endif;

            endif;
            ?>

            <form
                method="POST"
                action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
            >

                <!-- Tell WordPress which action to process -->
                <input
                    type="hidden"
                    name="action"
                    value="hash_frontend_login"
                >

                <!-- WordPress security nonce -->
                <?php
                wp_nonce_field(
                    'hash_frontend_login',
                    'hash_login_nonce'
                );
                ?>

                <!-- Username / Email -->
                <div class="mb-3">
                    <label
                        for="login_username"
                        class="form-label"
                    >
                        Username or Email
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="login_username"
                        name="username"
                        required
                    >
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label
                        for="login_password"
                        class="form-label"
                    >
                        Password
                    </label>

                    <input
                        type="password"
                        class="form-control"
                        id="login_password"
                        name="password"
                        required
                    >
                </div>

                <!-- Remember Me -->
                <div class="form-check mb-3">

                    <input
                        type="checkbox"
                        class="form-check-input"
                        id="remember"
                        name="remember"
                        value="1"
                    >

                    <label
                        class="form-check-label"
                        for="remember"
                    >
                        Remember me
                    </label>

                </div>

                <!-- Submit -->
                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Login
                </button>

            </form>

        </div>
    </div>
</div>
</div>
<?php get_footer(); ?>
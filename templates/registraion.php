<?php 
/**
 * Template Name: Registration
 * Description: A custom page template.
 */


get_header(); ?>
<div class = " page-main-content">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <h2 class="mb-4">Create an Account</h2>

            <form
                method="POST"
                action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
            >

                <!-- Tell WordPress which action to process -->
                <input
                    type="hidden"
                    name="action"
                    value="hash_frontend_registration"
                >

                <!-- WordPress security nonce -->
                <?php
                wp_nonce_field(
                    'hash_frontend_registration',
                    'hash_registration_nonce'
                );
                ?>

                <!-- First Name -->
                <div class="mb-3">
                    <label for="first_name" class="form-label">
                        First Name
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="first_name"
                        name="first_name"
                    >
                </div>

                <!-- Last Name -->
                <div class="mb-3">
                    <label for="last_name" class="form-label">
                        Last Name
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="last_name"
                        name="last_name"
                    >
                </div>

                <!-- Username -->
                <div class="mb-3">
                    <label for="username" class="form-label">
                        Username
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="username"
                        name="username"
                        required
                    >
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">
                        Email Address
                    </label>

                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        name="email"
                        required
                    >
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">
                        Password
                    </label>

                    <input
                        type="password"
                        class="form-control"
                        id="password"
                        name="password"
                        minlength="8"
                        required
                    >
                </div>

                <!-- Confirm Password -->
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">
                        Confirm Password
                    </label>

                    <input
                        type="password"
                        class="form-control"
                        id="confirm_password"
                        name="confirm_password"
                        minlength="8"
                        required
                    >
                </div>

                <!-- Submit -->
                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Register
                </button>

            </form>

        </div>
    </div>
</div>
</div>
<?php get_footer(); ?>
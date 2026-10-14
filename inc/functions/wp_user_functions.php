<?php 
/**
 * Handle frontend user registration.
 */
/**
 * Handle frontend user registration.
 *
 * This function:
 * - Validates the registration form.
 * - Creates the WordPress user.
 * - Saves the user's first and last name.
 * - Sends a welcome email to the new user.
 * - Sends a notification email to the WordPress administrator.
 * - Automatically logs the user in.
 * - Redirects the user to the My Account page.
 */
function hash_frontend_registration() {

    /*
     * ---------------------------------------------------------
     * Check the WordPress security nonce.
     * ---------------------------------------------------------
     */
    if (
        !isset($_POST['hash_registration_nonce']) ||
        !wp_verify_nonce(
            $_POST['hash_registration_nonce'],
            'hash_frontend_registration'
        )
    ) {
        wp_die('Security check failed.');
    }


    /*
     * ---------------------------------------------------------
     * Sanitize submitted form fields.
     * ---------------------------------------------------------
     */
    $username         = sanitize_user($_POST['username'] ?? '');
    $email            = sanitize_email($_POST['email'] ?? '');
    $password         = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $first_name       = sanitize_text_field($_POST['first_name'] ?? '');
    $last_name        = sanitize_text_field($_POST['last_name'] ?? '');


    /*
     * ---------------------------------------------------------
     * Create a WordPress error object.
     * ---------------------------------------------------------
     */
    $errors = new WP_Error();


    /*
     * ---------------------------------------------------------
     * Validate username.
     * ---------------------------------------------------------
     */

    // Check if username is empty.
    if (empty($username)) {

        $errors->add(
            'username_empty',
            'Username is required.'
        );

    // Check if username already exists.
    } elseif (username_exists($username)) {

        $errors->add(
            'username_exists',
            'That username is already taken.'
        );
    }


    /*
     * ---------------------------------------------------------
     * Validate email address.
     * ---------------------------------------------------------
     */

    // Check if email is empty.
    if (empty($email)) {

        $errors->add(
            'email_empty',
            'Email is required.'
        );

    // Check if email format is valid.
    } elseif (!is_email($email)) {

        $errors->add(
            'email_invalid',
            'Please enter a valid email address.'
        );

    // Check if email is already registered.
    } elseif (email_exists($email)) {

        $errors->add(
            'email_exists',
            'That email address is already registered.'
        );
    }


    /*
     * ---------------------------------------------------------
     * Validate password.
     * ---------------------------------------------------------
     */

    // Check if password is empty.
    if (empty($password)) {

        $errors->add(
            'password_empty',
            'Password is required.'
        );

    // Check minimum password length.
    } elseif (strlen($password) < 8) {

        $errors->add(
            'password_short',
            'Password must be at least 8 characters.'
        );
    }


    /*
     * ---------------------------------------------------------
     * Check password confirmation.
     * ---------------------------------------------------------
     */
    if ($password !== $confirm_password) {

        $errors->add(
            'password_mismatch',
            'Passwords do not match.'
        );
    }


    /*
     * ---------------------------------------------------------
     * Stop registration if there are validation errors.
     * ---------------------------------------------------------
     */
    if ($errors->has_errors()) {

        // Store the error messages temporarily.
        set_transient(
            'hash_registration_errors_' . wp_get_session_token(),
            $errors->get_error_messages(),
            60
        );

        // Redirect the user back to the registration page.
        wp_safe_redirect(
            wp_get_referer()
        );

        exit;
    }


    /*
     * ---------------------------------------------------------
     * Create the WordPress user.
     * ---------------------------------------------------------
     */
    $user_id = wp_create_user(
        $username,
        $password,
        $email
    );


    /*
     * ---------------------------------------------------------
     * Check if WordPress failed to create the user.
     * ---------------------------------------------------------
     */
    if (is_wp_error($user_id)) {

        // Store the WordPress error messages temporarily.
        set_transient(
            'hash_registration_errors_' . wp_get_session_token(),
            $user_id->get_error_messages(),
            60
        );

        // Redirect the user back to the registration page.
        wp_safe_redirect(
            wp_get_referer()
        );

        exit;
    }


    /*
     * ---------------------------------------------------------
     * Update first and last name.
     * ---------------------------------------------------------
     */
    wp_update_user([
        'ID'         => $user_id,
        'first_name' => $first_name,
        'last_name'  => $last_name,
    ]);


    /*
     * ---------------------------------------------------------
     * Get basic site information.
     * ---------------------------------------------------------
     */

    // Get the website name.
    $site_name = wp_specialchars_decode(
        get_bloginfo('name'),
        ENT_QUOTES
    );

    // Get the user's account/login page.
    $login_url = home_url('/my-account/');


    /*
     * =========================================================
     * EMAIL #1 - SEND WELCOME EMAIL TO THE NEW USER
     * =========================================================
     */

    // Email subject.
    $user_subject = 'Welcome to ' . $site_name;

    // Build the email message.
    $user_message  = "Hi {$first_name},\n\n";

    $user_message .= "Your account has been successfully created.\n\n";

    $user_message .= "Account Details:\n";
    $user_message .= "-------------------------\n";
    $user_message .= "Username: {$username}\n";
    $user_message .= "Email: {$email}\n\n";

    $user_message .= "You can log in to your account here:\n";
    $user_message .= $login_url . "\n\n";

    $user_message .= "Thank you,\n";
    $user_message .= $site_name;


    /*
     * Send the welcome email to the newly registered user.
     *
     * Note:
     * The password is NOT included for security reasons.
     */
    wp_mail(
        $email,
        $user_subject,
        $user_message
    );


    /*
     * =========================================================
     * EMAIL #2 - SEND NEW REGISTRATION NOTIFICATION TO ADMIN
     * =========================================================
     */

    // Get the administrator email from WordPress settings.
    $admin_email = get_option('admin_email');

    // Admin email subject.
    $admin_subject = 'New User Registration - ' . $site_name;

    // Build the admin notification email.
    $admin_message  = "A new user has registered on your website.\n\n";

    $admin_message .= "User Details:\n";
    $admin_message .= "-------------------------\n";
    $admin_message .= "First Name: {$first_name}\n";
    $admin_message .= "Last Name: {$last_name}\n";
    $admin_message .= "Username: {$username}\n";
    $admin_message .= "Email: {$email}\n";
    $admin_message .= "User ID: {$user_id}\n\n";

    $admin_message .= "Manage this user in WordPress:\n";

    $admin_message .= admin_url(
        'user-edit.php?user_id=' . $user_id
    );


    /*
     * Send the registration notification
     * to the WordPress administrator.
     */
    wp_mail(
        $admin_email,
        $admin_subject,
        $admin_message
    );


    /*
     * ---------------------------------------------------------
     * Automatically log the newly registered user in.
     * ---------------------------------------------------------
     */
    wp_set_auth_cookie(
        $user_id
    );


    /*
     * ---------------------------------------------------------
     * Redirect the user to the My Account page.
     * ---------------------------------------------------------
     */
    wp_safe_redirect(
        $login_url
    );

    exit;
}


/*
 * ---------------------------------------------------------
 * Process registration for logged-out users.
 * ---------------------------------------------------------
 */
add_action(
    'admin_post_nopriv_hash_frontend_registration',
    'hash_frontend_registration'
);


/*
 * ---------------------------------------------------------
 * Process registration for logged-in users.
 * ---------------------------------------------------------
 */
add_action(
    'admin_post_hash_frontend_registration',
    'hash_frontend_registration'
);




/**
 * Handle frontend user login.
 */
function hash_frontend_login() {

    // Check the nonce for security.
    if (
        !isset($_POST['hash_login_nonce']) ||
        !wp_verify_nonce(
            $_POST['hash_login_nonce'],
            'hash_frontend_login'
        )
    ) {
        wp_die('Security check failed.');
    }

    // Sanitize the submitted username/email.
    $username = sanitize_text_field($_POST['username'] ?? '');

    // Password should NOT be sanitized because special characters
    // may be part of the password.
    $password = $_POST['password'] ?? '';

    // Remember me checkbox.
    $remember = !empty($_POST['remember']);

    // Validate required fields.
    if (empty($username) || empty($password)) {

        wp_safe_redirect(
            add_query_arg(
                'login_error',
                'required',
                wp_get_referer()
            )
        );

        exit;
    }

    // Attempt to log the user in.
    $user = wp_signon(
        [
            'user_login'    => $username,
            'user_password' => $password,
            'remember'      => $remember,
        ],
        is_ssl()
    );

    // Check for login errors.
    if (is_wp_error($user)) {

        wp_safe_redirect(
            add_query_arg(
                'login_error',
                'invalid',
                wp_get_referer()
            )
        );

        exit;
    }

    // Successful login.
    wp_safe_redirect(
        home_url('/my-account/')
    );

    exit;
}

// Handle login for logged-out users.
add_action(
    'admin_post_nopriv_hash_frontend_login',
    'hash_frontend_login'
);
?>
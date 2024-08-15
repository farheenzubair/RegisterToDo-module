<?php

function rtd_register_form() 
{
    ob_start(); ?>
    <div class="rtd-form-container rtd-register-form-container">
        <h2><?php _e('Register', 'register-todo'); ?></h2>
        <form id="rtd-register-form" method="post">
            <!--nonce for security -->
            <?php wp_nonce_field('rtd_register_action', 'rtd_register_nonce'); ?>

            <label for="username"><?php _e('Username', 'register-todo'); ?></label>
            <input type="text" name="username" id="username" required>

            <label for="email"><?php _e('Email', 'register-todo'); ?></label>
            <input type="email" name="email" id="email" required>

            <label for="password"><?php _e('Password', 'register-todo'); ?></label>
            <input type="password" name="password" id="password" required>

            <label for="confirm_password"><?php _e('Confirm Password', 'register-todo'); ?></label>
            <input type="password" name="confirm_password" id="confirm_password" required>

            <input type="submit" name="register" value="<?php _e('Register', 'register-todo'); ?>">
        </form>
        <p><?php _e('Already registered? <a href="' . esc_url(home_url('/login')) . '">Login here</a>.', 'register-todo'); ?></p>
    </div>
    <?php
    return ob_get_clean();
}

function rtd_handle_registration() 
{
    // Check nonce field is valid or not
    if (!isset($_POST['rtd_register_nonce']) || !wp_verify_nonce($_POST['rtd_register_nonce'], 'rtd_register_action')) 
    {
        wp_send_json_error(__('Nonce verification failed', 'register-todo'));
    }

    // Validate and sanitize input
    $username = sanitize_user($_POST['username']);
    $email = sanitize_email($_POST['email']);
    $password = sanitize_text_field($_POST['password']);

    // Strong password validation
    if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[\W_]/', $password)) 
    {
        wp_send_json_error(__('Password must be at least 8 characters long and include one uppercase letter and one special character.', 'register-todo'));
    }

    $user_id = wp_create_user($username, $password, $email);

    if (is_wp_error($user_id)) 
    {
        wp_send_json_error($user_id->get_error_message());
    }

    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id);

    wp_send_json_success(array(
        'redirect' => home_url('/login'),
        'message'  => __('Registration successful! You are now logged in.', 'register-todo')
    ));
}

// Register AJAX actions
add_action('wp_ajax_rtd_register_user', 'rtd_handle_registration');
add_action('wp_ajax_nopriv_rtd_register_user', 'rtd_handle_registration');


<?php

function rtd_login_form() 
{
    ob_start(); ?>
    <div class="rtd-form-container rtd-login-form-container">
        <h2><?php _e('Login', 'register-todo'); ?></h2>
        <form id="rtd-login-form" method="post">
            <!-- Nonce for security -->
            <?php wp_nonce_field('rtd_login_action', 'rtd_login_nonce'); ?>

            <label for="username"><?php _e('Username', 'register-todo'); ?></label>
            <input type="text" name="username" id="username" required>

            <label for="password"><?php _e('Password', 'register-todo'); ?></label>
            <input type="password" name="password" id="password" required>

            <input type="submit" name="login" value="<?php _e('Login', 'register-todo'); ?>">
        </form>
        <p><?php _e('Not registered? <a href="' . esc_url(home_url('/register')) . '">Register Yourself</a>.', 'register-todo'); ?></p>
    </div>
    <?php
    return ob_get_clean();
}

function rtd_handle_login() 
{
    // Check nonce field is valid
    if (!isset($_POST['rtd_login_nonce']) || !wp_verify_nonce($_POST['rtd_login_nonce'], 'rtd_login_action')) 
    {
        wp_send_json_error(__('Nonce verification failed', 'register-todo'));
    }

    $username = sanitize_user($_POST['username']);
    $password = sanitize_text_field($_POST['password']);

    $user = wp_signon(array('user_login' => $username, 'user_password' => $password, 'remember' => true), false);

    if (is_wp_error($user)) 
    {
        wp_send_json_error($user->get_error_message());
    }

    wp_send_json_success(array(
        'redirect' => home_url('/todo'),
        'message'  => __('Login successful! You will now be redirected to your todo list.', 'register-todo')
    ));
}

add_action('wp_ajax_rtd_login_user', 'rtd_handle_login');
add_action('wp_ajax_nopriv_rtd_login_user', 'rtd_handle_login');


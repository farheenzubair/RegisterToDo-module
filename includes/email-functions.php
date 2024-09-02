<?php
// Function to send email with pending tasks
function rtd_send_pending_tasks_email() {
    global $wpdb;

    $users = get_users(); // Get all registered users

    foreach ($users as $user) {
        $user_id = $user->ID;

        // Query for pending tasks
        $tasks = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT task_name FROM {$wpdb->prefix}rtd_todos WHERE user_id = %d AND status = 'pending'",
                $user_id
            )
        );

        if ($tasks) {
            // Create email content
            $task_list = "";
            foreach ($tasks as $task) {
                $task_list .= "- " . esc_html($task->task_name) . "<br>";
            }

            $subject = __('Pending Task Reminder', 'register-todo'); // Updated subject line
            $message = sprintf(
                __("Hello %s,<br><br>Here are your pending tasks:<br><br>%s<br>Best regards,<br>Your To-Do List Team", 'register-todo'),
                esc_html($user->display_name),
                $task_list
            );

            // Email headers
            $headers = array('Content-Type: text/html; charset=UTF-8');

            // Send the email
            $sent = wp_mail($user->user_email, $subject, $message, $headers);

            if (!$sent) {
                error_log("Failed to send email to $user->user_email");
            } else {
                error_log("Email sent to $user->user_email with subject: $subject");
            }
        }
    }
}
add_action('rtd_daily_task_email', 'rtd_send_pending_tasks_email');

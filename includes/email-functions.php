<?php
// Function to send email with pending tasks
function rtd_send_pending_tasks_email() {
    global $wpdb;

    $users = get_users(); // Get all users
    foreach ($users as $user) {
        $user_id = $user->ID;
        $user_email = $user->user_email;

        // Query for pending tasks
        $tasks = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT task_name FROM {$wpdb->prefix}rtd_todos WHERE user_id = %d AND status = 'pending'",
                $user_id
            )
        );

        if ($tasks) {
            $subject = __('Your Pending Tasks', 'register-todo');
            $message = __('Here are your pending tasks:', 'register-todo') . "\n\n";

            foreach ($tasks as $task) {
                $message .= $task->task_name . "\n";
            }

            wp_mail($user_email, $subject, $message);
        }
    }
}
add_action('rtd_daily_task_email', 'rtd_send_pending_tasks_email');

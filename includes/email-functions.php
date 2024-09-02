<?php

function rtd_send_pending_tasks_email() {
    global $wpdb;

    $users = get_users(); 

    foreach ($users as $user) {
        $user_id = $user->ID;

        $tasks = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT task_name FROM {$wpdb->prefix}rtd_todos WHERE user_id = %d AND status = 'pending'",
                $user_id
            )
        );

        if ($tasks) {
            $task_list = "";
            foreach ($tasks as $task) {
                $task_list .= "- " . esc_html($task->task_name) . "<br>";
            }

            $subject = __('Pending Task Reminder', 'register-todo');
            $message = sprintf(
                __("Hello %s,<br><br>your pending tasks:<br><br>%s<br>Best regards,<br>Your To-Do List Team", 'register-todo'),
                esc_html($user->display_name),
                $task_list
            );

            $headers = array('Content-Type: text/html; charset=UTF-8');

            // Send the email
            wp_mail($user->user_email, $subject, $message, $headers);
        }
    }
}

add_action('rtd_daily_task_email', 'rtd_send_pending_tasks_email');

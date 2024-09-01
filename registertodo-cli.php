<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('WP_CLI')) {
    return;
}

class RegisterToDo_CLI_Command {

    public function __construct() {
        WP_CLI::add_command('registertodo', $this);
    }

    public function add_todo_item($args, $assoc_args) {
        global $wpdb;

        $user_id = intval($args[0]);
        $task = sanitize_text_field($assoc_args['task']);

        if (empty($task)) {
            WP_CLI::error(__('Task is required', 'registertodo'));
        }

        $table_name = $wpdb->prefix . 'todos';

        $result = $wpdb->insert(
            $table_name,
            [
                'user_id' => $user_id,
                'task' => $task,
                'status' => 'pending',
                'created_at' => current_time('mysql'),
            ]
        );

        if ($result === false) {
            WP_CLI::error(__('Failed to add todo item', 'registertodo'));
        } else {
            WP_CLI::success(__('Todo item added successfully', 'registertodo'));
        }
    }

    public function get_todo_items($args, $assoc_args) {
        global $wpdb;

        $user_id = intval($args[0]);
        $table_name = $wpdb->prefix . 'todos';

        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE user_id = %d",
                $user_id
            )
        );

        if (empty($results)) {
            WP_CLI::success(__('No todo items found for user', 'registertodo'));
            return;
        }

        foreach ($results as $result) {
            WP_CLI::line("ID: {$result->id}, Task: {$result->task}, Status: {$result->status}");
        }
    }

    public function change_status($args, $assoc_args) {
        global $wpdb;

        $task_id = intval($args[0]);
        $status = sanitize_text_field($assoc_args['status']);
        $table_name = $wpdb->prefix . 'todos';

        $updated = $wpdb->update(
            $table_name,
            ['status' => $status],
            ['id' => $task_id]
        );

        if ($updated === false) {
            WP_CLI::error(__('Failed to update task status', 'registertodo'));
        } else {
            WP_CLI::success(__('Task status updated successfully', 'registertodo'));
        }
    }
}

// Register the CLI command
WP_CLI::add_command('registertodo', 'RegisterToDo_CLI_Command');

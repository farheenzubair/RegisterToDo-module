<?php

function rtd_todo_form() {
    ob_start(); ?>
    <div class="rtd-form-container rtd-todo-form-container">
        <h2><?php _e('Todo List', 'register-todo'); ?></h2>

        <form id="rtd-todo-form" method="post">
            <!-- Nonce for security -->
            <?php wp_nonce_field('rtd_todo_action', 'rtd_todo_nonce'); ?>

            <input type="text" name="todo" id="rtd-todo-input" placeholder="<?php _e('Add a new todo', 'register-todo'); ?>" required>
            <input type="submit" name="add_todo" value="<?php _e('Add Todo', 'register-todo'); ?>">
        </form>

        <ul id="rtd-todo-list">
            <?php
            global $wpdb;
            $current_user = wp_get_current_user();
            $todos = get_user_meta($current_user->ID, 'rtd_todos', true);

            if (!empty($todos)) {
                foreach ($todos as $index => $todo) {
                    // Ensure $todo is an array
                    if (is_array($todo) && isset($todo['text'])) {
                        echo '<li>';
                        echo '<strong>' . esc_html($todo['text']) . '</strong>';
                        echo '<div class="rtd-todo-actions">';
                        echo '<button class="rtd-edit-todo" data-index="' . esc_attr($index) . '">' . __('Edit', 'register-todo') . '</button>';
                        echo '<button class="rtd-delete-todo" data-index="' . esc_attr($index) . '">' . __('Delete', 'register-todo') . '</button>';
                        echo '<button class="rtd-complete-todo" data-index="' . esc_attr($index) . '">' . __('Complete', 'register-todo') . '</button>';
                        echo '</div>';
                        echo '</li>';
                    }
                }
            }
            ?>
        </ul>
    </div>
    <?php
    return ob_get_clean();
}


function rtd_handle_todo() {
    // Check if nonce is valid
    if (!isset($_POST['rtd_todo_nonce']) || !wp_verify_nonce($_POST['rtd_todo_nonce'], 'rtd_todo_action')) 
    {
        wp_send_json_error(__('Nonce verification failed', 'register-todo'));
    }

    $action = isset($_POST['action']) ? sanitize_text_field($_POST['action']) : '';

    if (isset($_POST['action']) && $_POST['action'] == 'rtd_add_todo') {
        $todo = sanitize_text_field($_POST['todo']);
        $current_user = wp_get_current_user();
        $existing_todos = get_user_meta($current_user->ID, 'rtd_todos', true);
        if (!$existing_todos) $existing_todos = array();
        $existing_todos[] = array('text' => $todo);
        update_user_meta($current_user->ID, 'rtd_todos', $existing_todos);
        wp_send_json_success(array('redirect' => home_url('/todo')));
    }
    

    if ($action == 'rtd_update_todo') 
    {
        $index = intval($_POST['index']);
        $new_todo_text = sanitize_text_field($_POST['todo']);
        $current_user = wp_get_current_user();
        $existing_todos = get_user_meta($current_user->ID, 'rtd_todos', true);

        if (isset($existing_todos[$index])) {
            $existing_todos[$index]['text'] = $new_todo_text;
            update_user_meta($current_user->ID, 'rtd_todos', $existing_todos);
            wp_send_json_success(array('redirect' => home_url('/todo')));
        } else {
            wp_send_json_error(__('Todo item not found', 'register-todo'));
        }
    }

    if ($action == 'rtd_delete_todo') 
    {
        $index = intval($_POST['index']);
        $current_user = wp_get_current_user();
        $existing_todos = get_user_meta($current_user->ID, 'rtd_todos', true);
        if (isset($existing_todos[$index])) {
            unset($existing_todos[$index]);
            $existing_todos = array_values($existing_todos); // Reindex array
            update_user_meta($current_user->ID, 'rtd_todos', $existing_todos);
            wp_send_json_success(array('redirect' => home_url('/todo')));
        } else {
            wp_send_json_error(__('Todo item not found', 'register-todo'));
        }
    }

    if ($action == 'rtd_complete_todo') 
    {
        $index = intval($_POST['index']);
        $current_user = wp_get_current_user();
        $existing_todos = get_user_meta($current_user->ID, 'rtd_todos', true);
        if (isset($existing_todos[$index])) {
            $existing_todos[$index]['status'] = 'completed';
            update_user_meta($current_user->ID, 'rtd_todos', $existing_todos);
            wp_send_json_success(array('redirect' => home_url('/todo')));
        } else {
            wp_send_json_error(__('Todo item not found', 'register-todo'));
        }
    }
}
add_action('wp_ajax_rtd_add_todo', 'rtd_handle_todo');
add_action('wp_ajax_rtd_update_todo', 'rtd_handle_todo');
add_action('wp_ajax_rtd_delete_todo', 'rtd_handle_todo');
add_action('wp_ajax_rtd_complete_todo', 'rtd_handle_todo');

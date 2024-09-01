<?php
// Register REST API routes
add_action('rest_api_init', function () {
    register_rest_route('registertodo/v1', '/todos/', array(
        'methods'  => 'GET',
        'callback' => 'get_user_todos',
        'permission_callback' => function () {
            return is_user_logged_in();
        },
    ));
    
    
    register_rest_route('registertodo/v1', '/todos/', array(
        'methods'  => 'POST',
        'callback' => 'add_user_todo',
        'permission_callback' => function () {
            return is_user_logged_in();
        },
        'args'     => array(
            'user_id' => array(
                'required' => true,
                'validate_callback' => function ($param, $request, $key) {
                    return is_int($param);
                },
            ),
            'item'    => array(
                'required' => true,
                'validate_callback' => function ($param, $request, $key) {
                    return is_string($param);
                },
            ),
            'status'  => array(
                'required' => true,
                'validate_callback' => function ($param, $request, $key) {
                    return in_array($param, array('pending', 'completed'));
                },
            ),
        ),
    ));

    register_rest_route('registertodo/v1', '/todos/(?P<task_id>\d+)', array(
        'methods'  => 'PUT',
        'callback' => 'update_todo_status',
        'permission_callback' => function () {
            return is_user_logged_in();
        },
        'args'     => array(
            'status' => array(
                'required' => true,
                'validate_callback' => function ($param, $request, $key) {
                    return in_array($param, array('pending', 'completed'));
                },
            ),
        ),
    ));
});

// Define your REST API callbacks here
function get_user_todos($data) {
    $user_id = get_current_user_id();
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'todos';
    $results = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table_name WHERE user_id = %d",
        $user_id
    ), ARRAY_A);

    return new WP_REST_Response($results, 200);
}

function add_user_todo($data) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'todos';

    $user_id = intval($data['user_id']);
    $item = sanitize_text_field($data['item']);
    $status = sanitize_text_field($data['status']);
    
    $result = $wpdb->insert(
        $table_name,
        array(
            'user_id' => $user_id,
            'item'    => $item,
            'status'  => $status,
        ),
        array(
            '%d',
            '%s',
            '%s'
        )
    );

    if ($result) {
        $task_id = $wpdb->insert_id;
        return new WP_REST_Response(array('task_id' => $task_id), 201);
    } else {
        return new WP_Error('cant_add_todo', 'Could not add to-do item', array('status' => 500));
    }
}

function update_todo_status($data) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'todos';

    $task_id = intval($data['task_id']);
    $status = sanitize_text_field($data['status']);

    $result = $wpdb->update(
        $table_name,
        array('status' => $status),
        array('id' => $task_id),
        array('%s'),
        array('%d')
    );

    if ($result !== false) {
        return new WP_REST_Response(true, 200);
    } else {
        return new WP_REST_Response(false, 500);
    }
}

// Create todos table if it doesn't exist
function rtd_create_todos_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'todos';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        user_id bigint(20) NOT NULL,
        item text NOT NULL,
        status varchar(20) NOT NULL DEFAULT 'pending',
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

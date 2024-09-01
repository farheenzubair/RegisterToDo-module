<?php
/*
Plugin Name: RegisterToDo
Description: A plugin for user registration, login, and todo management with AJAX validation.
Author: Farheen
Text Domain: register-todo
Domain Path: /languages
*/

// Ensure WordPress is loaded
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
// Include WP-CLI commands
if (defined('WP_CLI') && WP_CLI) {
    require_once plugin_dir_path(__FILE__) . 'includes/wp-cli-commands.php';
}


// Activation and deactivation hooks
function rtd_activate() {
    // Create custom database tables if needed
    rtd_create_todos_table();
}

function rtd_deactivate() {
    // Cleanup if needed
}

register_activation_hook(__FILE__, 'rtd_activate');
register_deactivation_hook(__FILE__, 'rtd_deactivate');

// Include files
require_once plugin_dir_path(__FILE__) . 'includes/register-form.php';
require_once plugin_dir_path(__FILE__) . 'includes/login-form.php';
require_once plugin_dir_path(__FILE__) . 'includes/todo-form.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes.php'; 
require_once plugin_dir_path(__FILE__) . 'includes/rest-api.php'; // Include REST API routes

// Enqueue scripts and styles
function rtd_enqueue_scripts() {
    wp_enqueue_style('rtd-style', plugin_dir_url(__FILE__) . 'public/css/register-todo-style.css');
    wp_enqueue_script('rtd-script', plugin_dir_url(__FILE__) . 'public/js/register-todo-script.js', array('jquery'), null, true);

    // Localize script to pass AJAX URL and nonce
    wp_localize_script('rtd-script', 'rtd_ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'register_nonce' => wp_create_nonce('rtd_register_nonce'),
        'login_nonce' => wp_create_nonce('rtd_login_nonce'),
        'todo_nonce' => wp_create_nonce('rtd_todo_nonce'),
        'password_validation_message' => __('Password must be at least 8 characters long and include one uppercase letter and one special character.', 'register-todo'),
        'new_todo_prompt' => __('Enter new todo item:', 'register-todo'),
        'delete_todo_confirm' => __('Are you sure you want to delete this todo?', 'register-todo'),
    ));
}

add_action('wp_enqueue_scripts', 'rtd_enqueue_scripts');

// Load text domain for translations
function rtd_load_textdomain() {
    load_plugin_textdomain('register-todo', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'rtd_load_textdomain');

// REST API routes
require_once plugin_dir_path(__FILE__) . 'includes/rest-api.php';

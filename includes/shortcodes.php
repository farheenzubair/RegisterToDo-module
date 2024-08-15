<?php

function rtd_register_shortcodes() 
{
    add_shortcode('register_form', 'rtd_register_form');
    add_shortcode('login_form', 'rtd_login_form');
    add_shortcode('todo_form', 'rtd_todo_form');
}

add_action('init', 'rtd_register_shortcodes');

jQuery(document).ready(function($) 
{
    // Handle Register Form
    $('#rtd-register-form').on('submit', function(e) 
    {
        e.preventDefault();

        // Password Validation
        var password = $('#password').val();
        var passwordPattern = /^(?=.*[A-Z])(?=.*[\W_]).{8,}$/; // 8 chars, 1 uppercase, 1 special char
        if (!passwordPattern.test(password)) 
        {
            alert(rtd_ajax_object.password_validation_message);
            return;
        }

        var formData = $(this).serialize() + '&action=rtd_register_user';

        // AJAX request to backend 
        $.post(rtd_ajax_object.ajax_url, formData, function(response) 
        {
            // Response from backend, redirect page to URL 
            if (response.success) 
            {
                alert(response.data.message); // Display success message
                window.location.href = response.data.redirect;
            } 
            else 
            {
                alert(response.data);
            }
        });
    });

    // Handle Login Form on submit
    $('#rtd-login-form').on('submit', function(e) 
    {
        e.preventDefault();

        var formData = $(this).serialize() + '&action=rtd_login_user';

        $.post(rtd_ajax_object.ajax_url, formData, function(response) 
        {
            if (response.success) 
            {
                alert(response.data.message); // Display success message
                window.location.href = response.data.redirect;
            } 
            else 
            {
                alert(response.data.message);
            }
        });
    });

    // Handle Add Todo
    $('#rtd-todo-form').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize() + '&action=rtd_add_todo';

        $.post(rtd_ajax_object.ajax_url, formData, function(response) {
            if (response.success) {
                location.reload(); // Reload to show the updated list
            } else {
                alert(response.data.message);
            }
        });
    });

    // Handle Update Todo
    $(document).on('click', '.rtd-edit-todo', function() {
        var index = $(this).data('index');
        var newTodo = prompt(rtd_ajax_object.new_todo_prompt);

        if (newTodo) {
            $.post(rtd_ajax_object.ajax_url, {
                action: 'rtd_update_todo',
                index: index,
                todo: newTodo,
                rtd_todo_nonce: $('#rtd-todo-form').find('input[name="rtd_todo_nonce"]').val()
            }, function(response) {
                if (response.success) {
                    location.reload(); // Reload to show the updated list
                } else {
                    alert(response.data);
                }
            });
        }
    });

    // Handle Delete Todo
    $(document).on('click', '.rtd-delete-todo', function() {
        if (confirm(rtd_ajax_object.delete_todo_confirm)) {
            var index = $(this).data('index');

            $.post(rtd_ajax_object.ajax_url, {
                action: 'rtd_delete_todo',
                index: index,
                rtd_todo_nonce: $('#rtd-todo-form').find('input[name="rtd_todo_nonce"]').val()
            }, function(response) {
                if (response.success) {
                    location.reload(); // Reload to show the updated list
                } else {
                    alert(response.data);
                }
            });
        }
    });

    // Handle Complete Todo
    $(document).on('click', '.rtd-complete-todo', function() {
        var index = $(this).data('index');

        $.post(rtd_ajax_object.ajax_url, {
            action: 'rtd_complete_todo',
            index: index,
            rtd_todo_nonce: $('#rtd-todo-form').find('input[name="rtd_todo_nonce"]').val()
        }, function(response) {
            if (response.success) {
                location.reload(); // Reload to show the updated list
            } else {
                alert(response.data);
            }
        });
    });
});

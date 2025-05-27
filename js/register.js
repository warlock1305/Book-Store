$(document).ready(function () {
    $('form').on('submit', function (e) {
        e.preventDefault();
        const userData = {
            first_name: $('#first_name').val(),
            last_name: $('#last_name').val(),
            username: $('#username').val(),
            email: $('#email').val(),
            password: $('#password').val(),
            confirm_password: $('#confirm_password').val()
        };

        $.ajax({
            url: 'http://localhost/Book%20store/auth/register',
            method: 'POST',
            data: userData,
            success: function (response) {
                alert('Registration successful!');
                window.location.href = 'login.html';
            },
            error: function (xhr) {
                alert('Registration failed: ' + xhr.responseText);
            }
        });
    });
});
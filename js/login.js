$(document).ready(function () {

  $.ajaxSetup({
    beforeSend: function (xhr) {
      const token = localStorage.getItem('token');
      console.log('Setting Authentication header with token:', token);
      if (token) {
        xhr.setRequestHeader('Authentication', token);
      }
    }
  });

  $('form').on('submit', function (e) {
    e.preventDefault();

    const loginData = {
      email_or_username: $('#email_or_username').val(),
      password: $('#password').val()
    };

    $.ajax({
      url: 'http://localhost/Book%20store/auth/login',
      method: 'POST',
      data: loginData,
      success: function (response) {
        alert('Login successful!' + response.data.token);
        localStorage.setItem('token', response.data.token);
        window.location.href = 'home.html';
      },
      error: function (xhr) {
        alert('Login failed: ' + xhr.responseText);
      }
    });
  });
});

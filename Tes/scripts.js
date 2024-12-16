document.getElementById('loginForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const errorElement = document.getElementById('error');

    fetch('login_process.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `username=${username}&password=${password}`,
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = 'admin.php';
        } else {
            errorElement.textContent = data.message || 'Login failed. Please try again.';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        errorElement.textContent = 'An unexpected error occurred.';
    });
});

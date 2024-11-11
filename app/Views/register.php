<?= $this->extend('layouts/login_layout') ?>

<?= $this->section('content') ?>
<h3 class="text-center mb-4">Register for DeskApp</h3>

<!-- Alert messages for success or error -->
<div id="alertMessage" class="alert d-none" role="alert"></div>

<form id="registerForm">
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person"></i></span>
            <input type="text" class="form-control" id="username" name="username" required placeholder="Enter your username">
        </div>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" class="form-control" id="email" name="email" required placeholder="Enter your email">
        </div>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" class="form-control" id="password" name="password" required placeholder="Enter your password">
        </div>
    </div>

    <div class="mb-3">
        <label for="confirmPassword" class="form-label">Confirm Password</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required placeholder="Confirm your password">
        </div>
    </div>

    <div class="mb-3">
        <label for="role" class="form-label">Role</label>
        <select class="form-select" id="role" name="role">
            <option value="supplier">Supplier</option>
            <option value="contractor">Contractor</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary w-100 mb-3">Register</button>

    <div class="text-center">Already have an account? <a href="/login" class="text-decoration-none">Login here</a></div>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#registerForm').on('submit', function(event) {
            event.preventDefault(); // Prevent traditional form submission

            // Hide alert message if visible
            $('#alertMessage').addClass('d-none').removeClass('alert-success alert-danger');

            const password = $('#password').val();
            const confirmPassword = $('#confirmPassword').val();

            if (password !== confirmPassword) {
                $('#alertMessage').removeClass('d-none alert-success').addClass('alert-danger').text("Passwords do not match.");
                return;
            }

            const formData = {
                username: $('#username').val(),
                email: $('#email').val(),
                password: password,
                role: $('#role').val()
            };

            $.ajax({
                url: 'http://localhost/auth/register',
                type: 'POST',
                data: JSON.stringify(formData),
                contentType: 'application/json',
                success: function(response) {
                    $('#alertMessage').removeClass('d-none alert-danger').addClass('alert-success').text('Registration successful! Redirecting to login...');
                    setTimeout(function() {
                        window.location.href = '/login';
                    }, 1500);
                },
                error: function(error) {
                    const errorMessage = error.responseJSON ? error.responseJSON.message : 'Registration failed. Please try again.';
                    $('#alertMessage').removeClass('d-none alert-success').addClass('alert-danger').text(errorMessage);
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>
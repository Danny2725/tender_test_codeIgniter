<?= $this->extend('layouts/login_layout') ?>

<?= $this->section('content') ?>
<h3 class="text-center mb-4">Login To DeskApp</h3>

<!-- Alert messages for success or error -->
<div id="alertMessage" class="alert d-none" role="alert"></div>

<form id="loginForm">
    <input type="hidden" id="role" name="role" value="contractor">
    
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="text" class="form-control" id="email" name="email" required placeholder="Enter your email">
        </div>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" class="form-control" id="password" name="password" required placeholder="Enter your password">
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="remember" name="remember">
            <label class="form-check-label" for="remember">Remember</label>
        </div>
        <a href="#" class="text-decoration-none">Forgot Password</a>
    </div>

    <button type="submit" class="btn btn-primary w-100 mb-3">Sign In</button>

    <div class="text-center">OR</div>

    <a href="/register" class="btn btn-outline-primary w-100 mt-3">Register To Create Account</a>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#loginForm').on('submit', function(event) {
            event.preventDefault(); 
            
            $('#alertMessage').addClass('d-none').removeClass('alert-success alert-danger');

            const formData = {
                email: $('#email').val(),
                password: $('#password').val(),
            };
            $.ajax({
                url: 'http://localhost/auth/login',
                type: 'POST',
                data: JSON.stringify(formData),
                contentType: 'application/json',
                success: function(response) {
                    document.cookie = `token=${response.token}; path=/; max-age=3600`; 
                    let redirectUrl = '';
                    if (response.user.role === 'contractor') {
                        redirectUrl = '/tender/list_contractor';
                    } else if (response.user.role === 'supplier') {
                        redirectUrl = '/tender/list_supplier';
                    }
                    if (redirectUrl) {
                        $('#alertMessage').removeClass('d-none alert-danger').addClass('alert-success').text('Login successful!');
                        setTimeout(function() {
                            window.location.href = redirectUrl;
                        }, 1500);
                    } else {
                        $('#alertMessage').removeClass('d-none alert-success').addClass('alert-danger').text('Invalid role.');
                    }
                },
                error: function(error) {
                    const errorMessage = error.responseJSON ? error.responseJSON.message : 'Login failed. Please try again.';
                    $('#alertMessage').removeClass('d-none alert-success').addClass('alert-danger').text(errorMessage);
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>
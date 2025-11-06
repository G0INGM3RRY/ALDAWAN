<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="{{ asset('css/loading-animations.css') }}" rel="stylesheet">
    <title>Login</title>
</head>
<body class="bg-light">

<div class="container-fluid vh-100 d-flex align-items-center justify-content-center">
  <div class="row w-100 justify-content-center">
    <div class="col-md-6 col-lg-4">
      <div class="card shadow">
        <div class="card-body p-4">
          <h2 class="card-title text-center mb-4">Login</h2>

          <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
              <label for="email" id="email-label" class="form-label">Email</label>
              <input type="email" id="email" name="email" class="form-control" required autofocus autocomplete="username" value="{{ old('email') }}">
              @error('email')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <div class="input-group">
                <input type="password" name="password" id="password" class="form-control border-end-0" required autocomplete="current-password">
                <button class="btn border-start-0" type="button" id="togglePassword" style="background-color: white; border-color: #dee2e6;">
                  <i class="bi bi-eye" id="togglePasswordIcon" style="color: #6c757d; font-size: 0.9rem;"></i>
                </button>
              </div>
              @error('password')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3 form-check">
              <input type="checkbox" name="remember" class="form-check-input" id="remember">
              <label class="form-check-label" for="remember">Remember Me</label>
            </div>

            @if(session('status'))
              <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @if(session('error'))
              <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="d-grid">
              <button type="submit" class="btn btn-primary">Login</button>
            </div>

            <div class="text-center mt-3">
              @if (Route::has('password.request'))
                <a class="btn btn-link" href="{{ route('password.request') }}">Forgot your password?</a>
              @endif
            </div>
            <div class="text-center mt-3">
              <a href="{{ route('register') }}" class="btn btn-link">Doesn’t have an account?</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// Password visibility toggle
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('togglePasswordIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('bi-eye');
        toggleIcon.classList.add('bi-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('bi-eye-slash');
        toggleIcon.classList.add('bi-eye');
    }
});
</script>
<script src="{{ asset('js/loading-animations.js') }}"></script>

</body>
</html>

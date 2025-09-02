<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Forgot Password</title>
</head>
<body class="bg-light">

<div class="container-fluid vh-100 d-flex align-items-center justify-content-center">
  <div class="row w-100 justify-content-center">
    <div class="col-md-6 col-lg-4">
      <div class="card shadow">
        <div class="card-body p-4">
          <h2 class="card-title text-center mb-4">Forgot Password</h2>
          
          <div class="alert alert-info mb-4">
            <small>Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</small>
          </div>

          @if(session('status'))
            <div class="alert alert-success mb-4">
              {{ session('status') }}
            </div>
          @endif

          <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" name="email" class="form-control" required autofocus value="{{ old('email') }}">
              @error('email')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-primary">Email Password Reset Link</button>
            </div>
          </form>

          <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="text-decoration-none">← Back to Login</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

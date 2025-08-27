<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
              <label for="email" class="form-label">Email</label>
              <input type="email" name="email" class="form-control" required autofocus autocomplete="username" value="{{ old('email') }}">
              @error('email')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" name="password" class="form-control" required autocomplete="current-password">
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

</body>
</html>

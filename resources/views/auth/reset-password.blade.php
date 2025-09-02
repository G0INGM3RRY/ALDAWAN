<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Reset Password</title>
</head>
<body class="bg-light">

<div class="container-fluid vh-100 d-flex align-items-center justify-content-center">
  <div class="row w-100 justify-content-center">
    <div class="col-md-6 col-lg-4">
      <div class="card shadow">
        <div class="card-body p-4">
          <h2 class="card-title text-center mb-4">Reset Password</h2>

          <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" name="email" class="form-control" required autofocus autocomplete="username" value="{{ old('email', $request->email) }}">
              @error('email')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">New Password</label>
              <input type="password" name="password" class="form-control" required autocomplete="new-password">
              @error('password')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
              <label for="password_confirmation" class="form-label">Confirm Password</label>
              <input type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
              @error('password_confirmation')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-primary">Reset Password</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

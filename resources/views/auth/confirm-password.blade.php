<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Confirm Password</title>
</head>
<body class="bg-light">

<div class="container-fluid vh-100 d-flex align-items-center justify-content-center">
  <div class="row w-100 justify-content-center">
    <div class="col-md-6 col-lg-4">
      <div class="card shadow">
        <div class="card-body p-4">
          <h2 class="card-title text-center mb-4">Confirm Password</h2>
          
          <div class="alert alert-warning mb-4">
            <small>This is a secure area of the application. Please confirm your password before continuing.</small>
          </div>

          <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" name="password" class="form-control" required autocomplete="current-password">
              @error('password')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-primary">Confirm</button>
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

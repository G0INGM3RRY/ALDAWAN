<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Verify Email</title>
</head>
<body class="bg-light">

<div class="container-fluid vh-100 d-flex align-items-center justify-content-center">
  <div class="row w-100 justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="card shadow">
        <div class="card-body p-4">
          <h2 class="card-title text-center mb-4">Verify Your Email</h2>
          
          <div class="alert alert-info mb-4">
            <small>Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.</small>
          </div>

          @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success mb-4">
              A new verification link has been sent to the email address you provided during registration.
            </div>
          @endif

          <div class="d-flex justify-content-between align-items-center">
            <form method="POST" action="{{ route('verification.send') }}">
              @csrf
              <button type="submit" class="btn btn-primary">Resend Verification Email</button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="btn btn-outline-secondary">Log Out</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

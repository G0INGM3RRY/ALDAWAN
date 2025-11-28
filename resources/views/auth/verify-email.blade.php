<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Verify Email</title>
    <script>
        // Check verification status every 3 seconds
        let checkInterval = setInterval(function() {
            fetch('{{ route("verification.check") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.verified) {
                        clearInterval(checkInterval);
                        // Show success message briefly
                        document.body.innerHTML = `
                            <div class="container-fluid vh-100 d-flex align-items-center justify-content-center">
                                <div class="text-center">
                                    <div class="mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-check-circle-fill text-primary" viewBox="0 0 16 16">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-primary">Email Verified!</h3>
                                    <p class="text-muted">Redirecting to dashboard...</p>
                                </div>
                            </div>
                        `;
                        // Redirect after 1.5 seconds
                        setTimeout(function() {
                            window.location.href = '{{ route("dashboard") }}?verified=1';
                        }, 1500);
                    }
                })
                .catch(error => console.log('Checking verification status...'));
        }, 3000); // Check every 3 seconds

        // Also listen for storage events (for same-device tabs)
        window.addEventListener('storage', function(e) {
            if (e.key === 'email_verified' && e.newValue === 'true') {
                localStorage.removeItem('email_verified');
                window.location.reload();
            }
        });
    </script>
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

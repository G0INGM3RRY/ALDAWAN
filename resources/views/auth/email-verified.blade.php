<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Email Verified</title>
</head>
<body class="bg-light">

<div class="container-fluid vh-100 d-flex align-items-center justify-content-center">
    <div class="row w-100 justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow text-center">
                <div class="card-body p-5">
                    <div class="mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-check-circle-fill text-primary" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                        </svg>
                    </div>
                    <h3 class="mb-3 text-primary">Email Verified!</h3>
                    <p class="text-muted mb-4">Your email has been successfully verified.</p>
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Redirecting...</span>
                    </div>
                    <p class="text-muted small mt-3">Redirecting to dashboard...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Notify other tabs that email has been verified
    localStorage.setItem('email_verified', 'true');
    
    // Try to close this tab (works if opened via window.open or from email)
    setTimeout(function() {
        window.close();
    }, 500);
    
    // If tab didn't close (can't close tabs not opened by script), redirect after 2 seconds
    setTimeout(function() {
        // Check if window is still open
        if (!window.closed) {
            window.location.href = "{{ route('dashboard') }}?verified=1";
        }
    }, 2000);
    
    // Clear the localStorage flag after notification
    setTimeout(function() {
        localStorage.removeItem('email_verified');
    }, 1000);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

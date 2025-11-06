<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loading Animations Demo - ALDAWAN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/loading-animations.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <h1 class="mb-4">ALDAWAN Loading Animations Demo</h1>
        
        <div class="row">
            <!-- Form Submission Loading -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">1. Form Submission Loading</h5>
                    </div>
                    <div class="card-body">
                        <p>When you submit a form, the button automatically shows a loading spinner:</p>
                        <form action="#" method="POST" onsubmit="event.preventDefault(); setTimeout(() => location.reload(), 2000);">
                            <div class="mb-3">
                                <input type="text" class="form-control" placeholder="Enter something..." required>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>Submit Form
                            </button>
                        </form>
                        <small class="text-muted d-block mt-2">Try clicking submit to see the loading state!</small>
                    </div>
                </div>
            </div>

            <!-- Page Transition Loading -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">2. Page Transition Loading</h5>
                    </div>
                    <div class="card-body">
                        <p>When you click internal links, a loading overlay appears:</p>
                        <a href="{{ route('dashboard') }}" class="btn btn-success">
                            Go to Dashboard
                        </a>
                        <small class="text-muted d-block mt-2">Click to see the page loader!</small>
                    </div>
                </div>
            </div>

            <!-- Manual Loading Control -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">3. Manual Loading Control</h5>
                    </div>
                    <div class="card-body">
                        <p>You can manually show/hide the page loader:</p>
                        <button onclick="showPageLoader(); setTimeout(hidePageLoader, 2000)" class="btn btn-warning">
                            Show Loader (2 seconds)
                        </button>
                        <small class="text-muted d-block mt-2">Great for AJAX requests!</small>
                    </div>
                </div>
            </div>

            <!-- Inline Spinner -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">4. Inline Spinner</h5>
                    </div>
                    <div class="card-body">
                        <p>Add a small spinner next to text:</p>
                        <button id="inlineBtn" onclick="toggleInlineSpinner()" class="btn btn-info">
                            Loading data...
                        </button>
                        <small class="text-muted d-block mt-2">Click to toggle inline spinner!</small>
                    </div>
                </div>
            </div>

            <!-- Component Usage -->
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">5. Spinner Component</h5>
                    </div>
                    <div class="card-body">
                        <p>You can also use the Blade component anywhere:</p>
                        <div class="d-flex gap-3 align-items-center">
                            <x-loading-spinner />
                            <x-loading-spinner style="width: 2rem; height: 2rem;" />
                            <x-loading-spinner style="width: 3rem; height: 3rem;" />
                        </div>
                        <small class="text-muted d-block mt-2">Usage: &lt;x-loading-spinner /&gt;</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-success">
            <h5>✅ All loading animations are now active across your entire application!</h5>
            <ul class="mb-0">
                <li>All forms automatically show loading state on submit</li>
                <li>All internal links show page transition loader</li>
                <li>Manual controls available for AJAX requests</li>
                <li>Consistent blue theme matching ALDAWAN brand</li>
            </ul>
        </div>

        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">← Back to Dashboard</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/loading-animations.js') }}"></script>
    <script>
        let spinnerShown = false;
        function toggleInlineSpinner() {
            const btn = document.getElementById('inlineBtn');
            if (spinnerShown) {
                hideInlineLoader(btn);
                spinnerShown = false;
            } else {
                showInlineLoader(btn);
                spinnerShown = true;
            }
        }
    </script>
</body>
</html>

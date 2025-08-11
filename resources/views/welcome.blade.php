<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ALDAWAN - Job Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background-color: #f8f9fa;
            padding: 60px 0;
        }
        .search-box {
            background: white;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid #dee2e6;
        }
        .feature-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
        }
        .job-category {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        .navbar-brand {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand text-primary" href="#">ALDAWAN</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Find Jobs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                </ul>
                @if (Route::has('login'))
                    <ul class="navbar-nav">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/dashboard') }}">Dashboard</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">Register</a>
                                </li>
                            @endif
                        @endauth
                    </ul>
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h1 class="display-5 fw-bold mb-4">Find Your Dream Job</h1>
                    <p class="lead mb-5 text-muted">Connect with employers and discover opportunities that match your skills</p>
                    
                    <!-- Job Search Box -->
                    <div class="search-box">
                        <div class="row g-3 justify-content-center">
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="Job title or keywords">
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-primary w-100">Search Jobs</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="h3 fw-bold">Why Choose ALDAWAN?</h2>
                    <p class="text-muted">Your gateway to the best opportunities</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <h5 class="card-title">Top Employers</h5>
                            <p class="card-text text-muted">Connect with leading companies and organizations looking for talented professionals like you.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <h5 class="card-title">Includes Informal Jobs</h5>
                            <p class="card-text text-muted">Small jobs are allowed too.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <h5 class="card-title">PESO Verified Jobs</h5>
                            <p class="card-text text-muted">All job postings are verified and legitimate opportunities.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    

    <!-- Call to Action -->
    <section class="py-5 bg-primary text-white">
        <div class="container">
            <div class="row text-center">
                <div class="col-12">
                    <h2 class="h3 fw-bold mb-4">Ready to Get Started?</h2>
                    <p class="mb-4">Join thousands of job seekers who found their dream jobs through ALDAWAN</p>
                    @if (Route::has('register'))
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-light me-3">Create Account</a>
                        @endguest
                    @endif
                    <a href="#" class="btn btn-outline-light">Browse Jobs</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-4 bg-dark text-white">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>ALDAWAN</h5>
                    <p class="text-muted">Your trusted partner in finding the perfect job opportunity.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted mb-0">&copy; {{ date('Y') }} ALDAWAN. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

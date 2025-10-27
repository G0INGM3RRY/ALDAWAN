<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ALDAWAN - Job Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-standards.css') }}" rel="stylesheet">
    @vite('resources/css/welcome.css')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4" style="background-color: #0d6efd !important;">
        <div class="container-fluid">
            <button class="navbar-toggler me-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand mx-auto" href="{{ route('welcome') }}" style="color: #ffffff !important; font-weight: bold;">ALDAWAN</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('welcome') }}" style="color: #ffffff !important; font-weight: 500;"><i class="fas fa-home me-1"></i>Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about" style="color: #ffffff !important; font-weight: 500;"><i class="fas fa-info-circle me-1"></i>About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#support" style="color: #ffffff !important; font-weight: 500;"><i class="fas fa-life-ring me-1"></i>Support</a>
                    </li>
                </ul>
                @if (Route::has('login'))
                    <ul class="navbar-nav ms-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/dashboard') }}" style="color: #ffffff !important; font-weight: 500;"><i class="fas fa-tachometer-alt me-1"></i>Dashboard</a>
                            </li>
                        @else
                            <li class="nav-item me-2">
                                <a class="nav-link" href="{{ route('login') }}" style="color: #ffffff !important; font-weight: 500;"><i class="fas fa-sign-in-alt me-1"></i>Log In</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}" style="color: #ffffff !important; font-weight: 500;"><i class="fas fa-user-plus me-1"></i>Sign Up</a>
                            </li>
                        @endauth
                    </ul>
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section - Split Layout -->
    <section class="hero-section d-flex align-items-center justify-content-center" style="min-height: calc(100vh - 76px); background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
        <div class="container">
            <!-- Main Content Card -->
            <div class="hero-card bg-white rounded-4 shadow-lg overflow-hidden" style="max-width: 1000px; margin: 0 auto;">
                <div class="row g-0">
                    
                    <!-- Left Content Column -->
                    <div class="col-lg-6 d-flex align-items-center px-5 py-5">
                        <div class="hero-content">
                            <!-- Main Heading -->
                            <h1 class="mb-4">
                                Want a Job? Try what matches to you
                            </h1>
                            
                            <!-- Description -->
                            <p class="text-muted mb-4">
                                Log in to find the job for you
                            </p>
                            
                            <!-- Action Buttons -->
                            <div class="d-flex flex-column flex-sm-row gap-3 mb-4">
                                @guest
                                    <a href="{{ route('login') }}" class="btn btn-primary">
                                        Log In
                                    </a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="btn btn-outline-primary">
                                            Sign Up
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                                        Go to Dashboard
                                    </a>
                                @endguest
                            </div>

                        </div>
                    </div>
                    
                    <!-- Right Image Column -->
                    <div class="col-lg-6 p-4 d-flex align-items-center justify-content-center">
                        <div class="hero-image-container">
                            <img src="{{ asset('images/hero-background.jpg') }}" 
                                 alt="ALDAWAN Job Portal" 
                                 class="img-fluid rounded-3"
                                 style="width: 100%; height: auto; object-fit: cover;">
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>
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
                    <a href="{{ route('login') }}" class="btn btn-outline-light">Browse Jobs</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-4 bg-secondary text-white">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-md-6">
                    <h5 class="text-white">ALDAWAN</h5>
                    <p class="text-light">Your trusted partner in finding the perfect job opportunity.</p>
                    <p class="text-light mb-0">&copy; {{ date('Y') }} ALDAWAN. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

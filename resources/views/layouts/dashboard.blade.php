<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-standards.css') }}" rel="stylesheet">
    @vite('resources/css/dashboard.css')
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4" style="background-color: #0d6efd !important;">
  <div class="container-fluid">
    <button class="navbar-toggler me-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand mx-auto" href="{{ route('dashboard') }}" style="color: #ffffff !important; font-weight: bold;">ALDAWAN</a>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          @if(Auth::user()->role === 'employer')
            <a class="nav-link" href="{{ route('employers.dashboard') }}" style="color: #ffffff !important; font-weight: 500;"><i class="fas fa-tachometer-alt me-1"></i>Dashboard</a>
          @else
            <a class="nav-link" href="{{ route('jobseekers.dashboard') }}" style="color: #ffffff !important; font-weight: 500;"><i class="fas fa-tachometer-alt me-1"></i>Dashboard</a>
          @endif
        </li>
        @if(Auth::user()->role === 'seeker')
        <li class="nav-item">
          <a class="nav-link" href="{{ route('jobs.index') }}" style="color: #ffffff !important; font-weight: 500;"><i class="fas fa-search me-1"></i>Browse Jobs</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('jobseekers.index') }}" style="color: #ffffff !important; font-weight: 500;"><i class="fas fa-user me-1"></i>My Profile</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('jobseekers.applications') }}" style="color: #ffffff !important; font-weight: 500;">My Applications</a>
        </li>
        @endif
        
        @if(Auth::user()->role === 'employer')
        <li class="nav-item">
          <a class="nav-link" href="{{ route('employers.show') }}" style="color: #ffffff !important; font-weight: 500;"><i class="fas fa-building me-1"></i>My Profile</a>
        </li>
        @endif
        
        <!-- Debug: Current user role is: {{ Auth::user()->role }} -->
        <li class="nav-item">
          @if(Auth::user()->role === 'employer')
            <a class="nav-link" href="{{ route('employers.edit') }}" style="color: #ffffff !important; font-weight: 500;"><i class="fas fa-user-edit me-1"></i>Update Profile</a>
          @elseif(Auth::user()->jobseekerProfile && Auth::user()->jobseekerProfile->job_seeker_type === 'informal')
            <a class="nav-link" href="{{ route('jobseekers.informal.edit') }}" style="color: #ffffff !important; font-weight: 500;"><i class="fas fa-user-edit me-1"></i>Update Profile</a>
          @else
            <a class="nav-link" href="{{ route('jobseekers.edit') }}" style="color: #ffffff !important; font-weight: 500;"><i class="fas fa-user-edit me-1"></i>Update Profile</a>
          @endif
        </li>
        
        <li class="nav-item">
          <a class="nav-link" href="{{ route('profile.edit') }}" style="color: #ffffff !important; font-weight: 500;"><i class="fas fa-cog me-1"></i>Account Settings</a>
        </li>
        <li class="nav-item">
          <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-link nav-link" style="color: #ffffff !important; font-weight: 500; text-decoration: none;">Log Out</button>
          </form>
        </li>
      </ul>
    </div>
  </div>
</nav>
<div class="container">
    @yield('content')
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

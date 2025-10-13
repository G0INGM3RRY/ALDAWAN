<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite('resources/css/dashboard.css')
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container-fluid">
    <button class="navbar-toggler me-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand mx-auto" href="{{ route('dashboard') }}">ALDAWAN</a>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          @if(Auth::user()->role === 'employer')
            <a class="nav-link" href="{{ route('employers.dashboard') }}"><i class="fas fa-tachometer-alt me-1"></i>Dashboard</a>
          @else
            <a class="nav-link" href="{{ route('jobseekers.dashboard') }}"><i class="fas fa-tachometer-alt me-1"></i>Dashboard</a>
          @endif
        </li>
        @if(Auth::user()->role === 'seeker')
        <li class="nav-item">
          <a class="nav-link" href="{{ route('jobs.index') }}"><i class="fas fa-search me-1"></i>Browse Jobs</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('jobseekers.index') }}"><i class="fas fa-user me-1"></i>My Profile</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('jobseekers.applications') }}">My Applications</a>
        </li>
        @endif
        
        @if(Auth::user()->role === 'employer')
        <li class="nav-item">
          <a class="nav-link" href="{{ route('employers.show') }}"><i class="fas fa-building me-1"></i>My Profile</a>
        </li>
        @endif
        
        <!-- Debug: Current user role is: {{ Auth::user()->role }} -->
        <li class="nav-item">
          @if(Auth::user()->role === 'employer')
            <a class="nav-link" href="{{ route('employers.edit') }}"><i class="fas fa-user-edit me-1"></i>Update Profile</a>
          @elseif(Auth::user()->jobseekerProfile && Auth::user()->jobseekerProfile->job_seeker_type === 'informal')
            <a class="nav-link" href="{{ route('jobseekers.informal.edit') }}"><i class="fas fa-user-edit me-1"></i>Update Profile</a>
          @else
            <a class="nav-link" href="{{ route('jobseekers.edit') }}"><i class="fas fa-user-edit me-1"></i>Update Profile</a>
          @endif
        </li>
        
        <li class="nav-item">
          <a class="nav-link" href="{{ route('profile.edit') }}"><i class="fas fa-cog me-1"></i>Account Settings</a>
        </li>
        <li class="nav-item">
          <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-link nav-link">Log Out</button>
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

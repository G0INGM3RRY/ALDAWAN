<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/loading-animations.css') }}" rel="stylesheet">
    @vite(['resources/css/bootstrap-standards.css', 'resources/css/dashboard.css'])
    <style>
        /* Active Navigation Indicator */
        .navbar-nav .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }
        .navbar-nav .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2) !important;
            border-radius: 0.25rem;
            font-weight: 600 !important;
        }
        .navbar-nav .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 40%;
            height: 3px;
            background-color: #ffffff;
            border-radius: 2px;
        }
        .navbar-nav .nav-link:hover:not(.active) {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 0.25rem;
        }
    </style>
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
            <a class="nav-link {{ request()->routeIs('employers.dashboard') ? 'active' : '' }}" href="{{ route('employers.dashboard') }}" style="color: #ffffff !important; font-weight: 500;"><i class="fas fa-tachometer-alt me-1"></i>Dashboard</a>
          @else
            <a class="nav-link {{ request()->routeIs('jobseekers.dashboard') ? 'active' : '' }}" href="{{ route('jobseekers.dashboard') }}" style="color: #ffffff !important; font-weight: 500;"><i class="fas fa-tachometer-alt me-1"></i>Dashboard</a>
          @endif
        </li>
        @if(Auth::user()->role === 'seeker')
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('jobs.*') ? 'active' : '' }}" href="{{ route('jobs.index') }}" style="color: #ffffff !important; font-weight: 500;"><i class="fas fa-search me-1"></i>Browse Jobs</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('jobseekers.index') || request()->routeIs('jobseekers.show') ? 'active' : '' }}" href="{{ route('jobseekers.index') }}" style="color: #ffffff !important; font-weight: 500;"><i class="fas fa-user me-1"></i>My Profile</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('jobseekers.applications') ? 'active' : '' }}" href="{{ route('jobseekers.applications') }}" style="color: #ffffff !important; font-weight: 500;">My Applications</a>
        </li>
        @endif
        
        <!-- Notifications Link (All Users) -->
        <li class="nav-item">
          <a class="nav-link position-relative {{ request()->routeIs('notifications.*') ? 'active' : '' }}" href="{{ route('notifications.index') }}" style="color: #ffffff !important; font-weight: 500;">
            <i class="fas fa-bell me-1"></i>Notifications
            @if(Auth::user()->unreadNotificationsCount() > 0)
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ Auth::user()->unreadNotificationsCount() }}
              </span>
            @endif
          </a>
        </li>

        <!-- Messages Link (All Users) -->
        <li class="nav-item">
          <a class="nav-link position-relative {{ request()->routeIs('messages.*') ? 'active' : '' }}" href="{{ route('messages.inbox') }}" style="color: #ffffff !important; font-weight: 500;">
            <i class="fas fa-envelope me-1"></i>Messages
            @if(Auth::user()->unreadMessagesCount() > 0)
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ Auth::user()->unreadMessagesCount() }}
              </span>
            @endif
          </a>
        </li>
        
        @if(Auth::user()->role === 'employer')
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('employers.show') ? 'active' : '' }}" href="{{ route('employers.show') }}" style="color: #ffffff !important; font-weight: 500;"><i class="fas fa-building me-1"></i>My Profile</a>
        </li>
        @endif
        
        <li class="nav-item">
          @if(Auth::user()->role === 'employer')
            <a class="nav-link {{ request()->routeIs('employers.edit') ? 'active' : '' }}" href="{{ route('employers.edit') }}" style="color: #ffffff !important; font-weight: 500;"><i class="fas fa-user-edit me-1"></i>Update Profile</a>
          @elseif(Auth::user()->jobseekerProfile && optional(Auth::user()->jobseekerProfile)->job_seeker_type === 'informal')
            <a class="nav-link {{ request()->routeIs('jobseekers.informal.edit') ? 'active' : '' }}" href="{{ route('jobseekers.informal.edit') }}" style="color: #ffffff !important; font-weight: 500;"><i class="fas fa-user-edit me-1"></i>Update Profile</a>
          @else
            <a class="nav-link {{ request()->routeIs('jobseekers.edit') ? 'active' : '' }}" href="{{ route('jobseekers.edit') }}" style="color: #ffffff !important; font-weight: 500;"><i class="fas fa-user-edit me-1"></i>Update Profile</a>
          @endif
        </li>
        
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.edit') }}" style="color: #ffffff !important; font-weight: 500;"><i class="fas fa-cog me-1"></i>Account Settings</a>
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

<!-- Notification Toast Pop-ups -->
@include('components.notification-toast')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/loading-animations.js') }}"></script>
</body>
</html>

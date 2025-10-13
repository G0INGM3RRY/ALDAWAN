<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Register User</title>
</head>
<body class="bg-light">

<div class="container-fluid vh-100 d-flex align-items-center justify-content-center">
  <div class="row w-100 justify-content-center">
    <div class="col-md-8 col-lg-5">
      <div class="card shadow">
        <div class="card-body p-4">
          <h2 class="card-title text-center mb-4">Create New Account</h2>

          <form action="{{ route('register') }}" method="POST">
            @csrf

            <div class="mb-3">
              <label for="name" class="form-label">Username</label>
              <input type="text" name="name" class="form-control" required autofocus autocomplete="name" value="{{ old('name') }}">
              @error('name')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" name="email" class="form-control" required autocomplete="username" value="{{ old('email') }}">
              @error('email')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" name="password" class="form-control" required autocomplete="new-password">
              @error('password')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
              <label for="password_confirmation" class="form-label">Confirm Password</label>
              <input type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
              @error('password_confirmation')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
              <label for="role" class="form-label">Role</label>
              <select name="role" id="role" class="form-control" required>
                <option value="employer">Employer</option>
                <option value="seeker">Job Seeker</option>
                <!-- Admin option removed for security -->
              </select>
              @error('role')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3" id="employerTypeContainer" style="display: none;">
              <label for="employer_type" class="form-label">Employer Type</label>
              <select name="employer_type" id="employer_type" class="form-control">
                <option value="">Select Type</option>
                <option value="formal">Formal Employer (Registered Business)</option>
                <option value="informal">Informal Employer (Household/Individual)</option>
              </select>
              <small class="form-text text-muted">
                <strong>Formal:</strong> Companies, businesses with DTI/SEC registration<br>
                <strong>Informal:</strong> Households, individuals needing domestic help (maids, drivers, caregivers)
              </small>
              @error('employer_type')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3" id="jobSeekerTypeContainer" style="display: none;">
              <label for="job_seeker_type" class="form-label">Job Seeker Type</label>
              <select name="job_seeker_type" id="job_seeker_type" class="form-control">
                <option value="">Select Type</option>
                <option value="formal">Formal</option>
                <option value="informal">Informal</option>
              </select>
              @error('job_seeker_type')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-primary">Create Account</button>
            </div>

            <div class="text-center mt-3">
              <a href="{{ route('login') }}" class="btn btn-link">Already have an account?</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // Show/hide type dropdowns based on role selection
  document.getElementById('role').addEventListener('change', function() {
    var jobSeekerTypeContainer = document.getElementById('jobSeekerTypeContainer');
    var employerTypeContainer = document.getElementById('employerTypeContainer');
    
    if (this.value === 'seeker') {
      jobSeekerTypeContainer.style.display = 'block';
      employerTypeContainer.style.display = 'none';
      document.getElementById('job_seeker_type').setAttribute('required', 'required');
      document.getElementById('employer_type').removeAttribute('required');
    } else if (this.value === 'employer') {
      jobSeekerTypeContainer.style.display = 'none';
      employerTypeContainer.style.display = 'block';
      document.getElementById('job_seeker_type').removeAttribute('required');
      document.getElementById('employer_type').setAttribute('required', 'required');
    } else {
      jobSeekerTypeContainer.style.display = 'none';
      employerTypeContainer.style.display = 'none';
      document.getElementById('job_seeker_type').removeAttribute('required');
      document.getElementById('employer_type').removeAttribute('required');
    }
  });
</script>

</body>
</html>

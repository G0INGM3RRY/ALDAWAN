@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-cog me-2"></i>Account Settings</h4>
                </div>
                <div class="card-body">
                    <!-- Profile Information Form -->
                    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                        @csrf
                    </form>

                    <form method="post" action="{{ route('profile.update') }}" class="mb-4">
                        @csrf
                        @method('patch')

                        <h5 class="border-bottom pb-2 mb-3">
                            <i class="fas fa-user me-2 text-primary"></i>Profile Information
                        </h5>
                        <p class="text-muted mb-3">Update your account's profile information and email address.</p>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">{{ __('Name') }}</label>
                                <input id="name" name="name" type="text" class="form-control" 
                                       value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                                @error('name')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">{{ __('Email') }}</label>
                                <input id="email" name="email" type="email" class="form-control" 
                                       value="{{ old('email', $user->email) }}" required autocomplete="username">
                                @error('email')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror

                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                    <div class="mt-2">
                                        <p class="text-warning">
                                            {{ __('Your email address is unverified.') }}
                                            <button form="send-verification" class="btn btn-link p-0 text-decoration-underline">
                                                {{ __('Click here to re-send the verification email.') }}
                                            </button>
                                        </p>

                                        @if (session('status') === 'verification-link-sent')
                                            <p class="text-success">
                                                {{ __('A new verification link has been sent to your email address.') }}
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>{{ __('Save') }}
                            </button>

                            @if (session('status') === 'profile-updated')
                                <div class="text-success" id="profile-updated-message">
                                    <i class="fas fa-check me-1"></i>{{ __('Saved.') }}
                                </div>
                            @endif
                        </div>
                    </form>

                    <!-- Update Password Form -->
                    <form method="post" action="{{ route('password.update') }}" class="mb-4">
                        @csrf
                        @method('put')

                        <h5 class="border-bottom pb-2 mb-3">
                            <i class="fas fa-lock me-2 text-primary"></i>Update Password
                        </h5>
                        <p class="text-muted mb-3">Ensure your account is using a long, random password to stay secure.</p>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="update_password_current_password" class="form-label">{{ __('Current Password') }}</label>
                                <input id="update_password_current_password" name="current_password" type="password" 
                                       class="form-control" autocomplete="current-password">
                                @error('current_password', 'updatePassword')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="update_password_password" class="form-label">{{ __('New Password') }}</label>
                                <input id="update_password_password" name="password" type="password" 
                                       class="form-control" autocomplete="new-password">
                                @error('password', 'updatePassword')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="update_password_password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                                <input id="update_password_password_confirmation" name="password_confirmation" type="password" 
                                       class="form-control" autocomplete="new-password">
                                @error('password_confirmation', 'updatePassword')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>{{ __('Save') }}
                            </button>

                            @if (session('status') === 'password-updated')
                                <div class="text-success" id="password-updated-message">
                                    <i class="fas fa-check me-1"></i>{{ __('Saved.') }}
                                </div>
                            @endif
                        </div>
                    </form>

                    <!-- Delete User Form -->
                    <div class="border-top pt-3">
                        <h5 class="pb-2 mb-3">
                            <i class="fas fa-exclamation-triangle me-2 text-danger"></i>Delete Account
                        </h5>
                        <p class="text-muted mb-3">
                            Once your account is deleted, all of its resources and data will be permanently deleted. 
                            Before deleting your account, please download any data or information that you wish to retain.
                        </p>

                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-user-deletion">
                            <i class="fas fa-trash me-1"></i>{{ __('Delete Account') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Confirmation Modal -->
<div class="modal fade" id="confirm-user-deletion" tabindex="-1" aria-labelledby="confirm-user-deletion-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="confirm-user-deletion-label">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ __('Are you sure you want to delete your account?') }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <p class="text-muted">
                        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                    </p>

                    <div class="mb-3">
                        <label for="password" class="form-label visually-hidden">{{ __('Password') }}</label>
                        <input id="password" name="password" type="password" class="form-control" 
                               placeholder="{{ __('Password') }}">
                        @error('password', 'userDeletion')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>{{ __('Delete Account') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Auto-hide success messages after 3 seconds
document.addEventListener('DOMContentLoaded', function() {
    const profileMessage = document.getElementById('profile-updated-message');
    const passwordMessage = document.getElementById('password-updated-message');
    
    if (profileMessage) {
        setTimeout(() => {
            profileMessage.style.display = 'none';
        }, 3000);
    }
    
    if (passwordMessage) {
        setTimeout(() => {
            passwordMessage.style.display = 'none';
        }, 3000);
    }
});
</script>
@endsection
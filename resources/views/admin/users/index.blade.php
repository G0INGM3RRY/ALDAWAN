@extends('layouts.admin')
@section('title', 'User Management')
@section('page-title', 'User Management')

@section('content')
<div class="mb-3 d-flex justify-content-between align-items-center">
    <div>
        @if(request('archived') == 'true')
            <a href="{{ route('admin.users.index') }}" class="btn btn-primary">
                <i class="bi bi-people"></i> View Active Users
            </a>
        @else
            <a href="{{ route('admin.users.index', ['archived' => 'true']) }}" class="btn btn-secondary">
                <i class="bi bi-archive"></i> View Archived Users
            </a>
        @endif
    </div>
    <div>
        @if(request('archived') == 'true')
            <span class="badge bg-secondary fs-6">Showing Archived Users</span>
        @else
            <span class="badge bg-success fs-6">Showing Active Users</span>
        @endif
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Verified</th>
                @if(request('archived') == 'true')
                    <th>Archived At</th>
                @endif
                <th>Actions</th>
            </tr>  
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ ucfirst($user->role) }}</td>
                <td>
                    @if($user->verification_status === 'verified')
                        <span class="badge bg-success">Yes</span>
                    @else
                        <span class="badge bg-secondary">No</span>
                    @endif
                </td>
                @if(request('archived') == 'true')
                    <td>{{ $user->deleted_at ? $user->deleted_at->format('M d, Y') : '-' }}</td>
                @endif
                <td>
                    @if(request('archived') == 'true')
                        <form action="{{ route('admin.users.restore', $user->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Restore this user?')">
                                <i class="bi bi-arrow-counterclockwise"></i> Restore
                            </button>
                        </form>
                    @else
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Archive this user? Their data will be preserved.')">
                                <i class="bi bi-archive"></i> Archive
                            </button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach

        @endsection
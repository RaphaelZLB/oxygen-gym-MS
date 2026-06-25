@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <div class="d-flex align-items-center justify-content-between app-page-header">
        <h1 class="h3 app-page-title">Users</h1>
        <a class="btn btn-primary" href="{{ route('users.create') }}">Create</a>
    </div>

    <form class="row g-2 mb-3" method="GET" action="{{ route('users.index') }}">
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="q" value="{{ $q }}" placeholder="Search by name or email">
        </div>
        <div class="col-12 col-md-auto">
            <button class="btn btn-outline-secondary" type="submit">Search</button>
            <a class="btn btn-link" href="{{ route('users.index') }}">Reset</a>
        </div>
    </form>

    <div class="card app-card app-card-table">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @foreach($user->roles as $role)
                                <span class="badge bg-secondary">{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('users.edit', $user) }}">Edit</a>
                            @if(auth()->id() !== $user->id)
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted p-3">No users found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $users->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
@endsection

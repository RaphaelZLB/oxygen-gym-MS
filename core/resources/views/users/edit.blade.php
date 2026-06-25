@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    <div class="app-page-header">
        <h1 class="h3 app-page-title">Edit User</h1>
    </div>

    <div class="card app-card">
        <div class="card-body">
            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label">Name</label>
                        <input class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Password (leave blank to keep current)</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" minlength="8">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Role</label>
                        <select class="form-select @error('role') is-invalid @enderror" name="role" required>
                            @php $currentRole = $user->roles->first()?->name; @endphp
                            <option value="">Select a role...</option>
                            <option value="Admin" @selected(old('role', $currentRole) === 'Admin')>Admin</option>
                            <option value="Receptionist" @selected(old('role', $currentRole) === 'Receptionist')>Receptionist</option>
                            <option value="Trainer" @selected(old('role', $currentRole) === 'Trainer')>Trainer</option>
                            <option value="Member" @selected(old('role', $currentRole) === 'Member')>Member</option>
                        </select>
                        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mt-3 d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Update</button>
                    <a class="btn btn-outline-secondary" href="{{ route('users.index') }}">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
